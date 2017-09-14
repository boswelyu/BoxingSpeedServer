<?php

require_once("DbTable.php");
require_once("DbTypeMap.php");

class DbParser
{

    var $template;
    var $dbIndexTemplate;
    var $outputPath;

    var $check_index_code = "";

    /** @var  mysqli $mysqli */
    private $mysqli;

    public function DbParser($template_path, $target_path)
    {
        $this->template = file_get_contents($template_path . "DbTableTemplate.php");
        $this->dbIndexTemplate = file_get_contents($template_path . "CheckDBIndex.php");

        $this->outputPath = $target_path;
    }

    public function parse($targetTables)
    {
        if (!empty($targetTables)) {
            if (is_string($targetTables)) {
                try {
                    $this->parseTable($targetTables);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } else if (is_array($targetTables)) {
                foreach ($targetTables as $table) {
                    try {
                        $this->parseTable($table);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
            return;
        }

        $this->mysqli = new mysqli(GEN_DATABASE_HOST, GEN_DATABASE_USER, GEN_DATABASE_PASSWORD, GEN_DATABASE_DB_NAME, GEN_DATABASE_PORT);
        if($this->mysqli->connect_error) {
            echo "Connect To DB Failed: " . $this->mysqli->connect_error . "\n";
            return;
        }else {
            echo "Connected To DB: " . GEN_DATABASE_HOST . ", Start Parse DB: " . GEN_DATABASE_DB_NAME . "\n";
        }

        $qr = $this->mysqli->query("SHOW TABLES");
        $fr = $qr->fetch_all();
        $tables = array();
        foreach($fr as $table) {
            $tables[] = $table[0];
        }

        foreach($tables as $table) {
            echo "Process Table: $table \n";
            try {
                $this->parseTable($table);
            } catch (Exception $e) {
                echo "Parse Table $table Failed with error: " . $e->getMessage() . "\n";
            }
        }

//        $checkCode = str_replace("/*::INDEX_CHECK_CODE::*/", $this->check_index_code, $this->dbIndexTemplate);
//        $path = $this->outputPath . "CheckDBIndex.php";
//
//        file_put_contents($path, $checkCode);

    }

    private function getComments($tableName)
    {
        $result = array();

        $qr = $this->mysqli->query("SELECT COLUMN_NAME as name,COLUMN_COMMENT as comment FROM INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='{$tableName}'");
        $cmr = $qr->fetch_array();
        if (empty($cmr)) {
            return $result;
        }
        foreach ($cmr as $c) {
            if(empty($c['comment'])) {
                continue;
            }
            $d = str_replace("\r", " ", $c['comment']);
            $d = str_replace("\n", " ", $d);
            $result[$c['name']] = $d;
        }

        return $result;
    }

    public function parseTable($tableName)
    {
        $qr = $this->mysqli->query("DESC " . $tableName);

        $resArray = array();
        while($res = $qr->fetch_array()) {
            $resArray[] = $res;
        }

        if (empty($resArray)) {
            echo "Error parse table " . $tableName . "\n";
            return;
        }

        $fields = array();
        $comments = $this->getComments($tableName);

        foreach($resArray as $tableInfo) {

            $tf = new TableField();
            $tf->name = $tableInfo['Field'];
            $tf->dirtyName = sprintf("is_%s_dirty", $tf->name);
            $tf->type = DBtypeMap::dbType2Php($tableInfo['Type']);
            $tf->default = $tableInfo['Default'];
            if (isset($tf->default) && DBtypeMap::isString($tf->type)) {
                $tf->default = "'{$tf->default}'";
            }

            if (!empty($comments)) {
                $tf->comment = $comments[$tf->name];
            }
            $tf->isKey = (strcasecmp($tableInfo['Key'], "PRI") == 0);
            $fields[] = $tf;
        }

        $indices = array();

        $qr = $this->mysqli->query("show index from " . $tableName);
        $indexInfo = $qr->fetch_array();
        if(count($indexInfo) <= 0) {
            echo "No index found from table $tableName \n";
        }else {

            $keyName = $indexInfo['Key_name'];
            $seq = intval($indexInfo['Seq_in_index']);
            if (isset($indices[$keyName])) {
                $index = $indices[$keyName];
                $index->fields[$seq - 1] = $indexInfo['Column_name'];
            } else {
                $index = new TableIndex();
                $index->name = $keyName;
                $index->fields[$seq - 1] = $indexInfo['Column_name'];
                $index->unique = intval($indexInfo['Non_unique']);

                $indices[$keyName] = $index;
            }


        }

        $tableObj = new Table($tableName, "Tb" . $this->toClassName($tableName), $fields, $indices);
        $tableObj->genDetails();

        $time = date("Y-m-d H:i:s");

        $tpl = $this->template;

        $tpl = str_replace("/*::DATE_TIME_CODE::*/", $time, $tpl);
        $tpl = str_replace("/#::TABLE_NAME::#/", $tableName, $tpl);
        $tpl = str_replace("/*::TABLE_CLASS_NAME::*/", $tableObj->name, $tpl);
        $tpl = str_replace("/#::KEY_NAME::#/", $tableObj->key_name, $tpl);
        $tpl = str_replace("/*::FIELD_DEFINE_CODE::*/", $tableObj->field_define_code, $tpl);
        $tpl = str_replace("/*::FIELD_DIRTY_DEFINE_CODE::*/", $tableObj->field_dirty_define_code, $tpl);
        $tpl = str_replace("/*::FIELD_GET_SET_CODE::*/", $tableObj->field_get_set_code, $tpl);
        $tpl = str_replace("/*::LOAD_TABLE_CODE::*/	", $tableObj->load_table_code, $tpl);
        $tpl = str_replace("/*::LOAD_CODE::*/", $tableObj->load_code, $tpl);
        $tpl = str_replace("/*::LOAD_FROM_EXIST_FIELDS_CODE::*/", $tableObj->load_from_exist_fields_code, $tpl);
        $tpl = str_replace("/*::INSERT_SQL_CODE::*/", $tableObj->insert_sql_code, $tpl);
        $tpl = str_replace("/*::UPDATE_SQL_CODE::*/", $tableObj->update_sql_code, $tpl);
        $tpl = str_replace("/*::CLEAN_CODE::*/", $tableObj->clean_code, $tpl);
        $tpl = str_replace("/*::TO_DEBUG_STRING_CODE::*/", $tableObj->to_debug_string_code, $tpl);
        $tpl = str_replace("/*::SQL_HEADER_CODE::*/", $tableObj->sql_header_code, $tpl);
        $tpl = str_replace("/*::INSERT_VALUE_CODE::*/", $tableObj->insert_value_code, $tpl);
        $tpl = str_replace("/*::CACHE_CMP_CONDITION_CODE::*/", $tableObj->cache_cmp_condition_code, $tpl);
        $tpl = str_replace("/*::COPY_CACHE_TABLE_CODE::*/", $tableObj->copy_cache_table_code, $tpl);
        $tpl = str_replace("/*::TABLE_INDEX_INFO::*/", $tableObj->table_index_info_code, $tpl);
        $tpl = str_replace("/*::TABLE_ALL_FIELD_NAMES::*/", $tableObj->table_all_field_names_code, $tpl);
        $tpl = str_replace("/*::HS_INSERT_PARAM_CODES::*/", $tableObj->hs_insert_param_code, $tpl);
        $tpl = str_replace("/*::HS_UPDATE_PARAM_CODES::*/", $tableObj->hs_update_param_code, $tpl);


        $tpl = str_replace(",)", ")", $tpl);

        $path = $this->outputPath . $tableObj->name . ".php";

        file_put_contents($path, $tpl);

        //	$this->check_index_code .= $tableObj->genCheckIndexCode();
    }

    public static function toClassName($name)
    {
        $cname = $name;
        $cname[0] = strtoupper($cname[0]);

        $n2 = "";
        $l = strlen($cname);
        $need_upper = false;
        for ($i = 0; $i < $l; $i++) {
            if (strncasecmp($cname[$i], '_', 1) != 0) {
                if (!$need_upper) {
                    $n2 .= $cname[$i];
                } else {
                    $n2 .= strtoupper($cname[$i]);
                    $need_upper = false;
                }
            } else {
                $need_upper = true;
            }
        }

        return $n2;
    }
}

?>