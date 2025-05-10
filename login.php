<?php
session_start();
include('conexion.php');  

// Validación del login al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta para verificar si el usuario y la contraseña son correctos
    $sql = "SELECT * FROM Usuarios WHERE Usuario = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Login exitoso, obtener los datos del usuario
        $user_data = $result->fetch_assoc(); // Obtener los datos del usuario
        $_SESSION['usuario'] = $usuario;
        $_SESSION['nombre'] = $user_data['Nombre']; // Guardar el nombre en la sesión

        // Marcar que el login fue exitoso en la sesión
        $_SESSION['login_success'] = true;

        // Redirigir al mismo archivo (login.php) para ejecutar el alert
        header("Location: login.php");
        exit();  // Finaliza la ejecución del script
    } else {
        // Si la validación falla, mostrar un mensaje de error
        $error_msg = "⚠️ Usuario o contraseña incorrectos";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login.css">
    <!-- Incluir SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <section class="login-container">
        <div class="login-box">
            <h2>Iniciar Sesión</h2>
            <form method="post" class="login-form">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-button">Entrar</button>

                <!-- Mostrar error si existe -->
                <?php if (isset($error_msg)): ?>
                    <p class="error-msg"><?php echo $error_msg; ?></p>
                <?php endif; ?>
            </form>

            <p class="registro-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </div>
    </section>

    <!-- Video de fondo -->
    <video autoplay muted loop class="background-video">
        <source src="imagen/FondoLogin.mp4" type="video/mp4">
    </video>

    <!-- Mostrar el SweetAlert2 si la sesión fue exitosa -->
    <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
        <script>
            // Asegúrate de que el nombre del usuario esté correctamente pasado desde la sesión
            Swal.fire({
                title: 'Inicio de sesión correcto!',
                icon: 'success',
                text: '¡Bienvenido, <?php echo $_SESSION['nombre']; ?>!', // Mostrar el nombre del usuario
                confirmButtonText: 'OK'
            }).then(function() {
                window.location.href = 'index.php';  // Redirigir a index
            });
        </script>
        <?php
        // Borrar la variable de sesión después de mostrar el alert
        unset($_SESSION['login_success']);
        unset($_SESSION['nombre']);  // Limpiar el nombre de la sesión después de usarlo
        ?>
    <?php endif; ?>

</body>
</html>