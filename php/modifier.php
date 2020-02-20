<?php
session_start();
require '../layout/header.php';


if ($_GET['type'] === 'prof') {

    require_once '../classes/Prof.php';
    $prof = new Prof();
    $coordonnees = $prof->detailsProf($_GET['pers']);
    $_SESSION['idProf'] = $_GET['pers'];

}else if ($_GET['type'] === 'etu'){

    require_once '../classes/Etudiant.php';
    $etu = new Etudiant();
    $_SESSION['idEtu'] = $_GET['pers'];
    $coordonnees = $etu->detailsEtudiaant($_GET['pers']);

} else {
    echo 'type inconnu';
}
?>

<?php

if($_GET['type'] === 'etu') {

    ?>
    <form method="post" action="../classes/traitement.php">
        <ul>
            <li>Absences: <input name="absence" type="number" value="<?= $coordonnees['nbr_absence'] ?>" required></li>
            <li>Absences non justifiées: <input name="absenceNonJustifiee" type="number" value="<?= $coordonnees['absence_justifiee'] ?>" required></li>
            <li>Badges n° <input name="badge" type="number" value="<?= $coordonnees['badge'] ?>" required></li>
        </ul>
        <input name="modifier_etu" class="btn btn-primary" type="submit">
    </form>

<?php

} else if ($_GET['type'] === 'prof') {

    ?>

    <form method="post" action="../classes/traitement.php">
        <ul>
            <li>Modifier le nom d'utilisateur <input name="username" type="text" value="<?= $coordonnees['username'] ?>" required></li>
            <li>Modifier le mot de passe : <input name="password" type="password" value="<?= $coordonnees['password'] ?>" required></li>
            <li>Modifier le role : <input name="role" type="text" value="<?= $coordonnees['role'] ?>" required></li>
        </ul>
        <input name="modifier_prof" class="btn btn-primary" type="submit">
    </form>

<?php

}

?>


