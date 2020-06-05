    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    /*** Validar si existe el codigo para una sucursal***/
    function validarCodigo(){
        var destino  = $('#URLFormulario').val();
        var sucursal = $('#sucursal').val();
        var codigo   = $('#codigo').val();
        var id       = $('#id').val();
        
        if(id != ""){
            $.getJSON(destino, {verificarCodigo:true, codigo:codigo, sucursal:sucursal, id:id}, function(mensaje){
                if(mensaje != ""){
                    $('#codigo').parent().children('#errorDialogo').remove();
                    $('#codigo').focus();
                    $('#codigo').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $('#codigo').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            });
        
        } else{
            $.getJSON(destino, {verificarCodigo:true, codigo:codigo, sucursal:sucursal, id:id}, function(mensaje){
                if(mensaje != ""){
                    $('#codigo').parent().children('#errorDialogo').remove();
                    $('#codigo').focus();
                    $('#codigo').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $('#codigo').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            });
        }
    }
    
    /*** Validar si existe la descripcion para una sucursal***/
    function validarNombre(){
        var destino  = $('#URLFormulario').val();
        var sucursal = $('#sucursal').val();
        var nombre   = $('#nombre').val();
        var id       = $('#id').val();
        
        if(id != ""){
            $.getJSON(destino, {verificarNombre:true, nombre:nombre, sucursal:sucursal, id:id}, function(mensaje){
                if(mensaje != ""){
                    $('#nombre').parent().children('#errorDialogo').remove();
                    $('#nombre').focus();
                    $('#nombre').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $('#nombre').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            });
        
        } else{
            $.getJSON(destino, {verificarNombre:true, nombre:nombre, sucursal:sucursal, id:id}, function(mensaje){
                if(mensaje != ""){
                    $('#nombre').parent().children('#errorDialogo').remove();
                    $('#nombre').focus();
                    $('#nombre').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $('#nombre').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            });
        }
    }
