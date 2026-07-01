<?php
// Archivo: registro.php
require_once 'includes/auth.php';
require_once 'config/conexion.php';

redirigir_si_autenticado();

$errores = [];
$exito = "";

// Variables para mantener los datos en el formulario si hay error
$nombre = $usuario = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y recoger datos
    $nombre = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($usuario) || empty($email) || empty($password) || empty($confirm_password)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato de correo electrónico no es válido.";
    }

    if (strlen($password) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }

    if ($password !== $confirm_password) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Si no hay errores básicos, verificamos en la base de datos
    if (empty($errores)) {
        try {
            // Comprobar si el usuario o email ya existen
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = :usuario OR email = :email LIMIT 1");
            $stmt->execute([':usuario' => $usuario, ':email' => $email]);
            
            if ($stmt->rowCount() > 0) {
                $errores[] = "El nombre de usuario o correo electrónico ya está registrado.";
            } else {
                // Generar hash seguro de la contraseña
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insertar nuevo usuario
                $stmt_insert = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, email, password) VALUES (:nombre, :usuario, :email, :password)");
                $stmt_insert->execute([
                    ':nombre' => htmlspecialchars($nombre),
                    ':usuario' => htmlspecialchars($usuario),
                    ':email' => filter_var($email, FILTER_SANITIZE_EMAIL),
                    ':password' => $password_hash
                ]);

                $exito = "Cuenta creada exitosamente. Redirigiendo al login...";
                
                // Limpiar variables tras éxito
                $nombre = $usuario = $email = "";
                
                // Redirigir después de 2 segundos (simulado vía header)
                header("refresh:2;url=login.php");
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $errores[] = "Ocurrió un error en el servidor. Intente más tarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Crear una Cuenta</h2>
                <p>Regístrate para continuar</p>
            </div>

            <?php if (!empty($errores)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($exito)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($exito); ?>
                </div>
            <?php endif; ?>

            <form action="registro.php" method="POST" id="registroForm">
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrarse</button>
            </form>
            
            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>