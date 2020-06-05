    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(valor,selector){
        if (selector=='selector2'){
            var valor = $('#selector2').val().split("-");
            valor = borrarEspacios(valor[1]);
            $('#descripcion').val(valor);
        }
    }    

    function validarCodigo(item){

        var destino  = $('#URLFormulario').val();
        var codigo   = $(item).val();

        var vector = $('#id_municipio').val().split(",");
        var codigo_dane_minicipio = vector[2];
        var codigo_dian = $('#codigo_dian').val();

        if(codigo != ""){
            $.getJSON(destino, {verificarCodigo:true, codigo : codigo , municipio : codigo_dane_minicipio , codigo_dian : codigo_dian}, function(mensaje){
                if(mensaje != ""){
                    $(item).parent().children('#errorDialogo').remove();
                    $(item).focus();
                    $(item).parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $(item).parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');

                    //$('#codigo_actividad_municipio').removeAttr("disabled");


                }
            });

        } /*else{
            $.getJSON(destino, {verificarCodigo:true, codigo:codigo, id_sucursal:sucursal, id:id}, function(mensaje){
                if(mensaje != ""){
                    $('#codigo').parent().children('#errorDialogo').remove();
                    $('#codigo').focus();
                    $('#codigo').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $('#codigo').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            });
        }*/

    }
