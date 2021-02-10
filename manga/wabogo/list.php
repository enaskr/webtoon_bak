<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
?>
<?php
$url = $siteUrl.str_replace("{toonid}",$_GET["wr_id"],$listUrl);
echo "<script type='text/javascript'>console.log('$url');</script>";
$get_html_contents = file_get_html($url);
for($html_c = 0; $html_c < $try_count; $html_c++){
	if(strlen($get_html_contents) > 10000){
		break;
	} else {
		$get_html_contents = file_get_html($url);
	}
}

foreach($get_html_contents->find('h3.tit_area') as $e){
	$title = trim(strip_tags($e));
}
$idx = 0;
foreach($get_html_contents->find('img') as $e){
	if ( $idx==1 ) {
		$thumb = $e->src;
		if ( startsWith($thumb, "http") == false ) $thumb = $siteUrl.$thumb;
		break;
	}
	$idx++;
}
foreach($get_html_contents->find('div.comic_story') as $e){
	$contents = trim(strip_tags($e));
}
foreach($get_html_contents->find('div.item-details') as $e){
	$genre = trim(strip_tags($e));
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
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $contents; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "[장르:".$genre."]"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
$target_episode = array();

foreach($get_html_contents->find('ul.list-body') as $e){
	foreach($e->find('li') as $f){
		$g = str_get_html($f->innertext);
		$idx=0;
		foreach($g->find('div') as $h){
			$i = str_get_html($h->innertext);
			if ( $idx==0 ) {
				$chasu = trim(strip_tags($h));
			}
			if ( $idx==1 ) {
				$epititle = trim(strip_tags($h));
				foreach($i->find('a') as $j){
					$epilink = $j->href;
					$toonstr = explode("/", $epilink);
					$toondtlid = $toonstr[count($toonstr)-1];
				}
			}

			$idx++;
		}
		foreach($g->find('a') as $h){
			$targeturl = $h->href;
			if ( startsWith($targeturl, "/") == true ) $targeturl = substr($targeturl, 1);
		}

			$isAlreadyView = "SELECT UV.MBR_NO AS MBR_NO, UV.SITE_ID AS SITE_ID, UV.TOON_ID AS TOON_ID, UVD.VIEW_ID AS VIEW_ID, UVD.UPTDTIME AS UPTDTIME FROM USER_VIEW UV, USER_VIEW_DTL UVD ";
			$isAlreadyView = $isAlreadyView." WHERE UV.MBR_NO = '".$MBR_NO."' AND UV.SITE_ID = '".$siteId."' AND UV.TOON_ID = '".$_GET['wr_id']."' AND UV.USE_YN='Y' ";
			$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVD.MBR_NO AND UV.SITE_ID = UVD.SITE_ID AND UV.TOON_ID = UVD.TOON_ID AND UVD.VIEW_ID = '".$toondtlid."' ";
			$isAlreadyView = $isAlreadyView." ORDER BY UVD.UPTDTIME DESC LIMIT 1;";
			$webtoonView = $webtoonDB->query($isAlreadyView);
			$viewDate = "";
			$alreadyView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$viewDate = $row["UPTDTIME"];
				$dbepiurl = $row["VIEW_ID"];
			}
			if ( strlen($viewDate) > 15 ) {
				$alreadyView = "<a href='../../lib/remove_view.php?siteid=".$siteId."&toonid=".urlencode($_GET['wr_id'])."&epiid=".$toondtlid."'><font size='2'>[ ".$viewDate." viewed ]</font></a>";
			}
			echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".$toondtlid."&wr_id=".urlencode($_GET['wr_id'])."'>".$chasu.".".$epititle."</a></font>".$alreadyView."<br></td></tr>";
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
