<?php
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

	foreach($get_html_contents->find('a#goNextBtn') as $e){
		$next_url = $e->href;
		$next_url = str_replace("#next","",$next_url);

		$nextparse = explode('/' , $next_url);
		$tempparse = explode('?', $nextparse[4]);
		$nexturl = $tempparse[0];
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
