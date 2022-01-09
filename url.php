<?php
	header('Content-Type: text/html; charset=UTF-8');
	$webtoonDB = new SQLite3('./lib/system.db');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" id="viewport" content="width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
<meta name="mobile-web-app-capable" content="yes">
<title>웹툰 사이트</title>
<link rel="stylesheet" href="./lib/css/ui2.css" type="text/css">
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]> <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> <![endif]-->
<script src="./lib/js/jquery-2.1.4.min.js"></script>
<script src="./lib/js/jquery.grid-a-licious.min.js"></script>
<script>
if (screen.width > 812) {
    $(document).ready(function(){
		$("#container").gridalicious({
			gutter: 20,
			width: 100% 
		});
	});
}
else {
   $(document).ready(function(){  
		$('nav a').click(function () { 
			$('body,html').animate({ 
				  scrollTop: 0 
			 }, 300); 
			 return false; 
		}); 
		$(".item > dl > dt").click(function() {
			$(this).parent().toggleClass("active").siblings("dt").removeClass("active");
		});
	});
}
</script>
</head>
<body>
<div id="container">
	<!-- item : S -->
	<div class="item">
		<dl>
			<dt>WEBTOON</dt>
			<dd>
<?php
		$uptdtime = "";
		$sql = "SELECT SITE_ID, SITE_NAME, SITE_URL, SITE_TYPE, UPTDTIME FROM SITE_INFO WHERE SITE_TYPE='webtoon' AND USE_LEVEL < 99999 AND USE_YN='Y' ORDER BY SITE_TYPE DESC, CAST(ORDER_NUM AS INT), SITE_NAME ASC;";
		$conf_result = $webtoonDB->query($sql);
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			echo "<a href='".$conf["SITE_URL"]."' target='_top'>".$conf["SITE_NAME"]."<span style='font-size:20px;'><br>[".$conf["SITE_URL"]."]</span></a>";
			if ( strlen($uptdtime) == 0 ) $uptdtime = $conf["UPTDTIME"];
		}
?>
			</dd>
			<dt>만화책</dt>
			<dd>
<?php
		$sql = "SELECT SITE_ID, SITE_NAME, SITE_URL, SITE_TYPE, UPTDTIME FROM SITE_INFO WHERE SITE_TYPE='manga' AND USE_LEVEL < 99999 AND USE_YN='Y' ORDER BY SITE_TYPE DESC, CAST(ORDER_NUM AS INT), SITE_NAME ASC;";
		$conf_result = $webtoonDB->query($sql);
		while($conf = $conf_result->fetchArray(SQLITE3_ASSOC)){
			echo "<a href='".$conf["SITE_URL"]."' target='_top'>".$conf["SITE_NAME"]."<span style='font-size:20px;'><br>[".$conf["SITE_URL"]."]</span></a>";
		}
?>
			</dd>
			<dt style="font-size:12px;"><?php echo $uptdtime; ?> Updated.</dt>
		</dl>
	</div>
	<!--// item : E -->

</div>
</body>
</html>
