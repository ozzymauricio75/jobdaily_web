    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    /*** Adicionar combinaciones de teclas para el manejo de botones***/
    $(document).bind('keydown', 'Ctrl+a', function(evt) {$('#ADICARTI').click(); return false;});
    $(document).bind('keydown', 'Ctrl+c', function(evt) {$('#CONSARTI').click(); return false;});
    $(document).bind('keydown', 'Ctrl+m', function(evt) {$('#MODIARTI').click(); return false;});
    $(document).bind('keydown', 'Ctrl+e', function(evt) {$('#ELIMARTI').click(); return false;});
    $(document).bind('keydown', 'Ctrl+x', function(evt) {$('#EXISARTI').click(); return false;});

    function cargarReferencia(){
        var valor  = $('#codigo').val();
        $('#referencia').val(valor);
    }
    

    /*** Cargar datos si el articulo existe ***/
    function cargarDatos() {
        var destino           = $('#URLFormulario').val();
        var referencia        = $('#codigo_alfanumerico').val();
        var codigo            = $('#codigo_maximo').val();

        /*** Descargar contenido  ***/
        $.getJSON(destino, {recargar: true, referencia_carga: referencia}, function(datos){
            if (datos != ""){
                if(datos[0] != ""){    
                    var codigo_marca           = datos[22];
                    var nombre_marca           = datos[23];
                    var tipo_articulo          = datos[2];
                    var nombre_tipo_articulo   = datos[24];
                    var codigo_impuesto_compra = datos[8];
                    var nombre_impuesto_compra = datos[25];
                    var codigo_impuesto_venta  = datos[9];
                    var nombre_impuesto_venta  = datos[26];
                    var codigo_unidad_compra   = datos[14];
                    var nombre_unidad_compra   = datos[27];
                    var padre                  = datos[29];
                    var grupo                  = datos[30];
               
                    $('#codigo').val(datos[0]);
                    $('#tipo_articulo').val(datos[2]);
                    $('#codigo_barras').val(datos[21]);
                    $('#descripcion').val(datos[1]);
                    $('#alto').val(datos[4]);
                    $('#ancho').val(datos[5]);
                    $('#profundidad').val(datos[6]);
                    $('#peso').val(datos[7]);
                    $('#formato_imprime').val(datos[18]);
                    $('#ficha_tecnica').val(datos[3]);
                    $('#referencia');
                    $('#codigo_barras_alterna');
                    $('#codigo_impuesto_compra').html('');
                    $('#codigo_impuesto_compra').append('<option value="'+codigo_impuesto_compra+'">' +nombre_impuesto_compra+ '</option>');
                    $('#codigo_impuesto_venta').html('');
                    $('#codigo_impuesto_venta').append('<option value="'+codigo_impuesto_venta+'">' +nombre_impuesto_venta+ '</option>');
                    $('#codigo_marca').html('');
                    $('#codigo_marca').append('<option value="'+codigo_marca+'">' +nombre_marca+ '</option>');
                    $('#codigo_unidad_compra').html('');
                    $('#codigo_unidad_compra').append('<option value="'+codigo_unidad_compra+'">' +nombre_unidad_compra+ '</option>');
                    $('#codigo_iso').val(datos[16]);

                }else{
                    $('#codigo').val('');
                    $('#codigo').val(codigo);
                    $('#codigo_alfanumerico').val('');
                    $('#descripcion').val('');
                    $('#codigo_barras').val('');
                    $('#selector1').val('');
                    $('#tipo_articulo').val('');
                    $('#alto').val('');
                    $('#ancho').val('');
                    $('#profundidad').val('');
                    $('#peso').val('');
                    $('#ficha_tecnica').val('');
                    $('#codigo_impuesto_compra').val('');
                    $('#codigo_marca').val('');
                    $('#codigo_unidad_compra').val('');
                    $('#codigo_iso').val('');
                    $('#referencia').val('');
                    $('#codigo_barras_alterna').val('');
                }
            }
        });
    } 

    /*** Cargar datos si el articulo existe ***/
    function cargarDatosArticulo() {
        var destino           = $('#URLFormulario').val();
        var referencia        = $('#selector2').val();

        /*** Descargar contenido  ***/
        $.getJSON(destino, {recargar: true, referencia_carga: referencia}, function(datos){
            if (datos != ""){
                if(datos[0] != ""){    
                    var codigo_marca           = datos[22];
                    var nombre_marca           = datos[23];
                    var tipo_articulo          = datos[2];
                    var nombre_tipo_articulo   = datos[24];
                    var codigo_impuesto_compra = datos[8];
                    var nombre_impuesto_compra = datos[25];
                    var codigo_impuesto_venta  = datos[9];
                    var nombre_impuesto_venta  = datos[26];
                    var codigo_unidad_compra   = datos[14];
                    var nombre_unidad_compra   = datos[27];
                    var padre                  = datos[29];
                    var grupo                  = datos[30];
               
                    $('#codigo').val(datos[0]).attr("disabled","disabled");
                    $('#selector2').val(referencia);
                    $('#tipo_articulo').val(datos[2]).attr("disabled","disabled");
                    $('#codigo_barras').val(datos[21]).attr("disabled","disabled");
                    $('#descripcion').val(datos[1]).attr("disabled","disabled");
                    $('#alto').val(datos[4]).attr("disabled","disabled");
                    $('#ancho').val(datos[5]).attr("disabled","disabled");
                    $('#profundidad').val(datos[6]).attr("disabled","disabled");
                    $('#peso').val(datos[7]).attr("disabled","disabled");
                    $('#formato_imprime').val(datos[18]).attr("disabled","disabled");
                    $('#ficha_tecnica').val(datos[3]).attr("disabled","disabled");
                    $('#referencia').attr("disabled","disabled");
                    $('#codigo_barras_alterna').attr("disabled","disabled");
                    $('#codigo_impuesto_compra').html('');
                    $('#codigo_impuesto_compra').append('<option value="'+codigo_impuesto_compra+'">' +nombre_impuesto_compra+ '</option>').attr("disabled","disabled");
                    $('#codigo_impuesto_venta').html('');
                    $('#codigo_impuesto_venta').append('<option value="'+codigo_impuesto_venta+'">' +nombre_impuesto_venta+ '</option>').attr("disabled","disabled");
                    $('#codigo_marca').html('');
                    $('#codigo_marca').append('<option value="'+codigo_marca+'">' +nombre_marca+ '</option>').attr("disabled","disabled");
                    $('#codigo_unidad_compra').html('');
                    $('#codigo_unidad_compra').append('<option value="'+codigo_unidad_compra+'">' +nombre_unidad_compra+ '</option>').attr("disabled","disabled");
                    $('#codigo_iso').val(datos[16]).attr("disabled","disabled");
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
            $('#codigo_barras_alterna').val('');
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
   