<?php
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT+9");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Connection: close");

	$SERVER_PATH = $_POST['server_path'];
	$HTTP_PATH = $_POST['http_path'];

	$file_handle = fopen("pathinfo.php", "w");

	$filecont = "<?php \n";
	$filecont = $filecont."\t$"."server_path = str_replace("."basename(__FILE__), '', str_replace("."basename(__FILE__), '', "."realpath(__FILE__))); \n";
	$filecont = $filecont."\t$"."http_path = str_replace("."basename($"."_SERVER['PHP_SELF']),'',$"."_SERVER['PHP_SELF']); \n";
	$filecont = $filecont."\t$"."homepath = '".$SERVER_PATH."'; \n";
	$filecont = $filecont."\t$"."homeurl = '".$HTTP_PATH."'; \n?>\n";

	fwrite($file_handle, $filecont);
	fclose($file_handle);

	$dbcopy = "N";
	if(!file_exists("webtoon.db")) {
		if(copy("webtoon_init.db", "webtoon.db")) {
			$dbcopy = "Y";
		}
	}
	$fileSize = filesize("./webtoon.db");
	if ( $fileSize < 1000 ) {
		unlink("./webtoon.db");
		$dbcopy = "N";
		if(copy("webtoon_init.db", "webtoon.db")) {
			$dbcopy = "Y";
		}
		$fileSize = filesize("./webtoon.db");
		if ( $fileSize < 1000 ) {  $dbcopy = "F"; }
	}

	if(file_exists("../install/install_save.php")) { unlink("../install/install_save.php"); }
	if(file_exists("../install/install_delete.php")) { unlink("../install/install_delete.php"); }
	if(file_exists("../install/install_dbreset.php")) { unlink("../install/install_dbreset.php"); }
	copy("install_save.php", "../install/install_save.php");
	copy("install_delete.php", "../install/install_delete.php");
	copy("install_dbreset.php", "../install/install_dbreset.php");
	if(file_exists("../install/install_save.php")) { unlink("install_save.php"); }
	if(file_exists("../install/install_delete.php")) { unlink("install_delete.php"); }
	if(file_exists("../install/install_dbreset.php")) { unlink("install_dbreset.php"); }

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
					기본 경로 설정이 완료되었습니다<br><br>
					웹 경로 : <?php echo $HTTP_PATH; ?><br>
					서버 경로 : <?php echo $SERVER_PATH; ?><br>
					<?php if ($dbcopy=="Y") { echo "기본 Database 설정이 완료되었습니다.<br><br>"; } ?>
					<?php if ($dbcopy=="F") { echo "기본 Database 설정이 실패하였습니다.<br>설치폴더/lib/webtoon_init.db 파일을<br>설치폴더/lib/webtoon.db로 복사해주세요.<br><br>"; } ?>
					서버의 보안을 위하여 <br>
					기본설정파일을 삭제하였습니다.<br><br>
					재설정이 필요한 경우 <br>
					install/install_*.php 파일을 lib 폴더로<br>
					옮긴 후 install.php를 실행해주세요.
				</div>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr><td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'><input type="button" value='홈으로 가기' style='border:none;line-height:50px;width:100%;background-color:#e3e3e3;' onClick='location.href="<?php echo $HTTP_PATH; ?>";'></td></tr></table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>