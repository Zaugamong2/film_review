<?php
require 'includes/functions.php';
// POST only, so a link or image on another site cannot log a visitor out.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    logOut();
}
redirect('index.php');
