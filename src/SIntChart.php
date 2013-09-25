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

        if (empty($this->min)) {
            $minFeedback = $collection->find($findOption)->sort($minSort)->limit(1)->getNext();
            // TODO：这个方法有点挫，可以改进
            $this->min = $this->getField($minFeedback, $this->key);
        }
        
        if (empty($this->max)) {
            $maxFeedback = $collection->find($findOption)->sort($maxSort)->limit(1)->getNext();
            $this->max = $this->getField($maxFeedback, $this->key);
        }

        if (empty($this->step)) {
            $this->step = ($this->max - $this->min) / 10;
        }

        $low = $this->min;
        $high = $low;
        $stepCounts = array();
        while ($high <= $this->max) {
            $high = $low + $this->step;
            $stepCounts[] = array(
                'min' => $low,
                'max' => $high,
                'count' => 0,
            );
            $low = $high;
        }

        $option = array(
            $this->key => array(
                '$gte' => $this->min,
                '$lt' => $this->max,
            ),
        );
        $items = $collection->find($option);
        foreach ($items as $item) {
            foreach ($stepCounts as $key => $stepChart) {
                $keyval = $this->getField($item, $this->key);
                if ($stepChart['min'] <= $keyval && $stepChart['max'] > $keyval) {
                    $stepCounts[$key]['count']++;
                    break;
                }
            }
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
        // 如果有最小值和最大值
        if (!empty($option['min'])) {
            $this->min = $option['min'];
        }
        if (!empty($option['max'])) {
            $this->max = $option['max'];
        }
        if (!empty($option['step'])) {
            $this->step = $option['step'];
        }

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
                                text: "{{$chart_yshow}}"
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

        $chart_title = empty($option['title']) ? '分布表' : $option['title'];
        $chart_yshow = empty($option['y_show']) ? '次数' : $option['y_show'];
        $search = array('{{$chart_id}}', '{{$chart_title}}', '{{$chart_xAxis}}', '{{$chart_data}}', '{{$chart_yshow}}');
        $replace = array($chart_id, $chart_title, $chart_xAxis, $chart_data, $chart_yshow);
        return str_replace($search, $replace, $html);
    }
}