<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JJLCARS - Concesionario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styleindex.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Encabezado -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="#"><i class="bi bi-car-front me-2"></i>JJLCARS</a>

        <!-- Botón hamburguesa -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido" aria-controls="navbarContenido" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido del menú -->
        <div class="collapse navbar-collapse" id="navbarContenido">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item"><a href="#inicio" class="nav-link text-white">Inicio</a></li>

                <!-- Dropdown Vehículos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="vehiculosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Vehículos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#ferrari">Ferrari</a></li>
                        <li><a class="dropdown-item" href="#audi">Audi</a></li>
                        <li><a class="dropdown-item" href="#chevrolet">Chevrolet</a></li>
                        <li><a class="dropdown-item" href="#bmw">BMW</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a href="#agendar-cita" class="nav-link text-white">Agendar Cita</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="mostrarFormularioContacto()">Contacto</a></li>

<!-- Login con dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-plus-fill me-1"></i>
        <?php echo isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Login'; ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
        <?php if (isset($_SESSION['usuario'])): ?>
            <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
        <?php else: ?>
            <li><a class="dropdown-item" href="login.php">Iniciar Sesión</a></li>
            <li><a class="dropdown-item" href="registro.php">Registrar</a></li>
        <?php endif; ?>
    </ul>
</li>

<!-- Carrito -->
<li class="nav-item">
<a href="#" class="nav-link text-white fs-5"><i class="bi bi-cart4"></i></a>
</li>
</ul>
</div>
</div>
</nav>

<!-- Carrusel -->
<section id="inicio" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="Imagen/BMW X4.jpg" class="carousel-img" alt="BMW X4">
      <div class="custom-caption">¡Sueña en grande sobre ruedas!</div>
    </div>
    <div class="carousel-item">
      <img src="Imagen/Audi A3.JPG" class="carousel-img" alt="Audi A3">
      <div class="custom-caption">Ahorra con estilo y potencia</div>
    </div>
    <div class="carousel-item">
      <img src="Imagen/Audi RS3.JPG" class="carousel-img" alt="Audi RS3">
      <div class="custom-caption">Agenda tu prueba, lúcete mañana</div>
    </div>
  </div>

  

    <!-- Botones de control del carrusel (flechas personalizadas en lugar de puntos) -->
    <button class="carousel-control-prev" type="button" data-bs-target="#inicio" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#inicio" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
    </button>
</section>

