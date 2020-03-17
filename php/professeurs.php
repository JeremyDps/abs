<?php

    session_start();
    if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

        require '../layout/header.php';
        require_once '../classes/Prof.php';

        $profs = new Prof();

        $list_profs = $profs->allProfs();
?>

<div class = "container">
    <div>
        <nav class="navbar navbar-expand ">
            <ul class="navbar-nav">
                <li class="nav-item dropdown" style="margin-right: 50px; padding-left: 60px;">
                    <a class="btn btn-primary" href="professeurs.php">Liste des professeurs</a>
                </li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="groupes.php">Liste des Groupes</a></li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="cours.php">Liste des Cours</a></li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="passage.php">Passer les étudiants à l'année suivante</a></li>
            </ul>
        </nav>
    </div><br>

    <a href="ajouter.php?type=prof" class="btn btn-primary">Ajouter un professeur</a>

    <table class="table" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tag</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Nom d'utilisateur</th>
            <th scope="col">Rôle</th>
            <th scope="col">Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list_profs as $prof): ?>

            <tr>
                <th scope="row"><?= $prof['id']?></th>
                <td><a href="profile.php?pers=<?= $prof['id'] ?>&type=prof"><?= $prof['nom'] ?></a></td>
                <td><?= $prof['prenom'] ?></td>
                <td><?= $prof['username'] ?></td>
                <td><?= $prof['role'] ?></td>
                <td><a href="delete.php?type=prof&pers=<?= $prof['id'] ?>">Supprimer le professeur</a> </td>
            </tr>
        <?php endforeach ?>

        </tbody>
    </table>
<?php
    }else {
        header('Location: index.php');
    }
?>
