<?php

	include 'inc/fonction.inc';
	$verif = fonction_account_verif();
	if (@$verif[connect] === TRUE) header('location: manager.php');

?>

<!DOCTYPE html>
<html lang=fr>
	<head>
		<title>
			Major Bot : Manager
		</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<img class=logo alt="logo Major Bot" src=img/logo.png />
		<form method="post" action=connexion.php>
			<div class=texte>
				Major Bot : Manager
			</div>
			<input type=text name=nom placeholder="Nom d'utilisateur" required />
			<br />
			<input type=password name=pass placeholder="Mot de passe" required />
			<br />
			<input type=submit value=connexion />
		</form>
	</body>
</html>