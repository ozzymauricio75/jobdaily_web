/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elecciFón) cualquier versión posterior
*
  * Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Hacer un llamado a todas aquellas funciones y opciones que apliquen para cualquier componente común que esté siendo ejecutado ***/
function ejecutarFuncionesGlobales() {
    /*** Evitar el cache de datos en las peticiones ***/
    $.ajax({cache: false});

    /*** Estilo para el bloqueador de contenido ***/
    $.blockUI.defaults.css = {
        padding:        0,
        margin:         '-30px',
        width:          '60px',
        height:         '60px',
        top:            '40%',
        left:           '50%',
        textAlign:      'center',
        color:          '#000',
        cursor:         'wait'
    };

    /*** Bloquear el contenido de la página cuando se termine un evento AJAX ***/
    $().ajaxStop($.unblockUI);

    /*** Corregir problema de transparencias de imágenes PNG en Internet Explorer < 7 ***/
    jQuery.ifixpng('imagenes/pixel.gif');
    jQuery('*').ifixpng();

    /*** Igualar el tamaño del bloque derecho con el del bloque izquierdo ***/
    igualarBloques('#bloqueIzquierdo','#bloqueDerecho');

    /*** Formatear el menú ***/
    generarMenu();

    /*** Formatear la tabla principal ***/
    formatearTabla();

    /*** Enfocar por defecto el campo para búsquedas ***/
    $('#expresionBusqueda').focus();

    /*** Realizar la búsqueda cuando se presione la tecla ENTER ***/
    $('#expresionBusqueda').keypress(function (tecla) {
        if (tecla.which == 13) {
            realizarBusqueda();
        }
    });

    /*** Restaurar el listado de elementos tras una búsqueda ***/
    $('#botonBuscar').dblclick(function () {
        deshacerBusqueda();
    });

    /*** Autocompletar la búsqueda ***/
    var base = $('#URLBase').val();
    $('#expresionBusqueda').autocomplete(base, {
        width: 250,
        max: 50,
        delay: 150,
        extraParams: {completar: true},
        formatResult: function (dato) {return '"'+dato+'"';}
    });

    /*** Cambiar el color del texto del botón al hacer clic ***/
    $('.textoBoton').mousedown(function () {
        $(this).toggleClass("click");
    }).mouseup(function () {
        $(this).toggleClass("click");
    });

}

/*** Igualar el tamaño de dos bloques (div) ***/
function igualarBloques(referente, destino) {
    $(destino).height($(referente).height());
}

/*** Generar menú a partir de listas HMTL ***/
function generarMenu() {
    $('#menuGeneral').menu(
        {
            arrowSrc: 'imagenes/flecha-menu.png',
            copyClassAttr: true,
            minWidth: 150,
            hideDelay : 150,
            onClick: function(e, menuItem){
            }
        }
    );
}

/*** Cambiar la apariencia de la tabla principal ***/
function formatearTabla() {
    $('#tablaPrincipal tr:even').addClass("even");
    $('#tablaPrincipal tr:odd').addClass("odd");
    $('#tablaPrincipal th').addClass("encabezadoTabla");

    $('#tablaPrincipal').tableHover(
        {
            clickClass: 'seleccionada'
        }
    );
}

/*** Cambiar la página de resultados a partir de un botón ***/
function ordenarResultados(columna) {
    /*** Ordenar por una columna especifica al hacer clic sobre el título de ésta en la tabla ***/
    var orden = $(columna).attr('id');
    var destino = $('#URLBase').val();
    $.getJSON(destino,{origen: 'ajax', orden: orden}, function(datos) {
        $('#contenidoPrincipal').html(datos[0]);
        $('#paginador').html(datos[1]);
        $('#registros').html(datos[2]);
        $('#botones').html(datos[3]);
        formatearTabla();
    });
    return true;
}

/*** Cambiar la página de resultados a partir de un botón ***/
function cambiarPaginaDesdeBoton(boton) {
    var destino = $('#URLBase').val();
    var pagina  = $(boton).attr('id').split('_');
    $.getJSON(destino,{origen: 'ajax', pagina: pagina[1]}, function(datos) {
        $('#contenidoPrincipal').html(datos[0]);
        $('#paginador').html(datos[1]);
        $('#registros').html(datos[2]);
        $('#botones').html(datos[3]);
        formatearTabla();
    });
    return false;
}

