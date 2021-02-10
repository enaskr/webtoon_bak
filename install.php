<?php
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT+9");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Connection: close");

	$server_path = str_replace(basename(__FILE__), "", str_replace(basename(__FILE__), "", realpath(__FILE__)));
	$http_path = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);

	$fileSize = filesize("./lib/config.php");
	if ( $fileSize < 40 ) {
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
<link rel='stylesheet' href='./lib/css/ui.css' type='text/css'>
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
				<div class='group' style='padding:0px;'><form method="post" action="./lib/install_save.php">
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
						<tr>
							<td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'>설정명</td><td align="center" style='font-size:20px;color:#000000;width:75%;background-color:#e3e3e3;'>설정값</td>
						</tr>
						<tr>
							<td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#fff2dd;line-height:50px;'>웹 경로</td><td align="center" style='font-size:20px;color:#000000;width:75%;background-color:#fff2dd;'><input type="text" name="http_path" style='border:none;line-height:50px;width:100%;background-color:#fff2dd;' value="<?php echo $http_path; ?>"></td>
						</tr>
						<tr>
							<td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#fff2dd;line-height:50px;'>서버 경로</td><td align="center" style='font-size:20px;color:#000000;width:75%;background-color:#fff2dd;'><input type="text" name="server_path" style='border:none;line-height:50px;width:100%;background-color:#fff2dd;' value="<?php echo $server_path; ?>"></td>
						</tr>
						<tr>
							<td align="center" colspan='2' style='font-size:20px;color:#000000;width:25%;background-color:#fff2dd;line-height:50px;'><input type='submit' name='savebtn' value='저장하기' style='border:none;line-height:50px;width:100%;background-color:#e0eefe;'></td>
						</tr>
					</table></form>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
<?php
	} else {
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
<link rel='stylesheet' href='./lib/css/ui.css' type='text/css'>
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
					기본 경로를 변경하시려면<br>
					<font color="red">[설치경로]/lib/config.php</font>를 삭제한 후<br>
					다시 시도해주세요.
				</div>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr><td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'><input type="button" value='홈으로 가기' style='border:none;line-height:50px;width:100%;background-color:#e3e3e3;' onClick='location.href="./";'></td></tr></table>
				</div>
<?php
	if(file_exists("./lib/config.php")) {
?>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr><td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'><input type="button" value='config.php 삭제하기' style='border:none;line-height:50px;width:100%;background-color:#e3e3e3;' onClick='location.href="./lib/install_delete.php";'></td></tr></table>
				</div>
<?php
	}
?>
<?php
	if(file_exists("./lib/webtoon.db")) {
?>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr><td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'><input type="button" value='Database 초기화하기' style='border:none;line-height:50px;width:100%;background-color:#e3e3e3;' onClick='location.href="./lib/install_dbreset.php";'></td></tr></table>
				</div>
<?php
	}
?>
<?php
	if(!file_exists("./lib/webtoon.db")) {
?>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr><td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'><input type="button" value='Database 생성하기' style='border:none;line-height:50px;width:100%;background-color:#e3e3e3;' onClick='location.href="./lib/install_dbmake.php";'></td></tr></table>
				</div>
<?php
	}
?>
			</dd>
		</dl>
	</div>
</body>
</html>
<?php
	}
?>