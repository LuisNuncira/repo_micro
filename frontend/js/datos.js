axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['Accept'] = 'application/json';

async function fetchSprints() {
    try {
        const response = await axios.get('sprints');
        console.log('Datos obtenidos:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error en la solicitud:', error.response?.data || error.message);
        throw error;
    }
}

async function cargarSelectSprints() {
    try {
        const select = document.getElementById('sprint_id');
        select.innerHTML = '<option value="">Cargando sprints...</option>';

        const data = await fetchSprints();

        if (!Array.isArray(data) || data.length === 0) {
            select.innerHTML = '<option value="">No hay sprints disponibles</option>';
            return;
        }

        select.innerHTML = '<option value="">Selecciona un sprint</option>';
        data.forEach(sprint => {
            const option = document.createElement('option');
            option.value = sprint.id;
            option.textContent = sprint.nombre;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar sprints en el select:', error);
        document.getElementById('sprint_id').innerHTML = '<option value="">Error al cargar</option>';
    }
}

function mostrarVista(vista) {
    const vistas = document.querySelectorAll('.vista');
    vistas.forEach(v => v.style.display = 'none');

    const elementoVista = document.getElementById(vista);
    if (elementoVista) {
        elementoVista.style.display = 'block';
        if (vista === 'historial') {
            loadHistorial();
        } else if (vista === 'reporte') {
            cargarReporteComentarios();
        }
    } else {
        console.warn(`La vista con id "${vista}" no existe.`);
    }
}

async function loadHistorial() {
    try {
        const data = await fetchSprints();
        const historial = document.getElementById('lista-historial');
        historial.innerHTML = '';

        if (data.length === 0) {
            historial.innerHTML = '<p>No se han registrado retrospectivas aún.</p>';
            return;
        }

        const fragment = document.createDocumentFragment();

        data.forEach(item => {
            const card = document.createElement('div');
            card.className = 'card';
            card.innerHTML = `
                <h3>${item.nombre}</h3>
                <p><strong>Fecha Inicio:</strong> ${item.fecha_inicio}</p>
                <p><strong>Fecha Fin:</strong> ${item.fecha_fin}</p>
                <p><strong>Categoría:</strong> ${item.categoria || 'N/A'}</p>
                <button class="btn btn-info" onclick="irAComentarios(${item.id})">🗒️ Comentarios</button>
                <button class="btn btn-danger" onclick="eliminarRetrospectiva(${item.id})">🗑️ Eliminar</button>
            `;
            fragment.appendChild(card);
        });

        historial.appendChild(fragment);
    } catch (error) {
        console.error('Error al cargar el historial:', error.response?.data || error.message);
        alert('Error al cargar el historial.');
    }
}

async function guardarRetrospectiva(event) {
    event.preventDefault();

    const sprint = document.getElementById('nombre').value;
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;

    if (!sprint) {
        alert('Por favor, ingresa el número del sprint.');
        return;
    }
    if (!fechaInicio || !fechaFin) {
        alert('Por favor, ingresa las fechas de inicio y fin');
        return;
    }
    console.log({
        nombre: sprint,
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin,
    });

    await axios.post('sprints', {
        nombre: sprint.trim(sprint),
        fecha_inicio: fechaInicio,
        fecha_fin: fechaFin,
    });

    alert('¡Retrospectiva creada exitosamente!');
    document.getElementById('form-retrospectiva').reset();
    mostrarVista('historial');
}

async function eliminarRetrospectiva(id) {
    if (confirm('¿Estás seguro de eliminar esta retrospectiva?')) {
        try {
            await axios.delete(`http://127.0.0.1:8000/api/sprints/${id}`);

            alert('Retrospectiva eliminada correctamente.');
            loadHistorial();
        } catch (error) {
            console.error('Error al eliminar:', error.response?.data || error.message);
            alert('Error al eliminar la retrospectiva.');
        }
    }
}

window.mostrarVista = mostrarVista;
window.guardarRetrospectiva = guardarRetrospectiva;
window.eliminarRetrospectiva = eliminarRetrospectiva;
window.guardarRetrospectivacome = guardarRetrospectivacome;

document.addEventListener('DOMContentLoaded', () => {
    mostrarVista('inicio');
});

async function guardarRetrospectivacome(event) {
    if (event && typeof event.preventDefault === 'function') {
        event.preventDefault();
    }

    const sprint_id = document.getElementById('sprint_id').value;
    const categoria = document.getElementById('categoria').value;
    const descripcion = document.getElementById('descripcion').value;
    const cumplida = document.getElementById('cumplida')?.checked ? 1 : 0;
    const fecha_revision = document.getElementById('fecha_revision').value;

    if (!sprint_id) {
        alert('Por favor, selecciona un sprint válido.');
        return;
    }

    const categoriasValidas = ['accion', 'logro', 'impedimento', 'comentario', 'otro'];
    if (!categoria || !categoriasValidas.includes(categoria)) {
        alert('Por favor, selecciona una categoría válida: ' + categoriasValidas.join(', '));
        return;
    }

    if (!descripcion || descripcion.trim().length === 0) {
        alert('Por favor, ingresa una descripción válida.');
        return;
    }

    try {
        const response = await axios.post('/retro-items', {
            sprint_id: parseInt(sprint_id),
            categoria: categoria,
            descripcion: descripcion.trim(),
            cumplida: cumplida,
            fecha_revision: fecha_revision || null
        });

        alert('¡Item de retrospectiva guardado exitosamente!');
        document.getElementById('form-retrospectiva').reset();

        if (typeof mostrarVista === 'function') {
            mostrarVista('historial');
        }

        return response.data;

    } catch (error) {
        console.error('Error al guardar la retrospectiva:', error);

        if (error.response) {
            if (error.response.status === 422) {
                const errors = error.response.data.errors;
                let errorMessage = 'Errores de validación:\n';
                for (const key in errors) {
                    errorMessage += `- ${errors[key].join(', ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert(`Error del servidor: ${error.response.data.message || 'Por favor, intente nuevamente.'}`);
            }
        } else {
            alert('Error de conexión. Por favor, verifica tu conexión a internet.');
        }
    }
}

function mostrarCamposAdicionales() {
    const categoria = document.getElementById('categoria').value;
    const campos = document.getElementById('campos-adicionales');

    if (categoria !== '') {
        campos.style.display = 'block';
    } else {
        campos.style.display = 'none';
    }
}

function irAComentarios(sprintId) {
    mostrarVista('crear-retro-item');

    setTimeout(() => {
        const select = document.getElementById('sprint_id');
        if (select) {
            select.value = sprintId;
        }
    }, 500);
}

async function cargarReporteComentarios() {
    try {
        const sprints = await fetchSprints();
        const contenedor = document.getElementById('reporte-comentarios');
        contenedor.innerHTML = '';

        if (!sprints.length) {
            contenedor.innerHTML = '<p>No hay sprints registrados.</p>';
            return;
        }

        for (const sprint of sprints) {
            const response = await axios.get(`sprints/${sprint.id}/retro-items`);
            const items = response.data;

            const bloque = document.createElement('div');
            bloque.classList.add('card');
            bloque.innerHTML = `<h3>${sprint.nombre}</h3>`;

            // Botón para crear nuevo sprint basado en este
            const btnCrearNuevoSprint = document.createElement('button');
            btnCrearNuevoSprint.textContent = '➕ Crear nuevo sprint basado en este';
            btnCrearNuevoSprint.classList.add('btn', 'btn-secondary');
            btnCrearNuevoSprint.addEventListener('click', () => {
                abrirFormularioNuevoSprint(sprint);
            });
            bloque.appendChild(btnCrearNuevoSprint);

            if (!items.length) {
                bloque.innerHTML += '<p>No hay comentarios registrados para este sprint.</p>';
            } else {
                const lista = document.createElement('ul');
                items.forEach(item => {
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <strong>Categoría:</strong> ${item.categoria}<br>
                        <strong>Descripción:</strong> ${item.descripcion}<br>
                        ${item.fecha_revision ? `<strong>Revisión:</strong> ${item.fecha_revision}<br>` : ''}
                        <strong>Estado:</strong> 
                        <button class="btn-estado" data-id="${item.id}" data-cumplida="${item.cumplida ? 1 : 0}">
                            ${item.cumplida ? '✅ Cumplida' : '❌ No cumplida'}
                        </button>
                    `;
                    lista.appendChild(li);
                });
                bloque.appendChild(lista);
            }

            contenedor.appendChild(bloque);
        }

        contenedor.querySelectorAll('.btn-estado').forEach(button => {
            button.addEventListener('click', async (e) => {
                const btn = e.target;
                const id = btn.getAttribute('data-id');
                const cumplidaActual = btn.getAttribute('data-cumplida') === '1';

                try {
                    await axios.patch(`retro-items/${id}/toggle-cumplida`);

                    if (cumplidaActual) {
                        btn.textContent = '❌ No cumplida';
                        btn.setAttribute('data-cumplida', '0');
                    } else {
                        btn.textContent = '✅ Cumplida';
                        btn.setAttribute('data-cumplida', '1');
                    }
                } catch (error) {
                    console.error('Error al actualizar estado:', error);
                    alert('Error al actualizar el estado del ítem.');
                }
            });
        });

    } catch (error) {
        console.error('Error al cargar reporte de comentarios:', error);
        alert('Error al cargar reporte de comentarios.');
    }
}

function abrirFormularioNuevoSprint(sprintBase) {
    mostrarVista('crear-retro-item');

    document.getElementById('nombre').value = sprintBase.nombre + ' - copia';
    document.getElementById('fecha_inicio').value = sprintBase.fecha_inicio;
    document.getElementById('fecha_fin').value = sprintBase.fecha_fin;


}

