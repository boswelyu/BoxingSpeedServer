<?php

/**
 * Created by PhpStorm.
 * User: Boswell Yu
 * Date: 2017/10/6
 * Time: 23:20
 */
class XMessage
{
    var $raw_data;
    var $curr_pos;
    var $length;

    function XMessage()
    {
        $this->curr_pos = 0;
        $this->length = 0;
    }

    function setData($data) {
        $this->curr_pos = 0;
        $this->raw_data = $data;
        $this->length = strlen($this->raw_data);
    }

    function getLength()
    {
        return $this->length;
    }

    function readByte()
    {
        return ord($this->raw_data[$this->curr_pos++]);
    }

    function readInt16()
    {
        $bytes = substr($this->raw_data, $this->curr_pos, 2);
        $this->curr_pos += 2;

        $res = unpack("s", $bytes);
        return $res[1];
    }

    function readInt32()
    {
        $bytes = substr($this->raw_data, $this->curr_pos, 4);
        $this->curr_pos += 4;
        $res = unpack("i", $bytes);
        return $res[1];
    }

    function readBytes($length)
    {
        if($length <= 1) {
            $this->curr_pos += $length;
            return "";
        }

        $ret = substr($this->raw_data, $this->curr_pos, $length);
        $this->curr_pos += $length;
        return $ret;
    }

}