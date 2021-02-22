<?php
	include('../../lib/config.php');

	$urlstr = str_replace("https://newtoki","",$siteUrl);
	$urlstr = str_replace(".com","",$urlstr);
	$urlstr = str_replace("/","",$urlstr);
	$urlnum = (int)$urlstr;
	for($i=$urlnum;$i < $urlnum+5;$i++){
		$base_url = "https://newtoki".$i.".com/";
		$get_html_contents = file_get_html($base_url);
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
		if ( endsWith($newurl,"/") == false ) $newurl = $newurl."/";
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
		window.location.href="<?= $homeurl ?>";
	</script>
<?php
	}
?>
</body>
</html>

