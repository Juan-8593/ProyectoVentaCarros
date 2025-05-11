<?php
$conexion = new mysqli("localhost", "root", "", "jjlcars");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';

$sql = "INSERT INTO citas (nombre, correo, fecha, hora) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssss", $nombre, $correo, $fecha, $hora);

if ($stmt->execute()) {
    echo "Cita agendada exitosamente.";
} else {
    echo "Error al agendar la cita: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
