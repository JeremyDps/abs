<?php
require 'bootstrap.php';
require '../classes/Events.php';
require 'EventValidator.php';
require '../classes/Event.php';
$pdo = get_pdo();
$events = new Events($pdo);
$errors = [];
try {
    $event = $events->find($_GET['id'], $_GET['pers'] );
} catch (\Exception $e) {
    e404();
} catch (\Error $e) {
    e404();
}

$data = [
    'nom'         => $event->getName(),
    'date'        => $event->getStart()->format('Y-m-d'),
    'start'       => $event->getStart()->format('H:i'),
    'end'         => $event->getEnd()->format('H:i'),
    'description' => $event->getDescription()
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $validator = new EventValidator();
    $errors = $validator->validates($data);
    if (empty($errors)) {
        $events->hydrate($event, $data);
        $events->update($event);
        header('Location: calendrier?pers='.$_GET['pers']);
        exit();
    }
}

render('header', ['title' => $event->getName()]);
?>

<div class="container">

    <h1>Editer l'évènement
        <small><?= h($event->getName()); ?></small>
    </h1>

    <form action="" method="post" class="form">
        <?php render('calendar/form', ['data' => $data, 'errors' => $errors]); ?>
        <div class="form-group">
            <button class="btn btn-primary">Modifier l'évènement</button>
        </div>
    </form>
</div>

<!--<?php render('footer'); ?>
