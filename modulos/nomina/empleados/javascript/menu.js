$(document).ready(function() {
    ejecutarFuncionesGlobales();
});

function cargarOpcion(datos_llegada,url_id,rango_dias,rango_fecha_salud,rango_fecha_pension,rango_dias_salario)
{
    $('.ui-dialog').remove();
    var destino     = $('#URLFormulario').val();
    var ancho=640;
    var alto = 440;
    $.blockUI({
        message: $('#cuadroEspera'),
        fadeOut: 0
    });
    $('#mensajeBarraEstado').html('').removeAttr('class','style').css('display','block');

    datos_cadena = datos_llegada.split("|");

    var opcion = '0';
    pestana_contables ='0';

    if($("#translado_sucursal").is(':checked')){
        pestana_basica ='1';pestana_contrato ='1';pestana_transacciones_fijas='1';pestana_transacciones_tiempo='1';datos_llegada='0|1|1|0|1|0|1|0';
    }else{
        pestana_basica ='0';pestana_contrato ='0';pestana_transacciones_fijas='0';pestana_transacciones_tiempo='0';
    }
    /*if($("#renovar_contrato").is(':checked')){
        pestana_contrato ='1';pestana_transacciones_fijas='1';pestana_transacciones_tiempo='1';datos_llegada='0|0|1|0|1|0|1|0';
    }else{
        pestana_contrato ='1';pestana_transacciones_fijas='1';pestana_transacciones_tiempo='1';
    }*/
    if($("#entidades_parafiscales").is(':checked')){
        pestana_parafiscales ='1';datos_llegada='0|0|0|1|0|0|0|0';
    } else{
        pestana_parafiscales ='0';
    }
    if($("#ingresos_varios").is(':checked')){
        pestana_contables ='1';datos_llegada='0|0|0|0|0|1|0|0';
    }else{
        pestana_contables ='0';
    }
    if($("#modificar_transacciones").is(':checked') && !($("#renovar_contrato").is(':checked'))){
        pestana_contrato ='0';pestana_transacciones_fijas='1';pestana_transacciones_tiempo='1';datos_llegada='0|0|0|0|1|0|1|0';
    }
    if($("#modificar_salario").is(':checked')){
        modificar_salario ='1';datos_llegada='0|0|0|0|0|0|0|1|0';
    } else{
        modificar_salario ='0';
    }

    var fecha_inician_ingresos_varios = $("#fecha_ingresos_varios_calendario").val();
    var base    = $('#URLBase').val().split('=');
      /*** Descargar contenido para el cuadro de diálogo ***/
    $.getJSON(destino, {
        opcion :opcion,
        opcion_modificar_salario:modificar_salario,
        opcion_basica:pestana_basica,
        opcion_contrato:pestana_contrato,
        opcion_transacciones_fijas : pestana_transacciones_fijas,
        opcion_contables:pestana_contables,
        opcion_transacciones_tiempo : pestana_transacciones_tiempo,
        opciones : datos_llegada,
        opcion_parafiscales:pestana_parafiscales,
        cargarmenu: "cargado",
        generar: true,
        origen: base[1],
        id: url_id
    }, function(datos) {
        if (datos[0] == '') {
            var titulo = datos[1];
            $('#cuadroDialogo').html(datos[2]);
            $('#cuadroDialogo').css('height',alto - 30);
            /*** Generar el cuadro de diálogo ***/
            $('#cuadroDialogo').dialog({
                modal: true,
                draggable: false,
                resizable: false,
                title: titulo,
                width: ancho,
                height: alto,
                close: function() {
                    $('#expresionBusqueda').focus();
                },
                overlay: {
                    opacity: 0.75,
                    background: "#000000"
                }
            });
            $('#indicadorEsperaComando').width(ancho);
            $('#indicadorEsperaComando').height(alto);
            /*** Generar visualización en árbol de selección simple ***/
            $('.arbol, .arbolPerfiles').treeview({
                collapsed: true,
                animated: 'fast',
                unique: true
            });
            var rango = "";
            /*** Generar selector de fecha  ***/
            if(modificar_salario=='1'){
                rango="-"+rango_dias_salario+"D";
            }else{
                rango="-"+rango_dias+"D";
            }
            $('.selectorFecha').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    //minDate: rango,
                    maxDate: "+100y",
                    buttonImage: 'imagenes/calendario.png',
                    buttonImageOnly: true

                })).attr('readonly', 'readonly');

            var rango_salud  = "";
            var rango_pension= "";

            //////Calendario para la fecha incicio de la salud//////
            if(rango_fecha_salud >= 0)
            {
                rango_salud="-"+rango_fecha_salud+"D";
            }else
            {
                rango_fecha_salud=rango_fecha_salud * -1;
                rango_salud="+"+rango_fecha_salud+"D";
            }

            $('.selectorFechaSalud').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    minDate: rango_salud,
                    maxDate: "+100y",
                    buttonImage: 'imagenes/calendario.png',
                    buttonImageOnly: true

                })).attr('readonly', 'readonly');

            //////Calendario para la fecha incicio de la pension//////
            if(rango_fecha_pension >= 0)
            {
                rango_pension="-"+rango_fecha_pension+"D";
            }else
            {
                rango_fecha_pension=rango_fecha_pension * -1;
                rango_pension="+"+rango_fecha_pension+"D";
            }
            $('.selectorFechaPension').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    minDate: rango_pension,
                    maxDate: "+100y",
                    buttonImage: 'imagenes/calendario.png',
                    buttonImageOnly: true

                })).attr('readonly', 'readonly');



            $('.fechaAntigua').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    maxDate: "+100y",
                    yearRange: "-100:0",
                    buttonImage: 'imagenes/calendario.png',
                    buttonImageOnly: true

                })).attr('readonly', 'readonly');
            $(".fechaNuevas").attr("readonly","readonly").datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                maxDate: "+100y",
                yearRange: "-100:10",
                buttonImage: 'imagenes/calendario.png',
                buttonImageOnly: true
            });
            $(".fechaRango").attr("readonly","readonly").datepicker({
                numberOfMonths:1,
                showAnim:"fadeIn",
                buttonImage: 'imagenes/calendario.png',
                buttonImageOnly: true,
                altField: "#Range",
                dateFormat: "yy/mm/dd",
                rangeSelect: true
            });
            /*** Generar visualización de pestañas ***/
            var $pestanas = $('#pestanas > ul').tabs({
                show: function () {
                    $('*').blur();
                    $('#pestanas > .ui-tabs-panel:visible :input:first').not(".selectorFecha, .fechaAntigua, .fechaNuevas, .fechaNuevas").focus();
                    $('#pestanas > .ui-tabs-panel').css('width',ancho - 20);
                    $('#pestanas > .ui-tabs-panel').css('height',alto - 140);
                    $('#pestanas > .ui-tabs-panel').css('overflow','auto');
                }
            });
            /*** Dimensionar pestañas de acuerdo al tamaño del cuadro de diálogo ***/
            /*** Autocompletar la selección ***/
            $('.autocompletable').autocomplete(destino, {
                minChars: 3,
                width: 250,
                max: 100,
                delay: 100,
                matchContains: true,
                cacheLength: 100,
                extraParams: {
                    completar: true
                },
                formatResult: function(datos, valor) {
                    return valor.split("|")[0];
                }
            }).blur(function() {
                var x = $(this).attr('id');
                $(x).autocomplete().remove();
            });
            $('.autocompletable').result(function(evento, datos, formateado) {
                if (datos) {
                    //$(this).parent().find('input:hidden').val(datos[1]);
                    $(this).nextAll('input:first').val(datos[1]);

                    if (jQuery.isFunction(recargarDatos)) {
                        /*** Prueba ***/
                        var x = $(this).attr('id');
                        recargarDatos(datos[1], x);
                    }

                } else {
                    $(this).parent().find('input:hidden').val('');
                }

            });
            $(function() {
                $('*').tooltip({
                    track: true,
                    delay: 0,
                    showURL: false
                });
            });
            /*** Definir formato para campos con clase 'hora' (Plugin: MaskedInput) ***/
            $.mask.definitions['N']='[012345]';
            $.mask.definitions['n']='[012]';
            $.mask.definitions['m']='[0123]';
            $.mask.definitions['t']='[0123456789]';
            $('.hora').mask('nt:Nt');
            //////////////////////////////////////
              if(typeof($("#minDate").val()) != "undefined"){
                  datos_fecha = $("#minDate").val().split("-");
                  anio = parseInt(datos_fecha[0]);
                  mes  = parseInt(datos_fecha[1])-1;
                  dia  = parseInt(datos_fecha[2]);
                  var minDate= new Date(anio,mes,dia);
                  $('.selectorFechaBloquear').datepicker($.extend({},
                                $.datepicker.regional['es'], {
                                    showOn: 'both',
                                    dateFormat: 'yy-mm-dd',
                                    buttonImage: 'imagenes/calendario.png',
                                    buttonImageOnly: true,
                                    minDate : minDate
                  })).attr('readonly', 'readonly');
              }
            ////////////////////////////////////
            /*** Ocultar inicialmente todos los elementos que contengan la clase 'oculto' ***/
            $('.oculto').parent().hide();

            /*** Forzar la visualización  del cuadro de diálogo ***/
            $('#cuadroDialogo').css('display','block');
        } else {
            //$('#mensajeBarraEstado').html(datos[0]).fadeOut(3000).addClass('mensajeError');
            alert(datos[0]);
            $('#expresionBusqueda').focus();
        }
        //////////Cargar Datos///////////
        $("#oculto_fecha_inician_ingreso_varios").val(fecha_inician_ingresos_varios)

    });

}
function autoCompletableLocal(item,codigo,concepto) {
    var destino            = $('#URLFormulario').val();
    var anexo_contable     = $('#id_anexos_contables').val();

    $(item).autocomplete(destino, {
        minChars: 3,
        width: 250,
        max: 100,
        delay: 100,
        matchContains: true,
        cacheLength: 100,
        extraParams: {
            item     : $(item).attr('id'),
            verificarTransaccion: true,
            anexo    : anexo_contable,
            concepto : concepto

        },
        formatResult: function(datos, valor) {
            return valor.split("|")[0];
        }
    }).blur(function() {
        var x = $(this).attr('id');
        $(x).autocomplete().remove();
    }).result(function(evento, datos, formateado) {
        if (datos) {
            $('#'+codigo).val(datos[1]);

        } else {
            $('#'+codigo).val('');
        }
    });
}
function autoCompletableLocalModificar(item,codigo,concepto) {
    var destino            = $('#URLFormulario').val();
    var anexo_contable     = $('#oculto_id_anexos_contables').val();

    $(item).autocomplete(destino, {
        minChars: 3,
        width: 250,
        max: 100,
        delay: 100,
        matchContains: true,
        cacheLength: 100,
        extraParams: {
            item     : $(item).attr('id'),
            verificarTransaccion: true,
            anexo    : anexo_contable,
            concepto : concepto

        },
        formatResult: function(datos, valor) {
            return valor.split("|")[0];
        }
    }).blur(function() {
        var x = $(this).attr('id');
        $(x).autocomplete().remove();
    }).result(function(evento, datos, formateado) {
        if (datos) {
            $('#'+codigo).val(datos[1]);

        } else {
            $('#'+codigo).val('');
        }
    });
}
/*** Validar un elemento ***/
function validarEmpleado(url_id){
    var destino     = $('#URLFormulario').val();
    var id_empleado = $('#documento_identidad_empleado').val();

    $.getJSON(destino, {
        verificarEmpleado:true,
        id_empleado:id_empleado,
        id:url_id
    }, function(mensaje){
        if(mensaje != ""){

            $('#selector1').parent().children('#errorDialogo').remove();
            $('#selector1').focus();
            $('#selector1').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#selector1').parent().children('.mensajeErrorLinea').fadeOut(2000).addClass('mensajeError').css('display','block');
        }
    });

}
function recargarDatos(id,selector){
    if (selector == 'selector1'){
        var destino = $('#URLFormulario').val();
        var id_empleado = $('#documento_identidad_empleado').val();
        $.getJSON(destino,{
            verificarPension: true,
            id_empleado:id_empleado
        }, function(datos){
            if(datos == '1'){
                $('#codigo_entidad_pension').parent().hide();
                $('#codigo_entidad_pension').addClass('campoInactivo').attr('disabled','disabled');
                $('#pensiones').parent().hide();
                $('#pensiones').addClass('campoInactivo').attr('disabled','disabled');

            }
            else{

                $('#codigo_entidad_pension').parent().show();
                $('#codigo_entidad_pension').removeClass('campoInactivo').removeAttr('disabled','disabled');
                $('#pensiones').parent().show();
                $('#pensiones').removeClass('campoInactivo').removeAttr('disabled','disabled');

            }
        });
    }
}

