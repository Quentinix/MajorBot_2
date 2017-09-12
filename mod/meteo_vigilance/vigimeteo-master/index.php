<?php

/*************************************************************************************
**												
** Script vigilance m�t�o pour syst�mes domotique
**												
** V1 - DjMaboul - http://www.touteladomotique.com/forum/viewtopic.php?f=72&t=1724
**						
** Version ult�rieures - DjMomo - Voir Readme.md
**
**************************************************************************************/

$fichierXML = __DIR__."/carte_vigilance_meteo.xml";

require_once(__DIR__."/VigilanceMeteo.class.php");
$fichier = false;
$format = "xml";

// Choix entre affichage ou sauvegarde
$_GET_lower = array_change_key_case($_GET, CASE_LOWER);
if (isset ($_GET_lower['json']))
{
	$format = "json";
	header('Content-Type: application/json; charset=utf-8');
}
elseif (isset ($_GET_lower['xml']))
	$format = "xml";
else
	$fichier = $fichierXML;

$meteo = new VigilanceMeteo($format,"Etats de vigilance m�t�orologique des d�partements (m�tropole et outre-mer) et territoires d'outre-mer fran�ais");
$meteo->DonneesVigilance($fichier);

// Et c'est tout !

?>
