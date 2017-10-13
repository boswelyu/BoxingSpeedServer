<?php
/**
 * User: YuBo
 * Date: 2017/10/11
 * Time: 15:02
 */


require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Server/LoginReply.php");
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Common/RESULT.php");
require_once($GLOBALS["SERVER_ROOT"] . "/appworker/manager/ProfilerManager.php");


function OnLogin($userId, \Client\Login $loginMsg)
{
    $loginReply = new \Server\LoginReply();

    $loginReply->setResult(\Common\RESULT::RESULT_SUCCESS);

    $profile = ProfilerManager::getUserProfile($userId);
    $loginReply->setProfile($profile);

    return $loginReply;
}
