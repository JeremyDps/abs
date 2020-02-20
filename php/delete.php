<?php
session_start();
if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

    require '../layout/header.php';

    ?>

    <?php if($_GET['type'] === 'etu') {
        require_once '../classes/Etudiant.php';
        $etu = new Etudiant();
        $deleteEtu = $etu->delete($_GET['pers']);
    } ?>


<?php  }else{
    header('Location: index.php');
}  ?>

