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
        $badge = $_POST['badge'];
        $groupe = $_POST['groupe'];
        $groupe_tp = $_POST['groupe_tp'];

        $etu->updateEtudiant($badge, $groupe, $groupe_tp);
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
        $cours = $_POST['cours'];
        $salle = $_POST['salle'];
        $start = $_POST['start'];
        $end = $_POST['end'];

        if(stristr($classe, 'TP')) {
            $classes->etuByClasseTP($classe);
        }

        $classes->etuByClasse($classe);

        header('Location: ../php/absence.php?classe='.$classe.'&cours='.$cours.'&salle='.$salle.'&start='.$start.'&end='.$end);
    }

    if(isset($_POST['create_etu'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $formation = $_POST['formation'];
        $groupe = $_POST['groupe'];
        $groupe_tp = $_POST['groupe_tp'];
        $badge = $_POST['badge'];

        $etu->createEtudiant($nom, $prenom, $formation, $groupe, $groupe_tp, $badge);
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

        if(stristr($nom, 'TP')) {
            if(stristr($nom, '1')) {
                if(stristr($nom, 'TPA') || stristr($nom, 'TPB')) {
                    $classe = "DUT1 TD1";
                } else if(stristr($nom, 'TPC') || stristr($nom, 'TPD')) {
                    $classe = "DUT1 TD2";
                } else if(stristr($nom, 'TPE') || stristr($nom, 'TPF')) {
                    $classe = "DUT1 TD3";
                }
                $classes->createGroupeTP($nom, $classe);
            } else if (stristr($nom, '2')) {
                if(stristr($nom, 'TPA') || stristr($nom, 'TPB')) {
                    $classe = "DUT2 TD1";
                } else if(stristr($nom, 'TPC') || stristr($nom, 'TPD')) {
                    $classe = "DUT2 TD2";
                } else if(stristr($nom, 'TPE') || stristr($nom, 'TPF')) {
                    $classe = "DUT2 TD3";
                }
                $classes->createGroupe($nom, $classe);
            }

        }


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

    if(isset($_POST['add_classe_prof'])) {
        $classe_prof = $_POST['classe'];

        $classes->addClasseProf($classe_prof);
    }

    if(isset($_POST['select_classes_prof'])) {
        session_start();
        $classe_prof = $_POST['classe'];

        header('Location: ../php/prof_cours.php?pers='.$_SESSION['prof_id'].'&type=prof&classe='.$classe_prof);
    }

    if(isset($_POST['add_cours_prof'])) {
        session_start();
        foreach ($_POST['tableau'] as $key => $value) {
            $prof->addCours($_SESSION['prof_id'], $value);
        }
    }

    if (isset($_POST['select_cours_prof'])) {
        session_start();

        header('Location: ../php/prof_cours.php?pers='. $_SESSION['prof_id'] .'&type=prof&select=true');
    }

    if(isset($_POST['groupes_by_classe'])) {
        $classe = $_POST['classe'];

        header('Location: ../php/groupes.php?classe='.$classe);
    }