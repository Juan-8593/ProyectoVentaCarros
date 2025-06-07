<?php
session_start();
include('../conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo_ingresado = $_POST['codigo'];
    $nueva = $_POST['nueva'];
    $repetir = $_POST['repetir'];

    if ($codigo_ingresado == $_SESSION['codigo_recuperacion']) {
        if ($nueva === $repetir) {
            $correo = $_SESSION['correo_recuperacion'];

            $sql = "UPDATE Clientes SET Password = ? WHERE correo = ?";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Error en la consulta: " . $conn->error);
            }

            $stmt->bind_param("ss", $nueva, $correo);
            $stmt->execute();

            unset($_SESSION['codigo_recuperacion']);
            unset($_SESSION['correo_recuperacion']);

            echo "<script>alert('Contraseña actualizada correctamente'); window.location.href = '../login.php';</script>";
            exit();
        } else {
            $error = "⚠️ Las contraseñas no coinciden.";
        }
    } else {
        $error = "⚠️ Código incorrecto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Código</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../imagen/Verificacion.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
        }
    </style>
</head>
<body>

<div class="background-image"></div>

<section class="login-container">
    <div class="login-box">
        <h2>Verificación</h2>
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="codigo">Código enviado a tu correo:</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <div class="form-group">
                <label for="nueva">Nueva contraseña:</label>
                <input type="password" id="nueva" name="nueva" required>
            </div>
            <div class="form-group">
                <label for="repetir">Repetir contraseña:</label>
                <input type="password" id="repetir" name="repetir" required>
            </div>
            <button type="submit" class="login-button">Cambiar contraseña</button>
            <?php if (isset($error)): ?>
                <p class="error-msg"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </div>
</section>

</body>
</html>
