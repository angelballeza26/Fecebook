<?php
// Archivo: index.php
require_once 'includes/auth.php';

// El index simplemente redirige dependiendo del estado de la sesión
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit;
?>