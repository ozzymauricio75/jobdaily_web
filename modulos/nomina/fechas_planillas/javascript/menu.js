    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function generarDias() {

        var listaTabla = generarListado();
        var destino       = $('#URLFormulario').val();
        var tipo_planilla = $('#tipo_planilla').val();
        var idPlanilla    = $('#id_planilla').val();
        var ano           = $("#ano").val();
        var error         = false;

        if(tipo_planilla != '0' && idPlanilla!=0){
            if(tipo_planilla != '4'){
                $("#listaDiasPagos tbody").remove();
            }
            if(tipo_planilla != '2'){
                var fechaCompleta = $('#fecha_unica').val();
                ano = fechaCompleta.split("-");
                ano = ano[0];
            }else{
                var fechaCompleta = $('#fecha_fin').val();

                var fechas  = fechaCompleta.split("-");
                var fecha1  = borrarEspacios(fechas[0]);
                var fecha2  = borrarEspacios(fechas[1]);
                var fechas1 = fecha1.split("/");
                var fechas2 = fecha2.split("/");
                var anoQ    = parseInt(fechas1[0]);
                var mesQ    = parseInt(fechas1[1]);
                var diaFinal= 0;
                if(mesQ == 4 || mesQ == 6 || mesQ == 9 || mesQ == 11){
                    diaFinal = 30;
                }else if(mesQ == 2 && ((anoQ % 4 == 0) && ((anoQ % 100 != 0) || (anoQ % 400 == 0)))){
                    diaFinal = 29;
                }else if(mesQ == 2){
                    diaFinal = 28;
                }else{
                    diaFinal = 31;
                }

                var mensaje = "";

                fecha1      = new Date(parseInt(fechas1[0]),parseInt(fechas1[1]),parseInt(fechas1[2]));
                var fecha12 = new Date(parseInt(fechas1[0]),parseInt(fechas1[1]),15);
                fecha2      = new Date(parseInt(fechas2[0]),parseInt(fechas2[1]),parseInt(fechas2[2]));
                var fecha22 = new Date(parseInt(fechas1[0]),parseInt(fechas1[1]),diaFinal);

                if(fecha1>fecha12){
                    error   = true;
                    mensaje = $('#error_fecha_quincena1').val();
                }else if(fecha2<=fecha12 || fecha2 > fecha22){
                    error   = true
                    mensaje = $('#error_fecha_quincena2').val();
                }
            }

            if(!error){
                $.getJSON(destino, {
                            generarDiasPago : true,
                            fecha_completa  : fechaCompleta,
                            id_planilla     : idPlanilla,
                            ano_final       : ano,
                            lista_tabla     : listaTabla,
                            tipo_planilla   : tipo_planilla
                        },
                        function(datos) {

                            for(var i=0;i<datos.length;i++){
                                var insertar = true;
                                var dato = datos[i].split(",");
                                if (tipo_planilla == '4'){
                                    $("#listaDiasPagos").find(".selectorFechaTabla").each(function (){
                                        fecha_tabla = $(this).val();
                                        if(fecha_tabla == dato[0]){
                                            insertar = false;
                                        }
                                    });
                                }
                                if (insertar){
                                    adicionarFechas(dato[0],dato[1],dato[2],dato[3]);
                                }
                            }
                        }
                );
            }
        }else{
            alert(mensaje);
        }
    }

    function adicionarFechas(fecha,mes,dia, estado){
        var id = new Date();
        if(estado == '1'){
            var calendario = '<input class="selectorFechaTabla" type="text" value="'+fecha+'" size="10" onClick="pegarFecha(this)" onChange="validarFechas(this)"/>'+
                             '<img class="ui-datepicker-trigger" src="imagenes/calendario.png">';
            var remover    = $('#removedor').html();
        }else{
            var calendario = "<input type='text' size='10' value='"+fecha+"' disabled = 'disabled'>";
            var remover    = "";
        }
        var valorClase = 'even';
        if ($("#listaDiasPagos tr:last").hasClass("even")) {
            valorClase = 'odd';
        } else {
            valorClase = 'even';
        }

        var item =  '<tr id="'+id+'" class="'+valorClase+'">'+
            '<td align="left">'+
                '<input type="hidden" class="fecha_tabla" name="fecha_tabla[]" value="'+fecha+'">'+
                '<input type="hidden" class="estado" name="estado[]" value="'+estado+'">'+remover+
            '</td>'+

            '<td align="left">'+mes+'</td>'+
            '<td align="left">'+dia+'</td>'+
            '<td align="left">'+calendario+'</td>'+

         '</tr>';
        $('#listaDiasPagos').append(item);

        /// Generar selector de fecha  ///
        $('.selectorFechaTabla').datepicker(
            $.extend({},$.datepicker.regional['es'], {
                dateFormat: 'yy-mm-dd',
            })).attr('readonly', 'readonly');
        ///****************************///

    }

    function cargarFechas(){
        var destino    = $('#URLFormulario').val();
        var idPlanilla = $('#id_planilla').val();
        $.getJSON(destino,{
            verificar: true,
            id_planilla:idPlanilla
        }, function(datos){
            $("#listaDiasPagos tbody").remove();
            if(datos == 2){
                $('#contenedor_fechas').parent().show();
                $('#contenedor_fechas').removeClass('campoInactivo').removeAttr('disabled','disabled');
                $('#contenedor_fechas').val();
                $('#contenedor_ano').parent().show();
                $('#contenedor_ano').removeClass('campoInactivo').removeAttr('disabled','disabled');
                $('#contenedor_fecha_unica').parent().hide();
                $('#contenedor_fecha_unica').addClass('campoInactivo').attr('disabled','disabled');
            }else{
                $('#contenedor_fecha_unica').parent().show();
                $('#contenedor_fecha_unica').removeClass('campoInactivo').removeAttr('disabled','disabled');
                $('#contenedor_fecha_unica').val();
                $('#contenedor_fechas').parent().hide();
                $('#contenedor_fechas').addClass('campoInactivo').attr('disabled','disabled');
                $('#contenedor_ano').parent().hide();
                $('#contenedor_ano').addClass('campoInactivo').attr('disabled','disabled');
            }
            $('#tipo_planilla').val(datos);
        });
    }

    function removerItem(boton){
        $(boton).parents('tr').remove();
    }

    function removerTable(boton){
        var estado = "";
        $('#listaDiasPagos').find('input[class="estado"]').each(function () {
            estado = $(this).val();
            if(estado == '1'){
                $(this).parents('tr').remove();
            }
        });
    }

    function recargarListaAno(){
        var destino    = $('#URLFormulario').val();
        var idPlanilla = $('#id_planilla').val();
        $.getJSON(destino,{
                verificar   : true,
                id_planilla : idPlanilla
            },
            function(datos){
                var lista   = '';
                if(datos==2){
                    var fecha = $("#fecha_fin").val().split("/")[0];
                }else{
                    var fecha = $("#fecha_unica").val();
                }
                var ano  = fecha.split("-")[0];
                var anos = new Array()
                for(i=0;i<9;i++){
                    anos[i]=parseInt(ano)+i;
                }
                jQuery.each(anos, function(valor, texto) {
                    lista = lista+'<option value="'+texto+'">'+texto+'</option>';
                });
                $("#ano").html(lista);
            }
        );
    }

    function generarListado(){
        var listado = "";
        var fechas  = $('#listaDiasPagos').children('tbody').children('tr').children('td').children('input[class="fecha_tabla"]');
        var fecha   = "";
        var estado  = "";
        jQuery.each(fechas, function(id, elemento){
            fecha   = $(elemento).val();
            estado  = $($(elemento).parent().children('input[class="estado"]')[0]).val();
            listado+= fecha+","+estado+"/";
        });
        return listado;
    }

    function pegarFecha(campo){
        var fecha = $(campo).val();
        $('#fecha_soporte').val(fecha);
    }

    function validarFechas(campo){

        var diasSemana = new Array();
        diasSemana[0]   = $('#domingo').val();
        diasSemana[1]   = $('#lunes').val();
        diasSemana[2]   = $('#martes').val();
        diasSemana[3]   = $('#miercoles').val();
        diasSemana[4]   = $('#jueves').val();
        diasSemana[5]   = $('#viernes').val();
        diasSemana[6]   = $('#sabado').val();

        var fecha      = $(campo).val();
        var fechaVieja = $('#fecha_soporte').val()
        var valor      = "";
        var contador   = 0;

        var datos     = fecha.split('-');
        var ano      = parseInt(datos[0]);
        var mes       = parseInt(datos[1]-1);
        var dia       = parseInt(datos[2]);
        var diaSem    = new Date(ano,mes,dia);
        var diaSemana = diasSemana[diaSem.getDay()];

        datos         = fechaVieja.split('-');
        var anoViejo = parseInt(datos[0]);
        var mesViejo  = parseInt(datos[1]-1);
        var diaViejo  = parseInt(datos[2]);

        var error   = false;
        var mensaje = true;

        if(mes != mesViejo){
            error = true;
            mensaje = $('#fuera_mes').val();
        }else if($('#tipo_planilla').val() && diaViejo <= 15 && dia >15){
            error   = true;
            mensaje = $('#error_menor_16').val();
        }else if($('#tipo_planilla').val() && diaViejo > 15 && dia <= 15){
            error   = true;
            mensaje = $('#error_mayor_15').val();
        }

        if(!error){
            $('#listaDiasPagos').find('input[class="fecha_tabla"]').each(function () {
                valor = $(this).val();
                if(valor == fecha){
                    contador++;
                }
            });
            if(contador > 0){
                $(campo).val($('#fecha_soporte').val());
                alert($('#existe_fecha').val());
            }else{
                $($(campo).parent().parent().children('td').children('input[class="fecha_tabla"]')[0]).val(fecha);
                $($(campo).parent().parent().children('td')[2]).html(diaSemana);
            }
        }else{
            $(campo).val($('#fecha_soporte').val());
            alert(mensaje);
        }
    }

    function cambiarFormatoFecha(){
        var ano = parseInt($('#ano').val());

        $('#fecha_inicial').val(ano+"-01"+"-01");

        $('.selectorFechaModificar').datepicker($.extend({},
            $.datepicker.regional['es'], {
                showOn: 'both',
                dateFormat: 'yy-mm-dd',
                buttonImage: 'imagenes/calendario.png',
                buttonImageOnly: true,
                minDate : new Date(ano,0,1),
                maxDate : new Date(ano,11,31)
        })).attr('readonly', 'readonly');
    }
