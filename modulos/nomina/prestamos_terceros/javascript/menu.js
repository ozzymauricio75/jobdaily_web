$(document).ready(function() {
ejecutarFuncionesGlobales();
});

///////////////Nuevos Datos///////////////////

function acLocalEmpleados(item) {
var destino  = $('#URLFormulario').val();
var sucursal = $('#codigo_sucursal').val();
$(item).autocomplete(destino, {
    minChars: 3,
    width: 250,
    max: 100,
    delay: 100,

    matchContains: true,
    cacheLength:100,
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
    //console.log(datos[1]);
    if (datos) {
        $('#documento_empleado').val(datos[1]);
        CampoformaPago();
        tipoFormadePago();
    //recargarListaTransacciones('documento_aspirante','codigo_transaccion_tiempo');
    //verificarAnexosEnTransacciones();
    } else {
        $('#documento_empleado').val('');
    }
});

}


function CampoformaPago()
{
var destino            = $('#URLFormulario').val();
var documento_empleado = $("#documento_empleado").val();
var lista   = '';

if(documento_empleado!=""){
    $.getJSON(destino, {
        obtenerDatosContrato: true,
        documento_empleado:documento_empleado
    }, function(datos) {

        $("#codigo_planilla").val(datos["codigo_planilla"]);
        delete datos["codigo_planilla"];
        jQuery.each(datos, function(valor, texto) {
            lista = lista+'<option value="'+valor+'">'+texto+'</option>';
        });
        $("#periodo_pago").html(lista);
        tipoFormadePago();
    });
}
}

function cambiarEstadocheck(campo){
    if($(campo).is(':checked')){
        $("#permite_descuento").val("0");
    }else{
        $("#permite_descuento").val("1");
    }
}

function limpiarCampo()
{
$("#selector1").val("");
$("#documento_empleado").val("");
$("#selector2").val("");
$("#codigo_transaccion_contable").val("");
$("#valor_prestamo").val("");
$("#valor_descuento").val("");
$("#observaciones").val("");
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

 function validarCamposObligatorios()
 {
     var valor_prestamo  = $("#valor_prestamo").val();
     var valor_descuento = $("#valor_descuento").val();
     var mensaje         = $("#mensaje_vacios_campos").val();
     
     var campos_vacios   = false;
     if(campoVacio(valor_prestamo)){
         mensaje       += $("#mensaje_vacio_valor_prestamo").val();
         campos_vacios = true;
     }
     if(campoVacio(valor_descuento)){
         mensaje += $("#mensaje_vacio_valor_cuota").val();
         campos_vacios = true;
     }

     if(campos_vacios){
         $('#pestanas > ul').tabs("disable",1);
         alert(mensaje);
     }else{
         $('#pestanas > ul').tabs("enable",1);
     }
 }

function limiteDescuento(campo){

    var limite_descuento = $(campo).val();
    if(limite_descuento == '0'){
         $("#fecha_limite_pago_contenedor").parent().hide();
         $("#valor_tope_descuento").parent().hide();
         $("#fecha_limite_pago_contenedor").attr('disabled','disabled');
         $("#valor_tope_descuento").attr('disabled','disabled');
    }else
    if(limite_descuento == '1'){
        $("#fecha_limite_pago_contenedor").parent().show();
        $("#valor_tope_descuento").parent().hide();
        $("#fecha_limite_pago_contenedor").removeAttr('disabled','disabled');
        $("#valor_tope_descuento").attr('disabled','disabled');
    }else{
        $("#fecha_limite_pago_contenedor").parent().hide();
        $("#valor_tope_descuento").parent().show();
        $("#fecha_limite_pago_contenedor").attr('disabled','disabled');
        $("#valor_tope_descuento").removeAttr('disabled','disabled');
    }

}

function tipoFormadePago(){
    var periodo_pago = $("#periodo_pago").val();
    
    if(periodo_pago=="1" || periodo_pago=="2" || periodo_pago=="3"){
        $("#valor_descuento_1").parent().children('input[class="etiqueta"').text($("#valor_descuento_todo").val());
        $("#valor_descuento_1").parent().show();
        $("#valor_descuento_2").parent().hide();
    }else if(periodo_pago=="9" ){
        $("#valor_descuento_1").parent().children('input[class="etiqueta"').text($("#valor_descuento_primera_semana").val());
        $("#valor_descuento_2").parent().children('input[class="etiqueta"').text($("#valor_descuento_segunda_semana").val());

        $("#valor_descuento_1").parent().show();
        $("#valor_descuento_2").parent().show();
    }
}


