<?php
	include('./simple_html_dom.php');
	$userid = $_POST["user"];
	$userpw = $_POST["pass"];
	$loginDuration = 60;
	$systemDB = new SQLite3('./system.db');
	if($systemDB->lastErrorCode() == 0){
		$conf_result = $systemDB->query("SELECT CONF_VALUE FROM SERVER_CONFIG WHERE CONF_NAME='login_duration';");
		while($config = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$loginDuration = (int)$config["CONF_VALUE"];
		}
		$loginDuration = $loginDuration * 60;
	} else {
?>
<script type="text/javascript">
	alert("System DB연결시 오류가 발생했습니다.");
	window.history.back();
</script>
<?php
		echo $systemDB->lastErrorMsg();
	}

	$webtoonDB = new SQLite3('./webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){
		if ( $userid !=null && strlen($userid) > 3 && $userpw !=null && strlen($userpw) > 3 ) {
			$userpassword = strtoupper(hash("sha256", $userpw));
			$result = $webtoonDB->query("SELECT MBR_NO, USER_ID, LOGIN_COUNT, USER_NAME, EMAIL, PHONE, REGDTIME FROM USER_INFO WHERE USER_ID = '".$userid."' AND USER_PASSWD = '".$userpassword."' AND USER_STATUS IN ('OK','APPROVED') AND USE_YN='Y' LIMIT 1;");
			while($row = $result->fetchArray(SQLITE3_ASSOC)){
				$userMbrno = $row["MBR_NO"];
				$loginCount = (int)$row["LOGIN_COUNT"];

				$userList = "UPDATE USER_INFO SET LAST_LOGIN_DTIME='".date("Y.m.d H:i:s", time())."', LOGIN_COUNT = '".($loginCount+1)."', LOGIN_FAIL_COUNT = '0', LAST_LOGIN_IPADDRESS = '".getRealClientIp()."' WHERE MBR_NO = '".$userMbrno."'; ";
				$cnt = $webtoonDB->exec($userList);

				define('KEY', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
				define('KEY_128', substr(KEY,0,128/8));
				define('KEY_256', substr(KEY,0,256/8));
				$mbrid = openssl_encrypt($userMbrno."|".date("Ymd", time())."|".$userpassword, 'AES-256-CBC', KEY_256, 0, KEY_128);
				setcookie("MBRID", $mbrid, time()+$loginDuration, "/");
				Header("Location:../index.php"); 
			}
			if ( $userMbrno==null || strlen($userMbrno) == 0) {
?>
<script type="text/javascript">
	alert("아이디와 비밀번호를 정확하게 입력해주세요.");
	window.history.back();
</script>
<?php
			} else {
				$userList = "UPDATE USER_INFO SET LOGIN_FAIL_COUNT = LOGIN_FAIL_COUNT+1, LAST_LOGIN_IPADDRESS = '".getRealClientIp()."' WHERE MBR_NO = '".$userMbrno."'; ";
				$cnt = $webtoonDB->exec($userList);

			}
		} else {
?>
<script type="text/javascript">
	alert("아이디와 비밀번호를 정확하게 입력해주세요");
	window.history.back();
</script>
<?php
		}
	} else {
?>
<script type="text/javascript">
	alert("DB연결시 오류가 발생했습니다.");
	window.history.back();
</script>
<?php
		echo $webtoonDB->lastErrorMsg();
	}
?></body>
</html>
