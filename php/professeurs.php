<?php

    session_start();
    if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

        require '../layout/header.php';
        require_once '../classes/Prof.php';

        $profs = new Prof();

        $list_profs = $profs->allProfs();
?>

<div class = "container">
    <div class="right">
        <form method="post" action="../classes/traitement.php">
            <label name="recherche">Rechercher (nom, prénom, id ou formation)</label>
            <input type="text" name="recherche">
            <input type="submit" name="rechercher">
        </form>

    </div>

    <table class="table" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tag</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Nom d'utilisateur</th>
            <th scope="col">Rôle</th>
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
            </tr>
        <?php endforeach ?>

        </tbody>
    </table>
<?php
    }else {
        header('Location: index.php');
    }
?>