function cargarAnexos(){
    var destino = $('#URLFormulario').val();
    var lista   = "";
    var id_anexos_contable = $('#codigo_empresa').val()+"|"+$('#id_anexos_contables').val();
    $.getJSON(destino,{
        verificarAnexos: true,
        id_anexos_contable:id_anexos_contable
    }, function(datos){
        if(datos != ""){
            $('#id_auxiliar_contable').parent().show();
            $('#id_auxiliar_contable').removeClass('campoInactivo').removeAttr('disabled','disabled');
            $('#id_auxiliar_contable').val(datos);

            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#id_auxiliar_contable').html(lista);
        }else{
            $('#id_auxiliar_contable').parent().hide();
            $('#id_auxiliar_contable').addClass('campoInactivo').attr('disabled','disabled');

        }
    });
}
/*** verificar Turnos dependiendo si pérmite trabajar festivos ***/
function cargarPagoTransaccion(campo_planilla){
    //console.log(campo_planilla);
    var destino         = $('#URLFormulario').val();
    var idPlanilla      = $('#'+campo_planilla).val();
    $.getJSON(destino,{
        verificarPlanilla: true,
        id_planilla:idPlanilla
    }, function(datos){

        if(datos == 2){
            // document.getElementById("periodo_pago").innerHTML="";
            $('#quincenal').parent().show();
            $('#quincenal').removeClass("oculto").removeAttr("disabled");
            $('#semanal').parent().hide();
            $('#semanal').addClass("oculto").attr("disabled");
            $('.primera_semana').removeAttr("checked");
            $('.segunda_semana').removeAttr("checked");
            $('.tercera_semana').removeAttr("checked");
            $('.cuarta_semana').removeAttr("checked");
            $('#mensual').parent().hide();
            $('#mensual').addClass("oculto").attr("disabled");
            $('.mensualidad').removeAttr("checked");
            $(".forma_pago").parent().show();
            $(".forma_pago").removeClass("oculto");

        }
        else if (datos == 3){
            // document.getElementById("periodo_pago").innerHTML="";
            $('#semanal').parent().show();
            $('#semanal').removeClass("oculto").removeAttr("disabled");
            $('#quincenal').parent().hide();
            $('#quincenal').addClass("oculto").attr("disabled");
            $('.primera_quincena').removeAttr("checked");
            $('.segunda_quincena').removeAttr("checked");
            $('#mensual').parent().hide();
            $('#mensual').addClass("oculto").attr("disabled");
            $('.mensualidad').removeAttr("checked");

            $(".forma_pago").parent().hide();
            $(".forma_pago").addClass("oculto");

        }
        else{

            // document.getElementById("periodo_pago").innerHTML= $('#pago_mensual').val();
            $('#mensual').parent().show();
            $('#mensual').removeClass("oculto").removeAttr("disabled");
            $('#semanal').parent().hide();
            $('#semanal').addClass("oculto").attr("disabled");
            $('.primera_semana').removeAttr("checked");
            $('.segunda_semana').removeAttr("checked");
            $('.tercera_semana').removeAttr("checked");
            $('.cuarta_semana').removeAttr("checked");
            $('#quincenal').parent().hide();
            $('#quincenal').addClass("oculto").attr("disabled");
            $('.primera_quincena').removeAttr("checked");
            $('.segunda_quincena').removeAttr("checked");

            $(".forma_pago").parent().hide();
            $(".forma_pago").addClass("oculto");
        }

    });

}

