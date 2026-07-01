<?php
// Archivo: dashboard.php
require_once 'includes/auth.php';

// Proteger esta página: si no hay sesión, expulsa al login
requerir_autenticacion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-card">
            <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></h1>
            <p>Has iniciado sesión correctamente.</p>
            <div class="dashboard-actions">
                <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
            </div>
        </div>
    </div>
</body>
</html>