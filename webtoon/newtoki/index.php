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
		$result = getCurlDom($url, (int)$config["try_count"]);
		$get_html_contents = str_get_html($result);

		if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {  // 사이트 접속 실패 시 주소 업데이트 페이지로 자동 이동
			Header("Location:./update.php"); 
		}

		$toonidx = 0;
		foreach($get_html_contents->find('div.imgframe') as $e) {
			if ( $toonidx < (int)$config["max_list"] ) {
				$term = "";
				$f = str_get_html($e->innertext);
				foreach($f->find('img') as $g){
					$img_link = $g->src;
					if ( substr($img_link, 0,16) == "https://manatoki" && ( substr($img_link, 19,3) == "com" || substr($img_link, 19,3) == "net" )) {
						$img_link = str_replace(substr($img_link, 0,22), $siteUrl, $img_link);
					}
					if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
						$img_link = str_replace(substr($img_link, 0,21), $siteUrl, $img_link);
					}
				}
				foreach($f->find('a') as $g){
					$target_link = $g->href;
				}
				foreach($f->find('span') as $g){
					$title = trim(strip_tags($g));
				}
				foreach($f->find('div') as $e){
					if($e->class == "list-date bg-red right en"){
						$term = trim(strip_tags($e));
					}
					if($e->class == "list-date trans-bg-darkred right en"){
						$term = trim(strip_tags($e));
					}
					if($e->class == "list-date trans-bg-orangered right en"){
						$term = trim(strip_tags($e));
					}
					if($e->class == "list-date trans-bg-gray right en"){
						$term = trim(strip_tags($e));
					}
				}
				$titleparse = explode('/' , $target_link);
				#$title_temp = explode("?", $titleparse[5]);
				#$subject = $title_temp[0];
				$wr_id = $titleparse[4];

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
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br>[<font color=red>".$term."</font>]<br>".$alreadyView."</a>\n";
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
			for($p = 1; $p <= 1; $p++) {
				$url = $siteUrl.str_replace("{page}",$p,$recentUrl)."?".$recentParam;
				echo "<script type='text/javascript'>console.log('$url');</script>";
				$result = getCurlDom($url, (int)$config["try_count"]);
				$get_html_contents = str_get_html($result);

				if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {
					Header("Location:./update.php"); 
				}

				$toonidx = 0;
				foreach($get_html_contents->find('div.imgframe') as $e) {
					if ( $toonidx < (int)$config["max_list"] ) {
						$term = "";
						$f = str_get_html($e->innertext);
						foreach($f->find('img') as $g){
							$img_link = $g->src;
							if ( substr($img_link, 0,16) == "https://manatoki" && ( substr($img_link, 19,3) == "com" || substr($img_link, 19,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,22), $siteUrl, $img_link);
							}
							if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,21), $siteUrl, $img_link);
							}
						}
						foreach($f->find('a') as $g){
							$target_link = $g->href;
						}
						foreach($f->find('span') as $g){
							$title = trim(strip_tags($g));
						}
						foreach($f->find('div') as $e){
							if($e->class == "list-date bg-red right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-darkred right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-orangered right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-gray right en"){
								$term = trim(strip_tags($e));
							}
						}
						$titleparse = explode('/' , $target_link);
						#$title_temp = explode("?", $titleparse[5]);
						#$subject = $title_temp[0];
						$wr_id = $titleparse[4];

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
						echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br>[<font color=red>".$term."</font>]<br>".$alreadyView."</a>\n";
						echo "</tr>\n";
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
			for($p = 1; $p <= 4; $p++) {
				$url = $siteUrl.str_replace("{page}",$p,$endedUrl)."?".$endedParam;
				echo "<script type='text/javascript'>console.log('$url');</script>";
				$result = getCurlDom($url, (int)$config["try_count"]);
				$get_html_contents = str_get_html($result);

				if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {
					Header("Location:./update.php"); 
				}

				$toonidx = 0;
				foreach($get_html_contents->find('div.imgframe') as $e) {
					if ( $toonidx < (int)$config["max_list"] ) {
						$term = "";
						$f = str_get_html($e->innertext);
						foreach($f->find('img') as $g){
							$img_link = $g->src;
							if ( substr($img_link, 0,16) == "https://manatoki" && ( substr($img_link, 19,3) == "com" || substr($img_link, 19,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,22), $siteUrl, $img_link);
							}
							if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
								$img_link = str_replace(substr($img_link, 0,21), $siteUrl, $img_link);
							}
						}
						foreach($f->find('a') as $g){
							$target_link = $g->href;
						}
						foreach($f->find('span') as $g){
							$title = trim(strip_tags($g));
						}
						foreach($f->find('div') as $e){
							if($e->class == "list-date bg-red right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-darkred right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-orangered right en"){
								$term = trim(strip_tags($e));
							}
							if($e->class == "list-date trans-bg-gray right en"){
								$term = trim(strip_tags($e));
							}
						}
						$titleparse = explode('/' , $target_link);
						#$title_temp = explode("?", $titleparse[5]);
						#$subject = $title_temp[0];
						$wr_id = $titleparse[4];

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
						echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br>[<font color=red>".$term."</font>]<br>".$alreadyView."</a>\n";
						echo "</tr>\n";
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
