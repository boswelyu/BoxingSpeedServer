<?php

class TableField
{
    var $name;

    var $dirtyName;

    var $type;

    var $default;

    var $comment;

    var $isKey;
}


class TableIndex
{
    var $name='';
    var $fields=array();
    var $unique=0;
}

class Table
{
    var $tableName;
    var $name;
    var $fields;
    var $indices;

    var $keyfield;

    var $key_name;
    var $field_define_code;
    var $field_dirty_define_code;
    var $insert_sql_code;
    var $update_sql_code;
    var $clean_code;
    var $load_table_code;
    var $load_code;
    var $field_get_set_code;
    var $to_debug_string_code;
    var $load_from_exist_fields_code;
    var $sql_header_code;
    var $insert_value_code;
    var $cache_cmp_condition_code;
    var $copy_cache_table_code;

    var $table_index_info_code;/*::TABLE_INDEX_INFO::*/
    var $table_all_field_names_code;/*::TABLE_ALL_FIELD_NAMES::*/
    var $hs_insert_param_code; /*::HS_INSERT_PARAM_CODES::*/
    var $hs_update_param_code; /*::HS_UPDATE_PARAM_CODES::*/

    private $GET_SET_TEMPLATE = <<<TTTTPL
	public function /*[TNAME]*/ get[FNAME]()
	{
		return \$this->[NAME];
	}

	public function /*void*/ set[FNAME](/*[TNAME]*/ $[NAME])
	{
		\$this->[NAME] = [TMOTH]($[NAME]);
		\$this->[DNAME] = true;
		\$this->is_this_table_dirty = true;
	}


	public function /*void*/ set[FNAME]Null()
	{
		\$this->[NAME] = null;
		\$this->[DNAME] = true;
		\$this->is_this_table_dirty = true;
	}

TTTTPL;

    public function Table($tn,$n,$f,$indices)
    {
        $this->tableName = $tn;
        $this->name = $n;
        $this->fields = $f;
        $this->indices = $indices;
    }

    public function genDetails()
    {
        try {
            $this->key_name = $this->getKeyName();
            $this->field_define_code = $this->genFieldDefineCode();
            $this->field_dirty_define_code = $this->genFieldDirtyDefineCode();
            $this->field_get_set_code = $this->genFieldGetSetCode();
            $this->load_table_code = $this->genLoadTableCode();
            $this->load_code = $this->genLoadCode();
            $this->load_from_exist_fields_code = $this->genLoadFromExistFieldsCode();
            $this->insert_sql_code = $this->genInsertSqlCode();
            $this->update_sql_code = $this->genUpdateSqlCode();
            $this->clean_code = $this->genCleanCode();
            $this->to_debug_string_code = $this->genDebugStringCode();
            $this->sql_header_code = $this->genSqlHeaderCode();
            $this->insert_value_code = $this->genInsertValueCode();
            $this->cache_cmp_condition_code = $this->genCacheCmpConditionCode();
            $this->copy_cache_table_code = $this->genCopyCacheTableCode();
            $this->table_index_info_code = $this->genTbIndexInfoCode();/*::TABLE_INDEX_INFO::*/
            $this->table_all_field_names_code = $this->genTbAllFieldNameCode();/*::TABLE_ALL_FIELD_NAMES::*/
            $this->hs_insert_param_code = $this->genHsInsertParamsCode(); /*::HS_INSERT_PARAM_CODES::*/
            $this->hs_update_param_code = $this->genHsUpdateParamsCode(); /*::HS_UPDATE_PARAM_CODES::*/
        }catch(Exception $e) {
            $msg = $e->getMessage();
            echo "GenDetail Failed: $msg \n";
        }

    }

    private function getKeyName()
    {
        $key = null;
        foreach($this->fields as $field)
        {
            if (empty($key))
            {
                if ($field->isKey)
                {
                    $key = $field->name;
                    $this->keyfield = $field;
                }
            }
            else
            {
                if ($field->isKey)
                {
                    throw new Exception($this->name." parse table error multi keys ".$key." ".$field->name."\n",-1);
                }
            }
        }

        if (empty($key))
        {
            throw new Exception($this->name." Parse table error: no key found \n",-2);
        }

        return $key;
    }

