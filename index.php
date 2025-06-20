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
            <a class="nav-link dropdown-toggle text-white" href="#" id="vehiculosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Vehículos</a>
            <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#Ferrari">Ferrari</a></li>
            <li><a class="dropdown-item" href="#Audi">Audi</a></li>
            <li><a class="dropdown-item" href="Chevrolet">Chevrolet</a></li>
            <li><a class="dropdown-item" href="#BMW">BMW</a></li>
            </ul>
            </li>
            <li class="nav-item"><a href="#agendar-cita" class="nav-link text-white">Agendar Cita</a></li>
            <li class="nav-item"><a href="#contacto" class="nav-link text-white">Contacto</a></li>
            <li class="nav-item"><a href="#Sobre-Nosotros" class="nav-link text-white">Sobre Nosotros</a></li>
        <!-- Login con dropdown -->
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="loginDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-plus-fill me-1"></i>
            <?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Login'; ?></a>
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
            <img src="Imagen/<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
            <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $modelo; ?></h5>
            <p class="card-text"><?php echo $descripcion; ?></p>
            <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
            <button class="btn btn-primary mt-auto" 
            onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">Ver más
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
            <img src="Imagen/<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
            <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $modelo; ?></h5>
            <p class="card-text"><?php echo $descripcion; ?></p>
            <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
            <button class="btn btn-primary mt-auto" 
            onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">Ver más
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
            <img src="Imagen/<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
            <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $modelo; ?></h5>
            <p class="card-text"><?php echo $descripcion; ?></p>
            <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
            <button class="btn btn-primary mt-auto" 
            onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">Ver más
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
            <img src="Imagen/<?php echo $imagen; ?>" class="card-img-top" alt="<?php echo $modelo; ?>">
            <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $modelo; ?></h5>
            <p class="card-text"><?php echo $descripcion; ?></p>
            <p><strong>Inventario:</strong> <span id="inventarioCard-<?php echo $id; ?>"><?php echo $inventario; ?></span></p>
            <button class="btn btn-primary mt-auto" 
            onclick="verMas('<?php echo addslashes($modelo); ?>', '<?php echo $imagen; ?>', '<?php echo addslashes($descripcion); ?>', '<?php echo $precio; ?>', '<?php echo $inventario; ?>', '<?php echo $id; ?>')">Ver más
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

    <!-- Formulario Emergente -->
            <div id="formularioCita" style="display: none; max-width: 300px; margin: auto; text-align: center; font-family: Arial, sans-serif;">
            <form id="formularioCita1" action="procesar_cita.php" method="post">
            <h2 style="margin-bottom: 20px;">Agendar Cita</h2>
            <select name="tipoCita" id="tipoCita1" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
            <option value="">Tipo de cita</option>
            <option value="compra">Compra</option>
            <option value="servicio">Servicio</option>
            </select>

    <!-- Vehículos -->
            <div id="divTipoCompra1" style="display: none; margin-bottom: 15px;">
            <select name="tipoCompra" id="tipoCompra1" style="width: 100%; padding: 10px;">
            <option value="">Seleccione vehículo</option>
            <!-- Opciones desde JS + SQL -->
            </select>
            </div>

    <!-- Servicios -->
        <div id="divTipoServicio1" style="display: none; margin-bottom: 15px;">
        <select name="tipoServicio" id="tipoServicio1" style="width: 100%; padding: 10px;">
        <option value="">Seleccione Servicio</option>
        <!-- Opciones desde JS -->
        </select>
        </div>
        <input type="text" name="nombre" placeholder="Nombre" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
        <input type="email" name="correo" placeholder="Correo" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
        <input type="date" name="fecha" required style="width: 100%; padding: 10px; margin-bottom: 15px;">
        <input type="time" name="hora" required style="width: 100%; padding: 10px; margin-bottom: 20px;">
        <button type="submit" style="padding: 10px 20px; background-color: #7a5cf0; color: white;">Agendar</button>
        </form>
        </div>

    <!-- Sección Principal de Citas -->
        <section id="agendar-cita" class="cita-section">
        <div class="cita-container">
        <div class="formulario-cita">
        <h2>Agendar Cita</h2>
        <form id="formularioCita2" action="procesar_cita.php" method="post">
        <div class="mb-3">
        <select name="tipoCita" id="tipoCita2" required>
        <option value="">Tipo de cita</option>
        <option value="compra">Compra</option>
        <option value="servicio">Servicio</option>
        </select>
        </div>
               
    <!-- Vehículos -->
        <div class="mb-3" id="divTipoCompra2" style="display: none;">
        <select name="tipoCompra" id="tipoCompra2">
        <option value="">Seleccione vehículo</option>
        <!-- Opciones desde JS + SQL -->
        </select>
        </div>

        <!-- Servicios -->
        <div class="mb-3" id="divTipoServicio2" style="display: none;">
        <select name="tipoServicio" id="tipoServicio2">
        <option value="">Seleccione Servicio</option>
        <!-- Opciones desde JS -->
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
        <script>
        function actualizarCampos(formId) {
        const form = document.getElementById(formId);
        const tipoCita = form.querySelector('select[name="tipoCita"]');
        const divCompra = form.querySelector('#divTipoCompra' + formId.slice(-1));
        const divServicio = form.querySelector('#divTipoServicio' + formId.slice(-1));
        const selectCompra = divCompra.querySelector('select[name="tipoCompra"]');
        const selectServicio = divServicio.querySelector('select[name="tipoServicio"]');

        tipoCita.addEventListener('change', () => {
        if (tipoCita.value === 'compra') {
            divCompra.style.display = 'block';
            divServicio.style.display = 'none';
            selectCompra.required = true;
            selectServicio.required = false;
        } else if (tipoCita.value === 'servicio') {
            divCompra.style.display = 'none';
            divServicio.style.display = 'block';
            selectCompra.required = false;
            selectServicio.required = true;
        } else {
            divCompra.style.display = 'none';
            divServicio.style.display = 'none';
            selectCompra.required = false;
            selectServicio.required = false;
        }
        });

    // Dispara el evento al cargar para ajustar campos según valor inicial
    tipoCita.dispatchEvent(new Event('change'));
    }
    actualizarCampos('formularioCita1');
    actualizarCampos('formularioCita2');
    </script>
    <script>
    function actualizarCampos(formId) {
    const form = document.getElementById(formId);
    const tipoCita = form.querySelector('select[name="tipoCita"]');
    const divCompra = form.querySelector('#divTipoCompra' + formId.slice(-1));
    const divServicio = form.querySelector('#divTipoServicio' + formId.slice(-1));
    const selectCompra = divCompra.querySelector('select[name="tipoCompra"]');
    const selectServicio = divServicio.querySelector('select[name="tipoServicio"]');

    tipoCita.addEventListener('change', () => {
      if (tipoCita.value === 'compra') {
        divCompra.style.display = 'block';
        divServicio.style.display = 'none';
        selectCompra.required = true;
        selectServicio.required = false;
      } else if (tipoCita.value === 'servicio') {
        divCompra.style.display = 'none';
        divServicio.style.display = 'block';
        selectCompra.required = false;
        selectServicio.required = true;
      } else {
        divCompra.style.display = 'none';
        divServicio.style.display = 'none';
        selectCompra.required = false;
        selectServicio.required = false;
      }
    });

    // Dispara el evento al cargar para ajustar campos según valor inicial
    tipoCita.dispatchEvent(new Event('change'));
    }
    actualizarCampos('formularioCita1');
    actualizarCampos('formularioCita2');
    </script>
    </section>  

    <!-- Sobre Nosotros -->
        <div id="Sobre-Nosotros" class="info-card empresa">
        <h2>Sobre JJLCARS</h2>
        <img src="../Imagen/LogoJJLCAR.jpeg" alt="Imagen de la empresa" class="empresa-img">
        <div class="empresa-contenido">
        <h3>Nuestra Historia</h3>
        <p>JJLCARS nació en 2025 con la visión de revolucionar el mercado automotriz digital. Lo que comenzó como un pequeño catálogo de autos en línea, se transformó rápidamente en una de las plataformas más confiables para encontrar vehículos nuevos y seminuevos con transparencia, calidad y confianza.</p>
        <h3>Misión</h3>
        <p>Ofrecer a nuestros clientes la mejor experiencia al momento de buscar, comparar y adquirir vehículos, proporcionando atención personalizada, confianza y un catálogo actualizado con las mejores opciones del mercado.</p>
        <h3>Visión</h3>
        <p>Ser la plataforma líder en soluciones automotrices digitales en Guatemala, innovando constantemente para conectar a las personas con el vehículo de sus sueños.</p>
        </div>
        </div>

    <!-- Pie de página -->
    <footer id="contacto" class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 JJLCARS. Todos los derechos reservados.</p>
    <div>
    <a href="https://i.pinimg.com/736x/70/89/c7/7089c71e03059151b11a462eb2a24d00.jpg" class="text-white me-3"><i class="bi bi-facebook"></i></a>
    <a href="https://www.instagram.com/jjl.cars_?igsh=MXZqM3VtZW11NTg1Yw==" class="text-white me-3"><i class="bi bi-instagram"></i></a>
            <a href="https://www.whatsapp.com/" class="text-white"><i class="bi bi-whatsapp"></i></a>
            </div>
            </footer>
        <!-- Scripts -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="js/main.js"></script>
</body>
</html>