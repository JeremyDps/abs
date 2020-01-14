<?php
    require_once('User.php');
    require_once('Etudiant.php');

    $user = new User();
    $etu = new Etudiant();

    //vérification connexion
    if(isset($_POST['connexion'])) {
        $username = $_POST['username'];
        $mdp = $_POST['pswd'];

        $user->login($username, $mdp);
    }

    //verification modification etudiant
    if(isset($_POST['modifier'])) {
        $absence = $_POST['absence'];
        $absenceNonJustifiee = $_POST['absenceNonJustifiee'];
        $badge = $_POST['badge'];

        $etu->updateEtudiant($absence, $absenceNonJustifiee, $badge);
    }