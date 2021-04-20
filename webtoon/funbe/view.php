<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
	$epiurl = str_replace("{toondtlid}",$_GET["ws_id"],$viewUrl);
	$title = $_GET["title"];
	$next_epi = "";
	$referrer = $_SERVER['HTTP_REFERER'];

	$get_images = array();
	$url = $siteUrl.$epiurl; //주소셋팅
	echo "<script type='text/javascript'>console.log('$url');</script>";
	echo "<script type='text/javascript'>console.log('THUMB=".$_SESSION["THUMB"]."');</script>";
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

	$get_html_contents = str_get_html($result);

	foreach($get_html_contents->find('h1') as $e){
		if($e != null){
			$epititle = trim(strip_tags($e));
			break;
		} 
	}
	foreach($get_html_contents->find('div.view-wrap') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('div[class="btn-resource btn-prev at-tip"]') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('a') as $i){
				$prev_url = $i->href;
				$prevpos = explode("/", $prev_url);
				if ( startsWith($prev_url, "http") == false ) { 
					$prev_url = $siteUrl.$prev_url;
					$prev_epi = $prevpos[1];
				} else {
					$prev_epi = $prevpos[3];
				}
			}
		}
		foreach($f->find('div[class="btn-resource btn-next at-tip"]') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('a') as $i){
				$next_url = $i->href;
				$nextpos = explode("/", $next_url);
				if ( startsWith($next_url, "http") == false ) { 
					$next_url = $siteUrl.$next_url;
					$next_epi = $nextpos[1];
				} else {
					$next_epi = $nextpos[3];
				}
			}
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
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."&type=".$_GET['type']."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."&type=".$_GET['type']."'>▶</a>";
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
			if ( $_SESSION["THUMB"]!=null && strlen($_SESSION["THUMB"])>0 && strpos($referrer, "list.php")!==false  ) {
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

	$conts_arr = explode("var toon_img = ", $get_html_contents);
	$conts = trim($conts_arr[1]);
	$conts = str_replace("';","",$conts);
	$conts = str_replace("'","",$conts);
	$conts = base64_decode($conts);
	$conts = str_replace(' src="',' width="100%" src="',$conts);

	$f = str_get_html($conts);
	foreach($f->find('img') as $e){
		$get_images = $e->src;
		if ( startsWith($get_images, "http") == false ) $get_images = $siteUrl.$get_images;
		echo "<img src='".$get_images."' style='max-width:100%'><br>";
	}
?>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."&type=".$_GET['type']."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi != null && strlen($prev_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi != null && strlen($next_epi) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi."&type=".$_GET['type']."'>▶</a>";
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
