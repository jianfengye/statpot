<?php

// 这个类和一个报告相互对应
class SReport
{
    // 二级目录Array存储多个表单
    private $charts;

    function __construct($statpot) {
        $charts = $statpot;

        // TODO: 解析配置文件
        foreach ($statpot as $title => $stats) {
            foreach ($stats as $subtitle => $stat) {
                switch ($stat['type']) {
                    case 'bool':
                        $charts[$title][$subtitle] = new SBoolChart($stat);
                        break;
                    case 'int':
                        $charts[$title][$subtitle] = new SIntChart($stat);
                        break;
                    default:
                        throw new Exception("Statpot Config type error");
                        break;
                }
            }
        }

        $this->charts = $charts;
    }

    public function html()
    {
        // TODO: 生成HTML
    }
}