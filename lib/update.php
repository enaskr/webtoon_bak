<?php
	ini_set('memory_limit','-1');
	header('Content-Type: text/html; charset=UTF-8');
	$filepath = str_replace(basename(__FILE__), '', str_replace(basename(__FILE__), '', realpath(__FILE__))); 

	include($filepath.'simple_html_dom.php');
	$systemDB = new SQLite3($filepath.'system.db');
	
	$try_count = 3;
	$search_seq = 50;

	echo "TOON URL Update Start : ".date("Y.m.d H:i:s", time())."\n";

	$newurl = "";
	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'FUNBE'; ";
	$webtoonView = $systemDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 펀비 (FUNBE)
		$target = "https://linkzip02.link/webtoon/2906";
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
			$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'FUNBE';");
			echo "FUNBE => ".$newurl."\n";
		} else {
			$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'FUNBE';");
			echo "FUNBE FAIL!\n";
		}
	}

	$newurl = "";
	$toonsiteList = "SELECT SITE_ID, SITE_URL, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'TOONKOR'; ";
	$webtoonView = $systemDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
		$dbsiteUrl = $row["SITE_URL"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 툰코 (TOONKOR)
		$urlstr = $dbsiteUrl;
		$get_html_contents = file_get_html($urlstr);
		if ( strlen($get_html_contents) > 1000 ) {
			foreach($get_html_contents->find('meta') as $e){
				if($e->property == "og:url"){
					$newurl = $e->content;
					break;
				}
			}
		}

		$tempurl = explode('/' , $newurl);
		$newurl = $tempurl[0]."/".$tempurl[1]."/".$tempurl[2];

		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'TOONKOR';");
			echo "TOONKOR => ".$newurl."\n";
		} else {
			$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'TOONKOR';");
			echo "TOONKOR FAIL!\n";
		}
	}

	$newurl = "";
	$dbsiteUrl = "";
	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = '19ALLNET'; ";
	$webtoonView = $systemDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 19올넷 (19ALLNET) / 19올넷웹툰 (19ALLNETW)
		$target = "https://linkzip02.link/board_SnzU08/658";
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
			$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = '19ALLNET';");
			echo "19ALLNET => ".$newurl."\n";
			$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = '19ALLNETW';");
			echo "19ALLNETW => ".$newurl."\n";
		} else {
			$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '19ALLNET';");
			echo "19ALLNET FAIL!\n";
			$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '19ALLNETW';");
			echo "19ALLNETW FAIL!\n";
		}
	}

	$newurl = "";
	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'NEWTOKI'; ";
	$webtoonView = $systemDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 뉴토끼 (NEWTOKI)
		$target = "https://linkzip02.link/board_SnzU08/656";
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
			foreach($get_html_contents->find('div.document_656_452') as $e){
				$f = str_get_html($e->innertext);
				foreach($f->find('u') as $g){
					$newurl = $g->innertext;
					break;
				}
			}
		}

		echo "NEWTOKI ::: newurl => ".$newurl."\n";
		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'NEWTOKI';");
			echo "NEWTOKI => ".$newurl."\n";

		} else {
			$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'NEWTOKI';");
			echo "NEWTOKI FAIL!\n";
		}
	}

	$newurl = "";
	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'MANATOKI'; ";
	$webtoonView = $systemDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 마나토끼 (MANATOKI)
		$target = "https://linkzip02.link/board_SnzU08/5649";
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
			foreach($get_html_contents->find('div.document_5649_452') as $e){
				$f = str_get_html($e->innertext);
				foreach($f->find('u') as $g){
					$newurl = $g->innertext;
					break;
				}
			}
		}

		echo "MANATOKI ::: newurl => ".$newurl."\n";
		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$base_url2."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'MANATOKI';");
			echo "MANATOKI => ".$base_url2."\n";
		} else {
			$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'MANATOKI';");
			echo "MANATOKI FAIL!\n";
		}
	}


	$newurl = "";
	$toonsiteList = "SELECT SITE_ID, IFNULL(UPDATE_EXECUTE,'Y') AS UPDATE_EXECUTE FROM SITE_INFO WHERE SITE_ID = 'DOZI'; ";
	$webtoonView = $systemDB->query($toonsiteList);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){
		$dbsiteid = $row["SITE_ID"];
		$dbupdateexecute = $row["UPDATE_EXECUTE"];
	}
	if ( $dbupdateexecute != null && $dbupdateexecute == "Y" ) {
		// 도지코믹스 (DOZI)
		$target = "https://linkzip02.link/webtoon/17498";
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
			foreach($get_html_contents->find('div.document_17498_452') as $e){
				$f = str_get_html($e->innertext);
				foreach($f->find('u') as $g){
					$newurl = $g->innertext;
					break;
				}
			}
		}

		echo "DOZI ::: newurl => ".$newurl."\n";
		if ( strlen($newurl) > 10 ) {
			if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
			$systemDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$base_url2."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'DOZI';");
			echo "DOZI => ".$base_url2."\n";
		} else {
			$systemDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'DOZI';");
			echo "DOZI FAIL!\n";
		}
	}

	echo "TOON URL Update END : ".date("Y.m.d H:i:s", time())."\n";

?>
