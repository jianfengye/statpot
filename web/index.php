<?php

// 检测有没有安装mongo扩展
if (!extension_loaded('mongo')) {
    if (!dl('mongo.so')) {
        throw new Exception("Need install mongo.so extension");
    }
}

// 检测php版本是不是大于等于5.3
$phpversion = explode('.', PHP_VERSION);
if ($phpversion[0] < 5 || $phpversion[1] < 2) {
    throw new Exception("PHP version need bigger than 5.3");
}

define("ROOT_PATH", dirname(dirname(__FILE__)));

date_default_timezone_set("PRC");

// AutoLoad
function __autoload($className) {
	if (substr($className, 0, 1) == "S") {
		$path = ROOT_PATH . '/src/';
	}
	require_once("{$path}/{$className}.php");
}

// 读取statpot.json
$serverConfig = require_once(ROOT_PATH . "/app/server.php");

SMongo::genInstance($serverConfig['mongo']);

$statpot = require_once($serverConfig['statpot']);
$report = new SReport($statpot);

// 渲染html页面
echo $report->html();