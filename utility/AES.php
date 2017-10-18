<?php

/**
 * User: YuBo
 * Date: 2017/10/10
 * Time: 15:18
 */
class AES
{
    public static function Decrypt($userId, $msgdata)
    {
        $decryptData = $msgdata;
        $tbPlayerLogin = new TbPlayerLogin();
        $tbPlayerLogin->setUserId($userId);
        if($tbPlayerLogin->loadFromExistFields()) {
            $sessionKey = $tbPlayerLogin->getSessionKey();

            $iv = $sessionKey;
            $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
            if(mcrypt_generic_init($cipher, $sessionKey, $iv) != -1) {
                $decryptData = mdecrypt_generic($cipher, $msgdata);
                $decryptData = self::trimEnd($decryptData);
            }
        }
        return $decryptData;
    }

    private static function trimEnd($text){
    $len = strlen($text);
    $c = $text[$len-1];

    if(ord($c) < $len){
        for($i=$len-ord($c); $i<$len; $i++){
            if($text[$i] != $c)
            {
                return $text;
            }
        }
        return  substr($text, 0, $len-ord($c));
    }
    return $text;
}
}
