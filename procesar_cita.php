<?php
session_start();

header('Content-Type: application/json');

// Validar que el usuario esté logueado
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

// Obtener datos del formulario
$tipoCita = $_POST['tipoCita'] ?? '';
$tipoCompra = $_POST['tipoCompra'] ?? null; // Solo aplica si tipoCita = compra
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

$precio = null;
if ($tipoCita === 'compra') {
    if (!$tipoCompra) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Debe seleccionar un vehículo.'
        ]);
        exit;
    }

    $stmtVehiculo = $conexion->prepare("SELECT precio FROM vehiculos WHERE modelo = ?");
    if (!$stmtVehiculo) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error en la consulta SQL.'
        ]);
        exit;
    }
    $stmtVehiculo->bind_param("s", $tipoCompra);
    $stmtVehiculo->execute();
    $stmtVehiculo->bind_result($precio);
    if (!$stmtVehiculo->fetch()) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Modelo no encontrado.'
        ]);
        $stmtVehiculo->close();
        exit;
    }
    $stmtVehiculo->close();
}

// Preparar inserción
$sql = "INSERT INTO citas (tipoCita, tipoCompra, precio, nombre, correo, fecha, hora, fecha_registro, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 'pendiente')";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la preparación de la consulta.'
    ]);
    exit;
}

if ($tipoCita === 'compra') {
    $stmt->bind_param("ssdssss", $tipoCita, $tipoCompra, $precio, $nombre, $correo, $fecha, $hora);
} else {
    $null = null;
    $precio = 0.0;
    $stmt->bind_param("ssdssss", $tipoCita, $null, $precio, $nombre, $correo, $fecha, $hora);
}

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
