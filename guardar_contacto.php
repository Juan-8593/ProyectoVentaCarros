<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto si usas otro usuario
$password = "";     // Cambia si tienes contraseña
$dbname = "2doParcial";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$Nombre = $_POST['nombre'];
$Telefono = $_POST['telefono'];
$Email = $_POST['email'];
$Asunto = $_POST['asunto'];
$Mensaje = $_POST['mensaje'];

// Insertar en la base de datos
$sql = "INSERT INTO Contactos (Nombre, Telefono, email, asunto, mensaje) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $Nombre, $Telefono, $Email, $Asunto, $Mensaje);

if ($stmt->execute()) {
    // Redirige a index.html con un parámetro para saber que fue exitoso
    header("Location: index.php?mensaje=enviado");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
