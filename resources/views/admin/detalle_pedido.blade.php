<div class="container">
    <center><h1>Detalle del Pedido #<span id="pedidoId"></span></h1></center>
    <br>
    <h3>Cliente:</h3>
    <p id="clienteNombre"></p>
    <p id="clienteTelefono"></p>
    <p id="clienteCorreo"></p>
    <br>
    <h3>Estado del Pedido: <span id="estadoPedido"></span></h3>
    <br>
    <h3>Productos:</h3>
    <table id="productosTable" class="table table-striped">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Foto</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los productos se insertarán aquí dinámicamente -->
        </tbody>
    </table>
    <br><br>    
    <button id="siguienteEstadoBtn" class="btn btn-success">Siguiente Estado</button>
</div>

<script>
function cargarDatosPedido(idPedido) {

    $.ajax({
        url: `/pedidos/${idPedido}`,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Datos recibidos:', data);
            
            // Mostrar los datos del cliente
            $('#pedidoId').text(data.idPedido);
            $('#clienteNombre').text(`${data.cliente.nombres} ${data.cliente.apellidos}`);
            $('#clienteTelefono').text(`Teléfono: ${data.cliente.telefono}`);
            $('#clienteCorreo').text(`Correo: ${data.cliente.correo}`);
            
            $('#estadoPedido').text(data.estado_pedido.nombre_estado);
            actualizarEstadoPedido(data.estado_pedido.nombre_estado);
            
            // Mostrar los productos en la tabla
            const tbody = $('#productosTable tbody');
            tbody.empty(); // Limpiar el tbody antes de insertar los productos
            data.pedido_detalles.forEach(function(detalle) {
                const producto = detalle.producto;
                const row = `
                    <tr>
                        <td>${producto.nombreP}</td>
                        <td><img src="${producto.foto}" alt="Imagen del producto" style="width: 100px;"></td>
                        <td>${detalle.cantidad}</td>
                        <td><a href="/descargar/${producto.idProducto}" class="btn btn-info">Descargar</a></td>
                    </tr>
                `;
                tbody.append(row); // Agregar cada producto a la tabla
            });
        },
        error: function(error) {
            console.error('Error al cargar los detalles del pedido:', error);
        }
    });
}

function cambiarEstadoPedido(idPedido) {
    $.ajax({
        url: `/pedidos/${idPedido}/siguiente_estado`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content') // Token CSRF para seguridad
        },
        success: function(response) {
            alert('Estado del pedido actualizado');
            cargarDatosPedido(idPedido); // Recargar los datos del pedido
        },
        error: function(error) {
            console.error('Error al cambiar el estado:', error);
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

// Función para actualizar el estilo del span
function actualizarEstadoPedido(estado) {
    const spanEstado = document.getElementById('estadoPedido');
    const infoEstado = obtenerInfoEstado(estado);
    
    spanEstado.textContent = estado;
    spanEstado.style.backgroundColor = infoEstado.color;
    spanEstado.style.color = infoEstado.textColor;
    spanEstado.style.padding = '2px 6px';
    spanEstado.style.borderRadius = '3px';
}

$(document).ready(function() {
    $('#siguienteEstadoBtn').click(function() {
        const idPedido = $('#pedidoId').text();
        cambiarEstadoPedido(idPedido);
    });
});
</script>

