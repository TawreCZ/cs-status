<?php
require __DIR__ . "/status.class.php";

$servers = array(
	"217.11.249.92:27592", 
	"217.11.249.93:27254",
	"109.74.146.19:27783",
	"31.31.76.12:35000",
	);

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Server Status</title>
	</head>
	<body>
		<?php		
		
		foreach ($servers as $serverIP) {
			$server = new Status($serverIP);
		
		?>
		<div>
			IPserveru: <?=$server->host?><br>
			Online: <?=$server->online? "Ano": "Ne"?><br>
			Jméno: <?=$server->name?><br>
			Mapa: <?=$server->map?><br>
			Hráèù: <?=$server->num_players?> / <?=$server->max_players?><br>
			Mod: <?=$server->dir?><br>
			Popis: <?=$server->desc?><br>
		</div>
		<br>
		<?php } ?>
			
	</body>
</html>