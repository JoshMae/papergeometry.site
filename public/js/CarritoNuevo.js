$(document).ready(function() {
    // Interceptar el envío del formulario
    $('.form-agregar-carrito').on('submit', function(e) {
        e.preventDefault(); // Evitar el refresco de la página

        // Obtener los datos del formulario
        var formData = $(this).serialize();

        // Enviar la solicitud AJAX
        $.ajax({
            url: $(this).attr('action'), // Usar la acción del formulario
            method: "POST",
            data: formData,
            success: function(response) {
                $('#mensaje-flotante').fadeIn();

                // Ocultar el mensaje después de 2 segundos
                setTimeout(function() {
                    $('#mensaje-flotante').fadeOut();
                }, 2000);
            },
            error: function(xhr) {
                $('#mensaje-flotante').fadeIn().text('Hubo un error al agregar el producto al carrito.');

                setTimeout(function() {
                    $('#mensaje-flotante').fadeOut();
                }, 2000);
            }
        });
    });

    
});
