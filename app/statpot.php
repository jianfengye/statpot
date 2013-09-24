<?php

return array(
	"请求成功失败比例" => array(
		"DNS Look CDN成功率" => array(
			"type" => "bool",
			"collection" => "feedbacks",
			"key" => "dns_lookupcdn.success",
		),
		"DNS Look 非CDN成功率" => array(
			"type" => "bool",
			"collection" => "feedbacks",
			"key" => "dns_lookupnocdn.success",
		),
	),
	"接口速度" => array(
		"动态页接口速度" => array(
			"type" => "int",
			"collection" => "feedbacks",
			"key" => "apiphp.speed",
		),
		"静态页接口速度" => array(
			"type" => "int",
			"collection" => "feedbacks",
			"key" => "apihtml.speed",
		)
	),
);