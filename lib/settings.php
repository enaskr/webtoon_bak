<?php
	$server_path = (str_replace(basename(__FILE__), "", realpath(__FILE__)));
	$http_path = (str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]));
	include($server_path.'../lib/header.php');
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
						<tr>
							<td align="center" style='font-size:15px;color:#000000;width:25%;'>설정명</td><td align="center" style='font-size:15px;color:#000000;width:15%;'>설정값</td><td align="center" style='font-size:15px;color:#000000;width:30%;'>추가값1</td><td align="center" style='font-size:15px;color:#000000;width:30%;'>추가값2</td>
						</tr>
<?php

	if ( $USER_LEVEL < 99999 ) {
		$readonly = " readonly ";
	} else $readonly = "";
	$conf_result = $webtoonDB->query("SELECT CONF_NAME, CONF_VALUE, CONF_ADD1, CONF_ADD2, REGDTIME FROM SERVER_CONFIG;");
	while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
		echo "<tr style='background-color:#f8f8f8'>\n";
		echo "<form method='post'><input type='hidden' name='CONF_NAME' value='".$conf["CONF_NAME"]."'>";
		echo "<td style='font-size:15px;color:#8000ff;text-align:center;' valign=middle>";
		echo $conf["CONF_NAME"];
		echo "</td>\n";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle>";
		echo "<input type='text' style='border:none; line-height:30px; width:100%;text-align:center;' name='CONF_VALUE' value='".$conf['CONF_VALUE']."' ".$readonly."></td>\n";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle>";
		echo "<input type='text' style='border:none; line-height:30px; width:100%;text-align:center;' name='CONF_ADD1' value='".$conf['CONF_ADD1']."' ".$readonly."></td>\n";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle>";
		echo "<input type='text' style='border:none; line-height:30px; width:80%;text-align:center;' name='CONF_ADD2' value='".$conf['CONF_ADD2']."' ".$readonly.">";
		echo "<input type='button' name='savebtn' value='S' style='border:none;line-height:30px;width:20%;' onClick='saveSetting(this.form);'></td>\n";
		echo "</form></tr>\n";
	}
	if ( $USER_LEVEL == 99999 ) {
		echo "<form method='post'><tr style='background-color:#f8f8f8'>";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:100%;text-align:center;' name='CONF_NAME' value=''></td>";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:100%;text-align:center;' name='CONF_VALUE' value=''></td>";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:100%;text-align:center;' name='CONF_ADD1' value=''></td>";
		echo "<td style='font-size:15px;color:#8000ff;' align=center valign=middle><input type='text' style='border:none; line-height:30px; width:80%;text-align:center;' name='CONF_ADD2' value=''><input type='button' name='savebtn' value='＋' style='border:none;line-height:30px;width:20%;' onClick='saveSetting(this.form);'></td></form>";
		echo "</tr>\n";
	}
?>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
