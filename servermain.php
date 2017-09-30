<?php
use Workerman\Worker;
use Workerman\WebServer;
require_once __DIR__ . '/workerman/Autoloader.php';
require_once __DIR__ . '/webworker/WebProcessor.php';

// Create web page
$webserver = new WebServer("http://0.0.0.0:80");
$webserver->addRoot("www.tealcode.com", '/home/centos/bsserver/webpage');
$webserver->count = 2;

// 创建一个Worker监听7788端口，使用Socket通信
$httpworker = new Worker("tcp://0.0.0.0:7788");
Worker::$stdoutFile = "/home/centos/bsserver/log/worker.log";
// 启动4个进程对外提供服务
$httpworker->count = 4;

// 接收到浏览器发送的数据时回复hello world给浏览器
$httpworker->onMessage = function($connection, $data)
{
    // 向浏览器发送hello world
    WebProcessor::ProcessMessage($connection, $data);
};

// 运行worker
Worker::runAll();
