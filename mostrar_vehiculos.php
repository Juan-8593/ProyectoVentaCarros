<?php
// Configuración de conexión
$conn = new mysqli("localhost", "root", "", "jjlcars");

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Marca a filtrar (puedes hacerla dinámica con GET si quieres)
$marca = 'Ferrari';

// Preparar consulta
$sql = "SELECT id, marca, modelo, descripcion, precio, imagen, fecha_agregado, inventario FROM vehiculos WHERE marca = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la consulta SQL"]);
    exit();
}

$stmt->bind_param("s", $marca);
$stmt->execute();
$resultado = $stmt->get_result();

$vehiculos = [];
while ($row = $resultado->fetch_assoc()) {
    $vehiculos[] = $row;
}

// Establecer header JSON y enviar respuesta
header('Content-Type: application/json; charset=utf-8');
echo json_encode($vehiculos, JSON_UNESCAPED_UNICODE);

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
