<?php
session_start();
if ($_SESSION['connecte'] == true && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == "secretariat")) {

    require '../layout/header.php';
    require_once '../classes/Cours.php';

    $cours = new Cours();
    $list_cours = $cours->allCours();
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
    <a href="ajouter.php?type=cours" class="btn btn-primary">Ajouter un cours</a>
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
