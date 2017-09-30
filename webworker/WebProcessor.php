<?php
/**
 * Created by Boswell Yu.
 * User: boswell
 * Date: 8/20/17
 * Time: 8:37 PM
 */

use Workerman\Connection\ConnectionInterface;

require_once(__DIR__ . "/../config.php");
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Request/Person.php");

class WebProcessor
{
    public static function ProcessMessage(ConnectionInterface $connection, $data)
    {
        try{
            $clientMsg = new \Request\Person();
            $clientMsg->mergeFromString($data);

            echo "Name :" . $clientMsg->getName() . "\n";
            echo "Email: " . $clientMsg->getEmail();
            echo "ID: " . $clientMsg->getId();


//            echo "Person Email: " . $clientMsg->getEmail();

        }catch(Exception $ex) {
            echo "Parse Message Error";
        }
    }

}