<?php
/**
 * database table /#::TABLE_NAME::#/ description
 *
 * [This file was auto-generated. PLEASE DO NOT EDIT]
 *
 * @author Boswell Yu
 *
 */

require_once($GLOBALS['SERVER_ROOT'] . "utility/SQLUtil.php");

class /*::TABLE_CLASS_NAME::*/ {

    const TABLE_NAME = '/#::TABLE_NAME::#/';
    public static $table_index_info = /*::TABLE_INDEX_INFO::*/;
    public static $all_table_field_names = /*::TABLE_ALL_FIELD_NAMES::*/;

/*::FIELD_DEFINE_CODE::*/

    private $is_this_table_dirty = false;
/*::FIELD_DIRTY_DEFINE_CODE::*/

    public function /*::TABLE_CLASS_NAME::*/()
    {
    }


    /**
     * @example loadTable(array('/#::KEY_NAME::#/'),array("/#::KEY_NAME::#/"=>"123"))
     * @param array($condition)
     * @return array(/*::TABLE_CLASS_NAME::*/)
    */
    public static function /*array(/*::TABLE_CLASS_NAME::*/)*/loadTable(/*array*/ $fields = NULL, /*array*/$condition = NULL)
	{
		if(empty($fields))
		{
			$fields = self::$all_table_field_names;
		}

        $f = SQLUtil::parseFields($fields);

        if (empty($condition))
        {
            $sql = "SELECT {$f} FROM `". self::TABLE_NAME ."`";
        }
        else
        {
            $sql = "SELECT {$f} FROM `". self::TABLE_NAME ."` WHERE ".SQLUtil::parseCondition($condition);
        }

        $result = array();
        $resultSet = SQLUtil::RunQuery($sql);

        if (empty($resultSet) || $resultSet->num_rows == 0)
        {
            return $result;
        }

        while($row = $resultSet->fetch_array())
        {
            $tb = new /*::TABLE_CLASS_NAME::*/();
            /*::LOAD_TABLE_CODE::*/
            $result[] = $tb;
        }

        return $result;
    }

    public static function insertSqlHeader(/*array*/$fields = NULL)
    {
        $result = array();
        if(!empty($fields)){
            $f = SQLUtil::parseFields($fields);
            $result[0] = "INSERT INTO `" . self::TABLE_NAME . "` ({$f}) VALUES ";
            $ar = array();
            foreach($fields as $key){
                $ar[$key] = 1;
            }
            $result[1] = $ar;
        }else{
            /*::SQL_HEADER_CODE::*/
        }
        return $result;
    }

    public function /*boolean*/ load(/*array*/$fields = NULL, /*array*/$condition = NULL)
    {
        //ERROR:no condition
        if (empty($condition) )
        {
            if(isset($this->/#::KEY_NAME::#/))
                    $condition = array('/#::KEY_NAME::#/'=>$this->/#::KEY_NAME::#/);
                else
                    return false;
        }

        if(empty($fields))
        {
            $fields = self::$all_table_field_names;
        }

        $f = SQLUtil::parseFields($fields);

        $c =SQLUtil::parseCondition($condition);

        $sql = "SELECT {$f} FROM `" . self::TABLE_NAME . "` WHERE {$c}";

        $resultSet =SQLUtil::RunQuery($sql);

        if (!isset($resultSet) || $resultSet->num_rows == 0)
        {
            return false;
        }

        $ar = $resultSet->fetch_array();
        /*::LOAD_CODE::*/

        $this->clean();

        return true;
    }

    public function /*boolean*/ loadFromExistFields()
    {
        $emptyCondition = true;
        $emptyFields = true;

        $fields = array();
        $condition = array();

        /*::LOAD_FROM_EXIST_FIELDS_CODE::*/

        if ($emptyFields)
        {
            unset($fields);
        }

        if ($emptyCondition)
        {
            unset($condition);
        }

        return $this->load($fields, $condition);
    }

    public function /*boolean*/ insertOrUpdate()
    {
        $sql = $this->getInsertSQL();
        if (empty($sql))
        {
            return false;
        }
        $sql .= " ON DUPLICATE KEY UPDATE ";
        $sql .= $this->getUpdateFields();

        $resultSet = SQLUtil::RunQuery($sql,true,false);
        if (!$resultSet)
        {
            return false;
        }

        if (empty($this->/#::KEY_NAME::#/))
        {
            $this->/#::KEY_NAME::#/ = SQLUtil::GetInsertId();
        }
        $this->clean();

        return true;
    }

    public function /*boolean*/ insert()
    {
        $sql = $this->getInsertSQL();
        if (empty($sql))
        {
            return false;
        }

        $resultSet = SQLUtil::RunQuery($sql);
        var_dump($resultSet);

        if (!$resultSet)
        {
            return false;
        }

        if (empty($this->/#::KEY_NAME::#/))
        {
            $this->/#::KEY_NAME::#/ = SQLUtil::GetInsertId();
        }

        $this->clean();

        return true;
    }

    public function /*boolean*/ save(/*array*/$condition = NULL)
    {
        if (empty($condition) )
        {
            if(isset($this->/#::KEY_NAME::#/))
                $condition = array('/#::KEY_NAME::#/'=>$this->/#::KEY_NAME::#/);
            else
                return false;
        }

        $condStr = SQLUtil::parseCondition($condition);
        $sql = $this->getUpdateSQL($condStr);
        if(empty($sql)){
            // No Field needs to be updated
            return true;
        }

        $qr = SQLUtil::RunQuery($sql);
        $this->clean();

        return (boolean)$qr;
    }

    public function /*boolean*/ delete()
    {
        if (!isset($this->/#::KEY_NAME::#/))
        {
            return false;
        }

        $sql = "DELETE FROM `" . self::TABLE_NAME . "` WHERE `/#::KEY_NAME::#/`='{$this->/#::KEY_NAME::#/}'";
    
        $qr =SQLUtil::RunQuery($sql);

        return (boolean)$qr;
    }

	public function getInsertValue($fields)
	{
		$values = "(";		
		foreach($fields as $f => $k){
/*::INSERT_VALUE_CODE::*/		
		}
		$values .= ")";
		
		return str_replace(",,)",")",$values);		
	}
	
	private function /*string*/ getInsertSQL()
	{
		if (!$this->is_this_table_dirty)
		{
			return null;
		}		
		
		$fields = "(";
		$values = " VALUES(";

/*::INSERT_SQL_CODE::*/
		
		$fields .= ")";
		$values .= ")";
		
		$sql = "INSERT INTO `" . self::TABLE_NAME . "` ".$fields.$values;
		
		return str_replace(",,)",")",$sql);
	}
	
	private function /*string*/ getUpdateFields()
	{
		$update = "";
		
/*::UPDATE_SQL_CODE::*/
			
		if (empty($update) || strlen($update) < 1)
		{
			return;
		}
		
		$i = strrpos($update,",");
		if (!is_bool($i))
		{
			$update = substr($update,0,$i);
		}		
		
		return $update;
	}
	
	private function /*string*/ getUpdateSQL($condition)
	{
		if (!$this->is_this_table_dirty)
		{
			return null;
		}
		
		$update = $this->getUpdateFields();
		
		if (empty($update))
		{
			return;
		}
		
		$sql = "UPDATE `" . self::TABLE_NAME . "` SET {$update} WHERE {$condition}";
		
		return $sql;
	}
		
	private function /*void*/ clean() 
	{
		$this->is_this_table_dirty = false;
/*::CLEAN_CODE::*/
	}
	
/*::FIELD_GET_SET_CODE::*/	
	public function /*string*/ toDebugString()
	{
		$dbg = "(";
		
/*::TO_DEBUG_STRING_CODE::*/
		$dbg .= ")";
				
		return str_replace(",,)",")",$dbg);
	}
	
}

?>
