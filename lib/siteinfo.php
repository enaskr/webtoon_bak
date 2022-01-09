<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
?>
<script type="text/javascript">
	function saveSetting(frm) {
		if ( frm.PATHNAME.value == "" ) {
			if ( confirm(frm.TOONSITENAME.value+"을 삭제하시겠습니까?") ) {
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
			<dt>웹툰 사이트 정보</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php
	if ( $USER_LEVEL == 99999 ) {
?>
						<tr style='height:30px;'>
							<td align="center" style='width:25%;font-size:15px;font-weight:bold;color:#000000;'>ID</td>
							<td align="center" style='width:10%;font-size:15px;font-weight:bold;color:#000000;'>구분</td>
							<td align="center" style='width:25%;font-size:15px;font-weight:bold;color:#000000;'>사이트명</td>
							<td align="center" style='width:15%;font-size:15px;font-weight:bold;color:#000000;'>메인YN</td>
							<td align="center" style='width:25%;font-size:15px;font-weight:bold;color:#000000;'>업데이트실행</td>
						</tr>
<?php
		$toonsiteList = "SELECT SITE_ID, SITE_NAME, SITE_TYPE, SERVER_PATH, MAIN_VIEW, UPDATE_YN, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE, UPTDTIME FROM SITE_INFO ORDER BY SITE_TYPE DESC, SITE_NAME ASC; ";
		$webtoonView = $systemDB->query($toonsiteList);
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
			$dbsiteid = $row["SITE_ID"];
			$dbsitename = $row["SITE_NAME"];
			$dbsitetype = $row["SITE_TYPE"];
			$dbserverpath = $row["SERVER_PATH"];
			$dbuptdtime = $row["UPTDTIME"];
			$dbmainview = $row["MAIN_VIEW"];
			$dbupdateyn = $row["UPDATE_YN"];
			$dbupdateexecute = $row["UPDATE_EXECUTE"];

			echo "<tr style='background-color:#f8f8f8;height:50px;' onClick=\"location.href='./sitedetail.php?siteid=".$dbsiteid."';\">";
			echo "<td style='font-size:15px;' align=center valign=middle><input type='text' style='border:none; line-height:15px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' name='SITE_ID' value='".$dbsiteid."' readonly></td>";
			echo "<td style='font-size:15px;' align=center valign=middle><input type='text' style='border:none; line-height:15px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' name='SITE_TYPE' value='".strtoupper(substr($dbsitetype,0,1))."' readonly></td>";
			echo "<td style='font-size:15px;' align=center valign=middle><input type='text' style='border:none; line-height:15px; width:100%;font-weight:bold;text-align:center;color:#3300ff;background-color: #f8f8f8;' name='SITE_NAME' value='".$dbsitename."' readonly></td>";
			echo "<td style='font-size:15px;' align=center valign=middle><input type='text' style='border:none; line-height:15px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' name='MAIN_VIEW' value='".$dbmainview."'></td>";
			echo "<td style='font-size:15px;' align=center valign=middle><input type='text' style='border:none; line-height:15px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;' name='UPDATE_EXECUTE' value='".$dbupdateexecute."'></td>";
			echo "</tr>\n";
		}
	} else {
?>
						<tr style='height:100px;'>
							<td align="center" colspan="5" style='width:100%;font-size:15px;font-weight:bold;color:#000000;'>권한이 없습니다.<br><br>관리자에게 문의하세요.</td>
						</tr>
<?php
	}


?>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
