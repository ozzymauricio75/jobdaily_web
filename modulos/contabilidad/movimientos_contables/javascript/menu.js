    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    var contadorBanco  = 0;

    function verificarPeriodoContable() {

        var destino  = $('#URLFormulario').val();
        var fecha    = $('#fecha_contabilizacion').val();
        var sucursal = $('#sucursal_genera').val();
        $('#sucursal_genera2').val(sucursal);
        var modulo   = $('#modulo').val();

        $.getJSON(destino, {calcular_periodo_contable: true, fecha: fecha, sucursal: sucursal, modulo:modulo}, function(datos) {
            var error           = parseInt(datos[0]);
            var MensajeError    = datos[1];

            if(error==0) {
                $('#sucursal_genera').parent().children('#errorDialogo').remove();
                $('#sucursal_genera').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+MensajeError+'</span>');
                $('#sucursal_genera').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                $('#sucursal_genera').focus();
                $('#periodo_activo').val('0');
            }else{
                $('#periodo_activo').val('1');
            }
            recargarDatosDocumento();
        });
    }

    function recargarDatos(cuenta, selector) {
        if (selector == 'selector2') {
            setTimeout("recargarDatosCuenta()",200);
        }
    }

    function consecutivoCheque(){
        $('#cuenta_bancaria2').val($('#cuenta_bancaria').val());
        var destino = $('#URLFormulario').val();
        var llave   = $('#cuenta_bancaria').val();
        $('#id_cuenta').val(llave.split('|')[8]);
        $.getJSON(destino, {recargar_consecutivo_cheque: true, cuenta: llave}, function(datos){
            $('#consecutivo_cheque').val(datos[0]);
            $('#selector2').val(datos[1]);
            $('#selector2').attr("disabled","disabled");
            $('#sentido').val(datos[5]);
            $('#sentido').attr("disabled","disabled");
            var lista ='<option value="'+datos[2]+'">'+datos[3]+'</option>';
            $('#auxiliar_contable').removeAttr("disabled");
            $('#auxiliar_contable').html(lista);
        });
    }

    function recargarDatosCuenta() {
        var destino              = $('#URLFormulario').val();
        var idCuenta             = $("#id_cuenta").val();
        var fecha                = $('#fecha_contabilizacion').val();
        var descripcionTercero   = "";
        var idTercero            = "";
        var lista                = "";
        var saldos               = false;
        var anexos               = false;

        $('#valor_base1').attr("disabled","disabled").parent().hide();
        $('#valor_base2').attr("disabled","disabled").parent().hide();
        $('#cantidad_vencimientos').attr("disabled","disabled").parent().hide();
        $('#intervalo').attr("disabled","disabled").parent().hide();
        $('#auxiliar_contable').attr("disabled","disabled").parent().hide();
        $('#selector4').attr("disabled","disabled").parent().hide();
        $('#selector5').attr("disabled","disabled").parent().hide();
        $('#documento_saldo').attr("disabled","disabled").parent().hide();
        $('#tipo_documento_soporte').attr("disabled","disabled").parent().hide();
        $('#numero_documento_soporte').attr("disabled","disabled").parent().hide();
        $('#tipo_documento_bancario').attr("disabled","disabled").parent().hide();
        $('#numero_documento_bancario').attr("disabled","disabled").parent().hide();
        $('#valor_maximo').attr("disabled","disabled").parent().hide();
        $('#documento_saldo').html('<option value="0"></option>');
        $('#auxiliar_contable').html('<option value="0||0"></option>');
        $('#valor_maximo').val('0');
        $('#tipo_documento_soporte').val('0');
        $('#numero_documento_soporte').val('');
        $('#tipo_documento_bancario').val('0');
        $('#numero_documento_bancario').val('');
        $('#valor_maximo').val('');

        $.getJSON(destino, {recargarDatosCuenta: true, id: idCuenta, fecha: fecha}, function(datos) {
            jQuery.each(datos, function(id, dato){
                if(id == "saldo"){
                    if(dato == '1'){
                        saldos = true;
                    }
                    $('#maneja_saldos').val(dato);
                }else if(id == "tercero"){
                    /*** Mostrar datos de tercero, si aplican cuentas por tercero ***/
                    if(dato == '1'){
                        $('#selector3').removeAttr("disabled").parent().show();
                        descripcionTercero = $('#selector1').val();
                        idTercero          = $('#id_tercero').val();
                    } else {
                        $('#selector3').attr("disabled","disabled").parent().hide();
                    }
                    $('#maneja_tercero').val(dato);
                    if(idTercero!='0'){
                        $('#selector3').val(descripcionTercero);
                        $('#id_tercero_cuenta').val(idTercero);
                    }
                }else if(id == "tasa1"){
                    if(dato>0){
                         $('#tasa1').val(dato);
                         $('#valor_base1').removeAttr("disabled").parent().show();
                         validarValor();
                    }
                }else if(id == "tasa2"){
                    if(dato>0){
                         $('#tasa1').val(dato);
                         $('#valor_base1').removeAttr("disabled").parent().show();
                         validarValor();
                    }
                }else if(id == "flujo"){
                    if (dato == '3') {
                        var plan_cuenta = $('#cuenta_bancaria').val();
                        if (plan_cuenta != "0" && typeof(plan_cuenta)!="undefined") {
                            plan_cuenta = plan_cuenta.split("|")[8];
                        }

                        $('#tipo_documento_soporte').val('0');
                        $('#numero_documento_soporte').val('');
                        $('#tipo_documento_soporte').attr("disabled","disabled").parent().hide();
                        $('#numero_documento_soporte').attr("disabled","disabled").parent().hide();

                        if(plan_cuenta != idCuenta){
                            $('#tipo_documento_bancario').removeAttr("disabled").parent().show();
                            $('#numero_documento_bancario').removeAttr("disabled").parent().show();

                        }else{
                            $('#sentido').val('C');
                        }
                    }
                }else if(id == "sentido"){

                    var sentido        = $('#sentido').val();
                    var tercero        = $('#id_tercero').val();
                    var cuenta         = $('#id_cuenta').val();

                    $('#sentido_cuenta').val(dato);

                    if((sentido == 'D' && dato == 'C' && saldos) || (sentido == 'C' && dato == 'D' && saldos)){
                        var documentos     = '';
                        var primer_dato    = true;
                        var contadorSaldos = 0;
                        $.getJSON(destino, {recargarDatosSaldo: true, id_tercero: tercero, id_cuenta: cuenta}, function(datos_saldo) {
                            jQuery.each(datos_saldo, function(id_saldo, dato_saldo){
                                /*** Verifica que no se muestren los saldo que ya se adicionaron a la tabla ***/
                                var valorSaldo = validarAbonosCorrectos(id_saldo,dato_saldo);
                                if(valorSaldo>0){
                                    /*** Cambia el valor del saldo restante despues de haberse adicionado a la tabla ***/
                                    var texto_saldo = reemplazarValores(id_saldo,dato_saldo,valorSaldo);
                                    documentos = documentos+texto_saldo;

                                    $('#documento_saldo').removeAttr("disabled").parent().show();
                                    if (primer_dato == true) {
                                        primer_dato = false;
                                        $('#valor').val(valorSaldo);
                                        $('#valor_maximo').val(valorSaldo);
                                        $('#valor_maximo').removeAttr("disabled").parent().show();
                                    }
                                    contadorSaldos++;
                                }
                            });
                            $('#documento_saldo').html(documentos);
                            $('#documento_saldo').val('');
                            if(contadorSaldos==0){
                                $('#documento_saldo').attr("disabled","disabled").parent().hide();
                                $('#documento_saldo').html('<option value="0"></option>');
                                alert($('#error_no_saldos').val());
                            }else{
                                $('#tipo_documento_soporte').parent().show();
                                $('#numero_documento_soporte').parent().show();
                                var saldoDatos = $('#documento_saldo').val().split('/')[1].split('|');
                                $('#tipo_documento_soporte').val(saldoDatos[4]);
                                $('#numero_documento_soporte').val(saldoDatos[5]);
                            }
                        });
                    }else if((sentido == 'C' && dato == 'C' && saldos) || (sentido == 'D' && dato == 'D' && saldos)){
                        $('#cantidad_vencimientos').removeAttr("disabled").parent().show();
                        $('#intervalo').removeAttr("disabled").parent().show();
                        $('#tipo_documento_soporte').val('0');
                        $('#numero_documento_soporte').val('');
                    }
                }else if(id == "anexo_contable"){
                    if(dato != ''){
                        anexos = true;
                    }
                }else{
                    if(anexos && dato){
                        lista = lista+'<option value="'+id+'">'+dato+'</option>';
                        $('#auxiliar_contable').removeAttr("disabled").parent().show();
                        $('#auxiliar_contable').html(lista);
                    }
                }
            });
        });
    }

    function recargarDatosDocumento() {
        var destino     = $('#URLFormulario').val();
        var documento   = $('#tipo_documento option:selected').val();
        var fecha_contabilizacion = $('#fecha_contabilizacion').val();
        $('#tipo_documento2').val(documento);
        var sucursal    = $('#sucursal_genera').val();
        var lista       = '';
        var genera      = false;/*** Variable que me dice si el documento genera cheque o no ***/
        var contador    = 0;
        $('#id_cuenta').val('');
        $('#selector2').val('');
        $('#selector2').removeAttr("disabled");
        $('#sentido').removeAttr("disabled");
        $('#cuenta_bancaria').html('<option value="0"></option>');
        $('#cuenta_bancaria2').val('');
        $('#cuenta_bancaria').attr("disabled","disabled").parent().hide();
        $('#consecutivo_cheque').val('');
        $('#consecutivo_cheque').attr("disabled","disabled").parent().hide();

        $('#auxiliar_contable').attr("disabled","disabled").parent().hide();
        $('#auxiliar_contable').html(lista);

        $.getJSON(destino, {recargarDatosDocumento: true, documento: documento, sucursal: sucursal, fecha : fecha_contabilizacion}, function(datos) {
            jQuery.each(datos, function(id, dato){
                if(id=="manejo"){
                    $("manejo_automatico").val(dato);
                }else if(id == "consecutivo_documento"){
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
                $('#cuenta_bancaria2').val('');
            }else if(genera && contador>0){
                consecutivoCheque();
            }
        });
    }

    function validarValor() {/*** Metodo que valida que si existe un abono el valor no sea superior al saldo ***/
        var valor   = parseInt($('#valor').val());
        var maximo  = parseInt($('#valor_maximo').val());
        var tasa1   = parseFloat($('#tasa1').val());
        var tasa2   = parseFloat($('#tasa2').val());

        if ((valor > maximo) && (maximo >0 && typeof(maximo)!="undefined")) {
            $('#valor').val(maximo);
            valor = maximo;
        }

        if (tasa1 != 0) {
            var valor_base1 = (valor * 100) / tasa1;
            if(isNaN(valor_base1)){
                $('#valor_base1').val('');
            }else{
                $('#valor_base1').val(valor_base1);
            }
        }
        if (tasa2 != 0) {
            var valor_base2 = (valor * 100) / tasa2;
            if(isNaN(valor_base2)){
                $('#valor_base2').val('');
            }else{
                $('#valor_base2').val(valor_base2);
            }
        }
    }

    function cambiarValorAbono() {/*** Soporta el metodo de validarValor() ***/
        var documento_saldo = $('#documento_saldo').val();
        $('#valor').val(documento_saldo.split("/")[3]);
        $('#valor_maximo').val(documento_saldo.split("/")[3]);
        var saldoDatos = documento_saldo.split('/')[1].split('|');
        $('#tipo_documento_soporte').val(saldoDatos[4]);
        $('#numero_documento_soporte').val(saldoDatos[5]);
    }

    function agregarItem() {
        var sucursal                    = $('#sucursal_contabiliza').val();
        var cuenta                      = $('#id_cuenta').val();
        var sentido_cuenta              = $('#sentido_cuenta').val();
        var sentido                     = $('#sentido').val();
        var tercero                     = $('#id_tercero_cuenta').val();
        var tercero1                    = '0';
        var tercero2                    = '0';
        var documento_soporte           = $('#tipo_documento_soporte').val();
        var numero_soporte              = $('#numero_documento_soporte').val();
        var documento_bancario          = $('#tipo_documento_bancario').val();
        var numero_bancario             = $('#numero_documento_bancario').val();
        var auxiliar                    = $('#auxiliar_contable').val();
        var cuotas                      = $('#cantidad_vencimientos').val();
        var intervalos                  = parseInt($('#intervalo').val());
        var valor                       = parseInt($('#valor').val());
        var maneja_saldos               = $('#maneja_saldos').val();
        var maneja_tercero              = $('#maneja_tercero').val();
        var maximo                      = parseInt($('#valor_maximo').val());
        var valor_valido                = false;
        var fila                        = $('#consecutivo_fila').val();
        var boton_remover               = $('#removedor').html();
        var documento_saldo             = $('#documento_saldo').val();
        var llave_saldo                 = $('#documento_saldo').val();
        var documento_saldo_id          = 0;
        var documento_saldo_consecutivo = 0;
        var documento_saldo_valor       = 0;
        var documento_saldo_texto       = '';
        var mensaje                     = '';
        var opciones                    = '';
        var sucursal                    = $('#sucursal_contabiliza').val();
        var sucursal_texto              = $('#sucursal_contabiliza option:selected').text();
        var cuenta_texto                = $('#selector2').val().split("|")[0];
        var sentido                     = $('#sentido').val();
        var tercero_texto               = $('#selector3').val();

        /*** Validar que el valor del movimiento no sea mayor al valor maximo de abono del saldo, si aplica ***/
        if(maneja_saldos=='1'){
            if (maximo >0 && typeof(maximo)!="undefined") {
                if(valor <= maximo){
                valor_valido = true;
                }
            }else{
                valor_valido = true;
            }
        }else{
            valor_valido = true;
        }

        if ($('#tipo_documento_bancario').is(':disabled')){
            documento_bancario = '0';
            numero_bancario    = '';
        }

        comprobarCuentaEnTabla(cuenta,sentido,maneja_saldos);
        mensaje = '';

        if(cuenta==''){
            $('#botonAgregar').parent().children('#errorDialogo').remove();
            $('#botonAgregar').focus();
            mensaje = $('#cuenta_vacia').val();
            $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }else if($('#estado_insertar').val()=='N'){
            $('#botonAgregar').parent().children('#errorDialogo').remove();
            $('#botonAgregar').focus();
            if($('#cual_mensaje_error_cuentas').val()=='2'){
                mensaje = $('#denegado_registro_cuenta2').val();
            }else if($('#cual_mensaje_error_cuentas').val()=='1'){
                mensaje = $('#denegado_registro_cuenta').val();
            }
            $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }else if (valor == 0 || isNaN(valor)){
            $('#botonAgregar').parent().children('#errorDialogo').remove();
            $('#botonAgregar').focus();
            mensaje = $('#ingresar_valor').val();
            $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        } else if (maneja_tercero == '1' && tercero=='') {
            $('#botonAgregar').parent().children('#errorDialogo').remove();
            $('#botonAgregar').focus();
            mensaje = $('#ingresar_tercero').val();
            $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }  else if (!valor_valido) {
            $('#botonAgregar').parent().children('#errorDialogo').remove();
            $('#botonAgregar').focus();
            mensaje = $('#ingresar_valor_correcto').val();
            $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        } else if (!$('#cantidad_vencimientos').is(':disabled') && (cuotas == '0' || cuotas == '')){
            $('#botonAgregar').parent().children('#errorDialogo').remove();
            $('#botonAgregar').focus();
            mensaje = $('#cuotas_vacia').val();
            $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        } else if (!$('#intervalo').is(':disabled') && isNaN(intervalos)){
            $('#botonAgregar').parent().children('#errorDialogo').remove();
            $('#botonAgregar').focus();
            mensaje = $('#intervalos_vacio').val();
            $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }else {
            /*** Verificar si esta definido documento para abono de saldo ***/

            if(documento_saldo=='0' && maneja_saldos=='1' && $('#cantidad_vencimientos').is(':disabled')){
                $('#botonAgregar').parent().children('#errorDialogo').remove();
                $('#botonAgregar').focus();
                mensaje = $('#error_no_saldos').val();
                $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                return false;
            }else if (documento_saldo != '0') {
                documento_saldo             = $('#documento_saldo').val().split("/");
                documento_saldo_id          = documento_saldo[1];
                documento_saldo_consecutivo = documento_saldo[2];
                documento_saldo_valor       = documento_saldo[3];
                documento_saldo_texto       = documento_saldo[4];

                opciones = opciones+'<span class="etiqueta">Abono a documento:</span>'+documento_saldo_texto+' No. '+documento_saldo_consecutivo+' - Saldo total: $'+documento_saldo_valor+'<br/><br/>';
            }

            cuotas = parseInt(cuotas);

            if (!$('#auxiliar_contable').is(':disabled') && auxiliar!="000||00000000") {
                var auxiliar_texto = $('#auxiliar_contable option:selected').text();
                opciones = opciones+'<span class="etiqueta">Auxiliar:</span>'+auxiliar_texto+'<br/><br/>';
            }

            var tipo_cruce_texto   = '';
            var numero_cruce_texto = '';

            if ($('#tipo_documento_soporte').is(':disabled') && !$('#documento_saldo').is(':disabled')) {
                tipo_cruce_texto   = $('#tipo_documento_soporte option:selected').text();
                numero_cruce_texto = $('#numero_documento_soporte').val();
            }else{
                documento_soporte = '0';
                numero_soporte    = '';
            }

            if(!$('#cantidad_vencimientos').is(':disabled') && cuotas>1 && intervalos==0){
                $('#botonAgregar').parent().children('#errorDialogo').remove();
                $('#botonAgregar').focus();
                mensaje = $('#intervalos_invalidos').val();
                $('#botonAgregar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                $('#botonAgregar').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                return false;
            }else if(!$('#cantidad_vencimientos').is(':disabled')){
                var valor_cuota         = 0;
                var suma_cuotas         = 0;
                var fecha               = new Date();
                var milisecSumar        = parseInt(parseInt(intervalos)*24*60*60*1000);
                var valor_tabla         = '';
                var fechas              = '';
                var alineacion_valor    = 'left';
                /*** Generar fechas de vencimientos para el saldo ***/
                if (cuotas != 0) {
                    opciones = opciones+'<span class="etiqueta">Total saldo:</span>'+formatoNumero(valor)+'<br/><br/>';
                    for (var i=1; i <= cuotas; i++) {

                        if(i==cuotas){
                         valor_cuota = valor-suma_cuotas;
                        }else{
                            valor_cuota = parseInt(parseInt(valor)/cuotas);
                            suma_cuotas = suma_cuotas+valor_cuota;
                        }

                        fecha.setTime(fecha.getTime()+milisecSumar);
                        var dia         = fecha.getDate();
                        var mes         = fecha.getMonth()+1;
                        var ano         = fecha.getUTCFullYear();
                        if(mes<10){
                            mes = '0'+mes;
                        }
                        if(dia<10){
                            dia = '0'+dia;
                        }
                        var fecha_cuota = (ano+'-'+mes+'-'+dia);
                        fecha           = new Date(ano, mes-1, dia);
                        valor_tabla     = valor_tabla + '<span class="etiqueta">Saldo '+i+':</span>'+
                                                        '<input id="fechas_'+fila+'['+i+']" class="selectorFecha" type="text" value="'+fecha_cuota+'" maxlength="10" size="8" name="fechas_'+fila+'['+i+']" alt="" onClick="pegarFecha(this)" onChange="validarFechas(this)"/>'+
                                                        '<img class="ui-datepicker-trigger" src="imagenes/calendario.png">'+
                                                        '<input id="saldos_'+fila+'['+i+']" class="saldos" type="text" value="'+valor_cuota+'" maxlength="10" size="8" name="saldos_'+fila+'['+i+']" alt = "" onKeyUp="validarSaldo(this)"/><br/><br/>';
                    }
                }
            } else {
                valor_tabla = formatoNumero(valor);
                alineacion_valor    = 'right';
            }

            /*** Validar si se esta generando un cheque y se esta ingresando la cuenta correspondiente a la cuenta bancaria ***/
            var plan_cuenta = $('#cuenta_bancaria').val();
            var cheque      = $('#consecutivo_cheque').val();
            if (plan_cuenta) {
                plan_cuenta = plan_cuenta.split("|")[8];
            }
            if (plan_cuenta == cuenta) {
                opciones = opciones+'<span class="etiqueta">Cheque:</span>'+cheque+'<br/>';
            }

            /*** Ubicar valores debitos y creditos en la columna respectiva ***/
            var valor_debito    = '';
            var valor_credito   = '';
            if (sentido == 'D') {
                valor_debito = valor_tabla;
            } else {
                valor_credito = valor_tabla;
            }
            /*** Determinar clase de la fila ***/
            var valorClase = 'even';
            if ($("#listaItems tr:last").hasClass("even")) {
                valorClase = 'odd';
            }

            /*** Generar codigo requerido para insercion de fila en tabla ***/
            var item  = '<tr id="fila_'+fila+'" class="'+valorClase+'">'+
                            '<td align="center" width="8%">'+
                                '<input type="hidden" class="movimientos" name="movimientos['+fila+']" value="'+fila+'">'+
                                '<input type="hidden" class="sucursales" name="sucursales['+fila+']" value="'+sucursal+'">'+
                                '<input type="hidden" class="cuentas" name="cuentas['+fila+']" value="'+cuenta+'">'+
                                '<input type="hidden" class="senTcuentas" name="senTcuentas['+fila+']" value="'+sentido_cuenta+'">'+
                                '<input type="hidden" class="sentidos" name="sentidos['+fila+']" value="'+sentido+'">'+
                                '<input type="hidden" class="abonos" name="abonos['+fila+']" value="'+llave_saldo+'">'+
                                '<input type="hidden" class="terceros" name="terceros['+fila+']" value="'+tercero+'">'+
                                '<input type="hidden" class="auxiliares" name="auxiliares['+fila+']" value="'+auxiliar+'">'+
                                '<input type="hidden" class="documentos_soportes" name="documentos_soportes['+fila+']" value="'+documento_soporte+'">'+
                                '<input type="hidden" class="numeros_documentos_soportes" name="numeros_documentos_soportes['+fila+']" value="'+numero_soporte+'">'+
                                '<input type="hidden" class="documentos_bancarios" name="documentos_bancarios['+fila+']" value="'+documento_bancario+'">'+
                                '<input type="hidden" class="numeros_documentos_bancarios" name="numeros_documentos_bancarios['+fila+']" value="'+numero_bancario+'">'+
                                '<input type="hidden" class="terceros1" name="terceros1['+fila+']" value="'+tercero1+'">'+
                                '<input type="hidden" class="terceros2" name="terceros2['+fila+']" value="'+tercero2+'">'+
                                '<input type="hidden" class="valores" name="valores['+fila+']" value="'+valor+'">'+
                                boton_remover+
                            '</td>'+
                            '<td align="left">'+sucursal_texto+'</td>'+
                            '<td align="left">'+cuenta_texto+'</td>'+
                            '<td align="left">'+tercero_texto+'</td>'+
                            '<td align="left">'+opciones+'</td>'+
                            '<td align="left">'+tipo_cruce_texto+'</td>'+
                            '<td align="left">'+numero_cruce_texto+'</td>'+
                            '<td align="'+alineacion_valor+'" width="14%">'+valor_debito+'</td>'+
                            '<td align="'+alineacion_valor+'" width="14%">'+valor_credito+'</td>'
                        '</tr>';

            $('#listaItems').prepend(item);
            /*** Aplicar clase Datepicker a selectores de fecha de la fila insertada en la tabla ***/
            $('.selectorFecha').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    dateFormat: 'yy-mm-dd',
            })).attr('readonly', 'readonly');

            /*** Retornar valores iniciales a campos del formulario ***/
            $('#valor').val('0');
            $('#valor_maximo').val('0');
            $('#cantidad_vencimientos').val('0');
            $('#intervalo').val('0');
            $('#selector2').val('');
            $('#selector2').removeAttr("disabled");
            $('#sentido').removeAttr("disabled");
            $('#id_cuenta').val('');
            $('#sentido_cuenta').val('');
            $('#selector3').val('');
            $('#id_tercero_cuenta').val('');
            $('#selector4').val('');
            $('#selector5').val('');
            $('#auxiliar_contable').html('<option value="0||0"> </option>');
            $('#saldo_documento').html('<option value="0"> </option>');
            $('#documento_saldo').html('<option value="0"> </option>');

            $('#cantidad_vencimientos').attr("disabled","disabled").parent().hide();
            $('#valor_base1').attr("disabled","disabled").parent().hide();
            $('#valor_base2').attr("disabled","disabled").parent().hide();
            $('#intervalo').attr("disabled","disabled").parent().hide();
            $('#selector3').attr("disabled","disabled").parent().hide();
            $('#selector4').attr("disabled","disabled").parent().hide();
            $('#selector5').attr("disabled","disabled").parent().hide();
            $('#auxiliar_contable').attr("disabled","disabled").parent().hide();
            $('#saldo_documento').attr("disabled","disabled").parent().hide();
            $('#documento_saldo').attr("disabled","disabled").parent().hide();
            $('#tipo_documento_soporte').val('0');
            $('#tipo_documento_soporte').attr("disabled","disabled").parent().hide();
            $('#numero_documento_soporte').val('');
            $('#numero_documento_soporte').attr("disabled","disabled").parent().hide();
            $('#tipo_documento_bancario').val('');
            $('#tipo_documento_bancario').attr("disabled","disabled").parent().hide();
            $('#numero_documento_bancario').val('0');
            $('#numero_documento_bancario').attr("disabled","disabled").parent().hide();
            /*** Calcular totales debito y credito de los movimientos ***/
            var total_debito    = parseInt($('#total_debito').text().split(",").join(""));
            var total_credito   = parseInt($('#total_credito').text().split(",").join(""));
            var nuevo_debito    = 0;
            var nuevo_credito   = 0;
            if (sentido == 'D') {
                nuevo_debito = total_debito + parseInt(valor);
                $('#total_debito').text(formatoNumero(nuevo_debito));
            } else {
                nuevo_credito = total_credito + parseInt(valor);
                $('#total_credito').text(formatoNumero(nuevo_credito));
            }
            /*** Sugerir sentido y valor del siguiente movimiento ***/
            var total_debito    = parseInt($('#total_debito').text().split(",").join(""));
            var total_credito   = parseInt($('#total_credito').text().split(",").join(""));
            if (total_debito > total_credito) {
                $('#valor').val(total_debito - total_credito);
                $('#sentido').val('C');
            } else if (total_credito > total_debito) {
                $('#valor').val(total_credito - total_debito);
                $('#sentido').val('D');
            } else {
                $('#valor').val('0');
            }

            /*** Contador para id de filas de tabla ***/
            fila++;
            $('#consecutivo_fila').val(fila);

            contadorBanco++;
            $('#tipo_documento').attr("disabled","disabled");
            $('#cuenta_bancaria').attr("disabled","disabled");
            $('#sucursal_genera').attr("disabled","disabled");
        }
    }

    function removerItem(boton) {
        var valor           = parseInt($(boton).prevAll("input[class='valores']").val());
        var sentido         = $(boton).prevAll("input[class='sentidos']").val();
        var total_debito    = parseInt($('#total_debito').text().split(",").join(""));
        var total_credito   = parseInt($('#total_credito').text().split(",").join(""));
        var nuevo_debito    = 0;
        var nuevo_credito   = 0;
        if (sentido == 'D') {
            nuevo_debito = total_debito - valor;
            $('#total_debito').text(formatoNumero(nuevo_debito));
        } else {
            nuevo_credito = total_credito - valor;
            $('#total_credito').text(formatoNumero(nuevo_credito));
        }
        $(boton).parents('tr').remove();
        contadorBanco = contadorBanco-1;
        if(contadorBanco==0){
            $('#tipo_documento').removeAttr("disabled");
            $('#cuenta_bancaria').removeAttr("disabled");
            $('#sucursal_genera').removeAttr("disabled");
            var llave   = $('#cuenta_bancaria').val();
            setTimeout("recargarDatosDocumento()",200);
        }
    }

    function validarSaldo(saldo) {/*** Metodo que me valida el valor de las cuotas en la tabla ***/
        var id_saldo        = $(saldo).attr('id');
        var valor_saldo     = parseInt($(saldo).val());
        var saldos          = $(saldo).parent().children('input[class="saldos"]');
        var saldos_prev     = $(saldo).prevAll('input[class="saldos"]');/*** Todos los saldos anteriores al actual ***/
        var saldos_next     = $(saldo).nextAll('input[class="saldos"]');/*** Todos los saldos posteriores al actual ***/
        var id_saldo_last   = $(saldo).parent().children('input[class="saldos"]:last').attr('id');
        var valor_elemento  = 0;

        if(isNaN(valor_saldo)){
            valor_saldo = 0;
        }

        var total_saldos    = 0;
        jQuery.each(saldos, function(id, elemento){
            valor_elemento = parseInt($(elemento).val());
            if(isNaN(valor_elemento)){
                valor_elemento = 0;
            }
            total_saldos = total_saldos + valor_elemento;
        });
        var valor_total     = parseInt($(saldo).parent().prevAll(':last').children('input[class="valores"]').val());

        if (total_saldos != valor_total) {
            var total_prev  = valor_saldo;
            jQuery.each(saldos_prev, function(id, elemento){
                valor_elemento = parseInt($(elemento).val());
                if(isNaN(valor_elemento)){
                    valor_elemento = 0;
                }
                total_prev  = total_prev + valor_elemento;
            });

            var diferencia = valor_total - total_prev;
            if (id_saldo == id_saldo_last) {
                $(saldo).val(parseInt(diferencia + valor_saldo));
            } else {
                var saldos_restantes    = parseInt(diferencia / saldos_next.length);
                var suma_saldos         = 0;
                var contador            = 1;
                jQuery.each(saldos_next, function(id, elemento){
                    if(contador==saldos_next.length){
                        saldos_restantes  = diferencia-suma_saldos;
                    }
                    suma_saldos = suma_saldos+saldos_restantes;
                    $(elemento).val(saldos_restantes);
                    contador++;
                });
            }
        }
    }

    function formatoNumero(numero) {
        numero          = numero.toString();
        var numeroComas = '';
        cont            = 0;
        for ( m=numero.length; m>=0; m--) {
            if (cont != 3) {
                numeroComas = numero.charAt(m-1)+numeroComas;
                cont++;
            } else {
                cont = 0;
                if (m != 0) {
                    numeroComas = ','+numeroComas;
                    m++;
                }
            }
        }
        return numeroComas;
    }

    function inicialize(campo){
        valor = $(campo).val();
        if (valor == 0){
           $(campo).val("");
        }
    }

    function activarPestana(){/*** Valida los campos obligatorios de la primera pestana para generar item en las tablas ***/
        var documento = parseInt($('#tipo_documento').val());
        if($('#periodo_activo').val()=='0'){
            $('#pestanas > ul').tabs("disable",1);
            var MensajeError    = $('#periodo_activo_error').val();
            $('#sucursal_genera').parent().children('#errorDialogo').remove();
            $('#sucursal_genera').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+MensajeError+'</span>');
            $('#sucursal_genera').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
            $('#sucursal_genera').focus();
        }else if(documento == 0){
            $('#pestanas > ul').tabs("disable",1);
            $('#tipo_documento').parent().children('#errorDialogo').remove();
            $('#tipo_documento').focus();
            var mensaje = $('#seleccione_documento').val();
            $('#tipo_documento').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $('#tipo_documento').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }else if($('#maneja_cheque').val()=='1' && $('#cuenta_bancaria').val()=='0'){
            $('#pestanas > ul').tabs("disable",1);
            var mensaje = $('#error_no_cuentas_bancarias').val();
            alert(mensaje);
        }else{
            $('#pestanas > ul').tabs("enable",1);
        }
        if(!$('#tipo_documento').is(':disabled')){
        contadorBanco  = 0;
        }
    }

    function limpiarTercero(){
        if($('#selector1').val()==""){
            $('#id_tercero').val('0');
        }
    }

    function limpiarTerceroCuenta(){
        if($('#selector3').val()==""){
            $('#id_tercero_cuenta').val('');
        }
    }

    function limpiarCuenta(){
        if($('#selector2').val()==""){
            $('#id_cuenta').val('');
        }
    }

    /*** Cuando se cambia una fecha de un saldo en la tabla se pega la fecha en un campo auxiliar por si existe un cruce de estas ***/
    function pegarFecha(campo){
        $('#fecha_soporte').val($(campo).val());
    }

    /*** Verifica que no existan fechas iguales dentro de los saldos ya que esta es la diferencia en la llave***/
    function validarFechas(campo){
        var fecha          = $(campo).val();
        var id_fecha       = $(campo).attr('id');
        var padre          = $(campo).parent();
        var fechas         = $(campo).parent().children('input[class="selectorFecha hasDatepicker"]');
        var contador       = 0;
        var valor_elemento = '';
        var id_elemento    = '';
        jQuery.each(fechas, function(id, elemento){
            valor_elemento = $(elemento).val();
            id_elemento    = $(elemento).attr('id');
            if((id_elemento != id_fecha)&&(valor_elemento==fecha)){
                contador++;
            }
        });
        if(contador>0){
            alert($('#existe_fecha').val());
            $(campo).val($('#fecha_soporte').val());
        }
    }

    /*** Me verifica que si ya existe el abono en la tabla le reste al saldo y si la diferencia es cero entonces que no lo muestre ***/
    function validarAbonosCorrectos(idSaldo){
        var valorSaldo   = parseInt(idSaldo.split("/")[3]);
        var valores      = 0;
        var columna      = '';
        var idSaldoTabla = '';
        var llaveDescompuesta = idSaldo.split("/");
        llaveDescompuesta = llaveDescompuesta[0]+'/'+llaveDescompuesta[1]+'/'+llaveDescompuesta[2];
        $('#listaItems').find('.abonos').each(function () {
            idSaldoTabla = $(this).val();
            idSaldoTabla = idSaldoTabla.split("/");
            idSaldoTabla = idSaldoTabla[0]+'/'+idSaldoTabla[1]+'/'+idSaldoTabla[2];
            if(llaveDescompuesta==idSaldoTabla){
                columna =$(this).parent().children('input[class="valores"]');
                jQuery.each(columna, function(id, elemento){
                    valores = valores + parseInt($(elemento).val());
                });
            }
        });
        var diferencia = valorSaldo-valores;
        return diferencia;
    }

    /*** Me modifica la llave del saldo en el listado de saldos ***/
    function reemplazarValores(id_saldo,dato_saldo,valorSaldo){
        var llave = id_saldo.split('/');
        llave[3] = valorSaldo;
        llave = llave.join('/');
        var valor = dato_saldo.split('$');
        valor[1] = valorSaldo;
        valor = valor.join('$');
        return '<option value="'+llave+'">'+valor+'</option>';
    }

    /*** Verifica que en los movimientos se inserte una cuenta con el sentido correcto y que el item sea correcto***/
    function comprobarCuentaEnTabla(cuenta,sentido,maneja_saldos){

        $('#estado_insertar').val('S');

        var contador_cuenta = 0;///Cuantas cuentas de las mismas tengo en la tabla
        var contador_saldos = 0;///Cuantos movimientos de esta cuenta fueron saldos
        var contador_abonos = 0;///Cuantos movimientos de esta cuenta fueron abonos

        var sentido_cuenta = $('#sentido_cuenta').val();

        var sentidoMovimiento = '';///Variable que guarda los inputs que tienen el sentido del movimiento en la tabla
        var sentidoCuenta     = '';///Variable que guarda los inputs que tienen el sentido original de la cuenta en la tabla

        var sentidoItem     = '';
        var sentidoOriginal = '';

        if(maneja_saldos=='1'){
            $('#listaItems').find('.cuentas').each(function () {
                cuentaT = $(this).val();
                if(cuenta==cuentaT){
                    sentidoMovimiento =$(this).parent().children('input[class="sentidos"]');
                    sentidoCuenta =$(this).parent().children('input[class="senTcuentas"]');
                    jQuery.each(sentidoMovimiento, function(id, elemento){
                        sentidoItem = $(elemento).val();
                    });
                    jQuery.each(sentidoCuenta, function(id, elemento){
                        sentidoOriginal = $(elemento).val();
                    });
                    if(sentidoItem == sentidoOriginal){
                        contador_saldos++;
                    }else{
                        contador_abonos++;
                    }
                    contador_cuenta++;
                }
            });
            if((sentido_cuenta ==  sentido) && contador_cuenta>0){
                $('#estado_insertar').val('N');
                if(contador_abonos>0){
                    $('#cual_mensaje_error_cuentas').val('1');
                }else if(contador_saldos){
                    $('#cual_mensaje_error_cuentas').val('2');
                }
            }else if((sentido_cuenta !=  sentido) && contador_saldos>0){
                $('#estado_insertar').val('N');
                $('#cual_mensaje_error_cuentas').val('1');
            }
        }
    }
