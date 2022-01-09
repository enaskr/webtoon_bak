<html>
<head>
	<title>웹툰 주소 업데이트</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	header('Content-Type: text/html; charset=UTF-8');
	if ( $server_path == null || strlen($server_path)==0 ) {
		$server_path = str_replace(basename(__FILE__), "", str_replace(basename(__FILE__), "", realpath(__FILE__)));
	}
	if ( $http_path == null || strlen($http_path)==0 ) {
		$http_path = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);
	}

	include('../../lib/config.php');
	// 도지코믹스 (DOZI)
	$target = "https://linkzip02.link/webtoon/17498";
	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}

	if ( strlen($get_html_contents) > 0 ) {
		foreach($get_html_contents->find('div.document_17498_452') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('u') as $g){
				$newurl = $g->innertext;
				break;
			}
		}
	}

	if ( strlen($newurl) > 10 ) {
		if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
		$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".$thisTime."', UPDATE_YN='Y' WHERE SITE_ID = '".$siteId."';");
?>
	<script type="text/javascript">
		alert("주소를 성공적으로 업데이트했습니다.");
		history.back();
	</script>
<?php
	} else {
		$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '".$siteId."';");
?>
	<script type="text/javascript">
		alert("주소를 업데이트하지 못하였습니다.");
		window.location.href="<?php echo $homeurl; ?>";
	</script>
<?php
	}
?>
</body>
</html>

