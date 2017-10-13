<?php
/**
 * database table player description
 *
 * [This file was auto-generated. PLEASE DO NOT EDIT]
 *
 * @author Boswell Yu
 *
 */

require_once($GLOBALS['SERVER_ROOT'] . "utility/SQLUtil.php");

class TbPlayer {

    const TABLE_NAME = 'player';
    public static $table_index_info = array(
			'PRIMARY'=>array('unique'=>true, 'fields'=>array('user_id'=>0))
	);
    public static $all_table_field_names = array(
		'user_id',
		'user_name',
		'nickname',
		'password',
		'reg_time',
		'avatar_url'
	);

	private /*int*/ $user_id; //PRIMARY KEY 
	private /*string*/ $user_name;
	private /*string*/ $nickname;
	private /*string*/ $password;
	private /*string*/ $reg_time;
	private /*string*/ $avatar_url;


    private $is_this_table_dirty = false;
	private $is_user_id_dirty = false;
	private $is_user_name_dirty = false;
	private $is_nickname_dirty = false;
	private $is_password_dirty = false;
	private $is_reg_time_dirty = false;
	private $is_avatar_url_dirty = false;


    public function TbPlayer()
    {
    }


    /**
     * @example loadTable(array('user_id'),array("user_id"=>"123"))
     * @param array($condition)
     * @return array(TbPlayer)
    */
    public static function /*array(TbPlayer)*/loadTable(/*array*/ $fields = NULL, /*array*/$condition = NULL)
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
            $tb = new TbPlayer();
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
            			$result[0]="INSERT INTO `" . self::TABLE_NAME . "` (`user_id`,`user_name`,`nickname`,`password`,`reg_time`,`avatar_url`) VALUES ";
			$result[1] = array('user_id'=>1,'user_name'=>1,'nickname'=>1,'password'=>1,'reg_time'=>1,'avatar_url'=>1);
        }
        return $result;
    }

    public function /*boolean*/ load(/*array*/$fields = NULL, /*array*/$condition = NULL)
    {
        //ERROR:no condition
        if (empty($condition) )
        {
            if(isset($this->user_id))
                    $condition = array('user_id'=>$this->user_id);
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
        		if (isset($ar['user_id'])) $this->user_id = intval($ar['user_id']);
		if (isset($ar['user_name'])) $this->user_name = $ar['user_name'];
		if (isset($ar['nickname'])) $this->nickname = $ar['nickname'];
		if (isset($ar['password'])) $this->password = $ar['password'];
		if (isset($ar['reg_time'])) $this->reg_time = $ar['reg_time'];
		if (isset($ar['avatar_url'])) $this->avatar_url = $ar['avatar_url'];


        $this->clean();

        return true;
    }

    public function /*boolean*/ loadFromExistFields()
    {
        $emptyCondition = true;
        $emptyFields = true;

        $fields = array();
        $condition = array();

            	if (!isset($this->user_id)){
    		$emptyFields = false;
    		$fields[] = 'user_id';
    	}else{
    		$emptyCondition = false; 
    		$condition['user_id'] = $this->user_id;
    	}
    	if (!isset($this->user_name)){
    		$emptyFields = false;
    		$fields[] = 'user_name';
    	}else{
    		$emptyCondition = false; 
    		$condition['user_name'] = $this->user_name;
    	}
    	if (!isset($this->nickname)){
    		$emptyFields = false;
    		$fields[] = 'nickname';
    	}else{
    		$emptyCondition = false; 
    		$condition['nickname'] = $this->nickname;
    	}
    	if (!isset($this->password)){
    		$emptyFields = false;
    		$fields[] = 'password';
    	}else{
    		$emptyCondition = false; 
    		$condition['password'] = $this->password;
    	}
    	if (!isset($this->reg_time)){
    		$emptyFields = false;
    		$fields[] = 'reg_time';
    	}else{
    		$emptyCondition = false; 
    		$condition['reg_time'] = $this->reg_time;
    	}
    	if (!isset($this->avatar_url)){
    		$emptyFields = false;
    		$fields[] = 'avatar_url';
    	}else{
    		$emptyCondition = false; 
    		$condition['avatar_url'] = $this->avatar_url;
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

        if (empty($this->user_id))
        {
            $this->user_id = SQLUtil::GetInsertId();
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

        if (empty($this->user_id))
        {
            $this->user_id = SQLUtil::GetInsertId();
        }

        $this->clean();

        return true;
    }

    public function /*boolean*/ save(/*array*/$condition = NULL)
    {
        if (empty($condition) )
        {
            if(isset($this->user_id))
                $condition = array('user_id'=>$this->user_id);
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
        if (!isset($this->user_id))
        {
            return false;
        }

        $sql = "DELETE FROM `" . self::TABLE_NAME . "` WHERE `user_id`='{$this->user_id}'";
    
        $qr =SQLUtil::RunQuery($sql);

        return (boolean)$qr;
    }

	public function getInsertValue($fields)
	{
		$values = "(";		
		foreach($fields as $f => $k){
			 	
		if($f == 'user_id'){
			$values .= "'" . ($this->user_id) . "',";
		}
			 			else if($f == 'user_name'){
			$values .= "'" . addslashes($this->user_name) . "',";
		}
					else if($f == 'nickname'){
			$values .= "'" . addslashes($this->nickname) . "',";
		}
					else if($f == 'password'){
			$values .= "'" . addslashes($this->password) . "',";
		}
					else if($f == 'reg_time'){
			$values .= "'" . addslashes($this->reg_time) . "',";
		}
					else if($f == 'avatar_url'){
			$values .= "'" . addslashes($this->avatar_url) . "',";
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

			
		if (isset($this->user_id))
		{
			$fields .= "`user_id`,";
			$values .= "'" . ($this->user_id) . "',";
		}
						
		if (isset($this->user_name))
		{
			$fields .= "`user_name`,";
			$values .= "'" . addslashes($this->user_name) . "',";
		}
						
		if (isset($this->nickname))
		{
			$fields .= "`nickname`,";
			$values .= "'" . addslashes($this->nickname) . "',";
		}
						
		if (isset($this->password))
		{
			$fields .= "`password`,";
			$values .= "'" . addslashes($this->password) . "',";
		}
						
		if (isset($this->reg_time))
		{
			$fields .= "`reg_time`,";
			$values .= "'" . addslashes($this->reg_time) . "',";
		}
						
		if (isset($this->avatar_url))
		{
			$fields .= "`avatar_url`,";
			$values .= "'" . addslashes($this->avatar_url) . "',";
		}
			
		
		$fields .= ")";
		$values .= ")";
		
		$sql = "INSERT INTO `" . self::TABLE_NAME . "` ".$fields.$values;
		
		return str_replace(",)",")",$sql);
	}
	
	private function /*string*/ getUpdateFields()
	{
		$update = "";
		
			
		if ($this->is_user_name_dirty)
		{
			if (!isset($this->user_name))
			{
				$update .= ("`user_name`=null,");
			}
			else
			{
				$update .= ("`user_name`='".addslashes($this->user_name)."',");
			}
		}
						
		if ($this->is_nickname_dirty)
		{
			if (!isset($this->nickname))
			{
				$update .= ("`nickname`=null,");
			}
			else
			{
				$update .= ("`nickname`='".addslashes($this->nickname)."',");
			}
		}
						
		if ($this->is_password_dirty)
		{
			if (!isset($this->password))
			{
				$update .= ("`password`=null,");
			}
			else
			{
				$update .= ("`password`='".addslashes($this->password)."',");
			}
		}
						
		if ($this->is_reg_time_dirty)
		{
			if (!isset($this->reg_time))
			{
				$update .= ("`reg_time`=null,");
			}
			else
			{
				$update .= ("`reg_time`='".addslashes($this->reg_time)."',");
			}
		}
						
		if ($this->is_avatar_url_dirty)
		{
			if (!isset($this->avatar_url))
			{
				$update .= ("`avatar_url`=null,");
			}
			else
			{
				$update .= ("`avatar_url`='".addslashes($this->avatar_url)."',");
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
		$this->is_user_id_dirty = false;
		$this->is_user_name_dirty = false;
		$this->is_nickname_dirty = false;
		$this->is_password_dirty = false;
		$this->is_reg_time_dirty = false;
		$this->is_avatar_url_dirty = false;

	}
	
	public function /*int*/ getUserId()
	{
		return $this->user_id;
	}

	public function /*void*/ setUserId(/*int*/ $user_id)
	{
		$this->user_id = intval($user_id);
		$this->is_user_id_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setUserIdNull()
	{
		$this->user_id = null;
		$this->is_user_id_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getUserName()
	{
		return $this->user_name;
	}

	public function /*void*/ setUserName(/*string*/ $user_name)
	{
		$this->user_name = ($user_name);
		$this->is_user_name_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setUserNameNull()
	{
		$this->user_name = null;
		$this->is_user_name_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getNickname()
	{
		return $this->nickname;
	}

	public function /*void*/ setNickname(/*string*/ $nickname)
	{
		$this->nickname = ($nickname);
		$this->is_nickname_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setNicknameNull()
	{
		$this->nickname = null;
		$this->is_nickname_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getPassword()
	{
		return $this->password;
	}

	public function /*void*/ setPassword(/*string*/ $password)
	{
		$this->password = ($password);
		$this->is_password_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setPasswordNull()
	{
		$this->password = null;
		$this->is_password_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getRegTime()
	{
		return $this->reg_time;
	}

	public function /*void*/ setRegTime(/*string*/ $reg_time)
	{
		$this->reg_time = ($reg_time);
		$this->is_reg_time_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setRegTimeNull()
	{
		$this->reg_time = null;
		$this->is_reg_time_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getAvatarUrl()
	{
		return $this->avatar_url;
	}

	public function /*void*/ setAvatarUrl(/*string*/ $avatar_url)
	{
		$this->avatar_url = ($avatar_url);
		$this->is_avatar_url_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setAvatarUrlNull()
	{
		$this->avatar_url = null;
		$this->is_avatar_url_dirty = true;
		$this->is_this_table_dirty = true;
	}
	
	public function /*string*/ toDebugString()
	{
		$dbg = "(";
		
		$dbg .= ("user_id={$this->user_id},");
		$dbg .= ("user_name={$this->user_name},");
		$dbg .= ("nickname={$this->nickname},");
		$dbg .= ("password={$this->password},");
		$dbg .= ("reg_time={$this->reg_time},");
		$dbg .= ("avatar_url={$this->avatar_url},");

		$dbg .= ")";
				
		return str_replace(",)",")",$dbg);
	}
	
}

?>
