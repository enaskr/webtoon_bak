<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
?>
<div id='container'>
	<div class='item'>
		<dl>
<?php
	if($keywordstr != null){
?>
			<dt><?php echo $siteName; ?> 검색결과:<?php echo $_GET["keyword"]; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$url = $siteUrl.$searchUrl."?".str_replace("{keyword}",$keywordstr,$searchParam);
		echo "<script type='text/javascript'>console.log('$url');</script>";
		$get_html_contents = file_get_html($url);
		for($html_c = 0; $html_c < (int)$config["try_count"]; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($url);
			}
		}

		if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) { // 사이트 접속 실패 시 주소 업데이트 페이지로 자동 이동
			Header("Location:./update.php"); 
		}

		foreach($get_html_contents->find('div.section-item') as $e){
			if ( $loopcnt > (int)$config["max_list"] ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->src;
			}
			foreach($f->find('h3') as $g){
				$subject = trim(strip_tags($g));
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
			}
			foreach($f->find('div.section-item-addon') as $e){
				$term = trim(strip_tags($e));
			}
			$termpos = explode(' ' , $term);
			$term = $termpos[0];
			foreach($f->find('div.toon_gen') as $e){
				$genre = trim(strip_tags($e));
			}
			foreach($f->find('div.toon-adult') as $e){
				$adult = "(".trim(strip_tags($e)).")";
			}

			$isAlreadyView = "SELECT MBR_NO, SITE_ID, TOON_ID, UPTDTIME FROM USER_VIEW ";
			$isAlreadyView = $isAlreadyView." WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$target_link."' AND USE_YN='Y' ";
			$isAlreadyView = $isAlreadyView." ORDER BY UPTDTIME DESC LIMIT 1;";
			$webtoonView = $webtoonDB->query($isAlreadyView);
			$viewDate = "";
			$alreadyView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$viewDate = $row["UPTDTIME"];
			}
			if ( strlen($viewDate) > 15 ) {
				$alreadyView = "<span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
			}

			if ( $is_adult || $adult != "(19)" ) {
				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link).">".$subject."<br>[".$term."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
				$loopcnt++;
			}
		}
} else {
	if ( $ends != "END" ) {
?>
			<dt><?php echo $siteName; ?> 연재목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php

		$url = $siteUrl.$recentUrl."?".$recentParam;
		echo "<script type='text/javascript'>console.log('$url');</script>";

		$ch = curl_init(); //curl 로딩
		curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36');
		curl_setopt($ch, CURLOPT_TIMEOUT,3000);
		$result = curl_exec($ch); // curl 실행 및 결과값 저장
		for($html_c = 0; $html_c < (int)$config["try_count"]; $html_c++){
			if(strlen($result) > 10000){
				break;
			} else {
				$result = curl_exec($ch);
			}
		}
		curl_close ($ch); // curl 종료
		
		$html_arr = explode('<ul class="homelist col-7">', $result);
		$weeknum = date('w');
		$weeknum = $weeknum + 1;
		$get_html_contents = str_get_html($html_arr[$weeknum]);

		if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {  // 사이트 접속 실패 시 주소 업데이트 페이지로 자동 이동
			Header("Location:./update.php"); 
		}

		foreach($get_html_contents->find('li') as $e){
			if($e->class == "section-item section-item-1 today-focus-item"){
				if ( $loopcnt > (int)$config["max_list"] ) break;

				$term = "";
				$adult = "";
				$f = str_get_html($e->innertext);
				foreach($f->find('img') as $g){
					$img_link = $siteUrl.$g->src;
				}
				foreach($f->find('h3') as $g){
					$subject = trim(strip_tags($g));
				}
				foreach($f->find('a') as $g){
					$target_link = $g->href;
					if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
				}
				foreach($f->find('div.section-item-addon') as $e){
					$term = trim(strip_tags($e));
				}
				$termpos = explode(' ' , $term);
				$term = $termpos[0];
				foreach($f->find('div.toon_gen') as $e){
					$genre = trim(strip_tags($e));
				}
				foreach($f->find('div.toon-adult') as $e){
					$adult = "(".trim(strip_tags($e)).")";
				}

				$isAlreadyView = "SELECT MBR_NO, SITE_ID, TOON_ID, UPTDTIME FROM USER_VIEW ";
				$isAlreadyView = $isAlreadyView." WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$target_link."' AND USE_YN='Y' ";
				$isAlreadyView = $isAlreadyView." ORDER BY UPTDTIME DESC LIMIT 1;";
				$webtoonView = $webtoonDB->query($isAlreadyView);
				$viewDate = "";
				$alreadyView = "";
				while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
					$viewDate = $row["UPTDTIME"];
				}
				if ( strlen($viewDate) > 15 ) {
					$alreadyView = "<span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
				}

				if ( $is_adult || $adult != "(19)" ) {
					echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
					echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link).">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
					echo "</tr>\n";
					$loopcnt++;
				}
			}
		}
	} else {
?>
			<dt><?php echo $siteName; ?> 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$url = $siteUrl.$endedUrl."?".$endedParam;
		echo "<script type='text/javascript'>console.log('$url');</script>";
		$get_html_contents = file_get_html($url);
		for($html_c = 0; $html_c < (int)$config["try_count"]; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($url);
			}
		}

		if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {
			Header("Location:./update.php"); 
		}

		foreach($get_html_contents->find('div.section-item') as $e){
			if ( $loopcnt > (int)$config["max_list"] ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->src;
			}
			foreach($f->find('h3') as $g){
				$subject = trim(strip_tags($g));
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
			}
			foreach($f->find('div.section-item-addon') as $e){
				$term = trim(strip_tags($e));
			}
			$termpos = explode(' ' , $term);
			$term = $termpos[0];
			foreach($f->find('div.toon_gen') as $e){
				$genre = trim(strip_tags($e));
			}
			foreach($f->find('div.toon-adult') as $e){
				$adult = "(".trim(strip_tags($e)).")";
			}

			$isAlreadyView = "SELECT MBR_NO, SITE_ID, TOON_ID, UPTDTIME FROM USER_VIEW ";
			$isAlreadyView = $isAlreadyView." WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$target_link."' AND USE_YN='Y' ";
			$isAlreadyView = $isAlreadyView." ORDER BY UPTDTIME DESC LIMIT 1;";
			$webtoonView = $webtoonDB->query($isAlreadyView);
			$viewDate = "";
			$alreadyView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$viewDate = $row["UPTDTIME"];
			}
			if ( strlen($viewDate) > 15 ) {
				$alreadyView = "<span style='font-size:11px; font-color:grey;'>[".$viewDate." viewed]</span>";
			}

			if ( $is_adult || $adult != "(19)" ) {
				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link).">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
				$loopcnt++;
			}
		}
	}
}
?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
		<!--// item : E -->
	</div>
</body>
</html>
