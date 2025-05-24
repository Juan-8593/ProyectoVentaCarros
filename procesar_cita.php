<?php
$conexion = new mysqli("localhost", "root", "", "jjlcars");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Obtener datos del formulario
$tipoCita = $_POST['tipoCita'] ?? '';
$tipoCompra = $_POST['tipoCompra'] ?? null; // solo aplica si tipoCita = compra
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';

// Validaciones básicas
if (!$tipoCita || !$nombre || !$correo || !$fecha || !$hora) {
    die("Faltan datos obligatorios.");
}

// Si es compra, se requiere tipoCompra y se busca precio
$precio = null;
if ($tipoCita === 'compra') {
    if (!$tipoCompra) {
        die("Debe seleccionar un vehículo.");
    }

    $stmtVehiculo = $conexion->prepare("SELECT precio FROM vehiculos WHERE modelo = ?");
    $stmtVehiculo->bind_param("s", $tipoCompra);
    $stmtVehiculo->execute();
    $stmtVehiculo->bind_result($precio);
    if (!$stmtVehiculo->fetch()) {
        die("Modelo no encontrado.");
    }
    $stmtVehiculo->close();
}

// Preparar la consulta de inserción
$sql = "INSERT INTO citas (tipoCita, tipoCompra, precio, nombre, correo, fecha, hora, fecha_registro, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'pendiente')";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssdssss", $tipoCita, $tipoCompra, $precio, $nombre, $correo, $fecha, $hora);

if ($stmt->execute()) {
    echo "Cita agendada exitosamente.";
} else {
    echo "Error al agendar la cita: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
