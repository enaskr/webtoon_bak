<?php
	echo "Encrypted password is <b>".strtoupper(hash("sha256", $_GET["pass"]))."</b>";
?>
