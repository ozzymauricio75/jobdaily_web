    $(document).ready(function() {
        ejecutarFuncionesGlobales();
        $('#usuario').focus();

        /*** Procesar el formulario cuando se presione la tecla ENTER ***/
        $('#usuario, #contrasena, #sucursal').keypress(function (tecla) {
            if (tecla.which == 13) {
                procesarFormulario();
            }
        });

    });

    function procesarFormulario() {

        $('#mensajeBarraEstado').html('');
        $('#mensajeBarraEstado').removeAttr('class','style');
        $('#mensajeBarraEstado').css('display','block');
        var destino1 = $('#URL1').val();
        var destino2 = $('#URL2').val();

        $.post(destino1, {botonAceptar:true, usuario:$('#usuario').val(), contrasena:$('#contrasena').val(), sucursal:$('#sucursal').val()}, function(mensaje) {
            /*** Error de autenticación, presentar el mensaje devuelto por el servidor ***/
            if (mensaje) {
                $('#mensajeBarraEstado').html(mensaje).fadeOut(3000).addClass('mensajeError');

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
