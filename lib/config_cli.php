<?php
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT+9');
	header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('Content-Type: text/html; charset=UTF-8');
	header('Pragma: no-cache');
	header('Connection: close');

	include($homepath.'lib/simple_html_dom.php');
	include($homepath.'lib/dbconn_cli.php');

	function send_discord($webhookURL, $whContent, $whUserName, $whTitle, $whDescription, $whURL, $whColor) {
		$weekString = array("일", "월", "화", "수", "목", "금", "토");
			$json_data = json_encode([
			"content" => $whContent,
			"username" => $whUserName,
			"avatar_url" => "http://armtoon.enas.kr/lib/logo.gif",
			// Embeds Array
			"embeds" => [
				[
					"title" => $whTitle."\n[".date("Y-m-d",strtotime("now"))."(".$weekString[date('w', time())].") ".date("H:i",strtotime("now"))."]",
					"type" => "rich",
					"description" => $whDescription,
					"url" => $whURL,
					"color" => hexdec( $whColor )
				]
			]

		], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

		$discord_ch = curl_init( $webhookURL );
		curl_setopt( $discord_ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_setopt( $discord_ch, CURLOPT_POST, 1);
		curl_setopt( $discord_ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt( $discord_ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $discord_ch, CURLOPT_HEADER, 0);
		curl_setopt( $discord_ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $discord_ch );
		curl_close( $discord_ch );
	}

?>
