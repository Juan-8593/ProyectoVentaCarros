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

function verMas(modelo, imagen, descripcion, precio, inventario, id) {
    const inventarioCard = document.getElementById(`inventarioCard-${id}`);
    let inventarioNum = inventario;

    if (inventarioCard) {
        const inventarioDesdeDOM = parseInt(inventarioCard.textContent);
        if (!isNaN(inventarioDesdeDOM)) {
            inventarioNum = inventarioDesdeDOM;
        }
    }

    let contenidoCompra = '';

    if (inventarioNum > 0) {
        contenidoCompra = `
            <p><strong>Precio:</strong> Q${precio}</p>
            <p><strong>Inventario:</strong> <span id="inventarioDisplay">${inventarioNum}</span></p>
            <input type="number" id="cantidadCompra" class="swal2-input" min="1" max="${inventarioNum}" value="1" placeholder="Cantidad">
        `;
    } else {
        contenidoCompra = `<p class="text-danger">Vehículo agotado.</p>`;
    }

    Swal.fire({
        title: modelo,
        imageUrl: imagen,
        imageAlt: modelo,
        html: `
            <p>${descripcion}</p>
            ${contenidoCompra}
        `,
        showCancelButton: true,
        confirmButtonText: inventarioNum > 0 ? 'Comprar' : 'Cerrar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            if (inventarioNum > 0) {
                const inventarioActual = parseInt(document.getElementById('inventarioDisplay').textContent);
                const cantidad = parseInt(document.getElementById('cantidadCompra').value);

                if (!cantidad || cantidad < 1 || cantidad > inventarioActual) {
                    Swal.showValidationMessage(`Ingresa una cantidad válida (1 - ${inventarioActual})`);
                    return false;
                }
                return { idVehiculo: id, cantidad: cantidad };
            }
            return false;
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            fetch('procesar_compra.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `idVehiculo=${encodeURIComponent(result.value.idVehiculo)}&cantidad=${encodeURIComponent(result.value.cantidad)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar inventario en la tarjeta principal
                    if (inventarioCard) {
                        inventarioCard.textContent = data.nuevoInventario;
                    }

                    // Actualizar inventario en el SweetAlert detalle (antes de cerrar)
                    const inventarioDisplay = document.getElementById('inventarioDisplay');
                    const cantidadInput = document.getElementById('cantidadCompra');
                    if (inventarioDisplay && cantidadInput) {
                        inventarioDisplay.textContent = data.nuevoInventario;
                        cantidadInput.max = data.nuevoInventario;

                        if (parseInt(cantidadInput.value) > data.nuevoInventario) {
                            cantidadInput.value = data.nuevoInventario > 0 ? data.nuevoInventario : 1;
                        }
                    }

                    // No cerrar inmediatamente, actualizar visualmente primero
                    Swal.update({
                        html: `
                            <p>${descripcion}</p>
                            <p><strong>Precio:</strong> Q${precio}</p>
                            <p><strong>Inventario:</strong> <span id="inventarioDisplay">${data.nuevoInventario}</span></p>
                            <input type="number" id="cantidadCompra" class="swal2-input" min="1" max="${data.nuevoInventario}" value="1" placeholder="Cantidad" ${data.nuevoInventario === 0 ? 'disabled' : ''}>
                        `,
                        confirmButtonText: data.nuevoInventario > 0 ? 'Comprar' : 'Cerrar'
                    });

                    // Luego cerrar con un pequeño delay para que el usuario vea el cambio
                    setTimeout(() => {
                        Swal.close();
                        Swal.fire({
                            icon: 'success',
                            title: '¡Compra realizada!',
                            text: `Has comprado ${result.value.cantidad} unidad(es) de ${modelo}.`,
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    }, 800);

                } else if (data.error) {
                    Swal.fire('Error', data.error, 'error');
                } else {
                    Swal.fire('Error', 'Error inesperado al procesar la compra', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            });
        }
    });
}
