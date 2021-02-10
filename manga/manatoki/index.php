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
			<dt>마나토끼 검색결과:<?php echo $keywordstr; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$url = $siteUrl.$searchUrl."?".str_replace("{keyword}",$keywordstr,$searchParam);
		echo "<script type='text/javascript'>console.log('$url');</script>";

		$ch = curl_init(); //curl 로딩
		curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
		curl_setopt($ch, CURLOPT_TIMEOUT,3000);
		$result = curl_exec($ch); // curl 실행 및 결과값 저장
		curl_close ($ch); // curl 종료
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
						$img_link = str_replace(substr($img_link, 0,23), $base_url, $img_link);
					}
					if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
						$img_link = str_replace(substr($img_link, 0,22), $base_url, $img_link);
					}
				}
				foreach($f->find('a') as $g){
					$target_link = $g->href;
					$targeturl = str_replace("?stx=".$keywordstr,"",$target_link);
					$targeturl = str_replace("?stx=".urlencode($keywordstr),"",$targeturl);
					$targeturl = str_replace("&page=1","",$targeturl);
					break;
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

					if($e->class == "in-lable trans-bg-black"){
						$subject = trim(strip_tags($e));
					}
					if($e->class == "list-artist trans-bg-yellow en"){
						$author = trim(strip_tags($e));
					}
					if($e->class == "list-publish trans-bg-blue en"){
						$publish = trim(strip_tags($e));
					}

				}
				$titleparse = explode('/' , $targeturl);
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

				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id." style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br>[작가:".$author."][발행주기:<font color=red>".$publish."</font>]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
			} else {
				break;
			}
			$toonidx++;
		}
	} else {
			if ( $end != "END" ) {
?>
			<dt>마나토끼 신규목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 1; $p++) {
					$url = $siteUrl.str_replace("{page}",$p,$recentUrl)."?".$recentParam;
					echo "<script type='text/javascript'>console.log('$url');</script>";
					$ch = curl_init(); //curl 로딩
					curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
					curl_setopt($ch, CURLOPT_TIMEOUT,3000);
					$result = curl_exec($ch); // curl 실행 및 결과값 저장
					curl_close ($ch); // curl 종료
					$get_html_contents = str_get_html($result);

					if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {
						Header("Location:./update.php"); 
					}
					$toonidx = 0;
					foreach($get_html_contents->find('div.post-row') as $e) {
						if ( $toonidx < (int)$config["max_list"] ) {
							$term = "";
							$f = str_get_html($e->innertext);
							foreach($f->find('img') as $g){
								$img_link = $g->src;
							}
							$idx = 0;
							foreach($f->find('a') as $g){
								if ( $idx == 0 ) $epilink = $g->href;
								if ( $idx == 1 ) $target_link = $g->href;
								$idx++;
							}
							$idx = 0;
							foreach($f->find('div.post-subject') as $g){
								$title = trim(strip_tags($g));
								$titlepos = explode("&nbsp;",$title);
								$title = trim($titlepos[0]);
							}
							foreach($f->find('div') as $g){
								if($g->class == "post-text post-ko txt-short ellipsis"){
									$content = trim(strip_tags($g));
									$content = "[작가: ".$content;
									$content = str_replace("&nbsp;","] [장르: ",$content);
									$content = $content."]";
								}
							}
							$epiparse = explode('/' , $epilink);
							$ws_id = $epiparse[4];
							$titleparse = explode('/' , $target_link);
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
							echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($title)."&wr_id=".$wr_id.">".$title."<br><font size=2>".$content."</font><br>".$alreadyView."</a>\n";
							echo "</tr>\n";
						} else {
							break;
						}
						$toonidx++;
					}
				}
			} else {
?>
			<dt>마나토끼 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
				for($p = 1; $p <= 7; $p++) {
					$url = $siteUrl.str_replace("{page}",$p,$endedUrl)."?".$endedParam;
					echo "<script type='text/javascript'>console.log('$url');</script>";
					$ch = curl_init(); //curl 로딩
					curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
					curl_setopt($ch, CURLOPT_TIMEOUT,3000);
					$result = curl_exec($ch); // curl 실행 및 결과값 저장
					curl_close ($ch); // curl 종료
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
									$img_link = str_replace(substr($img_link, 0,23), $base_url, $img_link);
								}
								if ( substr($img_link, 0,15) == "https://newtoki"  && ( substr($img_link, 18,3) == "com" || substr($img_link, 18,3) == "net" )) {
									$img_link = str_replace(substr($img_link, 0,22), $base_url, $img_link);
								}
							}
							foreach($f->find('a') as $g){
								$target_link = $g->href;
								$targeturl = str_replace("?page=1&publish=%EC%99%84%EA%B2%B0","",$target_link);
								$targeturl = str_replace("?page=1&publish=완결","",$targeturl);
								$targeturl = str_replace("?page=1","",$targeturl);
								break;
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

								if($e->class == "in-lable trans-bg-black"){
									$subject = trim(strip_tags($e));
								}
								if($e->class == "list-artist trans-bg-yellow en"){
									$author = trim(strip_tags($e));
								}
								$publish = "완결";
							}
							$titleparse = explode('/' , $targeturl);
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
							echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href=list.php?title=".urlencode($subject)."&wr_id=".$wr_id.">".$subject."<br><font size=2>[".$author."]</font><br>".$alreadyView."</a>\n";
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
