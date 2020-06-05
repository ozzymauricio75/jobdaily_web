<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily:: Sofware empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar("seleccion_plan_contable_transacciones", $url_q);
    exit;
}

if (isset($url_verificarTasas) && isset($url_id_plan_contable)){
    $id_tasa1 = SQL::obtenerValor("plan_contable","codigo_tasa_aplicar_1","codigo_contable = '".$url_id_plan_contable."'");
    if (isset($url_selector)){
        if ($url_selector == "selector3" || $url_selector == "selector13" || $url_selector == "selector19"){
            $id_tasa2 = SQL::obtenerValor("plan_contable","codigo_tasa_aplicar_2","codigo_contable = '".$url_id_plan_contable."'");
        }
    }
    if ($id_tasa1==0){
        $mensaje[0] = true;
        $mensaje[1] = $textos["ASIGNAR_TASA"]." ".$url_descripcion;
    } else {
        if (isset($id_tasa2) && $id_tasa2==0){
            $mensaje[0] = true;
            $mensaje[1] = $textos["ASIGNAR_TASA"]." ".$url_descripcion;
        } else {
            $mensaje[0] = false;
        }
    }
    HTTP::enviarJSON($mensaje);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $tipos_documentos = SQL::seleccionar(array("tipos_documentos"),array("codigo"),"codigo > 0");
    if (SQL::filasDevueltas($tipos_documentos)){

        $vector_conceptos = array(
            "1" => $textos["COMPRAS_DIRECTAS"],
            "2" => $textos["COMPRAS_OBSEQUIO"],
            "3" => $textos["COMPRAS_FILIALES"],
            "4" => $textos["COMPRAS_CANJE"],
            "5" => $textos["COMPRAS_CONSIGNACION"]
        );

        $consecutivo = (int)SQL::obtenerValor("tipos_compra","MAX(codigo)","");
        if(!$consecutivo){
            $consecutivo = 1;
        }else{
            $consecutivo++;
        }

        // Definicion de pestanas
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 4, $consecutivo, array("title" => $textos["AYUDA_CODIGO"], "onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event);"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 40, 150, "", array("title" => $textos["AYUDA_DESCRIPCION"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("concepto_compra", $textos["CONCEPTO"],$vector_conceptos,1,array("title" => $textos["AYUDA_CONCEPTO"]))
            )
        );

        $formularios["PESTANA_CODIGOS_CONTABLES"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["CUENTAS_PAGAR"], 50, 255, "", array("title" => $textos["AYUDA_CUENTAS_PAGAR"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_cuentas_pagar)"))
                .HTML::campoOculto("codigo_contable_cuentas_pagar", ""),
            ),
            array(
                HTML::campoTextoCorto("*selector2", $textos["RETEFUENTE"], 50, 255, "", array("title" => $textos["AYUDA_RETEFUENTE"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_retefuente)"))
                .HTML::campoOculto("codigo_contable_retefuente", ""),
            ),
            array(
                HTML::campoTextoCorto("*selector3", $textos["RETEIVA"], 50, 255, "", array("title" => $textos["AYUDA_RETEIVA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_reteiva)"))
                .HTML::campoOculto("codigo_contable_reteiva", ""),
            ),
            array(
                HTML::campoTextoCorto("*selector4", $textos["SEGURO"], 50, 255, "", array("title" => $textos["AYUDA_SEGURO"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_seguro)"))
                .HTML::campoOculto("codigo_contable_seguro", ""),

                HTML::campoTextoCorto("*selector5", $textos["IVA_SEGURO"], 50, 255, "", array("title" => $textos["AYUDA_IVA_SEGURO"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_iva_seguro)"))
                .HTML::campoOculto("codigo_contable_iva_seguro", ""),
            ),
            array(
                HTML::campoTextoCorto("*selector6", $textos["FLETES"], 50, 255, "", array("title" => $textos["AYUDA_FLETES"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_flete)"))
                .HTML::campoOculto("codigo_contable_flete", ""),

                HTML::campoTextoCorto("*selector7", $textos["IVA_FLETES"], 50, 255, "", array("title" => $textos["AYUDA_IVA_FLETES"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_iva_flete)"))
                .HTML::campoOculto("codigo_contable_iva_flete", ""),

                HTML::campoTextoCorto("*selector8", $textos["IVA_DIFERENCIA"], 50, 255, "", array("title" => $textos["AYUDA_IVA_DIFERENCIA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_iva_diferencia)"))
                .HTML::campoOculto("codigo_contable_iva_diferencia", ""),
            )
        );

        $formularios["PESTANA_NOTAS_DEBITO"] = array(
            array(
                HTML::listaSeleccionSimple("tipo_documento_nota_debito", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion"),"",array("title" => $textos["AYUDA_TIPO_DOCUMENTO"])),
                HTML::campoTextoCorto("*valor_base_nota_debito", $textos["VALOR_BASE"], 8, 8, "", array("title" => $textos["AYUDA_VALOR_BASE"], "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("selector9", $textos["CUENTA_COMPRA"], 50, 255, "", array("title" => $textos["AYUDA_CUENTA_COMPRA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_compra_nota_debito)"))
                .HTML::campoOculto("codigo_contable_compra_nota_debito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector10", $textos["IVA"], 50, 255, "", array("title" => $textos["AYUDA_IVA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_iva_nota_debito)"))
                .HTML::campoOculto("codigo_contable_iva_nota_debito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector11", $textos["CUENTAS_PAGAR"], 50, 255, "", array("title" => $textos["AYUDA_CUENTAS_PAGAR"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_cuentas_pagar_nota_debito)"))
                .HTML::campoOculto("codigo_contable_cuentas_pagar_nota_debito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector12", $textos["RETEFUENTE"], 50, 255, "", array("title" => $textos["AYUDA_RETEFUENTE"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_retefuente_nota_debito)"))
                .HTML::campoOculto("codigo_contable_retefuente_nota_debito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector13", $textos["RETEIVA"], 50, 255, "", array("title" => $textos["AYUDA_RETEIVA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_reteiva_nota_debito)"))
                .HTML::campoOculto("codigo_contable_reteiva_nota_debito", ""),

                HTML::campoTextoCorto("selector14", $textos["RETEICA"], 50, 255, "", array("title" => $textos["AYUDA_IVA_SEGURO"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_reteica_nota_debito)"))
                .HTML::campoOculto("codigo_contable_reteica_nota_debito", ""),
            )
        );

        $formularios["PESTANA_NOTAS_CREDITO"] = array(
            array(
                HTML::listaSeleccionSimple("tipo_documento_nota_credito", $textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion"),"",array("title" => $textos["AYUDA_TIPO_DOCUMENTO"])),
                HTML::campoTextoCorto("*valor_base_nota_credito", $textos["VALOR_BASE"], 8, 8, "", array("title" => $textos["AYUDA_VALOR_BASE"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("selector15", $textos["CUENTA_COMPRA"], 50, 255, "", array("title" => $textos["AYUDA_CUENTA_COMPRA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_compra_nota_credito)"))
                .HTML::campoOculto("codigo_contable_compra_nota_credito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector16", $textos["IVA"], 50, 255, "", array("title" => $textos["AYUDA_IVA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_iva_nota_credito)"))
                .HTML::campoOculto("codigo_contable_iva_nota_credito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector17", $textos["CUENTAS_PAGAR"], 50, 255, "", array("title" => $textos["AYUDA_CUENTAS_PAGAR"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_cuentas_pagar_nota_credito)"))
                .HTML::campoOculto("codigo_contable_cuentas_pagar_nota_credito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector18", $textos["RETEFUENTE"], 50, 255, "", array("title" => $textos["AYUDA_RETEFUENTE"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_retefuente_nota_credito)"))
                .HTML::campoOculto("codigo_contable_retefuente_nota_credito", ""),
            ),
            array(
                HTML::campoTextoCorto("selector19", $textos["RETEIVA"], 50, 255, "", array("title" => $textos["AYUDA_RETEIVA"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_reteiva_nota_credito)"))
                .HTML::campoOculto("codigo_contable_reteiva_nota_credito", ""),

                HTML::campoTextoCorto("selector20", $textos["RETEICA"], 50, 255, "", array("title" => $textos["AYUDA_IVA_SEGURO"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_reteica_nota_credito)"))
                .HTML::campoOculto("codigo_contable_reteica_nota_credito", ""),
            )
        );

        $formularios["PESTANA_PROVISION"] = array(
            array(
                HTML::campoTextoCorto("selector21", $textos["CUENTA_INVENTARIO"], 50, 255, "", array("title" => $textos["AYUDA_CUENTA_INVENTARIO"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_inventario_provision)"))
                .HTML::campoOculto("codigo_contable_inventario_provision", ""),
            ),
            array(
                HTML::campoTextoCorto("selector22", $textos["CUENTA_PUENTE"], 50, 255, "", array("title" => $textos["AYUDA_CUENTA_PUENTE"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_puente_provision)"))
                .HTML::campoOculto("codigo_contable_puente_provision", ""),
            ),
            array(
                HTML::campoTextoCorto("selector23", $textos["RETEFUENTE"], 50, 255, "", array("title" => $textos["AYUDA_RETEFUENTE"], "class" => "autocompletable", "onKeyUp" => "limpiar_oculto_Autocompletable(this, codigo_contable_retefuente_provision)"))
                .HTML::campoOculto("codigo_contable_retefuente_provision", ""),
            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error     = $textos["ERROR_TIPOS_DOCUMENTOS"];
        $titulo    = "";
        $contenido = "";
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

    // Validar codigo
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("tipos_compra", "codigo", $url_valor,"codigo != 0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    // Validar descripcion
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("tipos_compra", "descripcion", $url_valor,"codigo != 0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $valor_debito  = (int)$forma_valor_base_nota_debito;
    $valor_credito = (int)$forma_valor_base_nota_credito;

    // Validar datos requeridos
    if(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    }elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];

    }elseif(empty($forma_concepto_compra)){
        $error   = true;
        $mensaje = $textos["CONCEPTO_VACIO"];

    }elseif(empty($forma_codigo_contable_cuentas_pagar)){
        $error   = true;
        $mensaje = $textos["PLAN_CXP_VACIO"];

    }elseif(empty($forma_codigo_contable_retefuente)){
        $error   = true;
        $mensaje = $textos["PLAN_RETEFUENTE_VACIO"];

    }elseif(empty($forma_codigo_contable_reteiva)){
        $error   = true;
        $mensaje = $textos["PLAN_IVA_VACIO"];

    }elseif(empty($forma_codigo_contable_seguro)){
        $error   = true;
        $mensaje = $textos["PLAN_SEGURO_VACIO"];

    }elseif(empty($forma_codigo_contable_iva_seguro)){
        $error   = true;
        $mensaje = $textos["PLAN_IVA_SEGURO_VACIO"];

    }elseif(empty($forma_codigo_contable_flete)){
        $error   = true;
        $mensaje = $textos["PLAN_FLETE_VACIO"];

    }elseif(empty($forma_codigo_contable_iva_flete)){
        $error   = true;
        $mensaje = $textos["PLAN_IVA_FLETE_VACIO"];

    }elseif(empty($forma_codigo_contable_iva_diferencia)){
        $error   = true;
        $mensaje = $textos["IVA_DIFERENCIA_VACIO"];

    }elseif(empty($forma_valor_base_nota_debito) || $valor_debito == 0){
        $error   = true;
        $mensaje = $textos["VALOR_DEBITO_VACIO"];

    }elseif(empty($forma_valor_base_nota_credito) || $valor_credito == 0){
        $error   = true;
        $mensaje = $textos["VALOR_CREDITO_VACIO"];

    }elseif($existe = SQL::existeItem("tipos_compra", "codigo", $forma_codigo,"codigo != 0")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

    }elseif($existe = SQL::existeItem("tipos_compra", "descripcion", $forma_descripcion,"codigo != 0")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];

    }else {

        // Insertar datos
        $datos = array(
            "codigo"                                     => $forma_codigo,
            "descripcion"                                => $forma_descripcion,
            "codigo_contable_cuentas_pagar"              => $forma_codigo_contable_cuentas_pagar,
            "codigo_contable_retefuente"                 => $forma_codigo_contable_retefuente,
            "codigo_contable_reteiva"                    => $forma_codigo_contable_reteiva,
            "codigo_contable_seguro"                     => $forma_codigo_contable_seguro,
            "codigo_contable_fletes"                     => $forma_codigo_contable_flete,
            "codigo_contable_iva_seguro"                 => $forma_codigo_contable_iva_seguro,
            "codigo_contable_iva_fletes"                 => $forma_codigo_contable_iva_flete,
            "codigo_contable_iva_diferencia"             => $forma_codigo_contable_iva_diferencia,
            "concepto_compra"                            => $forma_concepto_compra,
            "codigo_tipo_documento_nota_debito"          => $forma_tipo_documento_nota_debito,
            "valor_base_nota_debito"                     => $forma_valor_base_nota_debito,
            "codigo_contable_compra_nota_debito"         => $forma_codigo_contable_compra_nota_debito,
            "codigo_contable_iva_nota_debito"            => $forma_codigo_contable_iva_nota_debito,
            "codigo_contable_cuentas_pagar_nota_debito"  => $forma_codigo_contable_cuentas_pagar_nota_debito,
            "codigo_contable_retefuente_nota_debito"     => $forma_codigo_contable_retefuente_nota_debito,
            "codigo_contable_reteiva_nota_debito"        => $forma_codigo_contable_reteiva_nota_debito,
            "codigo_contable_reteica_nota_debito"        => $forma_codigo_contable_reteica_nota_debito,
            "codigo_tipo_documento_nota_credito"         => $forma_tipo_documento_nota_credito,
            "valor_base_nota_credito"                    => $forma_valor_base_nota_credito,
            "codigo_contable_compra_nota_credito"        => $forma_codigo_contable_compra_nota_credito,
            "codigo_contable_iva_nota_credito"           => $forma_codigo_contable_iva_nota_credito,
            "codigo_contable_cuentas_pagar_nota_credito" => $forma_codigo_contable_cuentas_pagar_nota_credito,
            "codigo_contable_retefuente_nota_credito"    => $forma_codigo_contable_retefuente_nota_credito,
            "codigo_contable_reteiva_nota_credito"       => $forma_codigo_contable_reteiva_nota_credito,
            "codigo_contable_reteica_nota_credito"       => $forma_codigo_contable_reteica_nota_credito,
            "codigo_contable_inventario_provision"       => $forma_codigo_contable_inventario_provision,
            "codigo_contable_puente_provision"           => $forma_codigo_contable_puente_provision,
            "codigo_contable_retefuente_provision"       => $forma_codigo_contable_retefuente_provision
        );

        $insertar = SQL::insertar("tipos_compra", $datos);

        // Error de insercion
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
