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
			<dt><?php echo $siteName; ?> 검색결과:<?php echo $keywordstr; ?></dt>
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
		$toonidx = 0;
		foreach($get_html_contents->find('div.item-card-gray') as $e) {
			if ( $toonidx < (int)$config["max_list"] ) {
				$term = "";
				$f = str_get_html($e->innertext);
				foreach($f->find('img') as $g){
					$img_link = $g->src;
					break;
				}
				foreach($f->find('a') as $g){
					$epilink = $g->href;
					$toonstr = explode("/",$epilink);
					if ( startsWith($epilink, "http") ) {
						$wr_id = $toonstr[5];
						break;
					} else {
						$wr_id = $toonstr[3];
						break;
					}
				}
				foreach($f->find('strong.title') as $g){
					$title = trim(strip_tags($g));
					break;
				}
				foreach($f->find('span.cate') as $g){
					$genre = trim(strip_tags($g));
					break;
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
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br>[".$genre."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
			} else {
				break;
			}
			$toonidx++;
		}
	} else {
		if ( $end != "END" ) {
?>
			<dt><?php echo $siteName; ?> 연재목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
			// 업데이트 페이지( /bbs/board.php?bo_table=comics&sop=and&sst=wr_datetime&sod=desc&type=alphabet&page=1 )의 목록 가져오기
			$url = $siteUrl.$recentUrl;
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
			foreach($get_html_contents->find('div.item-card-gray') as $e) {
				if ( $toonidx < (int)$config["max_list"] ) {
					$term = "";
					$f = str_get_html($e->innertext);
					foreach($f->find('img') as $g){
						$img_link = $g->src;
						break;
					}
					foreach($f->find('a') as $g){
						$epilink = $g->href;
						$toonstr = explode("/",$epilink);
						if ( startsWith($epilink, "http") ) {
							$wr_id = $toonstr[5];
							break;
						} else {
							$wr_id = $toonstr[3];
							break;
						}
					}
					foreach($f->find('strong.title') as $g){
						$title = trim(strip_tags($g));
						break;
					}
					foreach($f->find('span.cate') as $g){
						$genre = trim(strip_tags($g));
						break;
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
					echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br>[".$genre."]<br>".$alreadyView."</a>\n";
					echo "</tr>\n";
				} else {
					break;
				}
				$toonidx++;
			}
		} else {
?>
			<dt><?php echo $siteName; ?> 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
			// 업데이트 페이지( /bbs/board.php?bo_table=comics&sop=and&sst=wr_datetime&sod=desc&type=alphabet&page=1 )의 목록 가져오기
			$url = $siteUrl.$endedUrl;
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
			foreach($get_html_contents->find('div.item-card-gray') as $e) {
				if ( $toonidx < (int)$config["max_list"] ) {
					$term = "";
					$f = str_get_html($e->innertext);
					foreach($f->find('img') as $g){
						$img_link = $g->src;
						break;
					}
					foreach($f->find('a') as $g){
						$epilink = $g->href;
						$toonstr = explode("/",$epilink);
						if ( startsWith($epilink, "http") ) {
							$wr_id = $toonstr[5];
							break;
						} else {
							$wr_id = $toonstr[3];
							break;
						}
					}
					foreach($f->find('strong.title') as $g){
						$title = trim(strip_tags($g));
						break;
					}
					foreach($f->find('span.cate') as $g){
						$genre = trim(strip_tags($g));
						break;
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
					echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br>[".$genre."]<br>".$alreadyView."</a>\n";
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
