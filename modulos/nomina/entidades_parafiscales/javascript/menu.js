    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function cargarDatos() {
        var documento_identidad = $('#documento_identidad').val();
        var destino = $('#URLFormulario').val();

        /*** Descargar contenido  ***/
        $.getJSON(destino, {recargar: true, documento_identidad_carga: documento_identidad}, function(datos){
            if (datos != ""){
                $('#codigo_tipo_documento').val(datos[0]);
                $('#id_municipio_documento').val(datos[1]);
                $('#razon_social').val(datos[2]);
                $('#nombre_comercial').val(datos[3]);
                $('#id_localidad_residencia').val(datos[4]);
                $('#direccion_principal').val(datos[5]);
                $('#telefono_principal').val(datos[6]);
                $('#celular').val(datos[7]);
                $('#fax').val(datos[8]);
                $('#correo').val(datos[9]);
                $('#sitio_web').val(datos[10]);
                $('#selector1').val(datos[11]);
                $('#selector2').val(datos[12]);
            } else{
                $('#codigo_tipo_documento').val('');
                $('#id_municipio_documento').val('');
                $('#razon_social').val('');
                $('#nombre_comercial').val('');
                $('#id_localidad_residencia').val('');
                $('#direccion_principal').val('');
                $('#telefono_principal').val('');
                $('#celular').val('');
                $('#fax').val('');
                $('#correo').val('');
                $('#sitio_web').val('');
                $('#selector1').val('');
                $('#selector2').val('');
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

    function recargarDatos(){
    }

    function seleccionarTodo(){

        var contador_casillas_seleccionadas = 0;
        var contador_total_casillas = 0;

        $('.parafiscales:checkbox').each(function () {
            var id = $(this).val();
            if ($(this).is(':checked')) {
                contador_casillas_seleccionadas++;
            }
            contador_total_casillas++;
        });

        if(contador_total_casillas == contador_casillas_seleccionadas){
            $(".parafiscales:checkbox").removeAttr('checked');
        } else {
            $(".parafiscales:checkbox").attr('checked','checked');
        }
    }
