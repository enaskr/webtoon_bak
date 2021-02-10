<?php
	$cookieMBRID = ''; 
	if (isset($_COOKIE['MBRID']) ) { 
		setcookie("MBRID", $_COOKIE["MBRID"], time()-60, "/");
		unset($_COOKIE["MBRID"]);
	} 
?>
<!DOCTYPE html>
<html lang='ko'>
<head>
<meta charset='utf-8'>
<meta name='viewport' id='viewport' content='width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0'>
<meta name='mobile-web-app-capable' content='yes'>
<title>:::WEBTOON::웹툰:::</title>
<link rel='stylesheet' href='lib/css/ui.css' type='text/css'>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script> <![endif]-->
<script src='lib/js/jquery-2.1.4.min.js'></script>
</head>
<body>
<script type="text/javascript">
	alert("로그아웃 되었습니다.");
	location.href="../index.php";
</script>
</body>
</html>
