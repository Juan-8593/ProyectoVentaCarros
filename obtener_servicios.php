<?php
$conexion = new mysqli("localhost", "root", "", "jjlcars");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conexion->connect_error]));
}

$sql = "SELECT tipoServicio, precio, id FROM servicios";
$resultado = $conexion->query($sql);

$servicios = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $servicios[] = $fila;
    }
}

header('Content-Type: application/json');
echo json_encode($servicios);

$conexion->close();
?>