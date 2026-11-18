<?php
require 'includes/functions.php';

$errors = [];
$values = ['name' => '', 'email' => '', 'message' => ''];
$sent = (int)($_GET['sent'] ?? 0); // 1 = emailed, 2 = saved only

try {
    if (isset($_POST['message'])) {
        $values = [
            'name'    => trim($_POST['name'] ?? ''),
            'email'   => trim($_POST['email'] ?? ''),
            'message' => trim($_POST['message'] ?? ''),
        ];

        if ($values['name'] === '' || mb_strlen($values['name']) > 100) {
            $errors[] = 'Enter your name (up to 100 characters).';
        }
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($values['email']) > 150) {
            $errors[] = 'Enter a valid email address.';
        }
        if (mb_strlen($values['message']) < 10 || mb_strlen($values['message']) > 2000) {
            $errors[] = 'Write a message of 10 to 2000 characters.';
        }

        if (!$errors) {
            include 'includes/DatabaseConnection.php';

            // Remove line breaks from the name so it cannot inject extra mail headers.
            $safeName = str_replace(["\r", "\n"], ' ', $values['name']);
            $subject = SITE_NAME . ' contact form: ' . $safeName;
            $body = "From: $safeName <{$values['email']}>\r\n\r\n" . $values['message'];
            $headers = 'Reply-To: ' . $values['email'];

            // mail() often fails on a local XAMPP install, so the message is always stored too.
            $emailed = @mail(ADMIN_EMAIL, $subject, $body, $headers) ? 1 : 0;

            $sql = 'INSERT INTO contact_message SET
                name = :name, email = :email, message = :message, sentdate = NOW(), emailed = :emailed';
            query($pdo, $sql, [
                ':name' => $values['name'],
                ':email' => $values['email'],
                ':message' => $values['message'],
                ':emailed' => $emailed,
            ]);
            redirect('contact.php?sent=' . ($emailed ? 1 : 2));
        }
    }

    $title = 'Contact the administrator';
    ob_start();
    include 'templates/contact.html.php';
    $output = ob_get_clean();
} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = 'Database error: ' . h($e->getMessage());
}
include 'templates/layout.html.php';
