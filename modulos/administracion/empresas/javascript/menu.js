    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });
    
        /*** Cargar datos si el tercero existe ***/
    function cargarDatos() {
        var documento_identidad = $('#documento_identidad').val();
        var destino = $('#URLFormulario').val();

        /*** Descargar contenido  ***/
        $.getJSON(destino, {recargar: true, documento_identidad_carga: documento_identidad}, function(datos){
            if (datos != ""){
                $('#id_tipo_documento').val(datos[0]);
                $('#codigo_municipio_documento').val(datos[1]);
                $('#tipo_persona').val(datos[2]);
                $('#primer_nombre').val(datos[3]);
                $('#segundo_nombre').val(datos[4]);
                $('#primer_apellido').val(datos[5]);
                $('#segundo_apellido').val(datos[6]);
                $('#razon_social').val(datos[7]);
                $('#nombre_comercial').val(datos[8]);
                $('#fecha_nacimiento').val(datos[9]);
                $('#id_municipio_residencia').val(datos[10]);
                $('#direccion_principal').val(datos[11]);
                $('#telefono_principal').val(datos[12]);
                $('#celular').val(datos[13]);
                $('#fax').val(datos[14]);
                $('#correo').val(datos[15]);
                $('#sitio_web').val(datos[16]);


                var tipo_persona = $('#tipo_persona').val(datos[2]);

               if (datos[2] == 1){
                    $('#persona_natural').attr("checked","true")
                    $('#primer_nombre').parent().show();
                    $('#primer_nombre').removeClass("campoInactivo").removeAttr("disabled");
        	    $('#segundo_nombre').parent().show();
                    $('#segundo_nombre').removeClass("campoInactivo").removeAttr("disabled");
                    $('#primer_apellido').parent().show();
                    $('#primer_apellido').removeClass("campoInactivo").removeAttr("disabled");
                    $('#segundo_apellido').parent().show();
                    $('#segundo_apellido').removeClass("campoInactivo").removeAttr("disabled");
                    $('#razon_social').parent().hide();
                    $('#razon_social').addClass("campoInactivo").attr("disabled","disabled");
                    $('#nombre_comercial').parent().hide();
                    $('#nombre_comercial').addClass("campoInactivo").attr("disabled","disabled");
                    $('#digito_verificacion').parent().hide();
                }else if (datos[2] == 4){
                    $('#natural_comerciante').attr("checked","true")
                    $('#primer_nombre').parent().show();
                    $('#primer_nombre').removeClass("campoInactivo").removeAttr("disabled");
                    $('#segundo_nombre').parent().show();
                    $('#segundo_nombre').removeClass("campoInactivo").removeAttr("disabled");
                    $('#primer_apellido').parent().show();
                    $('#primer_apellido').removeClass("campoInactivo").removeAttr("disabled");
                    $('#segundo_apellido').parent().show();
                    $('#segundo_apellido').removeClass("campoInactivo").removeAttr("disabled");
                    $('#razon_social').parent().hide();
                    $('#razon_social').addClass("campoInactivo").attr("disabled","disabled");
                    $('#nombre_comercial').parent().show();
                    $('#nombre_comercial').removeClass("campoInactivo").removeAttr("disabled");
                    $('#digito_verificacion').parent().show();
                    calcularDV();
                }else{
                    if (datos[2]== 2){
                        $('#persona_juridica').attr("checked","true");
                        $('#digito_verificacion').parent().show();
                        calcularDV();
                    }else{
                        $('#codigo_interno').attr("checked","true");
                        $('#digito_verificacion').parent().hide();
                    }
                    $('#primer_nombre').parent().hide();
                    $('#primer_nombre').addClass("campoInactivo").attr("disabled","disabled");
                    $('#segundo_nombre').parent().hide();
                    $('#segundo_nombre').addClass("campoInactivo").attr("disabled","disabled");
                    $('#primer_apellido').parent().hide();
                    $('#primer_apellido').addClass("campoInactivo").attr("disabled","disabled");
                    $('#segundo_apellido').parent().hide();
                    $('#segundo_apellido').addClass("campoInactivo").attr("disabled","disabled");
                    $('#razon_social').parent().show();
                    $('#razon_social').removeClass("campoInactivo").removeAttr("disabled");
                    $('#nombre_comercial').parent().show();
                    $('#nombre_comercial').removeClass("campoInactivo").removeAttr("disabled");
                    
                }

                var id_municipio_documento = $('#codigo_municipio_documento').val();
                $.getJSON(destino, {recargarMunicipioDocumento: true, municipio_documento: id_municipio_documento}, function(dato){
                    if(dato){
                        $('#selector1').val(dato);
                    }
                });

                var id_municipio_residencia = $('#id_municipio_residencia').val();
                $.getJSON(destino, {recargarMunicipioResidencia: true, municipio_residencia: id_municipio_residencia}, function(dato){
                    if(dato){
                        $('#selector2').val(dato);
                        $('#selector3').val(dato);
                        $('#id_localidad').val(datos[10]);
                        $('#direccion').val(datos[11]);
                        $('#telefono').val(datos[12]);
                        $('#celular_tipo_direccion').val(datos[13]);
                    }
                });

            } else{
                $('#id_tipo_documento').val('');
                $('#id_municipio_documento').val('');
                $('#tipo_persona').val('');
                $('#primer_nombre').val('');
                $('#segundo_nombre').val('');
                $('#primer_apellido').val('');
                $('#segundo_apellido').val('');
                $('#razon_social').val('');
                $('#nombre_comercial').val('');
                $('#fecha_nacimiento').val('');
                $('#id_municipio_residencia').val('');
                $('#direccion_principal').val('');
                $('#telefono_principal').val('');
                $('#celular').val('');
                $('#fax').val('');
                $('#correo').val('');
                $('#sitio_web').val('');
            }
        });
    }

    /*** Desactivar o activar algunos campos dependiendo del tipo de persona ***/
    function activarNombres(valor){
	   if (valor == 1){
            $('#primer_nombre').parent().show();
            $('#primer_nombre').removeClass("campoInactivo").removeAttr("disabled");
	        $('#segundo_nombre').parent().show();
            $('#segundo_nombre').removeClass("campoInactivo").removeAttr("disabled");
            $('#primer_apellido').parent().show();
            $('#primer_apellido').removeClass("campoInactivo").removeAttr("disabled");
            $('#segundo_apellido').parent().show();
            $('#segundo_apellido').removeClass("campoInactivo").removeAttr("disabled");
            $('#razon_social').parent().hide();
            $('#razon_social').addClass("campoInactivo").attr("disabled","disabled");
            $('#nombre_comercial').parent().hide();
            $('#nombre_comercial').addClass("campoInactivo").attr("disabled","disabled");
            $('#digito_verificacion').parent().hide();
        }else if(valor == 4){
            $('#primer_nombre').parent().show();
            $('#primer_nombre').removeClass("campoInactivo").removeAttr("disabled");
	        $('#segundo_nombre').parent().show();
            $('#segundo_nombre').removeClass("campoInactivo").removeAttr("disabled");
            $('#primer_apellido').parent().show();
            $('#primer_apellido').removeClass("campoInactivo").removeAttr("disabled");
            $('#segundo_apellido').parent().show();
            $('#segundo_apellido').removeClass("campoInactivo").removeAttr("disabled");
            $('#razon_social').parent().hide();
            $('#razon_social').addClass("campoInactivo").attr("disabled","disabled");
            $('#nombre_comercial').parent().show();
            $('#nombre_comercial').removeClass("campoInactivo").removeAttr("disabled");
            $('#digito_verificacion').parent().show();
            calcularDV();
        }else {
            $('#primer_nombre').parent().hide();
            $('#primer_nombre').addClass("campoInactivo").attr("disabled","disabled");
            $('#segundo_nombre').parent().hide();
            $('#segundo_nombre').addClass("campoInactivo").attr("disabled","disabled");
            $('#primer_apellido').parent().hide();
            $('#primer_apellido').addClass("campoInactivo").attr("disabled","disabled");
            $('#segundo_apellido').parent().hide();
            $('#segundo_apellido').addClass("campoInactivo").attr("disabled","disabled");
            $('#razon_social').parent().show();
            $('#razon_social').removeClass("campoInactivo").removeAttr("disabled");
            $('#nombre_comercial').parent().show();
            $('#nombre_comercial').removeClass("campoInactivo").removeAttr("disabled");

            if (valor == 2) {
                $('#digito_verificacion').parent().show();
                calcularDV();
            } else {
                $('#digito_verificacion').parent().hide();
            }
        }
    }
    
    function calcularDV() {

        var nit    = $('#documento_identidad').val();
        var pesos    = new Array(3,7,13,17,19,23,29,37,41,43,47,53,59,67,71);

        var suma    = 0;
        var j        = 0;
        var resto    = 0;
        var digitoV    = 0;

        for ( i = nit.length; i >= 0; i-- ) {
            suma += nit.charAt(i-1) * pesos[j];
            j++;
        }

        resto = suma % 11;
        if ( resto == 0 || resto == 1 ) {
            digitoV = resto;
        } else {
            digitoV = 11 - resto;
        }
        $('#digito_verificacion').val(digitoV);
    }
    
    function resolucionRetefuente(){
        if($('#autoretenedor').attr('checked')){
            $('#selector6').parent().show(); 
            $('#selector6').removeAttr('disabled','disabled');
        }else{
            $('#selector6').parent().hide();
            $('#selector6').addClass('campoInactivo').attr('disabled');
            $('#selector6').val('');
            $('#resolucion_retefuente').val('');
        }
    }
    
    /*function resolucionRetieneICA(){
        if($('#autoretenedor_ica').attr('checked')){
            $('#selector4').parent().show();
            $('#selector4').removeAttr('disabled','disabled');
        }else{
            $('#selector4').parent().hide();
            $('#selector4').addClass('campoInactivo').attr('disabled');
            $('#selector4').val('');
            $('#resolucion_retiene_ica').val('');
        }
    }*/
   
    function resolucionContribuyente(){
        if($('#gran_contribuyente').attr('checked')){
            $('#selector5').parent().show();
            $('#selector5').removeAttr('disabled','disabled');
        }else{
            $('#selector5').parent().hide();
            $('#selector5').addClass('campoInactivo').attr('disabled');
            $('#selector5').val('');
            $('#resolucion_gran_contribuyente').val('');
        }
    }

    function copiarRazonSocial(){
        if ($('#persona_juridica').is(':checked') || $('#codigo_interno').is(':checked')) {
            $('#razon_social_empresa').val($('#razon_social').val());
        }
    }
    
    function recargarActividades(){

        var destino = $('#URLFormulario').val();
        var id_municipio_residencia = $('#id_municipio_residencia').val();

        if (id_municipio_residencia !=''){
            var lista   = '';
            $.getJSON(destino, {recargarActividad: true, id_municipio_residencia: id_municipio_residencia}, function(datos){
                jQuery.each(datos, function(valor, texto) {
                    lista = lista+'<option value="'+valor+'">'+texto+'</option>';
                });
                $('#id_actividad_principal').html(lista);
                $('#id_actividad_secundaria').html(lista);
            });            
        }
    }

    function recargarDatos(id,valor){

        if (valor=='selector2'){
            var id_municipio_residencia          = $('#id_municipio_residencia').val();
            var id_municipio_residencia_anterior = $('#id_municipio_residencia_anterior').val();
            
            if (typeof(id_municipio_residencia_anterior)!='undefined'){

                var vector_municipio = id_municipio_residencia.split(",");
                var id_municipio     = vector_municipio[0]+"|"+vector_municipio[1]+"|"+vector_municipio[2];

                var vector_municipio_anterior = id_municipio_residencia_anterior.split(",");
                var id_municipio_anterior     = vector_municipio_anterior[0]+"|"+vector_municipio_anterior[1]+"|"+vector_municipio_anterior[2];
            
                if(id_municipio != id_municipio_anterior){

                    var destino = $('#URLFormulario').val();

                    if (id_municipio_residencia !=''){
                        var lista   = '';
                        $.getJSON(destino, {recargarActividad: true, id_municipio_residencia: id_municipio_residencia}, function(datos){
                            jQuery.each(datos, function(valor, texto) {
                                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
                            });
                            $('#id_actividad_principal').html(lista);
                            $('#id_actividad_secundaria').html(lista);
                        });            
                    }
                }
            }
        }
    }
