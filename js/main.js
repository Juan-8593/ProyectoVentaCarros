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

// Función para interceptar envío de formularios de cita y mostrar SweetAlert
function manejarEnvioFormulario(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Cita agendada!',
                    text: data.message || 'Tu cita se ha agendado correctamente.',
                });
                form.reset();

                // Ocultar div de tipoCompra si estaba visible
                const divCompra = form.querySelector('div[id^="divTipoCompra"]');
                if (divCompra) divCompra.style.display = 'none';

            } else if (data.status === 'error' && data.needLogin) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Debes iniciar sesión',
                    text: data.message || 'Necesitas estar registrado para agendar una cita.',
                    showCancelButton: true,
                    confirmButtonText: 'Iniciar sesión',
                    cancelButtonText: 'Cancelar',
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.php'; // Cambia según tu ruta de login
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Ocurrió un error al agendar la cita.',
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo conectar con el servidor.',
            });
        });
    });
}

// Ejecutar configuración cuando se carga la página y autocompletar nombre si sesión activa
document.addEventListener('DOMContentLoaded', () => {
    configurarFormulario('tipoCita1', 'tipoCompra1', 'tipoCompra1');
    configurarFormulario('tipoCita2', 'tipoCompra2', 'divTipoCompra2');

    fetch('validar_sesion.php')
        .then(res => res.json())
        .then(data => {
            if (data.sesion_activa) {
                // Formulario dentro de #formularioCita
                const form1 = document.querySelector('#formularioCita form');
                if (form1) {
                    const inputNombre1 = form1.querySelector('input[name="nombre"]');
                    const inputCorreo1 = form1.querySelector('input[name="correo"]');
                    if (data.nombre && inputNombre1) {
                        inputNombre1.value = data.nombre;
                        inputNombre1.readOnly = true; // bloquear edición
                    }
                    if (data.correo && inputCorreo1) {
                        inputCorreo1.value = data.correo;
                        inputCorreo1.readOnly = true; // bloquear edición
                    }
                }

                // Formulario dentro de #agendar-cita
                const form2 = document.querySelector('#agendar-cita form');
                if (form2) {
                    const inputNombre2 = form2.querySelector('input[name="nombre"]');
                    const inputCorreo2 = form2.querySelector('input[name="correo"]');
                    if (data.nombre && inputNombre2) {
                        inputNombre2.value = data.nombre;
                        inputNombre2.readOnly = true; // bloquear edición
                    }
                    if (data.correo && inputCorreo2) {
                        inputCorreo2.value = data.correo;
                        inputCorreo2.readOnly = true; // bloquear edición
                    }
                }
            }
        })
        .catch(err => {
            console.error('Error al validar sesión:', err);
        });

    // Interceptar envío de formularios de cita
    manejarEnvioFormulario('formularioCita1');
    manejarEnvioFormulario('formularioCita2');
});

// Función para verificar sesión con validar_sesion.php (solo estado)
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
                    confirmButtonText: 'Iniciar Sesion',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((res) => {
                    if (res.isConfirmed) {
                        window.location.href = 'login.php';
                    } else {
                        window.location.href = 'index.php';
                    }
                });
            } else {
                document.getElementById('agendar-cita').scrollIntoView({ behavior: 'smooth' });

                const tipoCita = document.getElementById('tipoCita2');
                const tipoCompra = document.getElementById('tipoCompra2');
                const divTipoCompra = document.getElementById('divTipoCompra2');

                if (tipoCita && tipoCompra && divTipoCompra) {
                    tipoCita.value = 'compra';
                    divTipoCompra.style.display = 'block';
                    cargarModelos('tipoCompra2', modelo);
                }
            }
        }
    });
}
