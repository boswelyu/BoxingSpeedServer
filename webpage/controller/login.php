<?php
/**
 * Created by PhpStorm.
 * User: boswell
 * Date: 8/20/17
 * Time: 10:16 PM
 */

require_once(__DIR__ . "/../../config.php");
require_once($GLOBALS["SERVER_ROOT"] . "/webpage/module/WebMsg.php");
require_once($GLOBALS["SERVER_ROOT"] . "/dbtable/TbPlayerLogin.php");

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
$tbPlayer = new TbPlayerLogin();
$tbPlayer->setUserName($username);
if(!$tbPlayer->loadFromExistFields()) {
    // No such user
    $reply->SetError(WebMsg::ECODE_NO_USER, WebMsg::EINFO_NO_USER);
    $reply->SendOut();
    return;
}

if(strcmp($tbPlayer->getPassword(), $password) != 0) {
    $reply->SetError(WebMsg::ECODE_PWD_MISMATCH, WebMsg::EINFO_PWD_MISMATCH);
    $reply->SendOut();
    return;
}

// Login success, refresh the session key
$currTime = time();
$sessionKey = substr(md5("$username-$currTime"), 8, -8);
$tbPlayer->setSessionKey($sessionKey);
$tbPlayer->save();

// password validation success, return user info to client
$reply->SetContent("userId", $tbPlayer->getUserId());
$reply->SetContent("serverIp", SOCKET_IP);
$reply->SetContent("serverPort", SOCKET_PORT);
$reply->SetContent("sessionKey", $sessionKey);
$reply->SendOut();


