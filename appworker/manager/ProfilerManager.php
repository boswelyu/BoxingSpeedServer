<?php

/**
 * User: YuBo
 * Date: 2017/10/13
 * Time: 14:01
 */

require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Server/LoginReply.php");
require_once($GLOBALS["SERVER_ROOT"] . "/protobuf/Server/UserProfile.php");
require_once($GLOBALS["SERVER_ROOT"] . "/dbtable/TbPlayerLogin.php");
require_once($GLOBALS["SERVER_ROOT"] . "/dbtable/TbPlayerBasic.php");


class ProfilerManager
{
    public static function getUserProfile($userId)
    {
        // TODO: Add Local Cache

        $profiler = new \Server\UserProfile();

        $tbPlayerLogin = new TbPlayerLogin();
        $tbPlayerLogin->setUserId($userId);
        if($tbPlayerLogin->load()) {
            $profiler->setUserId($userId);
            $profiler->setUsername($tbPlayerLogin->getUserName());
            $profiler->setPhoneNum($tbPlayerLogin->getPhoneNumber());
        }

        $tbPlayerBasic = new TbPlayerBasic();
        $tbPlayerBasic->setUserId($userId);
        if($tbPlayerBasic->loadFromExistFields()) {
            $profiler->setNickname($tbPlayerBasic->getNickname());
            $profiler->setBirthDate($tbPlayerBasic->getBirthDate());
            $profiler->setGender($tbPlayerBasic->getGender());
            $profiler->setLocation($tbPlayerBasic->getLocation());
            $profiler->setSignature($tbPlayerBasic->getSignature());
            $profiler->setAvatarUrl($tbPlayerBasic->getAvatarUrl());
        }

        return $profiler;
    }
}
