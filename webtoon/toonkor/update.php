<?php
	ini_set('memory_limit','-1');
	include('../../lib/config.php');

	$urlstr = $siteUrl;
	$get_html_contents = file_get_html($urlstr);
	if ( strlen($get_html_contents) > 1000 ) {
		foreach($get_html_contents->find('meta') as $e){
			if($e->property == "og:url"){
				$newurl = $e->content;
				break;
			}
		}
	}

	$tempurl = explode('/' , $newurl);
	$newurl = $tempurl[0]."/".$tempurl[1]."/".$tempurl[2];

	if ( strlen($newurl) > 0 ) {
		$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".$thisTime."', UPDATE_YN='Y' WHERE SITE_ID = '".$siteId."';");
		echo $systemDB->lastErrorMsg();
?>
	<script type="text/javascript" >
		alert("주소를 성공적으로 업데이트했습니다.");
		history.back();
	</script>
<?php
	} else {
		$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '".$siteId."';");
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

