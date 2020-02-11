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

    function createEtudiant($nom, $prenom, $formation, $badge) {
        $db = new DBClass('gestion_absence', 'root','');
        //$db->insertEtudiant($nom, $prenom, $formation, $badge);
    }

    function selectEtu() {
        $db = new DBClass('gestion_absence');
        return $db->selectAllEtu();
    }

    function updateEtudiant($abs, $absNonJustifiee, $badge) {
        $db = new DBClass('gestion_absence');

        $db->updateEtudiantByUser($abs, $absNonJustifiee, $badge);

        header("Location: ../php/profile.php?etu=".$_SESSION['idEtu']);
    }

    function search($recherche) {
        $db = new DBClass('gestion_absence');

        return $db->searchStudent($recherche);
    }
}