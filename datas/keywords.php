<?php
$url = "http://affiliate-api.dmm.com/";
$apiid = "WvQxsAA42NzWgzzaUFxC";
$id = "dmmafiafiafi-990";
$xmlurl = $url."?api_id=".$apiid."&affiliate_id=".$id;
$opt = "&operation=ItemList&version=2.00&timestamp=2012-01-13%2014%3A08%3A16&site=DMM.co.jp&service=digital&floor=videoa&hits=100";

//検索種類:人気順
$sort = "&sort=rank";
$offset = "&offset=1";

$rss = $xmlurl.$opt.$sort.$offset;
$xml = simplexml_load_file($rss);


$filename = "./keywords_data.csv";
$outputdata = "[";

$blacklist = ["ハイビジョン","DVDトースター","独占配信","デビュー作品","単体作品","主観","ベスト・総集編","期間限定セール","AV OPEN 2015 企画部門","4時間以上作品","16時間以上作品","AV OPEN 2015 女優部門","スマホ推奨縦動画","AV OPEN 2015 素人部門","AV OPEN 2015 SM/ハード部門","AV OPEN 2014 ヘビー級","AV OPEN 2014 スーパーヘビー級","AV OPEN 2015 乙女部門","AV OPEN 2015 熟女部門","AV OPEN 2015 マニア/フェチ部門"];

for($i = 1;$i < 10001;$i = $i + 100){
//for($i = 1;$i < 100;$i = $i + 100){
    $offset = "&offset=".$i;
    $rss = $xmlurl.$opt.$sort.$offset;
    $xml = simplexml_load_file($rss);

    for($j = 0;$j < 100;$j++){
        echo "■";
        $data = $xml->result->items->item[$j]->iteminfo->keyword;

        for($k = 0;$k < count($data) ;$k++){
            echo $data[$k]->name;
            echo "<br>";

            $black = True;
            for($l = 0;$l < count($blacklist) ;$l++){
                if($data[$k]->name == $blacklist[$l])$black = False;
            }
            if($black)$outputdata = $outputdata.'"'.$data[$k]->name.'",';
        }

        echo "<br><br>";

    }

}

$outputdata = $outputdata.'""]';
$outputdata = str_replace("・", '","', $outputdata);
$outputdata = str_replace("系", '', $outputdata);
file_put_contents($filename,$outputdata);

?>
