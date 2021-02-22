<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
	// 3930=84D5D511604A52AF969027F7263390A3D8B1E351D13AB720A1D99D98A2CEA341, aass=EF18B90AEC166CB245D316F5489D09FAC8C3EDC58496095C375B59989D6FABC1, sjvawebtoon=D7AC9D9D35A5BE404FF25BF83A91939ABBC0F1DA8E97473184AFA383D6DC2557
?>
<script type="text/javascript">
	function deleteUser(frm) {
		if ( confirm("회원을 삭제하시겠습니까?") ) {
			frm.action = "./userdelete.php";
			frm.submit();
		}
	}
	function changePassword(frm) {
<?php
	if ( $USER_LEVEL < 99999 ) {
?>
		if ( frm.userpassword.value == "" ) {
			alert("이전비밀번호를 입력해주세요.");
			return false;
		}
<?php
	}
?>
		if ( frm.newuserpassword.value == "" ) {
			alert("비밀번호를 입력해주세요.");
			return false;
		} else if ( frm.newuserpassword.value.length < 4 ) {
			alert("비밀번호는 4글자 이상 입력해야합니다.");
			return false;
		} else {
			frm.uptMode.value = "PWD";
			frm.action = "./userupdate.php";
			frm.submit();
		}
	}
	function changeUserinfo(frm) {
		if ( frm.useremail.value == "" && frm.userphone.value == "" ) {
			alert("이메일과 전화번호 중 1개는 반드시 입력해야합니다.");
			return false;
		} else {
			frm.uptMode.value = "info";
			frm.action = "./userupdate.php";
			frm.submit();
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><a href="<?= $homeurl ?>user/index.php"><?php echo $_GET["userid"]; ?>님 회원 정보</a></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<form name="userForm" method="post" action="<?= $homeurl ?>user/userupdate.php"><input type="hidden" name="userid" value="<?php echo $_GET["userid"];  ?>"><input type="hidden" name="uptMode" value="info">
<?php
	if ( $USER_LEVEL >= 99999 ) {
		$userList = "SELECT MBR_NO, USER_ID, USER_PASSWD, USER_NAME, EMAIL, PHONE, USER_STATUS, USER_LEVEL, LAST_LOGIN_DTIME, LAST_LOGIN_IPADDRESS, LOGIN_FAIL_COUNT, LOGIN_COUNT, VIEW_ADULT, REGDTIME, UPTDTIME FROM USER_INFO WHERE USER_ID = '".$_GET["userid"]."'; ";
	} else {
		$userList = "SELECT MBR_NO, USER_ID, USER_PASSWD, USER_NAME, EMAIL, PHONE, USER_STATUS, USER_LEVEL, LAST_LOGIN_DTIME, LAST_LOGIN_IPADDRESS, LOGIN_FAIL_COUNT, LOGIN_COUNT, VIEW_ADULT, REGDTIME, UPTDTIME FROM USER_INFO WHERE USER_ID = '".$USER_ID."'; ";
	}
		$webtoonView = $webtoonDB->query($userList);
		$viewDate = "";
		$alreadView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
			$mbr_no = $row["MBR_NO"];
			$memuserID = $row["USER_ID"];
			$memuserPass = $row["USER_PASSWD"];
			$memuserName = $row["USER_NAME"];
			$memuserEmail = $row["EMAIL"];
			$memuserPhone = $row["PHONE"];
			$memuserStatus = $row["USER_STATUS"];
			$memuserLevel = $row["USER_LEVEL"];
			$memuserViewAdult = $row["VIEW_ADULT"];
			$memuserLoginDate = $row["LAST_LOGIN_DTIME"];
			$memuserLoginIpaddress = $row["LAST_LOGIN_IPADDRESS"];
			$memuserLoginCount = $row["LOGIN_COUNT"];
			$memuserLoginFailCount = $row["LOGIN_FAIL_COUNT"];
			$memuserCreated = $row["REGDTIME"];
			$memuserUpdated = $row["UPTDTIME"];
			if ( $memuserUpdated == null || strlen($memuserUpdated) < 14 ) $memuserUpdated = $memuserCreated;
			if ( $memuserStatus == "WAIT" ) $waitstr = "selected"; else $waitstr="";
			if ( $memuserStatus == "REJECT" ) $rejectstr = "selected"; else $rejectstr="";
			if ( $memuserStatus == "OK" ) $okstr = "selected"; else $okstr="";
			if ( $memuserStatus == "APPROVED" ) $approvedstr = "selected"; else $approvedstr="";
			if ( $memuserViewAdult != null && $memuserViewAdult == "Y" ) {
				$strmemuserViewAdultY = "selected";
				$strmemuserViewAdultN = "";
			} else {
				$strmemuserViewAdultY = "";
				$strmemuserViewAdultN = "selected";
			}

			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td width=30%>회원번호</td><td>".$mbr_no."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>아이디</td><td>".$memuserID."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>이전비번</td><td><input style='border:none; line-height:48px; width:100%;' type='password' name='userpassword' value=''></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>변경비번</td><td><input style='border:none; line-height:48px; width:70%;' type='password' name='newuserpassword' value=''><input style='border:none; line-height:48px; width:30%;' type='button' value='비번 변경' onClick='changePassword(this.form);'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>성명</td><td>".$memuserName."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>이메일</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=useremail value='".$memuserEmail."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>전화번호</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=userphone value='".$memuserPhone."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>성인웹툰</td><td align='center' style=';font-size:15px;font-weight:bold;color:#000000;text-align-last:center;'><select name='viewadult' style='border:none; font-size:20px; line-height:30px; width:100%;font-weight:bold;text-align:center;background-color: #f8f8f8;'><option value='N' ".$strmemuserViewAdultN.">미노출</option><option value='Y' ".$strmemuserViewAdultY.">노출</option></select></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>로그인일시</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=logindate value='".$memuserLoginDate."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>로그인 IP</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=loginip value='".$memuserLoginIpaddress."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>로그인횟수</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=logincnt value='".$memuserLoginCount."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>로그인실패횟수</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=loginfailcnt value='".$memuserLoginFailCount."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>회원등급</td><td><input style='border:none; line-height:48px; width:100%;' type=text name=userlevel value='".$memuserLevel."'></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>상태</td><td>";
			echo "<select name='userstatus' style='border:none;background-color:#f8f8f8;width:100%;'><option value='WAIT' ".$approvedstr.">승인대기</option><option value='REJECT' ".$rejectstr.">승인거절</option><option value='OK' ".$okstr.">정상</option><option value='APPROVED' ".$approvedstr.">승인완료</option></select></td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>가입일</td><td>".$memuserCreated."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50' align='center'><td>정보변경일</td><td>".$memuserUpdated."</td></tr>";
			echo "<tr style='background-color:#f8f8f8' height='50'><td colspan='2' align='center'><a href='./myview.php?mbrno=".$mbr_no."' style='line-height:48px;font-weight:900;width:95%;color:#0000ff;'>".$memuserName."님이 보고있는 목록</a></td></tr>";

		}
?>
						<tr style='background-color:#f8f8f8' height='50'><td colspan="2" align="center" valign="middle"><input style="border:none;line-height:48px;width:70%;font-weight:900;color:#0000ff;" type="submit" value="회원정보 변경"><input style="border:none;line-height:48px;width:30%;font-weight:900;color:#0000ff;" type="button" value="회원삭제" onClick="deleteUser(this.form);"></td></tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
