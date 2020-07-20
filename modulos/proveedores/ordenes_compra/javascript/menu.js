     $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    /*** Adicionar combinaciones de teclas para el manejo de botones***/
    $(document).bind('keydown', 'Ctrl+a', function(evt) {$('#ADICOCPR').click(); return false;});
    $(document).bind('keydown', 'Ctrl+c', function(evt) {$('#CONSOCPR').click(); return false;});
    $(document).bind('keydown', 'Ctrl+n', function(evt) {$('#ANULORCO').click(); return false;});
    $(document).bind('keydown', 'Ctrl+g', function(evt) {$('#botonAgregarArticulo').click(); return false;});
    //$(document).bind('keydown', 'Ctrl+m', function(evt) {$('#MODICARA').click(); return false;});
    //$(document).bind('keydown', 'Ctrl+e', function(evt) {$('#ELIMCARA').click(); return false;});

    function quitarMiles($cadena){
        $valor = array();
        for ($i = 0; $i < strlen($cadena); $i++) {
            if (substr($cadena, $i, 1) != ".") {
                $valor[$i] = substr($cadena, $i, 1);
            }
        }
        $valor = implode($valor);
        return $valor;
    }

    function numberFormat(numero){
        // Variable que contendra el resultado final
        var resultado = "";
        // Si el numero empieza por el valor "-" (numero negativo)
        if(numero[0]=="-"){
            // Cogemos el numero eliminando los posibles puntos que tenga, y sin el signo negativo
            nuevoNumero=numero.replace(/\./g,'').substring(1);

        }else{
            // Cogemos el numero eliminando los posibles puntos que tenga
            nuevoNumero = numero.replace(/\./g,'');
        }
        // Si tiene decimales, se los quitamos al numero
        if(numero.indexOf(",")>=0)
            nuevoNumero = nuevoNumero.substring(0,nuevoNumero.indexOf(","));
            // Ponemos un punto cada 3 caracteres
            for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++)
                resultado = nuevoNumero.charAt(i) + ((j > 0) && (j % 3 == 0)? ".": "") + resultado;
            // Si tiene decimales, se lo añadimos al numero una vez formateado con los separadores de miles
            if(numero.indexOf(",")>=0)
                resultado+=numero.substring(numero.indexOf(","));
                if(numero[0]=="-"){
                // Devolvemos el valor añadiendo al inicio el signo negativo
                return "-"+resultado;
            }else{
                return resultado;
            }
    }

    function lanzarOpciones(){
        recargarListaEmpresas();
        recargarComprador();
        recargarProyectos();
    }

    function mostrarTotales(){
        var destino = $('#URLFormulario').val();
        $("#contenedor_totales_orden").parent().show();
    }

    function cargaNit(){
        var destino = $('#URLFormulario').val();
        var empresa = $('#empresa').val();

        $.getJSON(destino, {cargarNit: true, codigo_empresa: empresa}, function(datos) {
            if(datos != ""){
               var nit = datos;
                $('#nit_empresa').val(nit);
                $('#nit_empresa').attr("disabled","disabled");
            } 
        });    
    }

    function cargarDatosVendedor(){
        var destino   = $('#URLFormulario').val();
        var vendedor  = $('#vendedor_proveedor').val();

        $.getJSON(destino, {cargarDatosVendedor: true, codigo_vendedor: vendedor}, function(datos) {
            if(datos != ""){
                $('#correo_electronico').val(datos[7]).attr("disabled","disabled");
                $('#celular').val(datos[6]).attr("disabled","disabled");
            }

        });   
    }
    
    function recargarListaEmpresas(){
        var destino                 = $('#URLFormulario').val();
        var codigo_empresa_proyecto = $('#empresa').val();
        var sucursal                = $('#sucursal').val();   
        var lista                   = '';

        $.getJSON(destino, {recargar: true, codigo: codigo_empresa_proyecto, elemento: 'sucursal'}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#sucursal').html(lista);
            $("#proyecto").removeAttr("disabled");
            $("#solicitante").removeAttr("disabled");
            recargarProyectos();
            //$('#sucursal').val('');
        });
    }

    function recargarComprador(){
        var destino        = $('#URLFormulario').val();
        var codigo_empresa = $('#empresa').val();  

        $.getJSON(destino, {recargarComprador: true, empresa: codigo_empresa}, function(elementos) {
          
            if (elementos) {
                var documento    = elementos[0];
                vector_documento = documento.split('-');
                var nombre       = elementos[1];
                vector_nombre    = nombre.split('-');
                $('#codigo_comprador').html('');

                for(var i=0; i<vector_documento.length; i++){
                    
                    $('#codigo_comprador').append('<option value="'+vector_documento[i]+'">' +vector_nombre[i]+ '</option>');
                }
            }
            $('#sucursal').removeAttr('disabled');
            $('#codigo_comprador').removeAttr('disabled');
        });
    }

    function recargarProyectos(){
        var destino         = $('#URLFormulario').val();
        var codigo_empresa = $('#empresa').val();  

        $.getJSON(destino, {recargarProyecto: true, empresa: codigo_empresa}, function(elementos) {
          
            if (elementos) {
                var codigo       = elementos[0];
                vector_codigo    = codigo.split('-');
                var nombre       = elementos[1];
                vector_nombre    = nombre.split('-');
                $('#proyecto').html('');

                for(var i=0; i<vector_documento.length; i++){
                    
                    $('#proyecto').append('<option value="'+vector_codigo[i]+'">' +vector_nombre[i]+ '</option>');
                }
            }
            $('#proyecto').removeAttr('disabled');
        });
    }

    function recargarVendedores(){
        var destino  = $('#URLFormulario').val();
        var nit      = parseInt($('#selector4').val());  

        $.getJSON(destino, {recargarVendedor: true, nit_proveedor: nit}, function(elementos) {
          
            if (elementos) {
                var id           = elementos[0];
                vector_id        = id.split('-');
                var nombre       = elementos[1];
                vector_nombre    = nombre.split('-');
                
                $('#vendedor_proveedor').html('');
                for(var i=0; i<vector_id.length; i++){ 
                    $('#vendedor_proveedor').append('<option value="'+vector_id[i]+'">' +vector_nombre[i]+ '</option>');
                }
                grabarEncabezado();
            }
            $('#vendedor_proveedor').removeAttr('disabled');
        });
    }

    function cargarProveedor(){
        var destino         = $('#URLFormulario').val();
        var nit_proveedor   = parseInt($('#selector4').val());
        var codigo_proyecto = parseInt($('#proyecto').val());

        $.getJSON(destino, {cargarProveedor: true, nit_proveedor: nit_proveedor, codigo_proyecto: codigo_proyecto}, function(datos) {
            
            var codigo_vendedor = datos[2];
            var nombre_vendedor = datos[3];
            
            if(datos[0] != ""){ 
                $("#razon_social_proveedor").val(datos[0]).attr("disabled","disabled");
                $("#digito_verificacion").parent().show().attr("disabled","disabled");
                $("#digito_verificacion").val(datos[1]).attr("disabled","disabled");
                $("#direccion").val(datos[4]).attr("disabled","disabled");
                //$("#correo_electronico").val(datos[5]).attr("disabled","disabled");
                //$("#celular").val(datos[6]).attr("disabled","disabled");
                $("#selector1").val(datos[7]).attr("disabled","disabled");
                $("#prefijo_orden").text(datos[8]);
                $("#numero_orden").val(datos[9]).attr("disabled","disabled");
                //Habilita campos pestaña pedidos
                $("#selector7").parent().show();
                //recargarVendedores();
            } else {
                $("#razon_social_proveedor").val('');
                $("#digito_verificacion").val('');
                $("#direccion").val('');
                $("#correo_electronico").val('');
                $("#celular").val('');
                $("#selector1").val('');
            }
        });
    }

    function grabarEncabezado(){
        var destino          = $('#URLFormulario').val();
        var nit_proveedor    = parseInt($('#selector4').val());
        var codigo_proyecto  = parseInt($('#proyecto').val());
        var sucursal         = $('#sucursal').val();
        var fecha_documento  = $('#fecha_documento').val();
        var numero_orden     = $('#numero_orden').val();
        var codigo_comprador = $('#codigo_comprador').val();
        var codigo_moneda    = $('#id_moneda').val();
        var dias_pago        = $('#dias_pago').val();
        var tipos_documento  = $('#tipos_documento').val();
        var solicitante      = $('#solicitante').val();
        var descuento        = $('#descuento').val();

        $.getJSON(destino, {grabarEncabezado: true, nit_proveedor: nit_proveedor, codigo_proyecto: codigo_proyecto,
            sucursal: sucursal, fecha_documento: fecha_documento, numero_orden: numero_orden, codigo_comprador: codigo_comprador,
            codigo_moneda: codigo_moneda, dias_pago: dias_pago, tipos_documento: tipos_documento, solicitante: solicitante,
            descuento: descuento
        }, function(datos) {
            
            //********************************Esto lo debo revisar**************************************
            /*var codigo_vendedor = datos[2];
            var nombre_vendedor = datos[3];
            
            if(datos[0] != ""){ 
                $("#razon_social_proveedor").val(datos[0]).attr("disabled","disabled");
                $("#digito_verificacion").parent().show().attr("disabled","disabled");
                $("#digito_verificacion").val(datos[1]).attr("disabled","disabled");
                $("#direccion").val(datos[4]).attr("disabled","disabled");
                $("#correo_electronico").val(datos[5]).attr("disabled","disabled");
                $("#celular").val(datos[6]).attr("disabled","disabled");
                $("#selector1").val(datos[7]).attr("disabled","disabled");
                $("#prefijo_orden").text(datos[8]);
                $("#numero_orden").val(datos[9]).attr("disabled","disabled");
            } else {
                $("#razon_social_proveedor").val('');
                $("#digito_verificacion").val('');
                $("#direccion").val('');
                $("#correo_electronico").val('');
                $("#celular").val('');
                $("#selector1").val('');
            }*/
        });
    }

    function calcularSubtotal() {
        var destino                 = $('#URLformulario').val();
        var cantidad_total_articulo = $('#cantidad_total_articulo').val();
        var costo_unitario          = $('#costo_unitario').val();
        var subtotal                = 0;
        costo_unitario              = costo_unitario.replace(/,/g, "");

        subtotal = costo_unitario * cantidad_total_articulo;
        subtotal = subtotal.toFixed(2);
        //subtotal = ponerMiles(subtotal);
        $('#subtotal').val(subtotal);
    }

    function calcularSubtotalCruce() {
        var destino                 = $('#URLformulario').val();
        var cantidad_total_articulo = $('#cantidad_total_articulo').val();
        var valor_unitario          = $('#valor_unitario').val();
        var porcentaje_iva          = $('#porcentaje_iva').val();
        var subtotal                = 0;
        costo_unitario              = costo_unitario.replace(/,/g, "");

        subtotal   = valor_unitario * cantidad_total_articulo;
        valor_iva  = (subtotal * porcentaje_iva)/100;
        subtotal   = subtotal.toFixed(2);

        $('#subtotal').val(subtotal);
        $('#total_iva_pedido').val(valor_iva);
    }

    function cargarDatosArticulo(){
        var destino           = $('#URLFormulario').val();
        var referencia        = $('#selector7').val();
        var nit_proveedor     = parseInt($('#selector4').val());
        var mensajes          = new Array();
        var contador_mensajes = 1;
        var mensaje_error     = "No existe el codigo del articulo, por favor verifique";

        /*** Descargar contenido  ***/
        $.getJSON(destino, {cargarDatosArticuloCreado: true, referencia_carga: referencia, nit_proveedor: nit_proveedor}, function(datos){

            if (datos != ""){
                if(datos[0] != ""){
                    var codigo_unidad_compra   = datos[14];
                    var nombre_unidad_compra   = datos[27];
                    var costo                  = datos[31];
                    var id_asociado            = datos[32];
                    var categoria              = datos[33];
                    var ancho                  = datos[34];
                    var alto                   = datos[35];
                    costo                      = ponerMiles(costo);
                    
                    $("#descripcion").parent().show();
                    $("#id_unidad_compra").parent().show();
                    $("#costo_unitario").parent().show();
                    $("#cantidad_total_articulo").parent().show();
                    $("#subtotal").parent().show();
                    $("#observaciones_articulo").parent().show();

                    $('#descripcion').val(datos[1]).attr("disabled","disabled");
                    $('#costo_unitario').val(costo);
                    $('#id_unidad_compra').append('<option value="'+codigo_unidad_compra+'">' +nombre_unidad_compra+ '</option>').attr("disabled","disabled");
                
                } else {   
                    alert(mensaje_error);
                    $('#descripcion').val(''); 
                    $('#id_unidad_compra').val(''); 
                    $('#selector7').val('').focus();
                    $('#costo').val('');          
                    /*alert(datos[1]);
                    $('#selector7').val('');
                    $('#descripcion').val('');
                    $('#costo_unitario').val('');
                    $('#id_unidad_compra').val('');  
                    $('#selector7').focus(''); */
                }
            } /*else {   
                alert(mensaje_error);
                $('#descripcion').val(''); 
                $('#id_unidad_compra').val(''); 
                $('#selector7').val('').focus(); 
                $('#costo').val('');         
            } */
        });
    }

    function agregarItemArticulo() {

        var destino                     = $('#URLFormulario').val();
        var continuar                   = true;
        var mensajes                    = new Array();
        var contador_mensajes           = 1;
        var celular                     = $("#celular").val();
        var correo_electronico          = $("#correo_electronico").val();
        var empresa_orden               = $("#empresa").val();
        var id_sucursal_orden           = $("#sucursal").val();
        var fecha_entrega_orden         = $("#fecha_entrega").val();
        var id_comprador                = $("#codigo_comprador").val();
        var referencia                  = $("#selector7").val();
        var descripcion                 = $("#descripcion").val();
        var unidad_compra               = $("#id_unidad_compra").text();
        var codigo_unidad_compra        = $("#id_unidad_compra").val();
        var costo_unitario              = $("#costo_unitario").val();
        var cantidad_total_articulo     = $("#cantidad_total_articulo").val();
        var subtotal                    = $("#subtotal").val();
        var foto                        = $("#foto_articulo").val();
        var observaciones_articulo      = $("#observaciones_articulo").val();
        var aplica_descuento            = $("#aplica_descuento").val();
        var descuento                   = $("#descuento").val();
        var numero_orden                = $("#numero_orden").val();
        var indice                      = $("#indice").val();
        var vendedor_proveedor          = $("#vendedor_proveedor").val();

        /////////// Pestaña datos generales ///////////////////
        // Empresa
        var empresa_orden = parseInt($("#empresa").val());
        if (empresa_orden == undefined || empresa_orden == ""){
            continuar     = false;
            mensajes[contador_mensajes] = $("#error_empresa").val();
            contador_mensajes++;
        }
        // Proveedor
        var nit_proveedor = parseInt($("#selector4").val());
        if (nit_proveedor == undefined || isNaN(nit_proveedor)|| nit_proveedor == 0){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_nit_proveedor").val();
            contador_mensajes++;
        }
        var razon_social_proveedor = $("#razon_social_proveedor").val();
        if (razon_social_proveedor == undefined || razon_social_proveedor == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_razon_social_proveedor").val();
            contador_mensajes++;
        }
        
        // Dias pago proveedor
        var dias_pago = parseInt($("#dias_pago").val());
        if (dias_pago == undefined || dias_pago == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_dias_pago").val();
            contador_mensajes++;
        }
        // Moneda
        var moneda    = parseInt($("#id_moneda").val());
        if (moneda    == undefined || moneda == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_moneda").val();
            contador_mensajes++;
        }
        //Vendedor proveedor
        var vendedor_proveedor  = parseInt($("#vendedor_proveedor").val());
        if (vendedor_proveedor == undefined || vendedor_proveedor == ""){
            continuar           = false;
            mensajes[contador_mensajes] = $("#error_vendedor_proveedor").val();
            contador_mensajes++;
        }
        // Solicitante
        var solicitante =$("#solicitante").val();
        if (solicitante == undefined || solicitante == ""){
            continuar           = false;
            mensajes[contador_mensajes] = $("#error_solicitante").val();
            contador_mensajes++;
        }

        // Correo electronico vendedor
        var correo_electronico =$("#correo_electronico").val();
        if (correo_electronico == undefined || correo_electronico == ""){
            continuar           = false;
            mensajes[contador_mensajes] = $("#error_correo_electronico").val();
            contador_mensajes++;
        }

        // Celular vendedor
        var celular =$("#celular").val();
        if (celular == undefined || celular == ""){
            continuar           = false;
            mensajes[contador_mensajes] = $("#error_celular_vendedor").val();
            contador_mensajes++;
        }
            
        /////////// Pestaña pedidos de articulos ///////////////////
        var crear_articulo = false;
        if ($(".crear_articulo").is(':checked')){
            crear_articulo = true;
        }
        // datos articulo existe //
        /*var articulo    = $("#selector7").val();
        if (!crear_articulo && (articulo == undefined || isNaN(articulo) || articulo == 0)){
            continuar   = false;
            mensajes[contador_mensajes] = $("#error_articulo").val();
            contador_mensajes++;
        }*/
            
        // datos pedido //
        var cantidad_total_articulo     = parseFloat($("#cantidad_total_articulo").val());
        if (cantidad_total_articulo     == undefined || isNaN(cantidad_total_articulo) || cantidad_total_articulo == 0){
            continuar                   = false;
            mensajes[contador_mensajes] = $("#error_cantidad_total").val();
            contador_mensajes++;
        }
        var cantidad_total_control      = parseFloat($("#cantidad_total_control").val());
        var id_unidad                   = $("#id_unidad_compra").val();
        var valor_unitario              = parseFloat($("#costo_unitario").val());

        if (valor_unitario              == undefined || isNaN(valor_unitario) || valor_unitario == 0){
            continuar                   = false;
            mensajes[contador_mensajes] = $("#error_valor_unitario").val();
            contador_mensajes++;
        }
        var aplica_descuento            = false;
        var porcentaje_aplica_descuento = 0;

        /*if ($(".descuento_linea").is(':checked')){
            aplica_descuento                = true;
            porcentaje_aplica_descuento     = parseFloat($("#descuento").val());
            if (porcentaje_aplica_descuento == undefined || isNaN(porcentaje_aplica_descuento) || porcentaje_aplica_descuento == 0){
                continuar                   = false;
                mensajes[contador_mensajes] = $("#error_porcentaje_descuento_linea").val();
                contador_mensajes++;
            }
        }*/
        var observaciones_articulo = $("#observaciones_articulo").val();

        if (continuar){
            $(destino).find(".referencia_tabla").each(function(){
                var referencia_tabla = $(this).val();
                if (referencia == referencia_tabla){
                    existe_referencia = 1;
                }
            });

            $('#indicadorEsperaFormulario').width(128);
            $('#indicadorEsperaFormulario').height(15);
            $('#indicadorEsperaFormulario').css("display","block");
            $('#formularioPrincipal').ajaxForm();
            $('#formularioPrincipal').ajaxSubmit({dataType: 'json',data: {insertar_movimiento: true, numero_orden: numero_orden, 
                referencia: referencia, descripcion: descripcion, cantidad_total_articulo, codigo_unidad_compra: codigo_unidad_compra, 
                costo_unitario: costo_unitario, subtotal: subtotal, observaciones_articulo: observaciones_articulo,
                id_sucursal_orden: id_sucursal_orden, nit_proveedor: nit_proveedor, fecha_entrega_orden: fecha_entrega_orden,
                vendedor_proveedor: vendedor_proveedor, descuento: descuento
            }, success: function(datos) {
                if (datos[0]){
                    var numero_orden = $("#numero_orden").val();
                    var indice       = datos[10];
                
                    $(".terminar_orden").parent().show();
                    $(".terminar_orden").removeAttr("disabled");

                    if (referencia) {                                    
                        //var botonModificar = $('#modificarArticulo').html();
                        var botonEliminar  = $('#removerArticulo').html();
                        var valorClase     = 'even';

                        if ($("#listaArticulos tr:last").hasClass("even")) {
                            valorClase = 'odd';
                        }
                        var item  = '<tr id="'+indice+'" class="'+valorClase+'">'+
                                /*'<td align="center">'+
                                    botonModificar+
                                '</td>'+*/
                                '<td align="center">'+
                                    botonEliminar+
                                '</td>'+
                                '<td align="left">'+datos[3]+'</td>'+
                                '<td align="left">'+datos[4]+'</td>'+
                                '<td align="right">'+datos[5]+'</td>'+
                                '<td align="left">'+datos[6]+'</td>'+
                                '<td align="right">'+datos[7]+'</td>'+
                                '<td align="right">'+datos[8]+'</td>'+
                                '<td align="right">'+datos[11]+'</td>'+
                                '<td align="right">'+datos[12]+'</td>'+
                                '<td align="left">'+datos[9]+'</td>'+
                            '</tr>';

                        $('#listaArticulos').append(item);
                        $('#referencia').val('');
                        $('#cantidad_total_articulo').val('');
                        indice++;
                        $('#indice').val(indice);
                    }
                } else {
                    alert(datos[1]);
                }    
                $('#indicadorEsperaFormulario').css("display","none");     
            }  
            
            });

        } else {
            var mensaje_pantalla = "";
            for(var i=1;i<contador_mensajes;i++){
                mensaje_pantalla = mensaje_pantalla +" "+ mensajes[i]+"\n";
            }
            alert(mensaje_pantalla);
        } 
        $('#selector7').val('').focus();
        $("#descripcion").val('');
        $("#id_unidad_compra").text('');
        $("#id_unidad_compra").val('');
        $("#costo_unitario").val('');
        $("#cantidad_total_articulo").val('');
        $("#subtotal").val('');
        $("#foto_articulo").val('');
        $("#observaciones_articulo").val('');
        //$("#aplica_descuento").val('');
        //$("#descuento").val('');
        return true;
    }

    function removerArticulo(boton){
        var destino  = $("#URLFormulario").val();
        var id_tabla = $(boton).parents('tr').attr('id').split('_')[0];
        
        if (id_tabla == 'fila'){
            var id_tabla = $(boton).parents('tr').attr('id').split('_')[1];
        }
        
        if (!isNaN(id_tabla))
            id_tabla = parseInt(id_tabla);

            $.getJSON(destino, {eliminarMovimiento: true, id_tabla: id_tabla}, function(datos){
                if (!datos[0]){
                    alert(datos[1]);
                } else {
                    $(boton).parents('tr').remove();
                }
            });
    }

    function modificarArticulo(boton){
        var destino      = $("#URLFormulario").val();
        var id_tabla     = $(boton).parents('tr').attr('id').split('_')[0];
        var numero_orden = $('#numero_orden').val();
        
        if (id_tabla == 'fila'){
            var id_tabla = $(boton).parents('tr').attr('id').split('_')[1];
        }
        
        if (!isNaN(id_tabla))
            id_tabla = parseInt(id_tabla);

            $.getJSON(destino, {actualiza_movimiento: true, id_tabla: id_tabla, numero_orden: numero_orden}, function(datos){
                if (!datos[0]){
                    alert(datos[1]);
                } else {
                    var codigo_unidad_compra = datos[3];
                    var nombre_unidad_compra = datos[4];

                    $("#selector7").val(datos[1]);
                    $("#descripcion").val(datos[2]);
                    $("#cantidad_total_articulo").val(parseInt(datos[6]));
                    $('#id_unidad_compra').append('<option value="'+codigo_unidad_compra+'">' +nombre_unidad_compra+ '</option>').attr("disabled","disabled");
                    $("#costo_unitario").val(parseInt(datos[5]));
                    $("#subtotal").val(parseInt(datos[6]));
                    $("#observaciones_articulo").val(datos[8]);
                    $("#indice_tabla").val(datos[0]);
                    $(".modificar_articulo_tabla").parent().show();
                    $(".modificar_articulo_tabla").removeAttr('disabled');
                    $(".modificar_articulo_tabla").parent().show();
                    $(".agregar_articulo").parent().hide();
                    //$(".modificarArticuloTabla").css("display","none");
                }
            });
    }

    function modificarArticuloTabla(){
        var destino                     = $('#URLFormulario').val();
        var continuar                   = true;
        var mensajes                    = new Array();
        var contador_mensajes           = 1;
        var referencia                  = $("#selector7").val();
        var descripcion                 = $("#descripcion").val();
        var unidad_compra               = $("#id_unidad_compra").text();
        var codigo_unidad_compra        = $("#id_unidad_compra").val();
        var costo_unitario              = $("#costo_unitario").val();
        var cantidad_total_articulo     = $("#cantidad_total_articulo").val();
        var subtotal                    = $("#subtotal").val();
        var observaciones_articulo      = $("#observaciones_articulo").val();
        var aplica_descuento            = $("#aplica_descuento").val();
        var descuento                   = $("#descuento").val();
        var numero_orden                = $("#numero_orden").val();
        var indice_tabla                = $("#indice_tabla").val();
        var nit_proveedor               = $("#selector4").val();

        $.getJSON(destino, {actualiza_movimiento_tabla: true, numero_orden: numero_orden, referencia: referencia, descripcion: descripcion,
                cantidad_total_articulo, codigo_unidad_compra: codigo_unidad_compra, costo_unitario: costo_unitario, subtotal: subtotal, 
                observaciones_articulo: observaciones_articulo, indice_tabla: indice_tabla, nit_proveedor: nit_proveedor
            }, function(datos){
            if (!datos[0]){
                    alert(datos[1]);
                } else {
                    $("#selector7").val('');
                    $("#descripcion").val('');
                    $("#cantidad_total_articulo").val('');
                    $('#id_unidad_compra').val('');
                    $('#id_unidad_compra').text('');
                    $("#costo_unitario").val('');
                    $("#subtotal").val('');
                    $("#observaciones_articulo").val('');
                    $(".agregar_articulo").parent().show();
                    $(".modificar_articulo_tabla").parent().hide();
                }
            });
    }

    function totalPedido(){
        var destino             = $('#URLFormulario').val();
        var numero_orden        = $("#numero_orden").val();
        var nit_proveedor       = $("#selector4").val();
        var iva_incluido        = "0";
        var sucursal            = $("#sucursal").val();
        var fecha_documento     = $("#fecha_documento").val();
        var descuento           = $("#descuento").val();
        var observaciones_orden = $("#observaciones_orden").val();

        if ($(".iva_incluido").is(':checked')){
            iva_incluido = "1";
        }
        descuento     = parseFloat($("#descuento").val());
        if (descuento == undefined || isNaN(descuento) || descuento==0){
            descuento = "0.0000";
        }
        
        $.getJSON(destino, {total_pedido: true, numero_orden: numero_orden, iva_incluido: iva_incluido, descuento: descuento, 
            nit_proveedor: nit_proveedor, observaciones_orden: observaciones_orden},
            function (datos){
            if(datos[1]){
                var total_unidades          = (datos[1]);
                var subtotal_pedido         = (datos[2]);
                var total_iva_pedido        = (datos[3]);
                var total_pedido            = (datos[4]);
                var nit_proveedor           = (datos[5]);
                var nombre_proveedor        = (datos[6]);
                var proyecto                = (datos[7]);
                var prefijo_codigo_proyecto = (datos[8]);
                var numero_orden            = (datos[9]);
                var total_items             = (datos[10]);
                var valor_descuento         = (datos[11]);

                //campos del resumen
                $("#prefijo_orden_total").text(prefijo_codigo_proyecto);
                $("#numero_orden_total").text(numero_orden);
                $("#nit_proveedor_total").text(nit_proveedor);
                $("#proveedor_total").text(nombre_proveedor);
                $("#proyecto_total").text(proyecto);
                $("#cantidad_items_total").val(total_items);  
                //campos ocultos de la pestaña totales
                $("#campo_prefijo_orden").val(prefijo_codigo_proyecto);
                $("#campo_numero_orden_total").val(numero_orden);
                $("#campo_nit_proveedor").val(nit_proveedor);
                $("#campo_sucursal").val(sucursal);
                $("#campo_fecha_documento").val(fecha_documento);
                //campos de totales
                $("#total_unidades").val(total_unidades);
                $("#subtotal_pedido").val(subtotal_pedido);
                $("#total_iva_pedido").val(total_iva_pedido);
                $("#total_pedido").val(total_pedido); 
                $("#descuento_pedido").val(valor_descuento);       
            }
        });
    }

    function reCalculaTotalPedido(){
        var destino      = $('#URLFormulario').val();
        var numero_orden = $("#numero_orden").val();
        var iva_incluido = "0";

        if ($(".iva_incluido").is(':checked')){
            iva_incluido = "1";
        }
        descuento     = parseFloat($("#descuento").val());
        if (descuento == undefined || isNaN(descuento) || descuento==0){
            descuento = "0.0000";
        }
        
        $.getJSON(destino, {reCalculaTotal_pedido: true, numero_orden: numero_orden, iva_incluido: iva_incluido, descuento: descuento},
            function (datos){
            if(datos[1]){
                var total_unidades   = (datos[1]);
                var subtotal_pedido  = (datos[2]);
                var total_iva_pedido = (datos[3]);
                var total_pedido     = (datos[4]);

                $("#total_unidades").val(total_unidades);
                $("#subtotal_pedido").val(subtotal_pedido);
                $("#total_iva_pedido").val(total_iva_pedido);
                $("#total_pedido").val(total_pedido);        
            }
        });
    }

    function activaCamposTotales(){
        $("#listaItems").parent().hide();
        $("#total_unidades").parent().show(); 
        $("#subtotal_pedido").parent().show();
        $("#descuento_pedido").parent().show(); 
        $("#total_iva_pedido").parent().show();
        $("#total_pedido").parent().show();   
    }

    function activaCamposArticulos(){
        $("#listaItems").parent().show();
        $("#total_unidades").parent().hide(); 
        $("#subtotal_pedido").parent().hide();
        $("#descuento_pedido").parent().hide(); 
        $("#total_iva_pedido").parent().hide();
        $("#total_pedido").parent().hide(); 
    }

    function insertaCantidades(nombre_campo,codigo_articulo,numero_consecutivo){

        var destino             = $('#URLFormulario').val();
        var codigo_orden_compra = $('#codigo_orden_compra').val();
        var unidades_cruce      = $('#unidades_cruce').val();
        var unidades_orden      = $('#unidades_orden').val();
        var numero_filas        = $('#numero_filas').val();
        var unidades_pendientes = $('#unidades_pendientes').val();
        var cantidad_registros  = $('#cantidad_registros').val();
        var numero_consecutivo  = numero_consecutivo;
        var codigo_articulo     = codigo_articulo; 
        var referencia_articulo = referencia_articulo; 
        var indice              = numero_filas - (numero_filas - nombre_campo);
        var unidades_digitadas  = $("input[name='cantidades["+indice+"]']").val();
        unidades_digitadas      = unidades_digitadas.replace(".",""); 


        $.getJSON(destino, {insertaCantidades: true, codigo_articulo: codigo_articulo, numero_consecutivo: numero_consecutivo,
             codigo_orden_compra: codigo_orden_compra, unidades_digitadas: unidades_digitadas, indice: indice},
            function (datos){
            if(datos[0] == ""){
                //unidades_digitadas = unidades_digitadas.replace(".","");
                alert("Error, verifique las unidades digitadas son mayores a las pendientes.");
                //nombre_campo       ="";
                //codigo_articulo    ="";
                //numero_consecutivo =""; 
                //referencia_articulo =""; 

                $("input[name='cantidades["+indice+"]']").focus();
                $("input[name='cantidades["+indice+"]']").val('');

                /*for (i=indice;i<=numero_filas;i++){
                    $("input[name='cantidades["+indice+"]']").focus();
                }*/
            }else{
                //alert('Valor aceptado.');
                indice = datos[1];
                //indice = numero_filas - (numero_filas+nombre_campo);
                $("input[name='cantidades["+indice+"]']").focus();
                //nombre_campo       ="";
                //codigo_articulo    ="";
                //numero_consecutivo =""; 
                //referencia_articulo =""; 
            }
        });
        //var unidades_pendientes = unidades_orden - unidades_cruce;

        /*if(unidades_pendientes>0){
            var unidades_digitadas  = $("input[name='cantidades["+indice+"]']").val(); 
            unidades_digitadas      = unidades_digitadas.replace(".","");

            if(unidades_digitadas>unidades_pendientes){
                unidades_digitadas = unidades_digitadas.replace(".","");
                alert('La cantidad digitada es mayor que las unidades pendientes.');
                
                $("input[name='cantidades["+indice+"]']").val('');

                for (i=indice;i<=numero_filas;i++){
                    $("input[name='cantidades["+indice+"]']").focus();
                }
            }/*else if(unidades_digitadas<=unidades_pendientes){                                              
                indice++;
                //$("input[name='cantidades["+indice+"]']").removeAttr('disabled').focus();
            }
        }*/
    }

    function enterToTab(myEvent) {
       if (myEvent.keyCode == 13) {
           myEvent.keyCode = 9;
       }
    }

    function activaCampos(tipo,descuento){
        if (tipo == 1){
            var clase1 = "aplica_descuento_global"+descuento;
            var clase2 = "global"+descuento;
        } else if(tipo==2){
            var clase1 = "descuento_financiero_fijo";
            var clase2 = "fijo";
        } else if(tipo==3){
            var clase1 = "descuento_financiero_pronto_pago";
            var clase2 = "pronto_pago";
        } else if (tipo==4){
            var clase1 = "descuento_linea";
            var clase2 = "linea";
        } else if (tipo==5){
            var clase1 = "maneja_criterio";
            var clase2 = "criterio";
        } else if (tipo==6){
            var clase1 = "maneja_caracteristicas";
            var clase2 = "caracteristica";
        } else if (tipo==7){
            var clase1 = "maneja_color";
            var clase2 = "colores";
        } else if (tipo==8){
            var clase1 = "maneja_criterio_articulo";
            var clase2 = "criterio";
        }
        if ($("."+clase1).is(':checked')){

            if (tipo== 6){
                var continuar = cargarDatosDesdeEstructura(2);
            }
            if ((tipo == 6 && continuar) || tipo !=6){
                if (tipo != 5){
                    $("."+clase2).parent().show();
                    $("."+clase2).removeAttr('disabled');
                    $("."+clase2).parent().show();
                } else {
                    var continuar = cargarDatosDesdeEstructura(1);
                }
            } else if((tipo ==5 || tipo ==6) && !continuar){
                $("."+clase1+":checkbox").removeAttr('checked');
                $("."+clase2).parent().hide();
                $("."+clase2).attr('disabled','disabled');
                $("."+clase2).val('');
            }
            if (descuento >= 1 && descuento <= 3){
                var descuento2 = parseInt(descuento) + 1;
                $(".aplica_descuento_global"+descuento2).parent().show();
                $(".aplica_descuento_global"+descuento2).removeAttr('disabled');
                $("#descuento_global"+descuento2).val('');
            }
        } else {
            $("."+clase2).parent().hide();
            $("."+clase2).attr('disabled','disabled');
            $("."+clase2).val('');
            if (tipo==5 || tipo==8){
                $('#id_subnivel_articulo').parent().hide();
                $('#id_subnivel_articulo').attr("disabled","disabled");
                $('#id_criterio_subnivel_articulo').parent().hide();
                $('#id_criterio_subnivel_articulo').attr("disabled","disabled");
                $('#id_criterio_subnivel_articulo').val("0");
                $('#id_concepto_criterio_subnivel_articulo').parent().hide();
                $('#id_concepto_criterio_subnivel_articulo').attr("disabled","disabled");
                $('#id_concepto_criterio_subnivel_articulo').val("0");
                $('#id_concepto_criterio_subnivel_articulo').html("");
            }
            if (descuento >= 1 && descuento <= 3){
                var descuento2 = parseInt(descuento) + 1;
                for (i=descuento2;i<=3;i++){
                    $(".global"+i).parent().hide();
                    $(".global"+i).attr('disabled','disabled');
                    $(".global"+i).val('');
                    $(".aplica_descuento_global"+i).parent().hide();
                    $(".aplica_descuento_global"+i).removeAttr('checked');
                    $(".aplica_descuento_global"+i).attr('disabled','disabled');
                }
            }
        }
    }

    function activaFechaFinal(){

        var fecha = $("#fecha_documento").val().split("-");
        ano = parseInt(fecha[0]);
        mes = parseInt(fecha[1])-1;
        dia = parseInt(fecha[2]);
        var fecha_minima = new Date(ano,mes,dia);
        var numero_entregas = $("#numero_entregas").val();
        if (numero_entregas != undefined && numero_entregas > 0){
            $(".fechas_entregas").parent().show();
            $(".fechas_entregas").removeAttr("disabled");
            $(".fecha_final_entregas").removeClass("selectorFechaBloquear");
            $(".fecha_final_entregas").removeClass("hasDatepicker");
            $(".fecha_final_entregas").val($("#fecha_documento").val());
            $(".fecha_final_entregas").next().remove();
            $('.fecha_final_entregas').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    buttonImage: 'imagenes/calendario.png',
                    buttonImageOnly: true,
                    minDate : fecha_minima
            })).attr('readonly', 'readonly');
        } else {
            $(".fechas_entregas").parent().hide();
            $(".fechas_entregas").attr("disabled","disabled");
            $(".fecha_final_entregas").val("");
        }

        $(".fecha_final_entrega_articulo").removeClass("selectorFechaBloquear");
        $(".fecha_final_entrega_articulo").removeClass("hasDatepicker");
        $(".fecha_final_entrega_articulo").val($("#fecha_documento").val());
        $(".fecha_final_entrega_articulo").next().remove();
        $('.fecha_final_entrega_articulo').datepicker($.extend({},
            $.datepicker.regional['es'], {
                showOn: 'both',
                dateFormat: 'yy-mm-dd',
                buttonImage: 'imagenes/calendario.png',
                buttonImageOnly: true,
                minDate : fecha_minima
        })).attr('readonly', 'readonly');
    }

    function formato_numero(valor){
        var valor_formato = '';
        for ( m=0; m < valor.length; m++) {
            if (valor.charAt(m) != ',') {
                valor_formato = valor_formato + valor.charAt(m);
            }
        }
        return valor_formato;
    };
     
    function ponerMiles(valor) {
        var valorMiles = '';
        valor          = valor.toString();
        cont           = 0;
        for ( m=valor.length; m>=0; m--) {
            if (cont != 3) {
                valorMiles = valor.charAt(m-1)+valorMiles;
                cont++;
            } else {
                cont = 0;
                if (m != 0) {
                    valorMiles = ','+valorMiles;
                    m++;
                }
            }
        }
        return valorMiles;
    };

    function formatoMiles(input){
        var num = input.value.replace(/\./g,'');
            if(!isNaN(num)){
                num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                num = num.split('').reverse().join('').replace(/^[\.]/,'');
                input.value = num;
            } else{ alert('Solo se permiten numeros');
                input.value = input.value.replace(/[^\d\.]*/g,'');
        }
    }   