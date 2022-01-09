<?php
	include('config.php');

	$siteid = $_GET["siteid"];
	$toonid = $_GET["toonid"];
	$epiid = "";
	if (isset($_GET['epiid']) ) { 
		$epiid = $_GET['epiid'];  
	}
	$deleteCnt = 0;
	$cnt = 0;

	if ( $epiid != null && strlen($epiid) > 0 ) {
		$sql_view = "DELETE FROM 'USER_VIEW_DTL' WHERE MBR_NO='".$MBR_NO."' AND SITE_ID='".$siteid."' AND TOON_ID='".$toonid."' AND VIEW_ID='".$epiid."';";
		$cnt = $webtoonDB->exec($sql_view);
	}
	$deleteCnt = $deleteCnt + $cnt;

	$isAlreadyView = "SELECT MBR_NO, SITE_ID, TOON_ID, VIEW_ID, REGDTIME, UPTDTIME FROM USER_VIEW_DTL ";
	$isAlreadyView = $isAlreadyView." WHERE MBR_NO = '".$MBR_NO."' AND SITE_ID = '".$siteid."' AND TOON_ID = '".$toonid."' AND USE_YN='Y' ";
	$isAlreadyView = $isAlreadyView." LIMIT 1;";
	$webtoonView = $webtoonDB->query($isAlreadyView);
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$viewDate = $row["UPTDTIME"];
	}

	if ( $viewDate==null || strlen($viewDate) == 0 || ($epiid == null || strlen($epiid) == 0) && ($toonid != null && strlen($toonid)>0) ) {
		$sql_view = "DELETE FROM 'USER_VIEW_DTL' WHERE MBR_NO='".$MBR_NO."' AND SITE_ID='".$siteid."' AND TOON_ID='".$toonid."';";
		$cnt = $webtoonDB->exec($sql_view);
		$deleteCnt = $deleteCnt + $cnt;
		$sql_view = "DELETE FROM 'USER_VIEW' WHERE MBR_NO='".$MBR_NO."' AND SITE_ID='".$siteid."' AND TOON_ID='".$toonid."' ;";
		$cnt = $webtoonDB->exec($sql_view);
		$deleteCnt = $deleteCnt + $cnt;
	}
	
	if ( $deleteCnt > 0 ) {
?>
<script type="text/javascript">
	window.history.back();
</script>
<?php
	} else {
?>
<script type="text/javascript">
	window.history.back();
</script>
<?php
	}
?></body>
</html>
