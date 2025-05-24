<?php
$conexion = new mysqli("localhost", "root", "", "jjlcars");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conexion->connect_error]));
}

$sql = "SELECT modelo, precio FROM vehiculos";
$resultado = $conexion->query($sql);

$vehiculos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $vehiculos[] = $fila;
    }
}

header('Content-Type: application/json');
echo json_encode($vehiculos);

$conexion->close();
?>