function agregarPeriodoPago(men,campo_planilla){
    var destino             = $('#URLFormulario').val();
    var planilla            = $("#"+campo_planilla).val();
    var idconceptoContable  = "";
    var conceptoContable    = "";
    var elemento            = "";
    var periodo             = "";
    var contador            = 0;
    var id_tiempo           = new Date();
    var valor_ingreso       = $("#valor_ingreso_vario").val();
    //var campo_valor_ingreso = "<input type='text' class='campo' onKeyPress = 'return campoEntero(event)' onKeyUp='actualizarValorIngreso(this,\""+id_tiempo.getTime() +"\")' size='10' value='"+valor_ingreso+"' >";
    var elementos = new Array();
    /*
    if($('.primera_quincena').is(':checked')){
        primeraQuincena = $('#quincena_uno').val();
        elementos[limite] = primeraQuincena;
        limite++;*/

    var lista_pagos    = $("#selector2").val();
    idconceptoContable  = $("#codigo_transaccion").val();
    conceptoContable  = $("#selector2").val();
     $.getJSON(destino,{
        tipo_planilla: true,
        id:planilla,
        idconceptoContable:idconceptoContable
    }, function(datos){

        if(!campoVacio(valor_ingreso)){

            if(datos[0]== 1){
                elemento = $('#pago_mensual').val();
                periodo  =  3;
            }else if(datos[0]== 2){
                periodo = $("#forma_descuento_ingresos_varios").val();
                if(periodo==1)
                {
                    elemento= $('#2_quincena').val();
                }else
                {
                    elemento= $('#3_quincena').val();
                }

            }else if(datos[0] == 3){
                elemento = $('#semanal').val();
                periodo = 4;
            }

            var existe=false;
            var valorClase = '';
            if ($("#listaItemsPagos tr:last").hasClass("even")) {
                valorClase = 'odd';
            } else {
                valorClase = 'even';
            }
            var boton          = $('#botonRemoverextras').html();
            var botonModificar = $('#botonModificar').html();
            var estado         = $("#oculto_estado").val();
            var calendario     ="";

            if(estado=='M'){
                calendario = '<td align="left">'+"<input type='text' class='selectorFechaIngreso' size='10' value='"+datos[2]+"' >"+'</td>';
            }
            $('#listaItemsPagos').find('.idValidarDatos').each(function () {
                id = $(this).val();

                if(idconceptoContable==id){
                    existe=true;
                }
            });
            if(idconceptoContable  != ""){
                if(!existe){
                    var item  = '<tr id="fila_'+id_tiempo+'" class="'+valorClase+'">'+
                    '<td align="center">'+
                        '<input type="hidden" class="idValidarDatos" name="idValidarDatos['+idconceptoContable+']" value="'+idconceptoContable+'">'+
                        '<input type="hidden" class="IdConceptoContable" name="IdConceptoContable[]" value="'+idconceptoContable+'">'+
                        '<input type="hidden" class="PeriodoPagoTabla" name="PeriodoPagoTabla[]" value="'+periodo+'">'+
                        '<input type="hidden" class="ConceptoContableTabla" name="ConceptoContableTabla[]" value="'+idconceptoContable+'">'+
                        '<input type="hidden" id="'+id_tiempo.getTime()  +'" class="valorIngreso" name="valorIngreso[]" value="'+valor_ingreso+'" >'+
                        '<input type="hidden"  class="nombreconceptoContable" name="nombreconceptoContable[]" value="'+conceptoContable+'">'+
                        '<input type="hidden" id="'+idconceptoContable+'" class="fechaInicial" name="fechaInicial[]" value="'+datos[2]+'">'+
                        boton +"&nbsp;"+botonModificar +
                    '</td>'+
                    '<td class="dato" align="left">'+conceptoContable+'</td>'+
                    '<td class="dato" align="left">'+datos[1]+'</td>'+
                    '<td class="dato" align="left">'+elemento+'</td>'+
                    '<td class="dato" align="left">'+valor_ingreso+'</td>'+
                    calendario+
                    '</tr>';
                    $('#listaItemsPagos').append(item);
                    lista_pagos++;
                    $("#lista_pagos").val(lista_pagos);
                    //$("#selector2").val('');
                    $("#codigo_transaccion").val("");
                    estadosChecks('1');

                    /*** Generar selector de fecha  ***/
                    $('.selectorFechaIngreso').datepicker($.extend({},
                        $.datepicker.regional['es'], {
                            showOn: 'both',
                            onSelect: function(dateText, inst) {
                                $("#"+idconceptoContable).val(dateText);
                            },
                            dateFormat: 'yy-mm-dd',
                            buttonImage: 'imagenes/calendario.png',
                            buttonImageOnly: true
                        })).attr('readonly', 'readonly');
                }else{
                    $("#selector2").parent().children('#errorDialogo').remove();
                    $("#selector2").focus();
                    $("#selector2").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men+'</span>');
                    $("#selector2").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            }
            $("#selector2").val("");
            $("#codigo_transaccion").val("");
            $("#valor_ingreso_vario").val("");
        }else{
            $("#valor_ingreso_vario").parent().children('#errorDialogo').remove();
            $("#valor_ingreso_vario").focus();
            $("#valor_ingreso_vario").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+$("#mensaje_valor_ingreso_varios").val()+'</span>');
            $("#valor_ingreso_vario").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }
    });

}

