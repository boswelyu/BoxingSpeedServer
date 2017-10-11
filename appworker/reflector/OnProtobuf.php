<?php
/**
 * User: YuBo
 * Date: 2017/10/9
 * Time: 10:32
 */

require_once(__DIR__ . "/../../config.php");
require_once($GLOBALS["SERVER_ROOT"] . "/appworker/reflector/OnLogin.php");

function OnProtobuf($userId, \Client\ClientMsg $inPacket, \Server\ServerMsg &$outPacket)
{
    $protoHead = $inPacket->getProtoHead();
    if(!isset($protoHead)) {
        return MessageProcessor::ERROR_MISSING_HEAD;
    }

    if($protoHead->getUserId() != $userId) {
        return MessageProcessor::ERROR_USERID_MISMATCH;
    }

    $clientVersion = $protoHead->getVersion();
    $platform = $protoHead->getPlatform();
    echo "Client Version: $clientVersion, Client Platform: $platform \n";

    $login = $inPacket->getLogin();
    if(isset($login)) {
        $loginReply = OnLogin($userId, $login);
        if(isset($loginReply)) {
            $outPacket->setLoginReply($loginReply);
        }
    }

    return 0;
}
