<?php

require_once ('DBClass.php');

class Etudiant {
    private $IdEtu;
    private $prenom;
    private $nom;
    private $formation;
    private $nbr_absence;
    private $absence_justifiee;
    private $classe_id;
    private $etat;

    function __construct() {

    }

    /*function __construct($prenom, $nom, $formation, $classe_id) {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->formation = $formation;
        $this->classe_id = $classe_id;
    }*/

    function createEtudiant($nom, $prenom, $formation, $groupe, $groupe_tp, $badge) {
        $db = new DBClass('gestion_absence');
        $db->insertEtudiant($nom, $prenom, $formation, $groupe, $groupe_tp, $badge);

        header('Location: ../php/admin.php');
    }

    function selectEtu() {
        $db = new DBClass('gestion_absence');
        return $db->selectAllEtu();
    }

    function updateEtudiant($badge, $groupe, $groupe_tp) {
        $db = new DBClass('gestion_absence');

        $db->updateEtudiantByUser($badge, $groupe, $groupe_tp);

        header("Location: ../php/profile.php?pers=".$_SESSION['idEtu']."&type=etu");
    }

    function search($recherche) {
        $db = new DBClass('gestion_absence');

        return $db->searchStudent($recherche);
    }

    function detailsEtudiaant($id) {
        $db = new DBClass("gestion_absence");

        return $db->selectDetailsEtudiant($id);
    }

    function delete($id){
        $db = new DBClass("gestion_absence");

        $db->deleteEtu($id);

        header('Location: ../php/admin.php');
    }

    function etuByGroup($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuByGroup($id);
    }

    function updateGroupeEtu($etudiant_id, $groupe_id) {
        $db = new DBClass('gestion_absence');

        $db->updateGroupeEtu($etudiant_id, $groupe_id);

        header('Location: ../php/groupes.php');
    }

    function updateGroupeTPEtu($etudiant_id, $groupe_id) {
        $db = new DBClass('gestion_absence');

        $db->updateGroupeTPEtu($etudiant_id, $groupe_id);

        header('Location: ../php/groupes.php');
    }

    function EtuByClasse($idClasse) {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuByClasse($idClasse);

    }

    function EtuById($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuById($id);

    }

    function changeClasseEtu($idClasse, $idEtu) {
        $db = new DBClass('gestion_absence');

        $db->updateClasseEtu($idClasse, $idEtu);

        header('Location: ../php/admin.php');
    }

    function etuWithoutGroup() {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuWithoutGroup();
    }

    function detailsEtudiantSansGroupe($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectDetailsEtudiantWithoutGroupe($id);
    }

    function etuByGroupTP($id) {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuByGroupTP($id);
    }

    function selectEtuDifferent($classe) {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuDifferent($classe);
    }
}