function agregarTransaccionTiempo(){
    var conceptoTiempo    = $("#selector3").val();
    var idconceptoTiempo  = $("#id_transaccion_tiempo").val();

    var lista_tiempos    = parseInt($("#lista_tiempos").val());
    var valorClase = '';
    if ($("#listaItemsPagos tr:last").hasClass("even")) {
        valorClase = 'odd';
    } else {
        valorClase = 'even';
    }

    var boton = $('#botonRemoverextras').html();
    var item  = '<tr id="fila_'+lista_tiempos+'" class="'+valorClase+'">'+
    '<td align="center">'+
    '<input type="hidden" class="idPosicionTablaTransaccion" name="idPosicionTablaTransaccion['+lista_tiempos+']" value="'+lista_tiempos+'">'+
    '<input type="hidden" class="ConceptoContableTabla" name="transaccionTiempoTabla['+lista_tiempos+']" value="'+idconceptoTiempo+'">'+
    boton+
    '</td>'+
    '<td class="dato" align="left">'+conceptoTiempo+'</td>'+
    '</tr>';
    $('#listaItemsTiempos').append(item);
    lista_tiempos++;
    $("#lista_tiempos").val(lista_tiempos);
    $("#selector3").val('');

}

function CargarRiesgoProfesional() {

    var destino     = $('#URLFormulario').val();
    var idRiesgo    = $('#id_departamento').val();
    $.getJSON(destino,{
        verificarRiesgos: true,
        id_riesgo:idRiesgo
    }, function(datos){
        if(datos != ''){
            $('#riesgo_profesional').val(datos);
       }
    });

}
/**Establece el termino del contrato seleccionado**/
function tiposTerminosDecontrato()
{
    var destino     = $('#URLFormulario').val();
    var id = $('#id_tipo_contrato').val();

    $.getJSON(destino,{
        termino: true,
        id:id
    }, function(dato){
        var termino_contrato = dato[0];
        var tipo_salario = dato[1];
        $("#termino_contrato").val(termino_contrato);
        if(termino_contrato==1 || termino_contrato==3 )
        {
            $('#fecha_vencimiento_contrato').removeClass('oculto');
            $('#fecha_vencimiento_contrato').parent().show();
        }else{
            $('#fecha_vencimiento_contrato').addClass('oculto');
            $('#fecha_vencimiento_contrato').parent().hide();
        }
        $("#tipo_salario").val(tipo_salario);
        CalcularSalarioDiario();
    });

}


