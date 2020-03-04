<?php

require_once('DBClass.php');

class Classe{

    function allClasses() {
        $db = new DBClass('gestion_absence');

        return $db->selectAllClasses();
    }

    function allGroupes() {
        $db = new DBClass('gestion_absence');

        return $db->selectAllGroupes();
    }

    function classeByProf($username) {
    $db = new DBClass('gestion_absence');

    return $db->selectClasseByProf($username);
    }

    function classeByIdProf($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectClasseByIdProf($id);
    }

    function etuByClasse($classe) {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuByClasse($classe);
    }

    function groupeByClasse($classe) {
        $db = new DBClass('gestion_absence');

        return $db->selectGroupByClasse($classe);
    }

    function selectAllGroupesByClasse() {
        $db = new DBClass('gestion_absence');

        return $db->selectAllGroupByClasse();
    }

    function deleteGroupe($id){
        $db = new DBClass("gestion_absence");

        $db->deleteGroupe($id);
        echo $id;

        //header('Location: ../php/admin.php');
    }

    function groupById($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectGroupById($id);
    }

    function createGroupe($nom, $classe) {
        $db = new DBClass('gestion_absence');

        $db->insertGroupe($nom, $classe);

        header('Location: ../php/groupes.php');
    }

    function addClasseProf($classe) {
        session_start();

        $db = new DBClass('gestion_absence');

        $db->insertClasseProf($classe);

        header('Location: ../php/prof_cours?pers=' . $_SESSION['prof_id']. '&type=prof');
    }
}