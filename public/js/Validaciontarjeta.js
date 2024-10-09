document.addEventListener("DOMContentLoaded", function () {
    const tarjetaInput = document.getElementById("Tarjeta");
    const expiracionInput = document.getElementById("Expiracion");
    const codigoSegInput = document.getElementById("codigoseg");
    const nombreTarjetaInput = document.getElementById("nombretarjeta");
    const numeroCuenta = document.getElementById("Cuenta");
    const botonConfirmar = document.getElementById("confirmarPago");

    
    

    function mostrarError(input, mensaje) {
        const errorText = input.parentElement.querySelector(".error-text");
        if (errorText) {
            errorText.textContent = mensaje; // Reemplaza el mensaje 
        } else {
            const nuevoError = document.createElement("small");
            nuevoError.className = "error-text";
            nuevoError.textContent = mensaje;
            input.parentElement.appendChild(nuevoError); //  nuevo mensaje de error
        }
        input.classList.add("error"); // Aplica la clase de error
    }

    function removeError(input) {
        const errorText = input.parentElement.querySelector(".error-text");
        if (errorText) {
            errorText.textContent = ''; // Borra el texto de error
        }
        input.classList.remove("error"); // Remueve la clase de error
    }


    
    function validarTarjeta() {
        tarjetaInput.addEventListener("input", function () {
            let value = tarjetaInput.value.replace(/\D/g, ''); 
            tarjetaInput.value = value.replace(/(\d{4})(?=\d)/g, '$1 '); 
        });

        tarjetaInput.addEventListener("blur", function () {
            if (tarjetaInput.value.replace(/\s/g, '').length !== 8) {
                mostrarError(tarjetaInput, "Número de tarjeta inválido");
            } else {
                removeError(tarjetaInput);
            }
        });
    }

    function validarExpiracion() {
        expiracionInput.addEventListener("input", function () {
            let value = expiracionInput.value.replace(/\D/g, '');
            expiracionInput.value = value.length >= 4 ? value.slice(0, 2) + '/' + value.slice(2, 4) : value;
        });

        expiracionInput.addEventListener("blur", function () {
            const [mes, año] = expiracionInput.value.split('/');
            const fechaActual = new Date();
            const mesActual = fechaActual.getMonth() + 1;
            const añoActual = fechaActual.getFullYear() % 100;

            if (!mes || !año || mes < 1 || mes > 12 || año < añoActual || (año === añoActual && mes < mesActual)) {
                mostrarError(expiracionInput, "Fecha de expiración inválida");
            } else {
                removeError(expiracionInput);
            }
        });
    }

    function validarCodigoSeg() {
        codigoSegInput.addEventListener("input", function () {
            codigoSegInput.value = codigoSegInput.value.replace(/\D/g, '').slice(0, 3); 
        });

        codigoSegInput.addEventListener("blur", function () {
            if (codigoSegInput.value.length !== 3) {
                mostrarError(codigoSegInput, "Código de seguridad inválido");
            } else {
                removeError(codigoSegInput);
            }
        });
    }

    function validarNombre() {
        nombreTarjetaInput.addEventListener("blur", function () {
            if (nombreTarjetaInput.value.trim() === '') {
                mostrarError(nombreTarjetaInput, "El nombre no puede estar vacío");
            } else {
                removeError(nombreTarjetaInput);
            }
        });
    }

    function validarCamposVacios() {
        let valido = true;

        if (tarjetaInput.value.trim() === '') {
            mostrarError(tarjetaInput, "Este campo es obligatorio");
            valido = false;
        } else {
            removeError(tarjetaInput);
        }

        if (expiracionInput.value.trim() === '') {
            mostrarError(expiracionInput, "Este campo es obligatorio");
            valido = false;
        } else {
            removeError(expiracionInput);
        }

        if (codigoSegInput.value.trim() === '') {
            mostrarError(codigoSegInput, "Este campo es obligatorio");
            valido = false;
        } else {
            removeError(codigoSegInput);
        }

        if (nombreTarjetaInput.value.trim() === '') {
            mostrarError(nombreTarjetaInput, "Este campo es obligatorio");
            valido = false;
        } else {
            removeError(nombreTarjetaInput);
        }

        return valido;
    }


    botonConfirmar.addEventListener("click", function(e) {
        e.preventDefault(); 
        if (validarCamposVacios()) {
            
            console.log("Campos válidos, procesar pago...");
            
        } else {
            console.log("Existen campos vacíos o inválidos.");
        }
    });

    validarTarjeta();
    validarExpiracion();
    validarCodigoSeg();
    validarNombre();
});