function equivaleIntegral(mensaje)
{
    var PagoMensual = $("#salario_mensual").val();
    var salario_actual = $("#salario_minimo").val();
    var equivale_integral = parseInt($("#preferencia_equivale_salario_integral").val());
    var salario_integral = salario_actual*equivale_integral;
    var tipo_salario = $("#tipo_salario").val();
    mensaje = mensaje.split("$");
    mensaje = mensaje[0]+equivale_integral+mensaje[1];
    if(PagoMensual < salario_integral && tipo_salario == 1)
    {
        $('#salario_mensual').parent().children('#errorDialogo').remove();
        $('#salario_mensual').focus();
        $('#salario_mensual').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
        $('#salario_mensual').parent().children('.mensajeErrorLinea').fadeOut(30000).addClass('mensajeError').css('display','block');
    }
}

function CalcularSalarioDiario(){
    var PagoMensual      = parseInt($("#salario_mensual").val());
    var destino          = $('#URLFormulario').val();
    var termino_contrato = $("#termino_contrato").val();
    var tipo_salario     = $("#tipo_salario").val(tipo_salario);
    var salario_actual   = $("#salario_minimo").val();
    var dias_mes         = parseInt($("#dias_mes").val());
    var horas_mes        = parseInt($("#horas_mes").val());

    if(campoVacio(salario_actual)){
        salario_actual=0;
    }

    if(PagoMensual<= (salario_actual*2) && termino_contrato!=3 && tipo_salario!=1) // 3 son todos los salarios de los contratos que no tiene relacion laboral
    {
        $("#auxilio_si").attr('checked', true);
        $('#manejo_auxilio_trasnporte').parent().removeClass('oculto');
        $('#manejo_auxilio_trasnporte').parent().show();
        auxilocamposCheck('auxilio_si');

    }else{
        $("#auxilio_no").attr('checked', true);
        $("#auxilio_no").removeAttr('disabled');
        $("#auxilio_si").removeAttr('disabled');
        $('#manejo_auxilio_trasnporte').parent().addClass('oculto');
        $('#manejo_auxilio_trasnporte').parent().hide()
   }
   var horas_diarias  = (horas_mes/dias_mes);
   var HoraDia        = (PagoMensual/dias_mes);

   var valor_hora     = Math.round((HoraDia/horas_diarias));

   if(isNaN(valor_hora)){
       valor_hora     = "";
   }

   $("#salario_diario").val(valor_hora);
}


