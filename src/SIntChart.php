<?php

class SIntChart extends SChart
{
    private $min;
    private $max;
    private $step;

    function __construct($config)
    {
        parent::__construct($config);
        // TODO: 解析子类特有的属性
    }

    pubilc function html()
    {
        // TODO: 生成HTML
    }
}