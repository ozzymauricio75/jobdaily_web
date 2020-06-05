    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function persona_simplificada_cuentas() {
        if ($('#persona_simplificada').is(':checked')) {
            $('#selector2').parent().hide();
            $('#selector2').val('');
            $('#codigo_contable_iva').val('');
        } else {
            $('#selector2').removeClass("campoInactivo").removeAttr("disabled");
            $('#selector2').parent().show();
            $('#selector2').val('');
            $('#codigo_contable_iva').val('');
        }
    }

    function recargarDatos(id_plan_contable,selector){
        var destino        = $('#URLFormulario').val();
        var id_formulario = "";
        var descripcion   = $("#"+selector).val();
        var verificar     = false;

        if (selector=='selector2'){
            id_formulario    = "codigo_contable_iva";
            verificar        = true;
        }

        if (id_plan_contable!="" && verificar){
            $.getJSON(destino,{verificarTasas:true, id_plan_contable:id_plan_contable, selector: selector, descripcion: descripcion},function(mensaje){

                if (mensaje[0]==true){
                    alert(mensaje[1]);
                    $("#"+selector).val("");
                    $("#"+selector).focus();
                    $("#"+id_formulario).val("");
                }
            });
        }
    }
