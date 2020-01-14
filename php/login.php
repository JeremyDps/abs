<?php
//require '../layout/header.php'

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Automatisation des absences </title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>

<body>

<h1>Connexion</h1>
<div class="col-md-8">
    <form action="../classes/traitement.php" method="POST">
        <div class="form-group">
            <label for="username">Nom d'utilisateur</label>
            <input class="form-control" type="text" name="username" id="username">
            <label for="pswd">Mot de passe</label>
            <input class="form-control" type="password" name="pswd" id="pswd">
        </div>
        <button name="connexion" class="btn btn-primary" type="submit">Login</button>
    </form>
</div>

</body>
</html>