<?php
	if ( $server_path == null || strlen($server_path)==0 ) {
		$server_path = str_replace(basename(__FILE__), "", str_replace(basename(__FILE__), "", realpath(__FILE__)));
	}
	if ( $http_path == null || strlen($http_path)==0 ) {
		$http_path = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);
	}

	include('../../lib/config.php');
	$epiurl = str_replace("{toondtlid}",$_GET["ws_id"],$viewParam);
	$epiurl = str_replace("{toonid}",$_GET["wr_id"],$epiurl);

	$get_images = array();
	$url = $siteUrl.$viewUrl."?".$epiurl; //주소셋팅
	if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
		$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
	}
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

	$html_arr = explode("<title>", $result);
	$get_html_contents = str_get_html($result);

	foreach($get_html_contents->find('li.prev_href') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a.view_list_button') as $g){
				$next_url = $g->href;
				if ( startsWith($next_url, "http") == false && startsWith($next_url, "//") == false ) $next_url = substr($next_url, 1);
				$next_pos = explode("=",$next_url);
				$next_wspos = explode("&",$next_pos[2]);
				$next_epi = $next_wspos[0];
				$next_wr_id = $next_pos[3];
		}
	}
	if ( $next_wr_id != null && strlen($next_wr_id) > 0 ) {
		echo "Y";
//		echo "{result='Y', link='".$link."'}";
	} else {
		echo "N";
//		echo "{result='N', link=''}";
	}
?>