/*** Cambiar la página de resultados a partir de un elemento de una lista ***/
function cambiarPaginaDesdeLista(opcion) {
    var destino = $('#URLBase').val();
    $.getJSON(destino,{origen: 'ajax', pagina: opcion}, function(datos) {
        $('#contenidoPrincipal').html(datos[0]);
        $('#paginador').html(datos[1]);
        $('#registros').html(datos[2]);
        $('#botones').html(datos[3]);
        formatearTabla();
    });
    return false;
}

/*** Cambiar la página de resultados a partir de un botón ***/
function realizarBusqueda() {
    var destino = $('#URLBase').val();
    $.getJSON(destino,{origen: 'ajax', buscar: true, expresion: $('#expresionBusqueda').val()}, function(datos) {
        $('#contenidoPrincipal').html(datos[0]);
        $('#paginador').html(datos[1]);
        $('#registros').html(datos[2]);
        $('#botones').html(datos[3]);
        formatearTabla();
    });
    return false;
}

/*** Presentar de nuevo todos los registros después de haber realizado una búsqueda ***/
function deshacerBusqueda() {
    var destino = $('#URLBase').val();
    $('#expresionBusqueda').val('');
    $.getJSON(destino,{origen: 'ajax', buscar: true, expresion: $('#expresionBusqueda').val()}, function(datos) {
        $('#contenidoPrincipal').html(datos[0]);
        $('#paginador').html(datos[1]);
        $('#registros').html(datos[2]);
        $('#botones').html(datos[3]);
        formatearTabla();
    });
    return false;
}

/*** Recargar el contenido de una lista de selección cuando se seleccione un elemento de otra lista ***/
function recargarLista(origen, elemento, seleccionado) {
    var destino = $('#URLFormulario').val();
    var valor   = $('#'+origen).val();
    var lista   = '';
    $('#'+elemento).empty();

    /*** Enviar datos para la recarga ***/
    $.getJSON(destino, {recargar: true, origen: valor, elemento: elemento}, function(datos) {
        jQuery.each(datos, function(valor, texto) {
            lista = lista+'<option value="'+valor+'">'+texto+'</option>';
        });
        $('#'+elemento).html(lista);
        if(typeof(seleccionado) != "undefined"){
            $('#'+elemento).val(seleccionado);
        }
    });
}

