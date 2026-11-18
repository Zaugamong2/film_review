<?php
// Function library: small named helpers plus generic reusable ones.
require_once __DIR__ . '/config.php';

/** Escape text for safe output in HTML. */
function h($text)
{
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}

/** Redirect and stop the script (fixes the missing exit after header() in the lab files). */
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

/** GENERIC: run any prepared statement with parameters and return the statement. */
function query($pdo, $sql, $params = [])
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/** Fresh at 60% or above, otherwise Rotten (same rule as the Tomatometer). */
function ratingLabel($score)
{
    return ((int)$score >= 60) ? 'Fresh' : 'Rotten';
}

function formatDate($date)
{
    return date('j M Y', strtotime($date));
}

/** Trim and pick only the film fields from submitted data. */
function collectFilm(array $post)
{
    return [
        'title'        => trim($post['title'] ?? ''),
        'release_year' => trim($post['release_year'] ?? ''),
        'score'        => trim($post['score'] ?? ''),
        'reviewtext'   => trim($post['reviewtext'] ?? ''),
        'genreid'      => trim($post['genreid'] ?? ''),
        'reviewerid'   => trim($post['reviewerid'] ?? ''),
    ];
}

/** Server-side validation. Returns a list of error messages (empty = valid). */
function validateFilm(array $film, $pdo)
{
    $errors = [];
    $maxYear = (int)date('Y') + 2;

    if ($film['title'] === '' || mb_strlen($film['title']) > 150) {
        $errors[] = 'Enter a film title of up to 150 characters.';
    }
    if (filter_var($film['release_year'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1888, 'max_range' => $maxYear]]) === false) {
        $errors[] = "Enter a release year between 1888 and $maxYear.";
    }
    if (filter_var($film['score'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 100]]) === false) {
        $errors[] = 'Enter a score from 0 to 100.';
    }
    if (mb_strlen($film['reviewtext']) < 10 || mb_strlen($film['reviewtext']) > 2000) {
        $errors[] = 'Write a review of 10 to 2000 characters.';
    }
    if (query($pdo, 'SELECT COUNT(*) FROM genre WHERE id = :id', [':id' => $film['genreid']])->fetchColumn() == 0) {
        $errors[] = 'Choose a genre from the list.';
    }
    if (query($pdo, 'SELECT COUNT(*) FROM reviewer WHERE id = :id', [':id' => $film['reviewerid']])->fetchColumn() == 0) {
        $errors[] = 'Choose a reviewer from the list.';
    }
    return $errors;
}

/** Named parameters shared by the INSERT and UPDATE statements. */
function filmParams(array $film, $poster)
{
    return [
        ':title'      => $film['title'],
        ':year'       => (int)$film['release_year'],
        ':score'      => (int)$film['score'],
        ':reviewtext' => $film['reviewtext'],
        ':genreid'    => (int)$film['genreid'],
        ':reviewerid' => (int)$film['reviewerid'],
        ':poster'     => $poster,
    ];
}

/**
 * Validate and store an uploaded poster.
 * Returns ['filename' => string|null, 'error' => string|null].
 */
function savePoster($file)
{
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['filename' => null, 'error' => null];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['filename' => null, 'error' => 'The poster could not be uploaded. Try a smaller file.'];
    }
    if ($file['size'] > MAX_POSTER_BYTES) {
        return ['filename' => null, 'error' => 'The poster must be 2 MB or smaller.'];
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']); // checks real content, not the file name
    if (!isset($allowed[$mime])) {
        return ['filename' => null, 'error' => 'The poster must be a JPG, PNG or WebP image.'];
    }
    $dir = __DIR__ . '/../uploads/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime]; // random name avoids clashes and unsafe names
    if (!move_uploaded_file($file['tmp_name'], $dir . $name)) {
        return ['filename' => null, 'error' => 'The poster could not be saved.'];
    }
    return ['filename' => $name, 'error' => null];
}

function deletePosterFile($filename)
{
    if ($filename) {
        $path = __DIR__ . '/../uploads/' . basename($filename);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
