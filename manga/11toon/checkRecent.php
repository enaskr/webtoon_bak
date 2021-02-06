<?php
	if ( $server_path == null || strlen($server_path)==0 ) {
		$server_path = str_replace(basename(__FILE__), "", str_replace(basename(__FILE__), "", realpath(__FILE__)));
	}
	if ( $http_path == null || strlen($http_path)==0 ) {
		$http_path = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);
	}

	include('../../lib/config.php');
	$epiParam = str_replace("{toondtlid}",$_GET["ws_id"],$viewParam);
	$epiParam = str_replace("{toonid}",$_GET["wr_id"],$epiParam);

	$url = $siteUrl.$viewUrl."?".$epiParam; //주소셋팅
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

	foreach($get_html_contents->find('ul.inline-list') as $e){
		$f = str_get_html($e->innertext);
		$idx=0;
		foreach($f->find('button') as $g){
			if ( $idx==1 ) {
				if ( $g->onclick != null ) {
					$nexturl = $g->onclick;
					$nexturl = str_replace("location.href='./board.php?bo_table=toons&amp;wr_id=","",$nexturl);
					$nextpos = explode("&",$nexturl);
					$nextwsis = $nextpos[0];
				}
			}
			$idx++;
		}
	}
	if ( $nextwsis != null && strlen($nextwsis) > 0 ) {
		echo "Y";
//		echo "{result='Y', link='".$link."'}";
	} else {
		echo "N";
//		echo "{result='N', link=''}";
	}
?>
