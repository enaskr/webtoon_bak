<?php
	$webtoonDB = new SQLite3('../lib/webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){
		$userList = "SELECT MBR_NO, USER_ID, USER_PASSWD, USER_NAME, EMAIL, PHONE, USER_STATUS, REGDTIME, UPTDTIME FROM USER_INFO WHERE USER_ID = '".$_GET["userid"]."'; ";
		$webtoonView = $webtoonDB->query($userList);
		$viewDate = "";
		$alreadView = "";
		while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
			$memmbr_no = $row["MBR_NO"];
			$memuserID = $row["USER_ID"];
		}
	}
	if ( $memmbr_no != null && strlen($memmbr_no) > 0 ) {
		echo "Y";
	} else {
		echo "N";
	}
?>

