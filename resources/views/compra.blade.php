@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Compra</h2>
    <form id="compraForm" method="POST" action="{{ url('/api/registrar-compra') }}">
        @csrf
        <div class="form-group">
            <label for="nombres">Nombres del Cliente:</label>
            <input type="text" class="form-control" id="nombres" name="nombres" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos del Cliente:</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" required>
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono del Cliente:</label>
            <input type="text" class="form-control" id="telefono" name="telefono" required>
        </div>

        <div class="form-group">
            <label for="correo">Correo del Cliente:</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>

        <div class="form-group">
            <label for="total">Total de la Compra:</label>
            <input type="number" step="0.01" class="form-control" id="total" name="total" required>
        </div>

        <div class="form-group">
            <label for="respuesta">Respuesta del Pago (prueba):</label>
            <input type="number" class="form-control" id="respuesta" name="respuesta" required>
        </div>

        <div id="detalle-section">
            <h4>Detalles del Pedido</h4>
            <div class="detalle" data-index="0">
                <div class="form-group">
                    <label for="idProducto">ID del Producto:</label>
                    <input type="number" class="form-control" name="detalles[0][idProducto]" required>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" class="form-control" name="detalles[0][cantidad]" required>
                </div>

                <div class="form-group">
                    <label for="subTotal">Subtotal:</label>
                    <input type="number" step="0.01" class="form-control" name="detalles[0][subTotal]" required>
                </div>
            </div>
        </div>

        <button type="button" id="agregarDetalle" class="btn btn-secondary">Agregar Detalle</button>
        <button type="submit" class="btn btn-primary">Registrar Compra</button>
    </form>
</div>

<script>
    let detalleIndex = 1;

    document.getElementById('agregarDetalle').addEventListener('click', function() {
        const detalleSection = document.getElementById('detalle-section');

        let newDetalle = `
        <div class="detalle" data-index="${detalleIndex}">
            <hr>
            <div class="form-group">
                <label for="idProducto">ID del Producto:</label>
                <input type="number" class="form-control" name="detalles[${detalleIndex}][idProducto]" required>
            </div>

            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" class="form-control" name="detalles[${detalleIndex}][cantidad]" required>
            </div>

            <div class="form-group">
                <label for="subTotal">Subtotal:</label>
                <input type="number" step="0.01" class="form-control" name="detalles[${detalleIndex}][subTotal]" required>
            </div>
        </div>
        `;

        detalleSection.insertAdjacentHTML('beforeend', newDetalle);
        detalleIndex++;
    });

    // Captura el formulario y maneja la solicitud AJAX si prefieres no recargar la página
    document.getElementById('compraForm').addEventListener('submit', function(event) {
        event.preventDefault();

        let formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Compra registrada exitosamente');
            } else {
                alert('Error al registrar la compra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
@endsection
