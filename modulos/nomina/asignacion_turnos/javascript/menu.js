    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(){
    }

    function acLocalEmpleados(item) {
        var destino  = $('#URLFormulario').val();
        var sucursal = $('#codigo_sucursal').val();
        $(item).autocomplete(destino, {
            minChars: 3,
            width: 250,
            max: 100,
            delay: 100,

            matchContains: true,
            cacheLength:100,
            extraParams: {
                verificar: true,
                codigo_sucursal: sucursal
            },
            formatResult: function(datos, valor) {
                return valor.split("|")[0];
            }
        }).blur(function() {
            var x = $(this).attr('id');
            $(x).autocomplete().remove();
        }).result(function(evento, datos, formateado) {
           if (datos) {
                $('#documento_empleado').val(datos[1]);
            } else {
                $('#documento_empleado').val('');
            }
        });
    }
