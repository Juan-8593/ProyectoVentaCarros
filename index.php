<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Evolution - Concesionario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styleindex.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Encabezado -->
<header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0"><i class="bi bi-car-front"></i> Car Evolution</h1>
        <nav class="d-flex align-items-center">
            <ul class="nav me-3">
                <li class="nav-item"><a href="#inicio" class="nav-link text-white">Inicio</a></li>

                <!-- Dropdown Vehículos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="vehiculosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Vehículos
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#toyota">Toyota</a></li>
                        <li><a class="dropdown-item" href="#mazda">Mazda</a></li>
                        <li><a class="dropdown-item" href="#ferrari">Ferrari</a></li>
                        <li><a class="dropdown-item" href="#audi">Audi</a></li>
                        <li><a class="dropdown-item" href="#lamborghini">Lamborghini</a></li>
                        <li><a class="dropdown-item" href="#suzuki">Suzuki</a></li>
                        <li><a class="dropdown-item" href="#hyundai">Hyundai</a></li>
                        <li><a class="dropdown-item" href="#chevrolet">Chevrolet</a></li>
                        <li><a class="dropdown-item" href="#bmw">BMW</a></li>
                        <li><a class="dropdown-item" href="#mercedes">Mercedes-Benz</a></li>
                        <li><a class="dropdown-item" href="#nissan">Nissan</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="mostrarFormularioCita()">Agendar Cita</a></li>
                <li class="nav-item"><a href="#" class="nav-link text-white" onclick="mostrarFormularioContacto()">Contacto</a></li>
            </ul>
            <!-- Icono del carrito -->
            <a href="#" class="text-white fs-4"><i class="bi bi-cart4"></i></a>
        </nav>
    </div>
