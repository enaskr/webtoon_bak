<?php
	header('Content-Type: text/html; charset=UTF-8');
	$filepath = "/share/webtoon/lib/";
	include($filepath.'simple_html_dom.php');
	$webtoonDB = new SQLite3($filepath.'webtoon.db');
	
	$try_count = 3;
	$search_seq = 50;

	echo "TOON URL Update Start : ".date("Y.m.d H:i:s", time())."\n";

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'COPYTOON'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 카피툰 & 툰사랑
		$newurl = "";
		$target = "https://jusoshow.me/";
		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($target);
			}
		}

		// 카피툰 (COPYTOON)
		foreach($get_html_contents->find('li') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				if ( $g->title == "카피툰" ) $newurl = $g->href;
				break;
			}
		}

		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'COPYTOON';");
			echo "COPYTOON => ".$newurl."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'COPYTOON';");
			echo "COPYTOON FAIL!\n";
		}
	}

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'TOONSARANG'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 툰사랑 (TOONSARANG)
		$newurl = "";
		foreach($get_html_contents->find('li') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				if ( $g->title == "툰사랑" ) $newurl = $g->href;
				break;
			}
		}

		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'TOONSARANG';");
			echo "TOONSARANG => ".$newurl."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'TOONSARANG';");
			echo "TOONSARANG FAIL!\n";
		}
	}

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'FUNBE'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 펀비 (FUNBE)
		//$target = "https://linktong1.com/bbs/board.php?bo_table=webtoon&wr_id=38";
		$newurl = "";
		$target = "https://linkzip.site/board_SnzU08/2906";
		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($target);
			}
		}

		if ( strlen($get_html_contents) > 0 ) {
			foreach($get_html_contents->find('div.document_2906_452') as $e){
				$f = str_get_html($e->innertext);
				foreach($f->find('u') as $g){
					$newurl = $g->innertext;
					break;
				}
			}
		}
		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'FUNBE';");
			echo "FUNBE => ".$newurl."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'FUNBE';");
			echo "FUNBE FAIL!\n";
		}
	}

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = '19ALLNET'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 19올넷 (19ALLNET) / 19올넷웹툰 (19ALLNETW)
		//$target = "https://linktong1.com/bbs/board.php?bo_table=webtoon&wr_id=38";
		$newurl = "";
		$target = "https://linkzip.site/board_SnzU08/658";
		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($target);
			}
		}

		if ( strlen($get_html_contents) > 0 ) {
			foreach($get_html_contents->find('div.document_658_452') as $e){
				$f = str_get_html($e->innertext);
				foreach($f->find('u') as $g){
					$newurl = $g->innertext;
					break;
				}
			}
		}
		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = '19ALLNET';");
			echo "19ALLNET => ".$newurl."\n";
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = '19ALLNETW';");
			echo "19ALLNETW => ".$newurl."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '19ALLNET';");
			echo "19ALLNET FAIL!\n";
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '19ALLNETW';");
			echo "19ALLNETW FAIL!\n";
		}
	}

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'NEWTOKI'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// SEQUENCIAL : 뉴토끼(NEWTOKI), 프로툰(PROTOON), 스포위키(SPOWIKI), 마나팡(MANAPANG), 마나토끼(MANATOKI), 샤크툰(SHARKTOON)
		$siteID = array();
		$siteUrl = array();
		$sql = "SELECT SITE_ID, SITE_NAME, SITE_URL, SITE_TYPE, SERVER_PATH, USE_LEVEL, SEARCH_URL, SEARCH_PARAM, RECENT_URL, RECENT_PARAM, ENDED_URL, ENDED_PARAM, LIST_URL, LIST_PARAM, VIEW_URL, VIEW_PARAM FROM SITE_INFO WHERE USE_YN='Y'";
		$conf_result = $webtoonDB->query($sql);
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			$siteID[$conf["SITE_ID"]] = $conf["SITE_ID"];
			$siteUrl[$conf["SITE_ID"]] = $conf["SITE_URL"];
			if ( endsWith($siteUrl[$conf["SITE_ID"]], "/") == true ) $siteUrl[$conf["SITE_ID"]] = substr($siteUrl[$conf["SITE_ID"]], 0, strlen($siteUrl[$conf["SITE_ID"]])-1);
		}

		// 뉴토끼(NEWTOKI), 마나토끼(MANATOKI)
		$newurl = "";
		$urlstr = str_replace("https://newtoki","",$siteUrl["NEWTOKI"]);
		$urlstr = str_replace(".com","",$urlstr);
		$urlstr = str_replace("/","",$urlstr);
		$urlnum = (int)$urlstr;
		for($i=$urlnum;$i < $urlnum+$search_seq;$i++){
			$base_url = "https://newtoki".$i.".com";
			$base_url2 = "https://manatoki".$i.".net";
			$get_html_contents = file_get_html($base_url);
			if ( strlen($get_html_contents) > 0 ) {
				foreach($get_html_contents->find('meta') as $e){
					if($e->property == "og:url"){
						$newurl = $base_url;
						break;
					}
				}
				break;
			}
		}

		echo "NEWTOKI ::: newurl => ".$newurl."\n";
		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'NEWTOKI';");
			echo "NEWTOKI => ".$newurl."\n";
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$base_url2."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'MANATOKI';");
			echo "MANATOKI => ".$base_url2."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'NEWTOKI';");
			echo "NEWTOKI FAIL!\n";
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'MANATOKI';");
			echo "MANATOKI FAIL!\n";
		}
	}

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'PROTOON'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		//프로툰(PROTOON)
		$newurl = "";
		$urlstr = str_replace("https://protoon","",$siteUrl["PROTOON"]);
		$urlstr = str_replace(".com","",$urlstr);
		$urlstr = str_replace("/","",$urlstr);
		$urlnum = (int)$urlstr;
		for($i=$urlnum;$i < $urlnum+$search_seq;$i++){
			$base_url = "https://protoon".$i.".com/";
			$get_html_contents = file_get_html($base_url);
			if ( strlen($get_html_contents) > 0 ) {
				foreach($get_html_contents->find('meta') as $e){
					if($e->property == "og:url"){
						$newurl = $base_url;
						break;
					}
				}
				break;
			}
		}

		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'PROTOON';");
			echo "PROTOON => ".$newurl."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'PROTOON';");
			echo "PROTOON FAIL!\n";
		}
	}

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'MANAPANG'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 마나팡(MANAPANG)
		$newurl = "";
		$urlstr = str_replace("https://manapang","",$siteUrl["MANAPANG"]);
		$urlstr = str_replace(".com","",$urlstr);
		$urlstr = str_replace("/","",$urlstr);
		$urlnum = (int)$urlstr;
		for($i=$urlnum;$i < $urlnum+$search_seq;$i++){
			$base_url = "https://manapang".$i.".com/";
			$get_html_contents = file_get_html($base_url);
			if ( strlen($get_html_contents) > 0 ) {
				foreach($get_html_contents->find('meta') as $e){
					if($e->property == "og:url"){
						$newurl = $base_url;
						break;
					}
				}
				break;
			}
		}

		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'MANAPANG';");
			echo "MANAPANG => ".$newurl."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'MANAPANG';");
			echo "MANAPANG FAIL!\n";
		}
	}

	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'SHARKTOON'; ";
	$webtoonView = $webtoonDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 샤크툰(SHARKTOON)
		$newurl = "";
		$urlstr = str_replace("https://www.sharktoon","",$siteUrl["SHARKTOON"]);
		$urlstr = str_replace(".com","",$urlstr);
		$urlstr = str_replace("/","",$urlstr);
		$urlnum = (int)$urlstr;
		for($i=$urlnum;$i < $urlnum+$search_seq;$i++){
			$base_urlt = "https://www.sharktoon".$i.".com/웹툰";
			$base_url = "https://www.sharktoon".$i.".com";
			$get_html_contents = file_get_html($base_urlt);
			if ( strlen($get_html_contents) > 0 ) {
				foreach($get_html_contents->find('meta') as $e){
					if($e->property == "og:url"){
						$newurl = $base_url;
						break;
					}
				}
				break;
			}
		}

		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'SHARKTOON';");
			echo "SHARKTOON => ".$newurl."\n";
		} else {
			$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'SHARKTOON';");
			echo "SHARKTOON FAIL!\n";
		}
	}
	echo "TOON URL Update END : ".date("Y.m.d H:i:s", time())."\n";

?>
