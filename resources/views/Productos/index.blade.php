<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        .producto-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .producto {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            width: 250px;
        }
        .producto img {
            max-width: 100%;
            border-radius: 8px;
        }
        .producto h2 {
            font-size: 2em;
            margin: 10px 0;
        }
        .producto p {
            font-size: 1em;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Productos</h1>
    <div class="producto-container">
        @foreach ($productos as $producto)
            <div class="producto">
                <img src="{{ $producto['foto'] }}" alt="{{ $producto['nombre'] }}">
                <h2>{{ $producto['nombre'] }}</h2>
                <p>Precio: Q. {{ $producto['precio'] }}</p>
                <p>Descripción: {{ $producto['detalle'] }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
