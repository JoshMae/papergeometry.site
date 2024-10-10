@extends('layouts.app')

@section('title', 'Ordenar')

@section('content')

<style>
    select {
            padding: 10px;
            border: 2px solid #ff00ff;
            border-radius: 5px;
            background-color: #ffffff;
            font-size: 16px;
            color: #333;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        select:focus {
            border-color: #ff00ff;
            outline: none;
        }

        option {
            padding: 10px;
        }

        /* Estilos para el spinner */
        .loader {
    position: relative;
    width: 164px;
    height: 164px;
    display: none; /* Ocultar por defecto */
  }
  .loader::before , .loader::after {
    content: '';
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #00aeff;
    left: 50%;
    top: 50%;
    animation: rotate 1s ease-in infinite;
}
.loader::after {
  width: 20px;
  height: 20px;
  background-color: #fffb00;
  animation: rotate 1s ease-in infinite, moveY 1s ease-in infinite ;
}

@keyframes moveY {
  0% , 100% {top: 10%}
  45% , 55% {top: 59%}
  60% {top: 40%}
}
@keyframes rotate {
  0% { transform: translate(-50%, -100%) rotate(0deg) scale(1 , 1)}
  25%{ transform: translate(-50%, 0%) rotate(180deg) scale(1 , 1)}
  45% , 55%{ transform: translate(-50%, 100%) rotate(180deg) scale(3 , 0.5)}
  60%{ transform: translate(-50%, 100%) rotate(180deg) scale(1, 1)}
  75%{ transform: translate(-50%, 0%) rotate(270deg) scale(1 , 1)}
  100%{ transform: translate(-50%, -100%) rotate(360deg) scale(1 , 1)}
}
    
/* Estilos para el fondo del spinner */
.spinner-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.486); /* Fondo semitransparente */
    display: none; /* Ocultar por defecto */
    z-index: 999; /* Asegurar que esté detrás del spinner */
}

</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('css/Ordenar.css') }}">

<h1>Confirmar Orden</h1>

<div class="flex-container"> 
    
    <form id="pagoForm" autocomplete="off">
        @csrf
        <input type="hidden" id='token' name="token" value="{{ $cart_token }}">
        <div class="nuevo-bloque">
            <h2>Datos del cliente</h2>
            <div class="contenedor-datos-cliente">
                <input type="text" id="nombres" name="nombres" placeholder="Nombre" required>
                <small id="nombre-error" class="error-text"></small>
                <input type="text" id="apellidos" name="apellidos" placeholder="Apellido" required>
                <small id="apellido-error" class="error-text"></small>
                <input type="tel" name="telefono" id="telefono" placeholder="Número de teléfono" required>       
                <small id="tel-error" class="error-text"></small>         
                <input type="correo" id="correo" name="correo" placeholder="Ingresa tu correo electrónico" required>
                <small id="correo-error" class="error-text"></small>
            </div>  
        </div>
        <label for="metodo_pago">Selecciona el método de pago:</label>
        <select id="metodo_pago" name="metodo_pago" required>
            <option value="">-- Selecciona --</option>
            <option value="transaccion">Metodo de Pago</option>
        </select>
          
        <div id="formTransaccion" class="pago-opcion" style="display: none;">
            <h3>Información de Pago</h3>
            <div class="contenedor-transaccion">
                <input type="text" id="cuenta" name="cuenta" placeholder="Ingresa tu cuenta" >  
                <small id="cuenta-error" class="error-text"></small>
                <input type="hidden" name="valor" id="totalCompra" value="{{ number_format($totalCarrito, 2, '.', '') }}">
                
                <button type="submit">Realizar Pago</button>
            </div>
        </div>

        <div id="formTarjeta" class="pago-opcion" style="display: none;">
            <h5>Información de Pago</h5>
            <div class="contener-tarjeta">
                <div class="input-grupo">
                    <input type="text" id="Tarjeta" name="tarjeta" placeholder="Ingresa la tarjeta" >
                   {{--  <small id="tarjeta-error" class="error-text"></small> --}}
                </div>
                <div class="flex-row">
                    <div class="campo-exp-codigo">
                        <input type="text" id="Expiracion" name="expiracion" placeholder="MM/AA" >
                        <small id="expiracion-error" class="error-text"></small>
                    </div>
                    <div class="campo-codigo">
                        <input type="text" name="codigo_seg" id="codigoseg" placeholder="Código seguridad" >
                        <small id="codigoseg-error" class="error-text"></small>
                    </div>
                </div>
                <div class="input-grupo">
                    <input type="text" name="nombre_tarjeta" id="nombretarjeta" placeholder="Nombre de Titular" >
                    <small id="nombretarjeta-error" class="error-text"></small>
                </div>
                
                <input type="hidden" id="totalCompra" name="total" value="{{ number_format($totalCarrito, 2, '.', '') }}">
                <input type="hidden" id="detallesPedido" name="detalles">

                <button id="confirmarPago">Confirmar Pago</button>
                <div id="resultadoTransaccion" class="mt-4 alert" style="display: none;"></div>
            </div>
        </div>
    </form>

    <span class="loader" id="spinner"></span>
    <div class="spinner-overlay" id="spinnerOverlay"></div> <!-- Fondo del spinner -->
    {{-- <div id="spinner" class="spinner"></div> <!-- Spinner aquí --> --}}
    

    <div class="container-resumen">
        <div class="resumen">
            <h3>Resumen de Pedido</h3>
            @foreach($productosEnCarrito as $producto)
            <div class="producto-item" data-idProducto="{{ $producto['id'] }}">
                <div class="imagen-contenedor">
                    <img src="{{ $producto['foto'] }}" alt="{{ $producto['nombre'] }}" class="producto-imagen">
                    <p class="cantidad-badge">{{ $producto['cantidad'] }}</p>
                </div>
                <div class="producto-info">
                    <p class="producto-nombre">{{ $producto['nombre'] }}</p>
                    <p class="producto-subtotal">Q{{ number_format($producto['precio'] * $producto['cantidad'], 2) }}</p>
                </div>
            </div>
            @endforeach
            <div class="producto-final">
                <div class="subtotal-row">
                    <label>Subtotal:</label>
                    <label>Q{{ number_format($subtotal, 2) }}</label>
                </div>
                <div class="total-row">
                    <label>Total:</label>
                    <label>Q{{ number_format($totalCarrito, 2) }}</label>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/Validaciontarjeta.js') }}"></script>
