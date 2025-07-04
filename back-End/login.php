<?php
// login.php
require 'conexion.php';

// Obtener el input JSON y decodificarlo en un array asociativo
$data = json_decode(file_get_contents("php://input"), true);

// Validar que los datos sean un array
if (!is_array($data)) {
    echo json_encode(["error" => "Datos de entrada inválidos"]);
    exit;
}

// Verificar si los campos requeridos están presentes y no vacíos
if (
    !isset($data['usuario']) || !isset($data['contrasena']) ||
    empty(trim($data['usuario'])) || empty(trim($data['contrasena']))
) {
    echo json_encode(["error" => "Faltan datos requeridos"]);
    exit;
}

// Sanitizar entradas
$usuario = filter_var(trim($data['usuario']), FILTER_SANITIZE_STRING);
$contrasena = trim($data['contrasena']); // No sanitizar contraseña, solo trim

// Validar longitud mínima
if (strlen($usuario) < 3 || strlen($contrasena) < 6) {
    echo json_encode(["error" => "Usuario o contraseña demasiado cortos"]);
    exit;
}

try {
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contrasena, $user['contrasena'])) {
        echo json_encode(["mensaje" => "Autenticación satisfactoria"]);
    } else {
        echo json_encode(["error" => "Error en la autenticación"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error: " . $e->getMessage()]);
}
?>