    private function genFieldDefineCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            if ($field->isKey)
            {
                $s .= sprintf("	private /*%s*/ $%s; //PRIMARY KEY %s\r\n",$field->type,$field->name,$field->comment);
            }
            else
            {
                if (empty($field->comment)){
                    $s .= sprintf("	private /*%s*/ $%s;\r\n",$field->type,$field->name);
                }else{
                    $s .= sprintf("	private /*%s*/ $%s; //%s\r\n",$field->type,$field->name,$field->comment);
                }
            }
        }

        return $s;
    }

    private function genFieldDirtyDefineCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $s .= sprintf("	private $%s = false;\r\n",$field->dirtyName);
        }

        return $s;
    }

    private function genFieldGetSetCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $template = $this->GET_SET_TEMPLATE;
            $fName = DbParser::toClassName($field->name);

            $template = str_replace("[FNAME]",$fName,$template);
            $template = str_replace("[TNAME]",$field->type,$template);
            $template = str_replace("[NAME]",$field->name,$template);
            $template = str_replace("[DNAME]",$field->dirtyName,$template);
            $template = str_replace("[TMOTH]",DBtypeMap::typeVal($field->type),$template);

            $s .= $template;
        }

        return $s;
    }

    private function genInsertSqlCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $cvfuncName='';
            if(DBtypeMap::typeVal($field->type)=='')
            {
                $cvfuncName='addslashes';
            }

            $s .=
                <<<INSERCODE
			
		if (isset(\$this->{$field->name}))
		{
			\$fields .= "`{$field->name}`,";
			\$values .= "'" . $cvfuncName(\$this->{$field->name}) . "',";
		}
			
