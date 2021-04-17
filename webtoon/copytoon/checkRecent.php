<?php
	if ( $server_path == null || strlen($server_path)==0 ) {
		$server_path = str_replace(basename(__FILE__), "", str_replace(basename(__FILE__), "", realpath(__FILE__)));
	}
	if ( $http_path == null || strlen($http_path)==0 ) {
		$http_path = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);
	}

	include('../../lib/config.php');
	$epiurl = str_replace("{toondtlid}",$_GET["ws_id"],$viewUrl);

	$url = $siteUrl.$epiurl; //�ּҼ���
	$ch = curl_init(); //curl �ε�
	curl_setopt($ch, CURLOPT_URL,$url); //curl�� url ����
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // �� ������ 1�� �����ϴ� ���� ���Űǰ��� ����
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36');
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl ���� �� ����� ����
	for($html_c = 0; $html_c < (int)$config["try_count"]; $html_c++){
		if(strlen($result) > 10000){
			break;
		} else {
			$result = curl_exec($ch);
		}
	}
	curl_close ($ch); // curl ����
	$get_html_contents = str_get_html($result);

	foreach($get_html_contents->find('section#bo_v_atc') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('div') as $g){
			if ( $g->getAttribute("data-placement")=="left" ) {
				$h = str_get_html($g->innertext);
				foreach($h->find('a') as $i){
					$next_url = $i->href;
					$nextpos = explode("/", $next_url);
					if ( startsWith($next_url, "http") == false ) { 
						$next_url = $siteUrl.$next_url;
						$next_epi = $nextpos[1];
					} else {
						$next_epi = $nextpos[3];
					}
				}
			}
		}
	}

	$link = "view.php?title=".urlencode($_GET["title"])."&wr_id=".urlencode($_GET["wr_id"])."&ws_id=".urlencode($next_epi);
	$link2 = "view.php?title=".urlencode($_GET["title"])."&wr_id=".urlencode($_GET["wr_id"])."&ws_id=".urlencode($_GET["ws_id"]);
	if ( $next_epi != null && strlen($next_epi) > 0 ) {
		echo "Y|".$link;
//		echo "{result='Y', link='".$link."'}";
	} else {
		echo "N|".$link2;
//		echo "{result='N', link=''}";
	}
?>
