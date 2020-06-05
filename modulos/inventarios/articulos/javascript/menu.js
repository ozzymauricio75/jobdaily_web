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

    function removerItem(boton) {

        var destino      = $('#URLFormulario').val();
        var cantidad     = $(".referencia_tabla").length;
        var indice_tabla = $(boton).parents('tr:first').attr('id').split('_')[1];
        var principal    = $("input[name='principal["+indice_tabla+"]']").val();

        if(principal==1 && cantidad>0){
            $(".referencia_principal").removeAttr("disabled");
        }
        $(boton).parents('tr').remove();
        $('#referencia').focus();
    }
/*
    function seleccionar_todos_grupos(){
        
      var seleccionar_todos = true;
      var contador_casillas_seleccionadas = 0;
      var contador_total_casillas = 0;
      $('#PESTANA_GRUPOS').find('.grupos_electrodomesticos:checkbox').each(function (grupos) {
        var id = $(this).val();
        if($('#grupos_'+id).attr('checked')){
            contador_casillas_seleccionadas++;
        }
        contador_total_casillas++;
      });

      if(contador_total_casillas == contador_casillas_seleccionadas)
        seleccionar_todos=false;
        $(".grupos_electrodomesticos:checkbox").attr('checked', seleccionar_todos);
    }
    function seleccionar_todos_proveedores(){
      var seleccionar_todos = true;
      var contador_casillas_seleccionadas = 0;
      var contador_total_casillas = 0;
      $('#PESTANA_PROVEEDORES').find('.proveedores_electrodomesticos:checkbox').each(function (grupos) {

      var id = $(this).val();
        if($('#proveedores_'+id).attr('checked')){
            contador_casillas_seleccionadas++;
        }
        contador_total_casillas++;
      });

      if(contador_total_casillas == contador_casillas_seleccionadas)
            seleccionar_todos=false;

      $(".proveedores_electrodomesticos:checkbox").attr('checked', seleccionar_todos);
    }
    function seleccionar_todos_marcas(){
      var seleccionar_todos = true;
      var contador_casillas_seleccionadas = 0;
      var contador_total_casillas = 0;
      $('#PESTANA_MARCAS').find('.marcas_electrodomesticos:checkbox').each(function (grupos) {

        var id = $(this).val();
        if($('#marcas_'+id).attr('checked')){
            contador_casillas_seleccionadas++;
        }
        contador_total_casillas++;
      });

      if(contador_total_casillas == contador_casillas_seleccionadas)
         seleccionar_todos=false;
         $(".marcas_electrodomesticos:checkbox").attr('checked', seleccionar_todos);
    }
    function seleccionar_todas_sucursales(){
      var seleccionar_todos = true;
      var contador_casillas_seleccionadas = 0;
      var contador_total_casillas = 0;
      $('#PESTANA_SUCURSALES').find('.sucursales_electrodomesticos:checkbox').each(function (grupos) {

        var id = $(this).val();
        if($('#sucursales_'+id).attr('checked')){
            contador_casillas_seleccionadas++;
        }
        contador_total_casillas++;
      });

      if(contador_total_casillas == contador_casillas_seleccionadas)
        seleccionar_todos=false;
        $(".sucursales_electrodomesticos:checkbox").attr('checked', seleccionar_todos);
    }



    function funcion_ordenamiento_1(){
    
    var primera_seleccion = $('#item1').val();  
    if(primera_seleccion!=0){
        $('#orden1').parent().show();
        $('#orden1').removeClass("campoInactivo").removeAttr("disabled");

        $('#item2').parent().show();
        $('#item2').removeClass("campoInactivo").removeAttr("disabled");
        $('#orden2').parent().hide();
        $('#orden2').addClass("campoInactivo").attr("disabled","disabled");

        $('#item3').parent().hide();
        $('#item3').addClass("campoInactivo").attr("disabled","disabled");
        $('#orden3').parent().hide();
        $('#orden3').addClass("campoInactivo").attr("disabled","disabled");    
    }else{
        $('#orden1').parent().hide();
        $('#orden1').addClass("campoInactivo").attr("disabled","disabled");

        $('#item2').parent().hide();
        $('#item2').addClass("campoInactivo").attr("disabled","disabled");
        $('#orden2').parent().hide();
        $('#orden2').addClass("campoInactivo").attr("disabled","disabled");

        $('#item3').parent().hide();
        $('#item3').addClass("campoInactivo").attr("disabled","disabled");
        $('#orden3').parent().hide();
        $('#orden3').addClass("campoInactivo").attr("disabled","disabled");     
    }     


        var destino = $('#URLFormulario').val();    
        $.getJSON(destino, {recargarOrdenamiento: true, id_item1:primera_seleccion, valor:1}, function(elementos) {
        if (elementos) {
            $('#item2').html('');

            var ids = elementos[0];
            vector_ids = ids.split('-');
            
            var descripciones = elementos[1];
            vector_descripciones = descripciones.split('-');

            for(var i=0;i<vector_ids.length;i++){
            $('#item2').append('<option value="'+vector_ids[i]+'">' +vector_descripciones[i]+ '</option>');
            }
        }
        }); 

        $('#item2').val('0');
        $('#item3').val('0');
    }

    function funcion_ordenamiento_2(){
        var primera_seleccion = $('#item1').val();
        var segunda_seleccion = $('#item2').val();

        if(segunda_seleccion!=0){
            $('#orden2').parent().show();
            $('#orden2').removeClass("campoInactivo").removeAttr("disabled");

            $('#item3').parent().show();
            $('#item3').removeClass("campoInactivo").removeAttr("disabled");
            $('#orden3').parent().hide();
            $('#orden3').addClass("campoInactivo").attr("disabled","disabled");
        }else{
            $('#orden2').parent().hide();
            $('#orden2').addClass("campoInactivo").attr("disabled","disabled");

            $('#item3').parent().hide();
            $('#item3').addClass("campoInactivo").attr("disabled","disabled");
            $('#orden3').parent().hide();
            $('#orden3').addClass("campoInactivo").attr("disabled","disabled");
        }

        var destino = $('#URLFormulario').val();    
        $.getJSON(destino, {recargarOrdenamiento: true, id_item1:primera_seleccion, id_item2:segunda_seleccion, valor:2}, function(elementos) {
        if (elementos) {
            $('#item3').html('');

            var ids = elementos[0];
            vector_ids = ids.split('-');
            
            var descripciones = elementos[1];
            vector_descripciones = descripciones.split('-');

            for(var i=0;i<vector_ids.length;i++){
            $('#item3').append('<option value="'+vector_ids[i]+'">' +vector_descripciones[i]+ '</option>');
            }
        }
        }); 

        $('#item3').val('0');
    }

    function funcion_ordenamiento_3(){
        var primera_seleccion = $('#item1').val();
        var segunda_seleccion = $('#item2').val();
        var tercera_seleccion = $('#item3').val();

        if(tercera_seleccion!=0 && primera_seleccion!=0){
            $('#orden3').parent().show();
            $('#orden3').removeClass("campoInactivo").removeAttr("disabled");
        }else{
            $('#orden3').parent().hide();
            $('#orden3').addClass("campoInactivo").attr("disabled","disabled");
        }
    }
    
    /*function seleccionar_todos_grupos(){
        $(".grupos_electrodomesticos:checkbox").attr('checked', $('.grupos_electrodomesticos').is(':checked'));
    }   
    function seleccionar_todos_proveedores(){
        $(".proveedores_electrodomesticos:checkbox").attr('checked', $('.proveedores_electrodomesticos').is(':checked'));
    }   
    function seleccionar_todos_marcas(){
        $(".marcas_electrodomesticos:checkbox").attr('checked', $('.marcas_electrodomesticos').is(':checked'));
    }*/
