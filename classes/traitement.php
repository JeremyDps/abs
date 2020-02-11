<?php
    require_once('User.php');
    require_once('Etudiant.php');
    require_once ('Classe.php');

    $user = new User();
    $etu = new Etudiant();
    $classes = new Classe();

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

    if(isset($_POST['rechercher'])) {
        if(empty($_POST['recherche'])) {
            header('Location: ../php/admin.php');
        }else{
            $recherche = $_POST['recherche'];

            $etu->search($recherche);

            header('Location: ../php/admin.php?param='.$recherche);
        }

    }

    if(isset($_POST['valider_cours'])) {
        $classe = $_POST['classe'];
        $cours = $_POST['cour'];
        $salle = $_POST['salle'];

        $classes->etuByClasse($classe);

        header('Location: ../php/absence.php?classe='.$classe);
    }