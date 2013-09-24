statpot - make reports generating simply and easier

# statpot介绍
+ 基于配置文件快速生成统计分析报告
+ 基于mongodb的统计数据
+ 生成静态报告页面
+ 页面基于bootstrap
+ 统计图表基于highCharts
+ 可后续自行增加统计图表类型，易于扩展

# statpot环境要求
+ 已搭建mongo数据库并存储数据
+ 已安装php并安装了mongo.so扩展

# statpot目录结构

```bash
statpot
 | - app #配置文件
      |- server.php #服务器配置
      |- statpot.php #生成的页面相关配置
 | - example      #生成的示例，无用
 | - src   #statpot的代码文件库
 | - vender    #第三方库和资源
        |- Bootstrap #bootstrap资源，暂时无用
        |- Highchart #Highchart资源，暂时无用
 | - web     #网站入口
```

# statpot使用
## 配置statpot/app/ 目录下的配置文件

server.php
```bash
<?php

// 配置文件，这里允许使用宏 ROOT_PATH

return array(
    'mongo' => array(       //设置mongodb的相关配置
        'host' => 'localhost', 
        'port' => '27017', 
        'db' => 'feedback',
        'collection' => 'feedbacks'
    ),
    
    'statpot' => ROOT_PATH . '/app/statpot.php',   // 设置statpot的相关路径
);
```

statpot.php

```bash
<?php

return array(
    "请求成功失败比例" => array(
        "DNS Look CDN成功率" => array(
            "type" => "bool",
            "collection" => "feedbacks",
            "key" => "dnscdn.success",
        ),
        "DNS Look 非CDN成功率" => array(
            "type" => "bool",
            "collection" => "feedbacks",
            "key" => "dnsnocdn.success",
        ),
    ),
    "接口速度" => array(
        "动态页接口速度" => array(
            "type" => "int",
            "collection" => "feedbacks",
            "key" => "phpapi.speed",
        ),
        "静态页接口速度" => array(
            "type" => "int",
            "collection" => "feedbacks",
            "key" => "htmlapi.speed",
        )
    ),
);
```

## 生成页面
有两种方法
### 浏览器上查看
搭建php服务器环境，设置路径，并将访问路径定位到statpot/web/index.php。

通过浏览器访问statpot/web/index.php

### 生成静态页面
直接运行statpot/web.shell.php。

会在statpot/result/  文件夹下生成可在浏览器中打开的html文件。

#statpot.php配置文件说明
### 二级目录
statpot.php是个二级目录配置文件，对应生成页面的左边导航

### 字段含义
+ type - 生成的图表类型，现在支持bool/int, 分别对应bool类饼图/int的区间柱状图
+ collection - 对应的统计数据所在的mongo数据集中
+ key - 所需要生成的图表对应的mongo统计项
+ step - int类型特有，区间柱状图步长，如果没有设置默认为(max-min)/10

# License
[The MIT License (MIT)](http://opensource.org/licenses/MIT)

Copyright (c) 2013 Eric Zhang

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.