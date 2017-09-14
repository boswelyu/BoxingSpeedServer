<?php
/**
 * Created by Boswell Yu.
 * User: boswell
 * Date: 8/20/17
 * Time: 8:37 PM
 */

use Workerman\Connection\ConnectionInterface;

class WebProcessor
{
    public static function ProcessMessage(ConnectionInterface $connection, $data)
    {
        $connection->send("Hello Web Message, show message content here!");
    }

}