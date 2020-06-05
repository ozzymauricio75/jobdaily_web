    $(document).ready(function(){
        ejecutarFuncionesGlobales();
    });


    $(document).ready(function() {
        $.ajaxSetup({
            cache: false
        });
    });

    function recargarDatos(){
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
                $('#documento_identidad_empleado').val(datos[1]);
                $('#fechas').removeAttr("disabled","disabled");
            } else {
                $('#documento_identidad_empleado').val('');
                $('#fechas').attr("disabled");
                $('#anexos_contables').val('');
                $('#auxiliares_contables').val('');
            }
        });
    }

    function insertarHoras(){
        var fechas       = $("#fechas").val();
        var empleado     = $("#documento_identidad_empleado").val();
        var sucursal     = $("#codigo_sucursal").val();
        if(fechas && empleado){
            fechas           = fechas.split('-');
            var fecha_inicio = borrarEspacios(fechas[0]);
            var fecha_fin    = borrarEspacios(fechas[1]);
            var hora_inicio  = $("#hora_inicio").val();
            var hora_fin     = $("#hora_fin").val();

            if((hora_inicio == "") || (hora_inicio) > "23:59" || (hora_inicio < "00:00")){
                alert("Formato de hora de inicio invalido o vacio");
            }else{
                if((hora_fin == "") || (hora_fin) > "23:59" || (hora_fin < "00:00")){
                    alert("Formato de hora de fin invalido o vacio");
                }else{
                    hora_inicio = hora_inicio.split(':');
                    hora_fin    = hora_fin.split(':');
                    horaI       = parseInt(hora_inicio[0]);
                    minuI       = parseInt(hora_inicio[1]);
                    horaF       = parseInt(hora_fin[0]);
                    minuF       = parseInt(hora_fin[1]);
                    hora_inicio = hora_inicio.join(':');
                    hora_fin    = hora_fin.join(':');
                    if(minuF>0){
                        horaF+=1;
                    }
                    if(minuF==minuI && minuF>0){
                        horaF-=1;
                    }
                    var fecha1 = new Date(fecha_inicio);
                    var fecha2 = new Date(fecha_fin);
                    var resta  = (fecha2-fecha1)/1000/3600/24;
                    if(resta==0 && horaI>=horaF){
                        alert("Si las fechas de inicio y de fin son iguales, entonces la hora final debese mayor a la hora inicial");
                    }else{
                        if(resta>1){
                            alert("El turno no puede exceder las 24 horas, verifique las horas y las fechas");
                        }else{
                            if(resta==1 && horaF>horaI){
                                alert("El turno no puede exceder las 24 horas, verifique las horas y las fechas");
                            }else{
                                if(resta==1){
                                    horaF+=24;
                                }
                                var cantidad = horaF-horaI;
                                var contador = parseInt($("#contador").val());

                                var valorClase = '';

                                if ($("#listaItemsExtras tr:last").hasClass("even")) {
                                    valorClase = 'odd';
                                } else {
                                    valorClase = 'even';
                                }
                                var destino     = $('#URLFormulario').val();
                                var fechas1 = new Array();
                                var fechas2 = new Array();
                                var horas1  = new Array();
                                var horas2  = new Array();
                                var i=0;
                                $('#listaItemsExtras').find('.fechaInicioTabla').each(function () {
                                    fechas1[i] = $(this).val();
                                    i++;
                                });

                                i=0;

                                $('#listaItemsExtras').find('.horaInicioTabla').each(function () {
                                    horas1[i] = $(this).val();
                                    i++;
                                });

                                i=0;

                                $('#listaItemsExtras').find('.fechaFinTabla').each(function () {
                                    fechas2[i] = $(this).val();
                                    i++;
                                });

                                i=0;

                                $('#listaItemsExtras').find('.horaFinTabla').each(function () {
                                    horas2[i] = $(this).val();
                                    i++;
                                });

                                var fechasI = fechas1.join('|');
                                var fechasF = fechas2.join('|');
                                var horasI  = horas1.join('|');
                                var horasF  = horas2.join('|');
                                $.getJSON(destino,{
                                    validarCruces : true,
                                    fecha_inicio : fecha_inicio,
                                    fecha_fin : fecha_fin,
                                    hora_inicio : hora_inicio,
                                    hora_fin : hora_fin,
                                    cedula : empleado,
                                    codigo_sucursal : sucursal,
                                    fechasInicioTabla : fechasI,
                                    fechasFinTabla : fechasF,
                                    horasInicioTabla : horasI,
                                    horasFinTabla : horasF
                                }, function(datos){

                                    if(datos[0]){

                                        var boton = $('#botonRemoverextras').html();
                                        var item  = '<tr id="fila_'+contador+'" class="'+valorClase+'">'+
                                        '<td align="center">'+
                                        '<span type="hidden" class="posicionTabla" name="posicionTabla['+contador+']" value="'+contador+'">'+
                                        '<span type="hidden" class="cantidadTabla" name="cantidadTabla['+contador+']" value="'+cantidad+'">'+
                                        '<span type="hidden" class="fechaInicioTabla" name="fechaInicioTabla['+contador+']" value="'+fecha_inicio+'">'+
                                        '<span type="hidden" class="fechaFinTabla" name="fechaFinTabla['+contador+']" value="'+fecha_fin+'">'+
                                        '<span type="hidden" class="horaInicioTabla" name="horaInicioTabla['+contador+']" value="'+hora_inicio+'">'+
                                        '<span type="hidden" class="horaFinTabla" name="horaFinTabla['+contador+']" value="'+hora_fin+'">'+
                                        '<span type="hidden" class="codigoTransaccionTabla" name="codigoTransaccionTabla['+contador+']" value="'+datos[2]+'">'+
                                        boton+
                                        '</td>'+
                                        '<td align="left">'+fecha_inicio+'</td>'+
                                        '<td align="left">'+hora_inicio+'</td>'+
                                        '<td align="left">'+fecha_fin+'</td>'+
                                        '<td align="left">'+hora_fin+'</td>'+
                                        '<td align="left">'+datos[1]+'</td>'+
                                        '<td align="left">'+cantidad+'</td>'+
                                        '</tr>';
                                        $('#listaItemsExtras').append(item);
                                        contador++;
                                        $("#contador").val(contador)
                                        $('#fechas').val('');
                                        $('#hora_inicio').val('');
                                        $('#hora_fin').val('');
                                    }else{
                                        alert(datos[1]);
                                    }
                                });
                            }
                        }
                    }
                }
            }
        }else{
            alert("Fechas vacias o empleado sin seleccionar, verifique");
        }
    }

    function modificarHoras(){
        var fechas       = $("#fechas").val();
        var empleado     = $("#documento_identidad_empleado").val();
        var consecutivoI = $("#consecutivo").val();
        if(fechas){
            fechas           = fechas.split('-');
            var fecha_inicio = trim(fechas[0]);
            var fecha_fin    = trim(fechas[1]);
            var hora_inicio  = $("#hora_inicio").val();
            var hora_fin     = $("#hora_fin").val();
            if((hora_inicio == "") || (hora_inicio) > "23:59" || (hora_inicio < "00:00")){
                alert("Formato de hora de inicio invalido o vacio");
            }else{
                if((hora_fin == "") || (hora_fin) > "23:59" || (hora_fin < "00:00")){
                    alert("Formato de hora de fin invalido o vacio");
                }else{
                    hora_inicio = hora_inicio.split(':');
                    hora_fin    = hora_fin.split(':');
                    horaI       = parseInt(hora_inicio[0]);
                    minuI       = parseInt(hora_inicio[1]);
                    horaF       = parseInt(hora_fin[0]);
                    minuF       = parseInt(hora_fin[1]);
                    hora_inicio = hora_inicio.join(':');
                    hora_fin    = hora_fin.join(':');

                    if(minuF>0){
                        horaF+=1;
                    }

                    if(minuF==minuI && minuF>0){
                        horaF-=1;
                    }
                    var fecha1 =new Date(fecha_inicio);
                    var fecha2 =new Date(fecha_fin);
                    var resta=(fecha2-fecha1)/1000/3600/24;

                    if(resta==0 && horaI>=horaF){
                        alert("Si las fechas de inicio y de fin son iguales, entonces la hora final debese mayor a la hora inicial");
                    }else{
                        if(resta>1){
                            alert("El turno no puede exceder las 24 horas, verifique las horas y las fechas");
                        }else{
                            if(resta==1 && horaF>horaI){
                                alert("El turno no puede exceder las 24 horas, verifique las horas y las fechas");
                            }else{
                                if(resta==1){
                                    horaF+=24;
                                }
                                var cantidad = horaF-horaI;
                                var destino     = $('#URLFormulario').val();

                                $.getJSON(destino,{
                                    validarCruces : true,
                                    fecha_inicio : fecha_inicio,
                                    fecha_fin : fecha_fin,
                                    hora_inicio : hora_inicio,
                                    hora_fin : hora_fin,
                                    cedula : empleado,
                                    consecutivo : consecutivoI
                                }, function(datos){

                                    if(datos[0]){

                                        modificarItem(cantidad);
                                    }else{
                                        alert(datos[1]);
                                    }
                                });
                            }
                        }
                    }
                }
            }
        }else{
            alert("Fechas vacias, verifique");
        }
    }

    function removerItems(boton) {
        $(boton).parents('tr').remove();
    }

    function removerItemsModificar(boton) {

        var destino             = $('#URLFormulario').val();
        var documento_identidad = $("#documento_identidad_empleado").val();
        var fecha_inicio        = $(boton).parents('tr').children("td:first").children('input[class="fecha_inicio"]').val();
        var hora_inicio         = $(boton).parents('tr').children("td:first").children('input[class="hora_inicio"]').val();
        var hora_fin            = $(boton).parents('tr').children("td:first").children('input[class="hora_fin"]').val();

        if(confirm("Esta seguro de que desea eliminar la hora de la base de datos")){
            $.getJSON(destino,{eliminar_base_datos: true,documento_identidad:documento_identidad,
                hora_inicio:hora_inicio,hora_fin:hora_fin,fecha_inicio:fecha_inicio},function(datos){
                if(datos[0]){
                    $(boton).parents('tr').remove();
                }
                alert(datos[1]);
            });
        }

    }

    function removerTableModificar() {

        var destino             = $('#URLFormulario').val();
        var documento_identidad = $("#documento_identidad_empleado").val();
        var fecha_generacion    = $("#fecha_generacion").val();

        if(confirm("Esta seguro de que desea eliminar las horas de la base de datos")){
            $.getJSON(destino,{eliminar_base_datos : true,documento_identidad : documento_identidad,
                fecha_generacion : fecha_generacion},function(datos){
                if(datos[0]){
                    $("#listaItemsExtras tbody").remove();
                }
                alert(datos[1]);
            });
        }

    }


    /////////////////////////////////////////////
    function CalcularTipoHoras(){
        var destino             = $('#URLFormulario').val();
        var documento_identidad = $('#documento_identidad_empleado').val();
        var hora_inicio         = $('#hora_inicio').val();
        var hora_fin            = $('#hora_fin').val();
        var fechas              = $("#fechas").val();
        fechas                  = fechas.split('-');
        var fecha_inicio        = borrarEspacios(fechas[0]);
        var fecha_fin           = borrarEspacios(fechas[1]);
        var sucursal            = $('#codigo_sucursal').val();
        var valorClase          = '';
        var codigo_sucursal     = $('#codigo_sucursal2').val();
        if(documento_identidad){
            $.getJSON(destino,{validarTipoHora: true,documento_identidad:documento_identidad,hora_inicio:hora_inicio,
                hora_fin:hora_fin,fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,sucursal : sucursal}, function(datos){
                removerTable();
                if(datos[datos.length-1]=="1"){
                    var autorizaExtras = $('.autorizarExtra').is(':checked')
                    var datos_llegada  = "";
                    for(var i=0;i<(datos.length-1);i++)
                    {
                        if ($("#listaItemsExtras tr:last").hasClass("even")) {
                            valorClase = 'odd';
                        } else {
                            valorClase = 'even';
                        }
                        datos_llegada=datos[i].split("!");
                        var fecha = new Date();
                        var check = "";
                        
                        if(datos_llegada[8]== 1){
                            if(autorizaExtras)
                            {
                                check = '<input type="checkbox" name="fecha_check" value="ON" checked="checked" />';
                            }else
                            {
                                check = '<input type="checkbox" name="fecha_check"  value="ON" />';
                            }
                        }else
                        {
                            check = '<input disabled= "disabled "type="checkbox" name="fecha_check"  value="ON" />';
                        }

                        var boton = $('#botonRemoverextras').html();
                        var item  = '<tr id="fila_'+fecha+'" class="'+valorClase+'">'+
                        '<td align="center">'+'<input type="hidden" class="codigoTransaccionTabla" name="codigoTransaccionTabla[]" value="'+datos_llegada[2]+'">'+
                        '<input type="hidden" class="posicionTabla" name="posicionTabla[]" value="">'+
                        '<input type="hidden" class="fecha_inicio" name="fecha_inicio[]" value="'+datos_llegada[0]+'">'+
                        '<input type="hidden" class="hora_inicio" name="hora_inicio[]" value="'+datos_llegada[1]+'">'+
                        '<input type="hidden" class="fecha_fin" name="fecha_fin[]" value="'+datos_llegada[0]+'">'+
                        '<input type="hidden" class="hora_fin" name="hora_fin[]" value="'+datos_llegada[2]+'">'+
                        '<input type="hidden" class="codigo_transaccion_tiempo" name="codigo_transaccion_tiempo[]" value="'+datos_llegada[3]+'">'+
                        '<input type="hidden" class="codigo_contable" name="codigo_contable[]" value="'+datos_llegada[4]+'">'+
                        '<input type="hidden" class="minutos" name="minutos[]" value="'+datos_llegada[7]+'">'+
                        '<input type="hidden" class="horas" name="horas[]" value="'+datos_llegada[8]+'">'+
                        boton+
                        '</td>'+
                        '<td align="left">'+datos_llegada[0]+'</td>'+
                        '<td align="left">'+datos_llegada[1]+'</td>'+
                        '<td align="left">'+datos_llegada[2]+'</td>'+
                        '<td align="left">'+datos_llegada[5]+'</td>'+
                        '<td align="left">'+datos_llegada[6]+'</td>'+
                        '<td align="left">'+check+'</td>'+
                        '</tr>';
                        $('#listaItemsExtras').append(item);
                    }
                }else{
                    alert(datos[0]);
                }
            });
        }
    }

    function removerTable() {
        $("#listaItemsExtras tbody").remove();
    }

    function determinarTurnos(){
        var destino             = $('#URLFormulario').val();
        var documento_identidad = $('#documento_identidad_empleado').val();
        var codigo_sucursal     = $('#codigo_sucursal').val();
        var hora_inicio         = $('#hora_inicio').val();
        var hora_fin            = $('#hora_fin').val();
        var fechas              = $("#fechas").val();
        fechas                  = fechas.split('-');
        var fecha_inicio        = borrarEspacios(fechas[0]);
        var fecha_fin           = borrarEspacios(fechas[1]);

        $.getJSON(destino,{determinarTurno: true,documento_identidad:documento_identidad,hora_inicio:hora_inicio,
                           hora_fin:hora_fin,fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,sucursal:codigo_sucursal}, function(datos){
            if (datos[3] == "0") {
                $("#turno_laboral_1").val(datos[2]);
                $("#contenedor_turno_laboral_1").removeClass("oculto");
                $("#contenedor_turno_laboral_1").parent().show();
                $("#permite_festivos_1").val(datos[0]);
                $("#contenedor_turno_laboral_2").addClass("oculto");
                $("#contenedor_turno_laboral_2").parent().hide();
                document.getElementById("permite_festivos_1").innerHTML = datos[0];
            } else {
                $("#turno_laboral_2").val(datos[2]);
                $("#contenedor_turno_laboral_2").removeClass("oculto");
                $("#contenedor_turno_laboral_2").parent().show();
                $("#permite_festivos_2").val(datos[1]);
                $("#contenedor_turno_laboral_1").addClass("oculto");
                $("#contenedor_turno_laboral_1").parent().hide();
                document.getElementById("permite_festivos_2").innerHTML = datos[1];
                document.getElementById("poga_dominical").innerHTML     = datos[datos[4]];
                document.getElementById("poga_festivo").innerHTML       = datos[datos[5]];
            }
        });

    }

    function horasAutorizadasExtras(){
        var tab  = document.getElementById('listaItemsExtras')
        var elem = tab.getElementsByTagName('input');
        var arr  = new Array();
        for(i = 0,iarr = 0; i < elem.length; i++) {
            att = elem[i].getAttribute("name");
            if(att == "fecha_check") {
                arr[iarr] = elem[i];
                iarr++;
            }
        }
        var autorizaExtra="";
        for (i=0; i<=arr.length-1; i++) {
            chk = arr[i];
            if (chk.checked){
                autorizaExtra +=i+",";
            }
        }
        $("#autorizaHoraExtra").val(autorizaExtra);
    }

    function limpiarCampos(){
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
        $("anexos_contables").val(0);
        $("auxiliares_contables").val('');
        removerTable();
    }


    function obtenerRango(){
        var documento_identidad = $('#documento_identidad_empleado').val();
        var destino             = $('#URLFormulario').val();
        $.getJSON(destino,{obtenerFechaRango: true,documento_identidad:documento_identidad}, function(rango){
            $("#rango_fecha").val(rango);
            bloquearDias(rango);
        });
    }

    function bloquearDias(rango){
        var diasBloquear = "-"+rango+"D";
        $.datepicker.setDefaults({
            minDate: diasBloquear
        });
        $("#fechas").addClass("selectorFecha");
    }


    function verificarAnexosEnTransacciones(){
        var destino             = $('#URLFormulario').val();
        var documento_identidad = $('#documento_identidad_empleado').val();
        var codigo_sucursal     = $('#codigo_sucursal').val();
        var codigo_anexo        = $('#anexos_contables').val();
        var fecha_inicial       = $('#fecha_inicial').val();

        if(documento_identidad && codigo_anexo==0){
            $.getJSON(destino,{verificaAnexos: true,empleado : documento_identidad, sucursal : codigo_sucursal , fecha_inicial:fecha_inicial}, function(datos){
                if(datos){
                    if(datos[0]==1){
                        $('#anexos_contables').val('');
                        $('#auxiliares_contables').val('');
                    }else if(datos[0]==2){
                        $('#anexos_contables').val(datos[1]);
                        recargarLista('anexos_contables','auxiliares_contables',datos[2]);
                    }
                }
            });
        }
    }
