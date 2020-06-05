$(document).ready(function(){
    ejecutarFuncionesGlobales();
});

function recargarDatos(valor,id){
    //if(id == "selector")
    console.log(valor);
    console.log(id);
}

function acLocalEmpleados(item) {
    var destino  = $('#URLFormulario').val();
    var sucursal = $('#codigo_sucursal').val();
    var id       = $(item).attr("id");
    $(item).autocomplete(destino, {
        minChars: 3,
        width: 250,
        max: 100,
        delay: 100,

        matchContains: true,
        cacheLength:100,
        extraParams: {
            verificar: true,
            item     : id ,
            codigo_sucursal: sucursal
        },
        formatResult: function(datos, valor) {
            return valor.split("|")[0];
        }
    }).blur(function() {
        var x = $(this).attr('id');
        $(x).autocomplete().remove();
        if(borrarEspacios($("#selector1").val())==''){
            $('#selector2').attr('disabled','disabled');
        }
    }).result(function(evento, datos, formateado) {
        //console.log(datos[1]);
        if (datos) {
             if(id=='selector1'){
                 $('#documento_identidad_empleado').val(datos[1]);
                 $('#selector2').removeAttr('disabled');

                 var destino             = $('#URLFormulario').val();
                 var documento_identidad = datos[1];
                 $.getJSON(destino,{cargar_planilla: true,documento_identidad : documento_identidad}, function(val){
                    $('#codigo_planilla option[@value='+val+']').attr('selected', 'selected');
                    //$('#codigo_planilla').val(val);
                    cargarFechaPago2();
                 });

             }else{
                 $('#transaccion_contable').val(datos[1]);
                 verificarAnexosEnTransacciones();
             }
        } else {
             if(id=='selector1'){
                 $('#documento_identidad_empleado').val('');
                 $('#selector2').attr('disabled','disabled');
             }else{
                 $('#transaccion_contable').val(datos[1]);

             }
        }
    });

}

function removerItem(boton) {
    $(boton).parents('tr').remove();
    var numero_fila = document.getElementById("novedades_manuales").rows.length;
    if(numero_fila == 1 )
        {
            $('#codigo_sucursal').removeAttr('disabled');
            $('#selector1').removeAttr('disabled');
            $('#ano_generacion').removeAttr('disabled');
            $('#mes_generacion').removeAttr('disabled');
            $('#fecha_pago').removeAttr('disabled');
        }
}

function removerTable() {
    $("#listaItemsExtras tbody").remove();
}

function limpiarCampos()
{
    $("#selector1").val("");
    $("#documento_identidad_empleado").val("");
    $("#fechas").val("");
    $("#turno_laboral_1").val("");
    $("#turno_laboral_2").val("");
    $(".autorizarExtra").attr('checked', false);
    $("#contenedor_turno_laboral_1").addClass("oculto");
    $("#contenedor_turno_laboral_1").parent().hide();
    $("#contenedor_turno_laboral_2").addClass("oculto");
    $("#contenedor_turno_laboral_2").parent().hide();
    removerTable();
}

