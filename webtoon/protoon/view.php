<?php
include('../../lib/header.php');
	$epiurl = $viewUrl."?".str_replace("{toondtlid}",$_GET["ws_id"],$viewParam);
	$epiurl = str_replace("{toonid}",$_GET["wr_id"],$epiurl);
	$epiurl = str_replace("{type}",$_GET["type"],$epiurl);

	$url = $siteUrl.$epiurl; //주소셋팅
	echo "<script type='text/javascript'>console.log('$url');</script>";
	if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
		$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
	}
	$url = $base_url.$url; //주소셋팅
	$get_html_contents = file_get_html($url);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = file_get_html($ch);
		}
	}

	foreach($get_html_contents->find('div.etit') as $e){
		if($e != null){
			$epititle = trim(strip_tags($e));
			break;
		} 
	}
	foreach($get_html_contents->find('a.pbtn') as $e){
		if($e->href != null){
			$prev_url = $e->href;
			$urlparse = explode('?' , $prev_url);
			$uriparse = explode('&' , $urlparse[1]);
			$prev_epi = explode('=' , $uriparse[0]);
		}
	}
	foreach($get_html_contents->find('a.nbtn') as $e){
		if($e->href != null){
			$next_url = $e->href;
			$urlparse = explode('?' , $next_url);
			$uriparse = explode('&' , $urlparse[1]);
			$next_epi = explode('=' , $uriparse[0]);
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
							if( $prev_epi[1] != null && strlen($prev_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi[1]."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi[1] != null && strlen($next_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi[1]."&type=".$_GET['type']."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi[1] != null && strlen($prev_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi[1]."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi[1] != null && strlen($next_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi[1]."&type=".$_GET['type']."'>▶</a>";
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

	foreach($get_html_contents->find('div.eimg') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('img') as $g){
			if($g->getAttribute("data-src") != null){
				$get_images = $g->getAttribute("data-src");
				echo "<img src='".$get_images."' width='100%'><br>";
			}
		}
	}
?>
						</td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi[1] != null && strlen($prev_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi[1]."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi[1] != null && strlen($next_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi[1]."&type=".$_GET['type']."'>▶</a>";
							} else {
								echo "▷";
							}
						?>
						</td>
						<td style='font-size:16px;color:#8000ff;' align=center valign=middle>&nbsp;</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $prev_epi[1] != null && strlen($prev_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$prev_epi[1]."&type=".$_GET['type']."'>◀</a>";
							} else {
								echo "◁";
							}
						?>
						</td>
						<td style='width:10%;font-size:16px;color:#8000ff;' align=center valign=middle>
						<?php
							if( $next_epi[1] != null && strlen($next_epi[1]) > 0 ){
									echo "<a href='view.php?title=".urlencode($title)."&wr_id=".$_GET['wr_id']."&ws_id=".$next_epi[1]."&type=".$_GET['type']."'>▶</a>";
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
