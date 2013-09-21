<?php

// 配置文件，这里允许使用宏 ROOT_PATH

return array(
    'mongo' => array(
        'host' => 'localhost',
        'port' => '27017', 
        'db' => 'feedback',
        'collection' => 'feedbacks',
        'timeout' => 3
    ),
    
    'statpot' => ROOT_PATH . '/app/statpot.json',
);