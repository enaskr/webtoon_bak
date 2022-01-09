<?php

	$config = array();

	$systemDB = new SQLite3($homepath.'lib/system.db');
	if($systemDB->lastErrorCode() == 0){
		$conf_result = $systemDB->query("SELECT CONF_NAME, CONF_VALUE, CONF_ADD1, CONF_ADD2, REGDTIME FROM SERVER_CONFIG;");
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$config[$conf["CONF_NAME"]] = $conf['CONF_VALUE'];
			$config_add1[$conf["CONF_NAME"]] = $conf['CONF_ADD1'];
			$config_add2[$conf["CONF_NAME"]] = $conf['CONF_ADD2'];
		}

		$sql = "SELECT SITE_ID, SITE_NAME, SITE_URL, SITE_TYPE, SERVER_PATH, USE_LEVEL, SEARCH_URL, SEARCH_PARAM, RECENT_URL, RECENT_PARAM, ENDED_URL, ENDED_PARAM, LIST_URL, LIST_PARAM, VIEW_URL, VIEW_PARAM, MAIN_VIEW, ORDER_NUM, UPDATE_YN FROM SITE_INFO WHERE SITE_ID = '".$SITE_ID."' AND USE_YN='Y' LIMIT 1;";
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

?>
