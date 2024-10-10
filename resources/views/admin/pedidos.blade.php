{{-- <div class="container">
    <form action="{{ route('pedido.cambiarEstado', $pedido->idPedido) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="estado">Cambiar Estado:</label>
            <select name="idEstado" id="estado" class="form-control">
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}" {{ $pedido->idEstado == $estado->id ? 'selected' : '' }}>
                        {{ $estado->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Estado</button>
    </form>
    
</div> --}}

<style>
    #pedidosTable {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 15px;
        font-family: 'Arial', sans-serif;
    }
    #pedidosTable th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
    }
    #pedidosTable td {
        padding: 12px;
        vertical-align: middle;
        background-color: #ffffff;
        border: none;
        transition: all 0.3s;
    }
    #pedidosTable tr {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 5px;
        transition: all 0.3s;
    }
    #pedidosTable tr:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .estado-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        text-align: center;
        display: inline-block;
        min-width: 100px;
    }
    .btn-abrir {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
        cursor: pointer;
    }
    .btn-abrir:hover {
        background-color: #0056b3;
    }
    #pedidoDetalle {
        margin-top: 20px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
</style>

<div class="container">
    <h1>Pedidos</h1>
    <table id="pedidosTable" class="table table-striped">
        <thead>
            <tr>
                <th>ID Pedido</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos se insertarán aquí dinámicamente -->
        </tbody>
    </table>
    <div id="pedidoDetalle">
        <!-- La vista de detalles del pedido se cargará aquí -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    cargarPedidos();
});

function cargarPedidos() {
    console.log('Cargando pedidos...');

    $.ajax({
        url: '/pedidos',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Datos recibidos:', data);
            const tbody = $('#pedidosTable tbody');
            tbody.empty();

            data.forEach(function(pedido) {
                const estadoInfo = obtenerInfoEstado(pedido.estado);
                const row = `
                    <tr>
                        <td>${pedido.id}</td>
                        <td>${pedido.cliente}</td>
                        <td>
                            <span class="estado-badge" style="background-color: ${estadoInfo.color}; color: ${estadoInfo.textColor};">
                                ${pedido.estado}
                            </span>
                        </td>
                        <td>
                            <button onclick="cargarDetallePedido(${pedido.id})" class="btn-abrir">Abrir</button>
                        </td>
                    </tr>
                `;

                tbody.append(row);
            });
        },
        error: function(error) {
            console.error('Error al cargar los pedidos:', error);
        }
    });
}

function cargarDetallePedido(idPedido) {
    console.log('Cargando vista de detalle del pedido:', idPedido);

    $.ajax({
        url: `/pedidos/${idPedido}/detalle`,
        type: 'GET',
        dataType: 'html',
        success: function(vista) {
            $('#pedidoDetalle').html(vista);
            $('#pedidoDetalle').show();
            $('#pedidosTable').hide();
            // Ahora que la vista está cargada, iniciamos la carga de datos
            cargarDatosPedido(idPedido);
        },
        error: function(error) {
            console.error('Error al cargar la vista de detalle del pedido:', error);
            $('#pedidoDetalle').html('<p>Error al cargar la vista de detalle del pedido.</p>');
            $('#pedidoDetalle').show();
        }
    });
}

function obtenerInfoEstado(estado) {
    const estados = {
        'recibido': { color: '#FFE4E1', textColor: '#D8000C' },
        'imprimiendo': { color: '#FFF5E6', textColor: '#FF8C00' },
        'recortando': { color: '#FFFACD', textColor: '#DAA520' },
        'armando': { color: '#FFFFD4', textColor: '#8B8B00' },
        'finalizado': { color: '#E6FFE6', textColor: '#006400' }
    };

    return estados[estado.toLowerCase()] || { color: '#F0F0F0', textColor: '#333333' };
}
</script>