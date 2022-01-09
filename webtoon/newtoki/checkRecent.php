<?php
	include('../../lib/config.php');
	$epiurl = str_replace("{toondtlid}",$_GET["ws_id"],$viewUrl);

	$url = $siteUrl.$epiurl; //주소셋팅
	$ch = curl_init(); //curl 로딩
	curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36');
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl 실행 및 결과값 저장
	for($html_c = 0; $html_c < (int)$config["try_count"]; $html_c++){
		if(strlen($result) > 10000){
			break;
		} else {
			$result = curl_exec($ch);
		}
	}
	curl_close ($ch); // curl 종료
	$get_html_contents = str_get_html($result);
	$next_url = "";

	//echo $get_html_contents;
	// https://newtoki95.com/webtoon/13059818?toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0
	foreach($get_html_contents->find('a#goNextBtn') as $e){
		$next_url = $e->href;
		$next_url = str_replace("#next","",$next_url);

		$nextparse = explode('/' , $next_url);
		$tempparse = explode('?', $nextparse[4]);
		$next_url = $tempparse[0];
		break;
	}
	$link = "view.php?title=".urlencode($_GET["title"])."&wr_id=".urlencode($_GET["wr_id"])."&ws_id=".urlencode($next_url);
	$link2 = "view.php?title=".urlencode($_GET["title"])."&wr_id=".urlencode($_GET["wr_id"])."&ws_id=".urlencode($_GET["ws_id"]);
	if ( $next_url != null && strlen($next_url) > 0 ) {
		echo "Y|".$link;
//		echo "{result='Y', link='".$link."'}";
	} else {
		echo "N|".$link2;
//		echo "{result='N', link=''}";
	}
?>
