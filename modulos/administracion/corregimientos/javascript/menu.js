    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarListaMunicipios(){
        var destino      = $('#URLFormulario').val();
        var departamento = $('#departamento').val();
        var pais         = $('#pais').val();
        var lista        = '';

        $.getJSON(destino, {recargar: true, codigo_dane_departamento: departamento, elemento: 'municipio', codigo_iso : pais}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#municipio').html(lista);
            $('#municipio').val('');
        });
    }
