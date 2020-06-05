    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(id_plan_contable,selector){
        var destino        = $('#URLFormulario').val();
        var id_formulario = "";
        var descripcion   = $("#"+selector).val();
        var verificar     = false;

        if (selector=='selector2'){
            id_formulario    = "codigo_contable_retefuente";
            verificar        = true;
        }
        if (selector=='selector3'){
            id_formulario    = "codigo_contable_reteiva";
            verificar        = true;
        }
        if (selector=='selector6'){
            id_formulario    = "codigo_contable_iva_seguro";
            verificar        = true;
        }
        if (selector=='selector7'){
            id_formulario    = "codigo_contable_iva_flete";
            verificar        = true;
        }
        if (selector=='selector10'){
            id_formulario    = "codigo_contable_iva_nota_debito";
            verificar        = true;
        }
        if (selector=='selector12'){
            id_formulario    = "codigo_contable_retefuente_nota_debito";
            verificar        = true;
        }
        if (selector=='selector13'){
            id_formulario    = "codigo_contable_reteiva_nota_debito";
            verificar        = true;
        }
        if (selector=='selector14'){
            id_formulario    = "codigo_contable_reteica_nota_debito";
            verificar        = true;
        }
        if (selector=='selector16'){
            id_formulario    = "codigo_contable_iva_nota_credito";
            verificar        = true;
        }
        if (selector=='selector18'){
            id_formulario    = "codigo_contable_retefuente_nota_credito";
            verificar        = true;
        }
        if (selector=='selector19'){
            id_formulario    = "codigo_contable_reteiva_nota_credito";
            verificar        = true;
        }
        if (selector=='selector20'){
            id_formulario    = "codigo_contable_reteica_nota_credito";
            verificar        = true;
        }
        if (selector=='selector23'){
            id_formulario    = "codigo_contable_retefuente_provision";
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
