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
	$target = $newtoki_url."notice/7754";

	$url = $siteUrl."/notice/7754";
	if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
		$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
	}

	$ch = curl_init(); //curl 로딩
	curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl 실행 및 결과값 저장
	curl_close ($ch); // curl 종료
	$get_html_contents = str_get_html($result);

	if ( strlen($get_html_contents) > 1000 ) {
		$strpos = explode('<div itemprop="description" class="view-content">',$get_html_contents);
		$strpos2 = explode('<span style="color:#333333;">웹툰</span>',$strpos[1]);
		$strpos3 = explode('<font color="#333333" style="color:#333333;">&nbsp;일본만화</font>',$strpos2[1]); 
		$newstr = $strpos3[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	} else {
*/
		$urlstr = str_replace("https://manatoki","",$siteUrl);
		$urlstr = str_replace(".net","",$urlstr);
		$urlstr = str_replace("/","",$urlstr);
		$urlnum = (int)$urlstr;

		for($i=$urlnum;$i < $urlnum+5;$i++){
			$base_url = "https://manatoki".$i.".net/";
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

//	}

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
		window.location.href="<?php echo $homeurl; ?>";
	</script>
<?php
	}
?>
</body>
</html>