<!-- Sección Ferrari -->
<section id="Ferrari" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Ferrari</h2>
        <div class="row" id="contenedorFerrari">
            <?php
            $conn = new mysqli("localhost", "root", "", "jjlcars");
            if ($conn->connect_error) {
                echo "<p class='text-danger'>Error de conexión a la base de datos.</p>";
            } else {
                $sql = "SELECT * FROM vehiculos WHERE marca = 'Ferrari'";
                $resultado = $conn->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($vehiculo = $resultado->fetch_assoc()) {
                        $id = htmlspecialchars($vehiculo['id']);
                        $modelo = htmlspecialchars($vehiculo['modelo']);
                        $descripcion = htmlspecialchars($vehiculo['descripcion']);
                        $precio = htmlspecialchars($vehiculo['precio']);
                        $imagen = htmlspecialchars($vehiculo['imagen']);
                        $inventario = htmlspecialchars($vehiculo['inventario']);
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $modelo; ?></h5>
                                    <p class="card-text"><?php echo $descripcion; ?></p>
                                    <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
                                    <button class="btn btn-primary mt-auto" 
                                        onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">
                                        Ver más
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No hay vehículos Ferrari disponibles.</p>";
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</section>



<!-- Sección Audi -->
    <section id="Audi" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Audi</h2>
        <div class="row" id="contenedorAudi">
            <?php
            $conn = new mysqli("localhost", "root", "", "jjlcars");
            if ($conn->connect_error) {
                echo "<p class='text-danger'>Error de conexión a la base de datos.</p>";
            } else {
                $sql = "SELECT * FROM vehiculos WHERE marca = 'Audi'";
                $resultado = $conn->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($vehiculo = $resultado->fetch_assoc()) {
                        $id = htmlspecialchars($vehiculo['id']);
                        $modelo = htmlspecialchars($vehiculo['modelo']);
                        $descripcion = htmlspecialchars($vehiculo['descripcion']);
                        $precio = htmlspecialchars($vehiculo['precio']);
                        $imagen = htmlspecialchars($vehiculo['imagen']);
                        $inventario = htmlspecialchars($vehiculo['inventario']);
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $modelo; ?></h5>
                                    <p class="card-text"><?php echo $descripcion; ?></p>
                                    <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
                                    <button class="btn btn-primary mt-auto" 
                                        onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">
                                        Ver más
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No hay vehículos Audi disponibles.</p>";
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</section>


    <!-- Sección Chevrolet -->
    <section id="Chevrolet" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Chevrolet</h2>
        <div class="row" id="contenedorChevrolet">
            <?php
            $conn = new mysqli("localhost", "root", "", "jjlcars");
            if ($conn->connect_error) {
                echo "<p class='text-danger'>Error de conexión a la base de datos.</p>";
            } else {
                $sql = "SELECT * FROM vehiculos WHERE marca = 'Chevrolet'";
                $resultado = $conn->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($vehiculo = $resultado->fetch_assoc()) {
                        $id = htmlspecialchars($vehiculo['id']);
                        $modelo = htmlspecialchars($vehiculo['modelo']);
                        $descripcion = htmlspecialchars($vehiculo['descripcion']);
                        $precio = htmlspecialchars($vehiculo['precio']);
                        $imagen = htmlspecialchars($vehiculo['imagen']);
                        $inventario = htmlspecialchars($vehiculo['inventario']);
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $modelo; ?></h5>
                                    <p class="card-text"><?php echo $descripcion; ?></p>
                                    <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
                                    <button class="btn btn-primary mt-auto" 
                                        onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">
                                        Ver más
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No hay vehículos Chevrolet disponibles.</p>";
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</section>


    <!-- Sección BMW -->
    <section id="BMW" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">BMW</h2>
        <div class="row" id="contenedorBMW">
            <?php
            $conn = new mysqli("localhost", "root", "", "jjlcars");
            if ($conn->connect_error) {
                echo "<p class='text-danger'>Error de conexión a la base de datos.</p>";
            } else {
                $sql = "SELECT * FROM vehiculos WHERE marca = 'BMW'";
                $resultado = $conn->query($sql);

                if ($resultado && $resultado->num_rows > 0) {
                    while ($vehiculo = $resultado->fetch_assoc()) {
                        $id = htmlspecialchars($vehiculo['id']);
                        $modelo = htmlspecialchars($vehiculo['modelo']);
                        $descripcion = htmlspecialchars($vehiculo['descripcion']);
                        $precio = htmlspecialchars($vehiculo['precio']);
                        $imagen = htmlspecialchars($vehiculo['imagen']);
                        $inventario = htmlspecialchars($vehiculo['inventario']);
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo $modelo; ?></h5>
                                    <p class="card-text"><?php echo $descripcion; ?></p>
                                    <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
                                    <button class="btn btn-primary mt-auto" 
                                        onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">
                                        Ver más
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='text-center'>No hay vehículos BMW disponibles.</p>";
                }
                $conn->close();
            }
            ?>
        </div>
    </div>
</section>


    <!-- Sección FormsCita -->
    <div id="formularioCita" style="display: none; max-width: 300px; margin: auto; text-align: center; font-family: Arial, sans-serif;">
    <form action="procesar_cita.php" method="post">
        <h2 style="margin-bottom: 20px;">Agendar Cita</h2>

        <select name="tipoCita" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">
            <option value="">Tipo de cita</option>
            <option value="compra">Compra</option>
            <option value="mantenimiento">Mantenimiento</option>
        </select>

        <input type="text" name="nombre" placeholder="Nombre" required
               style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

        <input type="email" name="correo" placeholder="Correo" required
               style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

        <input type="date" name="fecha" required
               style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">

        <input type="time" name="hora" required
               style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px;">

        <button type="submit"
                style="padding: 10px 20px; background-color: #7a5cf0; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Agendar
        </button>
    </form>
</div>
                  

<section id="agendar-cita" class="cita-section">
    <div class="cita-container">
        <div class="formulario-cita">
            <h2>Agendar Cita</h2>
            <form action="procesar_cita.php" method="post">
                <div class="mb-3">
                    <select name="tipoCita" required>
                        <option value="">Tipo de cita</option>
                        <option value="compra">Compra</option>
                        <option value="mantenimiento">Mantenimiento</option>
                    </select>
                </div>

                <div class="mb-3">
                    <input type="text" name="nombre" placeholder="Nombre" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="correo" placeholder="Correo" required>
                </div>

                <div class="mb-3">
                    <input type="date" name="fecha" required>
                </div>

                <div class="mb-4">
                    <input type="time" name="hora" required>
                </div>

                <div class="text-left">
                    <button type="submit">Agendar</button>
                </div>
            </form>
        </div>
        <div class="imagen-cita">
            <img src="Imagen/LogoJJLCAR.jpeg" alt="Imagen de cita">
        </div>
    </div>
</section>


<!-- Pie de página -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 JJLCARS. Todos los derechos reservados.</p>
        <div>
            <a href="https://i.pinimg.com/736x/70/89/c7/7089c71e03059151b11a462eb2a24d00.jpg" class="text-white me-3"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/" class="text-white me-3"><i class="bi bi-instagram"></i></a>
            <a href="https://www.whatsapp.com/" class="text-white"><i class="bi bi-whatsapp"></i></a>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

    
</body>
</html>
