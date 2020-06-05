    $(document).ready( function() {
        $('#usuario').focus();
    });

    function procesarFormulario() {
        var destino1 = $('#URL1').val();
        var destino2 = $('#URL2').val();

        $.post(destino1, {botonAceptar:true, usuario:$('#usuario').val(), contrasena:$('#contrasena').val()}, function(mensaje) {
            /*** Error de autenticación, presentar el mensaje devuelto por el servidor ***/
            if (mensaje) {
                $('#barraEstado').html(mensaje).addClass('mensajeError');

            /*** Usuario autenticado correctamente, redireccionar a la página de inicio ***/
            } else {
                location = destino2;
            }
        });
    }

    function restaurarFormulario() {
        $('#usuario').val('');
        $('#contrasena').val('');
    };