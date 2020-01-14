<?php

require_once('DBClass.php');

class Cours
{
    function coursByProf($username) {
        $db = new DBClass('gestion_absence');

        return $db->selectCoursByProf($username);
    }
}