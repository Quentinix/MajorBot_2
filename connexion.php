<?php

include 'inc/fonction.inc';
$verif = fonction_account_verif();
if ($verif[connect] === TRUE) header('location: manager.php');

if (fonction_account_connect(TRUE, $_POST[nom], $_POST[pass]) === TRUE) header('location: manager.php');
else header('location: index.php?return=1');

?>