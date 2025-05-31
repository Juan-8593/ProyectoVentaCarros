<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Debes iniciar sesión para agendar una cita.',
        'needLogin' => true
    ]);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "jjlcars");
if ($conexion->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Conexión fallida a la base de datos.'
    ]);
    exit;
}

$tipoCita = $_POST['tipoCita'] ?? '';
$tipoCompra = $_POST['tipoCompra'] ?? null;   // Modelo vehículo
$tipoServicio = $_POST['tipoServicio'] ?? null; // Servicio o mantenimiento
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';

// Validaciones básicas
if (!$tipoCita || !$nombre || !$correo || !$fecha || !$hora) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Faltan datos obligatorios.'
    ]);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Correo electrónico no válido.'
    ]);
    exit;
}

$precio = 0.0;
$tipoSeleccionado = null;
$vehiculo_id = null;

if ($tipoCita === 'compra') {
    if (!$tipoCompra) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Debe seleccionar un vehículo.'
        ]);
        exit;
    }
    $tipoSeleccionado = $tipoCompra;

    $stmtVehiculo = $conexion->prepare("SELECT id, precio FROM vehiculos WHERE modelo = ?");
    if (!$stmtVehiculo) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error en la consulta SQL.'
        ]);
        exit;
    }
    $stmtVehiculo->bind_param("s", $tipoCompra);
    $stmtVehiculo->execute();
    $stmtVehiculo->bind_result($vehiculo_id, $precio);
    if (!$stmtVehiculo->fetch()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Modelo no encontrado.'
        ]);
        $stmtVehiculo->close();
        exit;
    }
    $stmtVehiculo->close();
} else if ($tipoCita === 'servicio' || $tipoCita === 'mantenimiento') {
    if (!$tipoServicio) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Debe seleccionar un servicio.'
        ]);
        exit;
    }
    $tipoSeleccionado = $tipoServicio;

    $stmtServicio = $conexion->prepare("SELECT id, precio FROM servicios WHERE tipoServicio = ?");
    if ($stmtServicio) {
        $stmtServicio->bind_param("s", $tipoServicio);
        $stmtServicio->execute();
        $stmtServicio->bind_result($vehiculo_id, $precio);
        $stmtServicio->fetch();
        $stmtServicio->close();
    } else {
        $vehiculo_id = null;
        $precio = 0.0;
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tipo de cita no válido.'
    ]);
    exit;
}

// Insertar cita con tipo seleccionado (texto) y ID del vehículo o servicio
$sql = "INSERT INTO citas (tipoCita, tipoCompra, vehiculo_id, precio, nombre, correo, fecha, hora, fecha_registro, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'pendiente')";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la preparación de la consulta.'
    ]);
    exit;
}

$stmt->bind_param("sssdssss", $tipoCita, $tipoSeleccionado, $vehiculo_id, $precio, $nombre, $correo, $fecha, $hora);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Cita agendada exitosamente.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al agendar la cita: ' . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();
?>
