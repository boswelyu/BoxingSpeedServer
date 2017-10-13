<?php

/**
 * User: YuBo
 * Date: 2017/10/13
 * Time: 14:01
 */

require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Server/LoginReply.php");
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Server/UserProfile.php");
require_once($GLOBALS["SERVER_ROOT"] . "/dbtable/TbPlayer.php");

class ProfilerManager
{
    public static function getUserProfile($userId)
    {
        $profiler = new \Server\UserProfile();

        $tbPlayer = new TbPlayer();
        $tbPlayer->setUserId($userId);
        if($tbPlayer->loadFromExistFields()) {
            $profiler->setUserId($userId);
            $profiler->setUsername($tbPlayer->getUserName());
            $profiler->setNickname($tbPlayer->getNickname());
            $profiler->setAvatarUrl($tbPlayer->getAvatarUrl());
        }
        else {
            echo "UserID Not Exist: $userId";
        }

        return $profiler;
    }
}
