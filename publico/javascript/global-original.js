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
* de la Licencia, o (a su elección) cualquier versión posterior
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

/*** Determinar si un objeto es una funcion, en reemplazo de la imprecisa funcion 'jQuery.isFunction' ***/
function esFuncion(objeto) {
    if(typeof objeto != "function") return false;
    if(typeof objeto.constructor != "function") return false;
    return objeto.constructor.prototype.hasOwnProperty( "call" );
}

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
function recargarLista(origen, elemento) {
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
    });
}

/*** Ejecutar una acción dependiendo del botón seleccionado ***/
function ejecutarComando(boton, ancho, alto) {
    $.blockUI({message: $('#cuadroEspera'), fadeOut: 0});
    $('#mensajeBarraEstado').html('');
    $('#mensajeBarraEstado').removeAttr('class','style');
    $('#mensajeBarraEstado').css('display','block');

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

              var a="-"+10+"D";
              $('.selectorFechaBloquear').datepicker($.extend({},
                            $.datepicker.regional['es'], {
                                showOn: 'both',
                                dateFormat: 'yy-mm-dd',
                                buttonImage: 'imagenes/calendario.png',
                                buttonImageOnly: true,
                                minDate : a
              })).attr('readonly', 'readonly');

            /*** Generar visualización de pestañas ***/
            var $pestanas = $('#pestanas > ul').tabs({
                show: function () {
                    $('*').blur();
                    $('.ui-tabs-panel:visible :input:first').focus();
                    $('.ui-tabs-panel').css('width',ancho - 20);
                    $('.ui-tabs-panel').css('height',alto - 140);
                    $('.ui-tabs-panel').css('overflow','auto');
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
                formatResult: function(datos, valor) { return valor.split("|")[0]; }
            }).blur(function() {
                var x = $(this).attr('id');
                $(x).autocomplete().remove();
            });

            $('.autocompletable').result(function(evento, datos, formateado) {
                if (datos) {
                    $(this).parent().find('input:hidden').val(datos[1]);

                    if (jQuery.isFunction(recargarDatos)) {
                        recargarDatos(datos[1]);
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

            /*** Ocultar inicialmente todos los elementos que contengan la clase 'oculto' ***/
            $('.oculto').parent().hide();

            /*** Forzar la visualización  del cuadro de diálogo ***/
            $('#cuadroDialogo').css('display','block');


        } else {
            $('#mensajeBarraEstado').html(datos[0]).fadeOut(3000).addClass('mensajeError');
            $('#expresionBusqueda').focus();
        }

/*        if (esFuncion(ejecutarFuncionesLocales)) {
            ejecutarFuncionesLocales();
        }*/
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
            $(item).focus();
            $('#errorDialogo').html(mensaje).fadeOut(3000).addClass('mensajeError');
        }
    });
}

/*** Procesar formulario para la adición de un elemento ***/
function adicionarItem() {
    $('#errorDialogo').html('');
    $('#errorDialogo').removeAttr('class','style');
    $('#errorDialogo').css('display','block');
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'procesar', value: true});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                $('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
            } else {
                pagina = $('#pagina').val();
                cambiarPaginaDesdeLista(pagina);
                $('#cuadroDialogo').dialog("close");
                $('#cuadroDialogo').dialog("destroy");
                $('#cuadroDialogo').empty();
/*                $('#formularioPrincipal').resetForm();
                var $pestanas = $('#pestanas > ul').tabs({selected: 0});
                $('.ui-tabs-panel:visible :input:first').focus();*/
                $('#mensajeBarraEstado').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
            }
        }
    });

    return true;
}

/*** Procesar formulario para la adición de un elemento ***/
function modificarItem(id) {
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
                $('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
            } else {
                pagina = $('#pagina').val();
                cambiarPaginaDesdeLista(pagina);
                $('#cuadroDialogo').dialog("close");
                $('#cuadroDialogo').dialog("destroy");
                $('#cuadroDialogo').empty();
                $('#mensajeBarraEstado').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
            }
        }
    });

    return true;
}

/*** Procesar formulario para la eliminación de un elemento ***/
function eliminarItem(id) {
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
                $('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
            } else {
                pagina = $('#pagina').val();
                cambiarPaginaDesdeLista(pagina);
                $('#cuadroDialogo').dialog("close");
                $('#cuadroDialogo').dialog("destroy");
                $('#cuadroDialogo').empty();
                $('#mensajeBarraEstado').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
            }
        }
    });
}

/*** Procesar formulario para la exportación de datos ***/
function exportarDatos() {
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'procesar', value: true});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                $('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
            } else {
                $('#errorDialogo').html(datos[1]);
            }
        }
    });
}