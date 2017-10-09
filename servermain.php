<?php
use Workerman\Worker;
use Workerman\WebServer;
require_once __DIR__ . '/workerman/Autoloader.php';
require_once __DIR__ . '/appworker/MessageProcessor.php';

// Create web page
$webserver = new WebServer("http://0.0.0.0:80");
$webserver->addRoot("www.tealcode.com", '/home/centos/bsserver/webpage');
$webserver->count = 2;

// 初始化MessageProcessor
MessageProcessor::InitService();

// 创建一个Worker监听7788端口，使用Socket通信
$worker = new Worker("tcp://0.0.0.0:7788");
Worker::$stdoutFile = "/home/centos/bsserver/log/worker.log";
// 启动4个进程对外提供服务
$worker->count = 4;

$worker->onMessage = function($connection, $data)
{
    MessageProcessor::ProcessMessage($connection, $data);
};

// 运行worker
Worker::runAll();
