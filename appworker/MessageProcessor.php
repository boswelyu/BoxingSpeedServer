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
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Server/ServerMsg.php");
require_once($GLOBALS["SERVER_ROOT"] . "/appworker/protocol/XMessage.php");
require_once($GLOBALS["SERVER_ROOT"] . "/appworker/reflector/OnProtobuf.php");
require_once($GLOBALS["SERVER_ROOT"] . "/utility/AES.php");

class MessageProcessor
{
    const PINGPONG_MESSAGE = 0x00000001;
    const PROTOBUF_MESSAGE = 0x00000002;

    private static $msgHandlerArray = array(
        self::PINGPONG_MESSAGE => "PingPongHandler",
        self::PROTOBUF_MESSAGE => "ProtobufHandler",
    );

    const PROCESS_SUCCESS = 0;
    const ERROR_GENERAL_FAIL = -1;
    const ERROR_MESSAGE_TOO_SHORT = -2;
    const ERROR_LENGTH_NOT_MATCH = -3;
    const ERROR_UNKNOWN_COMMAND = -4;
    const ERROR_HANDLE_MSG_FAILED = -5;
    const ERROR_EXCEPTION_RAISED = -6;
    const ERROR_MISSING_HEAD     = -7;
    const ERROR_USERID_MISMATCH  = -8;

    public static function InitService()
    {

    }

    public static function ProcessMessage(ConnectionInterface $connection, $data)
    {
        $xmsg = new XMessage();
        $xmsg->setData($data);

        echo "Received Message";

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
            $ret = OnProtobuf($userId, $clientMsg, $retPacket);

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

    //    1        1          2          4            4         4
    // Version | Encrypt | Head Len | Packet Len | Command | Error Code
    private static function ReplyClientError(ConnectionInterface $connection, $errorCode)
    {
        $xmsg = new XMessage();
        $xmsg->writebyte(1);
        $xmsg->writeByte(0);
        $xmsg->writeShort(16);
        $xmsg->writeInt32(16);
        $xmsg->writeInt32(self::PROTOBUF_MESSAGE);
        $xmsg->writeInt32($errorCode);

        $connection->send($xmsg->raw_data);
    }

    //    1        1          2          4            4           4
    // Version | Encrypt | Head Len | Packet Len | Command | Error Code(0) | Server Message Data
    private static function ReplyClientMessage(ConnectionInterface $connection, $userId, \Server\ServerMsg $retMsg)
    {
        $xmsg = new XMessage();
        $xmsg->writebyte(1);
        $xmsg->writeByte(0);
        $xmsg->writeShort(16);

        $serialData = $retMsg->serializeToString();
        $packetLen = strlen($serialData);

        $xmsg->writeInt32(16 + $packetLen);
        $xmsg->writeInt32(self::PROTOBUF_MESSAGE);
        $xmsg->writeInt32(0);

        $xmsg->writeBinary($serialData, $packetLen);

        $connection->send($xmsg->raw_data);
    }

}