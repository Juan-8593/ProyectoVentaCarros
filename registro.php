<?php
session_start();
include('conexion.php');

$mensaje = "";
$tipo = "";
$redirigir = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $tipoUsuario = $_POST['tipo_usuario'];
    $clave_admin = $_POST['clave_admin'] ?? '';

    // Validar si se seleccionó "Administrador"
    if ($tipoUsuario === "Administrador" && $clave_admin !== "12345") {
        $mensaje = "❌ Clave de administrador incorrecta. Intenta nuevamente.";
        $tipo = "error";
        $redirigir = "registro.php";
    } else {
        $sql = "INSERT INTO Usuarios (Usuario, Nombre, password, TipoUsuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $usuario, $nombre, $password, $tipoUsuario);

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
</head>
<body>

<!-- Video de fondo -->
<video autoplay muted loop class="background-video">
    <source src="imagen/RegistroFondo.mp4" type="video/mp4">
</video>

<section class="login-container">
    <h2>Crear Cuenta</h2>
    <form method="post" class="login-form">

        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" required>
        </div>

        <div class="form-group">
            <label for="nombre">Nombre completo:</label>
            <input type="text" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select name="tipo_usuario" id="tipo_usuario" onchange="mostrarClaveAdmin()" required>
                <option value="Usuario">Usuario</option>
                <option value="Administrador">Administrador</option>
            </select>
        </div>

        <div class="form-group" id="clave_admin_group" style="display: none;">
            <label for="clave_admin">Clave de Administrador:</label>
            <input type="password" name="clave_admin" id="clave_admin">
        </div>

        <button type="submit" class="login-button">Registrarse</button>

        <div class="registro-link">
            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
        </div>
    </form>
</section>

<script>
function mostrarClaveAdmin() {
    const tipo = document.getElementById('tipo_usuario').value;
    const adminField = document.getElementById('clave_admin_group');
    adminField.style.display = (tipo === 'Administrador') ? 'block' : 'none';
}
</script>

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
