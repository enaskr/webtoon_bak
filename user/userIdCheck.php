<?php
	$server_path = (str_replace(basename(__FILE__), "", realpath(__FILE__)));
	$http_path = (str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]));
	include($server_path.'../lib/dbconn.php');
	$cookieMbrNo = $_COOKIE["MBRNO"];
	$webtoonDB = new SQLite3($server_path.'../lib/webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){
		if ( $USER_LEVEL == 99999 ) {
			$userList = "SELECT MBR_NO, USER_ID, USER_PASSWD, USER_NAME, EMAIL, PHONE, USER_STATUS, REGDTIME, UPTDTIME FROM USER_INFO WHERE USER_ID = '".$_GET["userid"]."'; ";
			$webtoonView = $webtoonDB->query($userList);
			$viewDate = "";
			$alreadView = "";
			while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
				$memmbr_no = $row["MBR_NO"];
				$memuserID = $row["USER_ID"];
			}
		}
	}
	if ( $memmbr_no != null && strlen($memmbr_no) > 0 ) {
		echo "Y";
	} else {
		echo "N";
	}
?>
