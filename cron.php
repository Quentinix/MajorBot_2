<?php
/*
// Afficher les erreurs à l'écran
ini_set('display_errors', 1);
// Enregistrer les erreurs dans un fichier de log
ini_set('log_errors', 1);
// Nom du fichier qui enregistre les logs (attention aux droits à l'écriture)
ini_set('error_log', dirname(__file__) . '/log_error_php.txt');
// Afficher les erreurs et les avertissements
error_reporting(e_all);
*/
//mail("qbreton8@gmail.com", "test CRON", "Test CRON");

include_once __DIR__.'/inc/config.inc';
include_once __DIR__.'/inc/fonction.inc';

//var_dump($_SERVER);

//set_time_limit(30);

date_default_timezone_set('Europe/Paris');

$mysqli_connect = mysqli_connect($config_mysqli_host, $config_mysqli_user, $config_mysqli_mdp, $config_mysqli_db);
$mysqli_result = mysqli_query($mysqli_connect, "SELECT * FROM ".$config_mysqli_table_module);

$liste_module = array();

$timestampday = 3600*date("G") + 60*date("i") + date("s");

$i = 1;
$k = 1;
while ($row = mysqli_fetch_array($mysqli_result)){
	if (@$row[cron] == 1){
		$time1 = explode(",", @$row[cron_time]);
		$time1_count = count($time1);
		$j = 0;
		while ($j < $time1_count){
			$time2[$j] = explode("-", $time1[$j]);
			$j++;
		}
		$time2_count = count($time2);
		$j = 0;
		while ($j < $time2_count){
			$time3av[$j] = explode(":", $time2[$j][0]);
			$time3ap[$j] = explode(":", $time2[$j][1]);
			$j++;
		}
		$time3_count = count($time3av);
		$j = 0;
		while ($j < $time3_count){
			$time4av[$j] = $time3av[$j][0]*3600 + $time3av[$j][1]*60 + $time3av[$j][2];
			$time4ap[$j] = $time3ap[$j][0]*3600 + $time3ap[$j][1]*60 + $time3ap[$j][2];
			$j++;
		}
		$time4_count = count($time4av);
		$j = 0;
		$ok = FALSE;
		while ($j < $time4_count){
			if ($timestampday >= $time4av[$j] & $timestampday <= $time4ap[$j]) {
				$ok = TRUE;
			}
			$j++;
		}
		if ($ok == TRUE) {
			$liste_module[$k] = @$row[nom];
			$k++;
		}
		$i++;
	}
}

mysqli_close($mysqli_connect);

if (count($liste_module) == 0) exit;

$count_module = count($liste_module);

$module_actu = 1;
while ($module_actu <= $count_module){
	var_dump($module_actu);
	include_once __DIR__.'/mod/'.$liste_module[$module_actu].'/cron.php';
	$module_actu++;
}
var_dump($module_actu);
//var_dump($liste_module);


var_dump($timestampday);

//var_dump($_SERVER);

?>