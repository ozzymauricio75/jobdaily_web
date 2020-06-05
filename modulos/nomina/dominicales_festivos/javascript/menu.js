$(document).ready(function() {
    ejecutarFuncionesGlobales();
});

    
function generarFestivo() {
     
    var destino = $('#URLFormulario').val();
    var id      = new Date();
    var fecha   = $("#fecha").val();
    var descripcion = $("#descripcion").val();
    var valorClase  = 'even';
    var remover     = $('#botonRemover').html();
    var fecha_descripcion = "";
    var existe=true;
    var existe_domingo = true;
	
    $.getJSON(destino,{
        fechadescripcion: true,
        fecha:fecha
    }, function(dato){
            fecha_descripcion=dato;
            if ($("#listaFestivos tr:last").hasClass("even")) {
                valorClase = 'odd';
            }else{
                valorClase = 'even';
            }
            $('#listaFestivos').find('.fecha_festivo').each(function () {
                id = $(this).val();
                if(fecha==id){
                    existe=false;
                }
            });
            $('#listaDomingos').find('.fecha_domingo').each(function () {
                id = $(this).val();
                if(fecha==id){
                    existe_domingo=false;
                }
            });
            if(existe){
                if(existe_domingo){
                    $.getJSON(destino,{
                        validarFechas: true,
                        fecha:fecha
                    }, function(dato){
                        if(dato){
                            var item  = '<tr id="'+id+'" class="'+valorClase+'">'+
                            '<td align="center">'+
                            '<input type="hidden" class="fecha_festivo" name="fecha_festivo[]" value="'+fecha+'">'+
                            '<input type="hidden" class="descripcionTabla" name="descripcionTabla[]" value="'+descripcion+'">'+
                            remover+
                            '<td class="dato" align="left">'+fecha_descripcion+'</td>'+
                            '<td class="dato" align="left">'+descripcion+'</td>'+
                            '</tr>';

                            $('#listaFestivos').append(item);
                            $('#descripcion').val('');
                        }else{
                            $("#botonAgregar").parent().children('#errorDialogo').remove();
                            $("#botonAgregar").focus();
                            $("#botonAgregar").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+$("#mensaje_base").val()+'</span>');
                            $("#botonAgregar").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                        }
                    });
                }else{
                    $("#botonAgregar").parent().children('#errorDialogo').remove();
                    $("#botonAgregar").focus();
                    $("#botonAgregar").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+$("#mensaje_domingo").val()+'</span>');
                    $("#botonAgregar").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            }else{
                $("#botonAgregar").parent().children('#errorDialogo').remove();
                $("#botonAgregar").focus();
                $("#botonAgregar").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+$("#mensaje").val()+'</span>');
                $("#botonAgregar").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
            }
    });
}

function modificarFestivo() {
    var destino = $('#URLFormulario').val();
    var id      = new Date();
    var fecha = $("#fecha").val();
    var descripcion = $("#descripcion").val();
    var valorClase = 'even';
    var remover = $('#botonRemover').html();
    var fecha_descripcion = "";
    var existe=true;
    var existe_domingo = true;
					
   $.getJSON(destino,{
        fechadescripcion: true,
        fecha:fecha
    }, function(dato){
            fecha_descripcion=dato;
            if ($("#listaFestivos tr:last").hasClass("even")) {
                valorClase = 'odd';
            }else{
                valorClase = 'even';
            }
           $('#listaFestivos').find('.fecha_festivo').each(function () {
                id = $(this).val();

                if(fecha==id){
                    existe=false;
                }
            });
            $('#listaDomingos').find('.fecha_domingo').each(function () {
                id = $(this).val();
                if(fecha==id){
                    existe_domingo=false;
                }
            });

            if(existe){
                if(existe_domingo){
                    var item  = '<tr id="'+id+'" class="'+valorClase+'">'+
                    '<td align="center">'+
                    '<input type="hidden" class="fecha_festivo" name="fecha_festivo[]" value="'+fecha+'">'+
                    '<input type="hidden" class="descripcionTabla" name="descripcionTabla[]" value="'+descripcion+'">'+
                    remover+
                    '<td class="dato" align="left">'+fecha_descripcion+'</td>'+
                    '<td class="dato" align="left">'+descripcion+'</td>'+
                    '</tr>';

                    $('#listaFestivos').append(item);
                    $('#descripcion').val('');
                }else{
                    $("#botonAgregar").parent().children('#errorDialogo').remove();
                    $("#botonAgregar").focus();
                    $("#botonAgregar").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+$("#mensaje_domingo").val()+'</span>');
                    $("#botonAgregar").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                }
            }else{
                $("#botonAgregar").parent().children('#errorDialogo').remove();
                $("#botonAgregar").focus();
                $("#botonAgregar").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+$("#mensaje").val()+'</span>');
                $("#botonAgregar").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
            }
    });
}
    
function removerItem(boton) {
    $(boton).parents('tr').remove();
}
    
function removerTable(boton) {
    $("#listaFestivos tbody").remove();
}