/*** Ejecutar una acción dependiendo del botón seleccionado ***/
function ejecutarComando(boton, ancho, alto) {

    $.blockUI({message: $('#cuadroEspera'), fadeOut: 0});
    $('#mensajeBarraEstado').html('').removeAttr('class','style').css('display','block');

    /*** Verificar que se haya selecccionado una fila de la tabla principal para las operaciones que lo requieren ***/
    if ($('#tablaPrincipal td').hasClass('seleccionada')) {
            var id = $('.seleccionada:first').parent().attr('id').split('_')[1];
    } else {
        var id = '';
    }

    var base    = $('#URLBase').val().split('=');
    var comando = $(boton).attr('id');
    var destino = base[0]+'='+comando;

    /*** Descargar contenido para el cuadro de diálogo ***/
    $.getJSON(destino, {generar: true, origen: base[1], id: id}, function(datos) {

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

            /*** Generar selector de fecha  ***/
            $('.selectorFecha').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    buttonImage: 'imagenes/calendario.png',
                    buttonImageOnly: true
            })).attr('readonly', 'readonly');

            $('.selectorFechaSinImagen').datepicker(
            $.extend({},$.datepicker.regional['es'], {
                dateFormat: 'yy-mm-dd'
            })).attr('readonly', 'readonly');

            /////////////////Nuevos 2011-03-16///////////////
              //var minDate="-"+$("#minDate").val()+"D";
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
            /////////////////////////////////////////////////

              $('.fechaRangoBloquear').datepicker($.extend({},
                            $.datepicker.regional['es'], {
                            numberOfMonths:1, showAnim:"fadeIn",
                            buttonImage: 'imagenes/calendario.png',
                            buttonImageOnly: true,
                            altField: "#Range",
                            dateFormat: "yy/mm/dd",
                            yearRange: "-100:10",
                            rangeSelect: true,
                            minDate : minDate
              })).attr('readonly', 'readonly');
              ///////////////////////////////////////////////
            $(".fechaAntigua").attr("readonly","readonly").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:0",
            buttonImage: 'imagenes/calendario.png',
            buttonImageOnly: true
            });

            $(".fechaNuevas").attr("readonly","readonly").datepicker({
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:10",
            buttonImage: 'imagenes/calendario.png',
            buttonImageOnly: true
            });

            $(".fechaRango").attr("readonly","readonly").datepicker({
                numberOfMonths:1, showAnim:"fadeIn",
                buttonImage: 'imagenes/calendario.png',
                buttonImageOnly: true,altField: "#Range",
                dateFormat: "yy/mm/dd",
                yearRange: "-100:10",
                rangeSelect: true
            });

            /*** Generar visualización de pestañas ***/
            var $pestanas = $('#pestanas > ul').tabs({
                show: function () {
                    $('*').blur();
                    $('#pestanas > .ui-tabs-panel:visible :input:first').not(".selectorFecha, .fechaAntigua, .fechaNuevas, .selectorFechaBloquear, .fechaRangoBloquear, .selectorFechaSinImagen").focus();
                    $('#pestanas > .ui-tabs-panel').css('width',ancho - 20);
                    $('#pestanas > .ui-tabs-panel').css('height',alto - 140);
                    $('#pestanas > .ui-tabs-panel').css('overflow','auto');
                }
            });

            /*** Dimensionar pestañas de acuerdo al tamaño del cuadro de diálogo ***/

            /*** Autocompletar la selección ***/
            /*** Minimo 3 caracteres***/
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
                formatResult: function(datos, valor) {return valor.split("|")[0];}
            }).blur(function() {
                var x = $(this).attr('id');
                $(x).autocomplete().remove();
            });

            $('.autocompletable').result(function(evento, datos, formateado) {
                if (datos) {
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

            /*** Minimo 1 caracter***/
            $('.autocompletable_1').autocomplete(destino, {
                minChars: 1,
                width: 250,
                max: 100,
                delay: 100,
                matchContains: true,
                cacheLength: 100,
                extraParams: {
                    completar: true
                },
                formatResult: function(datos, valor) {return valor.split("|")[0];}
            }).blur(function() {
                var x = $(this).attr('id');
                $(x).autocomplete().remove();
            });

            $('.autocompletable_1').result(function(evento, datos, formateado) {
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
            $.mask.definitions['H']='[012]';
            $.mask.definitions['N']='[012345]';
            $.mask.definitions['n']='[0123456789]';
            $('.hora').mask('Hn:Nn');

            /*** Ocultar inicialmente todos los elementos que contengan la clase 'oculto' ***/
            $('.oculto').parent().hide();

            /*** Forzar la visualización  del cuadro de diálogo ***/
            $('#cuadroDialogo').css('display','block');


        } else {
            //$('#mensajeBarraEstado').html(datos[0]).fadeOut(3000).addClass('mensajeError');
            alert(datos[0]);
            $('#expresionBusqueda').focus();
        }

    });

    return true;
}

/*** Validar un elemento ***/
function validarItem(item) {
    $('#errorDialogo').html('');
    $('#errorDialogo').removeAttr('class','style');
    $('#errorDialogo').css('display','block');

    var destino = $('#URLFormulario').val();
    var id_item = $(item).attr('id');

    /*** Verificar que se haya selecccionado una fila de la tabla principal para las operaciones que lo requieren ***/
    if ($('#tablaPrincipal td').hasClass('seleccionada')) {
            var id = $('.seleccionada:first').parent().attr('id').split('_')[1];
    } else {
        var id = '';
    }

    /*** Descargar contenido para el cuadro de diálogo ***/
    $.getJSON(destino, {validar: true, id: id, item: id_item, valor: $(item).val()}, function(mensaje) {
        if (mensaje) {
            $(item).parent().children('#errorDialogo').remove();
            $(item).focus();
            $(item).parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
            $(item).parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }
    });
}

/*** Procesar formulario para la adición de un elemento ***/
function adicionarItem() {
    $('#indicadorEsperaComando').css("display","block");
    $('#errorDialogo').html('').removeAttr('class','style').css('display','block');
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'procesar', value: true});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                //$('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
                alert(datos[1]);
            } else {
                pagina = $('#pagina').val();
                cambiarPaginaDesdeLista(pagina);
                $('#cuadroDialogo').dialog("close");
                $('#cuadroDialogo').dialog("destroy");
                $('#cuadroDialogo').empty();
/*                $('#formularioPrincipal').resetForm();
                var $pestanas = $('#pestanas > ul').tabs({selected: 0});
                $('.ui-tabs-panel:visible :input:first').focus();*/
                //$('#mensajeBarraEstado').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
                alert(datos[1]);
            }

            $('#indicadorEsperaComando').css("display","none");
        }
    });

    return true;
}

