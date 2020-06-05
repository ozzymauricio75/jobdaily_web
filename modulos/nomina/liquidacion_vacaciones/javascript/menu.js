$(document).ready(function() {
    ejecutarFuncionesGlobales();
});

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

    }).result(function(evento, datos, formateado) {
        if (datos) {
            $('#documento_identidad').val(datos[1]);
            $('#codigo_sucursal').val(sucursal)
        } else {
            $('#documento_identidad').val('');
        }
    });
}

function adicionarEventoDatePicker()
{
    $('#fecha_inicial').datepicker($.extend({},
        $.datepicker.regional['es'], {
            showOn: 'both',
            dateFormat: 'yy-mm-dd',
            buttonImage: 'imagenes/calendario.png',
            buttonImageOnly: true,
            onSelect: function(dateText, inst) {
                cargarTablaLiquidacion();
                determinarDatosDias();
            }
    })).attr('readonly', 'readonly');
}

function determinarDatosDias(){
    var destino                      = $('#URLFormulario').val();
    var codigo_sucursal              = $('#codigo_sucursal').val();
    var documento_identidad_empleado = $("#documento_identidad").val();
    var fecha_inicio_tiempo          = $("#fecha_inicial").val();
    var dias                         = $('#dias_no_laborados').val();

    $.getJSON(destino,{cargar_dias_por_tomar : true,codigo_sucursal : codigo_sucursal,
    documento_identidad_empleado : documento_identidad_empleado,fecha_inicio_tiempo:fecha_inicio_tiempo,dias : dias},function(datos){

        if(campoVacio(dias)){
            datos[2]="";
            datos[3]="";
            datos[4]="";
        }
        if(datos[0]=="1"){
            alert(datos[1]);
            $("#listaItemsVacaciones tbody").remove();
            $("#fecha_en_rango").val("1");
            $("#fecha_final").text(datos[2]);
            $("#dias_disfruta").text(datos[3]);
            $("#dias_no_laborados").val("");
            $("#dias_tomados").text(datos[4]);
            $("#oculto_fecha_final").val(datos[2]);
            $("#oculto_dias_disfruta").val(datos[3]);
        }else{
            $("#fecha_en_rango").val("0");
            $("#fecha_final").text(datos[2]);
            $("#dias_disfruta").text(datos[3]);
            $("#dias_tomados").text(datos[4]);
            $("#oculto_fecha_final").val(datos[2]);
            $("#oculto_dias_disfruta").val(datos[3]);
        }

    });
}

function cargarTablaLiquidacion(){

    var destino            = $('#URLFormulario').val();
    var fecha_liquidacion  = $('#fecha_inicial').val();
    var documento_empleado = $('#documento_identidad').val();
    var codigo_sucursal    = $('#codigo_sucursal').val();
    var dias_tomados       = $('#dias_no_laborados').val();
    var forma_liquidacion  = $('#forma_liquidacion').val();

    
    $("#listaItemsVacaciones tbody").remove();
    $("#listaItemsVacaciones th").remove();
    $.getJSON(destino,{determino_salarios_pendientes : true,fecha_liquidacion : fecha_liquidacion,
    documento_empleado : documento_empleado,codigo_sucursal:codigo_sucursal,dias_tomados : dias_tomados,forma_liquidacion:forma_liquidacion},function(datos){
        generarTablaLiquidacion(datos.join("¬"));
    });
 }

function generarTablaLiquidacion(datos){
    datos = datos.split("¬")
    var valores_concepto_envio = "";
    
    $("#listaItemsVacaciones tbody").remove();
    $("#listaItemsVacaciones th").remove();
    for(i=0;i<datos.length;i++){

        id = new Date();
        var valores = datos[i].split("|");

        items =  '<tr id="'+id+'" class="odd">'+
            '<td align="left" width="250"><b>'+valores[0]+'</b></td>'+
            '<td align="right" width="100">'+valores[1]+'</td>'+
        '</tr>';

        $('#listaItemsVacaciones').append(items);

        for(c=2;c<valores.length;c+=2){
             id = new Date();
             var valorClase = 'even';
             nombre_cocepto   = valores[c];
             valores_concepto = valores[c+1];
             valores_concepto_envio +=valores_concepto+"|";

             items =  '<tr id="'+id+'" class="'+valorClase+'">'+
                '<td align="left" width="250"><span class="etiqueta">'+nombre_cocepto+'</span></td>'+
                '<td align="right" width="100">'+valores_concepto+'</td>'+
            '</tr>';
            $('#listaItemsVacaciones').append(items);
        }

    }
    $("#valores_conceptos").val(valores_concepto_envio);
}

function limpiarCampos()
{
    $("#listaItemsVacaciones tbody").remove();
    $("#selector1").val("");
    $("#documento_identidad").val("");
    $("#fecha_inicial").val("");
    $("#dias_no_laborados").val("");
    $("#fecha_final").text("");
    $("#dias_disfruta").text("");
    $("#dias_tomados").text("");
}

function recargarDatosDocumento() {
    var destino     = $('#URLFormulario').val();
    var documento   = $('#tipo_documento option:selected').val();
    var sucursal    = $('#codigo_sucursal').val();
    var lista       = '';
    var genera      = false;
    var contador    = 0;
    $('#codigo_contable').val('');
    $('#selector2').val('');
    $('#selector2').removeAttr("disabled");
    $('#cuenta_bancaria').html('<option value="0"></option>');
    $('#cuenta_bancaria').attr("disabled","disabled").parent().hide();
    $('#consecutivo_cheque').val('');
    $('#consecutivo_cheque').attr("disabled","disabled").parent().hide();

    $.getJSON(destino, {recargarDatosDocumento: true, documento: documento, sucursal: sucursal}, function(datos) {
        jQuery.each(datos, function(id, dato){
            if(id == "consecutivo_documento"){
                if(dato != 0){
                    $('#consecutivo_documento').val(dato);
                    $('#consecutivo_documento').attr("readonly","readonly");
                }else{
                    $('#consecutivo_documento').val('');
                    $('#consecutivo_documento').removeAttr("readonly");
                }
            }else if(id == "genera_cheque"){
                if(dato == '1'){
                    genera = true;
                }
                $('#maneja_cheque').val(dato);
            }else if(id == "consecutivo_cheque"){
                $('#consecutivo_cheque').removeAttr("disabled").parent().show();
                $('#consecutivo_cheque').val(dato);
            } else{
                lista = lista+'<option value="'+id+'">'+dato+'</option>';
                $('#cuenta_bancaria').removeAttr("disabled").parent().show();
                $('#cuenta_bancaria').html(lista);
                contador++;
            }
        });
        if(genera && contador==0){
            alert($('#error_no_cuentas_bancarias').val());
            existenCuentas = false;
        }else if(genera && contador>0){
            existenCuentas = true;
            consecutivoCheque();
        }
    });
}

function consecutivoCheque(){
    var destino = $('#URLFormulario').val();
    var llave   = $('#cuenta_bancaria').val();
    $('#codigo_contable').val(llave.split('|')[8]);
    $.getJSON(destino, {recargar_consecutivo_cheque: true, cuenta: llave}, function(datos){
        $('#consecutivo_cheque').val(datos[0]);
        $('#selector2').val(datos[1]);
        $('#selector2').attr("disabled","disabled");
        var lista ='<option value="'+datos[2]+'">'+datos[3]+'</option>';
        /*$('#auxiliar_contable').removeAttr("disabled");
        $('#auxiliar_contable').html(lista);*/
    });
}