const form = document.querySelector('.formulario-registro');
const edadInput = document.getElementById('edad');
const botonRestarEdad = document.getElementById('boton_restar_edad');
const botonSumarEdad = document.getElementById('boton_sumar_edad');
const mensajeFormulario = document.getElementById('mensaje-formulario');

// Elementos del formulario para validar y mostrar errores.
const campos = {
	nif: document.getElementById('nif'),
	nombre: document.getElementById('nombre'),
	apellidos: document.getElementById('apellidos'),
	edad: edadInput,
	domicilio: document.getElementById('domicilio'),
	poblacion: document.getElementById('poblacion'),
	provincia: document.getElementById('provincia'),
	cp: document.getElementById('cp'),
	telefono: document.getElementById('telefono'),
	email: document.getElementById('email'),
	modalidad: document.getElementById('modalidad'),
};

// Elementos para mostrar los mensajes de error debajo de cada campo.
const errores = {
	nif: document.getElementById('error-nif'),
	nombre: document.getElementById('error-nombre'),
	apellidos: document.getElementById('error-apellidos'),
	edad: document.getElementById('error-edad'),
	domicilio: document.getElementById('error-domicilio'),
	poblacion: document.getElementById('error-poblacion'),
	provincia: document.getElementById('error-provincia'),
	cp: document.getElementById('error-cp'),
	telefono: document.getElementById('error-telefono'),
	email: document.getElementById('error-email'),
	modalidad: document.getElementById('error-modalidad'),
};

// Expresiones regulares para comprobar los datos del formulario.
const expresiones = {
	nif: /^\d{8}[A-Za-z]$/,
	edad: /^-?\d+$/,
	cp: /^(10|06)\d{3}$/,
	telefono: /^\d{9}$/,
	email: /^[A-Za-z0-9._%+-]+@educarex\.es$/,
};

const mensajeInicial = mensajeFormulario.textContent.trim();

edadInput.value = '19';

function mostrarError(campo, mensaje) {
	errores[campo].textContent = mensaje;
}

function limpiarError(campo) {
	errores[campo].textContent = '';
}

// Limpia todos los mensajes de error antes de volver a validar.
function limpiarTodosLosErrores() {
	for (const campo in errores) {
		limpiarError(campo);
	}
}

function validarTextoObligatorio(campo, nombreCampo) {
	const valor = campos[campo].value.trim();

	if (valor === '') {
		mostrarError(campo, `El campo ${nombreCampo} es obligatorio.`);
		return false;
	}

	limpiarError(campo);
	return true;
}

function validarNif() {
	const valor = campos.nif.value.trim();

	if (valor === '') {
		mostrarError('nif', 'El NIF es obligatorio.');
		return false;
	}

	if (!expresiones.nif.test(valor)) {
		mostrarError('nif', 'El NIF debe tener 8 dígitos y una letra.');
		return false;
	}

	limpiarError('nif');
	return true;
}

function validarEdad() {
	const valor = campos.edad.value.trim();

	if (valor === '') {
		mostrarError('edad', 'La edad es obligatoria.');
		return false;
	}

	if (!expresiones.edad.test(valor)) {
		mostrarError('edad', 'La edad debe ser un número entero.');
		return false;
	}

	const numero = Number(valor);

	if (numero < 0) {
		mostrarError('edad', 'La edad no puede ser negativa.');
		return false;
	}

	if (numero < 19 || numero > 21) {
		mostrarError('edad', 'La edad debe estar entre 19 y 21.');
		return false;
	}

	limpiarError('edad');
	campos.edad.value = String(numero);
	return true;
}

function validarCp() {
	const valor = campos.cp.value.trim();

	if (valor === '') {
		mostrarError('cp', 'El código postal es obligatorio.');
		return false;
	}

	if (!expresiones.cp.test(valor)) {
		mostrarError('cp', 'El código postal debe empezar por 10 o 06 y tener 5 dígitos.');
		return false;
	}

	limpiarError('cp');
	return true;
}

function validarTelefono() {
	const valor = campos.telefono.value.trim();

	if (valor === '') {
		mostrarError('telefono', 'El teléfono es obligatorio.');
		return false;
	}

	if (!expresiones.telefono.test(valor)) {
		mostrarError('telefono', 'El teléfono debe contener 9 dígitos.');
		return false;
	}

	limpiarError('telefono');
	return true;
}

