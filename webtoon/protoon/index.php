<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
?>
<div id='container'>
	<div class='item'>
		<dl>
<?php

if ( $page == null ) $page = "1";
if($keywordstr != null){
?>
			<dt><?php echo $siteName; ?> 검색결과:<?php echo $keywordstr; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
	$url = $siteUrl.$searchUrl."?".str_replace("{keyword}",urlencode($keywordstr),$searchParam);
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

	foreach($get_html_contents->find('a.boxs') as $e) {
		if ( $loopcnt > (int)$config["max_list"] ) break;
		$term = "";
		$f = str_get_html($e->innertext);
		foreach($f->find('div.post') as $g) {
			$img_link = $g->getAttribute("style");
			$img_link = str_replace("background:url('","",$img_link);
			$img_link = str_replace("') center center no-repeat; background-size:cover;","",$img_link);
		}

		foreach($f->find('div.stit') as $g){
			$subject = trim(strip_tags($g));
		}
		foreach($f->find('div.gtit') as $g){
			$subject = trim(strip_tags($g));
		}
		$target_link = $e->href;
		$tgparse = explode('?' , $target_link);
		$idparse = explode('&' , $tgparse[1]);
		$wridparse = explode('=' , $idparse[0]);
		$wr_id = $wridparse[1];
		$genreparse = explode('=' , $idparse[1]);
		$genre = $genreparse[1];

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

		if ( $is_adult || $genre != "adult" ) {
				echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href='list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$genre."' style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
				echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href='list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$genre."'>".$subject."<br>[장르:".$genre."]<br>".$alreadyView."</a>\n";
				echo "</tr>\n";
				$loopcnt++;

		}
	}
} else {
	if ( $end != "END" ) {
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
		curl_setopt($ch, CURLOPT_TIMEOUT,3000);
		$result = curl_exec($ch); // curl 실행 및 결과값 저장
		curl_close ($ch); // curl 종료
		$get_html_contents = str_get_html($result);

		if ( $get_html_contents == null || strlen($get_html_contents) < 10000 ) {  // 사이트 접속 실패 시 주소 업데이트 페이지로 자동 이동
			Header("Location:./update.php"); 
		}

		foreach($get_html_contents->find('a.boxs') as $e){
			$loopcnt++;
			if ( $loopcnt > (int)$config["max_list"] ) break;
			$term = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('div.post') as $g) {
				$img_link = $g->getAttribute("style");
				$img_link = str_replace("background:url('","",$img_link);
				$img_link = str_replace("') center center no-repeat; background-size:cover;","",$img_link);
			}

			$idx = 0;
			foreach($f->find('div') as $g){
				if ( $idx == 1 ) {
					$term = trim(strip_tags($g));
				}
				if ( $idx == 2 ) {
					$subject = trim(strip_tags($g));
				}
				$idx++;
			}
			$idx = 0;

			$target_link = $e->href;
			$tgparse = explode('?' , $target_link);
			$idparse = explode('&' , $tgparse[1]);
			$wridparse = explode('=' , $idparse[0]);
			$wr_id = $wridparse[1];
			$typeparse = explode('=' , $idparse[1]);
			$type = $typeparse[1];

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

			echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href='list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type."' style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
			echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href='list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type."'>".$subject."<br>[".$term."]<br>".$alreadyView."</a>\n";
			echo "</tr>\n";

		}
	} else {
?>
			<dt><?php echo $siteName; ?> 완결목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
		$url = $siteUrl.$endedUrl."?".str_replace("{page}",$page,$endedParam);
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

		foreach($get_html_contents->find('a.boxs') as $e){
			$loopcnt++;
			if ( $loopcnt > (int)$config["max_list"] ) break;
			$term = "";
			$f = str_get_html($e->innertext);
			foreach($f->find('div.post') as $g) {
				$img_link = $g->getAttribute("style");
				$img_link = str_replace("background:url('","",$img_link);
				$img_link = str_replace("') center center no-repeat; background-size:cover;","",$img_link);
			}

			$subject = trim(strip_tags($e));

			$target_link = $e->href;
			$tgparse = explode('?' , $target_link);
			$idparse = explode('&' , $tgparse[1]);
			$wridparse = explode('=' , $idparse[0]);
			$wr_id = $wridparse[1];
			$typeparse = explode('=' , $idparse[1]);
			$type = $typeparse[1];

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

			echo "<tr style='background-color:#f8f8f8'><td style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><a href='list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type."' style='margin:0px;padding:3px 3px 3px 3px'><img class='rounded-lg' src=".$img_link." style='width:80px;max-height:80px;'></a></td> ";
			echo "<td style='word-wrap:break-word;max-height:80px;' valign=middle><a href='list.php?title=".urlencode($subject)."&wr_id=".$wr_id."&type=".$type."'>".$subject."<br>".$alreadyView."</a>\n";
			echo "</tr>\n";
		}
?>
<tr style='background-color:#f8f8f8'><td colspan="2" style='width:86px;font-size:16px;color:#8000ff;' align=center valign=middle><font size="4"><a href="index.php?end=END&page=1"><?php if ($page=="1") {echo "<font color=red><b>1</b></font>"; } else {echo "1";} ?></a> <a href="index.php?end=END&page=2"><?php if ($page=="2") {echo "<font color=red><b>2</b></font>"; } else {echo "2";} ?></a> <a href="index.php?end=END&page=3"><?php if ($page=="3") {echo "<font color=red><b>3</b></font>"; } else {echo "3";} ?></a> <a href="index.php?end=END&page=4"><?php if ($page=="4") {echo "<font color=red><b>4</b></font>"; } else {echo "4";} ?></a> <a href="index.php?end=END&page=5"><?php if ($page=="5") {echo "<font color=red><b>5</b></font>"; } else {echo "5";} ?></a> <a href="index.php?end=END&page=6"><?php if ($page=="6") {echo "<font color=red><b>6</b></font>"; } else {echo "6";} ?></a> <a href="index.php?end=END&page=7"><?php if ($page=="7") {echo "<font color=red><b>7</b></font>"; } else {echo "7";} ?></a> <a href="index.php?end=END&page=8"><?php if ($page=="8") {echo "<font color=red><b>8</b></font>"; } else {echo "8";} ?></a> <a href="index.php?end=END&page=9"><?php if ($page=="9") {echo "<font color=red><b>9</b></font>"; } else {echo "9";} ?></a> <a href="index.php?end=END&page=10"><?php if ($page=="10") {echo "<font color=red><b>10</b></font>"; } else {echo "10";} ?></a> <a href="index.php?end=END&page=11"><?php if ($page=="11") {echo "<font color=red><b>11/b></font>"; } else {echo "11";} ?></a> <a href="index.php?end=END&page=12"><?php if ($page=="12") {echo "<font color=red><b>12</b></font>"; } else {echo "12";} ?></a> <a href="index.php?end=END&page=13"><?php if ($page=="13") {echo "<font color=red><b>13</b></font>"; } else {echo "13";} ?></a> <a href="index.php?end=END&page=14"><?php if ($page=="14") {echo "<font color=red><b>14</b></font>"; } else {echo "14";} ?></a></font></center></td></tr>
						</table>
<?php
	}
}
?>
					</div>
				</dd>
			</dl>
		</div>
		<!--// item : E -->
	</div>
</body>
</html>
