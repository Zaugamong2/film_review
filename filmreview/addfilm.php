<?php
require 'includes/functions.php';
requireLogin();
try {
    include 'includes/DatabaseConnection.php';

    $errors = [];
    $film = ['title' => '', 'release_year' => '', 'score' => '', 'reviewtext' => '', 'genreid' => '', 'reviewerid' => '', 'poster' => null];

    if (isset($_POST['title'])) {
        $film = array_merge($film, collectFilm($_POST));
        $errors = validateFilm($film, $pdo);

        $upload = ['filename' => null, 'error' => null];
        if (!$errors) {
            $upload = savePoster($_FILES['poster'] ?? null);
            if ($upload['error']) {
                $errors[] = $upload['error'];
            }
        }

        if (!$errors) {
            $sql = 'INSERT INTO film SET
                title = :title,
                release_year = :year,
                score = :score,
                reviewtext = :reviewtext,
                genreid = :genreid,
                reviewerid = :reviewerid,
                poster = :poster,
                reviewdate = CURDATE()';
            query($pdo, $sql, filmParams($film, $upload['filename']));
            redirect('films.php');
        }
    }

    $genres = query($pdo, 'SELECT * FROM genre ORDER BY name')->fetchAll();
    $reviewers = query($pdo, 'SELECT * FROM reviewer ORDER BY name')->fetchAll();

    $title = 'Add a film review';
    $heading = 'Add a film review';
    $submitLabel = 'Add review';
    $isEdit = false;
    ob_start();
    include 'templates/filmform.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . h($e->getMessage());
}
include 'templates/layout.html.php';
