<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('css/spinner.css') }}" rel="stylesheet">
    <link href="{{ asset('css/inventario.css') }}" rel="stylesheet"> --}}
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="{{asset('img/7-Photoroom.png')}}" alt="Logo" class="logo">
                {{-- <h3 >Sidebar</h3> --}}
                {{-- <img class="sidebar-title" src="{{asset('img/7-Photoroom.png')}}" alt="TituloPaper" > --}}
                <h5 class="sidebar-title">Paper Geometry</h5>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#" data-vista="inicio" data-bs-toggle="tooltip" data-bs-placement="right" title="Inicio">
                        <i class="bi bi-house-door"></i> <span class="menu-text">Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-vista="pedidos" data-bs-toggle="tooltip" data-bs-placement="right" title="Pedidos">
                        <i class="bi bi-cart"></i> <span class="menu-text">Pedidos</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="#" data-vista="inventario" data-bs-toggle="tooltip" data-bs-placement="right" title="Inventario">
                        <i class="bi bi-box-seam"></i> <span class="menu-text">Inventario</span>
                    </a>
                </li> --}}
                <li>
                    <a href="#" data-vista="cobros" data-bs-toggle="tooltip" data-bs-placement="right" title="Cobros">
                        <i class="bi bi-cash-stack"></i> <span class="menu-text">Cobros</span>
                    </a>                    
                </li>
                <li>
                    <a href="#" data-bs-toggle="tooltip" data-bs-placement="right" title="Reporte de Ventas">
                        <i class="bi bi-graph-up"></i> <span class="menu-text">Ventas</span>
                    </a>                    
                </li>
                {{-- <li>
                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" data-vista="inicio" data-bs-toggle="tooltip" data-bs-placement="right" title="Cerrar Sesión" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bi bi-box-arrow-right"></i> <span class="menu-text">Cerrar Sesión</span>
                        </a>
                    </form>
                </li> --}}
            </ul>
        </nav>


        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid barra">
                    <button type="button" id="sidebarCollapse" class="btn boton" >
                        <i class="bi bi-list ic"></i>
                    </button>
                    <h2 class="ms-3" style="color: white; font-size:1.4em;">¡ Armamos buenos momentos !</h2>
                </div>
            </nav>

            <div class="container container-fluid style-container">
                <div id="contenido-dinamico">
                    <!-- Aquí se cargará el contenido dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <script> var baseUrl = "{{ url('/') }}"; </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html> 