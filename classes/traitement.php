<?php
    require_once('User.php');
    require_once('Etudiant.php');
    require_once ('Classe.php');
    require_once ('Prof.php');
    require_once ('Cours.php');

    $user = new User();
    $etu = new Etudiant();
    $classes = new Classe();
    $prof = new Prof();
    $cours = new Cours();

    //vérification connexion
    if(isset($_POST['connexion'])) {
        $username = $_POST['username'];
        $mdp = $_POST['pswd'];

        $user->login($username, $mdp);
    }

    //verification modification etudiant
    if(isset($_POST['modifier_etu'])) {
        $absence = $_POST['absence'];
        $absenceNonJustifiee = $_POST['absenceNonJustifiee'];
        $badge = $_POST['badge'];

        $etu->updateEtudiant($absence, $absenceNonJustifiee, $badge);
    }

    if(isset($_POST['modifier_prof'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $prof->updateProfesseur($username, $password, $role);
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

    if(isset($_POST['create_etu'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $formation = $_POST['formation'];
        $groupe = $_POST['groupe'];
        $badge = $_POST['badge'];

        $etu->createEtudiant($nom, $prenom, $formation, $groupe, $badge);
    }

    if(isset($_POST['create_prof'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        echo $nom . " " . $prenom .  ' ' .$password . ' ' . $role;

        $prof->createProf($nom, $prenom, $password, $role);
    }

    if(isset($_POST['create_groupe'])) {
        $nom = $_POST['nom'];
        $classe = $_POST['classe'];


        $classes->createGroupe($nom, $classe);
    }

    if(isset($_POST['modifier_cours'])) {
        $matricule = $_POST['matricule'];
        $nom = $_POST['nom'];
        $classe = $_POST['classe'];


        $cours->updateCours($matricule, $nom, $classe);
    }

    if(isset($_POST['create_cours'])) {
        $matricule = $_POST['matricule'];
        $nom = $_POST['nom'];
        $classe = $_POST['classe'];

        $cours->createCours($matricule, $nom, $classe);
    }

    if(isset($_POST['select_etu_classe'])) {

        $etu->etuByClasse($_POST['classe']) ;

        header('Location: ../php/passage.php?id='.$_POST['classe']);
    }

    if(isset($_POST['changer_classe_etu'])) {
        foreach ($_POST["tableau"] as $key => $value) {

            $etu->changeClasseEtu($_POST['classe'], $value);
        }
    }