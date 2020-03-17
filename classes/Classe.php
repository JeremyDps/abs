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

        header('Location: ../php/admin.php');
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

    function allGroupesTP() {
        $db = new DBClass('gestion_absence');

        return $db->selectAllGroupesTP();
    }

    function selectGroupeByClasse($classe) {
        $db = new DBClass('gestion_absence');

        return $db->selectGroupeByClasse($classe);
    }

    function selectGroupeById($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectGroupeById($id);
    }

    function groupTPById($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectGroupeTPById($id);
    }

    function createGroupeTP($nom, $groupe_td) {
        $db = new DBClass('gestion_absence');

        $db->insertGroupeTP($nom, $groupe_td);

        header('Location: ../php/groupes.php');
    }

    function deleteGroupeTP($id) {
        $db = new DBClass("gestion_absence");

        $db->deleteGroupeTP($id);

        header('Location: ../php/admin.php');
    }

    function etuByClasseTP($classe) {
        $db = new DBClass("gestion_absence");

        return $db->selectEtuByClasseTP($classe);
    }

    function etuByFormation($classe) {
        $db = new DBClass("gestion_absence");

        return $db->selectEtuByFormation($classe);
    }
}