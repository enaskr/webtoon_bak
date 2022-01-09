<?php
	session_start();
	$cookieMBRID = "";
	if (isset($_COOKIE['MBRID']) ) {
		$cookieMBRID = $_COOKIE['MBRID']; 
	}
	$canView = false;
	$isLogin = false;
	$viewAdultSite = false;
	$viewAdultUser = false;
	$loginDuration = 60;
	$config = array();
	$USER_LEVEL = 0;
	$USER_ID = "";


	$systemDB = new SQLite3($homepath.'lib/system.db');
	if($systemDB->lastErrorCode() == 0){
		$conf_result = $systemDB->query("SELECT CONF_NAME, CONF_VALUE, CONF_ADD1, CONF_ADD2, REGDTIME FROM SERVER_CONFIG;");
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$config[$conf["CONF_NAME"]] = $conf['CONF_VALUE'];
			$config_add1[$conf["CONF_NAME"]] = $conf['CONF_ADD1'];
			$config_add2[$conf["CONF_NAME"]] = $conf['CONF_ADD2'];
		}
		if ( $config["view_adult"] == "Y" ) $viewAdultSite = true;
		$loginDuration = (int)$config["login_duration"];
//		$config["view_adult"] / $config["max_list"] / $config["try_count"] / $config["login_view"] / $config["search_seq"] / $config["login_duration"]

		$sql = "SELECT SITE_ID, SITE_NAME, SITE_URL, SITE_TYPE, SERVER_PATH, USE_LEVEL, SEARCH_URL, SEARCH_PARAM, RECENT_URL, RECENT_PARAM, ENDED_URL, ENDED_PARAM, LIST_URL, LIST_PARAM, VIEW_URL, VIEW_PARAM, MAIN_VIEW, ORDER_NUM, UPDATE_YN FROM SITE_INFO WHERE SERVER_PATH = '".$lastpath."' AND USE_YN='Y' LIMIT 1;";
		$conf_result = $systemDB->query($sql);
		$useLevel = 0;
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$siteId = $conf["SITE_ID"];
			$siteName = $conf["SITE_NAME"];
			$siteUrl = $conf["SITE_URL"];
			if ( endsWith($siteUrl, "/") == true ) $siteUrl = substr($siteUrl, 0, strlen($siteUrl)-1);
			$siteType = $conf["SITE_TYPE"];
			$serverPath = $conf["SERVER_PATH"];
			$useLevel = (int)$conf["USE_LEVEL"];
			$searchUrl = $conf["SEARCH_URL"];
			if ( startsWith($searchUrl, "/") != true ) $searchUrl = "/".$searchUrl;
			$searchParam = $conf["SEARCH_PARAM"];
			$recentUrl = $conf["RECENT_URL"];
			if ( startsWith($recentUrl, "/") != true ) $recentUrl = "/".$recentUrl;
			$recentParam = $conf["RECENT_PARAM"];
			$endedUrl = $conf["ENDED_URL"];
			if ( startsWith($endedUrl, "/") != true ) $endedUrl = "/".$endedUrl;
			$endedParam = $conf["ENDED_PARAM"];
			$listUrl = $conf["LIST_URL"];
			if ( startsWith($listUrl, "/") != true ) $listUrl = "/".$listUrl;
			$listParam = $conf["LIST_PARAM"];
			$viewUrl = $conf["VIEW_URL"];
			if ( startsWith($viewUrl, "/") != true ) $viewUrl = "/".$viewUrl;
			$viewParam = $conf["VIEW_PARAM"];
			$mainView = $conf["MAIN_VIEW"];
			$orderNum = $conf["ORDER_NUM"];
			$updateYn = $conf["UPDATE_YN"];
		}

	} else {
		echo "System Database connection failed";
		echo $systemDB->lastErrorMsg();
	}

	$webtoonDB = new SQLite3($homepath.'lib/webtoon.db');
	if($webtoonDB->lastErrorCode() == 0){

		if ( $cookieMBRID != null && strlen($cookieMBRID) > 0 ) {
			define('KEY', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
			define('KEY_128', substr(KEY,0,128/8));
			define('KEY_256', substr(KEY,0,256/8));
			$mbr_id = openssl_decrypt($cookieMBRID, 'AES-256-CBC', KEY_256, 0, KEY_128);
			$mbrpos = explode("|", $mbr_id);
			$mbr_no = $mbrpos[0];
			$login_date = $mbrpos[1];
			$mbr_pass = $mbrpos[2];

			// SELECT
			$result = $webtoonDB->query("SELECT MBR_NO, USER_ID, USER_NAME, EMAIL, PHONE, USER_LEVEL, LAST_LOGIN_DTIME, LAST_LOGIN_IPADDRESS, LOGIN_FAIL_COUNT, VIEW_ADULT, REGDTIME FROM USER_INFO WHERE MBR_NO = '".$mbr_no."' AND USER_PASSWD = '".$mbr_pass."' AND USER_STATUS IN ('OK','APPROVED') AND USE_YN='Y' LIMIT 1;");
			while($row = $result->fetchArray(SQLITE3_ASSOC)){         
				$MBR_NO = $row["MBR_NO"];
				$USER_ID = $row["USER_ID"];
				$USER_NAME = $row["USER_NAME"];
				$EMAIL = $row["EMAIL"];
				$PHONE = $row["PHONE"];
				$USER_LEVEL = (int)$row["USER_LEVEL"];
				$LAST_LOGIN_DTIME = $row["LAST_LOGIN_DTIME"];
				$LAST_LOGIN_IPADDRESS = $row["LAST_LOGIN_IPADDRESS"];
				$LOGIN_FAIL_COUNT = $row["LOGIN_FAIL_COUNT"];
				$VIEW_ADULT = $row["VIEW_ADULT"];
				$REGDTIME = $row["REGDTIME"];
				$isLogin = true;

				if ( $useLevel <= $USER_LEVEL ) $canView = true;

				$mbrid = openssl_encrypt($MBR_NO."|".date("Ymd", time())."|".$mbr_pass, 'AES-256-CBC', KEY_256, 0, KEY_128);
				setcookie("MBRID", $mbrid, time()+($loginDuration*60), "/");  // 3600초 = 60초 * 60 분 * 1시간
			}
			if ( $isLogin != true ) {
				unset($_COOKIE["MBRID"]);
				setcookie("MBRID", "", time()-60, "/");
			}
			if ( $VIEW_ADULT == "Y" ) $viewAdultUser = true;
		}
		if ( $config["login_view"]!=null && $config["login_view"]=="Y" && $isLogin != true ) {
			unset($_COOKIE["MBRID"]);
			setcookie("MBRID", "", time()-60, "/");
			if ( $config["login_view"] == "Y" && ( strpos($_SERVER['REQUEST_URI'], "user/newUserJoin.php") != true && strpos($_SERVER['REQUEST_URI'], "user/userform.php") != true && strpos($_SERVER['REQUEST_URI'], "user/userjoin.php") != true && strpos($_SERVER['REQUEST_URI'], "user/userIdCheck.php") != true && strpos($_SERVER['REQUEST_URI'], "index.php") != true && strpos($_SERVER['REQUEST_URI'], "url.php") != true ) ) {
				Header("Location:".$homeurl);
			}
		}

	} else {
		echo "Database connection failed";
		echo $webtoonDB->lastErrorMsg();
	}

?>
