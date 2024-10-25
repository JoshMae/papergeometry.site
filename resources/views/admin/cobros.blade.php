<div class="container">
    <h1 class="text-center mb-4">Pedidos Realizados</h1>

    
   <!-- Contenedor para los filtros -->
<div class="filters-container">
    <!-- Filtro por fecha -->
    <div class="filter-item">
        <label for="fecha-filtro">Filtrar por día:</label>
        <input type="date" id="fecha-filtro" class="form-control" onchange="cargarPedidos()">
    </div>

    <!-- Buscador por nombre o ID -->
    <div class="filter-item">
        <label for="buscador">Buscar por nombre o ID de pedido:</label>
        <input type="text" id="buscador" class="form-control" onkeyup="buscarPedido()">
    </div>
</div>

    <!-- Total obtenido -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Total Obtenido</h5>
            <p id="total-obtenido" class="card-text">Q. 0.00</p>
        </div>
    </div>

    <!-- Tabla de pedidos -->
    <div class="table-responsive mb-4">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID Pedido</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody id="pedidos-table">
                <!-- Los pedidos se insertarán aquí dinámicamente -->
            </tbody>
        </table>
    </div>
    
    <!-- Contenedor de paginación -->
    <div class="pagination-container text-center">
        <button id="prevPage" class="btn btn-primary" onclick="prevPage()">Anterior</button>
        <span id="pageInfo"></span>
        <button id="nextPage" class="btn btn-primary" onclick="nextPage()">Siguiente</button>
    </div>
</div>

<script>
    let currentPage = 1;
    const itemsPerPage = 5;
    let pedidosFiltrados = [];
    let pedidosOriginal = [];

    function cargarPedidos() {
        const fechaFiltro = document.getElementById('fecha-filtro').value;

        fetch('/pedidos-json')
            .then(response => response.json())
            .then(data => {
                pedidosOriginal = data;
                aplicarFiltros();
            })
            .catch(error => console.error('Error:', error));
    }

    function aplicarFiltros() {
        const fechaFiltro = document.getElementById('fecha-filtro').value;
        const buscador = document.getElementById('buscador').value.toLowerCase();

        pedidosFiltrados = pedidosOriginal.filter(pedido => {
            const fecha = new Date(pedido.created_at).toISOString().slice(0, 10);
            const nombreCliente = `${pedido.cliente.nombres} ${pedido.cliente.apellidos}`.toLowerCase();
            const idPedido = pedido.idPedido.toString();

            const coincideFecha = !fechaFiltro || fecha === fechaFiltro;
            const coincideBusqueda = !buscador || nombreCliente.includes(buscador) || idPedido.includes(buscador);

            return coincideFecha && coincideBusqueda;
        });

        calcularTotalObtenido();
        mostrarPedidos();
    }

    function calcularTotalObtenido() {
        const totalObtenido = pedidosFiltrados.reduce((total, pedido) => total + parseFloat(pedido.total), 0);
        document.getElementById('total-obtenido').textContent = `Q. ${totalObtenido.toFixed(2)}`;
    }

    function mostrarPedidos() {
        const pedidosTable = document.getElementById('pedidos-table');
        pedidosTable.innerHTML = '';

        const start = (currentPage - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pedidosPagina = pedidosFiltrados.slice(start, end);

        if (pedidosPagina.length === 0) {
            pedidosTable.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron pedidos.</td></tr>';
        } else {
            pedidosPagina.forEach(pedido => {
                const row = `<tr>
                    <td>${pedido.idPedido}</td>
                    <td>${pedido.cliente.nombres} ${pedido.cliente.apellidos}</td>
                    <td>${pedido.estado_pedido.nombre_estado}</td>
                    <td>Q. ${pedido.total}</td>
                    <td>${new Date(pedido.created_at).toLocaleDateString()}</td>
                </tr>`;
                pedidosTable.innerHTML += row;
            });
        }

        actualizarPaginacion();
    }

    function actualizarPaginacion() {
        document.getElementById('pageInfo').textContent = `Página ${currentPage} de ${Math.ceil(pedidosFiltrados.length / itemsPerPage)}`;
        document.getElementById('prevPage').disabled = currentPage === 1;
        document.getElementById('nextPage').disabled = currentPage === Math.ceil(pedidosFiltrados.length / itemsPerPage);
    }

    function buscarPedido() {
        currentPage = 1;
        aplicarFiltros();
    }

    function prevPage() {
        if (currentPage > 1) {
            currentPage--;
            mostrarPedidos();
        }
    }

    function nextPage() {
        if (currentPage < Math.ceil(pedidosFiltrados.length / itemsPerPage)) {
            currentPage++;
            mostrarPedidos();
        }
    }

    cargarPedidos();
</script>

<style>
    

    .card {
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background-color: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    .table th {
        background-color: #1e40af;
        color: white;
        font-weight: 600;
        padding: 1rem;
        text-align: left;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f5f9;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-primary {
        background-color: #1e40af;
        color: white;
        border: none;
    }

    .btn-primary:hover:not(:disabled) {
        background-color: #1e3a8a;
    }

    .btn-primary:disabled {
        background-color: #94a3b8;
        cursor: not-allowed;
    }

    .pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }

    #pageInfo {
        font-weight: 500;
        color: #64748b;
    }

    #total-obtenido {
        font-size: 2rem;
        font-weight: 700;
        color: #1e40af;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 2rem;
        text-align: center;
    }

    label {
        font-weight: 500;
        color: #64748b;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Estilos para el contenedor de filtros */
.filters-container {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.filter-item {
    flex: 1;
    min-width: 250px; /* Evita que los inputs sean demasiado estrechos */
    max-width: calc(50% - 1rem); /* Asegura que no sean demasiado anchos */
}

.filter-item label {
    display: block;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 0.5rem;
}

.filter-item .form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    transition: border-color 0.2s;
}

.filter-item .form-control:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

    @media (max-width: 640px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .btn {
            padding: 0.5rem 1rem;
        }

        h1 {
            font-size: 2rem;
        }
    }
</style>