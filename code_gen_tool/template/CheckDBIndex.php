<?php
require_once (dirname(__FILE__) . "/../CMySQL.php");

/**
 *
 * [This file was auto-generated. PLEASE DONT EDIT]
 *
 * @author dany
 *
 */


function gotodie($msg)
{
    echo $msg. "\n";
    exit(1);
}



function connectmysql($mysql_host,$mysql_user,$mysql_password,$mysql_db)
{
    $con = mysql_connect($mysql_host,$mysql_user,$mysql_password);
    if (!$con)
    {
        gotodie('Could not connect: ' . mysql_error());
    }

    mysql_select_db($mysql_db, $con) or gotodie(mysql_error());
    return $con;
}




// public static $_table_index_info = array(
// 		'PRIMARY'=>array('unique'=>true, 'fields'=>array('user_id'=>0))
// );

function kdiff($src,$dst)
{

    foreach ($src as $k=>$v)
    {
        if(!isset($dst[$k]))
        {
            echo "dst missing index $k\n";
            return false;
        }
        foreach($v as $kv => $vv)
        {
            if(!isset($dst[$k][$kv]))
            {
                echo "dst index $k missing properties $kv\n";
                return false;
            }

            if(is_array($vv))
            {
                foreach($vv as $k3=>$v3)
                {
                    if(!isset($dst[$k][$kv][$k3]))
                    {
                        echo "dst index $k->$kv missing properties $k3\n";
                        return false;
                    }

                    if($v3 != $dst[$k][$kv][$k3])
                    {
                        echo "dst index $k->$kv properties $k3 not match: $v3 , {$dst[$k][$kv][$k3]}\n";
                        return false;
                    }
                }
            }
            else if($vv != $dst[$k][$kv])
            {
                echo "dst index $k  properties $kv not match $vv , {$dst[$k][$kv]}\n";
                return false;
            }
        }
    }
    return true;
}


function getIndexInfoFromMysql($tableName)
{
    $indices = array();

    $sql = "show index from ".$tableName;
    $result = mysql_query($sql);
    if(!$result)
        gotodie(mysql_error());

    while($indexInfo = mysql_fetch_array($result))
    {
        $keyName = $indexInfo['Key_name'];
        $seq = intval($indexInfo['Seq_in_index']);
        if(isset($indices[$keyName]))
        {
            $colName = $indexInfo['Column_name'];
            $indices[$keyName]['fields'][$colName] = $seq-1;
        }
        else
        {
            $index = array();
            $index['fields'] = array();
            $colName = $indexInfo['Column_name'];
            $index['fields'][$colName] = $seq-1;
            $index['unique']=intval($indexInfo['Non_unique'])==0?true:false;
            $indices[$keyName]=$index;

        }
    }
    mysql_free_result($result);


    return $indices;
}


function indexCheck($genInfo, $tableName)
{
    $indices = getIndexInfoFromMysql($tableName);
    $ret = kdiff($genInfo, $indices) ;
    if(!$ret)
    {

        echo "Index check failed $tableName\n";
        echo "-----------------------------－－\n";
    }

    return $ret;
}


function getTableField($tableName)
{
    $sql =  "DESC ".$tableName ;
    $fieldInfo = array();

    $result = mysql_query($sql);
    if(!$result)
        gotodie(mysql_error());

    while($tableInfo = mysql_fetch_array($result))
    {

        $name = $tableInfo['Field'];

        $type =  $tableInfo['Type'];
        $fieldInfo[$name]=$type;
    }
    return $fieldInfo;
}


function checkTableFields($fields, $tableName)
{
    $tbf = getTableField($tableName);

    foreach($fields as $f)
    {
        if(!isset($tbf[$f]))
        {
            echo "$tableName missing field $f\n";
            return false;
        }
    }

    return true;
}

if($_SERVER['argc']>4)
{
    $dbHost = $_SERVER['argv'][1];
    $dbUser = $_SERVER['argv'][2];
    $dbPwd = $_SERVER['argv'][3];
    $dbName = $_SERVER['argv'][4];
    $con = connectmysql($dbHost,$dbUser, $dbPwd, $dbName);
}
else
{
    echo "miss mysql parameters\n";
    exit(1);
}


$retval=true;

$path = dirname(__FILE__);
$dir = opendir($path);
while(false != ($filename=readdir($dir))){
    $fn = $filename;
    if(is_file($path."/".$fn) && preg_match('/Tb.*\.php/',$fn, $m)){
        require_once($fn);
        $className= substr($fn, 0,strlen($fn)-4);
        $ret=indexCheck($className::$_table_index_info,$className::_original_table_name);
        if($retval==true && $ret==false) $retval=false;
        $ret=checkTableFields($className::$_all_tabl_field_names,$className::_original_table_name);
        if($retval==true && $ret==false) $retval=false;
    }
}
closedir($dir);



if(!$retval)
{
    exit(1);
}