INSERCODE;
        }

        return $s;
    }

    private function genUpdateSqlCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            if (! $field->isKey)
            {
                $cvfuncName='';
                if(DBtypeMap::typeVal($field->type)=='')
                {
                    $cvfuncName='addslashes';
                }

                $s .=
                    <<<INSERCODE
			
		if (\$this->{$field->dirtyName})
		{
			if (!isset(\$this->{$field->name}))
			{
				\$update .= ("`{$field->name}`=null,");
			}
			else
			{
				\$update .= ("`{$field->name}`='".$cvfuncName(\$this->{$field->name})."',");
			}
		}
			
INSERCODE;

            }
        }

        return $s;
    }

    private function genCleanCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $s .= sprintf("		\$this->%s = false;\r\n",$field->dirtyName);
        }

        return $s;
    }

    private function genLoadCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $type = DBtypeMap::noStringTypeVal($field->type);
            if(empty($type)){
                $s .= "		if (isset(\$ar['{$field->name}'])) \$this->{$field->name} = \$ar['{$field->name}'];\r\n";
            }else{
                $s .= "		if (isset(\$ar['{$field->name}'])) \$this->{$field->name} = {$type}(\$ar['{$field->name}']);\r\n";
            }
        }


        return str_replace(",)","",$s);
    }

    private function genDebugStringCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $s .= "		\$dbg .= (\"{$field->name}={\$this->{$field->name}},\");\r\n";
        }

        return $s;
    }

    private function genLoadFromExistFieldsCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $s .= "    	if (!isset(\$this->{$field->name})){\r\n    		\$emptyFields = false;\r\n    		\$fields[] = '{$field->name}';\r\n    	}else{\r\n    		\$emptyCondition = false; \r\n    		\$condition['{$field->name}'] = \$this->$field->name;\r\n    	}\r\n";
        }

        return $s;
    }


    private function genLoadTableCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            $type = DBtypeMap::noStringTypeVal($field->type);
            if(empty($type)){
                $s .= "			if (isset(\$row['{$field->name}'])) \$tb->{$field->name} = \$row['{$field->name}'];\r\n";
            }else{
                $s .= "			if (isset(\$row['{$field->name}'])) \$tb->{$field->name} = {$type}(\$row['{$field->name}']);\r\n";
            }
        }

        return $s;
    }

    private function genSqlHeaderCode()
    {
        /**
        $result[0] = "INSERT INTO `player_tech` (`user_id`,`type`,`level`,`grow`,`percent`) VALUES ";
        $result[1] = array('user_id','type','level','grow','percent');
         */
        $s = "			\$result[0]=\"INSERT INTO `\" . self::TABLE_NAME . \"` (";
        $s2 = "			\$result[1] = array(";

        foreach($this->fields as $field)
        {
            $s .= "`{$field->name}`,";
            $s2 .= "'{$field->name}'=>1,";
        }

        $s .= ") VALUES \";\r\n";
        $s2 .= ");";

        $s .= $s2;

        return str_replace(",)",")",$s);
    }

    private function genInsertValueCode()
    {
        $s = "";
        $first = true;
        foreach($this->fields as $field)
        {

            $cvfuncName='';
            if(DBtypeMap::typeVal($field->type)=='')
            {
                $cvfuncName='addslashes';
            }


            if($first){
                $s.=<<<INSERTVALCOE
			 	
		if(\$f == '{$field->name}'){
			\$values .= "'" . $cvfuncName(\$this->{$field->name}) . "',";
		}
			 	
INSERTVALCOE;

            }else{
                $s .=<<<EEEE
		else if(\$f == '{$field->name}'){
			\$values .= "'" . $cvfuncName(\$this->{$field->name}) . "',";
		}
			
EEEE;

            }
            $first = false;
        }

        return $s;
    }

    private function genCacheCmpConditionCode()
    {
        //
        $s = "";
        foreach($this->fields as $field)
        {
            $tv = DBtypeMap::rawStringTypeVal($field->type);
            $s .= "			if(isset(\$condition['{$field->name}']) && {$tv}(\$condition['{$field->name}']) != \$record->{$field->name}) continue;\r\n";
        }
        return $s;
    }

    private function genCopyCacheTableCode()
    {
        $s = "";
        foreach ($this->fields as $field){
            $s .= "			\$this->{$field->name} = \$cacheTb->{$field->name};\r\n";
        }

        return $s;
    }


    private function genTbIndexInfoCode()
    {
        $trx = array("true","false");
        $s = "array(\n";
        $isFirst=true;
        foreach ($this->indices as $k=>$v){

            if(!$isFirst)
                $s.="			,";
            else
                $s.="			";

            $s .="'$k'=>array('unique'=>".$trx[$v->unique].", 'fields'=>array(";

            for($i=0;$i<count($v->fields);$i++)
            {
                $vn = $v->fields[$i];
                if($i!=0)
                    $s .= ",";
                $s .= "'$vn'=>$i";
            }

            $s.="))\n";
            $isFirst = false;
        }
        $s .="	)";

        return $s;
    }

    private function genTbAllFieldNameCode()
    {
        $s = "array(\n";
        $filedCount = count($this->fields);
        for($i=0; $i < $filedCount; $i++)
        {
            $f = $this->fields[$i];
            if($i == $filedCount - 1)
                $s .= "\t\t'{$f->name}'\n";
            else
                $s .= "\t\t'{$f->name}',\n";
        }
        $s .= "\t)";
        return $s;
    }

    private function genHsUpdateParamsCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {
            if (! $field->isKey)
            {
                $s.=<<<EEE

           	if (\$this->{$field->dirtyName})
			{
				\$fields[]='{$field->name}';
				if (!isset(\$this->{$field->name}))
				{
					\$values[]='';
				}
				else
				{
					\$values[]= \$this->{$field->name};
				}
			}

			
EEE;

            }
        }

        return $s;
    }

    private function genHsInsertParamsCode()
    {
        $s = '';

        foreach($this->fields as $field)
        {

            $s.=<<<EEE

				if (isset(\$this->{$field->name})){
					\$fields [] = '{$field->name}';
					\$values [] = \$this->{$field->name};
				}

EEE;

        }

        return $s;
    }

    public function genCheckIndexCode()
    {
        $s=<<<EEE
		 require_once('{$this->name}.php');
		 \$ret=indexCheck({$this->name}::\$_table_index_info,{$this->name}::_original_table_name);
		 if(\$retval==true && \$ret==false) \$retval=false;
		 \$ret=checkTableFields({$this->name}::\$_all_tabl_field_names,{$this->name}::_original_table_name);
		  if(\$retval==true && \$ret==false) \$retval=false;
		 /*-----*/

EEE;
        return $s;
    }
}

?>