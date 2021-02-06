<?php
	include('../../lib/header.php');
?>
<div id='container'>
	<div class='item'>
		<dl>
<?php
	if($_GET['keyword'] != null){
?>
			<dt>마니코믹스 검색결과:<?php echo $_GET["keyword"]; ?></dt>
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
			Header("Location:./update.php"); 
		}
		$toonidx = 0;
		foreach($get_html_contents->find('div.imgframe') as $e) {
			if ( $toonidx < (int)$config["max_list"] ) {
				$term = "";
				$f = str_get_html($e->innertext);
				foreach($f->find('img') as $g){
					$img_link = $g->src;
				}
				foreach($f->find('a') as $g){
					$epilink = $g->href;
					if ( strpos($epilink, "bo_table=comics") == false ) {
						$isBreak = true;
						break;
					} else $isBreak = false;
					$wr_id = str_replace($siteUrl."/","",$epilink);
					$wr_id = str_replace($siteUrl,"",$wr_id);
					$wr_id = str_replace("./board.php?bo_table=comics&amp;wr_id=","",$wr_id);
					$wr_id = str_replace("./board.php?bo_table=comics&wr_id=","",$wr_id);
					$wr_id = str_replace("board.php?bo_table=comics&wr_id=","",$wr_id);
					$wr_id = str_replace("board.php?bo_table=comics&amp;wr_id=","",$wr_id);
					// ./board.php?bo_table=comics&wr_id=116029
				}
				if ( $isBreak ) break;
				foreach($f->find('div.list_info_title') as $g){
					$title = trim(strip_tags($g));
				}
				$idx = 0;
				foreach($f->find('div') as $g){
					if ( $idx==4 ) {
						$content = trim(strip_tags($g));
					}
					if ( $idx==5 ) {
						$chasu = trim(strip_tags($g));
					}
					$idx++;
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

				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."(".$chasu.")<br>".$content."<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
			} else {
				break;
			}
			$toonidx++;
		}
	} else {
?>
			<dt>마니코믹스 목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		// 업데이트 페이지( /bbs/board.php?bo_table=comics&sop=and&sst=wr_datetime&sod=desc&type=alphabet&page=1 )의 목록 가져오기
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
			if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) { // 사이트 접속 실패 시 주소 업데이트 페이지로 자동 이동
				Header("Location:./update.php"); 
			}
			$toonidx = 0;
			foreach($get_html_contents->find('div.imgframe') as $e) {
				if ( $toonidx < (int)$config["max_list"] ) {
					$term = "";
					$f = str_get_html($e->innertext);
					foreach($f->find('img') as $g){
						$img_link = $g->src;
					}
					foreach($f->find('a') as $g){
						$epilink = $g->href;
						$wr_id = str_replace($siteUrl."/","",$epilink);
						$wr_id = str_replace($siteUrl,"",$wr_id);
						$wr_id = str_replace("bbs/board.php?bo_table=comics&amp;wr_id=","",$wr_id);
						$wr_id = str_replace("&amp;sst=wr_datetime&amp;sod=desc&amp;sop=and&amp;page=1","",$wr_id);
						// https://many25.com/bbs/board.php?bo_table=comics&wr_id=116070&sst=wr_datetime&sod=desc&sop=and&page=1
					}
					foreach($f->find('div.list_info_title') as $g){
						$title = trim(strip_tags($g));
					}
					$idx = 0;
					foreach($f->find('div') as $g){
						if ( $idx==2 ) {
							$publish = trim(strip_tags($g));
						}
						if ( $idx==5 ) {
							$content = trim(strip_tags($g));
						}
						if ( $idx==6 ) {
							$chasu = trim(strip_tags($g));
						}
						$idx++;
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

					echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
					echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."(".$chasu.")[".$publish."]<br>".$content."<br>".$alreadyView."</a>\n";
					echo "</tr>\n";
				} else {
					break;
				}
				$toonidx++;
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
