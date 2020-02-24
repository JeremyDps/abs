<?php
session_start();
if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

    require '../layout/header.php';

    require_once ('../classes/Etudiant.php');

    $etu = new Etudiant();

    $list_etudiants = $etu->selectEtu();

?>

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
                <td><a href="switch.php?pers=<?= $etudiant['idEtu'] ?>&target=<?= $_GET['pers'] ?>">Changer l'étudiant de groupe vers <?php echo $_GET['type']; ?></a></td>
            </tr>
        <?php endforeach ?>

        </tbody>
    </table>

<?php  }else{
    header('Location: index.php');
}  ?>
