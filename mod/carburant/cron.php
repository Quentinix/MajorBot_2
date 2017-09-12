<?php

echo "Carburant OK !<br />";

$mod = "mod_carburant";
$user = "numero abo FreeMobile";
$pass = "Mot de passe API FreeMobile";
$email = "qbreton8@gmail.com";

$url = 'https://donnees.roulez-eco.fr/opendata/instantane';
$agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
$resultatzip = curl_exec ($ch);
curl_close($ch);

$down = fopen("PrixCarburants_instantane.zip", "w+");
fputs($down, $resultatzip);
fclose($down);

$zip = new ZipArchive;
if ($zip->open('PrixCarburants_instantane.zip') === TRUE) {
	$zip->extractTo('tmp/');
	$zip->close();
}

$xml_file = simplexml_load_file("tmp/PrixCarburants_instantane.xml");

$count_xml = count($xml_file);
$i = 0;

$ville = array(
		1 => "sarrebourg",
		2 => "lorquin"
);
$carburant = array(
		1 => "sp95"
);

$message = "";
$message_envoi = FALSE;

while ($i < $count_xml) {
	if (array_search(strtolower($xml_file->pdv[$i]->ville), $ville) != 	FALSE) {
		$i_prix = 0;
		$count_prix = count($xml_file->pdv[$i]->prix);
		while ($i_prix < $count_prix) {
			if (@array_search(strtolower($xml_file->pdv[$i]->prix[$i_prix][nom]), $carburant) != FALSE) {
				$parametre = @$xml_file->pdv[$i][id]."_".@$xml_file->pdv[$i]->prix[$i_prix][nom];
				$prix_db = fonction_mod_mysqlaccess_select($mod, $parametre);
				if ($prix_db != @$xml_file->pdv[$i]->prix[$i_prix][valeur]){
					$parametre_marque = "id:".@$xml_file->pdv[$i][id];
					$marque = fonction_mod_mysqlaccess_select($mod, $parametre_marque);
					/* TODO: Envoi message par SMS : notification Free mobile seulement
					if ($marque != "") $message = "La station essence ".$marque." à ".@$xml_file->pdv[$i]->ville." pour le carburant ".@$xml_file->pdv[$i]->prix[$i_prix][nom]." change de prix pour passer de ".$prix_db." à ".@$xml_file->pdv[$i]->prix[$i_prix][valeur].".";
					else $message = "La station essence à ".@$xml_file->pdv[$i]->ville." au ".@$xml_file->pdv[$i]->adresse." pour le carburant ".@$xml_file->pdv[$i]->prix[$i_prix][nom]." change de prix pour passer de ".$prix_db." à ".@$xml_file->pdv[$i]->prix[$i_prix][valeur].".";
					fonction_mod_freemobile_sendsms($user, $pass, $message);
					*/
					/* TODO: Envoi message par email */
					$message_envoi = TRUE;
					if ($marque != "") $message .= "La station essence ".$marque." à ".@$xml_file->pdv[$i]->ville." pour le carburant ".@$xml_file->pdv[$i]->prix[$i_prix][nom]." change de prix pour passer de ".$prix_db." à ".@$xml_file->pdv[$i]->prix[$i_prix][valeur].".\r\n";
					else $message .= "La station essence à ".@$xml_file->pdv[$i]->ville." au ".@$xml_file->pdv[$i]->adresse." pour le carburant ".@$xml_file->pdv[$i]->prix[$i_prix][nom]." change de prix pour passer de ".$prix_db." à ".@$xml_file->pdv[$i]->prix[$i_prix][valeur].".\r\n";
					fonction_mod_mysqlaccess_write($mod, $parametre, @$xml_file->pdv[$i]->prix[$i_prix][valeur]);
				}
			}
			$i_prix++;
		}
	}
	$i++;
}

if ($message_envoi){
	$boundary = "-----=".md5(rand());
	$header = "From: \"Major Bot\"<webmaster@quentinix.fr>\r\n";
	$header .= "MIME-Version: 1.0\r\n";
	$header .= "Content-Type: multipart/alternative;\r\n"." boundary=\"$boundary\"\r\n";
	mail($email, "Changement prix carburant", $message, $header);
}


?>