function validarEmail() {
	const valor = campos.email.value.trim();

	if (valor === '') {
		mostrarError('email', 'El email es obligatorio.');
		return false;
	}

	if (!expresiones.email.test(valor)) {
		mostrarError('email', 'El email debe pertenecer al dominio educarex.es.');
		return false;
	}

	limpiarError('email');
	return true;
}

function validarModalidad() {
	const valor = campos.modalidad.value.trim();

	if (valor === '') {
		mostrarError('modalidad', 'Debes seleccionar una modalidad.');
		return false;
	}

	limpiarError('modalidad');
	return true;
}

function validarEdadPorBoton(desplazamiento) {
	const valor = campos.edad.value.trim();

	if (valor === '') {
		mostrarError('edad', 'La edad no puede estar vacía.');
		return false;
	}

	if (!expresiones.edad.test(valor)) {
		mostrarError('edad', 'La edad debe ser un número entero.');
		return false;
	}

	const numero = Number(valor);

	if (numero < 0) {
		mostrarError('edad', 'La edad no puede ser negativa.');
		return false;
	}

	const nuevoValor = numero + desplazamiento;

	if (nuevoValor < 19 || nuevoValor > 21) {
		mostrarError('edad', 'La edad debe estar entre 19 y 21.');
		return false;
	}

	campos.edad.value = String(nuevoValor);
	limpiarError('edad');
	return true;
}

// Los botones suman o restan un año respetando el rango permitido.
botonRestarEdad.addEventListener('click', () => {
	validarEdadPorBoton(-1);
});

botonSumarEdad.addEventListener('click', () => {
	validarEdadPorBoton(1);
});

campos.edad.addEventListener('input', () => {
	if (campos.edad.value.trim() === '') {
		limpiarError('edad');
		return;
	}

	validarEdad();
});

form.addEventListener('reset', () => {
	campos.edad.value = '19';
	limpiarTodosLosErrores();
	mensajeFormulario.className = 'mensaje_formulario';
	mensajeFormulario.textContent = mensajeInicial;
});

form.addEventListener('submit', (evento) => {
	// Guardamos el resultado de cada validación para comprobar si todo está correcto.
	const validaciones = [
		['nif', validarNif()],
		['nombre', validarTextoObligatorio('nombre', 'nombre')],
		['apellidos', validarTextoObligatorio('apellidos', 'apellidos')],
		['edad', validarEdad()],
		['domicilio', validarTextoObligatorio('domicilio', 'domicilio')],
		['poblacion', validarTextoObligatorio('poblacion', 'población')],
		['provincia', validarTextoObligatorio('provincia', 'provincia')],
		['cp', validarCp()],
		['telefono', validarTelefono()],
		['email', validarEmail()],
		['modalidad', validarModalidad()],
	];

	let primerCampoIncorrecto = '';

	for (let i = 0; i < validaciones.length; i++) {
		if (validaciones[i][1] === false) {
			primerCampoIncorrecto = validaciones[i][0];
			break;
		}
	}

	if (primerCampoIncorrecto !== '') {
		evento.preventDefault();
		campos[primerCampoIncorrecto].focus();
		mensajeFormulario.className = 'mensaje_formulario';
		mensajeFormulario.textContent = mensajeInicial;
		return;
	}

	evento.preventDefault();

	fetch(form.action, {
		method: 'POST',
		body: new FormData(form),
		headers: {
			Accept: 'application/json',
		},
	})
	.then((respuesta) => respuesta.json().then((datos) => ({
		ok: respuesta.ok,
		status: respuesta.status,
		datos,
	})))
	.then(({ ok, status, datos }) => {
		if (!ok) {
			throw new Error(datos.mensaje || `Error al registrar el participante. (${status})`);
		}

		form.reset();
		mensajeFormulario.className = 'mensaje_formulario mensaje_formulario_exito';
		mensajeFormulario.textContent = datos.mensaje || 'El participante se ha registrado correctamente.';
	})
	.catch(() => {
		mensajeFormulario.className = 'mensaje_formulario';
		mensajeFormulario.textContent = 'No se ha podido registrar el participante. Revisa los datos e inténtalo de nuevo.';
	});
});

limpiarTodosLosErrores();