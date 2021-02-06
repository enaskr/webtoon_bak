<?php
	include('../../lib/header.php');
?>
<?php
$pagenum = 1;
$pagecnt = 1;

do {
	$listParam = str_replace("{title}",$_GET["title"],$listParam);
	$listParam = str_replace("{toonid}",$_GET["wr_id"],$listParam);
	$listParam = str_replace("{page}",$pagenum,$listParam);
	$url = $siteUrl.$listUrl."?".$listParam;
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

	if ( $pagenum == 1 ) {
		foreach($get_html_contents->find('h2.title') as $e){
			$title = trim(strip_tags($e));
		}
		if ( $title == null || strlen($title)==0 ) {
			$title = $_GET["title"];
		}
		foreach($get_html_contents->find('p.artist') as $e){
			$author = trim(strip_tags($e));;
			if ( $genre == "작가 :" ) {
				$genre = "";
			} else {
				$genre = "[".$genre."]";
			}
		}
		foreach($get_html_contents->find('div.genre') as $e){
			$genre = trim(strip_tags($e));
			if ( $genre == "장르 :" ) {
				$genre = "";
			} else {
				$genre = "[".$genre."]";
			}
		}

		foreach($get_html_contents->find('div.cover-info-wrap') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('img') as $g){
				$thumb = $g->src;
				break;
			}
		}

		foreach($get_html_contents->find('nav.pg_wrap') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				$pagecnt = $g->href;
			}
		}
		$pagepos = explode("page=",$pagecnt);
		$pagecnt = $pagepos[1];
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
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $author.$genre; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
	}

	$idx = 0;
	$target_episode = array();

	foreach($get_html_contents->find('ul.episode-list') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('li') as $g){
			$h = str_get_html($g->innertext);
			foreach($h->find('button') as $i){
				$targeturl = $i->onclick;
				$targeturl = str_replace("location.href='./board.php?","",$targeturl);
				$targeturl = str_replace("'","",$targeturl);
				$targetpos1 = explode("&",$targeturl);
				$wr_id = (explode("=",$targetpos1[3]))[1];
				$ws_id = (explode("=",$targetpos1[1]))[1];
				// location.href='./board.php?bo_table=toons&wr_id=292075&stx=&is=24548'
	//			echo $targeturl.", wr_id=".$wr_id.", ws_id=".$ws_id;
			}
			$idx = 0;
			foreach($h->find('div') as $i){
				if ( $idx==5 ) $epititle = trim(strip_tags($i));
				$idx++;
			}
			$epiurl = "/bbs/board.php?bo_table=toons&wr_id=".$ws_id."&stx=&is=".$wr_id;
	//		echo ", epititle=".$epititle.", epiurl=".$epiurl;

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
				$alreadyView = "<a href='../../lib/remove_view.php?siteid=".$siteId."&toonid=".urlencode($_GET['wr_id'])."&epiid=".urlencode($dbepiurl)."'><font size='2'>[ ".$viewDate." viewed ]</font></a>";
			}
			echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".$ws_id."&wr_id=".$_GET['wr_id']."'>".$epititle."</a></font>".$alreadyView."<br></td></tr>";
		}
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
