<?php
	include('../lib/config.php');
// MBR_NO, USER_ID, USER_PASSWD, USER_NAME, EMAIL, PHONE, USER_STATUS, USER_LEVEL, LAST_LOGIN_DTIME, LAST_LOGIN_IPADDRESS, LOGIN_FAIL_COUNT, LOGIN_COUNT, REGDTIME, UPTDTIME
	$userList = "SELECT MBR_NO, USER_ID, USER_PASSWD, USER_NAME, EMAIL, PHONE, USER_STATUS, REGDTIME, UPTDTIME FROM USER_INFO WHERE USER_ID = '".$_POST["userid"]."'; ";
	$webtoonView = $webtoonDB->query($userList);
	$member_no = "";
	$member_userid = $_POST["userid"];
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$memmbr_no = $row["MBR_NO"];
		$memuserID = $row["USER_ID"];
	}
	if ( strlen($memmbr_no) == 0 ) {
		if ( strlen(trim($member_userid)) == 0 ) {
	?>
	<script type="text/javascript">
		alert("비정상적인 경로로 접근하였습니다.");
		location.replace("<?= $homeurl ?>");
	</script>
	<?php
			return;
		}
		$usersql = "INSERT INTO 'USER_INFO' ('MBR_NO', 'USER_ID', 'USER_NAME', 'EMAIL', 'PHONE', 'USER_PASSWD', 'USER_STATUS', 'USER_LEVEL', 'USE_YN', 'REGDTIME', 'UPTDTIME') ";
		if ( $USER_LEVEL == 99999 ) {
			$usersql = $usersql." VALUES ('".$_POST["mbr_no"]."', '".$_POST["userid"]."','".$_POST["username"]."','".$_POST["useremail"]."','".$_POST["userphone"]."','".strtoupper(hash("sha256", $_POST["userpassword"]))."','".$_POST["userstatus"]."','".$_POST["userlevel"]."', 'Y', '".date("YmdHis", time())."', '".date("YmdHis", time())."');";
		} else {
			$usersql = $usersql." VALUES ('".$_POST["mbr_no"]."', '".$_POST["userid"]."','".$_POST["username"]."','".$_POST["useremail"]."','".$_POST["userphone"]."','".strtoupper(hash("sha256", $_POST["userpassword"]))."','WAIT','1', 'Y', '".date("YmdHis", time())."', '".date("YmdHis", time())."');";
		}
			$cnt = $webtoonDB->exec($usersql);
			if ( $cnt == 1 ) {
	?>
	<script type="text/javascript">
		alert("회원정보를 정상적으로 등록하였습니다.");
		location.replace("<?= $homeurl ?>");
	</script>
	<?php
			} else {
	?>
	<script type="text/javascript">
		alert("회원정보 등록에 실패했습니다.");
		location.replace("<?= $homeurl ?>");
	</script>
	<?php
			}
	} else {
	?>
	<script type="text/javascript">
		alert("이미 존재하는 회원입니다.");
		location.replace("<?= $homeurl ?>");
	</script>
	<?php
	}
	?>
</body>
</html>
