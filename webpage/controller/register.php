<?php
/**
 * Created by PhpStorm.
 * User: boswell
 * Date: 10/17/2017
 * Time: 18:01 PM
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
$method = $request["regMethod"];

if($method == "email") {
    emailRegister($reply, $username, $password);
}else if($method == "phone") {
    phoneRegister($reply, $username, $password);
}

// 邮箱或用户名注册
function emailRegister(WebMsg $replyMsg, $username, $password)
{
    if(!isset($username)) {
        $replyMsg->setError(WebMsg::ECODE_INVALID_USER, WebMsg::EINFO_INVALID_USER);
        $replyMsg->SendOut();
        return;
    }

    if(!isset($password)) {
        $replyMsg->SetError(WebMsg::ECODE_INVALID_PWD, WebMsg::EINFO_INVALID_PWD);
        $replyMsg->SendOut();
        return;
    }

    // Validate username and password from DB
    $tbPlayer = new TbPlayerLogin();
    $tbPlayer->setUserName($username);
    if($tbPlayer->loadFromExistFields()) {
        // username duplex
        $replyMsg->SetError(WebMsg::ECODE_DUPLEX_USER, WebMsg::EINFO_DUPLEX_USER);
        $replyMsg->SendOut();
        return;
    }

    // username valid success, insert into database
    $tbPlayer->setPassword($password);
    $currTime = time();
    $sessionKey = substr(md5("$username-$currTime"), 8, -8);
    $tbPlayer->setSessionKey($sessionKey);
    $tbPlayer->setRegTime(date("Y-m-d H:i:s", $currTime));

    if(!$tbPlayer->insertOrUpdate()) {
        $replyMsg->SetError(WebMsg::ECODE_CREATE_FAILED, WebMsg::EINFO_CREATE_FAILED);
        $replyMsg->SendOut();
        return;
    }

    // Create PlayerBasic table in database
    $tbPlayerBasic = new TbPlayerBasic();
    $tbPlayerBasic->setUserId($tbPlayer->getUserId());
    $tbPlayerBasic->setGender(-1);
    if(!$tbPlayerBasic->insertOrUpdate()) {
        $replyMsg->SetError(WebMsg::ECODE_CREATE_FAILED, WebMsg::EINFO_CREATE_FAILED);
        $replyMsg->SendOut();
        return;
    }

    // Create player success,
    $replyMsg->SetContent("userId", $tbPlayer->getUserId());
    $replyMsg->SetContent("serverIp", SOCKET_IP);
    $replyMsg->SetContent("serverPort", SOCKET_PORT);
    $replyMsg->SetContent("sessionKey", $tbPlayer->getSessionKey());
    $replyMsg->SendOut();
}

// 用手机号码注册
function phoneRegister(WebMsg $replyMsg, $phoneNum, $passCode)
{
    if(!isset($phoneNum)) {
        $replyMsg->SetError(WebMsg::ECODE_INVALID_USER, WebMsg::EINFO_INVALID_USER);
        $replyMsg->SendOut();
        return;
    }

    if(!isset($passCode)) {
        $replyMsg->SetError(WebMsg::ECODE_CREATE_FAILED, WebMsg::EINFO_CREATE_FAILED);
        $replyMsg->SendOut();
        return;
    }

    $tbPlayerLogin = new TbPlayerLogin();
    $tbPlayerLogin->setPhoneNumber($phoneNum);
    if($tbPlayerLogin->loadFromExistFields()) {
        $replyMsg->SetError(WebMsg::ECODE_PHONE_DUPLEX, WebMsg::EINFO_PHONE_DUPLEX);
        $replyMsg->SendOut();
        return;
    }

    // TODO: 调用SMS接口进行验证码检验
    $smsapi = "https://webapi.sms.mob.com/sms/verify";
    $appkey = "21e1ede9448b0";
    $response = postRequest( $smsapi, array(
    	'appkey' => $appkey,
        'phone' => $phoneNum,
        'zone' => '86',
    	'code' => $passCode,
    ) );

    $responseObj = json_decode($response, true);
    if($responseObj["status"] != 200) {
        $replyMsg->SetError(WebMsg::ECODE_PASSCODE_FAIL, WebMsg::EINFO_PASSCODE_FAIL);
        $replyMsg->SendOut();
        return;
    }

    $currTime = time();
    $sessionKey = substr(md5("$phoneNum-$currTime"), 8, -8);
    $tbPlayerLogin->setSessionKey($sessionKey);
    $tbPlayerLogin->setRegTime(date("Y-m-d H:i:s", $currTime));

    // 验证成功，尝试注册该手机号
    if(!$tbPlayerLogin->insertOrUpdate()) {
        $replyMsg->SetError(WebMsg::ECODE_CREATE_FAILED, WebMsg::EINFO_CREATE_FAILED);
        $replyMsg->SendOut();
        return;
    }

    $userId = $tbPlayerLogin->getUserId();
    $tbPlayeBasic = new TbPlayerBasic();
    $tbPlayeBasic->setUserId($userId);
    $tbPlayeBasic->setGender(-1);
    if(!$tbPlayeBasic->insertOrUpdate()) {
        $replyMsg->SetError(WebMsg::ECODE_CREATE_FAILED, WebMsg::EINFO_CREATE_FAILED);
        $replyMsg->SendOut();
        return;
    }

    $replyMsg->SetContent("userId", $userId);
    $replyMsg->SetContent("serverIp", SOCKET_IP);
    $replyMsg->SetContent("serverPort", SOCKET_PORT);
    $replyMsg->SetContent("sessionKey", $tbPlayerLogin->getSessionKey());
    $replyMsg->SendOut();

}

function postRequest( $api, array $params = array(), $timeout = 30 ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api );
	// 以返回的形式接收信息
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	// 设置为POST方式
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
	// 不验证https证书
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
		'Accept: application/json',
	) );
	// 发送数据
	$response = curl_exec( $ch );
	// 不要忘记释放资源
	curl_close( $ch );
	return $response;
}