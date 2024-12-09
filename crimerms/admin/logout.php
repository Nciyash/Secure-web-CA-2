<?php
session_start();

// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_unset();
session_destroy();

// Ensure the session cookie is deleted
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy all other cookies
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time() - 42000, '/');
    }
}

// Redirect to signin page
header('Location: signin.php');
exit;
?>