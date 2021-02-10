<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
?>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>NAVER 일간순위</dt>
			<dd>
				<div class='group' style='padding:0px;'>
<?php
	header('Content-Type: text/html; charset=UTF-8');

	$try_count = 3;

	echo "<div style='display: block;width:100%;'><ul style='margin:0;padding:0;overflow: hidden;list-style: none;'>";

	$target = "https://search.naver.com/search.naver?where=nexearch&sm=tab_etc&mra=bjQz&pkid=47&query=%EA%B8%89%EC%83%81%EC%8A%B9%20%EB%9E%AD%ED%82%B9%20%EC%9B%B9%ED%88%B0";
	$get_html_contents = file_get_html($target);
	for($html_c = 0; $html_c < $try_count; $html_c++){
		if(strlen($get_html_contents) > 10000){
			break;
		} else {
			$get_html_contents = "";
			$get_html_contents = file_get_html($target);
		}
	}

	foreach($get_html_contents->find('div.cm_tab_info_box') as $e){
		//echo $e; 
		$f = str_get_html($e->innertext);
		foreach($f->find('div.item') as $g){
			foreach($g->find('img') as $h){
				$thumb = $h->src;
			}
			foreach($g->find('div.title_box') as $h){
				$i = str_get_html($h->innertext);
				foreach($i->find('a') as $j){
					$title = trim(strip_tags($j));
				}
			}
			foreach($g->find('span.sub_text') as $h){
				$author = trim(strip_tags($h));
			}
			echo "<li style='box-sizing: border-box;position: relative;float: left; height:200px;width:33%;'><img src='".$thumb."' style='width:100%;max-height:124px;'><br><span style='overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;word-wrap:break-word; line-height: 1.0em;height: 3.0em;'><a style='padding:0 0 0 0;margin:0 0 0 0;background-color:#ffffff;font-size:14px;font-weight:bold;' href='./naverdetail.php?type=detail&title=".$title."'>".$title."<br>[".$author."]</a></span><span style='overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;word-wrap:break-word; line-height: 1.0em;height: 1.0em;'><a style='padding:0 0 0 0;margin:0 0 0 0;background-color:#ffffff;font-size:14px;font-weight:bold;'  href='./naverdetail.php?type=latest&title=".$title."'>최신화보기</a></span></li>";
		}
	}
	echo "</div>";
?>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
