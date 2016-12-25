<?php
define('FORCE_SYNC_RESPONSE', true); // 强制同步输出

require 'server.php';

// 获取分块内容
$id = isset($_GET['id'])? $_GET['id'] : 1;
$data = getData($id);

// 输出
header('content-type:application/json;charset=utf8');
$ret = json_encode(
    array(
        'html' => $data
    )
);

echo $ret;
?>