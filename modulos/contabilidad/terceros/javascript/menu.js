    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(){
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
        }
    }
