<?php
	include('../../lib/config.php');
	include($homepath.'lib/header.php');
?>
<script type="text/javascript">
	var tooncnt = 0;
	function checkRecent(obj, title, toonid, epiid) {
		$.ajax({
			url:"./checkRecent.php?title="+title+"&wr_id="+toonid+"&ws_id="+epiid,
			type:"GET",
			success: function(result) {
				resultStr = result.substr(0,1);
				resultUrl = result.substring(2);
				if (resultStr == "Y") {
					document.getElementById(obj).innerHTML = "<a href='"+resultUrl+"'>O</a>";
				} else {
					document.getElementById(obj).innerHTML = "<a href='"+resultUrl+"'>-</a>";
				}
			}
		});
	}
	function chkAll() {
		for (i=0; i<tooncnt; i++) {
			var toontitle = document.getElementById('toontitle'+i).value;
			var toonid = document.getElementById('toonid'+i).value;
			var epiid = document.getElementById('epiid'+i).value;
			checkRecent('toon'+i, toontitle, toonid, epiid);
		}
	}
</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt><?php echo $siteName; ?> - 내가 본 목록 <a href='javascript:void(0);' onClick="chkAll();"><img src='<?php echo $homeurl; ?>lib/img/refresh.png' height=30 width=30 style='margin-left:50px;'></a></dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
<?php

	$isAlreadyView = "SELECT UV.MBR_NO AS MBR_NO, UV.SITE_ID AS SITE_ID, UV.TOON_ID AS TOON_ID, UV.TOON_TITLE AS TOON_TITLE, UV.TOON_THUMBNAIL AS TOON_THUMBNAIL, UVD.VIEW_ID AS VIEW_ID, UVD.VIEW_TITLE AS VIEW_TITLE, UVD.UPTDTIME AS UPTDTIME FROM USER_VIEW UV, USER_VIEW_DTL UVD, ";
	$isAlreadyView = $isAlreadyView." ( SELECT UV1.MBR_NO AS MBR_NO, UV1.SITE_ID AS SITE_ID, UV1.TOON_ID AS TOON_ID, MAX(UVD1.UPTDTIME) AS UPTDTIME ";
	$isAlreadyView = $isAlreadyView." FROM USER_VIEW UV1, USER_VIEW_DTL UVD1 ";
	$isAlreadyView = $isAlreadyView." WHERE UV1.MBR_NO = UVD1.MBR_NO AND UV1.SITE_ID = UVD1.SITE_ID AND UV1.TOON_ID = UVD1.TOON_ID ";
	$isAlreadyView = $isAlreadyView." GROUP BY UV1.MBR_NO, UV1.SITE_ID, UV1.TOON_ID) UVV ";
	$isAlreadyView = $isAlreadyView." WHERE UV.MBR_NO = '".$MBR_NO."' AND UV.SITE_ID = '".$siteId."' AND UV.USE_YN='Y' ";
	$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVD.MBR_NO AND UV.SITE_ID = UVD.SITE_ID AND UV.TOON_ID = UVD.TOON_ID ";
	$isAlreadyView = $isAlreadyView." AND UV.MBR_NO = UVV.MBR_NO AND UV.SITE_ID = UVV.SITE_ID AND UV.TOON_ID = UVV.TOON_ID AND UVD.UPTDTIME = UVV.UPTDTIME ";
	$isAlreadyView = $isAlreadyView." ORDER BY UV.TOON_TITLE ASC, UVD.UPTDTIME DESC;";
	$webtoonView = $webtoonDB->query($isAlreadyView);
	$viewDate = "";
	$alreadView = "";
	$idx = 0;
	while($row = $webtoonView->fetchArray(SQLITE3_ASSOC)){         
		$toonid = $row["TOON_ID"];
		$toontitle = $row["TOON_TITLE"];
		$toonthumb = $row["TOON_THUMBNAIL"];
		$epiid = $row["VIEW_ID"];
		$epititle = $row["VIEW_TITLE"];
		$uptdtime = $row["UPTDTIME"];
		$tmpthumb = substr($toonthumb, 10);
		$posthumb = strpos($tmpthumb, "/");
		$lastthumb = $siteUrl.substr($tmpthumb, (strpos($tmpthumb, "/")));

		echo "<input type='hidden' name='toontitle".$idx."' id='toontitle".$idx."' value='".$toontitle."'>";
		echo "<input type='hidden' name='toonid".$idx."' id='toonid".$idx."' value='".$toonid."'>";
		echo "<input type='hidden' name='epiid".$idx."' id='epiid".$idx."' value='".$epiid."'>";
		echo "<tr style='background-color:#f8f8f8'>";
		echo "<td style='word-wrap:break-word;min-height:50px;max-height:150px;' valign=middle><a style='margin:0px;padding:0px;width:100%;' href='list.php?title=".urlencode($toontitle)."&wr_id=".$toonid."'><img src='".$lastthumb."' style='min-height:50px;max-height:150px;width:20%;float:left;'><span style='margin:0px;padding:0px;line-height:16px;height:32px;font-size:14px;overflow: hidden;text-overflow: ellipsis;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient: vertical;width:80%;'>".$epititle;
		echo "</span><span style='font-size:12px;margin:0px;padding:0px;line-height:12px;width:80%;'>(".$uptdtime.")</span></a></td> ";
		echo "<td id='toon".$idx."' style='width:40px;font-size:16px;color=#ff3232;' align=center valign=middle><a style='margin:0px;padding:0px;' href='javascript:void(0);' onClick=\"checkRecent('toon".$idx."', '".$toontitle."', '".$toonid."', '".$epiid."');\">확인</a></td>";
		echo "<td style='width:40px;font-size:16px;color=#ff3232;' align=center valign=middle><a style='margin:0px;padding:0px;' href='../../lib/remove_view.php?siteid=".$siteId."&toonid=".$toonid."'>삭제</a></td>";
		echo "</tr>\n";
		$idx++;
	}
?><script type="text/javascript">tooncnt = <?php echo $idx; ?>;</script>
						</table>
					</div>
				</dd>
			</dl>
		</div>
		<!--// item : E -->
	</div>
</body>
</html>
