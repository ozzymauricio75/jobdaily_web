    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function autocompletableEmpleado(item){
        var destino         = $('#URLFormulario').val();
        var codigo_sucursal = $('#codigo_sucursal').val();

        $(item).autocomplete(destino, {
            minChars: 3,
            width: 250,
            max: 100,
            delay: 100,
            matchContains: true,
            cacheLength: 100,
            extraParams: {
                verificar_empleado: true,
                codigo_sucursal: codigo_sucursal
            },
            formatResult: function(datos, valor) {return valor.split("|")[0];console.log(valor)}
        }).blur(function() {
            ///Aqui poner evento cuando pierda el foco
        });

        $(item).result(function(evento, datos, formateado) {
            if (datos) {
                $('#documento_identidad').val(datos[1]);

                ///Aqui llamar el metodo de calculo

            } else {
                $('#documento_identidad').val('');
            }

        });
    }

    function inicializarEmpleado(){
        $("#selector1").val("");
        $("#documento_identidad").val("");
    }

    function cargarPeriodo(origen, elemento){

        var destino = $('#URLFormulario').val();
        var valor   = $('#'+origen).val();
        var lista   = '';
        $('#'+elemento).empty();

        /*** Enviar datos para la recarga ***/
        $.getJSON(destino, {verificar_planillas: true, codigo_planilla: valor, elemento: elemento}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#'+elemento).html(lista);
        });
    }
