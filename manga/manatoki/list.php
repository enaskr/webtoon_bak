<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
?>
<?php
$reqTitle=$_GET["title"];

$url = $siteUrl.$listUrl."?".str_replace("{toonid}",$_GET["wr_id"],$listParam);
echo "<script type='text/javascript'>console.log('$url');</script>";
$ch = curl_init(); //curl 로딩
curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
curl_setopt($ch, CURLOPT_TIMEOUT,3000);
$result = curl_exec($ch); // curl 실행 및 결과값 저장
curl_close ($ch); // curl 종료
$get_html_contents = str_get_html($result);

foreach($get_html_contents->find('meta') as $e){
	if($e->name == "subject"){
		$title = $e->content;
	} 
}

foreach($get_html_contents->find('div.view-content1') as $e){
	foreach($e->find('img') as $g){
		$thumb = $g->src;
	}
}
$idx = 0;
foreach($get_html_contents->find('div') as $e){
	if($e->class == "view-content"){
		if ( $idx == 2 ) $author = trim(strip_tags($e));
		if ( $idx == 3 ) $publish = trim(strip_tags($e));
		$idx++;
	}
	if($e->class == "view-content tags"){
		$genre = trim(strip_tags($e));
	}
}
$author = trim(str_replace("•","",$author));
$genre = trim(str_replace("•","",$genre));
$publish = trim(str_replace("•","",$publish));
$_SESSION['THUMB'] = $thumb;
setcookie("THUMB", $thumb, time()+1800, "/");
echo "<script type='text/javascript'>console.log('THUMB=".$thumb."');</script>";

?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><a href='<?php echo $url; ?>'><?php echo $reqTitle; ?></a></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "<img src='".$thumb."' style='max-width:100%;max-height:250px;'>"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $author; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $genre; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $publish; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
$target_episode = array();

foreach($get_html_contents->find('li.list-item') as $e){
	$f = str_get_html($e->innertext);
	foreach($f->find('a.item-subject') as $g){
		$targeturl = str_replace("?toon=%EC%99%84%EA%B2%B0%EC%9B%B9%ED%88%B0&spage=1","",$g->href);
		$targeturl = str_replace("?toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0&spage=1","",$targeturl);
		$targeturl = str_replace("?spage=1","",$targeturl);
		$epiparse = explode('/' , $targeturl);
		$epititle = strip_tags($g);
		$epititle = str_replace("화 1 ","화",$epititle);
		$epititle = str_replace("   1  ","",$epititle);
		$epiurl = str_replace($base_url,"/",$base_url."comic/".$epiparse[4]);

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
		echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".$title."&ws_id=".$epiparse[4]."&wr_id=".$_GET['wr_id']."'>".$epititle."</a></font>".$alreadyView."<br></td></tr>";
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
