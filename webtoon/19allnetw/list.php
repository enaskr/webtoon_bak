<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
?>
<?php
$pagenum = 1;
$pagecnt = 1;
$reqTitle=$_GET["title"];

do {
	$url = $siteUrl.str_replace("{page}",$pagenum,str_replace("{toonid}",$_GET["wr_id"],$listUrl));
	echo "<script type='text/javascript'>console.log('$url');</script>";
	$get_html_contents = file_get_html($url);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = file_get_html($url);
		}
	}

	if ( $pagenum == 1 ) {
		foreach($get_html_contents->find('div.cover-info-wrap') as $e){
			$f = str_get_html($e->innertext);
			$contcnt = 0;
			foreach($f->find('div.info') as $g){
				$h = str_get_html($g->innertext);
				foreach($h->find('h1') as $i){
					$title = trim(strip_tags($i));
					$contcnt++;
					break;
				}
				foreach($h->find('div.genre') as $i){
					$genre = trim(strip_tags($i));
					$contcnt++;
					break;
				}
				foreach($h->find('div.summary') as $i){
					$contents = trim(strip_tags($i));
					$contcnt++;
					break;
				}
				foreach($h->find('p.artist') as $i){
					$author = trim(strip_tags($i));
					$contcnt++;
					break;
				}
				if ( $contcnt == 4 ) break;
			}

			foreach($f->find('img') as $g){
				$thumb = $g->src;
				break;
			}
		}
		foreach($get_html_contents->find('ul.pagination') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				$pagecntstr = $g->href;
				$pagecnt = (explode("/",$pagecntstr))[7];
			}
		}
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
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "<img src='".$thumb."' style='max-width:100%'>"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $contents; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $author; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $genre; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
	}

	$target_episode = array();

	foreach($get_html_contents->find('li.is-purchasable') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a') as $g){
			$targeturl = str_replace($siteUrl,"",$g->href);
			$toonstr = explode("/",$targeturl);
			$ws_id = $toonstr[4];
			break;
		}
		foreach($f->find('div.episode-title') as $g){
			$epititle = trim(strip_tags($g));
			break;
		}

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
	$pagenum++;
} while  ($pagenum <= $pagecnt);
?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
	</div>
</body>
</html>
