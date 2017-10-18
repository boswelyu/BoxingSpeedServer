<?php
/**
 * database table player_friend description
 *
 * [This file was auto-generated. PLEASE DO NOT EDIT]
 *
 * @author Boswell Yu
 *
 */

require_once($GLOBALS['SERVER_ROOT'] . "utility/SQLUtil.php");

class TbPlayerFriend {

    const TABLE_NAME = 'player_friend';
    public static $table_index_info = array(
			'PRIMARY'=>array('unique'=>true, 'fields'=>array('list_idx'=>0))
	);
    public static $all_table_field_names = array(
		'list_idx',
		'user_id1',
		'user_id2',
		'create_time'
	);

	private /*string*/ $list_idx; //PRIMARY KEY 
	private /*int*/ $user_id1;
	private /*int*/ $user_id2;
	private /*string*/ $create_time;


    private $is_this_table_dirty = false;
	private $is_list_idx_dirty = false;
	private $is_user_id1_dirty = false;
	private $is_user_id2_dirty = false;
	private $is_create_time_dirty = false;


    public function TbPlayerFriend()
    {
    }


    /**
     * @example loadTable(array('list_idx'),array("list_idx"=>"123"))
     * @param array($condition)
     * @return array(TbPlayerFriend)
    */
    public static function /*array(TbPlayerFriend)*/loadTable(/*array*/ $fields = NULL, /*array*/$condition = NULL)
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
            $tb = new TbPlayerFriend();
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
            			$result[0]="INSERT INTO `" . self::TABLE_NAME . "` (`list_idx`,`user_id1`,`user_id2`,`create_time`) VALUES ";
			$result[1] = array('list_idx'=>1,'user_id1'=>1,'user_id2'=>1,'create_time'=>1);
        }
        return $result;
    }

    public function /*boolean*/ load(/*array*/$fields = NULL, /*array*/$condition = NULL)
    {
        //ERROR:no condition
        if (empty($condition) )
        {
            if(isset($this->list_idx))
                    $condition = array('list_idx'=>$this->list_idx);
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
        		if (isset($ar['list_idx'])) $this->list_idx = $ar['list_idx'];
		if (isset($ar['user_id1'])) $this->user_id1 = intval($ar['user_id1']);
		if (isset($ar['user_id2'])) $this->user_id2 = intval($ar['user_id2']);
		if (isset($ar['create_time'])) $this->create_time = $ar['create_time'];


        $this->clean();

        return true;
    }

    public function /*boolean*/ loadFromExistFields()
    {
        $emptyCondition = true;
        $emptyFields = true;

        $fields = array();
        $condition = array();

            	if (!isset($this->list_idx)){
    		$emptyFields = false;
    		$fields[] = 'list_idx';
    	}else{
    		$emptyCondition = false; 
    		$condition['list_idx'] = $this->list_idx;
    	}
    	if (!isset($this->user_id1)){
    		$emptyFields = false;
    		$fields[] = 'user_id1';
    	}else{
    		$emptyCondition = false; 
    		$condition['user_id1'] = $this->user_id1;
    	}
    	if (!isset($this->user_id2)){
    		$emptyFields = false;
    		$fields[] = 'user_id2';
    	}else{
    		$emptyCondition = false; 
    		$condition['user_id2'] = $this->user_id2;
    	}
    	if (!isset($this->create_time)){
    		$emptyFields = false;
    		$fields[] = 'create_time';
    	}else{
    		$emptyCondition = false; 
    		$condition['create_time'] = $this->create_time;
    	}


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

        if (empty($this->list_idx))
        {
            $this->list_idx = SQLUtil::GetInsertId();
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

        if (empty($this->list_idx))
        {
            $this->list_idx = SQLUtil::GetInsertId();
        }

        $this->clean();

        return true;
    }

    public function /*boolean*/ save(/*array*/$condition = NULL)
    {
        if (empty($condition) )
        {
            if(isset($this->list_idx))
                $condition = array('list_idx'=>$this->list_idx);
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
        if (!isset($this->list_idx))
        {
            return false;
        }

        $sql = "DELETE FROM `" . self::TABLE_NAME . "` WHERE `list_idx`='{$this->list_idx}'";
    
        $qr =SQLUtil::RunQuery($sql);

        return (boolean)$qr;
    }

	public function getInsertValue($fields)
	{
		$values = "(";		
		foreach($fields as $f => $k){
			 	
		if($f == 'list_idx'){
			$values .= "'" . addslashes($this->list_idx) . "',";
		}
			 			else if($f == 'user_id1'){
			$values .= "'" . ($this->user_id1) . "',";
		}
					else if($f == 'user_id2'){
			$values .= "'" . ($this->user_id2) . "',";
		}
					else if($f == 'create_time'){
			$values .= "'" . addslashes($this->create_time) . "',";
		}
					
		}
		$values .= ")";
		
		return str_replace(",)",")",$values);		
	}
	
	private function /*string*/ getInsertSQL()
	{
		if (!$this->is_this_table_dirty)
		{
			return null;
		}		
		
		$fields = "(";
		$values = " VALUES(";

			
		if (isset($this->list_idx))
		{
			$fields .= "`list_idx`,";
			$values .= "'" . addslashes($this->list_idx) . "',";
		}
						
		if (isset($this->user_id1))
		{
			$fields .= "`user_id1`,";
			$values .= "'" . ($this->user_id1) . "',";
		}
						
		if (isset($this->user_id2))
		{
			$fields .= "`user_id2`,";
			$values .= "'" . ($this->user_id2) . "',";
		}
						
		if (isset($this->create_time))
		{
			$fields .= "`create_time`,";
			$values .= "'" . addslashes($this->create_time) . "',";
		}
			
		
		$fields .= ")";
		$values .= ")";
		
		$sql = "INSERT INTO `" . self::TABLE_NAME . "` ".$fields.$values;
		
		return str_replace(",)",")",$sql);
	}
	
	private function /*string*/ getUpdateFields()
	{
		$update = "";
		
			
		if ($this->is_user_id1_dirty)
		{
			if (!isset($this->user_id1))
			{
				$update .= ("`user_id1`=null,");
			}
			else
			{
				$update .= ("`user_id1`='".($this->user_id1)."',");
			}
		}
						
		if ($this->is_user_id2_dirty)
		{
			if (!isset($this->user_id2))
			{
				$update .= ("`user_id2`=null,");
			}
			else
			{
				$update .= ("`user_id2`='".($this->user_id2)."',");
			}
		}
						
		if ($this->is_create_time_dirty)
		{
			if (!isset($this->create_time))
			{
				$update .= ("`create_time`=null,");
			}
			else
			{
				$update .= ("`create_time`='".addslashes($this->create_time)."',");
			}
		}
			
			
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
		$this->is_list_idx_dirty = false;
		$this->is_user_id1_dirty = false;
		$this->is_user_id2_dirty = false;
		$this->is_create_time_dirty = false;

	}
	
	public function /*string*/ getListIdx()
	{
		return $this->list_idx;
	}

	public function /*void*/ setListIdx(/*string*/ $list_idx)
	{
		$this->list_idx = ($list_idx);
		$this->is_list_idx_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setListIdxNull()
	{
		$this->list_idx = null;
		$this->is_list_idx_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*int*/ getUserId1()
	{
		return $this->user_id1;
	}

	public function /*void*/ setUserId1(/*int*/ $user_id1)
	{
		$this->user_id1 = intval($user_id1);
		$this->is_user_id1_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setUserId1Null()
	{
		$this->user_id1 = null;
		$this->is_user_id1_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*int*/ getUserId2()
	{
		return $this->user_id2;
	}

	public function /*void*/ setUserId2(/*int*/ $user_id2)
	{
		$this->user_id2 = intval($user_id2);
		$this->is_user_id2_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setUserId2Null()
	{
		$this->user_id2 = null;
		$this->is_user_id2_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getCreateTime()
	{
		return $this->create_time;
	}

	public function /*void*/ setCreateTime(/*string*/ $create_time)
	{
		$this->create_time = ($create_time);
		$this->is_create_time_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setCreateTimeNull()
	{
		$this->create_time = null;
		$this->is_create_time_dirty = true;
		$this->is_this_table_dirty = true;
	}
	
	public function /*string*/ toDebugString()
	{
		$dbg = "(";
		
		$dbg .= ("list_idx={$this->list_idx},");
		$dbg .= ("user_id1={$this->user_id1},");
		$dbg .= ("user_id2={$this->user_id2},");
		$dbg .= ("create_time={$this->create_time},");

		$dbg .= ")";
				
		return str_replace(",)",")",$dbg);
	}
	
}

?>
