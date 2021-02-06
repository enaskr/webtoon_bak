<?php
	include('../../lib/header.php');
?>
<div id='container'>
	<div class='item'>
		<dl>
<?php
	if($_GET['keyword'] != null){
?>
			<dt><?php echo $siteName; ?> 검색결과:<?php echo $_GET["keyword"]; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$url = $siteUrl.$searchUrl."?".str_replace("{keyword}",urlencode($_GET['keyword']),$searchParam);
		if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
			$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
		}
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

		foreach($get_html_contents->find('div.list-row') as $e){
			
			if ( $loopcnt > (int)$config["max_list"] ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->getAttribute("src");
			}
			foreach($f->find('a') as $g){
				if ( $g->getAttribute("title") != null ) $subject = $g->title;
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
				$toonstr = explode("/", $target_link);
				$toonid = $toonstr[count($toonstr)-1];
				break;
			}
			foreach($f->find('div') as $e){
				if ( $e->getAttribute("class") == "post-text post-ko txt-short ellipsis no-margin deepblue" ) $genre = trim(strip_tags($e));
			}
			foreach($f->find('span.list-date') as $e){
				$publish = trim(strip_tags($e));
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

			echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$toonid." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
			echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$toonid.">".$subject."(".$publish.")<br>[".$genre."]<br>".$alreadyView."</a>\n";
			echo "</tr>\n";
			$loopcnt++;
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
		if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
			$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
		}
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

		if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {  // 사이트 접속 실패 시 주소 업데이트 페이지로 자동 이동
			Header("Location:./update.php"); 
		}

		foreach($get_html_contents->find('div.list-row') as $e){
			
			if ( $loopcnt > (int)$config["max_list"] ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->getAttribute("src");
			}
			foreach($f->find('a') as $g){
				if ( $g->getAttribute("title") != null ) $subject = $g->title;
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
				$toonstr = explode("/", $target_link);
				$toonid = $toonstr[count($toonstr)-1];
				break;
			}
			foreach($f->find('div') as $e){
				if ( $e->getAttribute("class") == "post-text post-ko txt-short ellipsis no-margin deepblue" ) $genre = trim(strip_tags($e));
			}
			foreach($f->find('span.list-date') as $e){
				$publish = trim(strip_tags($e));
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

			echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$toonid." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
			echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$toonid.">".$subject."(".$publish.")<br>[".$genre."]<br>".$alreadyView."</a>\n";
			echo "</tr>\n";
			$loopcnt++;
		}
	} else {
?>
			<dt><?php echo $siteName; ?> 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$url = $siteUrl.$endedUrl."?".$endedParam;
		if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
			$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
		}
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

		foreach($get_html_contents->find('div.list-row') as $e){
			
			if ( $loopcnt > (int)$config["max_list"] ) break;
			$term = "";
			$adult = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$img_link = $g->getAttribute("src");
			}
			foreach($f->find('a') as $g){
				if ( $g->getAttribute("title") != null ) $subject = $g->title;
			}
			foreach($f->find('a') as $g){
				$target_link = $g->href;
				if ( startsWith($target_link, "/") == true ) $target_link = substr($target_link, 1);
				$toonstr = explode("/", $target_link);
				$toonid = $toonstr[count($toonstr)-1];
				break;
			}
			foreach($f->find('div') as $e){
				if ( $e->getAttribute("class") == "post-text post-ko txt-short ellipsis no-margin deepblue" ) $genre = trim(strip_tags($e));
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

			echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$toonid." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
			echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".$toonid."&wr_id=".urlencode($target_link).">".$subject."(완결)<br>[".$genre."]<br>".$alreadyView."</a>\n";
			echo "</tr>\n";
			$loopcnt++;
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
