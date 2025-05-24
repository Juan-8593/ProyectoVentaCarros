<?php
session_start();
include('../conexion.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Cuenta</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        body {
            background: url('../imagen/LoginRecuperacion.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body>

<section class="login-container">
    <div class="login-box">
        <h2>Recuperar Cuenta</h2>
        <form method="post" action="enviar_codigo.php" class="login-form">
            <div class="form-group">
                <label for="correo">Correo registrado:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <button type="submit" class="login-button">Enviar código</button>
        </form>
    </div>
</section>

</body>
</html>
