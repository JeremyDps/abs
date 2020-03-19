<?php

session_start();
if ($_SESSION['connecte'] == true && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == "secretariat")) {

    require '../layout/header.php';
    require_once '../classes/Classe.php';
    require_once '../classes/Etudiant.php';

    $classe = new Classe();
    $etu = new Etudiant();

    $list_classes = $classe->allClasses();


    /*if (isset ($_POST["tableau"])) {
        foreach ($_POST["tableau"] as $key => $value) {
            echo $value."<br />";
        }
    }
    else {
        echo "Aucune case n'a été cochée.<br />";
    }*/
?>
    <div>
        <nav class="navbar navbar-expand ">
            <ul class="navbar-nav">
                <li class="nav-item dropdown" style="margin-right: 50px; padding-left: 60px;">
                    <a class="btn btn-primary" href="professeurs.php">Liste des professeurs</a>
                </li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="groupes.php">Liste des Groupes</a></li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="cours.php">Liste des Cours</a></li>
                <li class="nav-item" style="margin-right: 50px;"><a class="btn btn-primary" href="passage.php">Passer les étudiants à l'année suivante</a></li>
            </ul>
        </nav>
    </div><br>

    <form method="post" action="../classes/traitement.php">
        <label name="classe">Choississez la classe : </label>
        <select id="classe" name="classe">
            <?php  foreach ($list_classes as $c): ?>
                <option name="classe" id="classe" value="<?= $c['id'] ?>"><?= $c['nom'] ?></option>
            <?php endforeach  ?>
        </select>
        <input type="submit" name="select_etu_classe" value="valider">
    </form>

    <?php  if(isset($_GET['id'])) {
           $list_etu = $etu->EtuByClasse($_GET['id']);
    ?>

        <table class="table" id="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Tag</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Cocher</th>
            </tr>
            </thead>
            <tbody>
                <form method="post" action="recap.php">
                    <?php foreach ($list_etu as $etudiant): ?>

                        <tr>
                            <th scope="row"><?= $etudiant['idEtu']?></th>
                            <td><a href="profile.php?etu=<?= $etudiant['idEtu'] ?>"><?= $etudiant['nom'] ?></a></td>
                            <td><?= $etudiant['prenom'] ?></td>
                            <td><input type='checkbox' name='tableau[]' value='<?= $etudiant['idEtu'] ?>' checked/></td>
                        </tr>
                    <?php endforeach ?>
                    <input type="submit" class="btn btn-primary" name="valider" value="Je fais passer ces étudiants à l'année suivante">
                </form>
            </tbody>
        </table>
<?php  }}else{
    header('Location: index.php');
}  ?>