/*** Procesar formulario para la adición de un elemento ***/
function modificarItem(id) {
    $('#indicadorEsperaComando').css("display","block");
    $('#errorDialogo').html('');
    $('#errorDialogo').removeAttr('class','style');
    $('#errorDialogo').css('display','block');
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'id', value: id});
            formData.push({name: 'procesar', value: true});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                //$('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
                alert(datos[1]);
            } else {
                pagina = $('#pagina').val();
                cambiarPaginaDesdeLista(pagina);
                $('#cuadroDialogo').dialog("close");
                $('#cuadroDialogo').dialog("destroy");
                $('#cuadroDialogo').empty();
                //$('#mensajeBarraEstado').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
                alert(datos[1]);
            }
            $('#indicadorEsperaComando').css("display","none");
        }
    });

    return true;
}

/*** Procesar formulario para la eliminación de un elemento ***/
function eliminarItem(id) {
    $('#indicadorEsperaComando').css("display","block");
    $('#errorDialogo').html('');
    $('#errorDialogo').removeAttr('class','style');
    $('#errorDialogo').css('display','block');
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'id', value: id});
            formData.push({name: 'procesar', value: true});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                //$('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
                alert(datos[1]);
            } else {
                pagina = $('#pagina').val();
                cambiarPaginaDesdeLista(pagina);
                $('#cuadroDialogo').dialog("close");
                $('#cuadroDialogo').dialog("destroy");
                $('#cuadroDialogo').empty();
                //$('#mensajeBarraEstado').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
                alert(datos[1]);
            }
            $('#indicadorEsperaComando').css("display","none");
        }
    });
}

/*** Procesar formulario para generar un documento ***/
function imprimirItem(cerrar, id) {

    cerrar = typeof(cerrar) != 'undefined' ? cerrar : false;
    id     = typeof(id) != 'undefined' ? id : '';

    $('#indicadorEsperaComando').css("display","block");
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'id', value: id});
            formData.push({name: 'procesar', value: true});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                alert(datos[1]);
            } else {
                if(cerrar == true){
                    pagina = $('#pagina').val();
                    cambiarPaginaDesdeLista(pagina);
                    $('#cuadroDialogo').dialog("close");
                    $('#cuadroDialogo').dialog("destroy");
                    $('#cuadroDialogo').empty();
                    alert(datos[1]);
                    window.open(datos[2], '_blank');
                }else{
                    window.open(datos[2], '_blank');
                }
            }
            $('#indicadorEsperaComando').css("display","none");
        }
    });

    return true;
}

/*** Procesar formulario para la exportación de datos ***/
function exportarDatos(seleccion) {
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'procesar', value: true});
            formData.push({name: 'seleccion', value: seleccion});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                $('#errorDialogo').html(datos[1]).fadeOut(6000).addClass('mensajeError');
            } else {
                $('#errorDialogo').html(datos[1]);
            }
        }
    });
}

/*** Procesar formulario para la exportación de datos cuando posee un indice***/
function exportarDatosIndice(id) {
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'id', value: id});
            formData.push({name: 'procesar', value: true});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                $('#errorDialogo').html(datos[1]).fadeOut(6000).addClass('mensajeError');
            } else {
                $('#errorDialogo').html(datos[1]);
            }
        }
    });
}

var nav = window.Event ? true : false;
function campoEntero(evento){
    /*** Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46 ***/
    var tecla = nav ? evento.which : evento.keyCode;
    return (tecla <= 13 || (tecla >= 48 && tecla <= 57));
}

