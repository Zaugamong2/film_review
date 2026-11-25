<?php
require 'includes/functions.php';
if (isLoggedIn()) {
    redirect('films.php');
}
$errors = [];
$email = '';
$needed = isset($_GET['needed']);
$registered = isset($_GET['registered']);

try {
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'] ?? '';

        include 'includes/DatabaseConnection.php';
        $user = query($pdo, 'SELECT id, name, password_hash FROM user WHERE email = :email', [':email' => $email])->fetch();

        // One generic message for both "no such email" and "wrong password" so the form does not reveal which emails exist.
        if ($user && $user['password_hash'] && password_verify($password, $user['password_hash'])) {
            logIn($user);
            redirect('films.php');
        }
        $errors[] = 'The email or password is incorrect.';
    }

    $title = 'Log in';
    ob_start();
    include 'templates/login.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . h($e->getMessage());
}
include 'templates/layout.html.php';
