<?php
// Session handling and access control for the login system.
// Login accounts live in the user table, separate from the reviewer table.
if (session_status() === PHP_SESSION_NONE) {
    // HttpOnly hides the cookie from JavaScript; SameSite=Lax stops it being sent on cross-site POSTs.
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function currentUserName()
{
    return $_SESSION['user_name'] ?? '';
}

/** Start a logged-in session. A new session id on login prevents session fixation. */
function logIn(array $user)
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_name'] = $user['name'];
}

function logOut()
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

/** Send visitors who are not logged in to the login page. */
function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('login.php?needed=1');
    }
}
