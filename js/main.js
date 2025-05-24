// Mostrar formulario de contacto
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

// Función reutilizable para cargar modelos con precio formateado
function cargarModelos(selectId, modeloSeleccionado = null) {
    const select = document.getElementById(selectId);
    if (!select) return;

    fetch('obtener_vehiculos.php')
        .then(res => res.json())
        .then(data => {
            if (Array.isArray(data)) {
                select.innerHTML = '<option value="">Seleccione vehículo</option>';
                data.forEach(v => {
                    const option = document.createElement('option');
                    option.value = v.modelo;

                    // Formatear precio con comas y 2 decimales
                    const precioFormateado = Number(v.precio).toLocaleString('es-GT', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    option.textContent = `${v.modelo} - Q${precioFormateado}`;

                    if (v.modelo === modeloSeleccionado) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            } else {
                select.innerHTML = '<option value="">Error al cargar modelos</option>';
            }
        })
        .catch(() => {
            select.innerHTML = '<option value="">Error de conexión</option>';
        });
}

// Configurar el comportamiento de tipoCita en formularios
function configurarFormulario(tipoCitaId, tipoCompraId, divCompraId) {
    const tipoCita = document.getElementById(tipoCitaId);
    const tipoCompra = document.getElementById(tipoCompraId);
    const divTipoCompra = document.getElementById(divCompraId);

    if (!tipoCita || !tipoCompra || !divTipoCompra) return;

    tipoCita.addEventListener('change', function () {
        if (this.value === 'compra') {
            divTipoCompra.style.display = 'block';
            cargarModelos(tipoCompraId);
        } else {
            divTipoCompra.style.display = 'none';
            tipoCompra.innerHTML = '<option value="">Seleccione vehículo</option>';
        }
    });
}

// Ejecutar configuración cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    configurarFormulario('tipoCita1', 'tipoCompra1', 'tipoCompra1');
    configurarFormulario('tipoCita2', 'tipoCompra2', 'divTipoCompra2');
});

// Función para verificar sesión con validar_sesion.php
function verificarSesion() {
    return fetch('validar_sesion.php')
        .then(res => res.json())
        .then(data => data.sesion_activa === true)
        .catch(() => false);
}

// Ver más detalles de un vehículo y agendar cita con validación de sesión
function verMas(modelo, imagen, descripcion, precio, inventario, id) {
    const precioFormateado = Number(precio).toLocaleString('es-GT', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    Swal.fire({
        title: modelo,
        imageUrl: imagen,
        imageAlt: modelo,
        html: `
            <p>${descripcion}</p>
            <p><strong>Precio:</strong> Q${precioFormateado}</p>
            <p><strong>Inventario:</strong> ${inventario}</p>
            <p>¿Deseas agendar una cita para este vehículo?</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Agendar cita',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const estaLogueado = await verificarSesion();

            if (!estaLogueado) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Debes iniciar sesión',
                    text: 'Necesitas estar registrado para agendar una cita.',
                    showCancelButton: true,
                    confirmButtonText: 'Registrarme',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location.href = 'registro.php';
                    } else {
                        window.location.href = 'index.php';
                    }
                });
            } else {
                // Ir a la sección de agendar cita
                document.getElementById('agendar-cita').scrollIntoView({ behavior: 'smooth' });

                const tipoCita = document.getElementById('tipoCita2');
                const tipoCompra = document.getElementById('tipoCompra2');
                const divTipoCompra = document.getElementById('divTipoCompra2');

                if (tipoCita && tipoCompra && divTipoCompra) {
                    tipoCita.value = 'compra';
                    divTipoCompra.style.display = 'block';

                    // Cargar modelos y preseleccionar
                    cargarModelos('tipoCompra2', modelo);
                }
            }
        }
    });
}
