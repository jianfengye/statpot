<?php

// 负责一个表单的基类
abstract class SChart 
{
    protected $type;
    protected $key;
    protected $collection;

    // 根据配置 array的构造函数
    function __construct($config) {
        // 生成type和key
        if (empty($config['type']) || empty($config['key']) || empty($config['collection'])) {
            throw new Exception("Statpot Config param error");
        }

        $this->type = $config['type'];
        $this->key = $config['key'];
        $this->collection = $config['collection'];
    }

    // 生成html页面
    abstract public function html($chart_id, $option = array());
}