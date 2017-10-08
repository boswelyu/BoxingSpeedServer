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
require_once($GLOBALS["SERVER_ROOT"] . "/webworker/protocol/XMessage.php");

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

    public static function InitService()
    {

    }

    public static function ProcessMessage(ConnectionInterface $connection, $data)
    {
        try{
            $xmsg = new XMessage();
            $xmsg->setData($data);

            $packageLen = $xmsg->getLength();
            if($packageLen < 8) {
                // Message is too short
                ReplyClientError($connection, MessageProcessor::ERROR_MESSAGE_TOO_SHORT);
                return;
            }

            $version = $xmsg->readByte();
            $encrypt = $xmsg->readByte();
            $headLen = $xmsg->readInt16();
            $msgLen = $xmsg->readInt32();
            if($msgLen != $packageLen) {
                ReplyClientError($connection, MessageProcessor::ERROR_LENGTH_NOT_MATCH);
                return;
            }


            $command = $xmsg->readInt32();
            $userId = $xmsg->readInt32();

            echo "msglen: $msgLen, verson: $version, encrypt: $encrypt. HeadLen: $headLen, command: $command, userId: $userId \n";

            if($command == 0x00000001) {
                // Protobuf Message
                $clientMsg = new \Client\ClientMsg();
                $clientMsg->mergeFromString($xmsg->readBytes($msgLen - $headLen));

                $loginMsg = $clientMsg->getLogin();
                if (isset($loginMsg)) {
                    $deviceId = $loginMsg->getDeviceId();
                    echo "Login Message Content: $deviceId\n";
                } else {
                    echo "No Login Message in Client Message\n";
                }
            } else {
                echo "Received message is not protobuf message!";
            }


        }catch(Exception $ex) {
            echo "Parse Message Error";
        }
    }

    private static function ReplyClientError(ConnectionInterface $connection, $errorcode)
    {
        // TODO: package the error code into one message return to client
    }

}