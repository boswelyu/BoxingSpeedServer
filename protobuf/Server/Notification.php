<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: server.proto

namespace Server;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Server.Notification</code>
 */
class Notification extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>sint32 newFriends = 1;</code>
     */
    private $newFriends = 0;
    /**
     * Generated from protobuf field <code>sint32 newMessage = 2;</code>
     */
    private $newMessage = 0;
    /**
     * Generated from protobuf field <code>sint32 newFunction = 3;</code>
     */
    private $newFunction = 0;

    public function __construct() {
        \GPBMetadata\Server::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>sint32 newFriends = 1;</code>
     * @return int
     */
    public function getNewFriends()
    {
        return $this->newFriends;
    }

    /**
     * Generated from protobuf field <code>sint32 newFriends = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setNewFriends($var)
    {
        GPBUtil::checkInt32($var);
        $this->newFriends = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>sint32 newMessage = 2;</code>
     * @return int
     */
    public function getNewMessage()
    {
        return $this->newMessage;
    }

    /**
     * Generated from protobuf field <code>sint32 newMessage = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setNewMessage($var)
    {
        GPBUtil::checkInt32($var);
        $this->newMessage = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>sint32 newFunction = 3;</code>
     * @return int
     */
    public function getNewFunction()
    {
        return $this->newFunction;
    }

    /**
     * Generated from protobuf field <code>sint32 newFunction = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setNewFunction($var)
    {
        GPBUtil::checkInt32($var);
        $this->newFunction = $var;

        return $this;
    }

}

