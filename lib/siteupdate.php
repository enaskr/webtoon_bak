<?php
	include('../lib/config.php');
	if ( $USER_LEVEL >= 99999 ) {
		$conf_sql = "SELECT SITE_ID, SITE_NAME, SERVER_PATH FROM SITE_INFO WHERE SITE_ID='".$_POST["SITE_ID"]."';";
		$conf_result = $systemDB->query($conf_sql);
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$toonsiteid = $conf["SITE_ID"];
			$toonsitename = $conf["SITE_NAME"];
			$pathname = $conf["SERVER_PATH"];
			if ( strlen($_POST["SERVER_PATH"]) == 0 ) {
				$userList = "UPDATE SITE_INFO SET USE_YN='N', UPTDTIME='".date("Y.m.d H:i:s", time())."' WHERE SITE_ID='".$_POST["SITE_ID"]."' AND USE_YN='N' ; ";
				$systemDB->exec($userList)
?>
<script type="text/javascript">
	alert("사이트 정보를 정상적으로 삭제하였습니다.");
	window.history.back();
</script>
<?php
			} else if ( $_POST["SERVER_PATH"]!=null && strlen($_POST["SERVER_PATH"])>0 ) {
				// UPDATE
				$userList = "UPDATE SITE_INFO SET SITE_NAME='".$_POST["SITE_NAME"]."', SITE_ALIAS='".$_POST["SITE_ALIAS"]."', SITE_URL='".$_POST["SITE_URL"]."',  ";
				$userList = $userList." SITE_TYPE='".$_POST["SITE_TYPE"]."', SERVER_PATH='".$_POST["SERVER_PATH"]."', USE_LEVEL='".$_POST["USE_LEVEL"]."',  ";
				$userList = $userList." SEARCH_URL='".$_POST["SEARCH_URL"]."', SEARCH_PARAM='".$_POST["SEARCH_PARAM"]."', RECENT_URL='".$_POST["RECENT_URL"]."',  ";
				$userList = $userList." RECENT_PARAM='".$_POST["RECENT_PARAM"]."', ENDED_URL='".$_POST["ENDED_URL"]."', ENDED_PARAM='".$_POST["ENDED_PARAM"]."',  ";
				$userList = $userList." LIST_URL='".$_POST["LIST_URL"]."', LIST_PARAM='".$_POST["LIST_PARAM"]."', VIEW_URL='".$_POST["VIEW_URL"]."',  ";
				$userList = $userList." VIEW_PARAM='".$_POST["VIEW_PARAM"]."', USE_YN='Y', NOTE='".$_POST["NOTE"]."', MAIN_VIEW='".$_POST["MAIN_VIEW"]."', ";
				$userList = $userList." ORDER_NUM='".$_POST["ORDER_NUM"]."', UPDATE_YN='".$_POST["UPDATE_YN"]."', UPDATE_EXECUTE='".$_POST["UPDATE_EXECUTE"]."', UPTDTIME='".date("Y.m.d H:i:s", time())."' ";
				$userList = $userList." WHERE SITE_ID = '".$_POST["SITE_ID"]."' ; ";
				//echo "SQL=".$userList;
				$query = $systemDB->exec($userList);
				if ( $query ) {
				if ( $systemDB->changes() > 0 ) {
?>
<script type="text/javascript">
	window.history.back();
</script>
<?php
				} else {
?>
<script type="text/javascript">
	alert("사이트 정보를 변경하지 못하였습니다");
	window.history.back();
</script>
<?php
				}
				} else {
?>
<script type="text/javascript">
	alert("사이트 정보를 변경하지 못하였습니다.");
	window.history.back();
</script>
<?php
				}
			}
		}
	}
?>
</body>
</html>
