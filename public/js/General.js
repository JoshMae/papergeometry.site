// Inicializa el contador
let contador = 0;

// Selecciona todos los botones "Agregar al Carrito"
const botonesAgregarCarrito = document.querySelectorAll('.btn-agregar-carrito');

// Agrega un evento de clic a cada botón
botonesAgregarCarrito.forEach(boton => {
    boton.addEventListener('click', function(event) {
        event.preventDefault(); // Evita que se envíe el formulario inmediatamente

        // Incrementa el contador
        contador++;
        document.getElementById('contador-carrito').textContent = contador;

        // Aquí podrías agregar la lógica para enviar el formulario si es necesario
        // this.closest('form').submit();
    });
});