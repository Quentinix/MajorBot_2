<?php

echo "dealabs OK !<br />";

//include_once __DIR__.'/../../inc/fonction.inc';

$mod = "mod_dealabs";
$email = "qbreton8@gmail.com";

$xml_file = simplexml_load_file("https://www.dealabs.com/rss/custom/0tZB8CvjoHO3qclCSEv2dT1rcuyCAZUX.xml");

$jour_semaine = array(
		1 => "Lundi",
		2 => "Mardi",
		3 => "Mercredi",
		4 => "Jeudi",
		5 => "Vendredi",
		6 => "Samedi",
		7 => "Dimanche"
);

$mois_annee = array(
		1 => "janvier",
		2 => "février",
		3 => "mars",
		4 => "avril",
		5 => "mai",
		6 => "juin",
		7 => "juillet",
		8 => "août",
		9 => "septembre",
		10 => "octobre",
		11 => "novembre",
		12 => "décembre",
		
);

$base_id = fonction_mod_mysqlaccess_select("mod_dealabs", "verif_id");
if ($base_id != "") $base_array = explode(":", $base_id);
else $base_array = array();

$xml_count = count($xml_file->channel->item);
$xml_i = 0;

$heure = date("G");

/*
$DHoraire = strstr(date("c"), "+");
if ($DHoraire === "+02:00"){
	$heure++;
}
*/

$message = "<!DOCTYPE html><html lang=fr><body>";
$message .= "<div class=header>Nouveaux deals perso le ".$jour_semaine[date("N")]." ".date("j")." ".$mois_annee[date("n")]." à ".$heure." heure.</div><br><br>";
$new_id = array();
$new_id_i = 0;

while ($xml_i < $xml_count){
	$dealabs_id = explode("/", $xml_file->channel->item[$xml_i]->guid);
	if (array_search($dealabs_id[1], $base_array) === FALSE){
		$new_id[$new_id_i] = $dealabs_id[1];
		$new_id_i++;
		//Message en HTML
		$message .= 
		"<div class=group><img style='width: 100px; height: 100px' src='".$xml_file->channel->item[$xml_i]->enclosure[url]."'><div class=desc>".$xml_file->channel->item[$xml_i]->title."</div><br><div class=link>".$xml_file->channel->item[$xml_i]->link."</div></div><br><br>";
	}else{
		$new_id[$new_id_i] = $dealabs_id[1];
		$new_id_i++;
	}
	$xml_i++;
}

$message .= "</body></html>";

fonction_mod_mysqlaccess_write($mod, "verif_id", implode(":", $new_id));

$boundary = "-----=".md5(rand());
$header = "From: \"Major Bot\"<webmaster@quentinix.fr>\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: text/html;\r\n"." boundary=\"$boundary\"\r\n";
mail($email, " Nouveaux deals perso le ".$jour_semaine[date("N")]." ".date("j")." ".$mois_annee[date("n")]." à ".$heure." heure.", $message, $header);

//var_dump($message);
?>