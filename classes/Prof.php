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

    function idProf($nom, $prenom, $password, $role) {
        $db = new DBClass('gestion_absence');

        return $db->selectIdProf($nom, $prenom, $password, $role);
    }

    function createProf($nom, $prenom, $password, $role) {
        $db = new DBClass('gestion_absence');

        $db->insertProfesseur($nom, $prenom, $password, $role);

        $idProf = $this->idProf($nom, $prenom, $password, $role);

        header('Location: ../php/prof_cours.php?pers='.$idProf.'&type=prof');
    }

    function delete($id){
        $db = new DBClass("gestion_absence");

        $db->deleteProfesseur($id);

        header('Location: ../php/professeurs.php');
    }

    function addCours($idProf, $idClasse) {
        $db = new DBClass('gestion_absence');

        $db->insertCoursProf($idProf, $idClasse);

        header('Location: ../php/prof_cours.php?pers='.$idProf.'&type=prof');

    }

    function coursProf($idProf) {
        $db = new DBClass('gestion_absence');

        return $db->selectCoursProf($idProf);
    }

    function deleteCoursProf($idProf, $idCours) {
        $db = new DBClass('gestion_absence');

        $db->deleteCoursProf($idProf, $idCours);

        header('Location: ../php/prof_cours.php?pers='. $idProf . '&type=prof&select=true');
    }
}