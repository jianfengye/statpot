<?php

// 这个类和一个报告相互对应
class SReport
{
    // 二级目录Array存储多个表单
    private $charts;

    function __construct($statpot) {
        $charts = $statpot;

        // 解析配置文件
        foreach ($statpot as $title => $stats) {
            foreach ($stats as $subtitle => $stat) {
                switch ($stat['type']) {
                    case 'bool':
                        $charts[$title][$subtitle]['chartObj'] = new SBoolChart($stat);
                        break;
                    case 'int':
                        $charts[$title][$subtitle]['chartObj'] = new SIntChart($stat);
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
        // 生成HTML
        $html = '<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>统计 &middot; 报表</title>

  <!-- Bootstrap core CSS -->
  <link href="http://v3.bootcss.com/dist/css/bootstrap.css" rel="stylesheet">

  <!-- Documentation extras -->
  <link href="http://v3.bootcss.com/assets/css/docs.css" rel="stylesheet">
  <link href="http://v3.bootcss.com/assets/highlight/css/github.css" rel="stylesheet">

  <script src="http://v3.bootcss.com/assets/js/jquery.js"></script>
</head>
<body>

  <!-- Docs master nav -->
  <header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
    <div class="container">
      <div class="navbar-header">
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a href="#" class="navbar-brand">统计报表</a>
      </div>
    </div>
  </header>
            <!-- Callout for the old docs link -->
  <div class="container bs-docs-container">
    <div class="row">
      <div class="col-md-3">
        <div class="bs-sidebar hidden-print" role="complementary">
          <ul class="nav bs-sidenav">';

        // 生成左侧导航
        foreach ($this->charts as $title => $stats) {
            $title_id = md5($title);
            $temp = '<li>
              <a href="#%s">%s</a><ul class="nav">';
            $html .= sprintf($temp, $title_id, $title);

            foreach ($stats as $subtitle => $stat) {
                $subtitle_id = md5($title . $subtitle);
                $temp = '<li>
                  <a href="#%s">%s</a>
                </li>';
                $html .= sprintf($temp, $subtitle_id, $subtitle);
            }
            $html .= '</ul>
            </li>';
          }
          $html .= '</ul>
        </div>
      </div>';

        $html .= '<div class="col-md-9" role="main">';

        // 输出表格
        foreach ($this->charts as $title => $stats) {
            $title_id = md5($title);
            $temp = '<div class="bs-docs-section">
          <div class="page-header">
            <h1 id="%s">%s</h1>
          </div>';
            $html .= sprintf($temp, $title_id, $title);

            foreach ($stats as $subtitle => $stat) {
                $subtitle_id = md5($title . $subtitle);
                $temp = '<h3>%s</h3>%s';

                // 获取子类的分类
                $option = array(
                    'title' => $subtitle,
                );

                $chart_html = $stat['chartObj']->html($subtitle_id, $option);
                $html .= sprintf($temp, $subtitle, $chart_html);
            }
            $html .= '</div>';
        }

        $html .= '</div>
    </div>
  </div>

  <!-- Footer
    ================================================== -->
  <footer class="bs-footer" role="contentinfo">
    <div class="container">

      <p>
        Designed and built with statpot by
        <a href="http://weibo.com/yjf10" target="_blank">@jianfengye110</a>
        .
      </p>
      <p>
        Code licensed under
        <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License v2.0</a>
        , documentation under
        <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>
        .
      </p>
    </div>
  </footer>

  <!-- JS and analytics only. -->
  <!-- Bootstrap core JavaScript
================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <script src="http://code.highcharts.com/highcharts.js"></script>
  <script src="http://code.highcharts.com/modules/exporting.js"></script>
          
  <script src="http://v3.bootcss.com/dist/js/bootstrap.js"></script>

  <script src="http://v3.bootcss.com/assets/js/application.js"></script>

</body>
</html>';

        return $html;
    }
}