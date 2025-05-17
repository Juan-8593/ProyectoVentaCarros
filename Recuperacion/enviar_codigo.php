<?php
session_start();
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['correo'])) {
    $correo = $_POST['correo'];

    // Verificar si el correo existe en la base de datos
    $sql = "SELECT * FROM Usuarios WHERE Correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generar código de verificación
        $codigo = rand(100000, 999999);
        $_SESSION['codigo_recuperacion'] = $codigo;
        $_SESSION['correo_recuperacion'] = $correo;

        // Enviar correo (usa tu configuración real de mail aquí)
        $asunto = "Código de recuperación de cuenta";
        $mensaje = "Tu código de recuperación es: $codigo";
        $headers = "From: no-responder@carevolution.com";

        mail($correo, $asunto, $mensaje, $headers);

        header("Location: verificar_codigo.php");
        exit();
    } else {
        echo "⚠️ El correo no está registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>
