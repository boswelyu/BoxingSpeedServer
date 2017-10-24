<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 8/23/17
 * Time: 4:36 AM
 */

class WebMsg
{
    // Predefined message names
    const LOGIN_REPLY = "login_reply";

    // Message Fields
    const MSG_STATUS = "status";
    const MSG_ERROR = "errorInfo";
    const MSG_NAME = "message";

    // 登陆错误消息
    const ECODE_EMPTY = -1;
    const EINFO_EMPTY = "Invalid empty request";

    const ECODE_INVALID_USER = -2;
    const EINFO_INVALID_USER = "Invalid Username";

    const ECODE_INVALID_PWD = -3;
    const EINFO_INVALID_PWD = "Invalid Password";

    const ECODE_NO_USER = -4;
    const EINFO_NO_USER = "Invalid UserName or Password";

    const ECODE_PWD_MISMATCH = -5;
    const EINFO_PWD_MISMATCH = "Invalid Username or Password";

    // 注册错误消息
    const ECODE_DUPLEX_USER = -6;
    const EINFO_DUPLEX_USER = "Username Already Exist";

    const ECODE_CREATE_FAILED = -7;
    const EINFO_CREATE_FAILED = "Create User Failed";

    // 上传头像错误消息
    const ECODE_INVALID_USERID = -8;
    const EINFO_INVALID_USERID = "Invalid User ID";

    const ECODE_EMPTY_UPLOAD_TYPE = -9;
    const EINFO_EMPTY_UPLOAD_TYPE = "Invalid upload type";

    const ECODE_INVALID_IMAGE = -10;
    const EINFO_INVALID_IMAGE = "Invalid Image Data";

    const ECODE_CREATE_FILE_FAILED = -11;
    const EINFO_CREATE_FILE_FAILED = "Uploaded file failed to save";

    private $stateArray = array("status" => "0", "errorInfo" => "");
    public function __construct($msgname)
    {
        if(isset($msgname)) {
            $this->stateArray["message"] = $msgname;
        }
    }

    public function SetError($code, $error)
    {
        if(isset($error)) {
            $this->stateArray[self::MSG_STATUS] = $code;
            $this->stateArray[self::MSG_ERROR] = $error;
        }
    }

    public function SetContent($key, $value)
    {
        $this->stateArray[$key] = $value;
    }

    public function SendOut()
    {
        if(is_array($this->stateArray)) {
            echo json_encode($this->stateArray);
        }
    }
}
