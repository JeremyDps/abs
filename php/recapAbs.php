<?php

    session_start();
    if ($_SESSION['connecte'] == true) {
    require '../layout/header.php';
    require_once '../classes/Etudiant.php';

    $etu = new Etudiant();

    $tabAbsents = array();
    $i = 0;

    $classe = $_GET['classe'];
    $cours = $_GET['cours'];
    $salle = $_GET['salle'];

    $tabAbsents['classe'] = $classe;
    $tabAbsents['cours'] = $cours;
    $tabAbsents['salle'] = $salle;

    foreach ($_POST["tableau"] as $key => $value) {
        $etuById = $etu->etuById($value);
        $tabAbsents[$i] = array(
            'id' => $value,
            'nom' => $etuById['nom'],
            'prenom' => $etuById['prenom']
        );
        $i++;
    }

    var_dump($tabAbsents);

?>

<?php

    }else{
    echo 'wesh';
    header('Location: index.php');
    }

?>
