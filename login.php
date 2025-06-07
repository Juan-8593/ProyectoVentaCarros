<?php
session_start();
include('conexion.php');

$correo = isset($_POST['correo']) ? $_POST['correo'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($correo) && !empty($password)) {

    // 1. Buscar usuario por correo
    $sql = "SELECT * FROM Clientes WHERE Correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user_data = $result->fetch_assoc();

        // 2. Verificar contraseña con password_verify
if (password_verify($password, $user_data['Password'])) {
    $_SESSION['Correo'] = $user_data['Correo'];
    $_SESSION['usuario'] = $user_data['Usuario'];
    $_SESSION['nombre'] = isset($user_data['nombre']) ? $user_data['nombre'] : ''; // <-- Aquí
    $_SESSION['login_success'] = true;
}

    else {
            // Contraseña incorrecta
            $error_msg = "⚠️ Usuario o contraseña incorrectos";
        }
    } else {
        // Usuario no encontrado
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .password-wrapper {
            position: relative;
        }
        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }
        .toggle-password:hover {
            color: #000;
        }
    </style>
</head>
<body>

<section class="login-container">
    <div class="login-box">
        <h2>Iniciar Sesión</h2>
        <form method="post" class="login-form">
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="text" id="correo" name="correo" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="login-button">Entrar</button>
            </div>

            <?php if (isset($error_msg)): ?>
                <p class="error-msg"><?php echo $error_msg; ?></p>
            <?php endif; ?>
        </form>

        <p class="registro-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        <p class="registro-link"><a href="Recuperacion/recuperar.php">¿Olvidaste tu contraseña?</a></p>
    </div>
</section>

<video autoplay muted loop class="background-video">
    <source src="imagen/FondoLogin.mp4" type="video/mp4">
</video>

<script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        togglePassword.classList.toggle("fa-eye");
        togglePassword.classList.toggle("fa-eye-slash");
    });
</script>

<?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']): ?>
<script>
    Swal.fire({
        title: 'Inicio de sesión correcto!',
        icon: 'success',
        text: '¡Bienvenido, <?php echo $_SESSION['usuario']; ?>!',
        confirmButtonText: 'OK'
    }).then(function() {
        window.location.href = 'index.php';
    });
</script>
<?php unset($_SESSION['login_success']); ?>
<?php endif; ?>

</body>
</html>