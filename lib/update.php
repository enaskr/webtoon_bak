<?php
	header('Content-Type: text/html; charset=UTF-8');
	include('idna_convert.class.php');
	include('simple_html_dom.php');
	$webtoonDB = new SQLite3('./webtoon.db');
	
	$try_count = 3;
	$search_seq = 50;

	echo "TOON URL Update Start : ".date("Y.m.d H:i:s", time())."\n";

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

	// 툰코 (TOONKOR)
	//$target = "https://linktong1.com/bbs/board.php?bo_table=webtoon&wr_id=10";
	$newurl = "";
	$target = "https://linkzip.site/board_SnzU08/634";
	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}
/*
	if ( strlen($get_html_contents) > 0 ) {
		$strpos = explode('<table border="1" style="width:100%;">',$get_html_contents);
		$strpos2 = explode('</table>',$strpos[1]);
		$newstr = $strpos2[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	}
*/
	if ( strlen($get_html_contents) > 0 ) {
		foreach($get_html_contents->find('div.document_634_452') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('u') as $g){
				$newurl = $g->innertext;
				break;
			}
		}
	}

	if ( strlen($newurl) > 10 ) {
		if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'TOONKOR';");
		echo "TOONKOR => ".$newurl."\n";
	} else {
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'TOONKOR';");
		echo "TOONKOR FAIL!\n";
	}

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
/*
	if ( strlen($get_html_contents) > 0 ) {
		$strpos = explode('<table border="1" style="width:100%;">',$get_html_contents);
		$strpos2 = explode('</table>',$strpos[1]);
		$newstr = $strpos2[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	}
*/
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
/*
	if ( strlen($get_html_contents) > 0 ) {
		$strpos = explode('<table border="1" style="width:100%;">',$get_html_contents);
		$strpos2 = explode('</table>',$strpos[1]);
		$newstr = $strpos2[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	}
*/
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
/*
	// 샤크툰 (SHARKTOON)
	$newurl = "";
	$target = "https://linktong1.com/bbs/board.php?bo_table=webtoon&wr_id=50";
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
		$strpos = explode('<table border="1" style="width:100%;">',$get_html_contents);
		$strpos2 = explode('</table>',$strpos[1]);
		$newstr = $strpos2[0];
		$newtokistr = str_get_html($newstr);
		foreach($newtokistr->find('a') as $e){
			$newurl = $e->href;
			break;
		}
	}

	if ( strlen($newurl) > 10 ) {
		if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."' WHERE SITE_ID = 'SHARKTOON';");
		echo "SHARKTOON => ".$newurl."\n";
	}
*/
	// 일일툰 (11TOON)
	$newurl = "";
	$url = "http://11toon1.com/";
	$ch = curl_init(); //curl 로딩
	curl_setopt($ch, CURLOPT_URL,$url); //curl에 url 셋팅
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
	curl_setopt($ch, CURLOPT_TIMEOUT,3000);
	$result = curl_exec($ch); // curl 실행 및 결과값 저장
	curl_close ($ch); // curl 종료
	$get_html_contents = str_get_html($result);

	if ( strlen($get_html_contents) > 0 ) {
		$idx = 0;
		foreach($get_html_contents->find('li') as $e) {
			if ( $idx == 2 ) {
				$f = str_get_html($e->innertext);
				foreach($f->find('a') as $g) {
					$newurl = $g->href;
					break;
				}
			}
			$idx++;
		}
	}

	if ( strlen($newurl) > 10 ) {
		if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
		$IDN = new idna_convert();
		$newurl = $IDN->encode($newurl);
		$newsql = "UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = '11TOON'; ";
		$webtoonDB->exec($newsql);
		echo "11TOON => ".$newurl."\n";
	} else {
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = '11TOON';");
		echo "11TOON FAIL!\n";
	}

	// SEQUENCIAL : 마니코믹스웹툰(MANYW), 뉴토끼(NEWTOKI), 프로툰(PROTOON), 스포위키(SPOWIKI), 마나팡(MANAPANG), 마나토끼(MANATOKI), 마니코믹스(MANY), 샤크툰(SHARKTOON)
	$siteID = array();
	$siteUrl = array();
	$sql = "SELECT SITE_ID, SITE_NAME, SITE_URL, SITE_TYPE, SERVER_PATH, USE_LEVEL, SEARCH_URL, SEARCH_PARAM, RECENT_URL, RECENT_PARAM, ENDED_URL, ENDED_PARAM, LIST_URL, LIST_PARAM, VIEW_URL, VIEW_PARAM, CF_REDIRECT, CF_COOKIE, CF_USERAGENT FROM SITE_INFO WHERE USE_YN='Y'";
	$conf_result = $webtoonDB->query($sql);
	while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
		$siteID[$conf["SITE_ID"]] = $conf["SITE_ID"];
		$siteUrl[$conf["SITE_ID"]] = $conf["SITE_URL"];
		if ( endsWith($siteUrl[$conf["SITE_ID"]], "/") == true ) $siteUrl[$conf["SITE_ID"]] = substr($siteUrl[$conf["SITE_ID"]], 0, strlen($siteUrl[$conf["SITE_ID"]])-1);
	}

	// 마니코믹스웹툰(MANYW) / 마니코믹스(MANY)
	$newurl = "";
	$urlstr = str_replace("https://many","",$siteUrl["MANY"]);
	$urlstr = str_replace(".com","",$urlstr);
	$urlstr = str_replace("/","",$urlstr);
	$urlnum = (int)$urlstr;
	for($i=$urlnum;$i < $urlnum+$search_seq;$i++){
		$base_url = "https://many".$i.".com/";
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
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'MANY';");
		echo "MANY => ".$newurl."\n";
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'MANYW';");
		echo "MANYW => ".$newurl."\n";
	} else {
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'MANY';");
		echo "MANY FAIL!\n";
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'MANYW';");
		echo "MANYW FAIL!\n";
	}

	// 뉴토끼(NEWTOKI), 마나토끼(MANATOKI)
	$newurl = "";
	$urlstr = str_replace("https://newtoki","",$siteUrl["NEWTOKI"]);
	$urlstr = str_replace(".com","",$urlstr);
	$urlstr = str_replace("/","",$urlstr);
	$urlnum = (int)$urlstr;
	echo "NEWTOKI ::: urlnum = ".$urlnum."\n";
	for($i=$urlnum;$i < $urlnum+$search_seq;$i++){
		$base_url = "https://newtoki".$i.".com";
		$base_url2 = "https://manatoki".$i.".net";
		$get_html_contents = file_get_html($base_url);
		if ( strlen($get_html_contents) > 0 ) {
	echo "NEWTOKI ::: i = ".$i."\n";
			foreach($get_html_contents->find('meta') as $e){
	echo "NEWTOKI ::: meta ok"."\n";
				if($e->property == "og:url"){
	echo "NEWTOKI ::: og:url ok"."\n";
					$newurl = $base_url;
	echo "NEWTOKI ::: newurl = ".$newurl."\n";
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


	// 스포위키(SPOWIKI)
	$newurl = "";
	$urlstr = str_replace("https://spowiki","",$siteUrl["SPOWIKI"]);
	$urlstr = str_replace(".com","",$urlstr);
	$urlstr = str_replace("/","",$urlstr);
	$urlnum = (int)$urlstr;
	for($i=$urlnum;$i < $urlnum+$search_seq;$i++){
		$base_url = "https://spowiki".$i.".com/";
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

	if ( strlen($newurl) > 19 ) {
		if ( endsWith($newurl,"/") == true ) $newurl = substr($newurl, 0, strlen($newurl)-1);
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET SITE_URL = '".$newurl."', UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='Y' WHERE SITE_ID = 'SPOWIKI';");
		echo "SPOWIKI => ".$newurl."\n";
	} else {
		$webtoonDB->exec("UPDATE 'SITE_INFO' SET UPTDTIME = '".date("Y.m.d H:i:s", time())."', UPDATE_YN='N' WHERE SITE_ID = 'SPOWIKI';");
		echo "SPOWIKI FAIL!\n";
	}

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

	echo "TOON URL Update END : ".date("Y.m.d H:i:s", time())."\n";

?>
