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
			<dt><a href="<?php echo $siteUrl; ?>"><?php echo $siteName; ?></a> 검색결과:<?php echo $keywordstr; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$url = $siteUrl.$searchUrl."?".str_replace("{keyword}",$keywordstr,$searchParam);
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

//		if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) { // 사이트 접속 실패 시 주소 업데이트 페이지로 자동 이동
//			Header("Location:./update.php"); 
//		}

		$toonidx = 0;
		foreach($get_html_contents->find('div.list-row') as $e) {
			if ( $toonidx < (int)$config["max_list"] ) {
				$term = "";
				$f = str_get_html($e->innertext);
				$img_idx = 0;
				foreach($f->find('img') as $g){
					if ( $img_idx == 0 ) {
						$img_link = $g->src;
						if ( startsWith($img_link,"http") == false ) $img_link = $siteUrl.$img_link;
						break;
					}
				}
				foreach($f->find('a') as $g){
					$epilink = $g->href;
					$title = $g->getAttribute("alt");
					$wr_id = str_replace("/webtoon/","",$epilink);
					break;
				}
				foreach($f->find('div.toon_gen') as $g){
					$genre = trim(strip_tags($g));
					break;
				}
				foreach($f->find('span.pull-right') as $g){
					$type = trim(strip_tags($g));
					$type = str_replace("	","",$type);
					$type = str_replace(" ","",$type);
					echo "<script type='text/javascript'>console.log('TYPE=$type');</script>";
					break;
				}

				if ( $type == "[웹툰]" ) {
					$isAlreadyView = "SELECT MBR_NO, SITE_ID, TOON_ID, UPTDTIME FROM USER_VIEW ";
					$isAlreadyView = $isAlreadyView." WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$wr_id."' AND USE_YN='Y' ";
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

					echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
					echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br>".$genre."<br>".$alreadyView."</a>\n";
					echo "</tr>\n";
				}
			} else {
				break;
			}
			$toonidx++;
		}
	} else {
		if ( $end != "END" ) {
?>
			<dt><a href="<?php echo $siteUrl; ?>"><?php echo $siteName; ?></a> 연재목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		// 업데이트 페이지( /bbs/board.php?bo_table=comics&sop=and&sst=wr_datetime&sod=desc&type=alphabet&page=1 )의 목록 가져오기
		for($p = 1; $p <= 1; $p++) {
			$url = $siteUrl.$recentUrl;
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
				$g = $f->find('img')[0];
				$img_link = $g->src;
				
				$g = $f->find('div.section-item-inner')[0];
				$subject = $g->alt;
				echo "<script type='text/javascript'>console.log('$subject');</script>";
				
				foreach($f->find('a') as $g){
					$target_link = $g->href;
					$target_link = str_replace($siteUrl, "", $target_link);
					$target_link = str_replace("/webtoon/", "", $target_link);
					break;
				}
				
				$g = $f->find('div.toon_gen')[0];
				$genre = trim(strip_tags($g));
				
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

				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link)." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".urlencode($target_link).">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
				$loopcnt++;
			}
		}
	} else {
?>
			<dt><a href="<?php echo $siteUrl; ?>"><?php echo $siteName; ?></a> 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		// 업데이트 페이지( /bbs/board.php?bo_table=comics&sop=and&sst=wr_datetime&sod=desc&type=alphabet&page=1 )의 목록 가져오기
		for($p = 1; $p <= 1; $p++) {
			$url = $siteUrl.$endedUrl;
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
				$g = $f->find('img')[0];
				$img_link = $g->src;
				
				$g = $f->find('div.section-item-inner')[0];
				$subject = $g->alt;
				echo "<script type='text/javascript'>console.log('$subject');</script>";
				
				foreach($f->find('a') as $g){
					$target_link = $g->href;
					$target_link = str_replace($siteUrl, "", $target_link);
					$target_link = str_replace("/webtoon/", "", $target_link);
					break;
				}
				
				$g = $f->find('div.toon_gen')[0];
				$genre = trim(strip_tags($g));
				
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
