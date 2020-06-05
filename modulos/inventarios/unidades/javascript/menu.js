    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    unidadPrincipal();
    });

    function unidadPrincipal() {

        var principal = $('.campo:checked').val();
        if(principal == 1) {
            $('#selector1').parent().hide();
            $('#factor_conversion').parent().hide();
            $('#selector1').val('');
            $('#factor_conversion').val(0);
            $('#codigo_unidad_principal').val(0);
        } else {
            $('#factor_conversion').val('');
            $('#selector1').val('');
            $('#codigo_unidad_principal').val('');
            $('#selector1').parent().show();
            $('#factor_conversion').parent().show();
        }
    }

    function recargarDatos(){
    }
