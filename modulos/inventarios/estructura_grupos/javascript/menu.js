    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    /*** Desactivar o activar todos los elementos del árbol si se selecciona el campo 'Grupo principal' ***/
    function bloquearArbol() {
        if ($('#bloqueArbol :radio').hasClass("campoInactivo")) {
            $('#bloqueArbol :radio').removeClass("campoInactivo").removeAttr("disabled");
        } else {
            $('#bloqueArbol :radio').addClass("campoInactivo").attr("disabled","disabled");
        }
    }

    /*** Adicionar combinaciones de teclas para el manejo de botones***/
    $(document).bind('keydown', 'Ctrl+a', function(evt) {$('#ADICESGR').click(); return false;});
    $(document).bind('keydown', 'Ctrl+c', function(evt) {$('#CONSESGR').click(); return false;});
    $(document).bind('keydown', 'Ctrl+m', function(evt) {$('#MODIESGR').click(); return false;});
    $(document).bind('keydown', 'Ctrl+e', function(evt) {$('#ELIMESGR').click(); return false;});


    /*** Validar si existe la descripcion para un grupo***/
    function validarDescripcion(){
        var destino     = $('#URLFormulario').val();
        var padre       = $('#padre').val();
        var descripcion = $('#descripcion').val();
        var id          = $('#id').val();
        
        if(id != ""){
            $.getJSON(destino, {verificarNombre:true, descripcion:descripcion, padre:padre, id:id}, function(mensaje){
                if(mensaje != ""){
                    $('#descripcion').parent().children('#errorDialogo').remove();
                    $('#descripcion').focus();
                    $('#descripcion').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $('#descripcion').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            });
        
        } else{
            $.getJSON(destino, {verificarNombre:true, descripcion:descripcion, padre:padre, id:id}, function(mensaje){
                if(mensaje != ""){
                    $('#descripcion').parent().children('#errorDialogo').remove();
                    $('#descripcion').focus();
                    $('#descripcion').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $('#descripcion').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            });
        }
    }
