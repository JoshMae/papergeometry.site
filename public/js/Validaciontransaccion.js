// validaciones.js

// Función para limpiar mensajes de error
function limpiarMensajesDeError() {
    const elementosError = document.querySelectorAll('.error-text');
    elementosError.forEach(error => {
        error.textContent = '';
    });
}

// Función para resetear estilos de los inputs
function resetearEstilosInputs() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.classList.remove('input-error'); // Asegúrate de que 'input-error' sea la clase que aplica el estilo de error
    });
}

function mostrarError(input, mensaje) {
    const errorText = input.parentElement.querySelector(".error-text");
    if (errorText) {
        errorText.textContent = mensaje;  
    } else {
        const nuevoError = document.createElement("small");
        nuevoError.className = "error-text";
        nuevoError.textContent = mensaje;
        input.parentElement.appendChild(nuevoError); 
    }
    input.classList.add("error"); 
}


// Validar datos del cliente
function validarDatosCliente() {
    const correo = document.getElementById('correo').value;
    const nombre = document.getElementById('nombres').value;
    const apellido = document.getElementById('apellidos').value;
    const telefono = document.querySelector('input[name="telefono"]').value;
    const correoError = document.getElementById('correo-error');
    const nombreError = document.getElementById('nombre-error');
    const apellidoError = document.getElementById('apellido-error');
    const telError = document.getElementById('tel-error');
    const cuenta = document.getElementById('cuenta').value;
    const cuentaError = document.getElementById('cuenta-error');

    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            // Limpiar mensaje de error
            const errorText = input.parentElement.querySelector(".error-text");
            if (errorText) {
                errorText.textContent = '';
            }
            // Remover estilo de error
            input.classList.remove('input-error');
        });
    });

    let esValido = true;

    // Limpiar errores previos
    limpiarMensajesDeError();
    resetearEstilosInputs(); 

    
    // Validar correo electrónico
    const patronEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!patronEmail.test(correo)) {
        correoError.textContent = 'Correo no válido';
        document.getElementById('correo').classList.add('input-error'); 
        esValido = false;
    }

    // Validar otros campos
    if (nombre.trim() === '') {
        nombreError.textContent = 'Nombre es requerido';
        document.getElementById('nombres').classList.add('input-error'); 
        esValido = false;
    }
    if (apellido.trim() === '') {
        apellidoError.textContent = 'Apellido es requerido';
        document.getElementById('apellidos').classList.add('input-error'); 
        esValido = false;
    }
    if (telefono.trim() === '') {
        telError.textContent = 'Número de teléfono es requerido';
        document.querySelector('input[name="telefono"]').classList.add('input-error'); // Añadir clase de error
        esValido = false;
    }

    if (cuenta.trim() === '') {
        mostrarError(document.getElementById('cuenta'), 'Se requiere número de cuenta');
        esValido = false;
    } else if (cuenta.length < 8) {
        mostrarError(document.getElementById('cuenta'), 'Número de cuenta inválido');
        esValido = false;
    } else if (!/^\d+$/.test(cuenta)) { // Asegura que solo contenga dígitos
        mostrarError(document.getElementById('cuenta'), 'El número de cuenta solo puede contener dígitos');
        esValido = false;
    }


    return esValido;
}
