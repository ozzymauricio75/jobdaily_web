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
                $('#primer_nombre').val(datos[0]);
                $('#segundo_nombre').val(datos[1]);
                $('#primer_apellido').val(datos[2]);
                $('#segundo_apellido').val(datos[3]);
                $('#correo').val(datos[4]);
                $('#celular').val(datos[5]);
            } else{
                $('#primer_nombre').val('');
                $('#segundo_nombre').val('');
                $('#primer_apellido').val('');
                $('#segundo_apellido').val('');   
                $('#correo').val('');   
                $('#celular').val('');
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
            $('#nombre_comercial').parent().hide();
            $('#nombre_comercial').addClass("campoInactivo").attr("disabled","disabled");
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
            $('#nombre_comercial').parent().show();
            $('#nombre_comercial').removeClass("campoInactivo").removeAttr("disabled");
        }
    }
