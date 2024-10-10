
document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('sidebar');
    var sidebarCollapse = document.getElementById('sidebarCollapse');

    sidebarCollapse.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });

    // Inicializar tooltips de Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

document.querySelectorAll('#sidebar a').forEach(function(element) {
    element.addEventListener('click', function() {
        bootstrap.Tooltip.getInstance(this).hide(); // Ocultar tooltip del elemento clickeado
    });
});

// Función para cargar el contenido dinámicamente
function cargarVista(vista) {
    const url = `${baseUrl}/cargar-vista/${vista}`;
    
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
            $('#contenido-dinamico').html(`
                <div class="spinner-container">
                    <div class="loader">
                </div>
            `);  
        },
        success: function(response) {
            $('#contenido-dinamico').html(response);
            
            localStorage.setItem('vistaActual', vista);
            
            actualizarSidebarActivo(vista);
        },
        error: function(xhr, status) {
            $('#contenido-dinamico').html('<p>Error al cargar el contenido.</p>');
        }
    });
}

function actualizarSidebarActivo(vista) {
    $('.list-unstyled.components li').removeClass('active');
    $(`.list-unstyled.components a[data-vista="${vista}"]`).parent('li').addClass('active');
}

// Evento para manejar los clics en los enlaces del sidebar
$(document).ready(function() {
    $('.list-unstyled.components a').on('click', function(e) {
        e.preventDefault();
        const vista = $(this).data('vista');
        cargarVista(vista);
    });

    // Cargar la última vista al iniciar la página
    const ultimaVista = localStorage.getItem('vistaActual');
    if (ultimaVista) {
        cargarVista(ultimaVista);
    } else {
        // Si no hay vista guardada, cargar la vista de inicio por defecto
        cargarVista('inicio');
    }
});