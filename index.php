<?php
	if(file_exists("./lib/config.php") && file_exists("./lib/webtoon.db") && file_exists("./lib/system.db")) {
	include('./lib/config.php');
?><!DOCTYPE html>
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
	<div id='container'>
		<div class='item'>
<?php
		if ( $cookieMBRID!=null && strlen($cookieMBRID) > 0 ) {
			//$cookieUserName = "jackie";
			$mbr_id = openssl_decrypt($cookieMBRID, 'AES-256-CBC', KEY_256, 0, KEY_128);
			$mbrpos = explode("|", $mbr_id);
			$mbr_no = $mbrpos[0];
			$login_date = $mbrpos[1];
			$mbr_pass = $mbrpos[2];
			if ( $mbr_no == null || strlen($mbr_no) == 0 || $mbr_pass == null || strlen($mbr_pass) == 0 ) exit("계정정보가 비정상입니다.");
?>
			<dl>
				<dt>WEBTOON</dt>
				<dd>
<?php
		$getSiteSQL = "SELECT SITE_ID, SITE_NAME, SERVER_PATH,  SITE_TYPE  FROM SITE_INFO WHERE CAST(USE_LEVEL AS INT) <= ".$USER_LEVEL." AND USE_YN='Y' AND UPDATE_YN='Y' AND MAIN_VIEW='Y' ORDER BY SITE_TYPE DESC, CAST(ORDER_NUM AS INT), SITE_NAME ASC;";
		$site_result = $systemDB->query($getSiteSQL);
		while($siteDB = $site_result->fetchArray(SQLITE3_ASSOC)){
			$getSiteId = $siteDB['SITE_ID'];
			$getSiteName = $siteDB['SITE_NAME'];
			$getServerPath = $siteDB['SERVER_PATH'];
			$getSiteType = $siteDB['SITE_TYPE'];
?>
					<a href="./<?php echo $getSiteType."/".$getServerPath; ?>/myview.php" target="_top" style="padding:20px 10px 20px 20px;">[<?php echo $getSiteType ?>]<?php echo $getSiteName; ?></a>
<?php
		}
?>
				</dd>
			</dl>
			<dl>
				<dd>
					<a href="#" style="padding:5px 10px 5px 20px;"></a>
					<a href="ranking/naver.php" style="padding:20px 10px 20px 20px;">NAVER 웹툰 순위</a>
					<a href="./url.php" style="padding:20px 10px 20px 20px;">웹툰사이트 링크</a>
					<a href="./user/index.php" style="padding:20px 10px 20px 20px;">설정</a>
					<a href="#" style="padding:5px 10px 5px 20px;"></a>
					<a href="./lib/logout.php" style="padding:20px 10px 20px 20px;">로그아웃</a>
				</dd>
			</dl>
<?php
		} else {
?>
			<dl>
				<dt>WEBTOON</dt>
				<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
					<form name="userLogin" method="post" action="./lib/login.php">
						<tr style='background-color:#f8f8f8'>
							<td style='width:25%;font-size:16px;color:#8000ff;' align=center valign=middle>아이디</td>
							<td style='font-size:16px;color:#8000ff;' align=center valign=middle><input type="text" name="user" style='border:none; line-height:48px; width:100%;' tabindex=1></td>
							<td rowspan="2" style='width:25%;font-size:16px;color:#8000ff;' align=center valign=middle><input type="submit" name="submit" style='border:none; line-height:98px; width:100%;' value="로그인" tabindex=3></td>
						</tr>
						<tr style='background-color:#f8f8f8'>
							<td style='width:25%;font-size:16px;color:#8000ff;' align=center valign=middle>비밀번호</td>
							<td style='font-size:16px;color:#8000ff;' align=center valign=middle><input type="password" name="pass" style='border:none; line-height:48px; width:100%;' tabindex=2></td>
						</tr>
						<tr style='background-color:#f8f8f8'>
							<td colspan="3" style='width:100%;font-size:16px;color:#8000ff;' align=center valign=middle><input type="button" name="submit" style='border:none; line-height:48px; width:100%;' value="회원가입" onClick="location.href='./user/userform.php';"></td>
						</tr>
					</form>
				</dd>
			</dl>
<?php
		}
?>
		</div>
	</div>
</body>
</html>
<?php
	} else {
?>
<!DOCTYPE html>
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
			<dt style="color:yellow;background-color:red;">기본 경로설정 파일오류</dt>
			<dd>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px;'>
					기본 경로설정 파일이 없거나,<br>
					잘못되어있습니다.<br><br>
					아래에서 설정해주세요.<br>
				</div>
				<div class='group' style='padding:0px;font-size:20px;line-height:50px'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0><tr><td align="center" style='font-size:20px;color:#000000;width:25%;background-color:#e3e3e3;line-height:50px;'><input type="button" value='기본환경 설정하기' style='border:none;line-height:50px;width:100%;background-color:#e3e3e3;' onClick='location.href="./install.php";'></td></tr></table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
<?php
	}
?>