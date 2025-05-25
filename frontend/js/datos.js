
axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
axios.defaults.headers.common['Accept'] = 'application/json';

async function fetchRetroItems() {
    try {
        const response = await axios.get('/retro-items');
        console.log('Datos obtenidos:', response.data);
        return response.data;
    } catch (error) {
        console.error('Error en la solicitud:', error.response?.data || error.message);
        throw error;
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
        }
    } else {
        console.warn(`La vista con id "${vista}" no existe.`);
    }
}

async function loadHistorial() {
    try {
        const data = await fetchRetroItems();
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
                <h3>Sprint ${item.sprint_id}</h3>
                <p><strong>Descripción:</strong> ${item.descripcion}</p>
                <p><strong>Categoría:</strong> ${item.categoria}</p>
                <button class="btn btn-danger" onclick="eliminarRetrospectiva(${item.id})">🗑️ Eliminar</button>
            `;
            fragment.appendChild(card);
        });

        historial.appendChild(fragment);
    } catch (error) {
        console.error('Error al cargar el historial:', error);
        alert('Error al cargar el historial.');
    }
}

async function guardarRetrospectiva(event) {
    event.preventDefault();

    const sprint = document.getElementById('sprint').value;
    const positivos = document.getElementById('positivos').value;
    const negativos = document.getElementById('negativos').value;
    const acciones = document.getElementById('acciones').value;

    if (!sprint) {
        alert('Por favor, ingresa el número del sprint.');
        return;
    }

    try {
        const descripcion = `✅ Positivos: ${positivos || 'Sin datos'}\n❌ Negativos: ${negativos || 'Sin datos'}\n🎯 Acciones: ${acciones || 'Sin datos'}`;

        await axios.post('/retro-items', {
            sprint_id: parseInt(sprint),
            descripcion: descripcion.trim(),
            categoria: 'accion'
        });

        alert('¡Retrospectiva creada exitosamente!');
        document.getElementById('form-retrospectiva').reset();
        mostrarVista('historial');
    } catch (error) {
        console.error('Error al guardar:', error.response?.data || error.message);
        alert('Hubo un error al guardar la retrospectiva.');
    }
}

async function eliminarRetrospectiva(id) {
    if (confirm('¿Estás seguro de eliminar esta retrospectiva?')) {
        try {
            await axios.delete(`/retro-items/${id}`);
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

document.addEventListener('DOMContentLoaded', () => {
    mostrarVista('inicio');
});
