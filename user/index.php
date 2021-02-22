<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><a href="<?= $homeurl ?>user/">회원 목록</a></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
						<tr>
							<td rowspan="2" width="70" align="center" style='font-size:15px;color:#000000;'>회원번호</td><td width="60" align="center" style='font-size:15px;color:#000000;'>아이디</td><td align="center" style='font-size:15px;color:#000000;'>이메일</td><td width="80" align="center" style='font-size:15px;color:#000000;'>상태</td>
						</tr>
						<tr>
							<td align="center" style='font-size:15px;color:#000000;'>성명</td><td align="center" style='font-size:15px;color:#000000;'>전화번호</td><td align="center" style='font-size:15px;color:#000000;'>가입일</td>
						</tr>
<?php
	if ( $USER_LEVEL >= 99999 ) {
		$userList = "SELECT MBR_NO, USER_ID, USER_NAME, EMAIL, PHONE, CASE WHEN USER_STATUS='OK' THEN '정상' WHEN USER_STATUS='WAIT' THEN '승인대기' WHEN USER_STATUS='REJECT' THEN '승인거절' WHEN USER_STATUS='APPROVED' THEN '승인완료' END AS STATUS, REGDTIME FROM USER_INFO ORDER BY REGDTIME DESC, USER_ID; ";
	} else {
		$userList = "SELECT MBR_NO, USER_ID, USER_NAME, EMAIL, PHONE, CASE WHEN USER_STATUS='OK' THEN '정상' WHEN USER_STATUS='WAIT' THEN '승인대기' WHEN USER_STATUS='REJECT' THEN '승인거절' WHEN USER_STATUS='APPROVED' THEN '승인완료' END AS STATUS, REGDTIME FROM USER_INFO WHERE USER_ID='".$USER_ID."' ORDER BY REGDTIME DESC, USER_ID; ";
	}

	$webtoonView = $webtoonDB->query($userList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$memmbr_no = $row["MBR_NO"];
		$memuserID = $row["USER_ID"];
		$memuserName = $row["USER_NAME"];
		$memuserEmail = $row["EMAIL"];
		$memuserPhone = $row["PHONE"];
		$memuserStatus = $row["STATUS"];
		$memuserCreated = $row["REGDTIME"];
		$memuserCreated = substr($memuserCreated,0,10);

		echo "<tr style='background-color:#f8f8f8' onClick=\"location.href='./userdetail.php?userid=".$memuserID."';\">";
		echo "<td rowspan='2' style='font-size:15px;color:#8000ff;' align=center valign=middle>".$memmbr_no."</td>";
		echo "<td style='height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserID."</td>";
		echo "<td style='height:30px;font-size:13px;color:#8000ff;' align=center valign=middle>".$memuserEmail."</td>";
		echo "<td style=;height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserStatus."</td>";
		echo "</tr>\n";
		echo "<tr style='background-color:#f8f8f8' onClick=\"location.href='./userdetail.php?userid=".$memuserID."';\">";
		echo "<td style='height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserName."</td>";
		echo "<td style='height:30px;font-size:13px;color:#8000ff;' align=center valign=middle>".$memuserPhone."</td>";
		echo "<td style='height:30px;font-size:15px;color:#8000ff;' align=center valign=middle>".$memuserCreated."</td>";
		echo "</tr>\n";

	}
?>
						<tr style='background-color:#f8f8f8' height='5'><td colspan="4" width="100%" align="center" valign="middle"></td></tr>
						<tr style='background-color:#f8f8f8' height='50'><td colspan="4" width="100%" align="center" valign="middle"><input style="border:none;line-height:48px;width:100%;font-weight:900;color:#0000ff;" type="button" value="회원 등록" onClick="location.href='./userform.php';"></td></tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
