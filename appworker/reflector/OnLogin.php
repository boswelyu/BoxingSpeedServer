<?php
/**
 * User: YuBo
 * Date: 2017/10/11
 * Time: 15:02
 */

require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Server/LoginReply.php");

function OnLogin($userId, \Client\Login $loginMsg)
{
    $loginReply = new \Server\LoginReply();

    $loginReply->setResult(1);

    return $loginReply;
}
