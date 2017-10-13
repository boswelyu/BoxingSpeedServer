<?php
/**
 * Created by PhpStorm.
 * User: boswell
 * Date: 8/20/17
 * Time: 10:16 PM
 */

require_once(__DIR__ . "/../../config.php");
require_once($GLOBALS["SERVER_ROOT"] . "/webpage/module/WebMsg.php");
require_once($GLOBALS["SERVER_ROOT"] . "/dbtable/TbPlayer.php");

$postdata = $GLOBALS['HTTP_RAW_POST_DATA'];

$reply = new WebMsg(WebMsg::LOGIN_REPLY);

$request = json_decode($postdata, true);
if(!isset($request))
{
    $reply->setError("Invalid empty request");
    $reply->SendOut();
    return;
}

$username = $request["username"];
$password = $request["password"];

if(!isset($username)) {
    $reply->setError("Invalid Username");
    $reply->SendOut();
    return;
}

if(!isset($password)) {
    $reply->SetError("Empty Password");
    $reply->SendOut();
    return;
}

// Validate username and password from DB
$tbPlayer = new TbPlayer();
$tbPlayer->setUserName($username);
if(!$tbPlayer->loadFromExistFields()) {
    // No such user
    $reply->SetError("Invalid Username or Password");
    $reply->SendOut();
    return;
}

if(strcmp($tbPlayer->getPassword(), $password) != 0) {
    $reply->SetError("Invalid Username or Password");
    $reply->SendOut();
    return;
}

// password validation success, return user info to client
$reply->SetContent("userId", $tbPlayer->getUserId());
$reply->SetContent("serverIp", SOCKET_IP);
$reply->SetContent("serverPort", SOCKET_PORT);
$reply->SetContent("sessionKey", "hlikeilslked");
$reply->SendOut();


