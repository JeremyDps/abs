<?php

session_start();
if ($_SESSION['connecte'] == true && $_SESSION['role'] == 'admin') {

    require '../layout/header.php';

    if($_GET['type'] === 'prof') {
        require_once '../classes/Classe.php';
        require_once '../classes/Cours.php';
        require_once '../classes/Prof.php';

        $classe = new Classe();
        $cours = new Cours();
        $prof = new Prof();

        $_SESSION['prof_id'] = $_GET['pers'];
        echo $_SESSION['prof_id'];

        $classe_by_prof = $classe->classeByIdProf($_GET['pers']);
        $allClasse = $classe->allClasses();
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
        <p>Vous enseignez en :
            <?php  if(empty($classe_by_prof)) {
                echo 'pas encore de classe';
            } else {
                foreach($classe_by_prof as $c):
                    echo '<strong>' . $c['nom'] . ' </strong>' . ' ';
                endforeach;
            } ?>
        </p>

        <form method="post" action="../classes/traitement.php">
            <label name="classe">Ajouter une classe</label>
            <select name="classe">
                <?php foreach($allClasse as $c): ?>
                <option name="classe"><?= $c['nom'] ?></option>
                <?php endforeach  ?>
                <input class="btn btn-primary" type="submit" value="ajouter la classe" name="add_classe_prof">
            </select>
        </form>

        <form method="post" action="../classes/traitement.php">
            <label name="classe">Afficher les cours pour : </label>
            <select name="classe">
                <?php foreach($allClasse as $c): ?>
                    <option name="classe"><?= $c['nom'] ?></option>
                <?php endforeach  ?>
                <input class="btn btn-primary" type="submit" value="Rechercher les cours" name="select_classes_prof">
            </select>
        </form>

        <form method="post" action="../classes/traitement.php">
            <input class="btn btn-primary" type="submit" name="select_cours_prof" value="Afficher les cours du professeur">
        </form>

    <?php if(isset($_GET['classe'])) {
            $list_cours = $cours->coursByClasse($_GET['classe']);
    ?>

            <table class="table" id="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Matricule</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Formation</th>
                    <th scope="col">Cochez les cours attribués au professeur</th>
                </tr>
                </thead>
                <tbody>
                <form method="post" action="../classes/traitement.php">
                    <?php foreach ($list_cours as $c): ?>

                        <tr>
                            <th scope="row"><?= $c['matricule']?></th>
                            <td><?= $c['nom'] ?></td>
                            <td><?= $c['classe'] ?></td>
                            <td><input type='checkbox' name='tableau[]' value='<?= $c['id'] ?>'/></td>
                        </tr>

                    <?php endforeach ?>
                    <input class="btn btn-primary" type="submit" name="add_cours_prof" value="valider les cours pour ce professeur">
                </form>
                </tbody>
            </table>

    <?php  }
        if(isset($_GET['select'])) {
            if($_GET['select'] == true) {
                $cours_prof = $prof->coursProf($_SESSION['prof_id']);
    ?>

                <table class="table" id="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Tag</th>
                        <th scope="col">Matricule</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Formation</th>
                        <th scope="col">Supprimer le cours pour ce professeur</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cours_prof as $c): ?>

                            <tr>
                                <th scope="row"><?= $c['id']?></th>
                                <td><?= $c['matricule'] ?></td>
                                <td><?= $c['nom'] ?></td>
                                <td><?= $c['classe'] ?></td>
                                <td><a href="delete.php?type=cours_prof&cours=<?= $c['id'] ?>&pers=<?= $_SESSION['prof_id'] ?>">Supprimer le cours pour ce prof</a> </td>
                            </tr>

                        <?php endforeach ?>
                    </tbody>
                </table>

    <?php }}  ?>


    <?php  } else {
        echo 'Erreur';
    }
    ?>

<?php } else{
    header('Location: index.php');
}  ?>
