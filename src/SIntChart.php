<?php

// 柱状图
// 图表示例见：http://www.highcharts.com/demo/column-basic
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
            $this->key => array('$exists' => true)
        );

        $minSort = array(
            $this->key => 1
        );

        $maxSort = array(
            $this->key => -1
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
                $this->key => array(
                    '$gte' => $low,
                    '$lt' => $high,
                ),
            );
            $count = $collection->count($countOption);
            $stepCounts[] = array(
                'min' => $low,
                'max' => $high,
                'count' => $count,
            );
            $low = $high;
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

    public function html($chart_id, $option = array())
    {
        // 生成统计数据
        $this->stat();

        // 生成线性图
        $html = '<script type="text/javascript">
            (function($){
                      $(function () {
                    $("#{{$chart_id}}").highcharts({
                        chart: {
                            type: "column"
                        },
                        title: {
                            text: "{{$chart_title}}"
                        },
                        xAxis: {
                            categories: [
                                {{$chart_xAxis}}
                            ]
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: "次数"
                            }
                        },
                        tooltip: {
                            headerFormat: \'<span style="font-size:10px">{point.key}</span><table>\',
                            pointFormat: \'<tr><td style="color:{series.color};padding:0">{series.name}: </td>\' +
                                \'<td style="padding:0"><b>{point.y}</b></td></tr>\',
                            footerFormat: "</table>",
                            shared: true,
                            useHTML: true
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: "{{$chart_title}}",
                            data: [{{$chart_data}}]
                
                        }]
                    });
                });
            })(jQuery);
            </script>
            <div id="{{$chart_id}}" style="min-width: 210px; height: 300px; margin: 0 auto"></div>';

        //计算chart_xAxis和chart_data
        $chart_xAxis = '';
        $chart_data = array();
        foreach ($this->stepCounts as $item) {
            $chart_xAxis .= sprintf('"%.2f-%.2f",', $item['min'], $item['max']);
            $chart_data[] = $item['count'];
        }

        $chart_xAxis = trim($chart_xAxis, ',');
        $chart_data = implode(',', $chart_data);

        $chart_title = empty($option['chart_title']) ? '分布表' : $option['chart_title'];
        $search = array('{{$chart_id}}', '{{$chart_title}}', '{{$chart_xAxis}}', '{{$chart_data}}');
        $replace = array($chart_id, $chart_title, $chart_xAxis, $chart_data);
        return str_replace($search, $replace, $html);
    }
}