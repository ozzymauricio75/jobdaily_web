    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(cuenta) {
        var destino     = $('#URLFormulario').val();
        var lista       = '';
        $.getJSON(destino, {recargarDatosCuenta: true, id: cuenta}, function(datos) {
            jQuery.each(datos, function(id, dato){

                if (id) {
                    lista = lista+'<option value="'+id+'">'+dato+'</option>';
                    $('#auxiliar_contable').removeClass("oculto").parent().show();
                } else {

                    if (isNaN(dato)) {
                        $('#errorDialogo').html('');
                        $('#errorDialogo').removeAttr('class','style');
                        $('#errorDialogo').css('display','block');
                        $('#errorDialogo').html(dato).fadeOut(6000).addClass('mensajeError');

                        $('#selector1').val('').focus();
                        $('#codigo_plan_contable').val('');
                    } else {
                        lista = '<option value="0||0"> </option>';
                    }

                    $('#auxiliar_contable').addClass("oculto").parent().hide();
                }
            });

            $('#auxiliar_contable').html(lista);
        });
    }

    function verificarSucursales(){
        var destino  = $('#URLFormulario').val();
        var id_banco = $('#codigo_banco').val();
        var lista    = '';

        $.getJSON(destino,{recargar_sucursales:true,id_banco:id_banco},function(datos){
            jQuery.each(datos,function(valor, descripcion){
                if(valor=="0"){
                    alert(descripcion);
                    lista = lista+'<option value="">'+''+'</option>';
                }else{
                lista = lista+'<option value="'+valor+'">'+descripcion+'</option>';
                }
            });
            $('#codigo_sucursal_banco').html(lista);
        });
    }

    function verificarSucursalesListado(){
        var destino  = $('#URLFormulario').val();
        var id_banco = $('#codigo_banco').val();
        var lista    = '<option value="0"></option>';

        $.getJSON(destino,{recargar_sucursales:true,id_banco:id_banco},function(datos){
            jQuery.each(datos,function(valor, descripcion){
                lista = lista+'<option value="'+valor+'">'+descripcion+'</option>';
            });
            $('#codigo_sucursal_banco').html(lista);
        });
    }
