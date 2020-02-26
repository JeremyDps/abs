<?php
    session_start();
if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {
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

    } else if($_GET['type'] === 'group') {

        require_once '../classes/Classe.php';
        require_once '../classes/Etudiant.php';
        $group = new Classe();
        $etu = new Etudiant();
        $coordonnees = $group->groupById($_GET['pers']);

        $list_etu = $etu->etuByGroup($coordonnees['id']);

    } else if($_GET['type'] === 'cours') {

        require_once '../classes/Cours.php';
        $cours = new Cours();
        $coordonnees = $cours->detailsCours($_GET['pers']);

    } else if($_GET['type'] === 'etuWithoutGroup') {

        require_once '../classes/Etudiant.php';
        $etu = new Etudiant();
        $coordonnees = $etu->detailsEtudiantSansGroupe($_GET['pers']);

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

    <?php  } else if ($_GET['type'] === 'group') {   ?>

        <h1> Groupe <?= $coordonnees['nom'] ?></h1>

        <div class="container">

            <ul>
                <li>Identifiant en base : n°<?= $coordonnees['id']?></li>
                <li>Classe : <?= $coordonnees['classe'] ?></li>


            </ul>

            <a class="btn btn-primary" href="allEtu.php?pers=<?= $_GET['pers']?>&type=<?= $coordonnees['nom'] ?>">Ajouter un étudiant</a>
            <?php if(!empty($list_etu)) {  ?>

                <table class="table" id="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Tag</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($list_etu as $e): ?>

                        <tr>
                            <th scope="row"><?= $e['id']?></th>
                            <td><a href="profile.php?pers=<?= $e['id'] ?>&type=etu"><?= $e['nom'] ?></a></td>
                            <td><?= $e['prenom'] ?></td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>

            <?php  } else {
                echo 'Il n\'y a pas d\'étudiant dans ce groupe';
            }
            ?>
    <?php  } else if($_GET['type'] === 'cours')  {  ?>

            <h1> Cours <?= $coordonnees['nom'] ?></h1>

            <div class="container">

                <ul>
                    <li>Identifiant en base : n°<?= $coordonnees['id']?></li>
                    <li>Matricule : <?= $coordonnees['matricule'] ?></li>
                    <li>Nom : <?= $coordonnees['nom'] ?></li>
                    <li>Formation : <?= $coordonnees['classe'] ?></li>
                </ul>

                <a href="modifier.php?pers=<?= $_GET['pers'] ?>&type=<?= $_GET['type'] ?>" class="btn btn-primary">Modifier</a>
            </div>

    <?php  } else if($_GET['type'] === 'etuWithoutGroup') { ?>

            <h1> Profil étudiant de <?= $coordonnees['nom'] ?> <?= $coordonnees['prenom'] ?></h1>

            <div class="container">

                <ul>
                    <li><?= $coordonnees['formation'] ?></li>
                    <li>Absences non justifiés : <?= $coordonnees['nbr_absence'] ?></li>
                    <li>Absences justifiés : <?= $coordonnees['absence_justifiee'] ?></li>
                    <li>Badges n°<?= $coordonnees['badge']?></li>
                </ul>

                <a href="modifier.php?pers=<?= $_GET['pers'] ?>&type=<?= $_GET['type'] ?>" class="btn btn-primary">Modifier</a>

    <?php } }else{
        header('Location: admin.php');
    }  ?>
</div>