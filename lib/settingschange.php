<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
	if ( $USER_LEVEL >= 99999 ) {
		$conf_sql = "SELECT CONF_NAME, CONF_VALUE, CONF_ADD1, CONF_ADD2, REGDTIME FROM SERVER_CONFIG WHERE CONF_NAME='".$_POST["CONF_NAME"]."';";
		$conf_result = $systemDB->query($conf_sql);
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$conf_name = $conf["CONF_NAME"];
			$conf_value = $conf["CONF_VALUE"];
			$conf_add1 = $conf["CONF_ADD1"];
			$conf_add2 = $conf["CONF_ADD2"];
			if ( strlen($_POST["CONF_VALUE"]) == 0 ) {
				$userList = "DELETE FROM SERVER_CONFIG WHERE CONF_NAME = '".$_POST["CONF_NAME"]."'; ";
				$systemDB->exec($userList)
?>
<script type="text/javascript">
	alert("설정 정보를 정상적으로 삭제하였습니다.");
	window.history.back();
</script>
<?php
			} else if ( $conf_value != $_POST["CONF_VALUE"] ) {
				// UPDATE
				$userList = "UPDATE SERVER_CONFIG SET CONF_VALUE='".$_POST["CONF_VALUE"]."', CONF_ADD1='".$_POST["CONF_ADD1"]."', CONF_ADD2='".$_POST["CONF_ADD2"]."', REGDTIME='".date("YmdHis", time())."' WHERE CONF_NAME = '".$_POST["CONF_NAME"]."'; ";
				$systemDB->exec($userList)
?>
<script type="text/javascript">
	window.history.back();
</script>
<?php
			}
		}
		if ( $conf_name != $_POST["CONF_NAME"] ) { 
			// INSERT
			$userList = "INSERT INTO SERVER_CONFIG (CONF_NAME, CONF_VALUE, CONF_ADD1, CONF_ADD2, REGDTIME) VALUES ('".$_POST["CONF_NAME"]."', '".$_POST["CONF_VALUE"]."', '".$_POST["CONF_ADD1"]."', '".$_POST["CONF_ADD2"]."', '".date("YmdHis", time())."'); ";
			$systemDB->exec($userList)
?>
<script type="text/javascript">
	window.history.back();
</script>
<?php
		}
	}
?>
</body>
</html>
