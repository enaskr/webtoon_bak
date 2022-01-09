<?php @ob_start();?>
<?php
	$strtitle = "";
	$uri= $_SERVER['REQUEST_URI']; //uri를 구합니다.

	if ( $login_view == true && strpos($uri, "/user/") != true ) {
		if ( $isLogin != true ) {
			Header("Location:".$homeurl); 
		}
	}
	$keywordstr = "";
	if (isset($_GET['keyword']) ) {
		$keywordstr = $_GET['keyword']; 
	}

	if ( $canView != true &&  strpos($uri, "/user/") != true && strpos($uri, "/lib/") != true ) {
?><script type='text/javascript'>
	alert('권한이 없습니다.\n\n관리자에게 권한부여 요청해주세요.');
	location.replace('<?php $homeurl ?>');
</script>
<?php
	}
?><!DOCTYPE html>
<html lang='ko'>
<head>
<meta charset='utf-8'>
<meta name='viewport' id='viewport' content='width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=2.0,minimum-scale=1.0'>
<meta name='mobile-web-app-capable' content='yes'>
<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />
<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Nanum+Gothic&amp;subset=korean">
<title>:::WEBTOON::웹툰:::</title>
<link rel='stylesheet' href='<?php echo $homeurl; ?>lib/css/ui.css' type='text/css'>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> <script src='http://html5shim.googlecode.com/svn/trunk/html5.js'></script> <![endif]-->
<script  src="//code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
	var lastpath = '<?php echo $lastpath; ?>';
	var lastpath2 = '<?php echo $lastpath2; ?>';

	$(function() {
		$(window).scroll(function() {
			if ($(this).scrollTop() > 500) {
				$('#MOVE_TOP_BTN').fadeIn();
			} else {
				$('#MOVE_TOP_BTN').fadeOut();
			}
		});

		$("#MOVE_TOP_BTN").click(function() {
			$('html, body').animate({
				scrollTop : 0
			}, 400);
			return false;
		});
	});

	//Javascript
	window.onpageshow =  function(event) { // iOS BFCahe 대응
		if (event.persisted) {
			window.location.reload();
		}
	}
	function chktoggle() {
		if ( lastpath == "lib" || lastpath == "user" ) {
			$("#settingslist").show();
		} else {
			$("#settingslist").hide();
		}
		if ( lastpath != "lib" && lastpath != "user" && lastpath != "ranking" && lastpath2 == "webtoon" ) {
			$("#webtoonlist").show();
		} else {
			$("#webtoonlist").hide();
		}
		if ( lastpath != "lib" && lastpath != "user" && lastpath != "ranking" && lastpath2 == "manga" ) {
			$("#mangalist").show();
		} else {
			$("#mangalist").hide();
		}
		if ( lastpath == "ranking" ) {
			$("#rankinglist").show();
		} else {
			$("#rankinglist").hide();
		}
	};
	function toggle(nm) {
		if ( nm == "settings" ) {
			$("#settingslist").show();
		} else {
			$("#settingslist").hide();
		}
		if ( nm == "webtoon" ) {
			$("#webtoonlist").show();
		} else {
			$("#webtoonlist").hide();
		}
		if ( nm == "manga" ) {
			$("#mangalist").show();
		} else {
			$("#mangalist").hide();
		}
		if ( nm == "ranking" ) {
			$("#rankinglist").show();
		} else {
			$("#rankinglist").hide();
		}
	};

	$(document).ready( function() {
		chktoggle();

	});
</script>
</head>
<body style="padding:5px 5px 5px 5px;">
<div style="margin:5px 0px 0px 0px;">
<font size="3" style='line-height:35px;'><span style="background:#8f7ee5;padding:5px 5px 5px 5px;box-sizing:border-box;border-radius:10px;margin:0px 5px 0px 0px"><a href="<?php echo $homeurl; ?>" style="font-weight:900;color:#ffff00;">홈</a></span>
<span id="settings"><span style="background:#8f7ee5;padding:5px 5px 5px 5px;box-sizing:border-box;border-radius:10px;margin:0px 0px 0px 0px"><a href="javascript:void(0);" onClick="toggle('settings');" style="font-weight:900;color:#ffff00;">설정</a></span><span id="settingslist" style="display:none;padding:5px 5px 5px 5px;margin: 0px 0px 0px 0px;">
	<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>user/' <?php if ( $filepath == "index.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>사용자목록</a></span>
	<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>user/myview.php' <?php if ( $filepath == "myview.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>나의목록</a></span>
	<!--span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>user/userform.php' <?php if ( $filepath == "userform.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>사용자등록</a></span-->
	<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>lib/settings.php' <?php if ( $filepath == "settings.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>서버설정</a></span>
	<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>lib/siteinfo.php' <?php if ( $filepath == "siteinfo.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>사이트</a></span>
