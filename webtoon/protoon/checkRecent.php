<?php
	if ( $server_path == null || strlen($server_path)==0 ) {
		$server_path = str_replace(basename(__FILE__), "", str_replace(basename(__FILE__), "", realpath(__FILE__)));
	}
	if ( $http_path == null || strlen($http_path)==0 ) {
		$http_path = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);
	}

	include('../../lib/config.php');

	$epiurl = $viewUrl."?".str_replace("{toondtlid}",$_GET["ws_id"],$viewParam);
	$epiurl = str_replace("{toonid}",$_GET["wr_id"],$epiurl);
	$epiurl = str_replace("{type}",$_GET["type"],$epiurl);

	$url = $siteUrl.$epiurl; //爽社実特
	if ( $config["cf_redirect"] != null && $config["cf_redirect"] == "Y" ) {
		$url = $config_add1["cf_redirect"]."?try_count=".$config["try_count"]."&cf_cookie=".urlencode($config["cf_cookie"])."&cf_useragent=".urlencode($config["cf_useragent"])."&target_url=".urlencode($url);
	}
	$url = $base_url.$url; //爽社実特

	$get_html_contents = file_get_html($url);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = file_get_html($ch);
		}
	}

	foreach($get_html_contents->find('a.nbtn') as $e){
		if($e->href != null){
			$next_url = $e->href;
			$urlparse = explode('?' , $next_url);
			$uriparse = explode('&' , $urlparse[1]);
			$next_epi = explode('=' , $uriparse[0]);
		}
	}

	$link = "view.php?title=".urlencode($_GET["title"])."&wr_id=".urlencode($_GET["wr_id"])."&ws_id=".urlencode($next_epi[1]);
	$link2 = "view.php?title=".urlencode($_GET["title"])."&wr_id=".urlencode($_GET["wr_id"])."&ws_id=".urlencode($_GET["ws_id"]);
	if ( $next_epi != null && strlen($next_epi[1]) > 0 ) {
		echo "Y|".$link;
	} else {
		echo "N|".$link2;
	}
?>
