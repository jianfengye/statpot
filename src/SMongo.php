<?php

// 全局Mongo类
Class SMongo
{
    private static $_mongo = null;
    private static $_mongoConfig = array();

    private function __construct() {

    }

    // 生成mongo实例
    public static function genInstance($mongoConfig) {
        $server = "mongodb://{$mongoConfig['host']}:{$mongoConfig['port']}";
        $options = array(
            'db' => $mongoConfig['db'],
            'connectTimeoutMS' => empty($mongoConfig['timeout']) ? 3000 : $mongoConfig['timeout'],
        );

        if (!empty($mongoConfig['username'])) {
            $options['username'] = $mongoConfig['username'];
        }

        if (!empty($mongoConfig['password'])) {
            $options['password'] = $mongoConfig['password'];
        }

        $class = 'MongoClient'; 
        if(!class_exists($class)){ 
            $class = 'Mongo'; 
        }

        $db = $mongoConfig['db'];
        self::$_mongo = new $class($server, $options);
        self::$_mongo = self::$_mongo->selectDB($db);
        self::$_mongoConfig = $mongoConfig;
    }

    // 获取mongo实例
    public static function getInstance() {
        if (is_null(self::$_mongo)) {
            self::genInstance(self::$_mongoConfig);
        }
        return self::$_mongo;
    }
}