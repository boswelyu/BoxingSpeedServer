<?php
/**
 * Created by Boswell Yu.
 * User: boswell
 * Date: 8/20/17
 * Time: 8:37 PM
 */

use Workerman\Connection\ConnectionInterface;

require_once(__DIR__ . "/../config.php");
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Client/ProtoHead.php");
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Client/Login.php");
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Client/ClientMsg.php");
require_once($GLOBALS["SERVER_ROOT"] . "/appworker/protocol/XMessage.php");
require_once($GLOBALS["SERVER_ROOT"] . "/appworker/reflector/OnProtobuf.php");

class MessageProcessor
{
    private static $msgHandlerArray = array(
        0x00000001 => "PingPongHandler",
        0x00000002 => "ProtobufHandler",
    );

    const PROCESS_SUCCESS = 0;
    const ERROR_GENERAL_FAIL = -1;
    const ERROR_MESSAGE_TOO_SHORT = -2;
    const ERROR_LENGTH_NOT_MATCH = -3;
    const ERROR_UNKNOWN_COMMAND = -4;
    const ERROR_HANDLE_MSG_FAILED = -5;
    const ERROR_EXCEPTION_RAISED = -6;

    public static function InitService()
    {

    }

    public static function ProcessMessage(ConnectionInterface $connection, $data)
    {
        $xmsg = new XMessage();
        $xmsg->setData($data);

        $packageLen = $xmsg->getLength();
        if($packageLen < 8) {
            // Message is too short
            self::ReplyClientError($connection, MessageProcessor::ERROR_MESSAGE_TOO_SHORT);
            return;
        }

        $version = $xmsg->readByte();
        $encrypt = $xmsg->readByte();
        $headLen = $xmsg->readInt16();
        $msgLen = $xmsg->readInt32();
        if($msgLen != $packageLen) {
            self::ReplyClientError($connection, MessageProcessor::ERROR_LENGTH_NOT_MATCH);
            return;
        }


        $command = $xmsg->readInt32();
        if(array_key_exists($command, self::$msgHandlerArray) !== true) {
            self::ReplyClientError($connection, MessageProcessor::ERROR_UNKNOWN_COMMAND);
            return;
        }

        $userId = $xmsg->readInt32();

        $realMsg = $xmsg->readBytes($msgLen - $headLen);
        if($encrypt == 1) {
            $realMsg = AES::Decrypt($userId, $realMsg);
        }

        echo "msglen: $msgLen, verson: $version, encrypt: $encrypt. HeadLen: $headLen, command: $command, userId: $userId \n";

        $action = self::$msgHandlerArray[$command];

        try {
            $ret = self::$action($connection, $userId, $realMsg);
        }catch(Exception $ex)
        {
            self::ReplyClientError($connection, MessageProcessor::ERROR_EXCEPTION_RAISED);
            return;
        }

        if($ret < 0) {
            self::ReplyClientError($connection, MessageProcessor::ERROR_HANDLE_MSG_FAILED);
            return;
        }
    }

    private static function ProtobufHandler($connection, $userId, $inPacket)
    {
        try {
            $clientMsg = new \Client\ClientMsg();
            $clientMsg->mergeFromString($inPacket);

            $retPacket = new \Server\ServerMsg();
            $ret = OnProtobuf($userId, $inPacket, $retPacket);

            if($ret < 0) {
                self::ReplyClientError($connection, $ret);
                return;
            }

            self::ReplyClientMessage($connection, $userId, $retPacket);

        }
        catch(Exception $ex) {
            throw $ex;
        }
    }

    private static function ReplyClientError(ConnectionInterface $connection, $errorcode)
    {
        // TODO: Pack the error code into message and return to client
    }

    private static function ReplyClientMessage(ConnectionInterface $connection, $userId, \Server\ServerMsg $retMsg)
    {

    }

}