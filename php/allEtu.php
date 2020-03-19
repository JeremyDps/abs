<?php
session_start();
if ($_SESSION['connecte'] == true && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == "secretariat")) {

    require '../layout/header.php';

    require_once ('../classes/Etudiant.php');

    $etu = new Etudiant();

    $list_etudiants = $etu->selectEtuDifferent($_GET['type']);

?>
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

    <table class="table" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tag</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Nombre d'absences</th>
            <th scope="col">Formation</th>
            <th scope="col">Changer de groupe</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list_etudiants as $etudiant): ?>

            <tr>
                <th scope="row"><?= $etudiant['idEtu']?></th>
                <td><a href="profile.php?etu=<?= $etudiant['idEtu'] ?>"><?= $etudiant['nom'] ?></a></td>
                <td><?= $etudiant['prenom'] ?></td>
                <td><?= $etudiant['nbr_absence'] ?></td>
                <td><?= $etudiant['formation'] ?></td>
                <td><a href="switch.php?pers=<?= $etudiant['idEtu'] ?>&target=<?= $_GET['pers'] ?>&groupe=<?= $_GET['type'] ?>">Changer l'étudiant de groupe vers <?php echo $_GET['type']; ?></a></td>
            </tr>
        <?php endforeach ?>

        </tbody>
    </table>

<?php  }else{
    header('Location: index.php');
}  ?>
