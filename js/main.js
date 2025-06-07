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

                    option.textContent = `${v.modelo} - $${precioFormateado}`;

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

// Configurar comportamiento del formulario según tipoCita
function configurarFormulario(tipoCitaId, tipoCompraId, divCompraId, tipoServicioId, divServicioId) {
    const tipoCita = document.getElementById(tipoCitaId);
    const tipoCompra = document.getElementById(tipoCompraId);
    const divTipoCompra = document.getElementById(divCompraId);
    const tipoServicio = document.getElementById(tipoServicioId);
    const divTipoServicio = document.getElementById(divServicioId);

    if (!tipoCita || !tipoCompra || !divTipoCompra || !tipoServicio || !divTipoServicio) return;

    // Inicializar ocultando ambos
    divTipoCompra.style.display = 'none';
    tipoCompra.disabled = true;
    divTipoServicio.style.display = 'none';
    tipoServicio.disabled = true;

    tipoCita.addEventListener('change', function () {
        if (this.value === 'compra') {
            divTipoCompra.style.display = 'block';
            tipoCompra.disabled = false;

            divTipoServicio.style.display = 'none';
            tipoServicio.disabled = true;

            cargarModelos(tipoCompraId);
        } else if (this.value === 'servicio') {
            divTipoCompra.style.display = 'none';
            tipoCompra.disabled = true;

            divTipoServicio.style.display = 'block';
            tipoServicio.disabled = false;

            cargarServicios(tipoServicioId);
        } else if (this.value === 'TestDeManejo') {
            divTipoCompra.style.display = 'none';
            tipoCompra.disabled = true;

            divTipoServicio.style.display = 'block';
       
            tipoServicio.disabled = false;
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

function verMas(modelo, nombreImagen, descripcion, precio, inventario, id, tipoCita = 'compra') {
    const rutaImagen = `imagen/${nombreImagen}`; // Concatenar ruta fija con nombre desde SQL
    const precioFormateado = Number(precio).toLocaleString('es-GT', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    Swal.fire({
        title: modelo,
        imageUrl: rutaImagen,
        imageAlt: modelo,
        html: `
            <p>${descripcion}</p>
            <p><strong>Precio:</strong> $${precioFormateado}</p>
            <p><strong>Inventario:</strong> ${inventario}</p>
            <p>¿Deseas agendar una cita para este ${tipoCita === 'compra' ? 'vehículo' : tipoCita}?</p>
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
                    window.location.href = res.isConfirmed ? 'login.php' : 'index.php';
                });
            } else {
                document.getElementById('agendar-cita').scrollIntoView({ behavior: 'smooth' });

                const tipoCitaInput = document.getElementById('tipoCita2');
                const tipoCompraInput = document.getElementById('tipoCompra2');
                const divTipoCompra = document.getElementById('divTipoCompra2');

                if (tipoCitaInput && tipoCompraInput && divTipoCompra) {
                    tipoCitaInput.value = tipoCita;
                    divTipoCompra.style.display = 'block';

                    if (tipoCita === 'compra') {
                        cargarModelos('tipoCompra2', modelo);
                    } else if (tipoCita === 'servicio') {
                        cargarServicios('tipoCompra2');
                        tipoCompraInput.value = tipoServicio.toLowerCase().replace(/\s+/g, '_');
                    }
                }
            }
        }
    });
}


// Inicialización al cargar página
document.addEventListener('DOMContentLoaded', () => {
    configurarFormulario('tipoCita1', 'tipoCompra1', 'divTipoCompra1', 'tipoServicio1', 'divTipoServicio1');
    configurarFormulario('tipoCita2', 'tipoCompra2', 'divTipoCompra2', 'tipoServicio2', 'divTipoServicio2');

    // Rellenar datos de sesión si aplica
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

function cargarServicios(selectId, servicioSeleccionado = '') {
    fetch('obtener_servicios.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al obtener servicios");
            }
            return response.json();
        })
        .then(data => {
            const select = document.getElementById(selectId);
            if (select) {
                select.innerHTML = ''; // Limpiar opciones previas

                data.forEach(servicio => {
                    const option = document.createElement('option');
                    option.value = servicio.tipoServicio;
                    option.textContent = `${servicio.tipoServicio} - $${Number(servicio.precio).toLocaleString('es-GT', {
                        minimumFractionDigits: 2
                    })}`;

                    if (servicio.tipoServicio.toLowerCase() === servicioSeleccionado.toLowerCase()) {
                        option.selected = true;
                    }

                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar servicios:', error);
        });
}

// Cargar vehículos desde SQL
function cargarVehiculosDesdeSQL(select) {
    fetch('obtener_vehiculos.php')
        .then(res => res.json())
        .then(data => {
            select.innerHTML = '<option value="">Seleccione vehículo</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.modelo;

                const precioFormateado = Number(item.precio).toLocaleString('es-GT', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                option.textContent = `${item.modelo} — $${precioFormateado}`;
                option.setAttribute('data-precio', item.precio);
                select.appendChild(option);
            });
        })
        .catch(err => {
            console.error('Error al cargar vehículos:', err);
            select.innerHTML = '<option value="">Error al cargar vehículos</option>';
        });
}

function cargarServiciosDesdeSQL(select) {
    fetch('obtener_servicios.php')
        .then(res => res.json())
        .then(data => {
            select.innerHTML = '<option value="">Seleccione un servicio</option>';
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.tipoServicio.toLowerCase().replace(/\s+/g, '_');
                const precioFormateado = Number(item.precio).toLocaleString('es-GT', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                option.textContent = `${item.tipoServicio} — $${precioFormateado}`;
                option.setAttribute('data-precio', item.precio);
                select.appendChild(option);
            });
        })
        .catch(err => {
            console.error('Error al cargar servicios desde SQL:', err);
            select.innerHTML = '<option value="">Error al cargar servicios</option>';
        });
}
