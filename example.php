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
			IP: <?=$server->host?><br>
			Online: <?=$server->online? "Yes": "No"?><br>
			Name: <?=$server->name?><br>
			Map: <?=$server->map?><br>
			Players: <?=$server->num_players?> / <?=$server->max_players?><br>
			Mode: <?=$server->dir?><br>
			Description: <?=$server->desc?><br>
		</div>
		<br>
		<?php } ?>
			
	</body>
</html>
