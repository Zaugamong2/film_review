<?php
// Lab base file, extended: database renamed, error mode and fetch mode set explicitly.
$pdo = new PDO('mysql:host=localhost;port=3307;dbname=filmreview;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
