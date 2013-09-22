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
    public function html($chart_id, $option = array())
    {
        $this->stat();
        // 生成饼图
        $html = '<script type="text/javascript">
(function($){
$(function () {
    $("{{$chart_id}}").highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: "{{$chart_title}}"
        },
        tooltip: {
          pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
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
<div id="{{$chart_id}}" style="min-width: 310px; height: 400px; margin: 0 auto"></div>';

        //计算chart_data
        $chart_data = "['成功' : {$this->trueCount}], ['失败' : {$this->falseCount}],['无值' : {$this->noneCount}],";
        $chart_title = empty($option['chart_title']) ? '比率表' : $option['chart_title'];
        $search = array('{{$chart_id}}', '{{$chart_title}}', '{{$chart_data}}');
        $replace = array($chart_id, $chart_title, $chart_data);
        return str_replace($search, $replace, $html);
    }
}