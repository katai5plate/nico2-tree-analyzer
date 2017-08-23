<meta name="robots" content="noindex,nofollow,noarchive" />
<?php 
    header('Content-Type: text/html; charset=UTF-8');

    function xp($url,$path){
        $html = file_get_contents($url);
        $html = str_replace('data-reactroot','data-reactroot=""',$html);
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8');
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        @$dom->loadHTML($html);
        $domXPath = new DOMXPath($dom);

        try{
            for($i=0;$i<count($path);$i+=3){
                $xpath = $path[$i+2];
                $xpath_record = $domXPath->evaluate($xpath);
                if (is_string($xpath_record)){
                    $result = $xpath_record;
                } else {
                    foreach ($xpath_record as $node){
                        $result = $node->textContent;
                    }
                }
                $output = str_replace("'", '', var_export($result,true));
                if($path[$i+1]=="text"){
                    $path[$i+2] = $output;
                }
                if($path[$i+1]=="image"){
                    $path[$i+2] = "<a href=\"" . $url . "\"><img src=\"" . $output . "\" alt=\"" . $path[$i] . " \"height=\"100\"></a>";
                }
                if($path[$i+1]=="ex"){
                    $path[$i+2] = "<br><br>" . print_r($xpath_record) . "<br>";
                }
            }
            echo "<tr>";
            for($i=0;$i<count($path);$i+=3){
                echo "<td>" . $path[$i+2] . "</td>";
            }
            echo "</tr>";
            
        } catch(Exception $e){
            echo 'ERROR： ',  $e->getMessage(), "<br>";
        }
    }

    //?id[]=gm1234&id[]=gm5678&id[]=gm9012 ...
    /*パズル*/
    //?id[]=gm3788&id[]=gm2785&id[]=gm1901&id[]=gm588&id[]=gm2708&id[]=gm2895&id[]=gm2926&id[]=gm2484&id[]=gm2719&id[]=gm2292
    /*RPG*/
    //?id[]=gm27&id[]=gm3856&id[]=gm3796&id[]=gm3955&id[]=gm3022&id[]=gm3626&id[]=gm3666&id[]=gm3970&id[]=gm137&id[]=gm2674
    /*アクション*/
    //?id[]=gm4&id[]=gm2018&id[]=gm1053&id[]=gm2739&id[]=gm1441&id[]=gm3113&id[]=gm2525&id[]=gm3651&id[]=gm1524&id[]=gm3734
    /*ADV*/
    //?id[]=gm3917&id[]=gm3866&id[]=gm1057&id[]=gm2718&id[]=gm1838&id[]=gm2689&id[]=gm1025&id[]=gm3656&id[]=gm17&id[]=gm4

    $ids = $_GET["id"];

    if(is_array($ids)){
        echo "<table>";
        echo "<tr>";
        echo "<td>" . "サムネ" . "</td>";
        echo "<td>" . "タイトル" . "</td>";
        echo "<td>" . "親作品数" . "</td>";
        echo "<td>" . "子作品数" . "</td>";
        echo "</tr>";
        foreach($ids as $id){
            sleep(2);
            xp("http://commons.nicovideo.jp/tree/" . $id,[
                'サムネ','image','string(body/div[@id="Contents"]/div[@id="Column01"]/div[@class="tree-area round5"]/div[@class="inner"]/div[@id="NowBox"]/div[@class="item-list"]/ul/li[@class="item3"]/div[@class="thum"]/a/img/@src)',
                'タイトル','text','string(body/div[@id="Contents"]/div[@id="Column01"]/div[@class="tree-area round5"]/div[@class="inner"]/div[@id="NowBox"]/div[@class="item-list"]/ul/li[@class="item3"]/div[@class="dsc"]/a)',
                '親作品数','text','string(body/div[@id="Contents"]/div[@id="Column01"]/div[@class="tree-area round5"]/div[@class="inner"]/div[@id="ParentBox"]/h3/span[@class="num"])',
                '子作品数','text','string(body/div[@id="Contents"]/div[@id="Column01"]/div[@class="tree-area round5"]/div[@class="inner"]/div[@id="ChildBox"]/h3/span[@class="num"])',
                //'--------------','text','string(body/@id)',
            ]);
        }
        echo "</table>";
    }else{
        print_r($ids);
    }
    
    //'test','test','body/div[@id="app"]/div data-reactroot', ←なぜかdata-reactroot要素があるとdivが見えなくなる
    
    /*
    sleep(1);
    echo "<table>";
    xp("https://game.nicovideo.jp/atsumaru/games/gm2082",[
        '02','ex','body/div[@id="app"]',
        '02','ex','body/div[@id="app"]/div',
        '公開日時','text','string(body/div[@id="app"]/div[@data-reactroot=""]/div[@class="wrapper inner"]/div/div[@class="content inner cf"]/div[@class="main"]/div[@class="cf"]/div[@class="introduction secBox"]/div[@class="gameInfoBox cf"]/p[@class="data" and 1])',
        '更新日時','text','string(body/div[@id="app"]/div/div[@class="wrapper inner"]/div/div[@class="content inner cf"]/div[@class="main"]/div[@class="cf"]/div[@class="introduction secBox"]/div[@class="gameInfoBox cf"]/p[@class="data" and 2])',
    ]);
    echo "</table>";
    */
    

?>
<style>
table {
	border-collapse: collapse;
}
td {
	border: solid 1px;
	padding: 0.5em;
}
</style>