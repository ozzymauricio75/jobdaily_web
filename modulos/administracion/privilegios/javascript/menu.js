    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

function validarSeleccion(nodo) {
    if ($(nodo).is(':checked')) {
        $(nodo).prevAll(':checkbox').attr('checked','checked');
        console.log('Marcando ...');
    } else {
        $(nodo).nextAll(':checkbox').removeAttr('checked','checked');
        console.log('Desmarcando ...');
    }
}