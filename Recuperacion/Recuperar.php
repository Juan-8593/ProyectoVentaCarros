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
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('../imagen/LoginRecuperacion.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.8s ease-in-out;
        }

        .login-box h2 {
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #555;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .login-button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }
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
                    <input type="email" id="correo" name="correo" required placeholder="ejemplo@correo.com">
                </div>
                <button type="submit" class="login-button">Enviar código</button>
            </form>
        </div>
    </section>

</body>
</html>
