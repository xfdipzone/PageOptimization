# PageOptimization
php输出页面的优化方法，兼容搜索引擎访问，并提供完整代码及测试。
<br>
使用php输出页面，如果页面的内容很多，用户需要等待页面所有内容加载后才可以看到页面内容，用户体验不好。
<br>
#1.页面输出优化方法
<br>
我们可以把页面内容分成几块，异步并发请求加载，当任何一块内容加载成功后即时显示，而不需要等待其他分块的内容加载。
<br>
这样只要有任何一块内容加载成功，用户都可以马上看到，提升用户体验。
<br>
因此页面中只需要保留js内容，使用ajax请求api加载内容显示。
<br><br>
```html
<!-- 分块1 -->
<div id="result1"></div>
<script type="text/javascript">
$(function() {
  $.get("api.php?id=1", {}, function(ret) {
    $("#result1").html(ret["html"]);
  },"json");
});
</script>

<!-- 分块2 -->
<div id="result2"></div>
<script type="text/javascript">
$(function() {
  $.get("api.php?id=2", {}, function(ret) {
    $("#result2").html(ret["html"]);
  },"json");
});
</script>

<!-- 分块3 -->
<div id="result3"></div>
<script type="text/javascript">
$(function() {
  $.get("api.php?id=3", {}, function(ret) {
    $("#result3").html(ret["html"]);
  },"json");
});
</script>
...
```
<br>
异步并发加载内容，可以大大加快页面输出速度。
<br>
<br>
#2.页面输出兼容搜索引擎
<br>
如果使用异步并发加载方式输出页面，对于搜索引擎不友好，搜索引擎会采集不到内容，因为内容都是使用ajax加载。
<br>
因此我们需要判断如果是搜索引擎机器人访问时，则直接输出页面内容，而不使用异步并发输出页面。
<br>
<br>
##判断是否搜索引擎机器人访问方法
```php
<?php
// 判断是否搜索引擎机器人访问
function isRobot() { 
    $agent= strtolower(isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : ''); 
    if(!empty($agent)){ 
        $spiderSite= array( 
            "TencentTraveler", 
            "Baiduspider+", 
            "BaiduGame", 
            "Googlebot", 
            "msnbot", 
            "Sosospider+", 
            "Sogou web spider", 
            "ia_archiver", 
            "Yahoo! Slurp", 
            "YoudaoBot", 
            "Yahoo Slurp", 
            "MSNBot", 
            "Java (Often spam bot)", 
            "BaiDuSpider", 
            "Voila", 
            "Yandex bot", 
            "BSpider", 
            "twiceler", 
            "Sogou Spider", 
            "Speedy Spider", 
            "Google AdSense", 
            "Heritrix", 
            "Python-urllib", 
            "Alexa (IA Archiver)", 
            "Ask", 
            "Exabot", 
            "Custo", 
            "OutfoxBot/YodaoBot", 
            "yacy", 
            "SurveyBot", 
            "legs", 
            "lwp-trivial", 
            "Nutch", 
            "StackRambler", 
            "The web archive (IA Archiver)", 
            "Perl tool", 
            "MJ12bot", 
            "Netcraft", 
            "MSIECrawler", 
            "WGet tools", 
            "larbin", 
            "Fish search", 
        ); 
        foreach($spiderSite as $val){ 
            $str = strtolower($val); 
            if(strpos($agent, $str) !== false){ 
                return true; 
            } 
        } 
    }

    return false; 
} 
?>
```

##测试正常用户访问
```php
<?php
$url = 'http://localhost/index.php'; // 访问index.php
$header = array();
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
```
输出页面内容<br><br>
```html
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8">
  <script src="jquery-1.11.0.min.js"></script>
  <title>内容模版</title>
 </head>

 <body>
    <div id="result1"></div>

        <script type="text/javascript">
        $(function() {
          $.get("api.php?id=1", {}, function(ret) {
            $("#result1").html(ret["html"]);
          },"json");
        });
        </script>
    <div id="result2"></div>

        <script type="text/javascript">
        $(function() {
          $.get("api.php?id=2", {}, function(ret) {
            $("#result2").html(ret["html"]);
          },"json");
        });
        </script>
    <div id="result3"></div>

        <script type="text/javascript">
        $(function() {
          $.get("api.php?id=3", {}, function(ret) {
            $("#result3").html(ret["html"]);
          },"json");
        });
        </script>
 </body>
</html>
```
使用了异步并发加载，提高页面输出速度。<br><br>

##测试搜索引擎机器人访问
```php
<?php
$url = 'http://localhost/index.php'; // 访问index.php
$header = array(
    'user-agent: Googlebot' // 加入搜索引擎关键字
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
```
输出页面内容
<br><br>
```html
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8">
  <script src="jquery-1.11.0.min.js"></script>
  <title>内容模版</title>
 </head>

 <body>
    <p>很久很久以前，在某个地方，有个很可爱的女孩。不幸的是，她父母双亡，而且家徒四壁，屋里除了一张可供歇息的床以外，就别无长物了。除此之外，她全身上下只剩下身上所穿的衣裳，还有一片好心人施舍给她有面包而已。这个小姑娘，是个心地善良、信心坚定的好女孩。不论境遇有多么凄惨不幸，她仍然深信，慈爱的上帝会默默地庇护着她。</p>
    <p>有一天，她只身上原野上去玩。她走着走着，忽然遇到一个衣衫褴褛的男子。他向女孩哀求道：“求求你!施舍一点东西给我这个可伶人吧!我实在是饿得快要受不了了啊!”听到这话，女孩便把自己仅有的那片面包拿出来，说道：“这是上帝的恩 典喔!”说完后， 女孩就继续上路了。走了一会儿，路旁突然出现了一个啼泣不已的男孩。“鸣——我的头好冷呀!就快冻僵了……你能不能施舍一点可以让我挡风的东西啊！”女孩便把自己头上那顶帽子脱了下来， 为男孩戴上。走了不久，她又碰到一个小孩。那孩子没有穿棉背心，冷得直打哆嗦。于是，好心的女孩便把自己的背心送给那个小孩。她继续往前走，突然又遇见另一个小孩。她再次答应对方的乞求，把上衣施舍给他。女孩再往前走，走进森林里。林深日尽，四周一下子变暗了起来。这时，又出现一个可怜的小男孩， 央求女孩把内衣脱给她。这个时候，虔诚又善良的女孩想：“现在天色已经暗下来了，任谁也看不清楚我的模样，就算脱掉内衣，应该无所谓吧！”因此，女孩脱下了内衣，送给乞讨的女孩。</p>
    <p>这个时候的女孩，真的是浑身赤裸、再无他物了。忽然间，天上闪烁的星星纷纷坠落，落在女孩的面前。天啊!它们都化成了闪亮耀眼的金币——货真价实的金币!而原先一丝不挂的孩，不知什么时候，竟裹上了一套细致、上等的亚麻衫!于是，这个好心的女孩，把金币捡回家，从此过着富足、快乐的生活。</p>
 </body>
</html>
```
<br>
页面内容直接输出，对搜索引擎友好。