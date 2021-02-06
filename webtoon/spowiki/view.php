<?php
	include('../../lib/header.php');
	$epiurl = str_replace("{toondtlid}",$_GET["ws_id"],$viewParam);
	$epiurl = str_replace("{toonid}",$_GET["wr_id"],$epiurl);

	$get_images = array();
	$url = $siteUrl.$viewUrl."?".$epiurl; //주소셋팅
	if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
		$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
	}
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

	$html_arr = explode("<title>", $result);
	$get_html_contents = str_get_html($result);

	foreach($get_html_contents->find('h1') as $e){
		if($e != null){
			$epititle = trim(strip_tags($e));
			break;
		} 
	}
	foreach($get_html_contents->find('li.next_href') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a.view_list_button') as $g){
				$prev_url = $g->href;
				if ( startsWith($prev_url, "http") == false && startsWith($prev_url, "//") == false ) $prev_url = substr($prev_url, 1);
				$prev_pos = explode("=",$prev_url);
				$prev_wspos = explode("&",$prev_pos[2]);
				$prev_epi = $prev_wspos[0];
				$prev_wr_id = $prev_pos[3];
		}
	}
	foreach($get_html_contents->find('li.prev_href') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a.view_list_button') as $g){
				$next_url = $g->href;
				if ( startsWith($next_url, "http") == false && startsWith($next_url, "//") == false ) $next_url = substr($next_url, 1);
				$next_pos = explode("=",$next_url);
				$next_wspos = explode("&",$next_pos[2]);
				$next_epi = $next_wspos[0];
				$next_wr_id = $next_pos[3];
		}
	}
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo "<a href='list.php?wr_id=".$_GET['wr_id']."&title=".urlencode($title)."'>".$epititle."</a> <a href='".$url."'><img src='logo.png' height='25px'></a>"; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td colspan="5" style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
<?php

	if ( $isLogin ) {
		$isAlreadyView = "SELECT MBR_NO, SITE_ID, TOON_ID, REGDTIME, UPTDTIME FROM USER_VIEW ";
		$isAlreadyView = $isAlreadyView." WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$_GET['wr_id']."' AND USE_YN='Y' ";
		$isAlreadyView = $isAlreadyView." LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["UPTDTIME"];
		}
		if ( strlen($viewDate) == 0 ) {
			$sql_view = "INSERT INTO 'USER_VIEW' ('MBR_NO', 'SITE_ID', 'TOON_ID', 'TOON_URL', 'TOON_PARAM', 'TOON_THUMBNAIL', 'TOON_TITLE', 'USE_YN', 'REGDTIME', 'UPTDTIME')";
			$sql_view = $sql_view." VALUES ('".$MBR_NO."','".$siteId."','".$_GET['wr_id']."','".$siteUrl.$listUrl."','".str_replace("{toonid}",$_GET["wr_id"],$listParam)."','".$_SESSION["THUMB"]."','".$_GET["title"]."', 'Y', '".$thisTime."', '".$thisTime."'); ";
			$webtoonDB->exec($sql_view);
		} else {
			$sql_view = "UPDATE 'USER_VIEW' SET UPTDTIME = '".$thisTime."' ";
			if ( $_SESSION["THUMB"]!=null && strlen($_SESSION["THUMB"])>0 ) {
				$sql_view = $sql_view.", TOON_THUMBNAIL = '".$_SESSION["THUMB"]."' ";
			}
			$sql_view = $sql_view."WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$_GET['wr_id']."' AND USE_YN = 'Y';";
			$webtoonDB->exec($sql_view);
		}
		$viewDate = "";
		$isAlreadyView = "SELECT MBR_NO, SITE_ID, TOON_ID, VIEW_ID, REGDTIME, UPTDTIME FROM USER_VIEW_DTL ";
		$isAlreadyView = $isAlreadyView." WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$_GET['wr_id']."' AND VIEW_ID = '".$_GET['ws_id']."' AND USE_YN='Y' ";
		$isAlreadyView = $isAlreadyView." LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["UPTDTIME"];
		}
		if ( strlen($viewDate) == 0 ) {
			$sql_view = "INSERT INTO 'USER_VIEW_DTL' ('MBR_NO', 'SITE_ID', 'TOON_ID', 'VIEW_ID', 'VIEW_URL', 'VIEW_PARAM', 'VIEW_TITLE', 'USE_YN', 'REGDTIME', 'UPTDTIME')";
			$sql_view = $sql_view." VALUES ('".$MBR_NO."','".$siteId."','".$_GET['wr_id']."','".$_GET['ws_id']."', '".$epiurl."','','".$epititle."','Y', '".$thisTime."', '".$thisTime."'); ";
			$webtoonDB->exec($sql_view);
		} else {
			$sql_view = "UPDATE 'USER_VIEW_DTL' SET UPTDTIME = '".$thisTime."' ";
			$sql_view = $sql_view."WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteId."' AND TOON_ID = '".$_GET['wr_id']."' AND VIEW_ID = '".$_GET['ws_id']."' AND USE_YN = 'Y';";
			$webtoonDB->exec($sql_view);
		}
	}

	foreach($get_html_contents->find('div#bo_v_img') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('img') as $g){
			$get_images = $g->content;
			if ( startsWith($get_images, "http") == false && startsWith($get_images, "//") == false ) $get_images = $base_url.substr($get_images,1);
			echo "<img src='".$get_images."' width='100%'><br>";
		}
	}
?>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
					</tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</div>
</body>
</html>