function CalcularSalarioDiarioModificar(){
    var PagoMensual      = parseInt($("#salario_mensual").val());
    var destino          = $('#URLFormulario').val();
    var termino_contrato = $("#termino_contrato").val();
    var tipo_salario     = $("#tipo_salario").val(tipo_salario);
    var salario_actual   = $("#salario_minimo").val();
    var dias_mes         = parseInt($("#oculto_dias_mes").val());
    var horas_mes        = parseInt($("#oculto_horas_mes").val());

    if(campoVacio(salario_actual)){
        salario_actual=0;
    }

    if(PagoMensual<= (salario_actual*2) && termino_contrato!=3 && tipo_salario!=1) // 3 son todos los salarios de los contratos que no tiene relacion laboral
    {
        $("#auxilio_si").attr('checked', true);
        $('#manejo_auxilio_trasnporte').parent().removeClass('oculto');
        $('#manejo_auxilio_trasnporte').parent().show();
        auxilocamposCheck('auxilio_si');

    }else{
        $("#auxilio_no").attr('checked', true);
        $("#auxilio_no").removeAttr('disabled');
        $("#auxilio_si").removeAttr('disabled');
        $('#manejo_auxilio_trasnporte').parent().addClass('oculto');
        $('#manejo_auxilio_trasnporte').parent().hide()
   }
   var horas_diarias  = (horas_mes/dias_mes);
   var HoraDia        = (PagoMensual/dias_mes);

   var valor_hora     = Math.round((HoraDia/horas_diarias));

   if(isNaN(valor_hora)){
       valor_hora     = "";
   }

   $("#salario_diario").val(valor_hora);
}

