<?php
// Archivo: logout.php
require_once 'includes/auth.php';

// Vaciar el arreglo de la sesión
$_SESSION = [];

// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión.
session_destroy();

// Redirigir al login
header("Location: login.php");
exit;
?>