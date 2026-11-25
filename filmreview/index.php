<?php
require 'includes/functions.php';
requireLogin();
try {
    include 'includes/DatabaseConnection.php';

    $sql = 'SELECT film.*, reviewer.name AS reviewername, genre.name AS genrename
            FROM film
            INNER JOIN reviewer ON film.reviewerid = reviewer.id
            INNER JOIN genre ON film.genreid = genre.id
            ORDER BY film.score DESC LIMIT 3';
    $films = query($pdo, $sql)->fetchAll();

    $title = SITE_NAME . ' - Home';
    ob_start();
    include 'templates/home.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . h($e->getMessage());
}
include 'templates/layout.html.php';