</header>
ASDAS
<!-- Carrusel de vehículos -->
<section id="inicio" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <!-- Imagen 1 -->
        <div class="carousel-item active">
            <img src="Imagen/Audi1.jpg" class="carousel-img" alt="Audi Modelo 2025">
            <div class="carousel-caption d-none d-md-block">
                <h5>Audi Modelo 2025</h5>
                <p>Confort y tecnología de punta para el conductor moderno.</p>
            </div>
        </div>
        <!-- Imagen 2 -->
        <div class="carousel-item">
            <img src="Imagen/Audi2.jpg" class="carousel-img" alt="Audi Q5 2025">
            <div class="carousel-caption d-none d-md-block">
                <h5>Audi Q5 2025</h5>
                <p>Estilo y rendimiento para una conducción excepcional.</p>
            </div>
        </div>
        <!-- Imagen 3 -->
        <div class="carousel-item">
            <img src="Imagen/Audi3.jpg" class="carousel-img" alt="Audi A6 2025">
            <div class="carousel-caption d-none d-md-block">
                <h5>Audi A6 2025</h5>
                <p>Lujo, confort y eficiencia en un solo vehículo.</p>
            </div>
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

    <!-- Sección Toyota -->
    <section id="toyota" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Toyota</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/toyota1.jpg" class="card-img-top" alt="Toyota Corolla 2025">
                        <div class="card-body">
                            <h5 class="card-title">Toyota Corolla 2025</h5>
                            <p class="card-text">Eficiencia japonesa con tecnología de punta.</p>
                            <button class="btn btn-primary" onclick="verMas('Toyota Corolla 2025', 'Imagen/toyota1.jpg', 'Eficiencia japonesa con tecnología de punta.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Mazda -->
    <section id="mazda" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Mazda</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/mazda1.jpg" class="card-img-top" alt="Mazda CX-5 2025">
                        <div class="card-body">
                            <h5 class="card-title">Mazda CX-5 2025</h5>
                            <p class="card-text">Diseño elegante y deportivo.</p>
                            <button class="btn btn-primary" onclick="verMas('Mazda CX-5 2025', 'Imagen/mazda1.jpg', 'Diseño elegante y deportivo.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Ferrari -->
    <section id="ferrari" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Ferrari</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/ferrari1.jpg" class="card-img-top" alt="Ferrari F8 Tributo">
                        <div class="card-body">
                            <h5 class="card-title">Ferrari F8 Tributo</h5>
                            <p class="card-text">Potencia y lujo incomparables.</p>
                            <button class="btn btn-primary" onclick="verMas('Ferrari F8 Tributo', 'Imagen/ferrari1.jpg', 'Potencia y lujo incomparables.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Audi -->
    <section id="audi" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Audi</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/Audi1.jpg" class="card-img-top" alt="Audi A4 2025">
                        <div class="card-body">
                            <h5 class="card-title">Audi A4 2025</h5>
                            <p class="card-text">Eficiencia y confort para toda la familia.</p>
                            <button class="btn btn-primary" onclick="verMas('Audi A4 2025', 'Imagen/Audi1.jpg', 'Eficiencia y confort para toda la familia.')">Ver más</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/Audi2.jpg" class="card-img-top" alt="Audi Q5 2025">
                        <div class="card-body">
                            <h5 class="card-title">Audi Q5 2025</h5>
                            <p class="card-text">Diseño y tecnología avanzada.</p>
                            <button class="btn btn-primary" onclick="verMas('Audi Q5 2025', 'Imagen/Audi2.jpg', 'Diseño y tecnología avanzada.')">Ver más</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/Audi3.jpg" class="card-img-top" alt="Audi A6 2025">
                        <div class="card-body">
                            <h5 class="card-title">Audi A6 2025</h5>
                            <p class="card-text">Confort y lujo en cada detalle.</p>
                            <button class="btn btn-primary" onclick="verMas('Audi A6 2025', 'Imagen/Audi3.jpg', 'Confort y lujo en cada detalle.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Lamborghini -->
    <section id="lamborghini" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Lamborghini</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/lamborghini1.jpg" class="card-img-top" alt="Lamborghini Aventador">
                        <div class="card-body">
                            <h5 class="card-title">Lamborghini Aventador</h5>
                            <p class="card-text">Rendimiento extremo y lujo sin igual.</p>
                            <button class="btn btn-primary" onclick="verMas('Lamborghini Aventador', 'Imagen/lamborghini1.jpg', 'Rendimiento extremo y lujo sin igual.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Suzuki -->
    <section id="suzuki" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Suzuki</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/suzuki1.jpg" class="card-img-top" alt="Suzuki Swift 2025">
                        <div class="card-body">
                            <h5 class="card-title">Suzuki Swift 2025</h5>
                            <p class="card-text">Eficiencia y estilo compacto.</p>
                            <button class="btn btn-primary" onclick="verMas('Suzuki Swift 2025', 'Imagen/suzuki1.jpg', 'Eficiencia y estilo compacto.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Hyundai -->
    <section id="hyundai" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Hyundai</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/hyundai1.jpg" class="card-img-top" alt="Hyundai Tucson 2025">
                        <div class="card-body">
                            <h5 class="card-title">Hyundai Tucson 2025</h5>
                            <p class="card-text">Diseño moderno y eficiencia en cada detalle.</p>
                            <button class="btn btn-primary" onclick="verMas('Hyundai Tucson 2025', 'Imagen/hyundai1.jpg', 'Diseño moderno y eficiencia en cada detalle.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Chevrolet -->
    <section id="chevrolet" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Chevrolet</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/chevrolet1.jpg" class="card-img-top" alt="Chevrolet Silverado 2025">
                        <div class="card-body">
                            <h5 class="card-title">Chevrolet Silverado 2025</h5>
                            <p class="card-text">Potencia y durabilidad.</p>
                            <button class="btn btn-primary" onclick="verMas('Chevrolet Silverado 2025', 'Imagen/chevrolet1.jpg', 'Potencia y durabilidad.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección BMW -->
    <section id="bmw" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">BMW</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/bmw1.jpg" class="card-img-top" alt="BMW 3 Series 2025">
                        <div class="card-body">
                            <h5 class="card-title">BMW 3 Series 2025</h5>
                            <p class="card-text">Confort deportivo y tecnología avanzada.</p>
                            <button class="btn btn-primary" onclick="verMas('BMW 3 Series 2025', 'Imagen/bmw1.jpg', 'Confort deportivo y tecnología avanzada.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Mercedes-Benz -->
    <section id="mercedes" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Mercedes-Benz</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/mercedes1.jpg" class="card-img-top" alt="Mercedes-Benz S-Class 2025">
                        <div class="card-body">
                            <h5 class="card-title">Mercedes-Benz S-Class 2025</h5>
                            <p class="card-text">Lujo y rendimiento excepcionales.</p>
                            <button class="btn btn-primary" onclick="verMas('Mercedes-Benz S-Class 2025', 'Imagen/mercedes1.jpg', 'Lujo y rendimiento excepcionales.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección Nissan -->
    <section id="nissan" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Nissan</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="Imagen/nissan1.jpg" class="card-img-top" alt="Nissan Altima 2025">
                        <div class="card-body">
                            <h5 class="card-title">Nissan Altima 2025</h5>
                            <p class="card-text">Tecnología y confort al más alto nivel.</p>
                            <button class="btn btn-primary" onclick="verMas('Nissan Altima 2025', 'Imagen/nissan1.jpg', 'Tecnología y confort al más alto nivel.')">Ver más</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Car Evolution. Todos los derechos reservados.</p>
        <div>
            <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
