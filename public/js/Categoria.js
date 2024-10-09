document.addEventListener("DOMContentLoaded", function() {
    const botonCategorias = document.getElementById('boton-categoria');
    const menuCategorias = document.getElementById('menu-categorias');


   // Mostrar el menú cuando el mouse está sobre el botón
   botonCategorias.addEventListener('mouseenter', function() {
    menuCategorias.classList.add('mostrar'); // Añade la clase 'mostrar' para visualizar el menú
    cargarCategorias(); // Carga las categorías dinámicamente si es necesario
});

// Ocultar el menú cuando el mouse sale del menú o del botón
botonCategorias.addEventListener('mouseleave', function() {
    setTimeout(() => {
        // Solo cierra si el mouse no está sobre el menú
        if (!menuCategorias.matches(':hover')) {
            menuCategorias.classList.remove('mostrar'); // Oculta el menú si el mouse no está sobre el menú
        }
    }, 100); // Breve retardo para permitir que el mouse pase al menú sin cerrarlo
});

// Mantener el menú visible cuando el mouse está sobre el menú
menuCategorias.addEventListener('mouseenter', function() {
    menuCategorias.classList.add('mostrar'); // Mantiene visible el menú
});

// Ocultar el menú cuando el mouse sale del menú
menuCategorias.addEventListener('mouseleave', function() {
    menuCategorias.classList.remove('mostrar'); // Oculta el menú cuando el mouse sale del menú
});
});

function cargarCategorias() {
    fetch('/Categorias')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            const menuCategorias = document.getElementById('menu-categorias');
            const ul = menuCategorias.querySelector('ul');
            ul.innerHTML = ''; // Limpia el contenido previo

            data.forEach(categoria => {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = `/categoria/${categoria.idCategoria}`; // Enlace correcto
                a.textContent = categoria.categoria; // Nombre de la categoría
                li.appendChild(a);
                ul.appendChild(li);
            });
        })
        .catch(error => console.error('ERROR AL CARGAR CATEGORIAS', error));
}
