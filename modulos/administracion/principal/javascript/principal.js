    $(document).ready(function() {
        ejecutarFuncionesGlobales();
        ejecutarFuncionesLocales();

        /*** Generar selector de fecha  ***/
        /*** $('#mesAgenda').datepicker($.extend({},
        	$.datepicker.regional['es'], {
        		showOn: 'both',
	        	dateFormat: 'yy-mm-dd',
	        	onSelect: function(fecha) {

					recargarTabla(fecha);
				}
			}
		)).attr('readonly', 'readonly'); ***/

        /*** Generar visualización de pestañas ***/
        var $pestanas = $('#pestanaMenuPrincipal > ul').tabs({
			show: function () {
				$('*').blur();
				//$('.ui-tabs-panel:visible :input:first').focus();
				$('.ui-tabs-panel').css('overflow','auto');
			}
		});

		/*** Elimina etiquetas de la pestaña del Formulario de inicio***/
		$('#contenidoPrincipal').find('form').removeAttr('id');

		/*** var fecha = $.datepicker._getInst($('#mesAgenda')[0]._calId)._getDate();
		fecha = $.datepicker.formatDate('yy-mm-dd', fecha);
		recargarTabla(fechaSeleccionada);***/
    });

/***function recargarTabla(fecha) {
	
	var base    = $('#URLBase').val().split('=');
    var comando = $('#mesAgenda').parents().find('.ui-tabs-panel:first').attr('id');
    var destino = base[0]+'='+comando;***/

	/*** Descargar contenido para la tabla de la agenda ***/
	/***$.getJSON(destino, {recargar: true, fecha: fecha}, function(datos) {

		$('#tablaAgenda').html(datos[2]);
		formatearTabla();
	});
}***/

/***function fechaSeleccionada(){
	var fecha = $.datepicker._getInst($('#mesAgenda')[0]._calId)._getDate();
	return $.datepicker.formatDate('yy-mm-dd', fecha);
}***/

function validarDuracion(item){
    if ($('#duracionH').val() == 0){
		if ($('#duracionM').val() == 0) {
			$('#duracionM').val('10');
		}
    } else if ($('#duracionH').val() == 4){
    	$('#duracionM').val('00');
    }
}

/*** Hacer un llamado a todas aquellas funciones y opciones que apliquen para cualquier componente común que esté siendo ejecutado ***/
function ejecutarFuncionesLocales() {
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

}

/*** Ejecutar una acción dependiendo del botón seleccionado ***/
function ejecutarAccion(boton, ancho, alto) {
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
    });
    return true;
}

/*** Validar un elemento ***/
function validarEvento(item) {
//     $('#errorDialogo').html('');
//     $('#errorDialogo').removeAttr('class','style');
//     $('#errorDialogo').css('display','block');

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
function adicionarEvento() {
    $('#errorDialogo').html('');
    $('#errorDialogo').removeAttr('class','style');
    $('#errorDialogo').css('display','block');
    $('#formularioPrincipal').ajaxForm();
    var fecha = fechaSeleccionada();

    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'procesar', value: true});
            formData.push({name: 'fecha', value: fecha});
        },
        dataType: 'json',
        success: function(datos) {
        	//console.log(datos [0]);
            if (datos[0]) {
                $('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
            } else {
                recargarTabla(fechaSeleccionada);
                $('#cuadroDialogo').dialog("close");
                $('#cuadroDialogo').dialog("destroy");
                $('#cuadroDialogo').empty();
                $('#formularioPrincipal').resetForm();
                //var $pestanas = $('#pestanas > ul').tabs({selected: 0});
                $('.ui-tabs-panel:visible :input:first').focus();
                $('#errorDialogo').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
            }
        }
    });

    return true;
}

/*** Procesar formulario para la adición de un elemento ***/
function modificarEvento(id) {
    $('#errorDialogo').html('');
    $('#errorDialogo').removeAttr('class','style');
    $('#errorDialogo').css('display','block');
    $('#formularioPrincipal').ajaxForm();
    $('#formularioPrincipal').ajaxSubmit({
        beforeSubmit: function(formData, jqForm, options) {
            formData.push({name: 'id', value: id});
            formData.push({name: 'procesar', value: true});
            formData.push({name: 'fecha', value: fechaSeleccionada});
        },
        dataType: 'json',
        success: function(datos) {
            if (datos[0]) {
                $('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');
            } else {
                recargarTabla(fechaSeleccionada);
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
function eliminarEvento(id) {
    $('#errorDialogo').html('');
    $('#errorDialogo').removeAttr('class','style');
    $('#errorDialogo').css('display','block');

    //var base    = $('#URLBase').val();
    //var destino = $('#URLFormulario').val();
    
	var base    = $('#URLBase').val().split('=');
    var comando = $('#formularioPrincipal').attr('action').split('=');
    var destino = base[0]+'='+comando[1];

    /*** Enviar datos para la eliminación ***/
    $.getJSON(destino, {procesar: true, id: id}, function(datos) {

        /*** Presentar mensaje de error ***/
        if (datos[0]) {
            $('#errorDialogo').html(datos[1]).fadeOut(3000).addClass('mensajeError');

        /*** Cerrar el cuadro de diálogo y presentar mensaje de éxito ***/
        } else {
            recargarTabla(fechaSeleccionada);
            $('#cuadroDialogo').dialog("close");
            $('#cuadroDialogo').dialog("destroy");
            $('#cuadroDialogo').toggle();
            $('#cuadroDialogo').empty();
            $('#mensajeBarraEstado').html(datos[1]).fadeOut(5000).addClass('mensajeExito');
        }

    });
}
