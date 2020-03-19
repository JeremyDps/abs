<?php
session_start();
if ($_SESSION['connecte'] == true && ($_SESSION['role'] == 'admin' || $_SESSION['role'] == "secretariat")) {

    require '../layout/header.php';
    if (isset ($_POST["tableau"])) {

        require_once '../classes/Classe.php';
        require_once '../classes/Etudiant.php';

        $classe = new Classe();
        $etu = new Etudiant();

        $list_classes = $classe->allClasses();

    ?>

        <h1>Récapitulatif</h1>


        <table class="table" id="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Tag</th>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Formation</th>
                <th scope="col">Cocher</th>
            </tr>
            </thead>
            <tbody>
            <form action="../classes/traitement.php" method="post">
            <?php foreach ($_POST["tableau"] as $key => $value) {
                $etuById = $etu->etuById($value);
            ?>

                <tr>
                    <th scope="row"><?= $etuById['id']?></th>
                    <td><a href="profile.php?pers=<?= $etuById['id'] ?>&type=etu"><?= $etuById['nom'] ?></a></td>
                    <td><?= $etuById['prenom'] ?></td>
                    <td><?= $etuById['formation'] ?></td>
                    <td><input type='checkbox' name='tableau[]' value='<?= $etuById['id'] ?>' checked/></td>
                </tr>
            <?php } ?>
                <label name="changement">Je fais passer ces étudiants en classe de : </label>
                <select id="classe" name="classe">
                    <?php  foreach ($list_classes as $c): ?>
                        <option name="classe" value="<?= $c['id'] ?>"><?= $c['nom'] ?></option>
                    <?php  endforeach  ?>
                </select>
                <input type="submit" name="changer_classe_etu" value="confirmer">
            </form>
            </tbody>
        </table>


<?php  }}else{
    header('Location: index.php');
}  ?>
