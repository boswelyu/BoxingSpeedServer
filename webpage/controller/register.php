<?php
/**
 * Created by PhpStorm.
 * User: boswell
 * Date: 10/17/2017
 * Time: 18:01 PM
 */

require_once(__DIR__ . "/../../config.php");
require_once($GLOBALS["SERVER_ROOT"] . "/webpage/module/WebMsg.php");
require_once($GLOBALS["SERVER_ROOT"] . "/dbtable/TbPlayer.php");

$postdata = $GLOBALS['HTTP_RAW_POST_DATA'];

$reply = new WebMsg(WebMsg::LOGIN_REPLY);

$request = json_decode($postdata, true);
if(!isset($request))
{
    $reply->setError(WebMsg::ECODE_EMPTY, WebMsg::EINFO_EMPTY);
    $reply->SendOut();
    return;
}

$username = $request["username"];
$password = $request["password"];

if(!isset($username)) {
    $reply->setError(WebMsg::ECODE_INVALID_USER, WebMsg::EINFO_INVALID_USER);
    $reply->SendOut();
    return;
}

if(!isset($password)) {
    $reply->SetError(WebMsg::ECODE_INVALID_PWD, WebMsg::EINFO_INVALID_PWD);
    $reply->SendOut();
    return;
}

// Validate username and password from DB
$tbPlayer = new TbPlayer();
$tbPlayer->setUserName($username);
if($tbPlayer->loadFromExistFields()) {
    // username duplex
    $reply->SetError(WebMsg::ECODE_DUPLEX_USER, WebMsg::EINFO_DUPLEX_USER);
    $reply->SendOut();
    return;
}

// username valid success, insert into database
$tbPlayer->setPassword($password);
if($tbPlayer->insertOrUpdate()) {
    // Create player success,
    $reply->SetContent("userId", $tbPlayer->getUserId());
    $reply->SetContent("serverIp", SOCKET_IP);
    $reply->SetContent("serverPort", SOCKET_PORT);
    $reply->SetContent("sessionKey", "hlikeilslked");
    $reply->SendOut();
} else {
    $reply->SetError(WebMsg::ECODE_CREATE_FAILED, WebMsg::EINFO_CREATE_FAILED);
    $reply->SendOut();
}

// password validation success, return user info to client



