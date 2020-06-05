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

            $("#forma_pago_prestamo").html(lista);

        });
    }
}



function generarTablaPagos()
{

    var destino        = $('#URLFormulario').val();
    var fechaInicio    = $("#fecha_inicio").val();
    var codigoPlanilla = $("#codigo_planilla").val();

    ///Calculo de numero de fechas a adicionar para completar el pago///
    var valorPrestamo = parseInt($("#valor_prestamo").val());
    var valorCuota    = parseInt($("#valor_descuento").val());
    var formaPago     = $("#forma_pago_prestamo").val();
    removerTable();
    recargarTabla(destino,fechaInicio,valorPrestamo,valorCuota,codigoPlanilla,formaPago,0);
}

function removerTable() {
    $("#listaItemsPagos tbody").remove();

}

function cargarNuevosDatos(){

    var index_id        = $("#identificador_check").val();
    var id             = "fila_"+index_id;
    var destino        = $('#URLFormulario').val();
    var codigoPlanilla = $("#codigo_planilla").val();
    var formaPago      = $("#forma_pago_prestamo").val();
    var valorCuota     = parseInt($("#valor_descuento").val());
    var tabla          = document.getElementById("listaItemsPagos");
    var numeroFilas    = tabla.rows.length;
    var columnas       = document.getElementById(id).getElementsByTagName("td");
    var i               = $("#"+id)[0].rowIndex;

    var c=0;
    while(c<(numeroFilas-i)){

        tabla.deleteRow(i);
        c++;
    }
    ///Calculo de numero de fechas a adicionar para completar el pago///
    var fechaInicio    = columnas[1].innerHTML;
    var valorPrestamo =  parseInt(columnas[2].innerHTML)+valorCuota;

    recargarTabla(destino,fechaInicio,valorPrestamo,valorCuota,codigoPlanilla,formaPago,index_id);
}


function recargarTabla(destino,fechaInicio,valorPrestamo,valorCuota,codigoPlanilla,formaPago,index_id)
{
    var proceso = $("#proceso").val();
    var identificador_check = $("#identificador_check").val();
  
    $.getJSON(destino, {
        generarDatosTabla: true,
        fechaInicio:fechaInicio,
        valorPrestamo:valorPrestamo,
        valorCuota:valorCuota,
        codigoPlanilla:codigoPlanilla,
        formaPago:formaPago
    }, function(datos) {

        for(i=0;i<datos.length;i++)
        {
            var id          = new Date();
            datos_llegada   = datos[i].split(",");
            fecha_decuento  = datos_llegada[0];
            valor_saldo     = datos_llegada[1];
            valor_descuentos = datos_llegada[2];

            if ($("#listaItemsPagos tr:last").hasClass("even")) {
                valorClase = 'odd';
            } else {
                valorClase = 'even';
            }


           if(proceso=='M' && (index_id)==parseInt(identificador_check)){
           var check = '<input id="DesautorizaDescuento" type="checkbox" onclick="cambiarEstadocheck(this)" name="desautorizaDescuento" value="ON" checked="checked" />';
           $("#fecha_marcada").val(fecha_decuento);
           }else
               {
                   check="";
               }

            if(proceso=='M'){
                  identificador_texto = "texto_"+index_id;
                  texto = '<input type="text" id="'+identificador_texto+'" class="campo" onKeyPress="return campoEntero(event)" onblur=" cargarNuevosModificaCuota(this);" value="'+valor_descuentos+'" maxlength="30" size="10" name="texto_valor_cuota"  >';
               }else
                   {
                  texto=valor_descuentos;
                   }

            identificador = "fila_"+index_id;
            id_descuento  = "filaSaldo_"+index_id;
            id_saldo      = "filaDescuento_"+index_id;

            var item  = '<tr id="'+identificador+'" class="'+valorClase+'">'+
            '<td align="center">'+
            '<input type="hidden" class="fechas_pago" name="fechas_pago[]" value="'+fecha_decuento+'">'+
            '<input type="hidden"  class="valor_saldo" name="valor_saldo[]" value="'+valor_saldo+'">'+
            '<input type="hidden"class="valor_descuentos" name="valor_descuentos[]" value="'+valor_descuentos+'">'+
            '<input type="hidden" class="descuenta" name="descuenta[]" value="1">'+check+
            '</td>'+

            '<td align="center">'+fecha_decuento+'</td>'+
            '<td align="left">'+valor_saldo+'</td>'+
            '<td align="center">'+texto+'</td>'+


            '</tr>';
            $('#listaItemsPagos').append(item);
            index_id++;

        }

    });

}

function cambiarEstadocheck(campo){

    if($(campo).is(':checked')){
        $("#permite_descuento").val("0");
    }else{
        $("#permite_descuento").val("1");

    }

}

