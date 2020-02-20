<?php
session_start();
if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

    require '../layout/header.php';
    require_once '../classes/Classe.php';

    $classe = new Classe();

    $classes = $classe->allClasses();
    var_dump($classes);

?>

<?php

 if($_GET['type'] === 'etu') {

 ?>

 <form action="../classes/traitement.php" method="post">
     <label name="nom"> Nom : </label>
     <input name="nom" type="text">  <br>
     <label name="prenom"> Prénom : </label>
     <input name="prenom" type="text">  <br>
     <label name="formation"> Formation : </label>
     <select id="formation" name="formation">
        <?php foreach($classes as $c):  ?>
            <option name="<?= $c['nom'] ?>"><?php echo $c['nom'] ?></option>
        <?php endforeach  ?>
     </select>  <br>
     <label name="badge"> Numéro de badge : </label>
     <input name="badge" type="text">  <br>
     <input type="submit" name="create_etu" value="Créer l'étudiant">
 </form>


<?php

}

?>



<?php  }else{
    header('Location: index.php');
}  ?>
