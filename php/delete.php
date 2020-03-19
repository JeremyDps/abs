<?php
session_start();
if ($_SESSION['connecte'] == true && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == "secretariat")) {

    require '../layout/header.php';

    ?>

    <?php if($_GET['type'] === 'etu') {

        require_once '../classes/Etudiant.php';
        $etu = new Etudiant();
        $deleteEtu = $etu->delete($_GET['pers']);

    } else if($_GET['type'] === 'prof') {

        require_once '../classes/Prof.php';
        $prof = new Prof();
        $deleteProf = $prof->delete($_GET['pers']);

    } else if($_GET['type'] === 'group') {

        require_once '../classes/Classe.php';
        $classe = new Classe();
        if(stristr($_GET['groupe'], 'TP')) {
            $deleteGroupe = $classe->deleteGroupeTP($_GET['pers']);
        } else {
            $deleteGroupe = $classe->deleteGroupe($_GET['pers']);
        }


    } else if($_GET['type'] === 'cours') {
        require_once '../classes/Cours.php';
        $cours = new Cours();
        $deleteCours = $cours->deleteCours($_GET['pers']);
    } else if($_GET['type'] === 'cours_prof'){
        require_once '../classes/Prof.php';
        $prof = new Prof();
        $deleteCoursProf = $prof->deleteCoursProf($_GET['pers'], $_GET['cours']);
    } else {
        echo 'type de personne inconnu';
    }?>


<?php  }else{
    header('Location: index.php');
}  ?>

