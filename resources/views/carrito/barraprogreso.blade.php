@extends('layouts.app')

@section('title', 'Barra de Pedido')

@section('content')

<h1>Estado del Pedido: <span id="estado-actual">{{ $pedido->estado_pedido }}</span></h1>

<!-- Barra de progreso -->
<div id="barra-progreso" style="width: 100%; background-color: #e0e0e0; border-radius: 4px; overflow: hidden;">
    <div id="progreso" style="height: 20px; width: 0; background-color: #76c7c0;"></div>
</div>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;

    // Inicializar Pusher
    var pusher = new Pusher('73f643c127e3ce2a5820', {
        cluster: 'us2'
    });

    // Cambia 'pedido-status' por 'pedidos' si es el canal correcto
    var channel = pusher.subscribe('pedidos'); // Cambiado a 'pedidos'

    // Escuchar el evento correcto
    channel.bind('pedido.estado.actualizado', function(data) { // Cambiado a 'pedido.estado.actualizado'
        // Verificar si el ID del pedido coincide
        if (data.idPedido === {{ $pedido->idPedido }}) {
            // Actualizar el estado en la vista del cliente
            $('#estado-actual').text(data.nuevo_estado);

            // Actualizar la barra de progreso
            actualizarBarraProgreso(data.nuevo_estado);
        }
    });

    // Función para actualizar la barra de progreso en base al estado
    function actualizarBarraProgreso(estado) {
        let progreso = $('#progreso');
        
        switch (estado.toLowerCase()) {
            case 'recibido':
                progreso.css('width', '20%');
                break;
            case 'imprimiendo':
                progreso.css('width', '40%');
                break;
            case 'recortando':
                progreso.css('width', '60%');
                break;
            case 'armando':
                progreso.css('width', '80%');
                break;
            case 'finalizado':
                progreso.css('width', '100%');
                break;
            default:
                progreso.css('width', '0%');
                break;
        }
    }
</script>

@endsection
