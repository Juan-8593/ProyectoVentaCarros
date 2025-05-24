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

// Cargar modelos con precio formateado (para compra)
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

// Cargar servicios con precio fijo (para servicio)
function cargarServicios(selectId) {
    const servicios = [
        { nombre: "Alineación", precio: 1500 },
        { nombre: "Cambio de aceite", precio: 800 },
        { nombre: "Frenos", precio: 1200 },
        { nombre: "Revisión general", precio: 2000 },
        { nombre: "Limpieza y lavado", precio: 1000 },
        { nombre: "Bujías", precio: 3000 }
    ];
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option value="">Seleccione servicio</option>';
    servicios.forEach(servicio => {
        const option = document.createElement('option');
        option.value = servicio.nombre.toLowerCase().replace(/\s+/g, '_');
        const precioFormateado = servicio.precio.toLocaleString('es-GT', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        option.textContent = `${servicio.nombre} - Q${precioFormateado}`;
        select.appendChild(option);
    });
}

// Cargar mantenimientos con precio fijo (para mantenimiento)
function cargarMantenimiento(selectId) {
    const mantenimientos = [
        { nombre: "Cambio de filtro de aire", precio: 1200 },
        { nombre: "Cambio de filtro de aceite", precio: 900 },
        { nombre: "Cambio de bujías", precio: 2500 },
        { nombre: "Revisión de batería", precio: 1800 },
        { nombre: "Revisión de sistema de enfriamiento", precio: 2200 }
    ];
    const select = document.getElementById(selectId);
    if (!select) return;

    select.innerHTML = '<option value="">Seleccione mantenimiento</option>';
    mantenimientos.forEach(mant => {
        const option = document.createElement('option');
        option.value = mant.nombre.toLowerCase().replace(/\s+/g, '_');
        const precioFormateado = mant.precio.toLocaleString('es-GT', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        option.textContent = `${mant.nombre} - Q${precioFormateado}`;
        select.appendChild(option);
    });
}

// Configurar comportamiento del formulario según tipoCita
function configurarFormulario(tipoCitaId, tipoCompraId, divCompraId) {
    const tipoCita = document.getElementById(tipoCitaId);
    const tipoCompra = document.getElementById(tipoCompraId);
    const divTipoCompra = document.getElementById(divCompraId);

    if (!tipoCita || !tipoCompra || !divTipoCompra) return;

    tipoCita.addEventListener('change', function () {
        if (this.value === 'compra') {
            divTipoCompra.style.display = 'block';
            cargarModelos(tipoCompraId);
        } else if (this.value === 'servicio') {
            divTipoCompra.style.display = 'block';
            cargarServicios(tipoCompraId);
        } else if (this.value === 'mantenimiento') {
            divTipoCompra.style.display = 'block';
            cargarMantenimiento(tipoCompraId);
        } else {
            divTipoCompra.style.display = 'none';
            tipoCompra.innerHTML = '<option value="">Seleccione opción</option>';
        }
    });
}

// Interceptar envío de formularios de cita
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
                        window.location.href = 'login.php';
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

// Verificar sesión (solo estado booleano)
function verificarSesion() {
    return fetch('validar_sesion.php')
        .then(res => res.json())
        .then(data => data.sesion_activa === true)
        .catch(() => false);
}

// Mostrar detalles de vehículo y agendar cita
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
                    confirmButtonText: 'Iniciar Sesión',
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
                // Llevar al formulario y prellenar con compra y modelo seleccionado
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

// Inicialización al cargar página
document.addEventListener('DOMContentLoaded', () => {
    configurarFormulario('tipoCita1', 'tipoCompra1', 'tipoCompra1');  // Aquí asumo que el div para compra tiene id igual a select tipoCompra1 (ajustar si no)
    configurarFormulario('tipoCita2', 'tipoCompra2', 'divTipoCompra2');

    // Rellenar datos de usuario si está logueado
    fetch('validar_sesion.php')
        .then(res => res.json())
        .then(data => {
            if (data.sesion_activa) {
                ['formularioCita', 'agendar-cita'].forEach(formId => {
                    const form = document.querySelector(`#${formId} form`);
                    if (form) {
                        const inputNombre = form.querySelector('input[name="nombre"]');
                        const inputCorreo = form.querySelector('input[name="correo"]');
                        if (data.nombre && inputNombre) {
                            inputNombre.value = data.nombre;
                            inputNombre.readOnly = true;
                        }
                        if (data.correo && inputCorreo) {
                            inputCorreo.value = data.correo;
                            inputCorreo.readOnly = true;
                        }
                    }
                });
            }
        })
        .catch(err => console.error('Error al validar sesión:', err));

    manejarEnvioFormulario('formularioCita1');
    manejarEnvioFormulario('formularioCita2');
});
