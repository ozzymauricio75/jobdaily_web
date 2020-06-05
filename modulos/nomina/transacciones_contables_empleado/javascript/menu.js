    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(articulo) {
    }

    function activarTipoRetencion(){
        var codigo_concepto_transaccion_contable = $("#codigo_concepto_transaccion_contable").val();
        if (codigo_concepto_transaccion_contable == 37){
            $("#tipo_retencion").parent().show();
            $("#tipo_retencion").removeClass("campoInactivo").removeAttr("disabled");
        } else {
            $("#tipo_retencion").parent().hide();
            $("#tipo_retencion").addClass("campoInactivo").attr("disabled","disabled");
        }
    }

    function validaContinuar(){
        $("#continuar").val(1);
    }
