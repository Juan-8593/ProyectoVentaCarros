<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

// Aquí usamos directamente $_SESSION['nombre'], que ya tienes del login
echo json_encode(['nombre' => $_SESSION['nombre']]);
