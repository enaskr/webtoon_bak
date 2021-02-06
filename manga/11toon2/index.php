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
		$url = $siteUrl.$searchUrl."?".str_replace("{keyword}",$_GET['keyword'],$searchParam);
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
//			Header("Location:./update.php"); 
		}
		$toonidx = 0;
		foreach($get_html_contents->find('ul#library-recents-list') as $e) {
			if ( $toonidx < (int)$config["max_list"] ) {
				$term = "";
				$f = str_get_html($e->innertext);
				$img_link = "";
				$subject = "";
				$wr_id = "";
				$target_link = "";
				$targeturl = "";
				foreach($f->find('li') as $g){
					$h = str_get_html($g->innertext);
					foreach($h->find('div.homelist-thumb') as $i){
						$img_link = $i->style;
						$img_link = str_replace("background-image: url('","",$img_link);
						$img_link = str_replace("');","",$img_link);
					}
					foreach($h->find('div.homelist-title') as $i){
						$subject = trim(strip_tags($i));
					}
					$wr_id = $g->getAttribute("data-id");

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

					if ( $img_link != null && strlen($img_link) > 0 ) {
						echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
						echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>".$alreadyView."</a>\n";
						echo "</tr>\n";
					}
				}
			} else {
				break;
			}
			$toonidx++;
		}
	} else {
			if ( $_GET["end"] != "END" ) {
?>
			<dt><?php echo $siteName; ?> 연재목록</dt> 
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 1; $p++) {
					$url = $siteUrl.$recentUrl."?".str_replace("{page}",$p,$recentParam);
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
					$toonidx = 0;
					foreach($get_html_contents->find('div.homelist-wrap') as $e) {
						if ( $toonidx < (int)$config["max_list"] ) {
							$term = "";
							$f = str_get_html($e->innertext);
							foreach($f->find('li') as $g){
								$subject = "";
								$h = str_get_html($g->innertext);
								foreach($h->find('div.homelist-thumb') as $i){
									$img_link = $i->style;
									$img_link = str_replace("background-image: url('","",$img_link);
									$img_link = str_replace("');","",$img_link);
								}
								foreach($h->find('a') as $i){
									$target_link = $i->href;
									$targetpos = explode("=",$target_link);
									$wr_id = $targetpos[3];
									break;
								}
								foreach($h->find('div.homelist-title') as $i){
									$subject = trim(strip_tags($i));
								}
								foreach($h->find('div.homelist-genre') as $i){
									$genre = trim(strip_tags($i));
									$genre = substr($genre,0,strlen($genre)-3);
								}
								if ( $subject != null && strlen($subject) > 0 ) {

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

									echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
									echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
									echo "</tr>\n";

								}
							}
						} else {
							break;
						}
						$toonidx++;
					}
				}
			} else {
?>
			<dt><?php echo $siteName; ?> 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 7; $p++) {
					$url = $siteUrl.$endedUrl."?".str_replace("{page}",$p,$endedParam);
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
			//			Header("Location:./update.php"); 
					}
					$toonidx = 0;
					foreach($get_html_contents->find('div.homelist-wrap') as $e) {
						if ( $toonidx < (int)$config["max_list"] ) {
							$term = "";
							$f = str_get_html($e->innertext);
							foreach($f->find('li') as $g){
								$h = str_get_html($g->innertext);
								foreach($h->find('div.homelist-thumb') as $i){
									$img_link = $i->style;
									$img_link = str_replace("background-image: url('","",$img_link);
									$img_link = str_replace("');","",$img_link);
								}
								foreach($h->find('a') as $i){
									$target_link = $i->href;
									$targetpos = explode("=",$target_link);
									$wr_id = $targetpos[3];
									break;
								}
								foreach($h->find('div.homelist-title') as $i){
									$subject = trim(strip_tags($i));
								}
								foreach($h->find('div.homelist-genre') as $i){
									$genre = trim(strip_tags($i));
									$genre = substr($genre,0,strlen($genre)-3);
								}

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

								echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
								echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>[".$genre."]<br>".$alreadyView."</a>\n";
								echo "</tr>\n";

							}
						} else {
							break;
						}
						$toonidx++;
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
