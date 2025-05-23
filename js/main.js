function verMas(nombre, imagen, descripcion, inventario) {
    const rutaImagen = imagen.includes("Imagen/") ? imagen : 'Imagen/' + imagen;

    // Variable local para inventario que podemos actualizar
    let inventarioActual = inventario;

    Swal.fire({
        title: `${nombre}`,
        html: `
            <img src="${rutaImagen}" alt="${nombre}" style="width: 100%; max-width: 400px; height: auto; margin-bottom: 20px;">
            <p><strong>Modelo:</strong> ${nombre}</p>
            <p><strong>Descripción:</strong> ${descripcion}</p>
            <p id="inventarioDisponible"><strong>Inventario disponible:</strong> ${inventarioActual}</p>
            <p>¿Te interesa comprar este vehículo?</p>
        `,
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Agregar al Carrito",
        cancelButtonText: "Cancelar",
        preConfirm: () => {
            // Solo permitir compra si inventario > 0
            if (inventarioActual <= 0) {
                Swal.showValidationMessage('No hay inventario disponible para este vehículo.');
                return false; // Cancela confirmación
            }
            return true;
        }
    }).then((resultCompra) => {
        if (resultCompra.isConfirmed) {
            agregarAlCarrito(nombre, rutaImagen, descripcion);

            fetch('agregarAlCarrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ modelo: nombre })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reducir inventario localmente y actualizar el texto del modal
                    inventarioActual--;

                    const swalContent = Swal.getHtmlContainer();
                    if (swalContent) {
                        const inventarioElemento = swalContent.querySelector('#inventarioDisponible');
                        if (inventarioElemento) {
                            inventarioElemento.innerHTML = `<strong>Inventario disponible:</strong> ${inventarioActual}`;
                        }
                    }

                    Swal.fire({
                        title: "¡Se ha agregado al carrito de compras!",
                        text: "Para completar la compra, dirígete al carrito de compras.",
                        icon: "success"
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: data.message || "No se pudo actualizar el inventario.",
                        icon: "error"
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    title: "Error",
                    text: "Error de conexión al intentar actualizar el inventario.",
                    icon: "error"
                });
            });
        } else if (resultCompra.isDismissed) {
            Swal.fire({
                title: "Compra cancelada",
                text: "No se realizó ninguna acción.",
                icon: "error"
            });
        }
    });
}

// Función para guardar el vehículo en el carrito localStorage
function agregarAlCarrito(nombre, imagen, descripcion) {
    const vehiculo = { nombre, imagen, descripcion };
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.push(vehiculo);
    localStorage.setItem("carrito", JSON.stringify(carrito));
}

// Función para cargar vehículos y mostrarlos dinámicamente
function cargarVehiculos(marca) {
    fetch(`obtenerVehiculos.php?marca=${marca}`)
        .then(response => response.json())
        .then(data => {
            const contenedor = document.getElementById(`contenedor${marca}`);
            contenedor.innerHTML = '';

            data.forEach(auto => {
                // Protección básica para evitar errores si algún campo es null o indefinido
                const modelo = (auto.modelo || '').replace(/'/g, "\\'");
                const descripcion = (auto.descripcion || '').replace(/'/g, "\\'");
                const imagen = auto.imagen || '';
                const inventario = auto.inventario !== undefined ? auto.inventario : 'No disponible';

const card = document.createElement("div");
card.className = "col-md-4";
card.innerHTML = `
    <div class="card mb-4">
        <img src="${auto.imagen}" class="card-img-top" alt="${auto.modelo}">
        <div class="card-body">
            <h5 class="card-title">${auto.modelo}</h5>
            <p class="card-text">${auto.descripcion}</p>
            <p class="inventario-texto"><strong>Inventario disponible:</strong> ${auto.inventario}</p>
            <button class="btn btn-primary" onclick="verMas('${auto.modelo}', '${auto.imagen}', '${auto.descripcion.replace(/'/g, "\\'")}', ${auto.inventario}, this)">
                Ver más
            </button>
        </div>
    </div>
`;
contenedor.appendChild(card);

            });
        })
        .catch(error => {
            console.error('Error al cargar los vehículos:', error);
        });
}

// Llamada inicial para cargar Ferrari (puedes cambiar o hacer dinámica esta parte)
document.addEventListener('DOMContentLoaded', () => {
    cargarVehiculos('Ferrari');
});

// Mostrar formulario de contacto
function mostrarFormularioContacto() {
    Swal.fire({
        title: 'Contáctanos',
        html: `
            <input id="nombreContacto" class="swal2-input" placeholder="Nombre">
            <input id="correoContacto" type="email" class="swal2-input" placeholder="Correo Electrónico">
            <textarea id="mensajeContacto" class="swal2-textarea" placeholder="Escribe tu mensaje aquí"></textarea>
        `,
        focusConfirm: false,
        preConfirm: () => {
            const nombre = document.getElementById('nombreContacto').value;
            const correo = document.getElementById('correoContacto').value;
            const mensaje = document.getElementById('mensajeContacto').value;

            if (!nombre || !correo || !mensaje) {
                Swal.showValidationMessage('Por favor completa todos los campos.');
                return false;
            }

            return { nombre, correo, mensaje };
        },
        confirmButtonText: 'Enviar',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                title: 'Mensaje Enviado',
                text: `Gracias por tu mensaje, ${result.value.nombre}. Nos pondremos en contacto contigo pronto.`,
                icon: 'success'
            });
        }
    });
}
    
function mostrarFormularioContacto() {
    Swal.fire({
        title: 'Contacto',
        html: `
            <form id="formContacto">
                <input type="text" name="nombre" class="swal2-input" placeholder="Tu nombre" required>
                <input type="email" name="correo" class="swal2-input" placeholder="Tu correo" required>
                <textarea name="mensaje" class="swal2-textarea" placeholder="Escribe tu mensaje" required></textarea>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        preConfirm: () => {
            const form = document.getElementById('formContacto');
            const formData = new FormData(form);

            return fetch('guardar_contacto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result.trim() === "ok") {
                    Swal.fire('¡Mensaje enviado!', 'Gracias por contactarnos.', 'success');
                } else {
                    Swal.fire('Error', result, 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'No se pudo enviar el mensaje.', 'error');
            });

            return false;
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    fetch('mostrar_vehiculos.php')
        .then(response => response.json())
        .then(data => {
            const contenedor = document.getElementById("contenedorFerrari");
            console.log(data); // <-- Aquí verifica que cada objeto tenga 'inventario'
            data.forEach(auto => {
                const card = document.createElement("div");
                card.className = "col-md-4";
                card.innerHTML = `
                    <div class="card mb-4">
                        <img src="${auto.imagen}" class="card-img-top" alt="${auto.modelo}">
                        <div class="card-body">
                            <h5 class="card-title">${auto.modelo}</h5>
                            <p class="card-text">${auto.descripcion}</p>
                            <p><strong>Inventario disponible:</strong> ${auto.inventario !== undefined ? auto.inventario : 'No disponible'}</p>
                            <button class="btn btn-primary" onclick="verMas('${auto.modelo}', '${auto.imagen}', '${auto.descripcion.replace(/'/g, "\\'")}', ${auto.inventario || 0})">Ver más</button>
                        </div>
                    </div>
                `;
                contenedor.appendChild(card);
            });
        })
        .catch(error => console.error('Error al cargar los vehículos:', error));
});
