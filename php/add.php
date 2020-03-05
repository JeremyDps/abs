<?php
require 'bootstrap.php';
require 'Validator.php';
require 'EventValidator.php';
require '../classes/Events.php';
require '../classes/Event.php';



$data = [
    'date'  => $_GET['date'] ?? date('Y-m-d'),
    'start' => date('H:i'),
    'end'   => date('H:i')
];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $validator = new EventValidator();
    $errors = $validator->validates($_POST);
    if (empty($errors)) {
        $events = new Events(get_pdo());
        $event = $events->hydrate(new Event(), $data);
        $events->create($event, $_GET['pers']);
        header('Location: calendrier?pers='.$_GET['pers']);
        exit();
    }
}

render('header', ['title' => 'Ajouter un évènement']);
?>

<div class="container">

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            Merci de corriger vos erreurs
        </div>
    <?php endif; ?>

    <h1>Ajouter un évènement</h1>
    <form action="" method="post" class="form">
        <?php render('calendar/form', ['data' => $data, 'errors' => $errors]); ?>
        <div class="form-group">
            <button class="btn btn-primary">Ajouter l'évènement</button>
        </div>
    </form>
</div>
<!--<?php render('footer'); ?>
