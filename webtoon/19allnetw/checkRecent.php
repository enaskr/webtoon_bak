<?php
	include('../../lib/config.php');
	$epiurl = str_replace("{toondtlid}",$_GET["ws_id"],$viewUrl);

	$get_images = array();
	$url = $siteUrl.$epiurl; //�ּҼ���
	if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
		$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
	}
	echo "<script type='text/javascript'>console.log('$url');</script>";
	echo "<script type='text/javascript'>console.log('THUMB=".$_COOKIE["THUMB"]."');</script>";
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

	foreach($get_html_contents->find('h1') as $e){
		$epititle = $e->content;
	}
	foreach($get_html_contents->find('a') as $e){
		if($e->getAttribute("data-original-title") == "<nobr>����ȭ</nobr>"){
			$next_url = $e->href;
			$nexturl = str_replace($base_url,"",$next_url);
			$nexturl = str_replace("./board.php?bo_table=comics&amp;wr_id=","",$nexturl);
			break;
		}
	}
	if ( $nexturl != null && strlen($nexturl) > 0 ) {
		echo "Y";
//		echo "{result='Y', link='".$link."'}";
	} else {
		echo "N";
//		echo "{result='N', link=''}";
	}
?>
