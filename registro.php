<?php
session_start();
include('conexion.php');

$mensaje = "";
$tipo = "";
$redirigir = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['usuario']; // Campo visualizado como 'Correo'
    $usuario = $_POST['nombre']; // Campo visualizado como 'Id Usuario'
    $nombreCompleto = $_POST['nombre_completo'];
    $password = $_POST['password'];
    $tipoCliente = "Cliente";

    // Encriptar la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Validar si el correo ya existe
    $verificar_sql = "SELECT * FROM Clientes WHERE Correo = ?";
    $stmt_verificar = $conn->prepare($verificar_sql);
    $stmt_verificar->bind_param("s", $correo);
    $stmt_verificar->execute();
    $resultado = $stmt_verificar->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "❌ El correo ya está registrado. Intenta con otro.";
        $tipo = "error";
        $redirigir = "registro.php";
    } else {
        $sql = "INSERT INTO Clientes (Correo, Usuario, Nombre, Password, tipoCliente) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $correo, $usuario, $nombreCompleto, $passwordHash, $tipoCliente);

        if ($stmt->execute()) {
            $mensaje = "¡Registro exitoso! Ahora puedes iniciar sesión.";
            $tipo = "success";
            $redirigir = "login.php";
        } else {
            $mensaje = "Error al registrar: " . $stmt->error;
            $tipo = "error";
            $redirigir = "registro.php";
        }

        $stmt->close();
    }

    $stmt_verificar->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrarse</title>
    <link rel="stylesheet" href="css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .login-form input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .login-container {
            width: 400px;
        }
    </style>
</head>
<body>

<video autoplay muted loop class="background-video">
    <source src="imagen/RegistroFondo.mp4" type="video/mp4">
</video>

<section class="login-container">
    <h2>Crear Cuenta</h2>
    <form method="post" class="login-form">
        <div class="form-group">
            <label for="usuario">Correo:</label>
            <input type="text" name="usuario" required>
        </div>

        <div class="form-group">
            <label for="nombre">Id Usuario:</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="nombre_completo">Nombre Completo:</label>
            <input type="text" name="nombre_completo" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="login-button">Registrarse</button>

        <div class="registro-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </div>
    </form>
</section>

<?php if (!empty($mensaje)): ?>
<script>
    Swal.fire({
        title: '<?php echo ($tipo === "success") ? "Éxito" : "Error"; ?>',
        text: '<?php echo $mensaje; ?>',
        icon: '<?php echo $tipo; ?>',
        confirmButtonText: 'Aceptar'
    }).then(() => {
        window.location.href = '<?php echo $redirigir; ?>';
    });
</script>
<?php endif; ?>

</body>
</html>
