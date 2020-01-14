<?php
    session_start();
    if ($_SESSION['connecte'] == true) {
    require '../layout/header.php';
    require_once ('../classes/Classe.php');
    require_once('../classes/Cours.php');

    $classe = new Classe();

    $list_classe = $classe->classeByProf($_SESSION['username']);

    $cours = new Cours();

    $list_cours = $cours->coursByProf($_SESSION['username']);

?>



<h1 class="text-center">Bienvenue <?php echo $_SESSION['prenom'] ?></h1>

<div class="col-md-8">
    <form action="">
        <div class="form-group">
            <label for="classe">Classe</label>
            <select class="form-control" name="classe" id="classe">
                <option value="">Selectionner votre classe</option>
                <?php foreach ($list_classe as $classe): ?>
                <option value=""><?= $classe?></option>
                <?php endforeach?>
            </select>
            <label for="cours">Cours</label>
            <select class="form-control" name="cours" id="cours">
                <option value="">Selectionner le cour enseigné</option>
                <?php foreach ($list_cours as $cour): ?>
                <option value="DevWeb"><?= $cour?></option>
                <?php endforeach ?>
            </select>
            <label for="salle">Salle</label>
            <input type="text" class="form-control" name="salle" id="salle" placeholder="Entrer la salle dans laquelle vous vous trouvez">

        </div>

    </form>
    <button class="btn btn-primary" onclick="showTable()">Rechercher</button>
    <br>

    <br>
    <table class="table hide" id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Tag</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">Heure d'arrivée</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Genel</td>
            <td>Julien</td>
            <td>8h30</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Jeremy</td>
            <td>Dupuis</td>
            <td>13h00</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Planck</td>
            <td>Théo</td>
            <td></td>
        </tr>
        </tbody>
    </table>

</div>

<?php  }else{
    echo 'wesh';
    header('Location: index.php');
}  ?>
