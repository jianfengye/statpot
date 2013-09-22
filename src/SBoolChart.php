<?php

// bool类型的图表
class SBoolChart extends SChart
{
    // 参数数据存储
    // 无

    // 解析后的数据存储
    private $trueCount;
    private $falseCount;
    private $noneCount;

    function __construct($config)
    {
        parent::__construct($config);
    }

    private function stat()
    {
        // 解析数据
        $mongo = SMongo::getInstance();
        $collection = $mongo->{$this->collection};

        $trueOption = array(
            {$this->key} => true
        );
        $this->trueCount = $collection->count($trueOption);

        $falseOption = array(
            {$this->key} => false
        );
        $this->falseCount = $collection->count($falseOption);
    
        $noneOption = array(
            {$this->key} => array(
                '$exists' => false
            ),
        );
        $this->noneCount = $collection->count($noneOption);
    }

    // 生成对应的HTML
    public function html()
    {
        $this->stat();
        // TODO
    }
}