<?php

echo "meteo_vigilance OK !<br />";

$mod = "mod_meteo_vigilance";
$user = "numero abo FreeMobile";
$pass = "Mot de passe API FreeMobile";
$email = "qbreton8@gmail.com";

include_once __DIR__.'/vigimeteo-master/index.php';

$xml_file = simplexml_load_file(__DIR__."/vigimeteo-master/carte_vigilance_meteo.xml");

$liste = array(
				0 => 57,
				1 => 67,
	);

$i = 0;
$message = "";

var_dump($xml_file);

while ($i < count($liste)) {
	//$alerte = fonction_mod_mysqlaccess_select("mod_meteo_vigilance","alerte_" . $liste[$i]);
	$alerte = 99;

	$dep = "dep_" . $liste[$i];
	$n_alerte = $xml_file->$dep->niveau;

	if ($alerte != $n_alerte){
		//fonction_mod_mysqlaccess_write($mod, "alerte", $n_alerte);
		$message .= "Le département ".$liste[$i]." est passée en vigilance ".strtolower($xml_file->$dep->alerte).".\r\n";
	}
	$i++;
}

//fonction_mod_freemobile_sendsms($user, $pass, $message);
$subject = "Changement vigilance météo !";
$boundary = "-----=".md5(rand());
$header = "From: \"Major Bot\"<webmaster@quentinix.fr>\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/alternative;\r\n"." boundary=\"$boundary\"\r\n";
mail($email, $subject, $message, $header);

?>