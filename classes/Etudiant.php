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

    function createEtudiant($nom, $prenom, $formation, $groupe, $badge) {
        $db = new DBClass('gestion_absence');
        $db->insertEtudiant($nom, $prenom, $formation, $groupe, $badge);

        header('Location: ../php/admin.php');
    }

    function selectEtu() {
        $db = new DBClass('gestion_absence');
        return $db->selectAllEtu();
    }

    function updateEtudiant($abs, $absNonJustifiee, $badge) {
        $db = new DBClass('gestion_absence');

        $db->updateEtudiantByUser($abs, $absNonJustifiee, $badge);

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

        $switch_groupe = $db->updateGroupeEtu($etudiant_id, $groupe_id);

        header('Location: ../php/groupes.php');
    }
}