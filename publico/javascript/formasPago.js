
function  mostrarCuenta(item)
{

    var id = $(item).attr('id');
    if($(item).is(':checked')){
        $('#pago_'+id).parent().removeClass("pago_sucursales oculto");
        $('#pago_'+id).parent().show();
    } else {
        $('#pago_'+id).parent().addClass('pago_sucursales oculto');
        $('#pago_'+id).parent().hide();
    }
}

function cargarFechaPagoFormaPago(tipo){

    var destino          = $('#URLFormulario').val();
    var codigo_planilla  = $('#codigo_planilla').val();
    var ano_generacion   = $('#ano_generacion').val();
    var mes_generacion   = $('#mes_generacion').val();
    var lista            = '';

    $('#fecha_pago').empty();
    $('#periodo').empty();

    /*** Enviar datos para la recarga ***/
    $.getJSON(destino, {recargar_datos: true, codigo_planilla: codigo_planilla, ano_generacion: ano_generacion, mes_generacion: mes_generacion}, function(datos) {
        jQuery.each(datos, function(valor, texto) {
            lista = lista+'<option value="'+valor+'">'+texto+'</option>';
        });
        $('#fecha_pago').html(lista);
    });

    setTimeout(function(){determinarPeriodoFormaPago(tipo)},200);
    setTimeout("determinarSucursales()",200);
    setTimeout("determinarSucursalesNomina()",400);
}

/*** funciones usadas en los balances de contabilidad ***/
function seleccionar_todas_sucursales_forma_pago(){

    var contador_casillas_seleccionadas = 0;
    var contador_total_casillas = 0;


    $('.sucursales_electrodomesticos:checkbox').each(function () {
        var id = $(this).val();
        if ($(this).is(':checked')) {
            contador_casillas_seleccionadas++;
        }
        contador_total_casillas++;
    });

    if(contador_total_casillas == contador_casillas_seleccionadas){
        $(".sucursales_electrodomesticos:checkbox").removeAttr('checked');
        $(".empresas_consolidados:checkbox").removeAttr('checked');
        $(".pago_sucursales").parent().hide();

    } else {


        $(".sucursales_electrodomesticos:checkbox").attr('checked','checked');
        $(".empresas_consolidados:checkbox").attr('checked','checked');

        $(".pago_sucursales").parent().show();
    }

}



///Permite determinar si ya se ha generado una forma de pago para esa sucursal//
function determinarSucursales()
{

    var destino             = $('#URLFormulario').val();
    var codigo_planilla     = $('#codigo_planilla').val();
    var ano_generacion      = $('#ano_generacion').val();
    var mes_generacion      = $('#mes_generacion').val();
    var fecha_pago_planilla = $('#fecha_pago').val();
    var periodo_pago        = $('#periodo').val();

    $.getJSON(destino, {
        determinarSucursales : true,
        codigo_planilla      : codigo_planilla,
        ano_generacion       : ano_generacion,
        mes_generacion       : mes_generacion,
        fecha_pago_planilla  : fecha_pago_planilla,
        periodo_pago         : periodo_pago
    }, function(datos) {

        dato = parseInt(datos);
        if(dato==0){
            $("#periodo_pago_activo").val("1");
        } else {
            alert($("#mensaje_forma_pago").val());
            $("#periodo_pago_activo").val("0");
        }
   });
}
function cargarCuentasBancarias()
{
    var codigo_cuenta   = $("#cuentas_bancarias").val();
    var destino         = $('#URLFormulario').val();

         $.getJSON(destino, {
         cargaCuentasBancarias : true,
         codigo_cuenta         : codigo_cuenta
    }, function(datos) {

      $("#cuenta").text(datos[0]);
      $("#codigo_plan_contable").val(datos[1]);
    });

}

function recargarDatos(){
    return 0;
}

function determinarPeriodoFormaPago(tipo_pago){

    var destino         = $('#URLFormulario').val();
    var codigo_planilla = $('#codigo_planilla').val();
    fechaPago           = $("#fecha_pago").val();

    if(fechaPago!=null){
        datosFecha = fechaPago.split("-");
        dia        = parseInt(datosFecha[2]);

        $.getJSON(destino, {recargarTipoPlanilla: true, codigo_planilla: codigo_planilla}, function(tipo) {

            if(tipo=='2'){
                if(dia<=15){

                    $("#nombre_periodo").text($("#primera_quincena").val());
                    $("#periodo").val("2");
                }else{
                    $("#nombre_periodo").text($("#segunda_quincena").val());
                    $("#periodo").val("3");
                }
            }else if (tipo=='1'){
                $("#nombre_periodo").text($("#mensual").val());
                $("#periodo").val("1");
            } else {
                $("#nombre_periodo").text($("#fecha_unica").val());
                $("#periodo").val("4");
            }

            determinarSucursales();
            if(tipo_pago=='P')
            {
                cargarDatosFormaPago();
            }
        });
    }
}


