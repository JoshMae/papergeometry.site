@extends('layouts.app')

@section('title', $categoria->categoria)

@section('content')

<link rel="stylesheet" href="{{ asset('css/Productos.css') }}"> 

<h1>Categoría: {{ $categoria->categoria }}</h1>
<div class="producto-container">
    @foreach ($productos as $producto)
        <div class="producto">
            <img src="{{ asset($producto->foto) }}" alt="{{ $producto->nombreP }}">
            <h3>{{ $producto->nombreP }}</h3>
            <p class="precio">Precio: Q. {{ number_format($producto->precio, 2) }}</p>
            <p class="descripcion">{{ $producto->detalleP }}</p>
            <form class="form-agregar-carrito" action="{{ route('nuevoCarritoAgregar') }}" method="POST">
                @csrf
                <input type="hidden" name="producto_id" value="{{ $producto->idProducto }}">
                <input type="hidden" name="nombre" value="{{ $producto->nombreP }}">
                <input type="hidden" name="precio" value="{{ $producto->precio }}">
                <input type="hidden" name="foto" value="{{ $producto->foto }}">
                <input type="hidden" name="cantidad" value="1">
                <button class="btn-agregar-carrito">Agregar al Carrito</button>    
            </form>                
        </div>
    @endforeach
    <div class="alerta-flotante" id="mensaje-flotante">
        ¡Producto agregado al carrito con éxito!
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/CarritoNuevo.js') }}"></script>
</div>    


@endsection