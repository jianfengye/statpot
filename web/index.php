<?php

// TODO: 检测有没有安装mongo扩展
// TODO: 检测php版本是不是大于等于5.3

define("ROOT_PATH", dirname(dirname("__FILE__")));

date_default_timezone_set("PRC");

// AutoLoad
function __autoload($className) {
	if (substr($className, 0, 1) == "S") {
		$path = ROOT_PATH . '/src/';
	}
	require_once("{$path}/{$className}.php");
}

// 读取statpot.json

// 获取mongo数据

// 渲染html页面