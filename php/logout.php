<?php

    // Supression des variables de session et de la session
    $_SESSION = array();
    session_destroy();

    // Supression des cookies de connexion automatique
    setcookie('login', '');
    setcookie('pass_hache', '');

    header('Location: index.php');

?>