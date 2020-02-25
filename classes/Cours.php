<?php

require_once('DBClass.php');

class Cours
{
    function coursByProf($username) {
        $db = new DBClass('gestion_absence');

        return $db->selectCoursByProf($username);
    }

    function allCours() {
        $db = new DBClass('gestion_absence');

        return $db->selectAllCours();
    }

    function deleteCours($id) {
        $db = new DBClass('gestion_absence');

        $db->deleteCours($id);

        header('Location: ../php/cours.php');
    }

    function detailsCours($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectDetailsCours($id);
    }

    function updateCours($matricule, $nom, $classe) {
        $db = new DBClass('gestion_absence');

        $db->updateCours($matricule, $nom, $classe);

        header('Location: ../php/cours.php');
    }

    function createCours($matricule, $nom, $classe) {
        $db = new DBClass('gestion_absence');

        $db->insertCours($matricule, $nom, $classe);

        header('Location: ../php/cours.php');
    }
}