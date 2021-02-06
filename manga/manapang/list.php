<?php
	include('../../lib/header.php');
?>
<?php
$pagenum = 1;
$pagecnt = 1;
do {
	$listParamStr = str_replace('{toonid}',$_GET['wr_id'],$listParam);
	$listParamStr = str_replace('{type}',$_GET['type'],$listParamStr);
	$listParamStr = str_replace('{page}',$pagenum,$listParamStr);
	$url = $siteUrl.$listUrl."?".$listParamStr;
	if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
		$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
	}
	echo "<script type='text/javascript'>console.log('url = $url');</script>";
	$ch = curl_init(); //curl 로딩
	curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl 실행 및 결과값 저장
	curl_close ($ch); // curl 종료
	$get_html_contents = str_get_html($result);

	if ( $pagenum == 1 ) {
		foreach($get_html_contents->find('div.titl') as $e){
			if($e != null){
				$title = trim(strip_tags($e));
			} 
		}
		foreach($get_html_contents->find('div.comm') as $e){
			if($e != null){
				$contents = trim(strip_tags($e));
			} 
		}
		foreach($get_html_contents->find('div.ands') as $e){
			if($e != null){
				$genreinfo = explode('<span>&nbsp;|&nbsp;</span>' , $e);
				$genre = trim(strip_tags($genreinfo[0]));
				$term = trim(strip_tags($genreinfo[1]));
			} 
		}
		foreach($get_html_contents->find('div.gpos') as $e){
			if($e->style != null){
				$thumb = $e->style;
				$thumb = str_replace("background:url('","",$thumb);
				$thumb = str_replace("') no-repeat center center; background-size:cover;","",$thumb);
				setcookie("THUMB", $thumb, time()+60, "/");
				break;
			} 
		}
		foreach($get_html_contents->find('div.page') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a.pags') as $g){
				$pagelink[] = $g->href;
			}
		}
		$pagestr = $pagelink[count($pagelink)-1];
		$tmpstr = explode("&cps_=",$pagestr);
		$pagecnt = $tmpstr[1];
$_SESSION['THUMB'] = $thumb;
setcookie("THUMB", $thumb, time()+1800, "/");
echo "<script type='text/javascript'>console.log('THUMB=".$thumb."');</script>";

?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo $_GET["title"]; ?></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "<img src='".$thumb."' style='float:left; max-height:154px; margin-right:20px;'>"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo $contents; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><?php echo "[장르:".$genre."] [".$term."]"; ?></td>
					</tr>
					<tr style='background-color:#f8f8f8'>
						<td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align=center valign=middle></td>
					</tr>
<?php
	}

	foreach($get_html_contents->find('div.body') as $e){
		$f = str_get_html($e->innertext);
		foreach($f->find('a.boxs') as $g){
			$targeturl = $g->href;
			$urlparse = explode('?' , $targeturl);
			$uriparse = explode('&' , $urlparse[1]);
			$epiparse = explode('=' , $uriparse[0]);
			$t = str_get_html($g->innertext);
			$chasu = "";
			foreach($t->find('div') as $h){
				if ( $h->class == "econ ecen" && strlen($chasu) == 0) {
					$chasu = trim(strip_tags($h));
				}
				if ( $h->class == "econ elef" ) {
					$epititle = trim(strip_tags($h));
					if ( endsWith($epititle, "(0)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(1)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(2)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(3)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(4)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(5)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(6)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(7)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(8)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
					if ( endsWith($epititle, "(9)") == true ) $epititle = substr($epititle, 0, strlen($epititle)-4);
				}
			}
			$epiurl = "idx_=".$epiparse[1]."&gid_=".$_GET['wr_id']."&typ_=".$_GET['type'];

			$isAlreadyView = "SELECT UV.MBR_NO AS MBR_NO, UV.SITE_ID AS SITE_ID, UV.TOON_ID AS TOON_ID, UVD.VIEW_ID AS VIEW_ID, UVD.UPTDTIME AS UPTDTIME FROM USER_VIEW UV, USER_VIEW_DTL UVD ";
			$isAlreadyView = $isAlreadyView." WHERE UV.MBR_NO = '".$MBR_NO."' AND UV.SITE_ID = '".$siteId."' AND UV.TOON_ID = '".$_GET['wr_id']."' AND UV.USE_YN='Y' ";
			$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVD.MBR_NO AND UV.SITE_ID = UVD.SITE_ID AND UV.TOON_ID = UVD.TOON_ID AND UVD.VIEW_ID = '".$epiparse[1]."' ";
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
			echo "<tr style='background-color:#f8f8f8'><td style='width:100%;height:10px;font-size:16px;color:#8000ff;' align='center' valign='middle'><font size=4><a href='view.php?title=".urlencode($title)."&ws_id=".$epiparse[1]."&wr_id=".$_GET['wr_id']."&type=".$_GET['type']."'>".$chasu."::".$epititle."</a></font>".$alreadyView."<br></td></tr>";
		}			
	}
	$pagenum++;
} while ($pagenum <= $pagecnt);
?>
						</table>
					</div>
				</dd>
			</dl>
		</div>
	</div>
</body>
</html>
