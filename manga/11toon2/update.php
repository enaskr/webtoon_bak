<?php
	header('Content-Type: text/html; charset=UTF-8');
?>
<html>
<head>
	<title>웹툰 주소 업데이트</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
	include('../../lib/common.php');
	include_once('./idna_convert.class.php');

	$target = "http://11toon1.com/";

	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 500){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}

	if ( strlen($get_html_contents) > 0 ) {
		$idx = 0;
		foreach($get_html_contents->find('li') as $e) {
			if ( $idx == 2 ) {
				$f = str_get_html($e->innertext);
				foreach($f->find('a') as $g) {
					$newurl = $g->href;
				}
			}
			$idx++;
		}
	}

	if ( strlen($newurl) > 0 ) {
		if ( endsWith($newurl,"/") == false ) $newurl = $newurl."/";
		$IDN = new idna_convert();
		$newurl = $IDN->encode($newurl);
		$webtoonDB->exec("UPDATE 'TOON_CONFIG' SET CONF_VALUE = '".$newurl."', REGDTIME = '".$thisTime."', UPDATE_YN='Y' WHERE CONF_NAME = '11toon2_url';");
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
