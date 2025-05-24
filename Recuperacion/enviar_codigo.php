<?php
session_start();
include('../conexion.php'); // Ajusta la ruta si es necesario

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // Asegúrate que esta ruta sea correcta

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['correo'])) {
    $correo = $_POST['correo'];

    // Verificar si el correo existe en la base de datos
    $sql = "SELECT * FROM Clientes WHERE correo = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generar código de recuperación
        $codigo = rand(100000, 999999);
        $_SESSION['codigo_recuperacion'] = $codigo;
        $_SESSION['correo_recuperacion'] = $correo;

        // Configurar PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'eduardojolon46@gmail.com';  
            $mail->Password   = 'owreqemguhwqigrs'; // Contraseña de aplicación

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente y destinatario
            $mail->setFrom('eduardojolon46@gmail.com', 'JJLCARS');
            $mail->addAddress($correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Codigo de recuperacion de cuenta';
            $mail->Body = "
                <h2>Hola, $correo</h2>
                <p>Has solicitado restablecer la contraseña para tu cuenta en <strong>JJLCARS</strong>.</p>
                <p>Tu código de recuperación es: <strong style='font-size: 1.5em;'>$codigo</strong></p>
                <p>Si no fuiste tú quien solicitó este cambio, te recomendamos cambiar tu contraseña lo antes posible para proteger tu cuenta.</p>
                <br>
                <p>Saludos,<br>Equipo JJLCARS</p>
            ";

            $mail->send();

            // Redirigir al formulario para verificar código
            header("Location: verificar_codigo.php");
            exit();

        } catch (Exception $e) {
            echo "❌ Error al enviar correo: {$mail->ErrorInfo}";
        }

    } else {
        echo "⚠️ El correo no está registrado.";
    }

    $stmt->close();
    $conn->close();
}
?>
