<?php
require 'includes/functions.php';
if (isLoggedIn()) {
    redirect('films.php');
}
$errors = [];
$values = ['name' => '', 'email' => ''];

try {
    if (isset($_POST['email'])) {
        $values = [
            'name'  => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if ($values['name'] === '' || mb_strlen($values['name']) > 100) {
            $errors[] = 'Enter your name (up to 100 characters).';
        }
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($values['email']) > 150) {
            $errors[] = 'Enter a valid email address.';
        }
        // bcrypt only uses the first 72 bytes, so longer passwords are refused rather than silently cut.
        if (strlen($password) < 8 || strlen($password) > 72) {
            $errors[] = 'Choose a password of 8 to 72 characters.';
        }
        if ($password !== $confirm) {
            $errors[] = 'The two passwords do not match.';
        }

        include 'includes/DatabaseConnection.php';

        if (!$errors) {
            $taken = query($pdo, 'SELECT COUNT(*) FROM user WHERE email = :email', [':email' => $values['email']])->fetchColumn();
            if ($taken > 0) {
                $errors[] = 'That email address is already registered. Try logging in instead.';
            }
        }

        if (!$errors) {
            try {
                query($pdo, 'INSERT INTO user SET name = :name, email = :email, password_hash = :hash', [
                    ':name'  => $values['name'],
                    ':email' => $values['email'],
                    ':hash'  => password_hash($password, PASSWORD_DEFAULT),
                ]);
            } catch (PDOException $e) {
                // 1062 = duplicate key: two people registered the same email at the same moment.
                if (($e->errorInfo[1] ?? 0) == 1062) {
                    $errors[] = 'That email address is already registered. Try logging in instead.';
                } else {
                    throw $e;
                }
            }
            if (!$errors) {
                logIn(['id' => $pdo->lastInsertId(), 'name' => $values['name']]);
                redirect('films.php');
            }
        }
    }

    $title = 'Register';
    ob_start();
    include 'templates/register.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . h($e->getMessage());
}
include 'templates/layout.html.php';
