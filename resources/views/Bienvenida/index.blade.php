<!-- resources/views/Bienvenida/index.blade.php -->
@extends('layouts.app')

@section('title', 'Bienvenida')

@section('content')
    <h1>{{ $mensajebienvendida }}</h1>
    <p>¡Descubre el arte en tus manos, un cubo a la vez!</p>
    <h2>{{ $subproductosenoferta }}</h2>

    <!-- Incluir la vista de productos -->
    @include('Productos.productos', ['productos' => $productos]) <!-- Pasamos la variable $productos -->

    <!-- Mensaje flotante de confirmación -->
    <div class="alerta-flotante" id="mensaje-flotante">
        ¡Producto agregado al carrito con éxito!
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/CarritoNuevo.js') }}"></script>
@endsection


