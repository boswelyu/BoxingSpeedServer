<?php
/**
 * database table player_basic description
 *
 * [This file was auto-generated. PLEASE DO NOT EDIT]
 *
 * @author Boswell Yu
 *
 */

require_once($GLOBALS['SERVER_ROOT'] . "utility/SQLUtil.php");

class TbPlayerBasic {

    const TABLE_NAME = 'player_basic';
    public static $table_index_info = array(
			'PRIMARY'=>array('unique'=>true, 'fields'=>array('user_id'=>0))
	);
    public static $all_table_field_names = array(
		'user_id',
		'gender',
		'birthDate',
		'nickname',
		'location',
		'signature',
		'avatar_url'
	);

	private /*int*/ $user_id; //PRIMARY KEY 
	private /*int*/ $gender;
	private /*string*/ $birthDate;
	private /*string*/ $nickname;
	private /*string*/ $location;
	private /*string*/ $signature;
	private /*string*/ $avatar_url;


    private $is_this_table_dirty = false;
	private $is_user_id_dirty = false;
	private $is_gender_dirty = false;
	private $is_birthDate_dirty = false;
	private $is_nickname_dirty = false;
	private $is_location_dirty = false;
	private $is_signature_dirty = false;
	private $is_avatar_url_dirty = false;


    public function TbPlayerBasic()
    {
    }


    /**
     * @example loadTable(array('user_id'),array("user_id"=>"123"))
     * @param array($condition)
     * @return array(TbPlayerBasic)
    */
    public static function /*array(TbPlayerBasic)*/loadTable(/*array*/ $fields = NULL, /*array*/$condition = NULL)
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
            $tb = new TbPlayerBasic();
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
            			$result[0]="INSERT INTO `" . self::TABLE_NAME . "` (`user_id`,`gender`,`birthDate`,`nickname`,`location`,`signature`,`avatar_url`) VALUES ";
			$result[1] = array('user_id'=>1,'gender'=>1,'birthDate'=>1,'nickname'=>1,'location'=>1,'signature'=>1,'avatar_url'=>1);
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
		if (isset($ar['gender'])) $this->gender = intval($ar['gender']);
		if (isset($ar['birthDate'])) $this->birthDate = $ar['birthDate'];
		if (isset($ar['nickname'])) $this->nickname = $ar['nickname'];
		if (isset($ar['location'])) $this->location = $ar['location'];
		if (isset($ar['signature'])) $this->signature = $ar['signature'];
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
    	if (!isset($this->gender)){
    		$emptyFields = false;
    		$fields[] = 'gender';
    	}else{
    		$emptyCondition = false; 
    		$condition['gender'] = $this->gender;
    	}
    	if (!isset($this->birthDate)){
    		$emptyFields = false;
    		$fields[] = 'birthDate';
    	}else{
    		$emptyCondition = false; 
    		$condition['birthDate'] = $this->birthDate;
    	}
    	if (!isset($this->nickname)){
    		$emptyFields = false;
    		$fields[] = 'nickname';
    	}else{
    		$emptyCondition = false; 
    		$condition['nickname'] = $this->nickname;
    	}
    	if (!isset($this->location)){
    		$emptyFields = false;
    		$fields[] = 'location';
    	}else{
    		$emptyCondition = false; 
    		$condition['location'] = $this->location;
    	}
    	if (!isset($this->signature)){
    		$emptyFields = false;
    		$fields[] = 'signature';
    	}else{
    		$emptyCondition = false; 
    		$condition['signature'] = $this->signature;
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
			 			else if($f == 'gender'){
			$values .= "'" . ($this->gender) . "',";
		}
					else if($f == 'birthDate'){
			$values .= "'" . addslashes($this->birthDate) . "',";
		}
					else if($f == 'nickname'){
			$values .= "'" . addslashes($this->nickname) . "',";
		}
					else if($f == 'location'){
			$values .= "'" . addslashes($this->location) . "',";
		}
					else if($f == 'signature'){
			$values .= "'" . addslashes($this->signature) . "',";
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
						
		if (isset($this->gender))
		{
			$fields .= "`gender`,";
			$values .= "'" . ($this->gender) . "',";
		}
						
		if (isset($this->birthDate))
		{
			$fields .= "`birthDate`,";
			$values .= "'" . addslashes($this->birthDate) . "',";
		}
						
		if (isset($this->nickname))
		{
			$fields .= "`nickname`,";
			$values .= "'" . addslashes($this->nickname) . "',";
		}
						
		if (isset($this->location))
		{
			$fields .= "`location`,";
			$values .= "'" . addslashes($this->location) . "',";
		}
						
		if (isset($this->signature))
		{
			$fields .= "`signature`,";
			$values .= "'" . addslashes($this->signature) . "',";
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
		
			
		if ($this->is_gender_dirty)
		{
			if (!isset($this->gender))
			{
				$update .= ("`gender`=null,");
			}
			else
			{
				$update .= ("`gender`='".($this->gender)."',");
			}
		}
						
		if ($this->is_birthDate_dirty)
		{
			if (!isset($this->birthDate))
			{
				$update .= ("`birthDate`=null,");
			}
			else
			{
				$update .= ("`birthDate`='".addslashes($this->birthDate)."',");
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
						
		if ($this->is_location_dirty)
		{
			if (!isset($this->location))
			{
				$update .= ("`location`=null,");
			}
			else
			{
				$update .= ("`location`='".addslashes($this->location)."',");
			}
		}
						
		if ($this->is_signature_dirty)
		{
			if (!isset($this->signature))
			{
				$update .= ("`signature`=null,");
			}
			else
			{
				$update .= ("`signature`='".addslashes($this->signature)."',");
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
		$this->is_gender_dirty = false;
		$this->is_birthDate_dirty = false;
		$this->is_nickname_dirty = false;
		$this->is_location_dirty = false;
		$this->is_signature_dirty = false;
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
	public function /*int*/ getGender()
	{
		return $this->gender;
	}

	public function /*void*/ setGender(/*int*/ $gender)
	{
		$this->gender = intval($gender);
		$this->is_gender_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setGenderNull()
	{
		$this->gender = null;
		$this->is_gender_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getBirthDate()
	{
		return $this->birthDate;
	}

	public function /*void*/ setBirthDate(/*string*/ $birthDate)
	{
		$this->birthDate = ($birthDate);
		$this->is_birthDate_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setBirthDateNull()
	{
		$this->birthDate = null;
		$this->is_birthDate_dirty = true;
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
	public function /*string*/ getLocation()
	{
		return $this->location;
	}

	public function /*void*/ setLocation(/*string*/ $location)
	{
		$this->location = ($location);
		$this->is_location_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setLocationNull()
	{
		$this->location = null;
		$this->is_location_dirty = true;
		$this->is_this_table_dirty = true;
	}
	public function /*string*/ getSignature()
	{
		return $this->signature;
	}

	public function /*void*/ setSignature(/*string*/ $signature)
	{
		$this->signature = ($signature);
		$this->is_signature_dirty = true;
		$this->is_this_table_dirty = true;
	}


	public function /*void*/ setSignatureNull()
	{
		$this->signature = null;
		$this->is_signature_dirty = true;
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
		$dbg .= ("gender={$this->gender},");
		$dbg .= ("birthDate={$this->birthDate},");
		$dbg .= ("nickname={$this->nickname},");
		$dbg .= ("location={$this->location},");
		$dbg .= ("signature={$this->signature},");
		$dbg .= ("avatar_url={$this->avatar_url},");

		$dbg .= ")";
				
		return str_replace(",)",")",$dbg);
	}
	
}

?>
