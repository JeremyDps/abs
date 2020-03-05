<?php

require_once '../classes/Etudiant.php';

$etu = new Etudiant();

if(stristr($_GET['groupe'], 'TP')) {
    $switchGroupEtu = $etu->updateGroupeTPEtu($_GET['pers'], $_GET['target']);
} else {
    $switchGroupEtu = $etu->updateGroupeEtu($_GET['pers'], $_GET['target']);
}


?>
