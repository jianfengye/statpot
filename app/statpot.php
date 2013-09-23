<?php

return array(
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