<?php

class SIntChart extends SChart
{
    // 参数数据存储
    private $step;

    // 解析后数据存储
    private $min;
    private $max;
    private $stepCounts;  // 每一段的计数

    function __construct($config)
    {
        parent::__construct($config);
        // 解析子类特有的属性
        if (!empty($config['step'])) {
            $this->step = $config['step'];
        }
    }

    private function stat()
    {
        // 解析数据
        $mongo = SMongo::getInstance();
        $collection = $mongo->{$this->collection};

        // 获取最小值和最大值
        $findOption = array(
            {$this->key} => array('$exists' => true)
        );

        $minSort = array(
            {$this->key} => 1
        );

        $maxSort = array(
            {$this->key} => -1
        );

        $minFeedback = $collection->find($findOption)->sort($minSort)->limit(1)->getNext();
        // TODO：这个方法有点挫，可以改进
        $this->min = $this->getField($minFeedback, $this->key);

        $maxFeedback = $collection->find($findOption)->sort($maxSort)->limit(1)->getNext();
        $this->max = $this->getField($maxFeedback, $this->key);

        if (empty($this->step)) {
            $this->step = ($this->max - $this->min) / 10;
        }

        $low = $this->min;
        $high = $low;
        $stepCounts = array();
        while ($high <= $this->max) {
            $high = $low + $this->step;
            $countOption = array(
                {$this->kind} => array(
                    '$gte' => $low,
                    '$lt' => $high,
                ),
            );
            $count = $collection->count($countOption);
            $stepCounts[] = array(
                'min' => $min,
                'max' => $max,
                'count' => $count,
            );
        }
        $this->stepCounts = $stepCounts;
    }

    private function getField($data, $field)
    {
        $fields = explode('.', $field);
        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                return null;
            }
            $data = $data[$field];
        }
        return $data;
    }

    pubilc function html()
    {
        // TODO: 生成HTML
    }
}