<?php

require_once('DBClass.php');

class Prof
{
    function allProfs() {
        $db = new DBClass('gestion_absence');

        return $db->selectAllProf();
    }

    function detailsProf($id) {
        $db = new DBClass("gestion_absence");

        return $db->selectDetailsProfesseur($id);
    }

    function updateProfesseur($username, $password, $role) {
        $db = new DBClass('gestion_absence');

        $db->updateProfesseurByUser($username, $password, $role);

        header("Location: ../php/profile.php?pers=".$_SESSION['idProf']."&type=prof");
    }

    function createProf($nom, $prenom, $password, $role) {
        $db = new DBClass('gestion_absence');

        $db->insertProfesseur($nom, $prenom, $password, $role);

        header('Location: ../php/professeurs.php');
    }

    function delete($id){
        $db = new DBClass("gestion_absence");

        $db->deleteProfesseur($id);

        header('Location: ../php/professeurs.php');
    }
}