/*
///Permite obtener datos de la forma de pago///
function cargarDatosFormaPago()
{

    var destino             = $('#URLFormulario').val();
    var codigo_planilla     = $('#codigo_planilla').val();
    var ano_generacion      = $('#ano_generacion').val();
    var mes_generacion      = $('#mes_generacion').val();
    var fecha_pago_planilla = $('#fecha_pago').val();
    var periodo_pago        = $('#periodo').val();

    $.getJSON(destino, {
        cargaInformacionPlanilla : true,
        codigo_planilla      : codigo_planilla,
        ano_generacion       : ano_generacion,
        mes_generacion       : mes_generacion,
        fecha_pago_planilla  : fecha_pago_planilla,
        periodo_pago         : periodo_pago
    }, function(datos) {
        var tipo_pago = datos[0];
        if(tipo_pago!='0'){
            if(tipo_pago=='1' || tipo_pago=='2'){
                $("#cuenta").text(datos[1]);
                $("#tipo_documento").text(datos[2]);
                $("#sucursal_genera").text(datos[3]);
                $(".datos_comun").removeClass("oculto");
                $(".datos_comun").parent().show();

                $("#cuenta_banco").addClass("oculto");
                $("#cuenta_banco").parent().hide();
            }else{
                $("#cuenta").text(datos[1]);
                $("#tipo_documento").text(datos[2]);
                $("#sucursal_genera").text(datos[3]);
                $("#cuenta_bancaria").text(datos[4]);

                $("#cuenta_banco").removeClass("oculto");
                $("#cuenta_banco").parent().show();
                $(".datos_comun").removeClass("oculto");
                $(".datos_comun").parent().show();
            }
        }else
        {
                $("#cuenta_banco").addClass("oculto");
                $("#cuenta_banco").parent().hide();
                $(".datos_comun").addClass("oculto");
                $(".datos_comun").parent().hide();
                alert($("#mensaje_forma_pago").val());
        }

   });
}
*/

function cargarDatosFormaPago()
{
var destino             = $('#URLFormulario').val();
var codigo_planilla     = $('#codigo_planilla').val();
var ano_generacion      = $('#ano_generacion').val();
var mes_generacion      = $('#mes_generacion').val();
var fecha_pago_planilla = $('#fecha_pago').val();
var periodo_pago        = $('#periodo').val();

$.getJSON(destino, {
    cargaInformacionPlanilla : true,
    codigo_planilla      : codigo_planilla,
    ano_generacion       : ano_generacion,
    mes_generacion       : mes_generacion,
    fecha_pago_planilla  : fecha_pago_planilla,
    periodo_pago         : periodo_pago
}, function(datos) {
        $("#listaPagos tbody").remove();

        for(i=0;i<datos.length;i++){

        forma_pago = datos[i].split("|");

        var valorClase = '';
        if ($("#listaPagos tr:last").hasClass("even")) {
                valorClase = 'odd';
        } else {
                valorClase = 'even';
        }

        var item =  '<tr id="'+i+'" class="'+valorClase+'">'+
        '<td align="left">'+forma_pago[1]+'</td>'+
        '<td align="left">'+forma_pago[2]+'</td>'+
        '<td align="left">'+forma_pago[3]+'</td>'+
        '<td align="left">'+forma_pago[4]+'</td>'+
        '<td align="left">'+forma_pago[5]+'</td>'+
        '</tr>';
        $('#listaPagos').append(item);
        }

});
}

   function determinarSucursalesNomina()
{
    var destino             = $('#URLFormulario').val();
    var codigo_planilla     = $('#codigo_planilla').val();
    var ano_generacion      = $('#ano_generacion').val();
    var mes_generacion      = $('#mes_generacion').val();
    var fecha_pago_planilla = $('#fecha_pago').val();
    var periodo_pago        = $('#periodo').val();

    $.getJSON(destino, {
        determinarSucursales_forma_nomina : true,
        codigo_planilla                   : codigo_planilla,
        ano_generacion                    : ano_generacion,
        mes_generacion                    : mes_generacion,
        fecha_pago_planilla               : fecha_pago_planilla,
        periodo_pago                      : periodo_pago
    }, function(datos) {

            sucursales = $('#sucursales_habilitar').val().split(',');

            for(i=0;i<(sucursales.length-1);i++){
             $(".sucursales_electrodomesticos:checkbox").removeAttr('checked');
             $(".empresas_consolidados:checkbox").removeAttr('checked');
             $(".pago_sucursales").parent().hide();
             $("#sucursales_"+sucursales[i]).parent().removeClass('oculto');
             $("#sucursales_"+sucursales[i]).parent().show();
            }

            for(i=0;i<datos.length;i++){
            $(".sucursales_electrodomesticos:checkbox").removeAttr('checked');
            $("#sucursales_"+datos[i]).parent().addClass('oculto');
            $("#sucursales_"+datos[i]).parent().hide();
            }

        });
}


function verificarPagoPlanilla()
{
   mensaje = confirm($("#mensaje_pagar_planilla").val());
   if(mensaje){
   adicionarItem();
   }
}



