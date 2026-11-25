<?php
require 'includes/functions.php';
requireLogin();
try {
    include 'includes/DatabaseConnection.php';

    $genreId = (int)($_GET['genre'] ?? 0);
    $search = trim($_GET['q'] ?? '');

    // Build the WHERE clause only from the filters that were supplied.
    $where = [];
    $params = [];
    if ($genreId > 0) {
        $where[] = 'film.genreid = :genreid';
        $params[':genreid'] = $genreId;
    }
    if ($search !== '') {
        $where[] = 'film.title LIKE :search';
        $params[':search'] = '%' . $search . '%';
    }

    // JOIN across three tables: film, reviewer and genre.
    $sql = 'SELECT film.*, reviewer.name AS reviewername, genre.name AS genrename
            FROM film
            INNER JOIN reviewer ON film.reviewerid = reviewer.id
            INNER JOIN genre ON film.genreid = genre.id';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY film.reviewdate DESC, film.id DESC';

    $films = query($pdo, $sql, $params)->fetchAll();
    $genres = query($pdo, 'SELECT * FROM genre ORDER BY name')->fetchAll();

    $title = 'Film reviews';
    ob_start();
    include 'templates/films.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . h($e->getMessage());
}
include 'templates/layout.html.php';
