<?php
    session_start();
    if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

        require '../layout/header.php';

        require_once ('../classes/Etudiant.php');

        $etu = new Etudiant();

        $list_etudiants = $etu->selectEtu();

        if(isset($_GET['param'])) {
            $coordonnees_etu = $etu->search($_GET['param']);
        }
?>

<div class = "container">
    <div class="right">
        <form method="post" action="../classes/traitement.php">
            <label name="recherche">Rechercher (nom, prénom, id ou formation)</label>
            <input type="text" name="recherche">
            <input type="submit" name="rechercher">
        </form>

        <a href="professeurs.php">Liste des professeurs</a>

    </div>

    <?php  if(!isset($_GET['param'])) {  ?>
    <a href="ajouter.php?type=etu" class="btn btn-primary">Ajouter un étudiant</a>
    <table class="table" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tag</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Nombre d'absences</th>
            <th scope="col">Formation</th>
            <th scope="col">Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list_etudiants as $etudiant): ?>

        <tr>
            <th scope="row"><?= $etudiant['idEtu']?></th>
            <td><a href="profile.php?pers=<?= $etudiant['idEtu'] ?>&type=etu"><?= $etudiant['nom'] ?></a></td>
            <td><?= $etudiant['prenom'] ?></td>
            <td><?= $etudiant['nbr_absence'] ?></td>
            <td><?= $etudiant['formation'] ?></td>
            <td><a href="delete.php?type=etu&pers=<?= $etudiant['idEtu'] ?>">Supprimer l'étudiant</a> </td>
        </tr>
        <?php endforeach ?>

        </tbody>
    </table>

    <?php  }else{
        if(empty($coordonnees_etu)) {
            echo 'tableau vide';  }else{  ?>
        <table class="table" id="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Tag</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Nombre d'absences</th>
                <th scope="col">Formation</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($coordonnees_etu as $etudiant): ?>

                <tr>
                    <th scope="row"><?= $etudiant['idEtu']?></th>
                    <td><a href="profile.php?etu=<?= $etudiant['idEtu'] ?>"><?= $etudiant['nom'] ?></a></td>
                    <td><?= $etudiant['prenom'] ?></td>
                    <td><?= $etudiant['nbr_absence'] ?></td>
                    <td><?= $etudiant['formation'] ?></td>

                </tr>
            <?php endforeach ?>

            </tbody>
        </table>

        <a href="admin.php" title="Précédent"><i class="fa fa-arrow-left"></i>Précédent</a>
    <?php  }}  ?>
</div>
</body>
</html>

    <?php  }else{
        header('Location: index.php');
    }  ?>