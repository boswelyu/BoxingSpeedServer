<?php
/**
 * User: YuBo
 * Date: 2016/11/11
 * Time: 19:52
 */

class SQLUtil
{
    /** @var mysqli $mysqli */
    private static $mysqli = null;
    // Construct Fields string which used in SQL query by given fields name array
    public static function parseFields($fields) {
        if(!is_array($fields)) {
            return $fields;
        }

        $nf = '';
        foreach ($fields as $key) {
            $k = addslashes($key);
            $nf .= "`{$k}`,";
        }

        $i = strrpos($nf, ",");
        if (!is_bool($i)) {
            $nf = substr($nf, 0, $i);
        }

        return $nf;
    }

    public static function parseCondition($condition) {
        if (!is_array($condition)) {
            return $condition;
        }

        $condResult = '';

        foreach ($condition as $key => $value) {
            //if((is_string($key) && empty($key)) || (is_string($value) && empty($value)))
            //{
            //	return "0=1";
            //}

            $k = addslashes($key);
            $v = addslashes($value);

            $condResult .= "`{$k}`='{$v}' AND ";
        }

        $i = strrpos($condResult, "AND");
        if (!is_bool($i)) {
            $condResult = substr($condResult, 0, $i);
        }

        return $condResult;
    }

    public static function RunQuery($query)
    {
        /** @var mysqli $link */
        $link = self::ConnectToDatabase();

        if(empty($link)) {
            return false;
        }

        $resultSet = $link->query($query);

        return $resultSet;
    }

    public static function GetInsertId() {
        if(empty(self::$mysqli)) {
            return false;
        }

        return self::$mysqli->insert_id;
    }

    private static function ConnectToDatabase() {
        if(empty(self::$mysqli)) {
            self::$mysqli = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);

            if(self::$mysqli->connect_error) {
//                Logger::getInstance()->Fatal("+++++ Connect To DB Failed ++++++");
//                echo "Connect to DB Failed";
                self::$mysqli = null;
            }
            else {
//                Logger::getInstance()->Notice("DB Connection Created");
//                echo "Connected to DB success";
                self::$mysqli->set_charset('utf-8');
            }
        }

        return self::$mysqli;
    }
}