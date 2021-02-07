<?php 
	$server_path = str_replace(basename(__FILE__), '', str_replace(basename(__FILE__), '', realpath(__FILE__))); 
	$http_path = str_replace(basename($_SERVER['PHP_SELF']),'',$_SERVER['PHP_SELF']); 
	$homepath = ''; 
	$homeurl = ''; 
?>
