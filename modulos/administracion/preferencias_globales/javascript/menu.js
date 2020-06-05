    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

function validarSeleccion(nodo) {
    if ($(nodo).is(':checked')) {
        $(nodo).prevAll(':checkbox').attr('checked','checked');
       
    } else {
        $(nodo).nextAll(':checkbox').removeAttr('checked','checked');
   
    }
}