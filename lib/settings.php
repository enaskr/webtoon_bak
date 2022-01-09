<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
?>
<script type="text/javascript">
	function saveSetting(frm, idx) {
		if ( frm.CONF_NAME.value == "" ) {
			alert("conf_name을 입력해주세요.");
			return false;
		}
		if ( frm.CONF_VALUE.value == "" ) {
			if ( confirm(frm.CONF_NAME.value+"을 삭제하시겠습니까?") ) {
				frm.action = "./settingschange.php";
				frm.submit();
			} else {
				return false;
			}
		} else {
			frm.action = "./settingschange.php";
			frm.submit();
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>설정 목록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php

	if ( $USER_LEVEL >= 99999 ) {
?>
						<tr>
							<td align="center" style='font-size:15px;color:#000000;width:50%;'>설정명</td><td align="center" style='font-size:15px;color:#000000;width:50%;'>설정값</td>
						</tr>
<?php
		$conf_result = $systemDB->query("SELECT CONF_NAME, CONF_VALUE, CONF_ADD1, CONF_ADD2, REGDTIME FROM SERVER_CONFIG;");
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			echo "<tr style='background-color:#f8f8f8'>\n";
			echo "<form method='post'><input type='hidden' name='CONF_NAME' value='".$conf["CONF_NAME"]."'>";
			echo "<td style='font-size:15px;color:#8000ff;text-align:center;' valign=middle>";
			echo $conf["CONF_NAME"];
			echo "</td>\n";
			echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle>";
			echo "<input type='text' style='border:none; line-height:30px; width:80%;text-align:center;' name='CONF_VALUE' value='".$conf['CONF_VALUE']."' ".$readonly.">";
			echo "<input type='button' name='savebtn' value='S' style='border:none;line-height:30px;width:20%;' onClick='saveSetting(this.form);'></td>\n";
			echo "</form></tr>\n";
		}
		echo "<form method='post'><tr style='background-color:#f8f8f8'>";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:100%;text-align:center;' name='CONF_NAME' value=''></td>";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:80%;text-align:center;' name='CONF_VALUE' value=''><input type='button' name='savebtn' value='＋' style='border:none;line-height:30px;width:20%;' onClick='saveSetting(this.form);'></td></form>";
		echo "</tr>\n";
	} else {
?>
						<tr>
							<td align="center" style='font-size:15px;font-weight:bold;color:#000000;height:100px;width:100%;'>권한이 없습니다.<br><br>관리자에게 문의하세요.</td>
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
