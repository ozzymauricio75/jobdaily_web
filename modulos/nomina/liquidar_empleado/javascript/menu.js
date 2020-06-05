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
    
}).result(function(evento, datos, formateado){
    //console.log(datos[1]);
    if (datos) {
        $('#documento_empleado').val(datos[1]);
        informacionEmpleado();
        //recargarListaTransacciones('documento_aspirante','codigo_transaccion_tiempo');
        //verificarAnexosEnTransacciones();
    } else {
        $('#documento_empleado').val('');
    }
});

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
    /*
    $('#auxiliar_contable').attr("disabled","disabled").parent().hide();
    $('#auxiliar_contable').html(lista);
    */
    $.getJSON(destino, {
        recargarDatosDocumento: true,
        documento: documento,
        sucursal: sucursal
    }, function(datos) {
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
    $.getJSON(destino, {
        recargar_consecutivo_cheque: true,
        cuenta: llave
    }, function(datos){
        $('#consecutivo_cheque').val(datos[0]);
        $('#selector2').val(datos[1]);
        $('#selector2').attr("disabled","disabled");
        var lista ='<option value="'+datos[2]+'">'+datos[3]+'</option>';
    /*$('#auxiliar_contable').removeAttr("disabled");
        $('#auxiliar_contable').html(lista);*/
    });
}

function determinarRetiro()
{
    var valorCesantiasOculto  = parseInt($("#oculto_valor_cesantias").val());
    var valorRetiro           = parseInt($("#valor_retiro").val());

    if(!isNaN(valorRetiro)){
        if(valorCesantiasOculto >= valorRetiro){
            $("#valor_cesantias").val(valorCesantiasOculto-valorRetiro);
        }else{
            $("#valor_retiro").val(valorCesantiasOculto);
            $("#valor_cesantias").val(0);
        }
    }else
    {
        $("#valor_cesantias").val(valorCesantiasOculto);
    }
}

function determinarDatosPretaciones(){
    var destino                   = $('#URLFormulario').val();
    var documento_empleado        = $('#documento_empleado').val();
    var fecha_liquidacion         = $('#fecha_liquidacion').val();
    var fecha_ingreso_empleado    = $("#fecha_ingreso_empleado").val();
    var codigo_sucursal           = $('#codigo_sucursal').val();
    var salario_base              = $("#salario_base").val();
    var manejo_auxilio_transporte = $("#manejo_auxilio_transporte").val();
    var tipo_contratacion         = $("#tipo_contratacion").val();

    $.getJSON(destino, {
        determinar_prestaciones   : true,
        documento_empleado        : documento_empleado,
        fecha_liquidacion         : fecha_liquidacion,
        fecha_ingreso_empleado    : fecha_ingreso_empleado,
        codigo_sucursal           : codigo_sucursal,
        salario_base              : salario_base,
        tipo_contratacion         : tipo_contratacion,
        manejo_auxilio_transporte : manejo_auxilio_transporte
    },function(datos){
        var datos_vacaciones = datos[0].split("|");

        $("#total_vacaciones").val(datos_vacaciones[0]);
        $("#salario_base_vacaciones").val(datos_vacaciones[1]);
        $("#fecha_inicio_vacaciones").val(datos_vacaciones[2]);
        $("#dias_liquidados_vacaciones").val(datos_vacaciones[3]);
        $("#periodo_pago_vacaciones").val(datos_vacaciones[4]);
        
        var datos_prima = datos[1].split("|");
        
        $("#total_primas").val(datos_prima[0]);
        $("#formula_primas").val(datos_prima[1]);
        $("#fecha_inicio_primas").val(datos_prima[2]);
        $("#dias_liquidados_primas").val(datos_prima[3]);
        $("#salario_base_primas").val(datos_prima[4]);
        $("#periodo_pago_primas").val(datos_prima[5]);

        var datos_cesantias = datos[2].split("|");

        $("#total_cesantias").val(datos_cesantias[0]);
        $("#total_intereses_cesantias").val(datos_cesantias[1]);
        $("#fecha_inicio_cesantias").val(datos_cesantias[2]);
        $("#dias_liquidados_cesantias").val(datos_cesantias[3]);
        $("#salario_base_cesantias").val(datos_cesantias[4]);
        $("#periodo_pago_cesantias").val(datos_cesantias[5]);
        $("#fecha_inicio_interes_cesantias").val(datos_cesantias[6]);
        $("#dias_liquidados_interes_cesantias").val(datos_cesantias[7]);
        $("#salario_base_interes_cesantias").val(datos_cesantias[8]);
        $("#periodo_pago_interes_cesantias").val(datos_cesantias[9]);
        

    });
}

function determinarPrestamos()
{
    var destino = $('#URLFormulario').val();
    var documento_empleado = $('#documento_empleado').val();
   
    $.getJSON(destino, {
        prestamos_empleado : true,
        documento_empleado : documento_empleado
    },function(datos){
        $("#total_prestamos").val(datos[0]);
        $("#formula_prestamos").val(datos[1]);
    });
}

function determinarSalariosPendientes()
{
    var destino = $('#URLFormulario').val();
    var documento_empleado         = $('#documento_empleado').val();
    var fecha_liquidacion          = $('#fecha_liquidacion').val();
    var codigo_sucursal            = $('#codigo_sucursal').val();
    var salario_base               = $("#salario_base").val();
    var fecha_ingreso_empleado     = $("#fecha_ingreso_empleado").val();
    var manejo_auxilio_transporte  = $("#manejo_auxilio_transporte").val();

    $.getJSON(destino, {

        determino_salarios_pendientes : true,
        documento_empleado        : documento_empleado,
        fecha_liquidacion         : fecha_liquidacion,
        codigo_sucursal           : codigo_sucursal,
        salario_base              : salario_base,
        fecha_ingreso_empleado    : fecha_ingreso_empleado,
        manejo_auxilio_transporte : manejo_auxilio_transporte

    },function(datos){
        
        $("#total_sueldo_pendiente").val(datos[0]);
        $("#total_auxilio_pendiente").val(datos[1]);
        $("#total_salud_pendiente").val(datos[2]);
        $("#total_pension_pendiente").val(datos[3]);
        $("#total_horas_extras_pendientes").val(datos[4]);

        $("#fecha_inicio_pago_salario").val(datos[5]);
        $("#dias_trabajados_salario").val(datos[6]);

        $("#dias_auxilio").val(datos[7]);

        $("#ibc_salud").val(datos[8]);
        $("#porcentaje_tasa_salud").val(datos[9]);

        $("#pensionado").val(datos[10]);
        $("#ibc_pension").val(datos[11]);
        $("#porcentaje_tasa_pension").val(datos[12]);


    });
}

function informacionEmpleado(){
    
    var destino = $('#URLFormulario').val();
    var fecha_liquidacion  = $('#fecha_liquidacion').val();
    var documento_empleado = $('#documento_empleado').val();
    var codigo_sucursal    = $('#codigo_sucursal').val();

    $.getJSON(destino, {
        informacion_empleado : true,
        documento_empleado   : documento_empleado,
        fecha_liquidacion    : fecha_liquidacion,
        codigo_sucursal      : codigo_sucursal
    },function(datos){
        $("#salario_base").val(datos[1]);
        $("#fecha_ingreso_empleado").val(datos[0]);
        $("#manejo_auxilio_transporte").val(datos[2]);
        $("#tipo_contratacion").val(datos[3]);
        calculoTotal();
    });
}

function calculoTotal()
{
    determinarDatosPretaciones();
    determinarPrestamos();
    determinarSalariosPendientes();
    
    $("#calculo_prestaciones tbody").remove();
    $("#calculo_prestaciones th").remove();
 
    var total_cesantias               = $("#total_cesantias").val();
    var total_intereses_cesantias     = $("#total_intereses_cesantias").val();
    var total_primas                  = $("#total_primas").val();
    var total_vacaciones              = $("#total_vacaciones").val();
    var total_sueldo_pendiente        = $("#total_sueldo_pendiente").val();
    var total_auxilio_pendiente       = $("#total_auxilio_pendiente").val();
    var total_horas_extras_pendientes = $("#total_horas_extras_pendientes").val();
   
    var total_prestaciones        = parseInt(total_cesantias)+parseInt(total_intereses_cesantias)+parseInt(total_primas)+parseInt(total_vacaciones)+parseInt(total_sueldo_pendiente)+parseInt(total_auxilio_pendiente)+parseInt(total_horas_extras_pendientes);

    var titulo_cesantias           = $("#titulo_cesantias").val();
    var titulo_intereses_cesantias = $("#titulo_intereses_cesantias").val();
    var titulo_primas              = $("#titulo_primas").val();
    var titulo_vacaciones          = $("#titulo_vacaciones").val();
    var titulo_sueldo_pendiente    = $("#titulo_sueldo_pendiente").val();
    var titulo_auxilio_pendiente   = $("#titulo_auxilio_pendiente").val();
    var titulo_extras_pendiente    = $("#titulo_extras_pendiente").val();
    var titulo_total               = $("#titulo_total").val();

    var formula_cesantias           = $("#formula_cesantias").val();
    var formula_intereses_cesantias = $("#formula_intereses_cesantias").val();
    var formula_primas              = $("#formula_primas").val();
    var formula_vacaciones          = $("#formula_vacaciones").val();

    var datos = new Array();
    datos  = [total_cesantias,total_intereses_cesantias,total_primas,total_vacaciones,total_sueldo_pendiente,total_auxilio_pendiente,total_horas_extras_pendientes,total_prestaciones];
    var formulas = new Array();
    formulas  = [formula_cesantias,formula_intereses_cesantias,formula_primas,formula_vacaciones,"","","",""];
    var titulos = new Array();
    titulos  = [titulo_cesantias,titulo_intereses_cesantias,titulo_primas,titulo_vacaciones,titulo_sueldo_pendiente,titulo_auxilio_pendiente,titulo_extras_pendiente,titulo_total];

    var titulo =  '<tr class="titulo" style="background-color: #E2ECED;"><td align="left" colspan="3"><b>'+$("#titulo_resumen_pagos").val()+'</b></td></tr>';
    $('#calculo_prestaciones').append(titulo);

    for(i=0;i<datos.length;i++){
        var id          = new Date();
        var valorClase = 'even';

        if(i==7){
            valorClase = 'titulo even';
        }
        var items =  '<tr id="'+id+'" class="'+valorClase+'">'+
        '<td align="left" width="250"><span class="etiqueta">'+titulos[i]+'</span></td>'+
        '<td align="left" width="200">'+formulas[i]+'</td>'+
        '<td align="left" width="50">'+datos[i]+'</td>'+
            
        '</tr>';
        $('#calculo_prestaciones').append(items);
    }
    ///////////RESUMEN DESCUENTOS LIQUIDACION///////
    var titulo_salud             = $("#titulo_salud").val();
    var titulo_pension           = $("#titulo_pension").val();
    var titulo_prestamos         = $("#titulo_prestamos").val();
    var titulo_total_deducciones = $("#titulo_total_deducciones").val();
    
    var total_salud_pendiente   = $("#total_salud_pendiente").val();
    var total_pension_pendiente = $("#total_pension_pendiente").val();
    var total_prestamos         = $("#total_prestamos").val();

    var formula_prestamos       = $("#formula_prestamos").val();

    var total_descuentos  = parseInt(total_prestamos)+parseInt(total_salud_pendiente)+parseInt(total_pension_pendiente);

    var datos_descuento = new Array();
    datos_descuento  = [total_salud_pendiente,total_pension_pendiente,total_prestamos,total_descuentos];
    var formulas_descuento = new Array();
    formulas_descuento  = ["","",formula_prestamos,""];
    var titulos_descuento = new Array();
    titulos_descuento  = [titulo_salud,titulo_pension,titulo_prestamos,titulo_total_deducciones];

    var titulo_descuento =  '<tr class="odd"><td align="left" colspan="3"></td></tr>';
    titulo_descuento +=  '<tr class="titulo"><td align="left" colspan="3" style="background-color: #E2ECED;"><b>'+$("#titulo_resumen_descuento").val()+'</b></td></tr>';
    
    $('#calculo_prestaciones').append(titulo_descuento);

    for(i=0;i<datos_descuento.length;i++){
        id          = new Date();
        valorClase = 'even';

        if(i==3){
            valorClase = 'titulo even';
        }
        items =  '<tr id="'+id+'" class="'+valorClase+'">'+
        '<td align="left" width="250"><span class="etiqueta">'+titulos_descuento[i]+'</span></td>'+
        '<td align="left" width="200">'+formulas_descuento[i]+'</td>'+
        '<td align="left" width="50">' +datos_descuento[i]+'</td>'+


        '</tr>';
        $('#calculo_prestaciones').append(items);
    }

    var valor_final =  '<tr class="odd"><td align="left" colspan="3"></td></tr>';
    valor_final +=  '<tr class="titulo" style="background-color: #E2ECED;"><td align="left" colspan="2"><b>'+$("#valor_liquidacion").val()+'</b></td><td>'+(total_prestaciones-total_descuentos)+'</td></tr>';
  
    $('#calculo_prestaciones').append(valor_final);
}

function datosDeCesantias()
{
    $("#listaCesantias tbody").remove();
    $("#listaCesantias th").remove();

    //$("#listaCesantias").removeClass("tablaInterna");

    var codigo_sucursal        = $("#codigo_sucursal").val();
    var fecha_ingreso_empleado = $("#fecha_ingreso_empleado").val();
    var salario_base           = $("#salario_base").val();
    var manejo_auxilio_transporte = $("#manejo_auxilio_transporte").val();
    var fecha_liquidacion = $("#fecha_liquidacion").val();
    var destino = $('#URLFormulario').val();
    var documento_empleado = $('#documento_empleado').val();

    $.getJSON(destino, {
        datos_cesantias: true,
        documento_empleado: documento_empleado,
        fecha_ingreso_empleado : fecha_ingreso_empleado,
        salario_base : salario_base,
        codigo_sucursal : codigo_sucursal,
        manejo_auxilio_transporte : manejo_auxilio_transporte,
        fecha_liquidacion : fecha_liquidacion
    },function(datos){
        $("#listaCesantias tbody").remove();
        var tamanio_datos = datos.length;
        if(tamanio_datos == 1){
            $("#valor_cesantias").val(datos[0]);
            $("#oculto_valor_cesantias").val(datos[0]);
            $("#valor_retiro").removeAttr("disabled");
        }else{
            for(i=0;i<tamanio_datos-1;i++){
                $datos_retiro = datos[i].split(",");
                var id          = new Date();
                var valorClase = 'even';

                var titulo =  '<tr><td align="left" colspan="3"><br/></td ></tr>';
                $('#listaCesantias').append(titulo);
                titulo =  '<tr class= "even" ><td align="left" colspan="3"><b>'+$("#titulo_fecha_liquidacion").val()+' : '+$datos_retiro[0]+'</b></td ></tr>';
                $('#listaCesantias').append(titulo);
                titulo =  '<tr  align="center" ><td  class= "titulo"></td > <td   class= "titulo"><span class="etiqueta">'+$("#titulo_valor").val()+'</span></td><td class= "titulo"><span class="etiqueta">'+$("#titulo_intereses").val()+'</span></td></tr>';
                $('#listaCesantias').append(titulo);

                var items =  '<tr id="'+id+'" class="'+valorClase+'">'+
                '<td align="left" class= "odd" width="100" ><span class="etiqueta">'+$("#titulo_valor_cesantias").val()+'</span></td>'+
                '<td width="50" align="center">'+$datos_retiro[1]+'</td>'+
                '<td align="center">'+$datos_retiro[2]+'</td>'+
                '</tr>'+
                '<tr id="'+id+'" class="'+valorClase+'">'+
                '<td align="left" class= "odd"><span class="etiqueta">'+$("#titulo_valor_retiro").val()+'</span></td>'+
                '<td align="center" >'+$datos_retiro[3]+'</td>'+
                '<td align="center" >'+$datos_retiro[4]+'</td>'+
                '</tr>'+
                '<tr id="'+id+'" class="'+valorClase+'">'+
                '<td align="left" class= "odd"><span class="etiqueta">'+$("#titulo_saldo_cesantias").val()+'</span></td>'+
                '<td align="center" >'+$datos_retiro[5]+'</td>'+
                '<td align="center"  > - </td>'+
                '</tr>';

                $('#listaCesantias').append(items);

                $("#valor_cesantias").val(datos[tamanio_datos-1]);
                $("#oculto_valor_cesantias").val(datos[tamanio_datos-1]);
                $("#valor_retiro").removeAttr("disabled");
            }


        }

    });
}

function validarCamposObligatorios(pestana)
{
    var documento_empleado = $("#documento_empleado").val();
    var mensaje         = $("#mensaje_vacios_campos").val();

    if(campoVacio(documento_empleado)){
        $('#pestanas > ul').tabs("disable",pestana);
        alert(mensaje);
    }else{
        $('#pestanas > ul').tabs("enable",pestana);
    }
}

