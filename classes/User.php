<?php

require_once('DBClass.php');

class User
{
    function login($username, $mdp) {
        $db = new DBClass('gestion_absence');

        if($db->connection($username, $mdp) == 1) {
            header('Location: ../php/absence.php');
            exit();
        }else if($db->connection($username, $mdp) == 2){
            header('Location: ../php/admin.php');
            exit();
        }else{
            echo "Erreur dans vos identifiants de connexion";
        }
    }
}