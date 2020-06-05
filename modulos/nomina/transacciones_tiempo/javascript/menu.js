    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(){
    }

    function cargarConceptos(){
       var destino       = $('#URLFormulario').val();
       var lista         = "";
       var tipo_concepto = $('#tipo_concepto').val();

       $.getJSON(destino,{verificarConceptos: true, tipo_concepto:tipo_concepto}, function(datos){
            if(datos != ""){

                if (tipo_concepto==2 || tipo_concepto==4 || tipo_concepto==5){
                    $('#tasa').parent().hide();
                    $('#tasa').addClass('campoInactivo').attr('disabled','disabled');
                    $('#contenedor_mensaje_ayuda').parent().hide();
                    $('#contenedor_mensaje_ayuda').addClass('campoInactivo').attr('disabled','disabled');
                    $('#divisor').parent().hide();
                    $('#divisor').addClass('campoInactivo').attr('disabled','disabled');
                    $('#dividendo').parent().hide();
                    $('#dividendo').addClass('campoInactivo').attr('disabled','disabled');
                } else if(tipo_concepto==3){
                    $('#tasa').parent().hide();
                    $('#tasa').addClass('campoInactivo').attr('disabled','disabled');
                    $('#contenedor_mensaje_ayuda').parent().show();
                    $('#contenedor_mensaje_ayuda').removeClass('campoInactivo').removeAttr('disabled','disabled');
                    $('#divisor').parent().show();
                    $('#divisor').removeClass('campoInactivo').removeAttr('disabled','disabled');
                    $('#dividendo').parent().show();
                    $('#dividendo').removeClass('campoInactivo').removeAttr('disabled','disabled');
                } else {
                    $('#tasa').parent().show();
                    $('#tasa').removeClass('campoInactivo').removeAttr('disabled','disabled');
                    $('#contenedor_mensaje_ayuda').parent().hide();
                    $('#contenedor_mensaje_ayuda').addClass('campoInactivo').attr('disabled','disabled');
                    $('#divisor').parent().hide();
                    $('#divisor').addClass('campoInactivo').attr('disabled','disabled');
                    $('#dividendo').parent().hide();
                    $('#dividendo').addClass('campoInactivo').attr('disabled','disabled');
                }

                jQuery.each(datos, function(id, descripcion) {
                    lista = lista+'<option value="'+id+'">'+descripcion+'</option>';
                });

                $('#codigo_concepto_transaccion_tiempo').html(lista);
            } else{
                $('#tasa').parent().hide();
                $('#tasa').addClass('campoInactivo').attr('disabled','disabled');
                $('#divisor').parent().hide();
                $('#divisor').addClass('campoInactivo').attr('disabled','disabled');
                $('#dividendo').parent().hide();
                $('#dividendo').addClass('campoInactivo').attr('disabled','disabled');
            }
       });
    }

    function activarContinuar(){
        $("#continua").val(1);
    }

    function cargarConceptosListado(){
       var destino       = $('#URLFormulario').val();
       var lista         = '<option value="">""</option>';
       var tipo_concepto = $('#tipo_concepto').val();

       $.getJSON(destino,{verificarConceptos: true, tipo_concepto:tipo_concepto}, function(datos){
            if(datos != ""){
                jQuery.each(datos, function(id, descripcion) {
                    lista = lista+'<option value="'+id+'">'+descripcion+'</option>';
                });

                $('#codigo_concepto_transaccion_tiempo').html(lista);
            }
       });
    }
