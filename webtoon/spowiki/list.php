<?php
	include('../../lib/header.php');
?>
<?php
$url = $siteUrl.$listUrl."?".str_replace("{toonid}",$_GET["wr_id"],$listParam);
if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
	$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
}
echo "<script type='text/javascript'>console.log('$url');</script>";
$ch = curl_init(); //curl 로딩
curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
curl_setopt($ch, CURLOPT_TIMEOUT,3000);
$result = curl_exec($ch); // curl 실행 및 결과값 저장
curl_close ($ch); // curl 종료
$get_html_contents = str_get_html($result);

foreach($get_html_contents->find('meta') as $e){
	if($e->name == "title"){
		$title = $e->content;
	} 
}
foreach($get_html_contents->find('div.thumb') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('img') as $g){
		$thumb = $g->src;
		if ( startsWith($thumb, "http") == false ) $thumb = $base_url.$thumb;
	}
}
foreach($get_html_contents->find('div.overview') as $e){
	$contents = trim(strip_tags($e));
}
foreach($get_html_contents->find('dl.summ') as $e){
	$g = str_get_html($e->innertext);
	$idx=0;
	$term = "";
	$tooncnt = "";
	foreach($g->find('dd') as $h){
		if ( $idx == 0 ) $author = trim(strip_tags($h));
		if ( $idx == 1 ) $tooncnt = trim(strip_tags($h));
		$idx++;
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
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $contents; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "[작가:".$author."]"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "[총편수:".$tooncnt."]"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
$target_episode = array();

foreach($get_html_contents->find('table.bt-table') as $e){
	$f = str_get_html($e->innertext);
	$idx=0;
	foreach($f->find('tr') as $g){
		$h = str_get_html($g->innertext);
		if ( $idx>0 ) {
			foreach($h->find('a') as $i){
				$targeturl = $i->href;
				if ( startsWith($targeturl, "http") == false && startsWith($targeturl, "//") == false ) $targeturl = substr($targeturl, 1);
				$epititle = trim(strip_tags($i));
				$targetpos = explode("=",$targeturl);
				$wspos = explode("&",$targetpos[2]);
				$ws_id = $wspos[0];
				$wr_id = $targetpos[3];

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
					$alreadyView = "<a href='../../lib/remove_view.php?siteid=".$siteId."&toonid=".urlencode($_GET['wr_id'])."&epiid=".urlencode($ws_id)."'><font size='2'>[ ".$viewDate." viewed ]</font></a>";
				}
				echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".$ws_id."&wr_id=".$_GET['wr_id']."'>".$epititle."</a></font>".$alreadyView."<br></td></tr>";
			}
		}
		$idx++;
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
