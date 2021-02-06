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

	$newurl = "";
	$urlstr = str_replace("https://protoon","",$siteUrl);
	$urlstr = str_replace(".com","",$urlstr);
	$urlstr = str_replace("/","",$urlstr);
	$urlnum = (int)$urlstr;
	echo "PROTOON ::: urlnum = ".$urlnum."\n";
	for($i=$urlnum;$i < $urlnum+5;$i++){
		$base_url = "https://protoon".$i.".com/";
		$get_html_contents = file_get_html($base_url);
		if ( strlen($get_html_contents) > 0 ) {
	echo "PROTOON ::: I = ".$i."\n";
			foreach($get_html_contents->find('meta') as $e){
	echo "PROTOON ::: meta OK\n";
				if($e->property == "og:url"){
	echo "PROTOON ::: og:url ok\n";
					$newurl = $base_url;
	echo "PROTOON ::: newurl = ".$newurl."\n";
					break;
				}
			}
	echo "PROTOON ::: i = ".$i." ::: newurl = ".$newurl."\n";
			if ( strlen($newurl) > 0 ) { break; }
		}
	}

	if ( strlen($newurl) > 0 ) {
		if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".$thisTime."', UPDATE_YN='Y' WHERE SITE_ID = '".$siteId."';");
?>
	<script type="text/javascript">
		alert("주소를 성공적으로 업데이트했습니다.");
		history.back();
	</script>
<?php
	} else {
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '".$siteId."';");
?>
	<script type="text/javascript">
		alert("주소 업데이트에 실패했습니다.");
		window.location.href="<?php echo $homeurl; ?>";
	</script>
<?php
	}
?>
</body>
</html>

