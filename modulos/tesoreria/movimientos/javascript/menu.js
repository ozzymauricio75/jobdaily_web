    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function cargarCuenta() {
        var destino     = $('#URLFormulario').val();
        var cuenta      = $('#selector3').val();

        $.getJSON(destino, {cargarCuenta: true, cuenta: cuenta}, function(datos) {
            if(datos!=""){
                $('#banco').val(datos[0]);
                $('#tercero').val(datos[1]);
            }else{
                alert('No existen datos con ese numero de cuenta');
                $('#selector3').focus();
                $('#selector3').val('');
                $('#banco').val('');
                $('#tercero').val('');
            }
        });
    }

    function cargarCuentaProveedor() {
        var destino         = $('#URLFormulario').val();
        var nit_proveedor   = $('#selector4').val();
         var lista          = '';

        $.getJSON(destino, {cargarCuentaProveedor: true, nit_proveedor: nit_proveedor}, function(datos) {
            jQuery.each(datos,function(valor, descripcion){
                if(valor=="0"){
                    alert(descripcion);
                    lista = lista+'<option value="">'+''+'</option>';
                }else{
                    lista = lista+'<option value="'+valor+'">'+descripcion+'</option>';
                }
            });
            $('#cuenta_destino').html(lista);
        });
    }

    function formatoMiles(input){
        var num = input.value.replace(/\./g,'');
        if(!isNaN(num)){
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/,'');
            input.value = num;
        } else{ 
            alert('Solo se permiten numeros');
            input.value = input.value.replace(/[^\d\.]*/g,'');
        }
    }

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

    function verificarConceptos(){
        var destino      = $('#URLFormulario').val();
        var codigo_grupo = $('#codigo_grupo').val();
        var lista        = '';

        $.getJSON(destino,{recargar_conceptos:true, codigo_grupo: codigo_grupo},function(datos){
            jQuery.each(datos,function(valor, descripcion){
                if(valor=="0"){
                    alert(descripcion);
                    lista = lista+'<option value="">'+''+'</option>';
                }else{
                lista = lista+'<option value="'+valor+'">'+descripcion+'</option>';
                }
            });
            $('#codigo_concepto').html(lista);
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
