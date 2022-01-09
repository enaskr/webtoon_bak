<?php
	include('../lib/config.php');
	$isSuccess = true;

	if ( $USER_LEVEL >= 99999 ) {
		if ( $_POST["uptMode"] == "PWD" ) {
			$userList = "UPDATE USER_INFO SET USER_PASSWD='".strtoupper(hash("sha256", $_POST["newuserpassword"]))."', UPTDTIME='".date("YmdHis", time())."' WHERE USER_ID = '".$_POST["userid"]."'; ";
		} else {
			$userList = "UPDATE USER_INFO SET USER_LEVEL='".$_POST["userlevel"]."', EMAIL='".$_POST["useremail"]."', PHONE='".$_POST["userphone"]."', VIEW_ADULT='".$_POST["viewadult"]."', USER_STATUS='".$_POST["userstatus"]."', UPTDTIME='".date("YmdHis", time())."' WHERE USER_ID = '".$_POST["userid"]."'; ";
		}
	} else {
		if ( $_POST["uptMode"] == "PWD" ) {
			$userPass = "SELECT MBR_NO, USER_ID, USER_PASSWD FROM USER_INFO WHERE USER_ID = '".$USER_ID."' AND USER_PASSWD = '".strtoupper(hash("sha256", $_POST["userpassword"]))."'; ";
			echo $userPass;
			$passView = $webtoonDB->query($userPass);
			while($row = $passView->fetchArray(SQLITE3_ASSOC)){
				$selUserid = $row["USER_ID"];
				$selPasswd = $row["USER_PASSWD"];
				echo "USER_ID=".$selUserid."<br>";
			}
			if ( $selUserid == null || strlen($selUserid) == 0 ) {
				$isSuccess = false;
			} else {
				$userList = "UPDATE USER_INFO SET USER_PASSWD='".strtoupper(hash("sha256", $_POST["newuserpassword"]))."', UPTDTIME='".date("YmdHis", time())."' WHERE USER_ID = '".$USER_ID."'  AND USER_PASSWD = '".strtoupper(hash("sha256", $_POST["userpassword"]))."'; ";
			}
		} else {
			$userList = "UPDATE USER_INFO SET EMAIL='".$_POST["useremail"]."', PHONE='".$_POST["userphone"]."', UPTDTIME='".date("YmdHis", time())."' WHERE USER_ID = '".$USER_ID."'; ";
		}
	}
		if ( $isSuccess ) {
			$cnt = $webtoonDB->exec($userList);
			if ( $cnt == 1 ) {
?>
<script type="text/javascript">
	alert("회원정보를 정상적으로 변경하였습니다.");
	window.history.back();
</script>
<?php
			} else {
?>
<script type="text/javascript">
	alert("회원정보를 변경하지 못하였습니다.");
	window.history.back();
</script>
<?php
			}
		} else {
?>
<script type="text/javascript">
	alert("이전 비밀번호가 다릅니다. 다시 입력해주세요.");
window.history.back();
</script>
<?php
		}
?>
</body>
</html>
