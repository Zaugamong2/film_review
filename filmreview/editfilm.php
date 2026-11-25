<?php
require 'includes/functions.php';
requireLogin();
try {
    include 'includes/DatabaseConnection.php';

    $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
    $existing = query($pdo, 'SELECT * FROM film WHERE id = :id', [':id' => $id])->fetch();

    if (!$existing) {
        http_response_code(404);
        $title = 'Film not found';
        $output = '<h2>Film not found</h2><p>That review does not exist. <a href="films.php">Back to all films</a></p>';
    } else {
        $film = $existing;
        $errors = [];

        if (isset($_POST['title'])) {
            $film = array_merge($existing, collectFilm($_POST));
            $errors = validateFilm($film, $pdo);

            $upload = ['filename' => null, 'error' => null];
            if (!$errors) {
                $upload = savePoster($_FILES['poster'] ?? null);
                if ($upload['error']) {
                    $errors[] = $upload['error'];
                }
            }

            if (!$errors) {
                // New upload wins; otherwise keep the old poster unless "remove" was ticked.
                $poster = $upload['filename'] ?? $existing['poster'];
                if (!$upload['filename'] && isset($_POST['removeposter'])) {
                    $poster = null;
                }

                $sql = 'UPDATE film SET
                    title = :title,
                    release_year = :year,
                    score = :score,
                    reviewtext = :reviewtext,
                    genreid = :genreid,
                    reviewerid = :reviewerid,
                    poster = :poster
                    WHERE id = :id';
                $params = filmParams($film, $poster);
                $params[':id'] = $id;
                query($pdo, $sql, $params);

                if ($poster !== $existing['poster']) {
                    deletePosterFile($existing['poster']);
                }
                redirect('films.php');
            }
        }

        $genres = query($pdo, 'SELECT * FROM genre ORDER BY name')->fetchAll();
        $reviewers = query($pdo, 'SELECT * FROM reviewer ORDER BY name')->fetchAll();

        $title = 'Edit review: ' . $existing['title'];
        $heading = 'Edit review';
        $submitLabel = 'Save changes';
        $isEdit = true;
        ob_start();
        include 'templates/filmform.html.php';
        $output = ob_get_clean();
    }
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . h($e->getMessage());
}
include 'templates/layout.html.php';
