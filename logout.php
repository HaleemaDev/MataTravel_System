<?php
/**
 * MataTravel System - Logout Handler
 * This script clears all session data and redirects to the login page.
 */

// 1. Initialize the session.
// If you are using session_start() in your other files, you must call it here 
// to access the current session before you can destroy it.
session_start();

// 2. Unset all of the session variables.
$_SESSION = array();

// 3. If it's desired to kill the session, also delete the session cookie.
// This completely resets the browser's session cookie.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finally, destroy the session.
session_destroy();

// 5. Redirect to the login page (or index page)
header("Location: login.php");
exit();
?>