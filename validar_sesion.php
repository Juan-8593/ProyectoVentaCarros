<?php
session_start();

$response = ['sesion_activa' => false];

if (isset($_SESSION['Correo'])) {
    $response['sesion_activa'] = true;
    $response['nombre'] = $_SESSION['nombre'] ?? '';
    $response['correo'] = $_SESSION['Correo'] ?? '';
}

header('Content-Type: application/json');
echo json_encode($response);
