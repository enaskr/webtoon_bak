<?php
	$server_path = (str_replace(basename(__FILE__), "", realpath(__FILE__)));
	$http_path = (str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]));
	include($server_path.'../lib/config.php');

	if ( $USER_LEVEL >= 99999 ) {
		$userList = "DELETE FROM USER_INFO WHERE USER_ID = '".$_POST["userid"]."'; ";
		$webtoonDB->exec($userList)
?>
<script type="text/javascript">
	alert("회원을 삭제했습니다.");
	location.replace("<?= $homeurl ?>");
</script>
<?php
	} else {
?>
<script type="text/javascript">
	alert("권한이 없습니다.\n\n회원삭제는 관리자만 처리할 수 있습니다.");
	location.replace("<?= $homeurl ?>");
</script>
<?php
	}
?>
</body>
</html>
