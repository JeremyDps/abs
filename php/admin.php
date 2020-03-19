<?php
    include "../classes/DBClass.php";

    $db = new DBClass('gestion_absence');


    session_start();
    if ($_SESSION['connecte'] == true && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == "secretariat")) {

        require '../layout/header.php';

        require_once ('../classes/Etudiant.php');

        $etu = new Etudiant();

        $list_etudiants = $etu->selectEtu();

        if(isset($_GET['param'])) {
            $coordonnees_etu = $etu->search($_GET['param']);
        }

        $list_etudiants_sans_groupe = $etu->etuWithoutGroup();



?>

<div class = "container">
    <div>
        <form method="post" action="../classes/traitement.php">
            <input class="form-control mr-sm-2" type="text" name="recherche" placeholder="Rechercher (nom, prénom, id ou formation)">
            <input class="btn btn-primary" type="submit" name="rechercher">
        </form><br>
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

    <?php  if(!isset($_GET['param'])) {  ?>
    <a href="ajouter.php?type=etu" class="btn btn-primary">Ajouter un étudiant</a><br>
    <table class="table" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tag</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Absences non justifiées</th>
            <th scope="col">Absences justifiées</th>
            <th scope="col">Formation</th>
            <th scope="col">Groupe</th>
            <th scope="col">TP</th>
            <th scope="col">Supprimer</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list_etudiants as $etudiant): ?>
        <tr>
            <th scope="row"><?= $etudiant['idEtu']?></th>
            <td><a href="profile.php?pers=<?= $etudiant['idEtu'] ?>&type=etu"><?= $etudiant['nom'] ?></a></td>
            <td><?= $etudiant['prenom'] ?></td>
            <td><?= $db->countAbs($etudiant['idEtu']) ?></td>
            <td><?= $db->countAbsJustifier($etudiant['idEtu']) ?></td>
            <td><?= $etudiant['formation'] ?></td>
            <td><?= $etudiant['groupe'] ?></td>
            <td><?= $etudiant['tp'] ?></td>
            <td><a href="delete.php?type=etu&pers=<?= $etudiant['idEtu'] ?>">Supprimer l'étudiant</a> </td>
        </tr>
        <?php endforeach ?>

        </tbody>
    </table>

        <?php  if(!empty($list_etudiants_sans_groupe)) {  ?>
        <h3> Ici la liste des étudiant sans groupe existant, pensez à les ajouter au plus vite dans un groupe</h3>

        <table class="table" id="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Tag</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Absences non justifiées</th>
                <th scope="col">Absences justifiées</th>
                <th scope="col">Formation</th>
                <th scope="col">Groupe</th>
                <th scope="col">Groupe TP</th>
                <th scope="col">Supprimer</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($list_etudiants_sans_groupe as $etudiant): ?>

                <tr>
                    <th scope="row"><?= $etudiant['id']?></th>
                    <td><a href="profile.php?pers=<?= $etudiant['id'] ?>&type=etuWithoutGroup"><?= $etudiant['nom'] ?></a></td>
                    <td><?= $etudiant['prenom'] ?></td>
                    <td><?= $db->countAbs($etudiant['id']) ?></td>
                    <td><?= $db->countAbsJustifier($etudiant['id']) ?></td>
                    <td><?= $etudiant['formation'] ?></td>
                    <td><?= $etudiant['groupe'] ?></td>
                    <td><?= $etudiant['groupe_tp'] ?></td>
                    <td><a href="delete.php?type=etu&pers=<?= $etudiant['id'] ?>">Supprimer l'étudiant</a> </td>
                </tr>
            <?php endforeach ?>

            </tbody>
        </table>

    <?php  }}else{
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
                    <?php if($etudiant['groupe'] != 'Aucun groupe') { ?>
                        <td><a href="profile.php?pers=<?= $etudiant['idEtu'] ?>&type=etu"><?= $etudiant['nom'] ?></a></td>
                    <?php } else { ?>
                        <td><a href="profile.php?pers=<?= $etudiant['idEtu'] ?>&type=etuWithoutGroup"><?= $etudiant['nom'] ?></a></td>
                    <?php  }  ?>
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