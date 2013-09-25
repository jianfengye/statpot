<?php

return array(
	"请求成功失败比例" => array(
		"DNS Look CDN成功率" => array(
			"type" => "SEnumChart",
			"title" => "DNS Look CDN",
			"collection" => "feedbacks",
			"key" => "dns_lookupcdn.success",
			"enum_show" => array(
				'1' => '成功',
				'0' => '失败',
				null => '无数据'
			),
		),
		"DNS Look 非CDN成功率" => array(
			"type" => "SEnumChart",
			"collection" => "feedbacks",
			"key" => "dns_lookupnocdn.success",
		),
	),
	"接口速度" => array(
		"动态页接口速度" => array(
			"type" => "SIntChart",
			"collection" => "feedbacks",
			"key" => "apiphp.speed",
		),
		"静态页接口速度" => array(
			"type" => "SIntChart",
			"collection" => "feedbacks",
			"key" => "apihtml.speed",
		)
	),
);