<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
?>
<script type="text/javascript">
	function saveSetting(frm) {
		if ( frm.SERVER_PATH.value == "" ) {
			if ( confirm(frm.SITE_NAME.value+"을 삭제하시겠습니까?") ) {
				frm.action = "./siteupdate.php";
				frm.submit();
			} else {
				return false;
			}
		} else {
			frm.action = "./siteupdate.php";
			frm.submit();
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><a href="./siteinfo.php"><?php echo $_GET["siteid"]; ?> 사이트 정보</a></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><form name="frm" method="post" action="./siteupdate.php">
<?php
	if ( $USER_LEVEL >= 99999 ) {
		$toonsiteList = "SELECT SITE_ID, SITE_NAME, SITE_ALIAS, SITE_URL, SITE_TYPE, SERVER_PATH, USE_LEVEL, SEARCH_URL, SEARCH_PARAM, RECENT_URL, RECENT_PARAM, ";
		$toonsiteList = $toonsiteList."ENDED_URL, ENDED_PARAM, LIST_URL, LIST_PARAM, VIEW_URL, VIEW_PARAM, USE_YN, NOTE, MAIN_VIEW, ORDER_NUM, UPDATE_YN, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE, REGDTIME, UPTDTIME ";
		$toonsiteList = $toonsiteList."FROM SITE_INFO WHERE SITE_ID='".$_GET["siteid"]."' LIMIT 1; ";
		$webtoonView = $systemDB->query($toonsiteList);
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
			$dbsite_id = $row["SITE_ID"];
			$dbsite_name = $row["SITE_NAME"];
			$dbsite_alias = $row["SITE_ALIAS"];
			$dbsite_url = $row["SITE_URL"];
			$dbsite_type = $row["SITE_TYPE"];
			$dbserver_path = $row["SERVER_PATH"];
			$dbuse_level = $row["USE_LEVEL"];
			$dbsearch_url = $row["SEARCH_URL"];
			$dbsearch_param = $row["SEARCH_PARAM"];
			$dbrecent_url = $row["RECENT_URL"];
			$dbrecent_param = $row["RECENT_PARAM"];
			$dbended_url = $row["ENDED_URL"];
			$dbended_param = $row["ENDED_PARAM"];
			$dblist_url = $row["LIST_URL"];
			$dblist_param = $row["LIST_PARAM"];
			$dbview_url = $row["VIEW_URL"];
			$dbview_param = $row["VIEW_PARAM"];
			$dbuse_yn = $row["USE_YN"];
			$dbnote = $row["NOTE"];
			$dbmainview = $row["MAIN_VIEW"];
			$dbordernum = $row["ORDER_NUM"];
			$dbupdateyn = $row["UPDATE_YN"];
			$dbupdateexecute = $row["UPDATE_EXECUTE"];
			$dbregdtime = $row["REGDTIME"];
			$dbuptdtime = $row["UPTDTIME"];

			if ( ( $dbcf_redirect != null && $dbcf_redirect == "N" ) || ( $dbcf_redirect == null || strlen($dbcf_redirect) == 0) ) {
				$strcfredirectN = "selected";
			} else $strcfredirectN = "";
			if ( $dbcf_redirect != null && $dbcf_redirect == "Y" ) {
				$strcfredirectY = "selected";
			} else $strcfredirectY = "";
			if ( $dbsite_type != null && $dbsite_type == "webtoon" ) {
				$strdbsiteWebtoon = "selected";
			} else $strdbsiteWebtoon = "";
			if ( $dbsite_type != null && $dbsite_type == "manga" ) {
				$strdbsiteManga = "selected";
			} else $strdbsiteManga = "";
			if ( $dbmainview != null && $dbmainview == "Y" ) {
				$strmainviewY = "selected";
				$strmainviewN = "";
			} else {
				$strmainviewY = "";
				$strmainviewN = "selected";
			}
			if ( $dbordernum == null ) {
				$dbordernum = "99999";
			}
			if ( $dbupdateyn != null && $dbupdateyn == "Y" ) {
				$strupdateynY = "selected";
				$strupdateynN = "";
			} else {
				$strupdateynY = "";
				$strupdateynN = "selected";
			}
			if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
				$strupdateexecuteY = "selected";
				$strupdateexecuteN = "";
			} else {
				$strupdateexecuteY = "";
				$strupdateexecuteN = "selected";
			}
			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='width:35%;font-size:15px;font-weight:bold;color:#000000;' rowspan='2'>SITE_ID</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='SITE_ID' value='".$dbsite_id."' readonly></td></tr>";
			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input type='button' style='border:solid 1px blue; line-height:30px; width:50%;font-weight:bold;text-align:center;background-color: #f8f8f8;' value='주소 업데이트' onClick='location.href=\"../".$dbsite_type."/".$dbserver_path."/update.php\";'><input type='button' style='border:solid 1px red; line-height:30px; width:50%;font-weight:bold;text-align:center;background-color: #f8f8f8;' value='사이트 크롤링' onClick='location.href=\"./geturl.php?target_url=".urlencode($dbsite_url.str_replace("{page}",$p,$recentUrl)."?".$dbrecent_param)."\";'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>SITE_NAME</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='SITE_NAME' value='".$dbsite_name."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>SITE_ALIAS</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='SITE_ALIAS' value='".$dbsite_alias."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>SITE_URL</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:90%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='SITE_URL' value='".$dbsite_url."'>";
			echo "<img src='./img/shortcuts.png' style='padding:0;margin:0;max-height:30px;width:10%;' onClick='window.open(\"".urldecode($dbsite_url)."\");'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>SITE_TYPE</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;text-align-last:center;'><select name='SITE_TYPE' style='border:none; font-size:20px; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;'><option value='manga' ".$strdbsiteManga.">만화책</option><option value='webtoon' ".$strdbsiteWebtoon.">웹툰</option></select></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>SERVER_PATH</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='SERVER_PATH' value='".$dbserver_path."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>USE_LEVEL</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='USE_LEVEL' value='".$dbuse_level."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>메인노출여부</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;text-align-last:center;'><select name='MAIN_VIEW' style='border:none; font-size:20px; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;'><option value='N' ".$strmainviewN.">미노출</option><option value='Y' ".$strmainviewY.">노출</option></select></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>노출순서</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='ORDER_NUM' value='".$dbordernum."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>업데이트 성공여부</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;text-align-last:center;'><select name='UPDATE_YN' style='border:none; font-size:20px; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;'><option value='N' ".$strupdateynN.">실패</option><option value='Y' ".$strupdateynY.">성공</option></select></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>업데이트 실행여부</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;text-align-last:center;'><select name='UPDATE_EXECUTE' style='border:none; font-size:20px; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;'><option value='N' ".$strupdateynN.">업데이트 안함</option><option value='Y' ".$strupdateynY.">업데이트 실행</option></select></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>SEARCH_URL</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='SEARCH_URL' value='".$dbsearch_url."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>SEARCH_PARAM</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='SEARCH_PARAM' value='".$dbsearch_param."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>RECENT_URL</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='RECENT_URL' value='".$dbrecent_url."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>RECENT_PARAM</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='RECENT_PARAM' value='".$dbrecent_param."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>ENDED_URL</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='ENDED_URL' value='".$dbended_url."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>ENDED_PARAM</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='ENDED_PARAM' value='".$dbended_param."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>LIST_URL</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='LIST_URL' value='".$dblist_url."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>LIST_PARAM</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='LIST_PARAM' value='".$dblist_param."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>VIEW_URL</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='VIEW_URL' value='".$dbview_url."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>VIEW_PARAM</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='VIEW_PARAM' value='".$dbview_param."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>Updated</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='UPTDTIME' value='".$dbuptdtime."'></td>\n";
			echo "</tr>\n";

			echo "<tr style='height:30px;'>\n";
			echo "<td align='center' style='font-size:15px;font-weight:bold;color:#000000;'>NOTE</td>";
			echo "<td align='center' style=';font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:90%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='text' name='NOTE' value='".$dbnote."'>";
			echo "<img src='./img/shortcuts.png' style='padding:0;margin:0;max-height:30px;width:10%;' onClick='window.open(\"".urldecode($dbnote)."\");'></td>\n";
			echo "</tr>\n";

		}
	}


?>
			<tr style='height:30px;'>
			<td colspan='2' align='center' style='font-size:15px;font-weight:bold;color:#000000;'><input style='border:none; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' type='submit' name='submit' value='저장하기'></td>
			</tr>
					</form></table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
