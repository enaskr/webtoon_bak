<?php 
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT+9'); 
	header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0'); 
	header('Cache-Control: post-check=0, pre-check=0', false); 
	header('Content-Type: text/html; charset=UTF-8');
	header('Pragma: no-cache'); 
	header('Connection: close'); 
	$keywordstr = ''; 
	if (isset($_GET['keyword']) ) { 
		$keywordstr = $_GET['keyword'];  
	} 
	$end = ''; 
	if (isset($_GET['end']) ) { 
		$end = $_GET['end'];  
	} 
	$cookieMBRID = ''; 
	if (isset($_COOKIE['MBRID']) ) { 
		$cookieMBRID = $_COOKIE['MBRID'];  
	} 
	 
	$server_path = str_replace(basename(__FILE__), '', str_replace(basename(__FILE__), '', realpath(__FILE__))); 
	$http_path = str_replace(basename($_SERVER['PHP_SELF']),'',$_SERVER['PHP_SELF']); 
	$homepath = '/share/webtoon/'; 
	$homeurl = '/'; 
	 
	include($homepath.'lib/simple_html_dom.php'); 
	 
	if ( lastIndexOf($http_path, '/') > 0 ) $newpath = substr($http_path, 0, strlen($http_path)-1); 
	else $newpath = $http_path; 
	$newpos = explode('/', $newpath); 
	$pathcnt = sizeof($newpos); 
	$lastpath = $newpos[$pathcnt-1]; 
	$lastpath2 = $newpos[$pathcnt-2]; 
	$filepath = basename($_SERVER['PHP_SELF']); 
	 
	$req_uri = urldecode($_SERVER['REQUEST_URI']); 
	$php_name = basename($_SERVER['PHP_SELF']); 
	$req_query = urldecode(getenv('QUERY_STRING')); 
	$this_url = $php_name.'?'.$req_query; 
	$loopcnt = 0; 
	 
	$thisTime = date('Y.m.d H:i:s', time());  
	$thisDate = date('Ymd', time());  
	 
	include($homepath.'lib/dbconn.php'); 
	$login_view = $config['login_view']; 
?>
