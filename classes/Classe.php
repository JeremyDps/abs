<?php

require_once('DBClass.php');

class Classe{
    function classeByProf($username) {
    $db = new DBClass('gestion_absence');

    return $db->selectClasseByProf($username);
    }

    function etuByClasse($classe) {
        $db = new DBClass('gestion_absence');

        return $db->selectEtuByClasse($classe);
    }

    function groupeByClasse($classe) {
        $db = new DBClass('gestion_absence');

        return $db->selectGroupByClasse($classe);
    }
}