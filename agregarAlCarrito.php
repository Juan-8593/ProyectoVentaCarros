<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['modelo'])) {
    echo json_encode(['success' => false, 'message' => 'Modelo no especificado']);
    exit;
}

$modelo = $data['modelo'];

$conn = new mysqli("localhost", "root", "", "jjlcars");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión']);
    exit;
}

// Verifica que haya inventario disponible
$stmt = $conn->prepare("SELECT inventario FROM vehiculos WHERE modelo = ?");
$stmt->bind_param("s", $modelo);
$stmt->execute();
$result = $stmt->get_result();
$vehiculo = $result->fetch_assoc();

if ($vehiculo && $vehiculo['inventario'] > 0) {
    // Reducir inventario
    $stmt = $conn->prepare("UPDATE vehiculos SET inventario = inventario - 1 WHERE modelo = ?");
    $stmt->bind_param("s", $modelo);
    $stmt->execute();

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Inventario agotado']);
}

$conn->close();
