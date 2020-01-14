<?php
    session_start();
    include '../classes/DBClass.php';
    require '../layout/header.php';
    $db = new DBClass("gestion_absence");
    $pdo = $db->getPDO();
    $query = $pdo->prepare('SELECT * FROM etudiant where idEtu = ?');
    $query->execute(array($_GET['etu']));
    $etudiants = $query->fetchAll(PDO::FETCH_OBJ);

?>
<?php foreach ($etudiants as $etudiant): ?>
<h1> Profil étudiant de <?= $etudiant->nom ?> <?= $etudiant->prenom ?></h1>

<div class="container">

    <ul>
        <li><?= $etudiant->formation ?></li>
        <li>Absences non justifiés : <?= $etudiant->nbr_absence ?></li>
        <li>Absences justifiés : <?= $etudiant->absence_justifiee ?></li>
        <li>Badges n°<?= $etudiant->idEtu?></li>
    </ul>
    <?php endforeach ?>

    <a href="modifier.php" class="btn btn-primary">Modifier</a>
</div>