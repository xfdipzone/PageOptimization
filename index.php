<?php
require 'server.php';

// 获取内容
$data1 = getData(1);
$data2 = getData(2);
$data3 = getData(3);

// 调用模版
include dirname(__FILE__).'/template.php';
?>