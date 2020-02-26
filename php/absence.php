<?php
    session_start();
    if ($_SESSION['connecte'] == true) {
        require '../layout/header.php';
        require_once('../classes/Classe.php');
        require_once('../classes/Cours.php');

        $classe = new Classe();

        $list_classe = $classe->classeByProf($_SESSION['username']);


        if (isset($_GET['classe'])) {
            $list_etu = $classe->etuByClasse($_GET['classe']);
        }


        $list_groupe_dut1 = $classe->groupeByClasse('DUT INFO 1');
        $list_groupe_dut2 = $classe->groupeByClasse('DUT INFO 2');

        $cours = new Cours();

        $list_cours = $cours->coursByProf($_SESSION['username']);

?>


    <h1 class="text-center">Bienvenue <?php echo $_SESSION['prenom'] ?></h1>

<div class="col-md-8">
    <form action="../classes/traitement.php" method="post">
        <div class="form-group">
            <label for="classe">Classe</label>
            <select class="form-control" name="classe" id="classe">
                <option value="">Selectionner votre classe</option>
                <?php foreach ($list_classe as $classe):
                if($classe == "DUT INFO 1") {
                ?>
                <optgroup label="<?= $classe ?>"></optgroup>
                <option value="<?= $classe ?>"><?= $classe ?></option>
                <?php foreach ($list_groupe_dut1 as $groupe):  ?>
                <option value="<?= $groupe['groupe'] ?>"><?= $groupe['groupe'] ?></option>
                <?php endforeach ?>
                <?php } if($classe == "DUT INFO 2")  {
                ?>
                    <optgroup label="<?= $classe ?>"></optgroup>
                    <option value="<?= $classe ?>"><?= $classe ?></option>
                    <?php foreach ($list_groupe_dut2 as $groupe):  ?>
                        <option value="<?= $groupe['groupe'] ?>"><?= $groupe['groupe'] ?></option>
                    <?php endforeach ?>
                <?php } else {  ?>
                        <option value="<?= $classe?>"><?= $classe?></option>
                    <?php } endforeach?>
                </select>
                <label for="cours">Cours</label>
                <select class="form-control" name="cours" id="cours">
                    <option value="">Selectionner le cour enseigné</option>
                    <?php foreach ($list_cours as $cour): ?>
                        <option value="<?= $cour?>"><?= $cour?></option>
                    <?php endforeach ?>
                </select>
                <label for="salle">Salle</label>
                <input type="text" class="form-control" name="salle" id="salle" placeholder="Entrer la salle dans laquelle vous vous trouvez">
                <button name="valider_cours" class="btn btn-primary" type="submit">Rechercher</button>

        </div>


    </form>
    <br>
    <br>
    <?php if(isset($_GET['classe'])) {
        $classee = $_GET['classe'];
        $cours = $_GET['cours'];
        $salle = $_GET['salle'];
        ?>
    <p>Classe : <strong><?php  echo $classee;  ?></strong>, cours : <strong><?php  echo $cours;  ?></strong>, salle : <strong><?php  echo $salle;  ?></strong></p>
    <table class="table" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID de l'étudiant</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Cochez les absents</th>
        </tr>
        </thead>
        <tbody>
            <form method="post" action="recapAbs.php?classe=<?= $classe?>&cours=<?= $cours ?>&salle=<?= $salle ?>">
                <?php foreach ($list_etu as $etudiant): ?>
                    <tr>
                        <th><?= $etudiant['idEtu'] ?></th>
                        <td><?= $etudiant['nom'] ?></td>
                        <td><?= $etudiant['prenom'] ?></td>
                        <td><input type='checkbox' name='tableau[]' value='<?= $etudiant['idEtu'] ?>'/></td>
                    </tr>
                <?php endforeach ?>
                <input class="btn btn-primary" type="submit" name="absents" value="valider les absents">
            </form>
        </tbody>
    </table>

    <?php  }  ?>
</div>

<?php  }else{
    echo 'wesh';
    header('Location: index.php');
}  ?>
