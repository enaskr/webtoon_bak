<?php
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT+9");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Connection: close");

	$dbcopy = "N";
	if(file_exists("config.php")) {
		unlink("config.php");
		$dbcopy = "Y";
	}

?><!DOCTYPE html>
<html lang='ko'>
<head>
<meta charset='utf-8'>
<meta name='viewport' id='viewport' content='width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0'>
<meta name='mobile-web-app-capable' content='yes'>
<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />
<?php echo $favicon; ?>
<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Nanum+Gothic&amp;subset=korean">
<title>:::WEBTOON::웹툰:::</title>
<link rel='stylesheet' href='./css/ui.css' type='text/css'>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script> <![endif]-->
<script  src="//code.jquery.com/jquery-latest.min.js"></script>
</head>
<body style="padding:5px 5px 5px 5px;">
<div id='container'>
	<div class='item'>
		<dl>
			<dt>기본 경로 설정</dt>
			<dd>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<?php if ($dbcopy=="Y") { echo "config.php 파일을 삭제했습니다."; } ?>
				</div>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr><td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'><input type="button" value='기본경로 설정하기' style='border:none;line-height:50px;width:100%;background-color:#e3e3e3;' onClick='location.href="../install.php";'></td></tr></table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>