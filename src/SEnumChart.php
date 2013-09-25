<?php

// Enum类型的图表
// 图表示例见：http://www.highcharts.com/demo/pie-basic
class SEnumChart extends SChart
{
    // 参数数据存储
    // 无

    // 解析后的数据存储
    private $group;

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

        // 使用group来解析数据
        $keys = array(
            $this->key => 1
        );

        $initial = array(
            "count" => 0,
        );

        $reduce = "function(cur,prev) { prev.count=prev.count+1; }";

        $group = $collection->group($keys, $initial, $reduce);
        $this->group = $group['retval'];

    }

    // 生成对应的HTML
    public function html($chart_id, $option = array())
    {
        $this->stat();
        // 生成饼图
        $html = '<script type="text/javascript">
(function($){
$(function () {
    $("#{{$chart_id}}").highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: "{{$chart_title}}"
        },
        tooltip: {
          pointFormat: "{series.name}: <b>{point.percentage:.1f}%<br/>{point.y}</b>"
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: "pointer",
                dataLabels: {
                    enabled: true,
                    color: "#000000",
                    connectorColor: "#000000",
                    format: "<b>{point.name}</b>: {point.percentage:.1f} %"
                }
            }
        },
        series: [{
            type: "pie",
            name: "比例",
            data: [
                {{$chart_data}}
            ]
        }]
    });
});
})(jQuery);
</script>
<div id="{{$chart_id}}" style="min-width: 210px; height: 300px; margin: 0 auto"></div>';

        //计算chart_data
        $chart_data = '';
        $enum_show = empty($option['enum_show']) ? array() : $option['enum_show'];

        foreach ($this->group as $item) {
            $val = $item[$this->key];
            $count = $item['count'];

            if (isset($enum_show[$val])) {
                $val = $enum_show[$val];
            }

            // 增加说明
            if ($val == null) {
                $val = 'null';
            }

            $chart_data .= "['{$val}', {$count}],";
        }

        $chart_data = trim($chart_data, ',');

        $chart_title = empty($option['title']) ? '比率表' : $option['title'];
        $search = array('{{$chart_id}}', '{{$chart_title}}', '{{$chart_data}}');
        $replace = array($chart_id, $chart_title, $chart_data);
        return str_replace($search, $replace, $html);
    }
}