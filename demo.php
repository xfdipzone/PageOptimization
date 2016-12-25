<?php
$url = 'http://localhost/index.php'; // 访问index.php
$header = array(
    //'user-agent: Googlebot' // 加入搜索引擎关键字，模拟搜索引擎机器人访问，屏蔽则为普通用户访问
);
$data = doCurl($url, array(), $header);
echo '<meta http-equiv="content-type" content="text/html;charset=utf-8">';
echo '<xmp>';
echo $data;
echo '</xmp>';

/**
 * curl请求
 * @param  String $url     请求地址
 * @param  Array  $data    请求参数
 * @param  Array  $header  请求header
 * @param  Int    $timeout 超时时间
 * @return String
 */
function doCurl($url, $data=array(), $header=array(), $timeout=30){  
  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

    $response = curl_exec($ch);  

    if($error=curl_error($ch)){  
        die($error);
    }  

    curl_close($ch);  

    return $response;
}  
?>