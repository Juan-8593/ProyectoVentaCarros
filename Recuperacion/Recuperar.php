<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Cuenta</title>
    <link rel="stylesheet" href="css/login.css">
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

<video autoplay muted loop class="background-video">
    <source src="imagen/LoginRecuperacion.jpg" type="video/mp4">
</video>

</body>
</html>
