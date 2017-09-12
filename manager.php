<?php

include 'inc/fonction.inc';
$verif = fonction_account_verif();
if (@$verif[connect] === FALSE) header('location: index.php');

$folder_list = scandir("mod/");
var_dump($folder_list);

$i = 2;
while (@$folder_list[$i]){
	echo $folder_list[$i++];
}

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