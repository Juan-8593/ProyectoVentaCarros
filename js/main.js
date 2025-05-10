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


// Mostrar formulario de agendar cita
function mostrarFormularioCita() {
    Swal.fire({
        title: 'Agendar Cita',
        html: `
            <input id="nombreCita" class="swal2-input" placeholder="Nombre">
            <input id="correoCita" type="email" class="swal2-input" placeholder="Correo Electrónico">
            <input id="fechaCita" type="date" class="swal2-input">
            <input id="horaCita" type="time" class="swal2-input">
        `,
        focusConfirm: false,
        preConfirm: () => {
            const nombre = document.getElementById('nombreCita').value;
            const correo = document.getElementById('correoCita').value;
            const fecha = document.getElementById('fechaCita').value;
            const hora = document.getElementById('horaCita').value;

            if (!nombre || !correo || !fecha || !hora) {
                Swal.showValidationMessage('Por favor completa todos los campos.');
                return false;
            }

            return { nombre, correo, fecha, hora };
        },
        confirmButtonText: 'Agendar',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                title: 'Cita Agendada',
                text: `Gracias ${result.value.nombre}, te esperamos el ${result.value.fecha} a las ${result.value.hora}.`,
                icon: 'success'
            });
        }
    });
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

// Interceptar clic en los enlaces del menú para usar SweetAlert
document.addEventListener("DOMContentLoaded", () => {
    document.querySelector('a[href="#cita"]').addEventListener('click', (e) => {
        e.preventDefault();
        mostrarFormularioCita();
    });

    document.querySelector('a[href="#contacto"]').addEventListener('click', (e) => {
        e.preventDefault();
        mostrarFormularioContacto();
    });
});
