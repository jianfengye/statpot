<?php

return array(
    "请求成功失败比例" => array(
        "DNS Look CDN成功率" => array(
            "type" => "SEnumChart",
            "title" => "DNS Look CDN",
            "collection" => "feedbacks",
            "key" => "dnscdn.success",
            "enum_show" => array(
                '1' => '成功',
                '0' => '失败',
                null => '无数据'
            ),
        ),
        "DNS Look 非CDN成功率" => array(
            "type" => "SEnumChart",
            "collection" => "feedbacks",
            "key" => "dnsnocdn.success",
        ),
    ),
    "接口速度" => array(
        "动态页接口速度" => array(
            "type" => "SIntChart",
            "collection" => "feedbacks",
            "key" => "phpapi.speed",
            "title" => "动态接口速度",
            "y_show" => "Kb/s",
            "min" => 0,
            "max" => 100,
            "step" => 5,
        ),
        "静态页接口速度" => array(
            "type" => "SIntChart",
            "collection" => "feedbacks",
            "key" => "htmlapi.speed",
        )
    ),
);