function auxilocamposCheck(item)
{

    if(item=="auxilio_no"){
        $('#manejo_auxilio_trasnporte').parent().addClass('oculto');
        $('#manejo_auxilio_trasnporte').parent().hide();
        $('#selector4').attr('disabled','disabled');
        $('#forma_pago_auxilio').attr('disabled','disabled');
        ////Limpio los datos de la transaccion fija auxilo de transporte///
        $("#codigo_transaccion_auxilio_transporte").val('');
        $("#selector4").val('');
    }else{
        PagoMensual = $("#salario_mensual").val();
        $('#manejo_auxilio_trasnporte').parent().removeClass("oculto")
        $('#manejo_auxilio_trasnporte').parent().show();
        $('#selector4').removeAttr('disabled');
        $('#forma_pago_auxilio').removeAttr('disabled','disabled');
        var idManejo=0;
        var destino     = $('#URLFormulario').val();
        var salario_actual = $("#salario_minimo").val();
        if(PagoMensual<= (salario_actual*2) )
        {
            idManejo=0;
        }else{
            idManejo=1;
        }
        $.getJSON(destino,{
            RecargarManejo: true,
            id:idManejo
        }, function(datos){
            var lista="";
           jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $("#manejo_auxilio_trasnporte").html(lista);
        });

    }
}


function removerItems(boton) {
    $(boton).parents('tr').remove();
}


function removerModificarTransaccion(boton) {

    var id_fila                = $(boton).parents('tr');
    var valor                  = $(id_fila).children("td").children('input[class="valorIngreso"]').val();
    var nombreConceptoContable = $(id_fila).children("td").children('input[class="nombreconceptoContable"]').val();
    var codigo_transaccion     = $(id_fila).children("td").children('input[class="IdConceptoContable"]').val();

    $("#valor_ingreso_vario").val(valor);
    $("#selector2").val(nombreConceptoContable);
    $("#codigo_transaccion").val(codigo_transaccion);
    $(boton).parents('tr').remove();
}


function CargarParafiscales() {
    var destino     = $('#URLFormulario').val();
    var id_empleado = $('#documento_identidad_empleado').val();
    $.getJSON(destino,{
        verificarParafiscales: true,
        id_empleado:id_empleado
    }, function(datos){
        $("#codigo_entidad_salud").val(datos[0]);
        $("#codigo_entidad_pension").val(datos[1]);
        if(datos[2]==1){
            $('#codigo_entidad_pension').parent().hide();
            $('#pensiones').parent().hide();
            $('#selector6').attr('disabled','disabled');
            $('#forma_descuento_pension').attr('disabled','disabled');

        }else{
            $('#codigo_entidad_pension').parent().show();
            $('#pensiones').parent().show();
            $('#selector6').removeAttr('disabled');
            $('#forma_descuento_pension').removeAttr('disabled');
        }
    });

}

