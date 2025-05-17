function verMas(nombre, imagen, descripcion) {
    Swal.fire({
        title: `¿Estás seguro de querer ver más detalles de ${nombre}?`,
        text: "Podrás ver más información y comprar el vehículo si lo deseas.",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Mostrar detalles",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: `${nombre}`,
                html: `
                    <img src="${imagen}" alt="${nombre}" style="width: 100%; max-width: 400px; height: auto; margin-bottom: 20px;">
                    <p><strong>Descripción:</strong> ${descripcion}</p>
                    <p>¿Te interesa comprar este vehículo?</p>
                `,
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Agregar al Carrito",
                cancelButtonText: "Cancelar"
            }).then((resultCompra) => {
                if (resultCompra.isConfirmed) {
                    agregarAlCarrito(nombre, imagen, descripcion);
                    Swal.fire({
                        title: "¡Se ha agregado al carrito de compras!",
                        text: "Para completar la compra, dirígete al carrito de compras.",
                        icon: "success"
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
    });
}

function agregarAlCarrito(nombre, imagen, descripcion) {
    const vehiculo = { nombre, imagen, descripcion };
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.push(vehiculo);
    localStorage.setItem("carrito", JSON.stringify(carrito));
}

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
