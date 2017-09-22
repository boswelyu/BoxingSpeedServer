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

    const MSG_ERROR = "status";
    const MSG_NAME = "message";

    private $stateArray = array("status" => "OK");
    public function __construct($msgname)
    {
        if(isset($msgname)) {
            $this->stateArray["message"] = $msgname;
        }
    }

    public function SetError($error)
    {
        if(isset($error)) {
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
