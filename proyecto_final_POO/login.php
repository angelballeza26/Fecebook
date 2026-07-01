<?php
// Archivo: login.php
require_once 'includes/auth.php';
require_once 'config/conexion.php';

redirigir_si_autenticado();

$error_msg = "";
$identificador = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = trim($_POST['identificador'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identificador) || empty($password)) {
        $error_msg = "Por favor, complete todos los campos.";
    } else {
        try {
            // Buscar al usuario por email o por nombre de usuario
            $stmt = $pdo->prepare("SELECT id, nombre, usuario, password FROM usuarios WHERE email = :identificador OR usuario = :identificador LIMIT 1");
            $stmt->execute([':identificador' => $identificador]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el usuario existe y la contraseña es correcta
            if ($user && password_verify($password, $user['password'])) {
                
                // Prevenir Session Fixation
                session_regenerate_id(true);

                // Guardar datos en la sesión
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nombre'] = $user['nombre'];
                $_SESSION['usuario_login'] = $user['usuario'];

                // Redireccionar al área protegida
                header("Location: dashboard.php");
                exit;
            } else {
                // Mensaje genérico por seguridad (no revelar si falló el usuario o la clave)
                $error_msg = "Usuario o contraseña incorrectos.";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error_msg = "Ocurrió un error en el servidor. Intente más tarde.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Iniciar Sesión</h2>
                <p>Bienvenido de nuevo</p>
            </div>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error_msg); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="identificador">Usuario o Correo electrónico</label>
                    <input type="text" id="identificador" name="identificador" value="<?php echo htmlspecialchars($identificador); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
            
            <div class="auth-footer">
                <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>