function campoDecimal(evento){
    /*** Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46 ***/
    var tecla = nav ? evento.which : evento.keyCode;
    return (tecla <= 13 || (tecla >= 48 && tecla <= 57) || tecla == 46 );
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

    /*** funciones usadas en los balances de contabilidad ***/
    function seleccionar_todas_sucursales(){

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
        } else {
            $(".sucursales_electrodomesticos:checkbox").attr('checked','checked');
            $(".empresas_consolidados:checkbox").attr('checked','checked');
        }
    }

    function todosTercero(){
        if($('#todos_terceros').attr('checked')){
            $('#selector1').parent().hide();
            $('#id_tercero').val('')
        }else{
            $('#selector1').parent().show();
        }
    }
    function todosClientes(){
        if($('#todos_clientes').attr('checked')){
            $('#selector1').parent().hide();
            $('#id_cliente_mayorista').val('')
        }else{
            $('#selector1').parent().show();
        }
    }

    function todosDocumentos(){
        if($('#todos_documentos').attr('checked')){
            $('#numero_desde').parent().parent().hide();
            $('#tipo_documento').parent().parent().hide();
        }else{
            $('#tipo_documento').parent().parent().show();
            todosConsecutivos();
        }
    }

    function todosConsecutivos(){
        if($('#todos_consecutivos').attr('checked')){
            $('#numero_desde').parent().parent().hide();
        }else{
            $('#numero_desde').parent().parent().show();
        }
    }

    function validarFecha() {
        var inicio  = $('#fecha_desde').val();
        var fin     = $('#fecha_hasta').val();

        if (inicio >= fin) {
            $('#fecha_hasta').val('');
            var mensaje = $('#error_fechas').val();
            alert(mensaje);
        }
    }

    function validarCuenta() {
        var cuenta_desde  = $('#cuenta_desde').val();
        var cuenta_hasta  = $('#cuenta_hasta').val();

        if (cuenta_desde > cuenta_hasta) {
            $('#cuenta_desde').val('');
            $('#cuenta_hasta').val('');
            var MensajeError = $('#error_cuentas').val();
            $('#cuenta_hasta').parent().children('#errorDialogo').remove();
            $('#cuenta_hasta').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+MensajeError+'</span>');
            $('#cuenta_hasta').parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
            $('#cuenta_hasta').focus();
        }
    }

    function validarNumeros() {
        var inicio  = parseInt($('#numero_desde').val());
        var fin     = parseInt($('#numero_hasta').val());

        if (inicio > fin) {
            $('#numero_desde').val('');
            $('#numero_hasta').val('');
            var MensajeError = $('#error_numeros').val();
            $('#numero_hasta').parent().children('#errorDialogo').remove();
            $('#numero_hasta').parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+MensajeError+'</span>');
            $('#numero_hasta').parent().children('.mensajeErrorLinea').fadeOut(7000).addClass('mensajeError').css('display','block');
            $('#numero_hasta').focus();
        }
    }

    function activarManejoDetalles(campo){
        var nivel = parseInt($(campo).val());
        if (nivel<5){
            $('#detalles_balance').parent().hide();
            $('#detalles_balance').addClass("campoInactivo").attr("disabled");
        } else {
            $('#detalles_balance').parent().show();
            $('#detalles_balance').removeClass("campoInactivo").removeAttr("disabled");
        }
    }

    /*** Procesar formulario para generar un documento ***/
    function imprimirItemCondicion(cerrar, id) {

        cerrar = typeof(cerrar) != 'undefined' ? cerrar : false;
        id     = typeof(id) != 'undefined' ? id : '';

        $('#indicadorEsperaComando').css("display","block");
        $('#formularioPrincipal').ajaxForm();
        $('#formularioPrincipal').ajaxSubmit({
            beforeSubmit: function(formData, jqForm, options) {
                formData.push({name: 'id', value: id});
                formData.push({name: 'procesar', value: true});
            },
            dataType: 'json',
            success: function(datos) {
                if (datos[0]) {
                    alert(datos[1]);
                } else {
                    if(cerrar == true){
                        pagina = $('#pagina').val();
                        cambiarPaginaDesdeLista(pagina);
                        $('#cuadroDialogo').dialog("close");
                        $('#cuadroDialogo').dialog("destroy");
                        $('#cuadroDialogo').empty();
                        alert(datos[1]);
                        if(datos[3] == true){
                            window.open(datos[2], '_blank');
                        }
                    }else{
                        if(datos[3] == true){
                            window.open(datos[2], '_blank');
                        }
                    }
                }
                $('#indicadorEsperaComando').css("display","none");
            }
        });

        return true;
    }

    /*** Validar un elemento de acuerdo a su llave primaria (LSM) ***/
    function validarItemsllaves(item,llave_valor,llave_post){
        var destino  = $('#URLFormulario').val();

        var valor_campo = "";
        var id_item     = $(item).attr('id');
        var llave_v     = llave_valor.split("|");
        var valor_item  = $(item).val();

        for(var i=0; i<llave_v.length;i++){

            valor = llave_v[i];
            valor = $("#"+valor).val();

            if((i+1)== llave_v.length){
                valor_campo += valor;
            }
            else{
                valor_campo += valor+"|";
            }

        }

        $.getJSON(destino, {
                validarItemsllaves:true,
                id:llave_post,
                item:id_item,
                valor:valor_campo,
                valor_item:valor_item
            },
            function(mensaje){
                if(mensaje != ""){
                    $(item).parent().children('#errorDialogo').remove();
                    $(item).parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                    $(item).parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                    $(item).focus();
                }
            }
        );
    }

    function cargarFechaPago(){

        var destino          = $('#URLFormulario').val();
        var codigo_planilla  = $('#codigo_planilla').val();
        var fecha_inicio     = $('#fecha_inicio').val();
        var fecha_fin        = $('#fecha_fin').val();
        var lista            = '';
        $('#fecha_pago').empty();
        $('#periodo').empty();

        /*** Enviar datos para la recarga ***/
        $.getJSON(destino, {recargar: true, codigo_planilla: codigo_planilla, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#fecha_pago').html(lista);
        });

        setTimeout("cargarPeriodoPlanilla()",200);
    }



    function cargarPeriodoPlanilla1(){

        var destino         = $('#URLFormulario').val();
        var codigo_planilla = $('#codigo_planilla').val();
        var ano_generacion  = $('#ano_generacion').val();
        var mes_generacion  = $('#mes_generacion').val();
        var lista           = '';
        var periodo         = "periodo";
        /*** Enviar datos para la recarga ***/
        $.getJSON(destino, {recargar: true, codigo_planilla: codigo_planilla, ano_generacion: ano_generacion, mes_generacion: mes_generacion, periodo: periodo}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';

            });

            $('#periodo').html(lista);

        });
    }

      function cargarFechaPago2(){

        var destino          = $('#URLFormulario').val();
        var codigo_planilla  = $('#codigo_planilla').val();
        var ano_generacion   = $('#ano_generacion').val();
        var mes_generacion   = $('#mes_generacion').val();
        var lista            = '';

        $('#fecha_pago').empty();
        $('#periodo').empty();

        /*** Enviar datos para la recarga ***/
        $.getJSON(destino, {recargar: true, codigo_planilla: codigo_planilla, ano_generacion: ano_generacion, mes_generacion: mes_generacion}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#fecha_pago').html(lista);
            determinarPeriodo();
        });

       // setTimeout("determinarPeriodo()",200);
    }

    function determinarPeriodo()
    {
        var destino         = $('#URLFormulario').val();
        var codigo_planilla = $('#codigo_planilla').val();
        var fechaPago       = $("#fecha_pago").val();

        if(fechaPago!=null){
            datosFecha          = fechaPago.split("-");
            dia                 = parseInt(datosFecha[2]);

            $.getJSON(destino, {recargarTipoPlanilla: true, codigo_planilla: codigo_planilla}, function(tipo) {

                var planillas_fecha_unica = $("#planillas_fecha_unica").val();
                if(tipo=='2'){

                    if (typeof(planillas_fecha_unica)!="undefined"){

                        $('.todas_planillas:checkbox').each(function () {
                            var id = $(this).val();
                            if ($(this).is(':checked')) {
                                $(".todas_planillas:checkbox").removeAttr('checked');
                            }
                        });
                        $('#planillas_fecha_unica').parent().hide();
                        $('#planillas_fecha_unica').addClass("campoInactivo").attr("disabled");
                    }
                    if(dia<=15){
                        $("#nombre_periodo").text($("#primera_quincena").val());
                        $("#periodo").val("2");
                    }else{
                        $("#nombre_periodo").text($("#segunda_quincena").val());
                        $("#periodo").val("3");
                    }
                }else if (tipo=='1'){

                    if (typeof(planillas_fecha_unica)!="undefined"){
                        $('#planillas_fecha_unica').parent().hide();
                        $('#planillas_fecha_unica').addClass("campoInactivo").attr("disabled");
                        $('.todas_planillas:checkbox').each(function () {
                            var id = $(this).val();
                            if ($(this).is(':checked')) {
                                $(".todas_planillas:checkbox").removeAttr('checked');
                            }
                        });
                    }

                    $("#nombre_periodo").text($("#mensual").val());
                    $("#periodo").val("1");
                }else if (tipo=='4'){
                    $("#nombre_periodo").text($("#fecha_unica").val());
                    $("#periodo").val("9");

                    if (typeof(planillas_fecha_unica)!="undefined"){
                        $('#planillas_fecha_unica').parent().show();
                        $('#planillas_fecha_unica').removeClass("campoInactivo").removeAttr("disabled");
                    }
                }

            });
        }

    }

    function borrarEspacios(cadena){
        var campo = cadena.replace(/^\s+/g,'').replace(/\s+$/g,'');
        return campo;
    }

    function seleccionHijos(campo) {
        var id    = $(campo).attr('id');
        var clase = $(campo).attr('class');

        if ($(campo).is(':checked')) {
            var marcar = 1;
        } else {
            var marcar = 0;
        }

        $('li.'+clase).find(':checkbox').each(function(){

            if ($(this).attr('id') != id){
                if (marcar==0) {
                    $(this).removeAttr("checked");
                } else {
                    $(this).attr("checked", "checked");
                }
            }
        });
    }

    function referenciaHijo(campo){

        var referencia = $(campo).children("a").attr("href");
        if (referencia){
            if (referencia !="#"){
                document.location.href = referencia;
            } else {
                var hijo = $(campo).children("a");
                ejecutarComando(hijo,600,500);
            }
        }
    }

    function in_array(elemento, arreglo){/*** Para determinar si un elemento esta dentro de un array ***/
        var a=false;
        for(var i=0;i<arreglo.length;i++){
            if(elemento == arreglo[i]){
                a=true;
                break;
            }
        }
        return a;
    }

    function campoVacio(valor){
        if((/^\s+jq/.test(valor)) || valor==''){
                return true;
        }
        return false;
    }

    function limpiar_oculto_Autocompletable(selector, oculto){/*** Aplicar con el metodo onKeyUp ***/
        if($(selector).val()==""){
            $(oculto).val('');
        }
    }

    function autocompletableListaPlanilla(item){
        var destino         = $('#URLFormulario').val();
        var listaSucursales = listadoSucursales();
        if(campoVacio(listaSucursales)){
            listaSucursales = $('#listaSucursales').val();
        }
        $(item).autocomplete(destino, {
            minChars: 3,
            width: 250,
            max: 100,
            delay: 100,
            matchContains: true,
            cacheLength: 100,
            extraParams: {
                verificar: true,
                sucursales: listaSucursales
            },
            formatResult: function(datos, valor) {return valor.split("|")[0];console.log(valor);}
        }).blur(function() {
            ///Aqui poner evento cuando pierda el foco
        });

        $(item).result(function(evento, datos, formateado) {
            if (datos) {
                $('#documento_identidad').val(datos[1]);

                ///Aqui llamar el metodo de calculo

            } else {
                $('#documento_identidad').val('');
            }

        });
    }

    function listadoSucursales(){

        var contador = 0;
        var listado  = new Array();

        $('.sucursales_electrodomesticos:checkbox').each(function () {
            var valor = $(this).val();
            if ($(this).is(':checked')) {
                listado[contador] = valor;
                contador++;
            }
        });

        return listado.join(",");
    }