function estadosChecks(estado){
    var  existe = true;
    idconceptoContable  = $("#codigo_transaccion").val();
    var destino = $('#URLFormulario').val();
    var planilla    = $("#id_planilla").val();
    $.getJSON(destino,{
        tipo_planilla: true,
        id:planilla
    }, function(tipo){
        $('#listaItemsPagos').find('.ConceptoContableTabla').each(function () {
            id = $(this).val();
            if(idconceptoContable==id){
                existe = false;
                estado = '1';
            }
        });
        if(tipo == 2){
            for(var i=2;i<=3;i++)
            {
                if(estado=='0' && $("#codigo_transaccion").val()!="" && existe){
                    $('.'+i+'_quincena').removeAttr("disabled")

                }
                if(estado=='1'){
                    $('.'+i+'_quincena').attr("disabled","disabled")

                }
            }
        }else if(tipo == 3){
            for( i=4;i<=7;i++)
            {
                if(estado=='0' && $("#codigo_transaccion").val()!="" && existe){
                    $('.'+i+'_semana').removeAttr("disabled")
                }
                if(estado=='1'){
                    $('.'+i+'_semana').attr("disabled","disabled")
                }
            }
        }

    });
}

function limpiarCampoTrasaccion()
{
     /////Limpio campos de transacciones contable////////
    $("#codigo_transaccion_salario").val('');
    $("#codigo_transaccion_auxilio_transporte").val('');
    $("#codigo_transaccion_salud").val('');
    $("#codigo_transaccion_pension").val('');
    ///////////////////////////////////////////////////
    $("#selector3").val('');
    $("#selector4").val('');
    $("#selector5").val('');
    $("#selector6").val('');

}

function mostrarCalendario(item,id_calendario)
{
    if($(item).is(':checked')){
        $(id_calendario).removeClass("oculto");
        $(id_calendario).parent().show();
    }else{
        $(id_calendario).addClass("oculto");
        $(id_calendario).parent().hide();
    }
}

function verificarValorIngreso(){

    var valor_minimo_ingreso_vario = parseInt($("#valor_minimo_ingreso_vario").val());
    var valor_ingreso_vario = parseInt($("#valor_ingreso_vario").val());
    var salida = true;

    if(valor_minimo_ingreso_vario > valor_ingreso_vario)
    {
        salida = false;
        alert($("#mensaje_minimo_vario").val()+valor_minimo_ingreso_vario);
    }

    return salida;
}


function adicionarTransaccionTabla(men,campo_planilla){
    var validado = verificarValorIngreso();
    if(validado){
        agregarPeriodoPago(men,campo_planilla)
    }
}

/*function actualizarValorIngreso(campo,id_campo_oculto){

    var valor_ingreso = $(campo).val();
    $("#"+id_campo_oculto).val(valor_ingreso);
}*/

/*
function validacionPreferenciasEmpresa()
{
    var destino         = $('#URLFormulario').val();
    var codigo_sucursal = $('#sucursal_labora') .val();

    $.getJSON(destino,{
        datos_preferencias_empresa: true,
        codigo_sucursal:codigo_sucursal
    },function(datos){

        if(datos[0]){
            $("#valor_minimo_ingreso_vario").val(datos[2]);
        }else{
            alert(datos[1]);
        }


    });

}*/

function habilitarHorasMes(campo){
    valor = $(campo).val();
    if(campoVacio(valor)){
        $("#horas_mes").attr('disabled','disabled');
        $("#horas_mes").val('');
    }else{
        $("#horas_mes").removeAttr('disabled');
    }
    CalcularSalarioDiario();
}

function validarDiasHoras(campo){
    valor = parseInt($(campo).val());
    horas = parseInt($("#dias_mes").val());
    numero_dias_horas = valor/24;
    if(numero_dias_horas > horas){
        $('#horas_mes').parent().children('#errorDialogo').remove();
        $('#horas_mes').focus();
        $('#horas_mes').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+$("#mensaje_dias_horas").val()+'</span>');
        $('#horas_mes').parent().children('.mensajeErrorLinea').fadeOut(30000).addClass('mensajeError').css('display','block');
    }else{
        $("#errorDialogo").remove();
    }
    CalcularSalarioDiario();
}

function fechaRetroactivo(){
    var fecha_salario     = $("#fecha_inicial_salario").val();
    var fecha_retoractivo = $("#fecha_retroactivo").val();
    if (fecha_salario < fecha_retoractivo){
        $("#fecha_retroactivo").val($("#fecha_inicial_salario").val());
    }
}

function validarFechaRetroactivo(){
    var fecha_salario     = $("#fecha_inicial_salario").val();
    var fecha_retoractivo = $("#fecha_retroactivo").val();
    if (fecha_retoractivo>fecha_salario){
        $("#fecha_retroactivo").val($("#fecha_inicial_salario").val());
    }
}