function verificarAnexosEnTransacciones(){
    var destino             = $('#URLFormulario').val();
    var documento_identidad = $('#documento_identidad_empleado').val();
    var codigo_transaccion  = $('#transaccion_contable').val();
    var codigo_sucursal     = $('#codigo_sucursal').val();
    var selector            = $('#selector2').val();

    if(documento_identidad){
        $.getJSON(destino,{verificaAnexos: true,selector : selector,empleado : documento_identidad, transaccion : codigo_transaccion, sucursal : codigo_sucursal}, function(datos){
            if(datos){
                if(datos[0]==1){
                    $('#codigo_anexo_contable').hide();
                    $('#codigo_auxiliar_contable').hide();
                }else if(datos[0]==2){
                    $('#codigo_anexo_contable').show();
                    $('#codigo_anexo_contable').val(datos[1]);
                    recargarListaAuxiliares('codigo_anexo_contable','codigo_auxiliar_contable',datos[2]);
                    $('#codigo_auxiliar_contable').show();
                }else if(datos[0]==3){
                    $("#transaccion_contable").val('');
                    $('#codigo_anexo_contable').hide();
                    $('#codigo_auxiliar_contable').hide();
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

function cargarNovedades()
{
    var remover                  = $('#removedor').html();
    var codigo_transaccion       = $("#transaccion_contable").val();
    var nombre_transaccion       = $("#selector2").val();
    var valor_novedad            = $("#valor_noveda").val();
    var codigo_anexo_contable    = $("#codigo_anexo_contable").val();
    var nombre_anexo_contable    = $("#codigo_anexo_contable option:selected").text();
    var codigo_auxiliar_contable = $("#codigo_auxiliar_contable").val();
    var nombre_auxiliar_contable = $("#codigo_auxiliar_contable option:selected").text();
    var codigo_planilla          = $("#codigo_planilla").val();
    var fecha_pago               = $("#fecha_pago").val();
    var mensaje                  = $("#mensaje_obligatorios").val();
    var genero_pago              = $("#genero_pago").val();

    var campos_vacios            = false;
    var id      = new Date();

    if(borrarEspacios(nombre_transaccion)=='')
    {
        mensaje += $("#mensaje_transaccion").val();
        campos_vacios = true;
    }
    if(borrarEspacios(valor_novedad)=='')
    {
        mensaje += $("#mensaje_valor_novedad").val();
        campos_vacios = true;
    }

    if(genero_pago=='2'){
        if(!campos_vacios){
            var valorClase = 'even';
            if ($("#novedades_manuales tr:last").hasClass("even")) {
                  valorClase = 'odd';
            } else {
                  valorClase = 'even';
            }

            var item = '<tr id="'+id+'" class="'+valorClase+'">'+
                            '<td align="left">'+
                                '<input type="hidden" name="transaccion[]" value="'+codigo_transaccion+'">'+
                                '<input type="hidden" name="valor_novedad_oculto[]" value="'+valor_novedad+'">'+
                                '<input type="hidden" name="codigo_anexo_contable[]" value="'+codigo_anexo_contable+'">'+
                                '<input type="hidden" name="codigo_auxiliar_contable[]" value="'+codigo_auxiliar_contable+'">'+
                                '<input type="hidden" name="codigo_planilla[]" value="'+codigo_planilla+'">'+
                                '<input type="hidden" name="fecha_pago[]" value="'+fecha_pago+'">'+
                                '<input type="hidden" name="margen['+id+']" value="">'+remover+
                            '</td>'+
                            '<td align="center">'+nombre_transaccion+'</td>'+
                            '<td align="center">'+valor_novedad+'</td>'+
                            '<td align="center">'+nombre_anexo_contable+'</td>'+
                            '<td align="center">'+nombre_auxiliar_contable+'</td>'+
                        '</tr>';

            $('#novedades_manuales').append(item);
            $('#selector1').attr('disabled','disabled');
            $('#codigo_sucursal').attr('disabled','disabled');
            $('#ano_generacion').attr('disabled','disabled');
            $('#mes_generacion').attr('disabled','disabled');
            $('#fecha_pago').attr('disabled','disabled');
            limpiarCamposOcultar();

        }else{

            alert(mensaje);
        }
    }else
        {
            alert($("#mensaje_genero_pago").val());
        }
}

function limpiarCamposOcultar()
{
    $("#transaccion_contable").val('');
    $("#selector2").val('');
    $("#valor_noveda").val('');
    $('#codigo_anexo_contable').hide();
    $('#codigo_auxiliar_contable').hide();
}

function validarPlanillaPagada()
{
     var destino             = $('#URLFormulario').val();
     var anio = $("#ano_generacion").val();
     var mes  = $("#mes_generacion").val();
     var codigo_planilla     = $("#codigo_planilla").val();
     var periodo             = $("#periodo").val();
     var fecha_pago_planilla = $("#fecha_pago").val();
     var codigo_sucursal     = $("#codigo_sucursal").val();

     $.getJSON(destino,{verificaPagoPlanilla: true,
         anio : anio,
         mes  : mes,
         codigo_planilla : codigo_planilla,
         periodo : periodo,
         fecha_pago_planilla : fecha_pago_planilla,
         codigo_sucursal : codigo_sucursal
         }, function(respuesta){
                $("#genero_pago").val(respuesta);
        });
}

function generarNovedadTabla()
{
    validarPlanillaPagada();
    setTimeout("cargarNovedades()",200);

}

function recargarListaAuxiliares(origen, elemento,seleccionado) {
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
        if(seleccionado){
            $('#'+elemento).val(seleccionado);
        }
    });
}

function verificarPeriodoContable()
{
    var destino         = $('#URLFormulario').val();
    var codigo_sucursal = $("#codigo_sucursal").val();
    var fecha_inicio    = $("#fecha_pago").val();
    var id_modulo       = $("#id_modulo").val();

    $.getJSON(destino,{
        verificar_periodo_contable: true,
        codigo_sucursal:codigo_sucursal,
        fecha_inicio:fecha_inicio,
        id_modulo:id_modulo
    },function(datos){
        if(datos[0]=="1"){
            generarNovedadTabla();
        }else{
            alert(datos[1]);
        }
    });
}

