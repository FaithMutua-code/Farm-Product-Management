<?php include 'config.php';

// Unset all session variables
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Delete remember me cookies
setcookie('staff_email', '', time() - 3600, '/');
setcookie('staff_password', '', time() - 3600, '/');

// Redirect to login page
header("Location: login.php");
exit();
?>