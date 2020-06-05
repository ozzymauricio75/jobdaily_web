    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

 
    function removerItems(boton) {
        $(boton).parents('tr').remove();
    }

    function removerTable(boton) {
        $("#listaItemsIncapacidad tbody").remove();
        //$('#codigo_sucursal').removeAttr('disabled');
        $('#selector1').removeAttr('disabled');
    }

    function removerTableModificar(boton) {
        $("#listaItemsIncapacidad tbody").remove();
        $('#codigo_transaccion_tiempo').removeAttr('disabled');
        $('#codigo_motivo_incapacidad').removeAttr('disabled');
        $('#fecha_reporte_incapacidad').removeAttr('disabled');
        $('#fecha_inicial_incapacidad').removeAttr('disabled');
        $('#codigo_anexo_contable').removeAttr('disabled');
        $('#codigo_auxiliar_contable').removeAttr('disabled');
        $('#dias_incapacidad').removeAttr('disabled');
        $('#numero_incapacidad').removeAttr('disabled');
        $("#botonEliminar").attr("style","display : none;");
        $("#botonAgregar").attr("style","display : inline;");
    }

    function pegarValor(campo){
        $("#codigo_sucursal2").val($(campo).val());
        $("#selector1").val('');
        $("#documento_aspirante").val('');
        if($(campo).val()==0){
            $("#selector1").attr("disabled","disabled");
        }else{
            $('#selector1').removeAttr('disabled');
        }
        verificarAnexosEnTransacciones();
    }

    function acLocalEmpleados(item) {
        var destino  = $('#URLFormulario').val();
        var sucursal = $('#codigo_sucursal').val();


        $(item).autocomplete(destino, {
            minChars: 3,
            width: 250,
            max: 100,
            delay: 100,
            matchContains: true,
            cacheLength: 100,
            extraParams: {
                verificar: true,
                codigo_sucursal: sucursal
            },
            formatResult: function(datos, valor) {
                return valor.split("|")[0];
            }
        }).blur(function() {
            var x = $(this).attr('id');
            $(x).autocomplete().remove();
            $('input#selector1').flushCache();
        }).result(function(evento, datos, formateado) {
            if (datos) {
                $('#documento_aspirante').val(datos[1]);
                $('#codigo_sucursal').val(sucursal)
            } else {
                $('#documento_aspirante').val('');
            }
        });
    }

    function recargarListaAuxiliares(origen, elemento) {
        var destino = $('#URLFormulario').val();
        var valor   = $('#'+origen).val();
        var lista   = '';
        $('#'+elemento).empty();

        /*** Enviar datos para la recarga ***/
        $.getJSON(destino, {recargar_auxiliares: true, origen: valor, elemento: elemento}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#'+elemento).html(lista);
        });
    }

    function verificarAnexosEnTransacciones(){
        var destino             = $('#URLFormulario').val();
        var documento_identidad = $('#documento_aspirante').val();
        var codigo_transaccion  = $('#codigo_transaccion_tiempo').val();
        var codigo_sucursal     = $('#codigo_sucursal2').val();
        if(documento_identidad!=""){
            $.getJSON(destino,{verificaAnexos: true, empleado : documento_identidad, transaccion : codigo_transaccion, sucursal : codigo_sucursal}, function(datos){
                if(datos){
                    if(datos==1){
                        $('#codigo_anexo_contable').hide();
                        $('#codigo_auxiliar_contable').hide();
                    }else if(datos==2){
                        $('#codigo_anexo_contable').hide();
                        $('#codigo_auxiliar_contable').hide();
                    }else if(datos==3){
                        $('#codigo_anexo_contable').show();
                        $('#codigo_auxiliar_contable').show();
                    }
                    $('#estado_anexo').val(datos);
                }
            });
        }else{
            $('#codigo_anexo_contable').hide();
            $('#codigo_auxiliar_contable').hide();
            $('#codigo_transaccion_tiempo').val('');
        }
    }

    function removerTable(boton){
        $("#listaItemsTiempo tbody").remove();
        $('#selector1').removeAttr('disabled','disabled');
        //$('#codigo_sucursal').removeAttr('disabled','disabled');
    }

    function adicionarTiempos(){
        var destino             = $('#URLFormulario').val();
        var existe              = false;
        var campos_vacio        = false;
        var motivo_incapacidad  = $('#codigo_motivo_tiempo option:selected').text();
        var codigo_incapacidad  = $('#codigo_motivo_tiempo').val();
        var tipo_transaccion    = $('#codigo_transaccion_tiempo option:selected').text();
        var codigo_transaccion  = $('#codigo_transaccion_tiempo').val();
        var fecha_inicio        = $("#fecha_inicial").val();
        var fecha_dias          = $("#dias_no_laborados").val();
        var documento_identidad = $("#documento_aspirante").val();
        var codigo_sucursal     = $("#codigo_sucursal2").val();
        var mesaje              = $("#mensaje_campos_vacios").val();
        var id_modulo           = $("#id_modulo").val();

       if(campoVacio(documento_identidad)){
            mesaje       += $("#mensaje_vacio_documento_empleado").val();
            campos_vacio = true;
       }
       if(campoVacio(fecha_dias)){
            mesaje      += " - "+$("#mensaje_vacio_dias").val()+" \n ";
            campos_vacio = true;
       }
       if(parseInt(codigo_transaccion) == 0){
            mesaje       += " - "+$("#mensaje_tipo_transaccion").val()+" \n ";
            campos_vacio = true;
       }

       if(!campos_vacio){

           $.getJSON(destino,{generarTiempos: true,id_modulo : id_modulo ,fecha_inicio : fecha_inicio, fecha_dias : fecha_dias , documento_identidad : documento_identidad,codigo_sucursal:codigo_sucursal}, function(datos){

                var existe_base_datos = datos[0];
                var periodo_contable  = datos[1];

                for(i=3;i<datos.length;i++){
                    $('#listaItemsTiempo').find('.fechas_tiempo').each(function () {
                        id = $(this).val();
                        if(datos[i]==id){
                            existe=true;
                        }
                    });
                }

                    
                    if(!existe && existe_base_datos){
                        for(i=3;i<datos.length;i++){

                            var identificador = new Date();
                            var valorClase = 'even';

                            if ($("#listaItemsTiempo tr:last").hasClass("even")) {
                                valorClase = 'odd';
                            }

                            var item  = '<tr id="'+identificador+'" class="'+valorClase+'">'+
                                '<td align="center">'+
                                '<input type="hidden" class="fechas_tiempo" name="fechas_tiempo[]" value="'+datos[i]+'">'+
                                '<input type="hidden" class="codigo_transaccion" name="codigo_transaccion[]" value="'+codigo_transaccion+'">'+
                                '<input type="hidden" class="motivo_no_laborado" name="motivo_no_laborado[]" value="'+codigo_incapacidad+'">'+
                                '<input type="hidden" class="codigo_anexo_contable_oculto" name="codigo_anexo_contable_oculto[]" value="'+$("#codigo_anexo_contable").val()+'">'+
                                '<input type="hidden" class="codigo_auxiliar_contable_oculto" name="codigo_auxiliar_contable_oculto[]" value="'+$("#codigo_auxiliar_contable").val()+'">'+
                                '<input type="hidden" class="fecha_inicio" name="fecha_inicio[]" value="'+fecha_inicio+'">'+
                                '</td>'+
                                '<td align="center">'+datos[i]+'</td>'+
                                '<td align="left">'+tipo_transaccion+'</td>'+
                                '<td align="center">'+motivo_incapacidad+'</td>'+
                            '</tr>';

                            $('#listaItemsTiempo').append(item);
                            $('#selector1').attr('disabled','disabled');
                            $('#codigo_sucursal').attr('disabled','disabled');
                            $('#codigo_transaccion_tiempo').val('0');
                        }
                        if(datos[2]!=""){
                            alert(datos[2]);
                        }
                    }else{
                        alert($("#mensaje_fechas_repetidas").val());
                    }

                    
                

           });

       }else{
           alert(mesaje);
       }
        
    }

    function removerItemsModificar(boton) {

        var destino             = $('#URLFormulario').val();
        var documento_identidad = $("#documento_aspirante").val();
        var fecha_inicial       = $("#fecha_incial_oculto").val();
        var codigo_sucursal     = $("#codigo_sucursal2").val();

        $.getJSON(destino,{
            opcion : "1",
            eliminar_base_datos: true,
            documento_identidad:documento_identidad,
            fecha_inicial:fecha_inicial,
            codigo_sucursal:codigo_sucursal
        },function(datos){
             if(datos[0]=='1'){
                var borrar_tiempo = confirm($("#mensaje_confimacion").val());
                if(borrar_tiempo){
                    confirmacionEliminar();
                }
             }else{
                $("#listaItemsTiempo tbody").remove();
             }
        });

    }

    function confirmacionEliminar(){

        var destino             = $('#URLFormulario').val();
        var documento_identidad = $("#documento_aspirante").val();
        var fecha_inicial       = $("#fecha_incial_oculto").val();
        var codigo_sucursal     = $("#codigo_sucursal2").val();
        
        $.getJSON(destino,{
            opcion : "0",
            eliminar_base_datos: true,
            documento_identidad:documento_identidad,
            fecha_inicial:fecha_inicial,
            codigo_sucursal:codigo_sucursal
        },function(datos){
             if(datos[0]=='1'){
                $("#listaItemsTiempo tbody").remove();
                alert(datos[1]);
             }else{
                alert(datos[1]);
             }
        });
    }
function verificarPeriodoContable()
{
    var destino         = $('#URLFormulario').val();
    var codigo_sucursal = $("#codigo_sucursal").val();
    var fecha_inicio    = $("#fecha_inicial").val();
    var id_modulo       = $("#id_modulo").val();

    $.getJSON(destino,{
        verificar_periodo_contable: true,
        codigo_sucursal:codigo_sucursal,
        fecha_inicio:fecha_inicio,
        id_modulo:id_modulo
    },function(datos){
        if(datos[0]=="1"){
            adicionarTiempos();
        }else{
            alert(datos[1]);
        }
    });
}
