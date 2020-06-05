    $(document).ready(function() {
        function ejecutarFuncionesLocales() {
            var clase_cuenta = $('#clase_cuenta');
            activarPestana(clase_cuenta);
        }
        ejecutarFuncionesGlobales();
    });
    /*** Desactivar o activar todos los elementos del árbol si se selecciona el campo 'Grupo principal' ***/
    function bloquearArbolContable() {
        if ($('#bloqueArbol :radio').hasClass("campoInactivo")) {
            $('#bloqueArbol :radio').removeClass("campoInactivo").removeAttr("disabled");
        } else {
            $('#bloqueArbol :radio').addClass("campoInactivo").attr("disabled","disabled");
        }
    }
    /*** Funcion para desactivar una pestaña ***/
    function activarPestana(opcion){
        valor = $(opcion).val();
        if (valor == 2){
            var pestanas = $('#pestanas > ul').tabs("disable",2);
        }else{
            var pestanas = $('#pestanas > ul').tabs("enable",2);
        }
    }

    function activarPestanaModificar(){
        valor = $("#clase_cuenta").val();
        if (valor == 2){
            var pestanas = $('#pestanas > ul').tabs("disable",2);
        }else{
            var pestanas = $('#pestanas > ul').tabs("enable",2);
        }
    }
