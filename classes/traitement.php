<?php
    require_once('User.php');

    $user = new User();

    //vérification connexion
    if(isset($_POST['connexion'])) {
        echo "wesh";
        $username = $_POST['username'];
        $mdp = $_POST['pswd'];

        $user->login($username, $mdp);
    }