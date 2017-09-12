<?php

echo "meteo OK !<br />";

/*
 * 		
 *		
 */
 
 $APIwunderground = "3a03973bbdfdc6fe";
 $mod = "mod_meteo";
 $email = "qbreton8@gmail.com";

$ville= array (
		0 => "Sarrebourg",
		1 => "Laneuveville-les-lorquin",
		2 => "Strasbourg"
);
$ville_count = count($ville);
$ville_i = 0;
$texte_final = "<!DOCTYPE html><html lang=fr><body>";

while ($ville_i < $ville_count){
	$url = 'http://api.wunderground.com/api/'. $APIwunderground .'/hourly/lang:FR/q/France/'.$ville[$ville_i].'.json';
	$agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	
	$resultarray = NULL;
	
	while ($resultarray == NULL){
		$resultapi = curl_exec ($ch);
		$resultarray = json_decode($resultapi, TRUE);
	}
	curl_close($ch);
	
	$count_hourly = count($resultarray["hourly_forecast"]);
	
	//définition heures
	$heure_nuit = array (
			1 => "12:00 AM",
			2 => "1:00 AM",
			3 => "2:00 AM",
			4 => "3:00 AM",
			5 => "4:00 AM",
			6 => "5:00 AM"
	);
	$heure_matin = array (
			1 => "6:00 AM",
			2 => "7:00 AM",
			3 => "8:00 AM",
			4 => "9:00 AM",
			5 => "10:00 AM",
			6 => "11:00 AM"
	);
	$heure_am = array (
			1 => "12:00 PM",
			2 => "1:00 PM",
			3 => "2:00 PM",
			4 => "3:00 PM",
			5 => "4:00 PM",
			6 => "5:00 PM",
			7 => "6:00 PM"
	);
	$heure_soir = array (
			1 => "7:00 PM",
			2 => "8:00 PM",
			3 => "9:00 PM",
			4 => "10:00 PM",
			5 => "11:00 PM"
	);
	
	//conversion heure 12 24
	$heure_convert = array(
			 "12:00 AM" => "0:00",
			 "1:00 AM"  => "1:00",
			 "2:00 AM"  => "2:00",
			 "3:00 AM"  => "3:00",
			 "4:00 AM"  => "4:00",
			 "5:00 AM"  => "5:00",
			 "6:00 AM"  => "6:00",
			 "7:00 AM"  => "7:00",
			 "8:00 AM"  => "8:00",
			 "9:00 AM"  => "9:00",
			 "10:00 AM" => "10:00",
			 "11:00 AM" => "11:00",
			 "12:00 PM" => "12:00",
			 "1:00 PM"  => "13:00",
			 "2:00 PM"  => "14:00",
			 "3:00 PM"  => "15:00",
			 "4:00 PM"  => "16:00",
			 "5:00 PM"  => "17:00",
			 "6:00 PM"  => "18:00",
			 "7:00 PM"  => "19:00",
			 "8:00 PM"  => "20:00",
			 "9:00 PM"  => "21:00",
			 "10:00 PM" => "22:00",
			 "11:00 PM" => "23:00"
	);
	
	//Variables evenements
	$date_du_jour_ok = FALSE;
	$temp_mini = 99;
	$temp_maxi = -99;
	$heure_actu = NULL;
	$heure_decal = NULL;
	$condition_actu = NULL;
	$condition_decal = NULL;
	$i = 0;
	
	while ($i < $count_hourly){
		if ($resultarray["hourly_forecast"][$i]["FCTTIME"]["mday"] == date("j") + 1){
			
			if ($date_du_jour_ok == FALSE){
				$texte_final .= "<br><br>Le ".$resultarray["hourly_forecast"][$i]["FCTTIME"]["mday"]." ".$resultarray["hourly_forecast"][$i]["FCTTIME"]["month_name"]." ".$resultarray["hourly_forecast"][$i]["FCTTIME"]["year"]." à ".$ville[$ville_i]."<br>";
				$date_du_jour = $resultarray["hourly_forecast"][$i]["FCTTIME"]["mday"]." ".$resultarray["hourly_forecast"][$i]["FCTTIME"]["month_name"]." ".$resultarray["hourly_forecast"][$i]["FCTTIME"]["year"];
				$date_du_jour_ok = TRUE;
			}
			if ($resultarray["hourly_forecast"][$i]["temp"]["metric"] < $temp_mini) $temp_mini = $resultarray["hourly_forecast"][$i]["temp"]["metric"];
			if ($resultarray["hourly_forecast"][$i]["temp"]["metric"] > $temp_maxi) $temp_maxi = $resultarray["hourly_forecast"][$i]["temp"]["metric"];
			if (!isset($tendance_temp1)) $tendance_temp1 = $resultarray["hourly_forecast"][$i]["temp"]["metric"];
			else $tendance_temp2 = $resultarray["hourly_forecast"][$i]["temp"]["metric"];
			if (array_search($resultarray["hourly_forecast"][$i]["FCTTIME"]["civil"], $heure_nuit) != FALSE) $heure_actu = "la nuit";
			if (array_search($resultarray["hourly_forecast"][$i]["FCTTIME"]["civil"], $heure_matin) != FALSE) $heure_actu = "le matin";
			if (array_search($resultarray["hourly_forecast"][$i]["FCTTIME"]["civil"], $heure_am) != FALSE) $heure_actu = "l'après midi";
			if (array_search($resultarray["hourly_forecast"][$i]["FCTTIME"]["civil"], $heure_soir) != FALSE) $heure_actu = "le soir";
			$condition_actu = $resultarray["hourly_forecast"][$i]["condition"];
			
			if ($heure_actu != $heure_decal){
				if ($heure_actu != "la nuit") {
					//$texte_final .= "Les températures seront de ".$temp_mini."°C à ".$temp_maxi."°C.<br>";
					if ($tendance_temp1 < $tendance_temp2) $texte_final .= "Les températures seront de ".$temp_mini."°C à ".$temp_maxi."°C.<br>";
					elseif ($tendance_temp1 > $tendance_temp2) $texte_final .= "Les températures seront de ".$temp_maxi."°C à ".$temp_mini."°C.<br>";
					else $texte_final .= "La température sera de ".$temp_mini."°C.<br>";
					unset($tendance_temp1);
				}
				$temp_mini = 99;
				$temp_maxi = -99;
				$texte_final .= "<br>Temps pour ".$heure_actu.":<br>";
				$heure_decal = $heure_actu;
				$condition_decal = NULL;
			}
			if ($condition_actu != $condition_decal){
				$texte_final .= "A ".$heure_convert[$resultarray["hourly_forecast"][$i]["FCTTIME"]["civil"]]." le temps est ".strtolower($condition_actu).". <img style='width: 27px; height: 27px;' src='".$resultarray["hourly_forecast"][$i]["icon_url"]."'><br>";
				$condition_decal = $condition_actu;
			}
			
		}elseif ($resultarray["hourly_forecast"][$i]["FCTTIME"]["hour"] == date("j") + 2) {
			break;
		}
		
		$i++;
	}
	//$texte_final .= "Les températures seront de ".$temp_mini."°C à ".$temp_maxi."°C.<br>";
	if ($tendance_temp1 < $tendance_temp2) $texte_final .= "Les températures seront de ".$temp_mini."°C à ".$temp_maxi."°C.<br>";
	elseif ($tendance_temp1 > $tendance_temp2) $texte_final .= "Les températures seront de ".$temp_maxi."°C à ".$temp_mini."°C.<br>";
	else $texte_final .= "La température sera de ".$temp_mini."°C.<br>";
	unset($tendance_temp1);
	$ville_i++;
}
$texte_final .= "<br>Bonne journée !";
$texte_final .= "</body></html>";

$boundary = "-----=".md5(rand());
$header = "From: \"Major Bot\"<webmaster@quentinix.fr>\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: text/html;\r\n"." boundary=\"$boundary\"\r\n";
mail($email, "Météo pour le ".$date_du_jour, $texte_final, $header);  //TODO: code debug




?>