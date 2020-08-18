    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });
    
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

    function cumplirItem(boton){
        var destino  = $('#URLFormulario').val();
        var id_tabla = $(boton).parents('tr').attr('id').split('_')[0];
        var aceptar  = 1;
        //$(boton).prevAll("input[name='estado_documento_tabla[]']").val('3');
        $(boton).removeAttr("onclick");
        $(boton).removeAttr("class");
        $(boton).html('Aprobada');
        $(boton).prev('span').remove();
        $(boton).next('span').remove();

        if (id_tabla == 'fila'){
            var id_tabla = $(boton).parents('tr').attr('id').split('_')[1];
        }
        
        if (!isNaN(id_tabla))
            id_tabla = parseInt(id_tabla);
            $.getJSON(destino, {recibirDocumento: true, id_tabla: id_tabla}, function(datos){
                if (!datos[0]){
                    alert(datos[1]);
                } else {
                    $(boton).parents('tr').remove();
                }
                $('#indicador').val(aceptar);
            });
    }

    function validaTotalOrden(){
        var destino                       = $('#URLFormulario').val();
        var codigo_proyecto               = $('#selector5').val();
        var documento_identidad_proveedor = $('#selector3').val();
        var orden_compra                  = $('#orden_compra').val();
        var valor_documento               = $('#valor_documento').val();
        var error                         = "Error, no existe orden de compra o proveedor asociado al proyecto.";

        $.getJSON(destino, {validaTotalOrden: true, codigo_proyecto: codigo_proyecto, documento_identidad_proveedor: 
            documento_identidad_proveedor, orden_compra: orden_compra}, function(datos) {

            if (datos[0]==true) {
                var total_orden               = parseInt(datos[1]);
                var valor_documentos_cruzados = parseInt(datos[2]);
                var tolerancia                = parseInt(datos[3]);

                if(!valor_documentos_cruzados){
                    valor_documentos_cruzados = 0;
                }

                valor_documentos_cruzados = parseInt(valor_documentos_cruzados); 
                valor_documento           = valor_documento.replace(/\./g,"");
                valor_documento           = parseInt(valor_documento); 

                acumulado                 = (valor_documentos_cruzados + valor_documento);
                total_con_tolerancia      = (((total_orden * tolerancia)/100) + total_orden);
                total_con_tolerancia      = parseInt(total_con_tolerancia); 

                if(acumulado>total_con_tolerancia){
                    alert("Error, el valor excede el total de la orden de compra");
                    $('#valor_documento').val('');
                }
            }
        }); 

    }

    function valoresOrden(){
        var destino                       = $('#URLFormulario').val();
        var codigo_proyecto               = $('#selector5').val();
        var documento_identidad_proveedor = $('#selector3').val();
        var orden_compra                  = $('#orden_compra').val();
        var error                         = "Error, no existe orden de compra o proveedor asociado al proyecto.";

        $.getJSON(destino, {valoresOrden: true, codigo_proyecto: codigo_proyecto, documento_identidad_proveedor: 
            documento_identidad_proveedor, orden_compra: orden_compra}, function(datos) {

            if (datos[0]==true) {
                var total_orden               = datos[1];
                var valor_documentos_cruzados = datos[2];

                $('#total_orden').text('$'+total_orden);
                $('#documentos_cruzados').text(valor_documentos_cruzados);

            }else if(datos[0]==false){
                alert(error);
            }    
        }); 

    }

    function cargarOrdenes(){
        var destino                       = $('#URLFormulario').val();
        var codigo_proyecto               = $('#selector5').val();
        var documento_identidad_proveedor = $('#selector3').val();  
        var error                         = "Error, no existe orden de compra o proveedor asociado al proyecto.";

        $.getJSON(destino, {cargarOrdenes: true, codigo_proyecto: codigo_proyecto, documento_identidad_proveedor: 
            documento_identidad_proveedor}, function(elementos) {

            if (elementos[0]!="") {
                var id           = elementos[0];
                vector_id        = id.split('-');
                var nombre       = elementos[1];
                vector_nombre    = nombre.split('-');
                
                $('#orden_compra').html('');
                $('#tipo_documento').removeAttr("disabled","disabled");
                $('#documento_soporte').removeAttr("disabled","disabled");
                $('#valor_documento').removeAttr("disabled","disabled");
                $('#fecha_recepcion').removeAttr("disabled","disabled");
                $('#fecha_vencimiento').removeAttr("disabled","disabled");
                $('#observaciones').removeAttr("disabled","disabled");
                for(var i=0; i<vector_id.length; i++){ 
                    $('#orden_compra').append('<option value="'+vector_id[i]+'">' +vector_nombre[i]+ '</option>');
                }
                $('#orden_compra').removeAttr('disabled');
            }else if(elementos[0]==""){
                $('#orden_compra').attr('disabled');
                $('#tipo_documento').attr("disabled","disabled");
                $('#documento_soporte').attr("disabled","disabled");
                $('#valor_documento').attr("disabled","disabled");
                $('#fecha_recepcion').attr("disabled","disabled");
                $('#fecha_vencimiento').attr("disabled","disabled");
                $('#observaciones').attr("disabled","disabled");

            }      
        });
    }

    function cargarDocumentoSoporte(){
        var destino                       = $('#URLFormulario').val();
        var codigo_proyecto               = $('#selector5').val();
        var documento_identidad_proveedor = $('#selector3').val(); 
        var orden_compra                  = $('#orden_compra').val();  
        var error                         = "Error, no existe orden de compra o proveedor asociado al proyecto.";

        $.getJSON(destino, {cargarDocumentoSoporte: true, codigo_proyecto: codigo_proyecto, documento_identidad_proveedor: 
            documento_identidad_proveedor, orden_compra: orden_compra}, function(elementos) {

            if (elementos[0]!="") {
                var id           = elementos[0];
                vector_id        = id.split('-');
                var nombre       = elementos[1];
                vector_nombre    = nombre.split('-');
                
                $('#documento_soporte').html('');
                for(var i=0; i<vector_id.length; i++){ 
                    $('#documento_soporte').append('<option value="'+vector_id[i]+'">' +vector_nombre[i]+ '</option>');
                }
                $('#documento_soporte').removeAttr('disabled');
            }else if(elementos[0]==""){
                $('#valor_documento').attr("disabled","disabled");
                $('#fecha_recepcion').attr("disabled","disabled");
                $('#fecha_vencimiento').attr("disabled","disabled");
                $('#observaciones').attr("disabled","disabled");

            }      
        });
    }

    function recargarListaEmpresas(){
        var destino                 = $('#URLFormulario').val();
        var codigo_empresa_proyecto = $('#empresa').val();
        var sucursal                = $('#sucursal').val();   
        var lista                   = '';

        $.getJSON(destino, {recargar: true, codigo: codigo_empresa_proyecto, elemento: 'sucursal', codigo_empresa : sucursal}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#sucursal').html(lista);
            //$('#sucursal').val('');
        });
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

    function quitarMiles(cadena){
        $valor = array();
        for ($i = 0; $i < strlen($cadena); $i++) {
            if (substr($cadena, $i, 1) != ".") {
                $valor[$i] = substr($cadena, $i, 1);
            }
        }
        $valor = implode($valor);
        return $valor;
    }

    