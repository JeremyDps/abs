<?php
session_start();
if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

    require '../layout/header.php';
    require_once '../classes/Cours.php';

    $cours = new Cours();
    $list_cours = $cours->allCours();
?>

    <table class="table" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tag</th>
            <th scope="col">Matricule</th>
            <th scope="col">Nom</th>
            <th scope="col">Formation</th>
            <th scope="col">Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list_cours as $l): ?>

            <tr>
                <th scope="row"><?= $l['id']?></th>
                <td><a href="profile.php?pers=<?= $l['id'] ?>&type=cours"><?= $l['matricule'] ?></a></td>
                <td><?= $l['nom'] ?></td>
                <td><?= $l['classe'] ?></td>
                <td><a href="delete.php?pers=<?= $l['id'] ?>&type=cours">Supprimer le cours</a></td>

            </tr>
        <?php endforeach ?>

        </tbody>
    </table>

<?php  }else{
    header('Location: index.php');
}  ?>
