<?php
//获取数据，正常情况应该读取db，演示只用数组代替
function getData($id){

    // 数据
    $data = array(
        1 => '很久很久以前，在某个地方，有个很可爱的女孩。不幸的是，她父母双亡，而且家徒四壁，屋里除了一张可供歇息的床以外，就别无长物了。除此之外，她全身上下只剩下身上所穿的衣裳，还有一片好心人施舍给她有面包而已。这个小姑娘，是个心地善良、信心坚定的好女孩。不论境遇有多么凄惨不幸，她仍然深信，慈爱的上帝会默默地庇护着她。',
        2 => '有一天，她只身上原野上去玩。她走着走着，忽然遇到一个衣衫褴褛的男子。他向女孩哀求道：“求求你!施舍一点东西给我这个可伶人吧!我实在是饿得快要受不了了啊!”听到这话，女孩便把自己仅有的那片面包拿出来，说道：“这是上帝的恩 典喔!”说完后， 女孩就继续上路了。走了一会儿，路旁突然出现了一个啼泣不已的男孩。“鸣——我的头好冷呀!就快冻僵了……你能不能施舍一点可以让我挡风的东西啊！”女孩便把自己头上那顶帽子脱了下来， 为男孩戴上。走了不久，她又碰到一个小孩。那孩子没有穿棉背心，冷得直打哆嗦。于是，好心的女孩便把自己的背心送给那个小孩。她继续往前走，突然又遇见另一个小孩。她再次答应对方的乞求，把上衣施舍给他。女孩再往前走，走进森林里。林深日尽，四周一下子变暗了起来。这时，又出现一个可怜的小男孩， 央求女孩把内衣脱给她。这个时候，虔诚又善良的女孩想：“现在天色已经暗下来了，任谁也看不清楚我的模样，就算脱掉内衣，应该无所谓吧！”因此，女孩脱下了内衣，送给乞讨的女孩。',
        3 => '这个时候的女孩，真的是浑身赤裸、再无他物了。忽然间，天上闪烁的星星纷纷坠落，落在女孩的面前。天啊!它们都化成了闪亮耀眼的金币——货真价实的金币!而原先一丝不挂的孩，不知什么时候，竟裹上了一套细致、上等的亚麻衫!于是，这个好心的女孩，把金币捡回家，从此过着富足、快乐的生活。'
    );

    $ret = '';

    // 判断是否搜索引擎机器人
    $is_robot = isRobot();

    // 搜索引擎机器人访问
    if($is_robot || defined('FORCE_SYNC_RESPONSE') && FORCE_SYNC_RESPONSE==true){

        $ret .= '<p>'.$data[$id].'</p>'.PHP_EOL;

    // 普通用户访问，异步请求
    }else{

        $ret = '<div id="result'.$id.'"></div>
        <script type="text/javascript">
        $(function() {
          $.get("api.php?id='.$id.'", {}, function(ret) {
            $("#result'.$id.'").html(ret["html"]);
          },"json");
        });
        </script>'.PHP_EOL.PHP_EOL;

    }

    return $ret;

}

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