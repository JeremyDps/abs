<?php
session_start();
if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

    require '../layout/header.php';
    require_once '../classes/Classe.php';

    $classe = new Classe();

    $list_groupes = $classe->selectAllGroupesByClasse();
?>

<a href="ajouter.php?type=group" class="btn btn-primary">Ajouter un groupe</a>
<table class="table" id="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Tag</th>
        <th scope="col">Nom du groupe</th>
        <th scope="col">Formation</th>
        <th scope="col">Supprimer</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_groupes as $g): ?>
        <tr>
            <th scope="row"><?= $g['id']?></th>
            <td><a href="profile.php?pers=<?= $g['id'] ?>&type=group"><?= $g['nomGroupe'] ?></a></td>
            <td><?= $g['nomClasse'] ?></td>
            <td><a href="delete.php?type=group&pers=<?= $g['id'] ?>">Supprimer le groupe</a> </td>
        </tr>
    <?php endforeach ?>

    </tbody>
</table>


<?php  }else{
    header('Location: index.php');
}  ?>
