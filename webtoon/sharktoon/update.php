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
/*
	$target = "https://linktong1.com/bbs/board.php?bo_table=webtoon&wr_id=50";

	$get_html_contents = file_get_html($target);

	if ( strlen($get_html_contents) > 0 ) {
		$strpos = explode('<table border="1" style="width:100%;">',$get_html_contents);
		$strpos2 = explode('</table>',$strpos[1]);
		$newstr = $strpos2[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	}
*/

	// 샤크툰(SHARKTOON)
	$newurl = "";
	$urlstr = str_replace("https://www.sharktoon","",$siteUrl["SHARKTOON"]);
	$urlstr = str_replace(".com","",$urlstr);
	$urlstr = str_replace("/","",$urlstr);
	$urlnum = (int)$urlstr;
	for($i=$urlnum;$i < $urlnum+5;$i++){
		$base_urlt = "https://www.sharktoon".$i.".com/웹툰";
		$base_url = "https://www.sharktoon".$i.".com";
		$get_html_contents = file_get_html($base_urlt);
		if ( strlen($get_html_contents) > 0 ) {
			foreach($get_html_contents->find('meta') as $e){
				if($e->property == "og:url"){
					$newurl = $base_url;
					break;
				}
			}
			break;
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
		alert("주소를 업데이트하지 못하였습니다.");
		window.location.href="<?php echo $homeurl; ?>";
	</script>
<?php
	}
?>
</body>
</html>

