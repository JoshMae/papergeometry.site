@extends('layouts.app')

@section('title', 'Carrito')

@section('content')
    <!-- Título de la página del carrito -->
    <h1>Carrito de Compras</h1>

    @if($carritoVacio)
        <p>Tu carrito está vacío</p>
        <div class="boton-de-vacio" id="boton-ver-productos">
            <button>
                <a href="{{ url('/') }}" class="btn btn-primary">Ver Productos</a>
            </button>
        </div>
        
    @else
    <!-- Contenido del carrito -->
    <div class="producto-container">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Nombre</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Eliminar</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="productos-carrito">
                    @foreach($productosEnCarrito as $producto)
                        <tr data-id="{{ $producto['id'] }}">
                            <td><img src="{{ $producto['foto'] }}" alt="{{ $producto['nombre'] }}" width="50"></td>
                            <td>{{ $producto['nombre'] }}</td>
                            <td>Q{{ number_format($producto['precio'], 2) }}</td>
                            <td>
                                <div class="cantidad-controles">
                                    <button class="disminuir-cantidad" data-id="{{ $producto['id'] }}">-</button>
                                    <input type="text" value="{{ $producto['cantidad'] }}" readonly>
                                    <button class="aumentar-cantidad" data-id="{{ $producto['id'] }}">+</button>
                                </div>
                            </td>
                            <td><button class="eliminar-producto" data-id="{{ $producto['id'] }}">X</button></td>
                            <td class="total-producto">Q{{ number_format($producto['precio'] * $producto['cantidad'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-carrito">
            <p>Total Q<span id="total-carrito">{{ $totalCarrito }}</span></p>
        </div>
        <div class="ordenar">
            <a href="{{ url('/ordenar') }}">
            <button class="boton-ordenar">Ordenar</button>
        </a>
        </div>
    </div>
    @endif

        


    <!-- Scripts para manejar AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
           
            // Aumentar cantidad
            $('.aumentar-cantidad').on('click', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: '/nuevo-carrito/aumentar/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        actualizarCarrito(response);
                    }
                });
            });

            // Disminuir cantidad
            $('.disminuir-cantidad').on('click', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: '/nuevo-carrito/disminuir/' + id,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        actualizarCarrito(response);
                    }
                });
            });

            // Eliminar producto
            $('.eliminar-producto').on('click', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: '/nuevo-carrito/eliminar/' + id,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        actualizarCarrito(response);
                    }
                });
            });

            // Función para actualizar el carrito en la vista
            function actualizarCarrito(response) {


     // Limpiar la tabla de productos
    $('#productos-carrito').html('');

    function generarBotonVerProductos() {
    // Clonamos el contenido del div que contiene el botón
    const botonHtml = $('#boton-ver-productos').html();
    
    // Retornamos el botón
    return `<div class="boton-de-vacio">${botonHtml}</div>`;
}
    // Si el carrito está vacío
    if (response.carritoVacio) {
        // Mostrar solo el mensaje de carrito vacío y el botón
        $('#productos-carrito').html('<p>Tu carrito está vacío</p>');
        $('#productos-carrito').append('<button><a href="{{ url("/Bienvenida") }}" class="btn btn-primary">Ver Productos</a></button>');


        // Ocultar el contenedor del carrito
        $('thead').hide();
        $('.total-carrito').hide();
        $('.ordenar').hide();
    } else {
        // Si el carrito tiene productos, los volvemos a agregar a la tabla
        $.each(response.productosEnCarrito, function(index, producto) {
            $('#productos-carrito').append(`
                <tr data-id="${producto.id}">
                    <td><img src="${producto.foto}" alt="${producto.nombre}" width="50"></td>
                    <td>${producto.nombre}</td>
                    <td>Q${producto.precio}</td>
                    <td>
                        <div class="cantidad-controles">
                            <button class="disminuir-cantidad" data-id="${producto.id}">-</button>
                            <input type="text" value="${producto.cantidad}" readonly>
                            <button class="aumentar-cantidad" data-id="${producto.id}">+</button>
                        </div>
                    </td>
                    <td><button class="eliminar-producto" data-id="${producto.id}">X</button></td>
                    <td class="total-producto">Q${producto.precio * producto.cantidad}</td>
                </tr>
            `);
        });

        // Mostrar el total del carrito
        $('#total-carrito').text(response.totalCarrito);

        // Mostrar los encabezados de la tabla y la sección de ordenar
        $('thead').show();
        $('.total-carrito').show();
        $('.ordenar').show();

        // Reasignar eventos a los nuevos botones
        asignarEventos();
    }
}



function asignarEventos() {
    $('.aumentar-cantidad').on('click', function() {
        let id = $(this).data('id');
        $.ajax({
            url: '/nuevo-carrito/aumentar/' + id,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                actualizarCarrito(response);
            }
        });
    });

    $('.disminuir-cantidad').on('click', function() {
        let id = $(this).data('id');
        $.ajax({
            url: '/nuevo-carrito/disminuir/' + id,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                actualizarCarrito(response);
            }
        });
    });

    $('.eliminar-producto').on('click', function() {
        let id = $(this).data('id');
        $.ajax({
            url: '/nuevo-carrito/eliminar/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                actualizarCarrito(response);
            }
        });
    });
}

        });
    </script>
@endsection