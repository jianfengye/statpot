<?php

define("SHELL_ROOT_PATH", dirname(dirname(__FILE__)));

// 记录index.php生成的数据,并储存
echo "开始生成报告..." . PHP_EOL;
echo "..." . PHP_EOL;

ob_start();

include_once(SHELL_ROOT_PATH . "/web/index.php");

$html = ob_get_contents();

ob_end_clean();

$output = SHELL_ROOT_PATH . '/result/stat_' . date("Ymd") . '.html';

file_put_contents($output, $html);

echo "生成报告结束， 存放位置： {$output}" . PHP_EOL;