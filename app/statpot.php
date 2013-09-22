<?php

return array(
	"请求成功失败比例" => array(
		"DNS Look CDN成功率" => array(
			"type" => "bool",
			"collection" => "feedbacks",
			"key" => "dns_lookupopenboxcdnmobilem360cn.success",
		),
		"DNS Look 非CDN成功率" => array(
			"type" => "bool",
			"collection" => "feedbacks",
			"key" => "dns_lookupopenboxmobilem360cn.success",
		),
	),
	"接口速度" => array(
		"动态页接口速度" => array(
			"type" => "int",
			"collection" => "feedbacks",
			"key" => "httpinewgetRecomendApps.speed",
		),
		"静态页接口速度" => array(
			"type" => "int",
			"collection" => "feedbacks",
			"key" => "httphtmllifeindexhtml.speed",
		),
		"apk接口速度" => array(
			"type" => "int",
			"collection" => "feedbacks",
			"key" => "httpapk.speed",
		),
	),
);