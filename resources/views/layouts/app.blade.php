
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('css/Bienvenida.css') }}">
    <link rel="stylesheet" href="{{ asset('css/NuevoCarrio.css') }}"> 
    
    @stack('styles')
</head>
<body>
    
    <!-- Header común -->
    <header>
        
        <div class="Logo">
            <a href="{{ url('/') }}">
            <img src="{{ asset('Img/7-Photoroom.png') }}" alt="Logo">
        </a>
        </div>
        <nav class="barranavegadora">
            <ul>
                <li class="espacio"><a href="{{ url('/') }}">Inicio</a></li>
                <li class="espacio"><a href="">Ofertas</a></li>
                <li class="espacio"><a href="">Productos</a></li>
                <li class="espacio"><a href="">Nosotros</a></li>
            </ul>
        </nav>
        
        <div class="BarraSearch">
            <input type="search" class="busqueda" placeholder="Buscar productos..." aria-label="Buscar productos">
            <button type="submit">
                <i class="fas fa-search"></i> 
            </button>

            <div class="Carrito">
                <a href="{{ url('/nuevo-carrito') }}">
                    <button class="carrito-button" id="boton-carrito">
                        <i class="fas fa-shopping-cart icono-carrito"></i>
                    </button>
                </a>
            </div>
            
        </div>
    </header>
    
    <div>
        
        <button id="boton-categoria">
            <i class="fas fa-bars"></i> Categorías
            <div id="menu-categorias" class="Menu-Categorias">
                <ul class="categorias">
                    <!-- Las categorías dinámicas se cargarán aquí -->
                </ul>
            </div>
        </button>
        
        
    </div>
    
    <main>
        @yield('content')
    </main>

    <!-- Footer común -->
    <footer style="background-color: #333; color: #fff; padding: 20px 0; text-align: center;">
        <div>
            <p>&copy; 2024 Paper Geometry. Todos los derechos reservados.</p>
        </div>
        <div class="politicas">
            <p><a href="#" style="color: #fff">Política de Privacidad</a> | <a href="#" style="color: #fff">Términos y Condiciones</a></p>
        </div>
    </footer>

    <script src="{{ asset('js/Categoria.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    
    <!-- Aquí podrías incluir scripts generales -->
    
</body>
</html>
