<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['usuario'])) {
    echo json_encode(['sesion_activa' => true]);
} else {
    echo json_encode(['sesion_activa' => false]);
}
?>
