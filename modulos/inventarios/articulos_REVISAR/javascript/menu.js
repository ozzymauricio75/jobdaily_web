    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function cargarReferencia(){
        var valor  = $('#codigo').val();
        $('#referencia').val(valor);
    }

    function recargarDatos(id)
    {
        var destino = $('#URLFormulario').val();
        $.getJSON(destino, {recargarPais: true, documento_identidad_proveedor:id}, function(elementos) {
            if (elementos) {
                var pais = elementos[0];
                $('#codigo_iso').val(pais);
                $('#codigo_marca').html('');

                var codigo_marcas    = elementos[1];
                vector_codigo_marcas = codigo_marcas.split('-');

                var descripcion_marcas    = elementos[2];
                vector_descripcion_marcas = descripcion_marcas.split('-');

                for(var i=0;i<vector_codigo_marcas.length;i++){
                    $('#codigo_marca').append('<option value="'+vector_codigo_marcas[i]+'">' +vector_descripcion_marcas[i]+ '</option>');
                }
            }
        });
    }

    function agregarItem() {

        var codigo              = $('#codigo').val();
        var referencia          = $('#referencia_alterna').val();
        var referencia_actual   = $('#referencia_actual').val();
        var codigo_barras       = $('#codigo_barras_alterna').val();
        var orden               = parseInt($('#orden_referencias').val());
        var existe_referencia   = 0;
        var existe_principal    = 0;

        if (referencia) {

            var boton      = $('#removedor').html();

            var valorClase = 'even';

            if ($("#lista_items_referencias tr:last").hasClass("even")) {
                valorClase = 'odd';
            }

            var item  = '<tr id="fila_'+orden+'" class="'+valorClase+'">'+
                            '<td align="center">'+
                            //'<input type="hidden" class="referencias" name="referencias['+orden+']" value="'+orden+'">'+
                            '<input type="hidden" class="referencias" name="referencias['+orden+']" value="'+orden+'">'+
                            '<input type="hidden" class="referencias_alternas" name="referencias_alternas['+orden+']" value="'+referencia+'">'+
                            '<input type="hidden" class="codigo_barras_referencia" name="codigo_barras_referencia['+orden+']" value="'+codigo_barras+'">'+
                            '<input type="hidden" class="estadoModificar" name="estadoModificar['+orden+']" value="0">'+boton+
                            '</td>'+
                            '<td align="left">'+referencia+'</td>'+
                            '<td align="left">'+codigo_barras+'</td>'+
                        '</tr>';

            $('#lista_items_referencias').append(item);
            $('#referencia_alterna').val('');
            $('#codigo_barras_alterna').val('');
            orden++;
            $('#orden_referencias').val(orden);
        }
    }

    function removerItem(boton) {

        var destino      = $('#URLFormulario').val();
        //var cantidad     = $(".referencia_tabla").length;
        var indice_tabla = $(boton).parents('tr:first').attr('id').split('_')[1];
        var principal    = $("input[name='estadoModificar["+indice_tabla+"]']").val();

        if(principal=='1'){
            $.getJSON(destino,{eliminarReferencia:true, indice_tabla:indice_tabla}, function(datos){
                if(parseInt(datos[0]) == 1){
                    $(boton).parents('tr').remove();
                }else{
                    alert(datos[1]);
                }
            });
        }
        $(boton).parents('tr').remove();
    }
   