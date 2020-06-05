    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function persona_simplificada_cuentas() {

        if ($('#persona_simplificada').is(':checked')) {
            $('#selector2').parent().hide();
            $('#selector2').val('');
            $('#codigo_contable_iva').val('');
            $('#selector3').parent().hide();
            $('#selector3').val('');
            $('#codigo_contable_iva_debito').val('');
            $('#selector4').parent().hide();
            $('#selector4').val('');
            $('#codigo_contable_iva_credito').val('');
            $('#selector6').parent().hide();
            $('#selector6').val('');
            $('#codigo_contable_iva_uvt').val('');

        } else {
            $('#selector2').parent().show();
            $('#selector3').parent().show();
            $('#selector4').parent().show();
            $('#selector6').parent().show();
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
        if (selector=='selector3'){
            id_formulario    = "codigo_contable_iva_debito";
            verificar        = true;
        }
        if (selector=='selector4'){
            id_formulario    = "codigo_contable_iva_credito";
            verificar        = true;
        }
        if (selector=='selector6'){
            id_formulario    = "codigo_contable_iva_uvat";
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
