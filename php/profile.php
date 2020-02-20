<?php
    session_start();
    include '../classes/DBClass.php';
    require '../layout/header.php';



    if ($_GET['type'] === 'prof') {

        require_once '../classes/Prof.php';
        $prof = new Prof();
        $coordonnees = $prof->detailsProf($_GET['pers']);

    }else if ($_GET['type'] === 'etu'){

        require_once '../classes/Etudiant.php';
        $etu = new Etudiant();
        $coordonnees = $etu->detailsEtudiaant($_GET['pers']);
    } else {
        echo 'type inconnu';
    }

?>

<?php

    if($_GET['type'] === 'etu') {

?>
<h1> Profil étudiant de <?= $coordonnees['nom'] ?> <?= $coordonnees['prenom'] ?></h1>

<div class="container">

    <ul>
        <li><?= $coordonnees['formation'] ?></li>
        <li>Absences non justifiés : <?= $coordonnees['nbr_absence'] ?></li>
        <li>Absences justifiés : <?= $coordonnees['absence_justifiee'] ?></li>
        <li>Badges n°<?= $coordonnees['badge']?></li>
    </ul>

    <a href="modifier.php?pers=<?= $_GET['pers'] ?>&type=<?= $_GET['type'] ?>" class="btn btn-primary">Modifier</a>

    <?php  } else if($_GET['type'] === 'prof') {

    ?>

    <h1> Profil Professeur de <?= $coordonnees['nom'] ?> <?= $coordonnees['prenom'] ?></h1>

    <div class="container">

        <ul>
            <li>Identifiant en base : n°<?= $coordonnees['id']?></li>
            <li>Nom d'utilisateur : <?= $coordonnees['username'] ?></li>
        </ul>

        <a href="modifier.php?pers=<?= $_GET['pers'] ?>&type=<?= $_GET['type'] ?>" class="btn btn-primary">Modifier</a>

    <?php  }  ?>
</div>