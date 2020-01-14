<?php
session_start();
require '../layout/header.php';

$_SESSION['idEtu'] = $_GET['etu'];
?>

<h1> Profil étudiant de <?php // $_POST['username']  ?></h1>



    <form method="post" action="../classes/traitement.php">
        <ul>
            <li>Nom de l'étudiant</li>
            <li>Absences: <input name="absence" type="text"></li>
            <li>Absences non justifier: <input name="absenceNonJustifiee" type="text"> </li>
            <li>Badges n° <input name="badge" type="text"></li>
        </ul>
        <input name="modifier" class="btn btn-primary" type="submit">
    </form>


</div>
