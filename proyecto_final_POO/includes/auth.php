<?php
// Archivo: includes/auth.php

// Iniciar sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario está autenticado.
 * Si no lo está, lo redirige al login.
 */
function requerir_autenticacion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Redirige al usuario al dashboard si ya ha iniciado sesión.
 * Útil para proteger las páginas de login y registro.
 */
function redirigir_si_autenticado() {
    if (isset($_SESSION['usuario_id'])) {
        header("Location: dashboard.php");
        exit;
    }
}
?>