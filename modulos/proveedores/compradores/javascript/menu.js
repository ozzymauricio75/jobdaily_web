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
                $('#id_municipio_documento').val(datos[1]);
                $('#tipo_persona').val(datos[2]);
                $('#primer_nombre').val(datos[3]);
                $('#segundo_nombre').val(datos[4]);
                $('#primer_apellido').val(datos[5]);
                $('#segundo_apellido').val(datos[6]);
                $('#razon_social').val(datos[7]);
                $('#nombre_comercial').val(datos[8]);
                $('#fecha_nacimiento').val(datos[9]);
                $('#fecha_ingreso').val(datos[10]);
                $('#id_municipio_residencia').val(datos[11]);
                $('#direccion_principal').val(datos[12]);
                $('#telefono_principal').val(datos[13]);
                $('#celular').val(datos[14]);
                $('#fax').val(datos[15]);
                $('#correo').val(datos[16]);
                $('#sitio_web').val(datos[17]);
                $('#genero').val(datos[18]);
                $('#activo').val(datos[19]);

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
                    $('#fecha_nacimiento').parent().show();
                    $('#fecha_nacimiento').removeClass("campoInactivo").removeAttr("disabled");
                    $('#fecha_ingreso').parent().show();
                    $('#fecha_ingreso').removeClass("campoInactivo").removeAttr("disabled");
                    $('#genero').parent().show();
                    $('#genero').removeClass("campoInactivo").removeAttr("disabled");
                    $('#razon_social').parent().hide();
                    $('#razon_social').addClass("campoInactivo").attr("disabled","disabled");
                }else{
                    if (datos[2]== 2){
                        $('#persona_juridica').attr("checked","true")
                    }else{
                        $('#codigo_interno').attr("checked","true")
                    }
                    $('#primer_nombre').parent().hide();
                    $('#primer_nombre').addClass("campoInactivo").attr("disabled","disabled");
                    $('#segundo_nombre').parent().hide();
                    $('#segundo_nombre').addClass("campoInactivo").attr("disabled","disabled");
                    $('#primer_apellido').parent().hide();
                    $('#primer_apellido').addClass("campoInactivo").attr("disabled","disabled");
                    $('#segundo_apellido').parent().hide();
                    $('#segundo_apellido').addClass("campoInactivo").attr("disabled","disabled");
                    $('#genero').parent().hide();
                    $('#genero').addClass("campoInactivo").attr("disabled","disabled");
                    $('#razon_social').parent().show();
                    $('#razon_social').removeClass("campoInactivo").removeAttr("disabled");
                    $('#fecha_nacimiento').parent().show();
                    $('#fecha_nacimiento').removeClass("campoInactivo").removeAttr("disabled");
                    $('#fecha_ingreso').parent().show();
                    $('#fecha_ingreso').removeClass("campoInactivo").removeAttr("disabled");

                }

                var id_municipio_documento = $('#id_municipio_documento').val();
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
                $('#fecha_ingreso').val('');
                $('#id_municipio_residencia').val('');
                $('#direccion_principal').val('');
                $('#telefono_principal').val('');
                $('#celular').val('');
                $('#fax').val('');
                $('#correo').val('');
                $('#sitio_web').val('');
                $('#genero').val('');
                $('#activo').val('');
                
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
            $('#fecha_nacimiento').parent().show();
            $('#fecha_nacimiento').removeClass("campoInactivo").removeAttr("disabled");
            $('#fecha_ingreso').parent().show();
            $('#fecha_ingreso').removeClass("campoInactivo").removeAttr("disabled");
            $('#genero').parent().show();
            $('#genero').removeClass("campoInactivo").removeAttr("disabled");
            $('#activo').parent().show();
            $('#activo').removeClass("campoInactivo").removeAttr("disabled");
            $('#razon_social').parent().hide();
            $('#razon_social').addClass("campoInactivo").attr("disabled","disabled");
        } else {
            $('#primer_nombre').parent().hide();
            $('#primer_nombre').addClass("campoInactivo").attr("disabled","disabled");
            $('#segundo_nombre').parent().hide();
            $('#segundo_nombre').addClass("campoInactivo").attr("disabled","disabled");
            $('#primer_apellido').parent().hide();
            $('#primer_apellido').addClass("campoInactivo").attr("disabled","disabled");
            $('#segundo_apellido').parent().hide();
            $('#segundo_apellido').addClass("campoInactivo").attr("disabled","disabled");
            $('#genero').parent().hide();
            $('#genero').addClass("campoInactivo").attr("disabled","disabled");
            $('#razon_social').parent().show();
            $('#razon_social').removeClass("campoInactivo").removeAttr("disabled");
        }
    }
