    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function cargarIncapacidades(mensaje){

        var destino             = $('#URLFormulario').val();
        var FechaReporte        = $('#fecha_reporte_incapacidad').val();
        var FechaInicial        = $('#fecha_inicial_incapacidad').val();
        var diasIncapacidad     = $('#dias_incapacidad').val();
        var codigo_transaccion  = $('#codigo_transaccion_tiempo').val();
        var transaccion         = $('#codigo_transaccion_tiempo :selected').text();
        var codigo_motivo       = $('#codigo_motivo_incapacidad').val();
        var motivo              = $('#codigo_motivo_incapacidad :selected').text();
        var numero              = $('#numero_incapacidad').val().toUpperCase();
        var documento_identidad = $('#documento_aspirante').val();
        var sucursal            = $('#codigo_sucursal2').val();
        var estado              = $('#estado_anexo').val();
        var anexoF              = $('#codigo_anexo_contable').val();
        var auxiliarF           = $('#codigo_auxiliar_contable').val();

        var fecha1 = new Date(FechaReporte);
        var fecha2 = new Date(FechaInicial);
        var resta  = parseInt((fecha2-fecha1)/1000/3600/24);

        if(resta>=0){
            if(documento_identidad && diasIncapacidad && typeof(sucursal) != "undefined" && sucursal!=0 && codigo_transaccion!=0){

                var fechas2  = new Array();
                var motivos2 = new Array();
                var i=0;

                $('#listaItemsIncapacidad').find('.fechaIncapacidadTabla').each(function () {
                    fechas2[i] = $(this).val();
                    i++;
                });

                i=0;

                $('#listaItemsIncapacidad').find('.motivoTabla').each(function () {
                    motivos2[i] = $(this).val();
                    i++;
                });

                var fechas  = fechas2.join('|');
                var motivos = motivos2.join('|');

                $.getJSON(destino,{generarIncapacidades: true, fecha_reporte : FechaReporte, fecha_inicio : FechaInicial, dias_incapacidad : diasIncapacidad, id_transaccion : codigo_transaccion , id_Motivo : codigo_motivo, empleado : documento_identidad, fechasTabla : fechas, motivosTabla : motivos, codigo_sucursal : sucursal, estado_anexo : estado, anexo : anexoF, auxiliar : auxiliarF}, function(datos){

                    if(datos[0] == true){
                        for(var i=1; i<datos.length; i++){

                            var res              = datos[i].split("|");
                            var fechaIncapacidad = res[0];
                            var idTransaccion    = res[1];
                            var empesa_anexo     = res[2];
                            var anexo            = res[3];
                            var auxiliar         = res[4];
                            var valorDia         = res[5];
                            var divisor          = res[6];
                            var dividendo        = res[7];
                            var valorMovimiento  = res[8];

                            $('#codigo_transaccion_tiempo').val(idTransaccion);
                            transaccion = $('#codigo_transaccion_tiempo :selected').text();

                            var valorClase = '';
                            if ($("#listaItemsIncapacidad tr:last").hasClass("even")) {
                                valorClase = 'odd';
                            }else {
                                valorClase = 'even';
                            }
                            var lista_incapacidad    = parseInt($("#lista_incapacidad").val());
                            var item  = '<tr id="fila_'+lista_incapacidad+'" class="'+valorClase+'">'+
                                        '<td align="center">'+
                                        '<input type="hidden" class="posicionTabla" name="posicionTabla['+lista_incapacidad+']" value="'+lista_incapacidad+'">'+
                                        '<input type="hidden" class="fechaReporteTabla" name="fechaReporteTabla['+lista_incapacidad+']" value="'+FechaReporte+'">'+
                                        '<input type="hidden" class="fechaInicialTabla" name="fechaInicialTabla['+lista_incapacidad+']" value="'+FechaInicial+'">'+
                                        '<input type="hidden" class="fechaIncapacidadTabla" name="fechaIncapacidadTabla['+lista_incapacidad+']" value="'+fechaIncapacidad+'">'+
                                        '<input type="hidden" class="diasTabla" name="diasTabla['+lista_incapacidad+']" value="'+diasIncapacidad+'">'+
                                        '<input type="hidden" class="transaccionTabla" name="transaccionTabla['+lista_incapacidad+']" value="'+idTransaccion+'">'+
                                        '<input type="hidden" class="motivoTabla" name="motivoTabla['+lista_incapacidad+']" value="'+codigo_motivo+'">'+
                                        '<input type="hidden" class="numeroTabla" name="numeroTabla['+lista_incapacidad+']" value="'+numero+'">'+
                                        '<input type="hidden" class="empresaTabla" name="empresaTabla['+lista_incapacidad+']" value="'+empesa_anexo+'">'+
                                        '<input type="hidden" class="anexoTabla" name="anexoTabla['+lista_incapacidad+']" value="'+anexo+'">'+
                                        '<input type="hidden" class="auxiliarTabla" name="auxiliarTabla['+lista_incapacidad+']" value="'+auxiliar+'">'+
                                        '<input type="hidden" class="valorDiaTabla" name="valorDiaTabla['+lista_incapacidad+']" value="'+valorDia+'">'+
                                        '<input type="hidden" class="divisorTabla" name="divisorTabla['+lista_incapacidad+']" value="'+divisor+'">'+
                                        '<input type="hidden" class="dividendoTabla" name="dividendoTabla['+lista_incapacidad+']" value="'+dividendo+'">'+
                                        '<input type="hidden" class="valorMovimientoTabla" name="valorMovimientoTabla['+lista_incapacidad+']" value="'+valorMovimiento+'">'+
                                        '</td>'+
                                        '<td align="center">'+fechaIncapacidad+'</td>'+
                                        '<td align="left">'+transaccion+'</td>'+
                                        '<td align="left">'+motivo+'</td>'+
                                        '<td align="center">'+numero+'</td>'+
                                        '</tr>';
                            $('#listaItemsIncapacidad').append(item);
                            lista_incapacidad++;
                            $("#lista_incapacidad").val(lista_incapacidad);
                        }
                            $('#fecha_inicial_incapacidad').val($('#fecha_sistema').val());
                            $('#fecha_reporte_incapacidad').val($('#fecha_sistema').val());
                            $('#dias_incapacidad').val('');
                            $('#numero_incapacidad').val('');
                            $('#codigo_transaccion_tiempo').val('');
                            $('#codigo_motivo_incapacidad').val('');
                            $("#codigo_sucursal").attr("disabled","disabled");
                            $("#selector1").attr("disabled","disabled");
                    }else{
                        alert(datos[1]);
                    }
                });
            }else{
                alert(mensaje);
            }
        }else{
            alert($('#mensaje_fechas').val());
        }
    }

    function cargarIncapacidadesModificar(mensaje){

         var destino             = $('#URLFormulario').val();
        var FechaReporte        = $('#fecha_reporte_incapacidad').val();
        var FechaInicial        = $('#fecha_inicial_incapacidad').val();
        var diasIncapacidad     = $('#dias_incapacidad').val();
        var codigo_transaccion  = $('#codigo_transaccion_tiempo').val();
        var transaccion         = $('#codigo_transaccion_tiempo :selected').text();
        var codigo_motivo       = $('#codigo_motivo_incapacidad').val();
        var motivo              = $('#codigo_motivo_incapacidad :selected').text();
        var numero              = $('#numero_incapacidad').val().toUpperCase();
        var documento_identidad = $('#documento_aspirante').val();
        var sucursal            = $('#codigo_sucursal2').val();
        var estado              = $('#estado_anexo').val();
        var anexoF              = $('#codigo_anexo_contable').val();
        var auxiliarF           = $('#codigo_auxiliar_contable').val();

        var fecha1 = new Date(FechaReporte);
        var fecha2 = new Date(FechaInicial);
        var resta  = parseInt((fecha2-fecha1)/1000/3600/24);

        if(resta>=0){
            if(diasIncapacidad){

                var fechas2  = new Array();
                var motivos2 = new Array();
                var i=0;

                $('#listaItemsIncapacidad').find('.fechaIncapacidadTabla').each(function () {
                    fechas2[i] = $(this).val();
                    i++;
                });

                i=0;

                $('#listaItemsIncapacidad').find('.motivoTabla').each(function () {
                    motivos2[i] = $(this).val();
                    i++;
                });

                var fechas  = fechas2.join('|');
                var motivos = motivos2.join('|');

                $.getJSON(destino,{generarIncapacidades: true, fecha_reporte : FechaReporte, fecha_inicio : FechaInicial, dias_incapacidad : diasIncapacidad, id_transaccion : codigo_transaccion , id_Motivo : codigo_motivo, empleado : documento_identidad, fechasTabla : fechas, motivosTabla : motivos, codigo_sucursal : sucursal, estado_anexo : estado, anexo : anexoF, auxiliar : auxiliarF}, function(datos){

                    if(datos[0] == true){
                        for(var i=1; i<datos.length; i++){

                            var res              = datos[i].split("|");
                            var fechaIncapacidad = res[0];
                            var idTransaccion    = res[1];
                            var empesa_anexo     = res[2];
                            var anexo            = res[3];
                            var auxiliar         = res[4];
                            var valorDia         = res[5];
                            var divisor          = res[6];
                            var dividendo        = res[7];
                            var valorMovimiento  = res[8];

                            $('#codigo_transaccion_tiempo').val(idTransaccion);
                            transaccion = $('#codigo_transaccion_tiempo :selected').text();

                            var valorClase = '';
                            if ($("#listaItemsIncapacidad tr:last").hasClass("even")) {
                                valorClase = 'odd';
                            }else {
                                valorClase = 'even';
                            }
                            var lista_incapacidad    = parseInt($("#lista_incapacidad").val());
                            var item  = '<tr id="fila_'+lista_incapacidad+'" class="'+valorClase+'">'+
                                        '<td align="center">'+
                                            '<input type="hidden" class="posicionTabla" name="posicionTabla['+lista_incapacidad+']" value="'+lista_incapacidad+'">'+
                                            '<input type="hidden" class="fechaReporteTabla" name="fechaReporteTabla['+lista_incapacidad+']" value="'+FechaReporte+'">'+
                                            '<input type="hidden" class="fechaInicialTabla" name="fechaInicialTabla['+lista_incapacidad+']" value="'+FechaInicial+'">'+
                                            '<input type="hidden" class="fechaIncapacidadTabla" name="fechaIncapacidadTabla['+lista_incapacidad+']" value="'+fechaIncapacidad+'">'+
                                            '<input type="hidden" class="diasTabla" name="diasTabla['+lista_incapacidad+']" value="'+diasIncapacidad+'">'+
                                            '<input type="hidden" class="transaccionTabla" name="transaccionTabla['+lista_incapacidad+']" value="'+idTransaccion+'">'+
                                            '<input type="hidden" class="motivoTabla" name="motivoTabla['+lista_incapacidad+']" value="'+codigo_motivo+'">'+
                                            '<input type="hidden" class="numeroTabla" name="numeroTabla['+lista_incapacidad+']" value="'+numero+'">'+
                                            '<input type="hidden" class="empresaTabla" name="empresaTabla['+lista_incapacidad+']" value="'+empesa_anexo+'">'+
                                            '<input type="hidden" class="anexoTabla" name="anexoTabla['+lista_incapacidad+']" value="'+anexo+'">'+
                                            '<input type="hidden" class="auxiliarTabla" name="auxiliarTabla['+lista_incapacidad+']" value="'+auxiliar+'">'+
                                            '<input type="hidden" class="valorDiaTabla" name="valorDiaTabla['+lista_incapacidad+']" value="'+valorDia+'">'+
                                            '<input type="hidden" class="divisorTabla" name="divisorTabla['+lista_incapacidad+']" value="'+divisor+'">'+
                                            '<input type="hidden" class="dividendoTabla" name="dividendoTabla['+lista_incapacidad+']" value="'+dividendo+'">'+
                                            '<input type="hidden" class="valorMovimientoTabla" name="valorMovimientoTabla['+lista_incapacidad+']" value="'+valorMovimiento+'">'+
                                        '</td>'+
                                        '<td align="center">'+fechaIncapacidad+'</td>'+
                                        '<td align="left">'+transaccion+'</td>'+
                                        '<td align="left">'+motivo+'</td>'+
                                        '<td align="center">'+numero+'</td>'+
                                        '</tr>';
                            $('#listaItemsIncapacidad').append(item);
                            lista_incapacidad++;
                            $("#lista_incapacidad").val(lista_incapacidad);
                        }
                            $("#codigo_transaccion_tiempo").attr("disabled","disabled");
                            $("#codigo_motivo_incapacidad").attr("disabled","disabled");
                            $("#fecha_reporte_incapacidad").attr("disabled","disabled");
                            $("#fecha_inicial_incapacidad").attr("disabled","disabled");
                            $("#codigo_anexo_contable").attr("disabled","disabled");
                            $("#codigo_auxiliar_contable").attr("disabled","disabled");
                            $("#dias_incapacidad").attr("disabled","disabled");
                            $("#numero_incapacidad").attr("disabled","disabled");
                            $("#botonAgregar").attr("style","display : none;");
                            $("#botonEliminar").attr("style","display : inline;");
                    }else{
                        alert(datos[1]);
                    }
                });
            }else{
                alert(mensaje);
            }
        }else{
            alert($('#mensaje_fechas').val());
        }
    }

    function removerItems(boton) {
        $(boton).parents('tr').remove();
    }

    function removerTable(boton) {
        $("#listaItemsIncapacidad tbody").remove();
        $('#codigo_sucursal').removeAttr('disabled');
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
        var sucursal = $('#codigo_sucursal2').val();

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
