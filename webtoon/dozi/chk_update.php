<?php
	$homepath = '/share/webtoon/';
	$webhookURL = "https://discordapp.com/api/webhooks/925920773091229767/X0QxbZ3A9QSeSJ4xQDoCCb-GmH14kSQC6w2h-ZlWflnny8GUdAm9VV7HMwTajDbMkINi";
	$MBR_NO = "20210210039265";
	$SITE_ID = "TOONKOR";

	include($homepath.'lib/config_cli.php');
?>
<?php
	function chkRecent($SiteId, $ToonId, $EpiId, $siteUrl, $viewUrl) {
		$epiurl = str_replace("{toondtlid}",$EpiId,$viewUrl);
		$result = false;
		$url = $siteUrl.$epiurl; //주소셋팅
		$next_epi = "";

		$ch = curl_init(); //curl 로딩
		curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Forwarded-For: 175.112.89.171"));
		curl_setopt($ch, CURLOPT_TIMEOUT,3000);
		$result = curl_exec($ch); // curl 실행 및 결과값 저장
		for($html_c = 0; $html_c < 5; $html_c++){
			if(strlen($result) > 10000){
				break;
			} else {
				$result = curl_exec($ch);
			}
		}
		curl_close ($ch); // curl 종료
		$get_html_contents = str_get_html($result);


		foreach($get_html_contents->find('div.view-wrap') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('div[class="btn-resource btn-next at-tip"]') as $g){
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

		if ( $next_epi != null && strlen($next_epi) > 0 ) {
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

?>
<?php
	$isAlreadyView = "SELECT UV.MBR_NO AS MBR_NO, UV.SITE_ID AS SITE_ID, UV.TOON_ID AS TOON_ID, UV.TOON_TITLE AS TOON_TITLE, UV.TOON_THUMBNAIL AS TOON_THUMBNAIL, UVD.VIEW_ID AS VIEW_ID, UVD.VIEW_TITLE AS VIEW_TITLE, UVD.UPTDTIME AS UPTDTIME FROM USER_VIEW UV, USER_VIEW_DTL UVD, ";
	$isAlreadyView = $isAlreadyView." ( SELECT UV1.MBR_NO AS MBR_NO, UV1.SITE_ID AS SITE_ID, UV1.TOON_ID AS TOON_ID, MAX(UVD1.UPTDTIME) AS UPTDTIME ";
	$isAlreadyView = $isAlreadyView." FROM USER_VIEW UV1, USER_VIEW_DTL UVD1 ";
	$isAlreadyView = $isAlreadyView." WHERE UV1.MBR_NO = UVD1.MBR_NO AND UV1.SITE_ID = UVD1.SITE_ID AND UV1.TOON_ID = UVD1.TOON_ID ";
	$isAlreadyView = $isAlreadyView." GROUP BY UV1.MBR_NO, UV1.SITE_ID, UV1.TOON_ID) UVV ";
	$isAlreadyView = $isAlreadyView." WHERE UV.MBR_NO = '".$MBR_NO."' AND UV.SITE_ID = '".$siteId."' AND UV.USE_YN='Y' ";
	$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVD.MBR_NO AND UV.SITE_ID = UVD.SITE_ID AND UV.TOON_ID = UVD.TOON_ID ";
	$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVV.MBR_NO AND UV.SITE_ID = UVV.SITE_ID AND UV.TOON_ID = UVV.TOON_ID AND UVD.UPTDTIME = UVV.UPTDTIME ";
	$isAlreadyView = $isAlreadyView." ORDER BY UV.TOON_TITLE ASC, UVD.UPTDTIME DESC;";
	$webtoonView = $webtoonDB->query($isAlreadyView);
	$viewDate = "";
	$alreadView = "";
	$idx = 0;
	$recentToons = "";
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$toonid = $row["TOON_ID"];
		$toontitle = $row["TOON_TITLE"];
		$epiid = $row["VIEW_ID"];
		$recentResult = chkRecent($siteId,$toonid,$epiid, $siteUrl, $viewUrl);
		if ( $recentResult ) {
			$recentToons = $recentToons.$toontitle."\n";
		}
		$idx++;
	}
	if ( strlen($recentToons) > 1 ) {
		send_discord($webhookURL, "툰코[TOONKOR] 웹툰이 업데이트되었습니다.", "Jackie Choi", "툰코[TOONKOR] 웹툰 업데이트 내역", $recentToons, "http://armtoon.enas.kr/webtoon/toonkor/", "3366ff");
	}
?>
