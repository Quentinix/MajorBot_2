<?php

include 'inc/fonction.inc';
include 'inc/config.inc';
$verif = fonction_account_verif();
if (@$verif[connect] === FALSE) header('location: index.php');

$folder_list = scandir("mod/");

$list_mod = array();
var_dump($folder_list);

$mysqli_connect = mysqli_connect($config_mysqli_host, $config_mysqli_user, $config_mysqli_mdp, $config_mysqli_db);
if(mysqli_errno($mysqli_connect))
	trigger_error("Echec requête MySQL : ".mysqli_errno($mysqli_connect)." : ".mysqli_error($mysqli_connect), E_USER_ERROR);

for ($i=2; $i < count($folder_list); $i++) {
	$mysqli_result = mysqli_query($mysqli_connect, "SELECT * FROM `" . $config_mysqli_table_module . "` WHERE `nom` LIKE '" . $folder_list[$i] . "'");
	if(mysqli_errno($mysqli_connect))
		trigger_error("Echec requête MySQL : ".mysqli_errno($mysqli_connect)." : ".mysqli_error($mysqli_connect), E_USER_ERROR);
	while ($mysqli_row = mysqli_fetch_assoc($mysqli_result)) {
		$list_mod[$i]["actif"] = "Oui";
		$list_mod[$i]["nom"] = $folder_list[$i];
		$list_mod[$i]["cron"] = $mysqli_row["cron"];
		$list_mod[$i]["cron_time"] = $mysqli_row["cron_time"];
	}
	if (!isset($list_mod[$i])){
		$list_mod[$i]["actif"] = "Non";
		$list_mod[$i]["nom"] = $folder_list[$i];
		$list_mod[$i]["cron"] = "";
		$list_mod[$i]["cron_time"] = "";
	}
}


var_dump($list_mod);

?>

<!DOCTYPE html>
<html lang=fr>
	<head>
		<title>
			Major Bot : Manager
		</title>
	</head>
	<body>
		<div class=list_mod>
			<?php 
				$i = 2;
				while (@$folder_list[$i]){
					echo
						"<div id=mod>"			.
						$folder_list[$i++]		.
						"</div>"
					;
				}
			?>
		</div>
	</body>
</html>