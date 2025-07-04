<?php
require 'conexion.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['usuario']) || !isset($data['contrasena'])) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

// Sanitización
$usuario = trim($data['usuario']);
$contrasena_raw = $data['contrasena'];

// Validaciones
if (empty($usuario) || empty($contrasena_raw)) {
    echo json_encode(["error" => "Usuario y contraseña son obligatorios"]);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $usuario)) {
    echo json_encode(["error" => "El usuario debe tener entre 4 y 20 caracteres alfanuméricos o guion bajo"]);
    exit;
}

if (strlen($contrasena_raw) < 6) {
    echo json_encode(["error" => "La contraseña debe tener al menos 6 caracteres"]);
    exit;
}

$contrasena = password_hash($contrasena_raw, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO usuarios (usuario, contrasena) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$usuario, $contrasena]);

    echo json_encode(["mensaje" => "Usuario registrado correctamente"]);
} catch (PDOException $e) {
    // Puedes verificar si el error es por usuario duplicado
    if ($e->getCode() == 23000) {
        echo json_encode(["error" => "El usuario ya existe"]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario: " . $e->getMessage()]);
    }
}
?>
