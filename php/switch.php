<?php

require_once '../classes/Etudiant.php';

$etu = new Etudiant();

$switchGroupEtu = $etu->updateGroupeEtu($_GET['pers'], $_GET['target']);

?>
