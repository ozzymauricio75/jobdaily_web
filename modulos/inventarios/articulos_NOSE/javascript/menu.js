    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function cargarReferencia(){
        var valor  = $('#codigo').val();
        $('#referencia').val(valor);
    }
    
    /*** Cargar datos si el tercero existe ***/
    function cargarDatosArticulo() {
        var destino           = $('#URLFormulario').val();
        var referencia        = $('#selector2').val();

        /*** Descargar contenido  ***/
        $.getJSON(destino, {recargar: true, referencia_carga: referencia}, function(datos){
            if (datos != ""){
                $('#codigo').val(datos[0]);
                $('#selector1').val(datos[20]);
                $('#tipo_articulo').val(datos[2]);
                //$('#codigo_alfanumerico').val(datos[3]);
                $('#codigo_barras').val(datos[21]);
                $('#descripcion').val(datos[1]);
                $('#alto').val(datos[4]);
                $('#ancho').val(datos[5]);
                $('#profundidad').val(datos[6]);
                $('#peso').val(datos[7]);
                $('#formato_imprime').val(datos[18]);
                $('#ficha_tecnica').val(datos[3]);
                $('#arbolGrupos').val(datos[11]);
                $('#codigo_impuesto_compra').val(datos[8]);
                $('#codigo_impuesto_venta').val(datos[9]);
                $('#codigo_marca').val(datos[10]);
                $('#codigo_unidad_compra').val(datos[14]);
                $('#codigo_iso').val(datos[16]);
            }
        });
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

    function agregarItemArticulo() {

        var destino             = $('#URLformulario').val();
        var indice              = $('#indice').val();
        var referencia          = $('#referencia').val();
        var codigo              = $('#codigo').val();
        var codigo_alfanumerico = $('#codigo_alfanumerico').val();
        var codigo_barras       = $('#codigo_barras_alterna').val();
        var existe_referencia   = 0;
        var existe_principal    = 0;
        var campo_principal     = "";

        $(destino).find(".referencia_tabla").each(function(){

            var referencia_tabla = $(this).val();
            if (referencia == referencia_tabla){
                existe_referencia = 1;
            }
        });

        if (referencia && codigo_alfanumerico && codigo_alfanumerico!=referencia && existe_referencia==0) {
                                            
            var boton      = $('#removedor').html();
            var valorClase = 'even';
            if ($("#lista_items tr:last").hasClass("even")) {
                valorClase = 'odd';
            }

            var item  = '<tr id="fila_'+indice+'" class="'+valorClase+'">'+
                            '<td align="center">'+
                                '<input type="hidden" class="referencia_tabla" name="referencia_tabla['+indice+']" value="'+referencia+'">'+
                                '<input type="hidden" class="codigo_barras_tabla" name="codigo_barras_tabla['+indice+']" value="'+codigo_barras+'">'+boton+
                            '</td>'+
                            '<td align="left">'+referencia+'</td>'+
                            '<td align="left">'+codigo_barras+'</td>'+
                        '</tr>';

            $('#lista_items').append(item);
            $('#referencia').val('');
            $('#codigo_barras').val('');
            indice++;
            $('#indice').val(indice);
        } else {
            if(existe_referencia == 1){
                var error = $('#existe_referencia').val();

            } else if(referencia==''){
                var error = $('#digite_referencia').val();

            } else if(codigo ==''){
                var error = $('#digite_codigo').val();

            } else if(codigo_alfanumerico ==''){
                var error = $('#digite_codigo_alfanumerico').val();

            } else if(codigo_alfanumerico == referencia){
                var error = $('#referencia_diferente').val();
            }
            alert(error);
        }
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
   