<?php
// Configuración de la conexión
$host = 'localhost';
$db   = 'api_usuarios';
$user = 'root';
$pass = '';
$port = 3307;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $conexion = new PDO($dsn, $user, $pass);
    // Configurar el modo de errores de PDO a excepción
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error al conectar con la base de datos",
        "detalle" => $e->getMessage()
    ]);
    exit;
}
?>
