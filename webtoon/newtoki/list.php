<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
?>
<?php
$reqTitle=$_GET["title"];
$url = $siteUrl.$listUrl."?".str_replace("{toonid}",$_GET["wr_id"],$listParam);
echo "<script type='text/javascript'>console.log('$url');</script>";
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><a href='<?php echo $url; ?>'><?php echo $reqTitle; ?></a></dt>
<?php
try {
$result = getCurlDom($url, (int)$config["try_count"]);
$get_html_contents = str_get_html($result);

foreach($get_html_contents->find('meta') as $e){
	if($e->name == "subject"){
		$title = $e->content;
	} 
}
$idx=0;
foreach($get_html_contents->find('div.view-content') as $e){
	if ( $idx==2 ) $contents = trim(strip_tags($e));;
	$idx++;
}

foreach($get_html_contents->find('div.view-img') as $e){
	$thumb = $e->innertext;
}
$thumbstr = (str_get_html($thumb))->find('img')[0];
$thumburl = $thumbstr->src;

$_SESSION['THUMB'] = $thumburl;
setcookie("THUMB", $thumburl, time()+1800, "/");
echo "<script type='text/javascript'>console.log('THUMB=".$thumburl."');</script>";
?>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><img src='<?php echo $thumburl; ?>' style='max-width:100%;'></td>
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
		$h = str_get_html($g->innertext);
		$newtitle = trim(strip_tags(removeNode($h, 'span')));
		$targeturl = str_replace("&amp;spage=1","",$g->href);
		$epiparse = explode('/' , $targeturl);

		$isAlreadyView = "SELECT UV.MBR_NO AS MBR_NO, UV.SITE_ID AS SITE_ID, UV.TOON_ID AS TOON_ID, UVD.VIEW_ID AS VIEW_ID, UVD.UPTDTIME AS UPTDTIME FROM USER_VIEW UV, USER_VIEW_DTL UVD ";
		$isAlreadyView = $isAlreadyView." WHERE UV.MBR_NO = '".$MBR_NO."' AND UV.SITE_ID = '".$siteId."' AND UV.TOON_ID = '".$_GET['wr_id']."' AND UV.USE_YN='Y' ";
		$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVD.MBR_NO AND UV.SITE_ID = UVD.SITE_ID AND UV.TOON_ID = UVD.TOON_ID AND UVD.VIEW_ID = '".$epiparse[4]."' ";
		$isAlreadyView = $isAlreadyView." ORDER BY UVD.UPTDTIME DESC LIMIT 1;";
		$webtoonView = $webtoonDB->query($isAlreadyView);
		$viewDate = "";
		$alreadyView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$viewDate = $row["UPTDTIME"];
			$dbepiurl = $row["VIEW_ID"];
		}
		if ( strlen($viewDate) > 15 ) {
			$alreadyView = "<a href='../../lib/remove_view.php?siteid=".$siteId."&toonid=".urlencode($_GET['wr_id'])."&epiid=".urlencode($epiparse[4])."'><font size='2'>[ ".$viewDate." viewed ]</font></a>";
		}
		echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".$title."&ws_id=".urlencode($epiparse[4])."&wr_id=".urlencode($_GET['wr_id'])."'>".$newtitle."</a></font>".$alreadyView."<br></td></tr>";
	}			
}

} catch(Exception $e) {
	$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
	echo '<dd><div class="group" style="padding:0px;"><table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr style="background-color:#f8f8f8"><td style="width:100%;font-size:16px;color:#8000ff;" align=center valign=middle>'.$s.'</td></tr>';
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