</span></span>
<span id="webtoon"><span style="background:#8f7ee5;padding:5px 5px 5px 5px;box-sizing:border-box;border-radius:10px;margin:0px 0px 0px 0px"><a href="javascript:void(0);" onClick="toggle('webtoon');" style="font-weight:900;color:#ffff00;word-break:keep-all;">웹툰</a></span><span id="webtoonlist" style="display:none;padding:5px 5px 5px 5px;margin:5px 0px 5px 0px;">
<?php
		$getSiteSQL = "SELECT SITE_ID, SITE_NAME, SERVER_PATH, SITE_TYPE  FROM SITE_INFO WHERE CAST(USE_LEVEL AS INT) <= ".$USER_LEVEL." AND USE_YN='Y' AND SITE_TYPE='webtoon' AND UPDATE_YN='Y' ORDER BY SITE_TYPE DESC, CAST(ORDER_NUM AS INT), SITE_NAME ASC;";
		$site_result = $systemDB->query($getSiteSQL);
		while($siteDB = $site_result->fetchArray(SQLITE3_ASSOC)){
			$getSiteId = $siteDB['SITE_ID'];
			$getSiteName = $siteDB['SITE_NAME'];
			$getServerPath = $siteDB['SERVER_PATH'];
			$getSiteType = $siteDB['SITE_TYPE'];
?>
<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>webtoon/<?php echo $getServerPath; ?>/myview.php' <?php if ( $lastpath == $getServerPath ) { ?>style='font-weight:bold;color:blue;'<?php } ?>><?php echo $getSiteName; ?></a></span>
<?php
		}
?></span></span>
<span id="manga"><span style="background:#8f7ee5;padding:5px 5px 5px 5px;box-sizing:border-box;border-radius:10px;margin:0px 0px 0px 0px"><a href='javascript:void(0);' onClick='toggle("manga");' style="font-weight:900;color:#ffff00;word-break:keep-all;">만화책</a></span><span id="mangalist" style="display:none;padding:5px 5px 5px 5px;margin:5px 0px 5px 0px;">
<?php
	$getSiteSQL = "SELECT SITE_ID, SITE_NAME, SERVER_PATH, SITE_TYPE  FROM SITE_INFO WHERE CAST(USE_LEVEL AS INT) <= ".$USER_LEVEL." AND USE_YN='Y' AND SITE_TYPE='manga' AND UPDATE_YN='Y' ORDER BY SITE_TYPE DESC, CAST(ORDER_NUM AS INT), SITE_NAME ASC;";
	$site_result = $systemDB->query($getSiteSQL);
	while($siteDB = $site_result->fetchArray(SQLITE3_ASSOC)){
		$getSiteId = $siteDB['SITE_ID'];
		$getSiteName = $siteDB['SITE_NAME'];
		$getServerPath = $siteDB['SERVER_PATH'];
		$getSiteType = $siteDB['SITE_TYPE'];
?>
<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>manga/<?php echo $getServerPath; ?>/myview.php' <?php if ( $lastpath == $getServerPath ) { ?>style='font-weight:bold;color:blue;'<?php } ?>><?php echo $getSiteName; ?></a></span>
<?php
		}
?></span></span>
<span id="ranking"><span style="background:#8f7ee5;padding:5px 5px 5px 5px;box-sizing:border-box;border-radius:10px;margin:0px 0px 0px 0px"><a href='javascript:void(0);' onClick='toggle("ranking");' style="font-weight:900;color:#ffff00;word-break:keep-all;">웹툰순위</a></span><span id="rankinglist" style="display:none;padding:5px 5px 5px 5px;margin:5px 0px 5px 0px;">
	<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>ranking/naver.php' <?php if ( $filepath == "naver.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>NAVER 일간</a></span>
	<span style="background:#fff;border:solid 1px blue;padding:3px 2px 2px 3px;box-sizing:border-box;border-radius:5px;margin:0px 0px 0px 0px"><a href='<?php echo $homeurl; ?>ranking/naverweek.php' <?php if ( $filepath == "naverweek.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>NAVER 주간</a></span>
</span></span>
</font></div>
<?php
	if ( $lastpath != "user" && $lastpath != "lib" && $lastpath != "ranking" ) {
?>
<form action="index.php" method="get">
<input type=text class="form-control mb-1 pb-2" name="keyword" width="150px" value="<?php echo $keywordstr; ?>" onClick="this.value='';" onBlur="if ( this.value=='' ) this.value='<?php echo $keywordstr; ?>'; else this.value=this.value;">
<button class="btn m-0 p-1 btn-success btn-block btn-sm" type=submit>검색하기</button>
</form>
<?php if ($isLogin) { ?>
<table style="border-color:#ffffff;" border=0 width="100%" cellspacing=0 cellpadding=0>
	<tr style='background-color:#f8f8f8'>
		<td style='width:34%;height:30px;font-size:16px;color:#8000ff;' align=center valign=middle><a href="./myview.php" <?php if ( ($lastpath2 == "webtoon" || $lastpath2 == "manga") && $filepath=="myview.php" ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>내가 본 목록</a></td>
		<td style='width:33%;font-size:16px;color:#8000ff;' align=center valign=middle><a href="./index.php" <?php if ( ($lastpath2 == "webtoon" || $lastpath2 == "manga") && $filepath=="index.php" && $end!="END" && $keywordstr == null ) { ?>style='font-weight:bold;color:blue;'<?php } ?>>최신업데이트</a></td>
		<td style='width:33%;font-size:16px;color:#8000ff;' align=center valign=middle><a href="./index.php?end=END" <?php if ( ($lastpath2 == "webtoon" || $lastpath2 == "manga") && $filepath=="index.php" && $end=="END" && $keywordstr == null) { ?>style='font-weight:bold;color:blue;'<?php } ?>>완결작</a></td>
	</tr>
</table>
<?php } ?>
<?php } ?>
<a id="MOVE_TOP_BTN" href="#"><img src="<?php echo $homeurl; ?>lib/img/top.png" width="40px" height="40px"></a>
