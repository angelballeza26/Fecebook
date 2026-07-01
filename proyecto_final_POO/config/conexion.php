<?php
// Archivo: config/conexion.php

$host = "127.0.0.1"; // Cambiado de 'localhost' a '127.0.0.1' para forzar el uso del puerto
$port = "3308";     
$dbname = "login_system";
$username = "root"; 
$password = "";     

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // REGISTRO DE DEPURACIÓN TEMPORAL:
    // Si el error persiste, descomenta la siguiente línea para ver el motivo exacto en pantalla:
    // die("Error real: " . $e->getMessage());
    
    error_log($e->getMessage()); 
    die("Error de conexión a la base de datos. Por favor, intente más tarde.");
}
?>