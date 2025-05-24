<?php
session_start();
header('Content-Type: application/json');

// Validar sesión
if (!isset($_SESSION['usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Debes iniciar sesión para comprar']);
    exit;
}

// Recibir datos POST
$idVehiculo = $_POST['idVehiculo'] ?? null;
$cantidad = intval($_POST['cantidad'] ?? 0);

if (!$idVehiculo || $cantidad <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "jjlcars");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener inventario actual
$sql = "SELECT inventario FROM vehiculos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idVehiculo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Vehículo no encontrado']);
    exit;
}

$row = $result->fetch_assoc();
$inventarioActual = intval($row['inventario']);

if ($inventarioActual < $cantidad) {
    http_response_code(400);
    echo json_encode(['error' => 'No hay suficiente inventario']);
    exit;
}

// Actualizar inventario
$nuevoInventario = $inventarioActual - $cantidad;
$sqlUpdate = "UPDATE vehiculos SET inventario = ? WHERE id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("ii", $nuevoInventario, $idVehiculo);
$success = $stmtUpdate->execute();

if ($success) {
    echo json_encode(['success' => true, 'nuevoInventario' => $nuevoInventario]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo actualizar el inventario']);
}

$stmt->close();
$stmtUpdate->close();
$conn->close();
exit;
