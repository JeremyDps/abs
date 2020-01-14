<?php
    session_start();
    if ($_SESSION['connecte'] == true) {

        require '../layout/header.php';

        require_once ('../classes/Etudiant.php');

        $etu = new Etudiant();

        $list_etudiants = $etu->selectEtu();

?>

<ul class = "list-group" id = "list">
    <li class = "list-group-item"><button class="btn-primary" onclick="showTable()">Afficher</button></li>
    <li class = "list-group-item"><button class="btn-primary">Ajouter</button></li>
</ul>

<div class = "container">
    <div class="right">
        <input type="text">
        <input type="submit">
    </div>

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
        <?php foreach ($list_etudiants as $etudiant): ?>

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
</div>
</body>
</html>

    <?php  }else{
        echo 'wesh';
        header('Location: index.php');
    }  ?>