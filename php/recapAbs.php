<?php
    require 'bootstrap.php';
    require '../classes/Event.php';
    require '../classes/Events.php';

    session_start();
    if ($_SESSION['connecte'] == true) {
    require '../layout/header.php';
    require_once '../classes/Etudiant.php';

    $etu = new Etudiant();

    $tabAbsents = array();
    $i = 0;

    $classe = $_GET['classe'];
    $cours = $_GET['cours'];
    $salle = $_GET['salle'];
    $start = $_GET['start'];
    $end = $_GET['end'];


    $tabAbsents['classe'] = $classe;
    $tabAbsents['cours'] = $cours;
    $tabAbsents['salle'] = $salle;

    $pdo = get_pdo();

    $abs = new Event();
    $abscence = new Events($pdo);

    $abs->setName('Absence');
    $abs->setStart($start);
    $abs->setEnd($end);
    $abs->setDescription('Absence non justifiée');
    foreach ($_POST["tableau"] as $key => $value) {
        $abscence->create($abs, $value);
    }

        header('Location: absence.php');
?>

<?php

    }else{
    header('Location: index.php');
    }

?>
