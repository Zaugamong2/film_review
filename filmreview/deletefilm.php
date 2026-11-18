<?php
require 'includes/functions.php';
// Only accept a POST with an id; opening this URL directly just returns to the list.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    redirect('films.php');
}
try {
    include 'includes/DatabaseConnection.php';

    $film = query($pdo, 'SELECT poster FROM film WHERE id = :id', [':id' => $_POST['id']])->fetch();
    query($pdo, 'DELETE FROM film WHERE id = :id', [':id' => $_POST['id']]);
    if ($film) {
        deletePosterFile($film['poster']);
    }
    redirect('films.php');
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Unable to delete the review: ' . h($e->getMessage());
    include 'templates/layout.html.php';
}
