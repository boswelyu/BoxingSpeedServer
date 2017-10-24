<?php
/**
 * User: YuBo
 * Date: 2017/10/24
 * Time: 9:53
 */

require_once(__DIR__ . "/../../config.php");
require_once($GLOBALS["SERVER_ROOT"] . "/webpage/module/WebMsg.php");
require_once($GLOBALS["SERVER_ROOT"] . "/dbtable/TbPlayerBasic.php");

$postdata = $GLOBALS['HTTP_RAW_POST_DATA'];

$reply = new WebMsg(WebMsg::LOGIN_REPLY);

$request = json_decode($postdata, true);

$userId = $request["userId"];
if(empty($userId)) {
    $reply->SetError(WebMsg::ECODE_INVALID_USERID, WebMsg::EINFO_INVALID_USERID);
    $reply->SendOut();
    return;
}

$tbPlayerBasic = new TbPlayerBasic();
$tbPlayerBasic->setUserId($userId);
if(!$tbPlayerBasic->load()) {
    $reply->SetError(WebMsg::ECODE_INVALID_USERID, WebMsg::EINFO_INVALID_USERID);
    $reply->SendOut();
    return;
}

$type = $request["type"];
if(empty($type)) {
    $reply->SetError(WebMsg::ECODE_EMPTY_UPLOAD_TYPE, WebMsg::EINFO_EMPTY_UPLOAD_TYPE);
    $reply->SendOut();
    return;
}

$imageData = base64_decode($request["image"]);
if(empty($imageData)) {
    $reply->SetError(WebMsg::ECODE_INVALID_IMAGE, WebMsg::EINFO_INVALID_IMAGE);
    $reply->SendOut();
    return;
}

$folderPath = $GLOBALS["SERVER_ROOT"] . "/webpage/upload/$userId";
if(!file_exists($folderPath)) {
    mkdir($folderPath, 0755, true);
}

$imageFileName = date("YmdHis", time()) . ".png";

$imageFullName = $folderPath . "/" . $imageFileName;
$imageFile = fopen($imageFullName, "w");

if(!fwrite($imageFile, $imageData))
{
    $reply->SetError(WebMsg::ECODE_CREATE_FILE_FAILED, WebMsg::EINFO_CREATE_FILE_FAILED);
    $reply->SendOut();
    return;
}

fclose($imageFile);

// 生成图片的文件链接，更新到用户数据表中
$imageUrl = "http://" . SOCKET_IP . "/upload/$userId/$imageFileName";
$tbPlayerBasic->setAvatarUrl($imageUrl);
$tbPlayerBasic->save();

// 文件保存成功，返回Reply消息
$reply->SendOut();


