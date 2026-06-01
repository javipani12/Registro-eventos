const tbodyParticipantes = document.getElementById('tabla-participantes-body');
const inputBusqueda = document.getElementById('busqueda');
const botonBuscar = document.getElementById('btn-buscar');
const botonRecargar = document.getElementById('btn-recargar');

const modalidades = {
	desarrollo_web: 'Desarrollo Web',
	administracion_sistemas: 'Administración de Sistemas',
	desarrollo_movil: 'Desarrollo Móvil',
};

function mostrarMensaje(mensaje) {
	tbodyParticipantes.innerHTML = `
		<tr>
			<td colspan="11">${mensaje}</td>
		</tr>
	`;
}

function crearCelda(texto) {
	const celda = document.createElement('td');
	celda.textContent = texto;
	return celda;
}

function pintarParticipantes(participantes) {
	tbodyParticipantes.innerHTML = '';

	if (participantes.length === 0) {
		mostrarMensaje('No hay participantes para mostrar.');
		return;
	}

	participantes.forEach((participante) => {
		const fila = document.createElement('tr');

		fila.appendChild(crearCelda(participante.nif));
		fila.appendChild(crearCelda(participante.nombre));
		fila.appendChild(crearCelda(participante.apellidos));
		fila.appendChild(crearCelda(participante.edad));
		fila.appendChild(crearCelda(participante.domicilio));
		fila.appendChild(crearCelda(participante.poblacion));
		fila.appendChild(crearCelda(participante.provincia));
		fila.appendChild(crearCelda(participante.codigo_postal));
		fila.appendChild(crearCelda(participante.telefono));
		fila.appendChild(crearCelda(participante.email));
		fila.appendChild(crearCelda(modalidades[participante.modalidad] || participante.modalidad));

		tbodyParticipantes.appendChild(fila);
	});
}

async function cargarParticipantes() {
	const busqueda = inputBusqueda.value.trim();
	const url = busqueda !== ''
		? `./php/listar_participante.php?q=${encodeURIComponent(busqueda)}`
		: './php/listar_participante.php';

	mostrarMensaje('Cargando participantes...');

	try {
		const respuesta = await fetch(url);
		const datos = await respuesta.json();

		if (!respuesta.ok || !datos.ok) {
			throw new Error(datos.mensaje || 'No se ha podido cargar el listado.');
		}

		pintarParticipantes(datos.participantes || []);
	} catch {
		mostrarMensaje('No se ha podido cargar el listado de participantes.');
	}
}

botonBuscar.addEventListener('click', cargarParticipantes);

botonRecargar.addEventListener('click', () => {
	window.location.reload();
});

inputBusqueda.addEventListener('keydown', (evento) => {
	if (evento.key === 'Enter') {
		evento.preventDefault();
		cargarParticipantes();
	}
});

cargarParticipantes();