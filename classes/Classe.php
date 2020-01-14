<?php

require_once('DBClass.php');

class Classe{
    function classeByProf($username) {
    $db = new DBClass('gestion_absence');

    return $db->selectClasseByProf($username);
    }
}