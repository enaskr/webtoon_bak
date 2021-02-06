<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=2.0">
<title>NAVER 웹툰</title>
</head>
<body style="margin:0;padding:0">
<?php
	header('Content-Type: text/html; charset=UTF-8');
	include('../lib/simple_html_dom.php');
	
	$try_count = 3;

	$linkType = $_GET['type'];
	if ( $linkType == "latest" ) {
		$target = "https://comic.naver.com/search.nhn?keyword=".urlencode($_GET['title']);
		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($target);
			}
		}

		foreach($get_html_contents->find('ul.resultList') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				$newurl = 'https://comic.naver.com'.$g->href;
				break;
			}
			if ( strlen($newurl) > 5 ) break;
		}

		$get_html_contents = "";
		$target = $newurl;
		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 1000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($target);
			}
		}
		$newurl = '';
		foreach($get_html_contents->find('table.viewList') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('td.title') as $g){
				$h = str_get_html($g->innertext);
				$title = trim(strip_tags($h));
				foreach($h->find('a') as $i){
					$newurl = "https://comic.naver.com".$i->href;
					break;
				}
				if ( strlen($newurl) > 5 ) break;
			}
			if ( strlen($newurl) > 5 ) break;
		}
		echo "<script type='text/javascript'>location.replace('".$newurl."');</script>";

	} else if ( $linkType == "detail2" ) {
		$target = "https://search.naver.com/search.naver?where=nexearch&query=".urlencode($_GET['title']);
		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($target);
			}
		}

		foreach($get_html_contents->find('div.title_area') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				$newurl = $g->href;
			}
			echo "<script type='text/javascript'>location.replace('".$newurl."');</script>";
		}
	} else {
		$target = "https://comic.naver.com/search.nhn?keyword=".urlencode($_GET['title']);
		$get_html_contents = file_get_html($target);
		for($html_c = 0; $html_c < $try_count; $html_c++){
			if(strlen($get_html_contents) > 10000){
				break;
			} else {
				$get_html_contents = "";
				$get_html_contents = file_get_html($target);
			}
		}

		foreach($get_html_contents->find('ul.resultList') as $e){
			$f = str_get_html($e->innertext);
			foreach($f->find('a') as $g){
				$newurl = $g->href;
				echo "<script type='text/javascript'>location.replace('https://comic.naver.com".$newurl."');</script>";
				break;
			}
			if ( strlen($newurl) > 5 ) break;
		}
	}
?>
</body>
</html>