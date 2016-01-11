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


$filename = "./name.csv";
$outputdata = "";

for($i = 1;$i < 10001;$i = $i + 100){
//for($i = 1;$i < 100;$i = $i + 100){
    $offset = "&offset=".$i;
    $rss = $xmlurl.$opt.$sort.$offset;
    $xml = simplexml_load_file($rss);

    for($j = 0;$j < 100;$j++){
        echo "■";
        $data = $xml->result->items->item[$j]->iteminfo->actress;//->title;

        for($k = 0;$k < count($data) ;$k++){
//            var_dump($data[$k]);

            if(!strpos($data[$k]->id,"_ruby")){
                if(!strpos($data[$k]->id,"_classify")){
                    echo $data[$k]->name;
                    $outputdata = $outputdata.$data[$k]->name."\r\n";
                }
            }
        }

        echo $data;
        echo "<br><br>";


    }
}
file_put_contents($filename,$outputdata);

?>
