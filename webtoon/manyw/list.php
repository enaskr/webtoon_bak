<?php
	include('../../lib/header.php');
?>
<?php
$url = $siteUrl.$listUrl."?".str_replace("{toonid}",$_GET["wr_id"],$listParam);
if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
	$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
}
echo "<script type='text/javascript'>console.log('$url');</script>";
$get_html_contents = file_get_html($url);
for($html_c = 0; $html_c < $try_count; $html_c++){
	if(strlen($get_html_contents) > 10000){
		break;
	} else {
		$get_html_contents = file_get_html($url);
	}
}

foreach($get_html_contents->find('div.row') as $e){
	$f = str_get_html($e->innertext);
	$idx = 0;
	foreach($f->find('div') as $g){
		$h = str_get_html($g->innertext);
		if ( $idx==5 ) {
			$titlestr = trim(strip_tags($g));
			$subidx = 0;
			foreach($h->find('span') as $i){
				if ( $subidx == 0 ) {
					$title = trim(strip_tags($i));
				}
				if ( $subidx == 1 ) {
					$genretitle = trim(strip_tags($i));
				}
				$subidx++;
			}
			$genre = str_replace($title,"",$titlestr);
			$genre = str_replace($genretitle,"",$genre);
		}
		if ( $idx==6 ) {
			$contents = trim(strip_tags($g));
		}
		$idx++;
	}
	foreach($f->find('img') as $g){
		$thumb = $g->src;
	}
}
$_SESSION['THUMB'] = $thumb;
setcookie("THUMB", $thumb, time()+1800, "/");
echo "<script type='text/javascript'>console.log('THUMB=".$thumb."');</script>";
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo $title; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "<img src='".$thumb."' style='width:100%'>"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $genre; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $contents; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
$target_episode = array();

foreach($get_html_contents->find('li.list-item') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('a.item-subject') as $g){
		$targeturl = str_replace($siteUrl,"",$g->href);
		$targeturl = str_replace("/bbs/board.php?bo_table=webtoon&amp;wr_id=","",$targeturl);
		$targeturl = str_replace("bbs/board.php?bo_table=webtoon&amp;wr_id=","",$targeturl);
		$ws_id = str_replace("&amp;spage=1","",$targeturl);
		$epititle = trim(strip_tags($g));
		$epiurl = "/bbs/board.php?bo_table=webtoon&wr_id=".$ws_id;

		$isAlreadyView = "SELECT UV.MBR_NO AS MBR_NO, UV.SITE_ID AS SITE_ID, UV.TOON_ID AS TOON_ID, UVD.VIEW_ID AS VIEW_ID, UVD.UPTDTIME AS UPTDTIME FROM USER_VIEW UV, USER_VIEW_DTL UVD ";
		$isAlreadyView = $isAlreadyView." WHERE UV.MBR_NO = '".$MBR_NO."' AND UV.SITE_ID = '".$siteId."' AND UV.TOON_ID = '".$_GET['wr_id']."' AND UV.USE_YN='Y' ";
		$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVD.MBR_NO AND UV.SITE_ID = UVD.SITE_ID AND UV.TOON_ID = UVD.TOON_ID AND UVD.VIEW_ID = '".$ws_id."' ";
		$isAlreadyView = $isAlreadyView." ORDER BY UVD.UPTDTIME DESC LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		$viewDate = "";
		$alreadyView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["UPTDTIME"];
			$dbepiurl = $row["VIEW_ID"];
		}
		if ( strlen($viewDate) > 15 ) {
			$alreadyView = "<a href='../../lib/remove_view.php?siteid=".$siteId."&toonid=".urlencode($_GET['wr_id'])."&epiid=".$ws_id."'><font size='2'>[ ".$viewDate." viewed ]</font></a>";
		}
		echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".$ws_id."&wr_id=".$_GET['wr_id']."'>".$epititle."</a></font>".$alreadyView."<br></td></tr>";
	}			
}
?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
	</div>
</body>
</html>