function cargarNuevosModificaCuota(campo){

     var check_marcado       = $("#identificador_check").val();
     var id                  = $(campo).parents('tr').attr('id')
     index_id                = id.split("_");
     index_id                = parseInt(index_id[1]);

     var destino             = $('#URLFormulario').val();
     var codigoPlanilla      = $("#codigo_planilla").val();
     var formaPago           = $("#forma_pago_prestamo").val();
     var identificador_texto = "#texto_"+index_id;
     var valorCuota          = parseInt($(identificador_texto).val());
     var valorCuotaEstable   = parseInt($("#valor_cuota_estable").val());
     var tabla               = document.getElementById("listaItemsPagos");
     var numeroFilas         = tabla.rows.length;
     var valorPrestamo       = 0;

     var id_siguiente       = "fila_"+(index_id+1);
     var columnas           = document.getElementById(id).getElementsByTagName("td");
     var columnas_siguiente = document.getElementById(id_siguiente).getElementsByTagName("td");

     var i = ($("#"+id)[0].rowIndex)+1;

    ///Calculo de numero de fechas a adicionar para completar el pago///
    var fechaInicio    = columnas_siguiente[1].innerHTML;


   if(index_id == parseInt(check_marcado))
        {
             if(parseInt($("#valor_saldo_llegada").val())>=valorCuota){
                     valorPrestamo =  parseInt($("#valor_saldo_llegada").val())-valorCuota;
                     continuar = true;
             }else
                 {
                     continuar = false;
                 }
        }else
       {
             var id_anterior        = "fila_"+(index_id-1);
             columnas_anterior  = document.getElementById(id_anterior).getElementsByTagName("td");
             valorPrestamo =  parseInt(columnas_anterior[2].innerHTML)-valorCuota;

             if(parseInt(columnas_anterior[2].innerHTML)>=valorCuota){

                 continuar = true;
             }else
             {
                 continuar = false;
             }
       }

       if(continuar){

        var c=0;
        while(c<(numeroFilas-i)){
        tabla.deleteRow(i);
        c++;
        }

       columnas[2].innerHTML = valorPrestamo;
       $(campo).children('input[class="valor_saldo"]').val(valorPrestamo);

       $(campo).parents('tr').children("td:first").children('input[class="valor_descuentos"]').val(valorCuota);
       $(campo).parents('tr').children("td:first").children('input[class="valor_saldo"]').val(valorPrestamo);

       recargarTabla(destino,fechaInicio,valorPrestamo,valorCuotaEstable,codigoPlanilla,formaPago,(index_id+1));
       }else
       {
               $(identificador_texto).val(valorCuotaEstable);
               alert($("#valor_cuota_mayor").val());
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
/*
        $('#auxiliar_contable').attr("disabled","disabled").parent().hide();
        $('#auxiliar_contable').html(lista);
*/
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

function informacionEmpleado(){

    var destino = $('#URLFormulario').val();
    var documento_empleado = $('#documento_empleado').val();
    var codigo_sucursal  = $('#codigo_sucursal').val();

    $.getJSON(destino, {
    informacion_empleado : true,
    documento_empleado   : documento_empleado,
    codigo_sucursal      : codigo_sucursal
    },function(datos){
        $("#fecha_ingreso_empleado").val(datos[0]);
        $("#salario_base").val(datos[1]);
        $("#manejo_auxilio_transporte").val(datos[1]);
        datosDeCesantias();
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

    function determinarCesantias()
    {
         var codigo_sucursal        = $("#codigo_sucursal").val();
         var fecha_ingreso_empleado = $("#fecha_ingreso_empleado").val();
         var salario_base           = $("#salario_base").val();
         var manejo_auxilio_transporte = $("#manejo_auxilio_transporte").val();
         var fecha_liquidacion = $("#fecha_liquidacion").val();
         var destino = $('#URLFormulario').val();
         var valor_total_cesantias = 0;
         var documento_empleado = $('#documento_empleado').val();
    $.getJSON(destino, {
        determino_cesantias: true,
        documento_empleado: documento_empleado,
        fecha_ingreso_empleado : fecha_ingreso_empleado,
        salario_base : salario_base,
        codigo_sucursal : codigo_sucursal,
        manejo_auxilio_transporte : manejo_auxilio_transporte,
        fecha_liquidacion : fecha_liquidacion
    }, function(datos){

       $("#valor_cesantias").val(datos[0]);
       $("#oculto_valor_cesantias").val(datos[0]);
       $("#valor_retiro").removeAttr("disabled");

       });

    }

function determinarRetiro()
{
    var valorCesantiasOculto  = parseInt($("#oculto_valor_cesantias").val());
    var valorRetiro      = parseInt($("#valor_retiro").val());

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
         
         if(tamanio_datos == 2){
               $("#valor_cesantias").val(datos[0]);
               $("#oculto_valor_cesantias").val(datos[0]);
               $("#valor_retiro").removeAttr("disabled");
         }else{
             for(i=0;i<tamanio_datos-2;i++){
          
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

                $("#valor_cesantias").val(datos[tamanio_datos-2]);
                $("#oculto_valor_cesantias").val(datos[tamanio_datos-1]);
                $("#valor_retiro").removeAttr("disabled");
             }


         }

       });
}