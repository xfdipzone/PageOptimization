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