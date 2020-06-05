    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function activarPagos(valor){
        if(valor == 2){
            $('#paga_dominical_no').parent().show();
            $('#paga_dominical_no').removeClass("campoInactivo").removeAttr("disabled");
            $('#paga_dominical_si').parent().show();
            $('#paga_dominical_si').removeClass("campoInactivo").removeAttr("disabled");
            $('#paga_festivo_no').parent().show();
            $('#paga_festivo_no').removeClass("campoInactivo").removeAttr("disabled");
            $('#paga_festivo_si').parent().show();
            $('#paga_festivo_si').removeClass("campoInactivo").removeAttr("disabled");
            $('#pagos_dominicales').parent().show();
            $('#pagos_dominicales').removeClass("campoInactivo").removeAttr("disabled");
            $('#pagos_festivos').parent().show();
            $('#pagos_festivos').removeClass("campoInactivo").removeAttr("disabled");
        }else{
            $('#paga_dominical_no').parent().hide();
            $('#paga_dominical_no').addClass("campoInactivo").attr("disabled","disabled");
            $('#paga_dominical_si').parent().hide();
            $('#paga_dominical_si').addClass("campoInactivo").attr("disabled","disabled");
            $('#paga_festivo_no').parent().hide();
            $('#paga_festivo_no').addClass("campoInactivo").attr("disabled","disabled");
            $('#paga_festivo_si').parent().hide();
            $('#paga_festivo_si').addClass("campoInactivo").attr("disabled","disabled");
            $('#pagos_dominicales').parent().hide();
            $('#pagos_dominicales').addClass("campoInactivo").attr("disabled","disabled");
            $('#pagos_festivos').parent().hide();
            $('#pagos_festivos').addClass("campoInactivo").attr("disabled","disabled");
        }
    }

    function activarHoras(valor){
        if(valor == 1){
            $('#turno2').parent().show();
            $('#turno2').removeClass("campoInactivo").removeAttr("disabled");
            $('#hora_inicia2').parent().show();
            $('#hora_inicia2').removeClass("campoInactivo").removeAttr("disabled","disabled");
            $('#hora_finaliza2').parent().show();
            $('#hora_finaliza2').removeClass("campoInactivo").removeAttr("disabled");

        }else{
            $('#hora_inicia2').parent().hide();
            $('#hora_inicia2').addClass("campoInactivo").attr("disabled","disabled");
            $('#hora_finaliza2').parent().hide();
            $('#hora_finaliza2').addClass("campoInactivo").attr("disabled","disabled");
            $('#turno2').parent().hide();
            $('#turno2').addClass("campoInactivo").attr("disabled","disabled");
            $('#hora_inicia2').val('');
            $('#hora_finaliza2').val('');
        }
    }

    function adicionarDia(){

        var horaInicia1     = $('#hora_inicia1').val();
        var horaFinal1      = $('#hora_finaliza1').val();
        var horaInicia2     = $('#hora_inicia2').val();
        var horaFinal2      = $('#hora_finaliza2').val();
        var seguido_si      = $('#seguido_si').attr('checked');
        var continuoTextoNo = 'checked';
        var continuoTextoSi = '';
        var estadoHora2     = '';
        var segundoTurno    = true;

        if(seguido_si){
            continuoTextoNo = '';
            continuoTextoSi = 'checked';
            segundoTurno    = false;
            estadoHora2     = 'disabled';
        }
        /*** Hacer las debidas validaciones del formulario ***/
        var estadoError  = false;
        var mensajeError = '';

        var respuesta = validarHoras(segundoTurno, horaInicia1, horaFinal1, horaInicia2, horaFinal2);
        estadoError   = respuesta[0];
        mensajeError  = respuesta[1];

        if(estadoError){
            $('#botonEliminar').parent().children('#errorDialogo').remove();
            $('#botonEliminar').focus();
            $('#botonEliminar').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensajeError+'</span>');
            $('#botonEliminar').parent().children('.mensajeErrorLinea').fadeOut(7000).addClass('mensajeError').css('display','block');
        }else{
        /*** Fin de las Validaciones ***/
            var semana = cargarArrays();
            for(var i=0;i<7;i++){
                var valorClase = '';
                if ($("#listaTurnos tr:last").hasClass("even")) {
                    valorClase = 'odd';
                }else {
                    valorClase = 'even';
                }
                var contador = semana[i];

                var item  = '<tr id="fila_'+contador+'" class="'+valorClase+'">'+
                                '<td align="left">'+/*** Columna Dia semana ***/
                                    '<input type="hidden" class="diaSemana" name="diaSemana['+contador+']"     value="'+contador   +'">'+
                                    contador+
                                '</td>'+

                                '<td align="center">'+/*** Columna Turno Continuo ***/
                                    '<label class="dato"><input type="radio" class="turnoContinuoSi" name="turnoContinuo['+contador+']" value="1" '+continuoTextoSi+' onChange="validarTurnos(this,1);" >Si</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
                                    '<label class="dato"><input type="radio" class="turnoContinuoNo" name="turnoContinuo['+contador+']" value="0" '+continuoTextoNo+' onChange="validarTurnos(this,2);" >No</label>'+
                                '</td>'+

                                '<td align="center">'+/*** Columna Primer Turno ***/
                                    '<input type="text" class="horaTabla" name="horaInicio1['+contador+']" value="'+horaInicia1 +'" size="5" maxlength="5">'+
                                    '<input type="text" class="horaTabla" name="horaFinal1['+contador+']"  value="'+horaFinal1  +'" size="5" maxlength="5">'+
                                '</td>'+

                                '<td align="center">'+/*** Columna Segundo Turno ***/
                                    '<input type="text" class="horaTabla" name="horaInicio2['+contador+']" value="'+horaInicia2 +'" size="5" maxlength="5" '+estadoHora2+'>'+
                                    '<input type="text" class="horaTabla" name="horaFinal2['+contador+']"  value="'+horaFinal2  +'" size="5" maxlength="5" '+estadoHora2+'>'+
                                '</td>'+

                                '<td align="center">'+/*** Columna Dia descanso ***/
                                    '<input type="checkbox" name="diaDescanso['+contador+']" onChange="habilitarHoras(this);" value="'+contador+'" title="'+$("#ayuda_dia_descanso").val()+'"/>'+
                                '</td>'+
                            '</tr>';

                $('#listaTurnos').append(item);
            }
            aplicarMascara();
            $('#botonAgregar').parent().hide();
            $('#hora_inicia1').attr("disabled","disabled");
            $('#hora_inicia2').attr("disabled","disabled");
            $('#hora_finaliza1').attr("disabled","disabled");
            $('#hora_finaliza2').attr("disabled","disabled");
            $('#seguido_si').attr("disabled","disabled");
            $('#seguido_no').attr("disabled","disabled");
        }
    }

    function removerTable(){
        $("#listaTurnos tbody").remove();
        $('#botonAgregar').parent().show();
        $('#hora_inicia1').removeAttr("disabled");
        $('#hora_inicia2').removeAttr("disabled");
        $('#hora_finaliza1').removeAttr("disabled");
        $('#hora_finaliza2').removeAttr("disabled");
        $('#seguido_si').removeAttr("disabled");
        $('#seguido_no').removeAttr("disabled");
    }

    function cargarArrays(){
        var semana = new Array(7);
        semana[0] = $('#lunes').val();
        semana[1] = $('#martes').val();
        semana[2] = $('#miercoles').val();
        semana[3] = $('#jueves').val();
        semana[4] = $('#viernes').val();
        semana[5] = $('#sabado').val();
        semana[6] = $('#domingo').val();
        return semana;
    }

    function habilitarHoras(campo){

        var clase             = $(campo).val();

        var horaInicia1       = $('#hora_inicia1').val();
        var horaFinal1        = $('#hora_finaliza1').val();
        var horaInicia2       = $('#hora_inicia2').val();
        var horaFinal2        = $('#hora_finaliza2').val();

        var nombreHoraInicio1 = "horaInicio1["+clase+"]";
        var nombreHoraFin1    = "horaFinal1["+clase+"]";
        var nombreHoraInicio2 = "horaInicio2["+clase+"]";
        var nombreHoraFin2    = "horaFinal2["+clase+"]";

        var campos            = $(campo).parent().parent().children('td').children('input[class="horaTabla"]');
        var turnoContinuoSi   = $(campo).parent().parent().children('td').children('label[class="dato"]').children('input[class="turnoContinuoSi"]')[0];
        var turnoContinuoNo   = $(campo).parent().parent().children('td').children('label[class="dato"]').children('input[class="turnoContinuoNo"]')[0];

        jQuery.each(campos, function(id, elemento){
            if($(campo).attr('checked')){
                $(elemento).attr("disabled","disabled");
                $(elemento).val('');
            }else{
                if($(elemento).attr('name') == nombreHoraInicio1){
                    $(elemento).val(horaInicia1);
                    $(elemento).removeAttr("disabled");
                }else if($(elemento).attr('name') == nombreHoraFin1){
                    $(elemento).val(horaFinal1);
                    $(elemento).removeAttr("disabled");
                }else if($(elemento).attr('name') == nombreHoraInicio2 && $(turnoContinuoNo).attr('checked')){
                    $(elemento).val(horaInicia2);
                    $(elemento).removeAttr("disabled");
                }else if($(elemento).attr('name') == nombreHoraFin2 && $(turnoContinuoNo).attr('checked')){
                    $(elemento).val(horaFinal2);
                    $(elemento).removeAttr("disabled");
                }
            }
        });

        if($(campo).attr('checked')){
            $(turnoContinuoSi).attr("disabled","disabled");
            $(turnoContinuoNo).attr("disabled","disabled");
        }else{
            $(turnoContinuoSi).removeAttr("disabled");
            $(turnoContinuoNo).removeAttr("disabled");
        }
    }

    function validarTurnos(campo, opcion){

        var clase = $($(campo).parent().parent().parent().children('td').children('input[class="diaSemana"]')[0]).val();

        var horaInicia2       = $('#hora_inicia2').val();
        var horaFinal2        = $('#hora_finaliza2').val();

        var nombreHoraInicio2 = "horaInicio2["+clase+"]";
        var nombreHoraFin2    = "horaFinal2["+clase+"]";

        var campos = $(campo).parent().parent().parent().children('td').children('input[class="horaTabla"]');

        jQuery.each(campos, function(id, elemento){
            if(opcion == 1){
                if($(elemento).attr('name') == nombreHoraInicio2){
                    $(elemento).attr("disabled","disabled");
                    $(elemento).val('');
                }else if($(elemento).attr('name') == nombreHoraFin2){
                    $(elemento).attr("disabled","disabled");
                    $(elemento).val('');
                }
            }else{
                if($(elemento).attr('name') == nombreHoraInicio2){
                    $(elemento).val(horaInicia2);
                    $(elemento).removeAttr("disabled");
                }else if($(elemento).attr('name') == nombreHoraFin2){
                    $(elemento).val(horaFinal2);
                    $(elemento).removeAttr("disabled");
                }
            }
        });
    }

    function validarHoras(segundoTurno, horaInicia1, horaFinal1, horaInicia2, horaFinal2){

        var estadoError  = false;
        var mensajeError = '';

        if(campoVacio(horaInicia1)){
            estadoError  = true;
            mensajeError = $('#hora_inicia1_vacio').val();
        }else if(campoVacio(horaFinal1)){
            estadoError  = true;
            mensajeError = $('#hora_finaliza1_vacio').val();
        }else if(horaInicia1 >= horaFinal1){
            estadoError  = true;
            mensajeError = $('#horas_turno1_iguales').val();
        }else if(horaInicia1 > "23:59" || horaInicia1 < "00:00"){
            estadoError  = true;
            mensajeError = $('#hora_inicia1_mal_formato').val();
        }else if(horaFinal1 > "23:59" || horaFinal1 < "00:00"){
            estadoError  = true;
            mensajeError = $('#hora_finaliza1_mal_formato').val();
        }else if(segundoTurno && campoVacio(horaInicia2)){
            estadoError  = true;
            mensajeError = $('#hora_inicia2_vacio').val();
        }else if(segundoTurno && campoVacio(horaFinal2)){
            estadoError  = true;
            mensajeError = $('#hora_finaliza2_vacio').val();
        }else if(segundoTurno && (horaFinal2 <= horaInicia2)){
            estadoError  = true;
            mensajeError = $('#horas_turno2_iguales').val();
        }else if(segundoTurno && (horaFinal1 >= horaInicia2)){
            estadoError  = true;
            mensajeError = $('#turnos_cruzados').val();
        }else if(segundoTurno && (horaInicia2 > "23:59" || horaInicia2 < "00:00")){
            estadoError  = true;
            mensajeError = $('#hora_inicia2_mal_formato').val();
        }else if(segundoTurno && (horaFinal2 > "23:59" || horaFinal2 < "00:00")){
            estadoError  = true;
            mensajeError = $('#hora_finaliza2_mal_formato').val();
        }

        var respuesta = new Array(2);
        respuesta[0] = estadoError;
        respuesta[1] = mensajeError;

        return respuesta;
    }

    function validarTabla(){
        var semana       = cargarArrays();
        var diaSemana    = '';
        var mensajeError = '';
        var estadoError  = false;
        var respuesta    = new Array();

        var diasEnTabla = $("#listaTurnos").children('tbody').children('tr').length;

        if(diasEnTabla > 0){
            for(var i=0;i<7;i++){

                diaSemana        = semana[i];
                var horaInicia1  = $($('#fila_'+diaSemana).children('td').children('input[name="horaInicio1['+diaSemana+']"]')[0]).val();
                var horaFinal1   = $($('#fila_'+diaSemana).children('td').children('input[name="horaFinal1['+diaSemana+']"]')[0]).val();
                var horaInicia2  = $($('#fila_'+diaSemana).children('td').children('input[name="horaInicio2['+diaSemana+']"]')[0]).val();
                var horaFinal2   = $($('#fila_'+diaSemana).children('td').children('input[name="horaFinal2['+diaSemana+']"]')[0]).val();
                var segundoTurno = $($('#fila_'+diaSemana).children('td').children('label[class="dato"]').children('input[class="turnoContinuoNo"]')[0]).attr('checked');
                var diaDescanso  = $($('#fila_'+diaSemana).children('td').children('input[name="diaDescanso['+diaSemana+']"]')[0]).attr('checked');
                if(!diaDescanso){
                    respuesta = validarHoras(segundoTurno, horaInicia1, horaFinal1, horaInicia2, horaFinal2);
                    estadoError  = respuesta[0];
                    mensajeError = respuesta[1];
                    if(estadoError){
                        break;
                    }
                }
            }
        }

        if(estadoError){
            mensajeError += " - "+diaSemana;
            $('#errorCuadroDialogo').parent().children('#errorDialogo').remove();
            $('#errorCuadroDialogo').focus();
            $('#errorCuadroDialogo').parent().append('<span id="errorDialogo" class="mensajeErrorLinea" style="text-align:left">'+mensajeError+'</span>');
            $('#errorCuadroDialogo').parent().children('.mensajeErrorLinea').fadeOut(7000).addClass('mensajeError').css('display','block');
        }else{
            adicionarItem();
        }
    }

    function validarTablaM(){
        var semana       = cargarArrays();
        var diaSemana    = '';
        var mensajeError = '';
        var estadoError  = false;
        var respuesta    = new Array();

        var diasEnTabla = $("#listaTurnos").children('tbody').children('tr').length;

        if(diasEnTabla > 0){
            for(var i=0;i<7;i++){

                diaSemana        = semana[i];
                var horaInicia1  = $($('#fila_'+diaSemana).children('td').children('input[name="horaInicio1['+diaSemana+']"]')[0]).val();
                var horaFinal1   = $($('#fila_'+diaSemana).children('td').children('input[name="horaFinal1['+diaSemana+']"]')[0]).val();
                var horaInicia2  = $($('#fila_'+diaSemana).children('td').children('input[name="horaInicio2['+diaSemana+']"]')[0]).val();
                var horaFinal2   = $($('#fila_'+diaSemana).children('td').children('input[name="horaFinal2['+diaSemana+']"]')[0]).val();
                var segundoTurno = $($('#fila_'+diaSemana).children('td').children('label[class="dato"]').children('input[class="turnoContinuoNo"]')[0]).attr('checked');
                var diaDescanso  = $($('#fila_'+diaSemana).children('td').children('input[name="diaDescanso['+diaSemana+']"]')[0]).attr('checked');
                if(!diaDescanso){
                    respuesta = validarHoras(segundoTurno, horaInicia1, horaFinal1, horaInicia2, horaFinal2);
                    estadoError  = respuesta[0];
                    mensajeError = respuesta[1];
                    if(estadoError){
                        break;
                    }
                }
            }
        }

        if(estadoError){
            mensajeError += " - "+diaSemana;
            $('#errorCuadroDialogo').parent().children('#errorDialogo').remove();
            $('#errorCuadroDialogo').focus();
            $('#errorCuadroDialogo').parent().append('<span id="errorDialogo" class="mensajeErrorLinea" style="text-align:left">'+mensajeError+'</span>');
            $('#errorCuadroDialogo').parent().children('.mensajeErrorLinea').fadeOut(7000).addClass('mensajeError').css('display','block');
        }else{
            modificarItem($("#llave_turno").val());
        }
    }

    function aplicarMascara(){
        $.mask.definitions['N']='[012345]';
        $.mask.definitions['H']='[012]';
        $.mask.definitions['n']='[0123456789]';
        $('.horaTabla').mask('Hn:Nn');
    }

    function eventoPestana(){
        var estado = $('#aplica_mascara').val();
        if(!estado){
            aplicarMascara();
            $('#aplica_mascara').val(true);
        }
    }