<script src="{{ asset('js/Validaciontransaccion.js') }}"></script>
{{-- 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
    document.getElementById('metodo_pago').addEventListener('change', function() {
    const metodo = this.value;
    const formTarjeta = document.getElementById('formTarjeta');
    const formTransaccion = document.getElementById('formTransaccion');

    // Ocultar ambos formularios inicialmente
    formTarjeta.style.display = 'none';
    formTransaccion.style.display = 'none';

    // Limpiar errores al cambiar el método de pago
    limpiarMensajesDeError();
    resetearEstilosInputs();

    // Mostrar el formulario correspondiente
    if (metodo === 'tarjeta') {
        formTarjeta.style.display = 'block';
    } else if (metodo === 'transaccion') {
        formTransaccion.style.display = 'block';
    }
});

document.getElementById('pagoForm').addEventListener('submit', function(e) {
    e.preventDefault(); 
    
    document.getElementById('spinnerOverlay').style.display = 'block'; // Mostrar fondo
    // Mostrar el spinner al iniciar la solicitud
    document.getElementById('spinner').style.display = 'block';

    if (!validarDatosCliente()) {
        document.getElementById('spinnerOverlay').style.display = 'none'; // Ocultar fondo
        document.getElementById('spinner').style.display = 'none'; // Ocultar spinner si no pasa la validación
        return;
    }

    const formData = new FormData(this);
    const data = {
        cuenta: formData.get('cuenta'),
        terminal: '1',
        valor: formData.get('valor'),
        empresa: 'PaperGeometry'
    };

    // Obtener el token CSRF desde el meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/api-proxy/cobropos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.text();
    })
    .then(result => {
        const resultadoElement = document.getElementById('resultadoTransaccion');
        resultadoElement.style.display = 'block';
        console.log(result);

        if (result === '1') {
            resultadoElement.className = 'mt-4 alert alert-success';
            resultadoElement.textContent = 'Transacción aprobada';
            registrarCompra();
        } else if (result === '0') {
            resultadoElement.className = 'mt-4 alert alert-danger';
            resultadoElement.textContent = 'Transacción rechazada';
            alert('Transacción rechazada');
        } else {
            resultadoElement.className = 'mt-4 alert alert-warning';
            resultadoElement.textContent = 'Respuesta inesperada: ' + result;
            alert('Respuesta inesperada: ' + result);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const resultadoElement = document.getElementById('resultadoTransaccion');
        resultadoElement.style.display = 'block';
        resultadoElement.className = 'mt-4 alert alert-danger';
        resultadoElement.textContent = 'Error al procesar la transacción: ' + error.message;
    })
    .finally(() => {
        document.getElementById('spinnerOverlay').style.display = 'none'; // Ocultar fondo
        // Ocultar el spinner al finalizar la solicitud
        document.getElementById('spinner').style.display = 'none';
    });
});

function registrarCompra() {
    // Recopilar datos del cliente y detalles del pedido
    const formData = new FormData(document.getElementById('pagoForm'));
    const clienteData = {
        nombres: formData.get('nombres'),
        token:formData.get('token'),
        apellidos: formData.get('apellidos'),
        telefono: formData.get('telefono'),
        correo: formData.get('correo'),
        total: document.querySelector('.total-row label:last-child').textContent.replace('Q', ''),
        detalles: obtenerDetallesPedido()
    };

    // Obtener el token CSRF desde el meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Llamada a la API para registrar la compra
    fetch('http://papergeometry.site/api/registrar-compra', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(clienteData)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Compra registrada exitosamente');
            window.location.href= '/estado_orden';
        } else {
            alert('Error al registrar la compra: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al registrar la compra');
    });
}

// Función para obtener los detalles del pedido
function obtenerDetallesPedido() {
    const productos = [];
    const items = document.querySelectorAll('.producto-item');
    items.forEach(item => {
        const idProducto = item.getAttribute('data-idProducto');
        const cantidad = item.querySelector('.cantidad-badge').textContent;
        const subTotal = item.querySelector('.producto-subtotal').textContent.replace('Q', '');

        productos.push({
            idProducto: idProducto,
            cantidad: cantidad,
            subTotal: subTotal
        });
    });
    return productos;
}


    

</script>

@endsection
