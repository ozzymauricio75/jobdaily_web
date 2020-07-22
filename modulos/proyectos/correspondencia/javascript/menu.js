    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function cargarDocumentosCorrespondencia(){
        var destino                       = $('#URLFormulario').val();
        var codigo_proyecto               = $('#selector5').val();
        var documento_identidad_proveedor = $('#selector3').val();  

        $.getJSON(destino, {cargarDocumentosCorrespondencia: true, codigo_proyecto: codigo_proyecto, documento_identidad_proveedor:
            documento_identidad_proveedor}, function(json_string) {
                        var html;
            html = "<p>Se encontraron [" + json_string.length + "] registro(s)</p>";
            html += "<table border='1'>";
            html +=     "<thead>";
            html +=         "<tr>";
            html +=             "<th>Nombre</th>";
            html +=             "<th>Apellido 1º</th>";
            html +=             "<th>Apellido 2º</th>";
            html +=             "<th>F/Nacimiento</th>";
            html +=             "<th>Cuota</th>";
            html +=         "</tr>";
            html +=     "</thead>";
            for( var contador=0; contador < aDatos.length; contador++ )
            {
                html += "<tr>";
                html += "<td>" + json_string[contador].codigo    + "</td>";
                html += "<td>" + json_string[contador].fecha_recepcion + "</td>";
                html += "<td>" + json_string[contador].documento_identidad + "</td>";
                html += "<td>" + json_string[contador].valor_documento      + "</td>";
                html += "<td>" + json_string[contador].fecha_vencimiento     + "</td>";
                html += "</tr>";
            }
            html += "</table>";
            $("#resultado").html( html );
        

        });
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

    function cumplirItem(boton){
        var destino  = $('#URLFormulario').val();
        var id_tabla = $(boton).parents('tr').attr('id').split('_')[0];
        var aceptar  = 1;
        //$(boton).prevAll("input[name='estado_documento_tabla[]']").val('3');
        $(boton).removeAttr("onclick");
        $(boton).removeAttr("class");
        $(boton).html('Recibida');
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
                grabarEncabezado();
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

    