<?php

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Automatisation des absences </title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <a class="navbar-brand" href="index.php">Gestion des absences</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <?php if($_SESSION['role'] == 'admin') {  ?>
                <li class="nav-item"><a href="../php/admin.php" class="nav-link">Page admin</a></li>
                <li class="nav-item"><a href="../php/absence.php" class="nav-link">Page Professeurs</a></li>
            <?php  }  ?>
        </ul>
        <?php if($_SESSION['connecte'] == true) {  ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item"><a href="logout.php" class="nav-link btn btn-outline-primary">Deconnexion</a></li>
            </ul>
        <?php  }else{  ?>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item"><a href="login.php" class="nav-link btn btn-outline-primary">Connexion</a></li>
        </ul>
        <?php  }  ?>

    </div>
</nav>

<main role="main" class="container">