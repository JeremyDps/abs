<?php
session_start();
if ($_SESSION['connecte'] == true && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == "secretariat")) {

    require '../layout/header.php';
    require_once '../classes/Classe.php';

    $classe = new Classe();

    $list_groupes = $classe->selectAllGroupesByClasse();
    $list_classe = $classe->allClasses();
?>
    <div>
        <nav class="navbar navbar-expand ">
            <ul class="navbar-nav">
                <li class="nav-item dropdown" style="margin-right: 50px; padding-left: 60px;">
                    <a class="btn btn-primary" href="professeurs.php">Liste des utilisateurs</a>
                </li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="groupes.php">Liste des Groupes</a></li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="cours.php">Liste des Cours</a></li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="passage.php">Passer les étudiants à l'année suivante</a></li>
            </ul>
        </nav>
    </div><br>
    <form method="post" action="../classes/traitement.php">
        <label name="classe">Choisissez la classe</label>
        <select name="classe">
            <?php foreach ($list_classe as $c) : ?>
            <option name="classe"><?= $c['nom'] ?></option>
            <?php endforeach  ?>
        </select>
        <input class="btn btn-primary" type="submit" name="groupes_by_classe" value="Afficher les groupes">
    </form>

    <?php if(isset($_GET['classe'])) {
        $list_groupe = $classe->selectGroupeByClasse($_GET['classe']);
    ?>

<a href="ajouter.php?type=group" class="btn btn-primary">Ajouter un groupe</a>
<table class="table" id="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Tag</th>
        <th scope="col">Nom du groupe</th>
        <th scope="col">Supprimer</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list_groupe as $g): ?>
        <tr>
            <td><?= $g['id'] ?></td>
            <td><a href="profile.php?pers=<?= $g['id'] ?>&type=group&groupe=<?= $g['nom'] ?>"><?= $g['nom'] ?></a></td>
            <td><a href="delete.php?type=group&pers=<?= $g['id'] ?>&groupe=<?= $g['nom'] ?>">Supprimer le groupe</a> </td>
        </tr>
    <?php endforeach ?>

    </tbody>
</table>

        <?php  }  ?>


<?php  }else{
    header('Location: index.php');
}  ?>
