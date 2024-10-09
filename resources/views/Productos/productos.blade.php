<link rel="stylesheet" href="{{ asset('css/Productos.css') }}"> 
<div class="producto-container">
    @if (!empty($productos) && count($productos) > 0)
        @foreach ($productos as $producto)
            <div class="producto">
                <img src="{{ $producto['foto'] }}" alt="{{ $producto['nombre'] }}">
                <h3>{{ $producto['nombre'] }}</h3>
                <p class="precio">Precio: Q. {{ $producto['precio'] }}</p>
                <p class="descripcion"> {{ $producto['detalle'] }}</p>
                <!-- Formulario de agregar al carrito -->
                <form class="form-agregar-carrito" action="{{ route('nuevoCarritoAgregar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="producto_id" value="{{ $producto['idProducto'] }}">
                    <input type="hidden" name="nombre" value="{{ $producto['nombre'] }}">
                    <input type="hidden" name="precio" value="{{ $producto['precio'] }}">
                    <input type="hidden" name="foto" value="{{ $producto['foto'] }}">
                    <input type="hidden" name="cantidad" value="1">
                    <button class="btn-agregar-carrito">Agregar al Carrito</button>    
                </form>
            </div>
        @endforeach
    @else
        <p>No hay productos disponibles en este momento.</p>
    @endif
</div>
