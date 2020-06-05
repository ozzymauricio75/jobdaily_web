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
        if ($url_selector == "selector3"){
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


    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $vistaConsulta = "tipos_devoluciones_compra";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '".$url_id."'");
        $datos         = SQL::filaEnObjeto($consulta);

        $error         = "";
        $titulo        = $componente->nombre;

        $vector_conceptos = array(
            "1" => $textos["COMPRAS_DIRECTAS"],
            "2" => $textos["COMPRAS_OBSEQUIO"],
            "3" => $textos["COMPRAS_FILIALES"],
            "4" => $textos["COMPRAS_CANJE"],
            "5" => $textos["COMPRAS_CONSIGNACION"]
        );

        $nombre_codigo_1 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_cuentas_pagar."'");
        $nombre_codigo_2 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_retefuente."'");
        $nombre_codigo_3 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_reteiva."'");
        $nombre_codigo_4 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_seguro."'");
        $nombre_codigo_5 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_iva_seguro."'");
        $nombre_codigo_6 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_fletes."'");
        $nombre_codigo_7 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_iva_fletes."'");

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 4, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntro(event);"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["NOMBRE"], 40, 150, $datos->descripcion, array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("concepto_compra", $textos["CONCEPTO"],$vector_conceptos,$datos->concepto_compra,array("title" => $textos["AYUDA_CONCEPTO"]))
            )
        );

        $formularios["PESTANA_CODIGOS_CONTABLES"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["CUENTAS_PAGAR"], 50, 255, $nombre_codigo_1, array("title" => $textos["AYUDA_CUENTAS_PAGAR"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_cuentas_pagar", $datos->codigo_contable_cuentas_pagar),
            ),
            array(
                HTML::campoTextoCorto("*selector2", $textos["RETEFUENTE"], 50, 255, $nombre_codigo_2, array("title" => $textos["AYUDA_RETEFUENTE"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_retefuente", $datos->codigo_contable_retefuente),
            ),
            array(
                HTML::campoTextoCorto("*selector3", $textos["RETEIVA"], 50, 255, $nombre_codigo_3, array("title" => $textos["AYUDA_RETEIVA"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_reteiva", $datos->codigo_contable_reteiva),
            ),
            array(
                HTML::campoTextoCorto("*selector4", $textos["SEGURO"], 50, 255, $nombre_codigo_4, array("title" => $textos["AYUDA_SEGURO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_seguro", $datos->codigo_contable_seguro)
            ),
            array(
                HTML::campoTextoCorto("*selector6", $textos["IVA_SEGURO"], 50, 255, $nombre_codigo_5, array("title" => $textos["AYUDA_IVA_SEGURO"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_iva_seguro", $datos->codigo_contable_iva_seguro),
            ),
            array(
                HTML::campoTextoCorto("*selector5", $textos["FLETES"], 50, 255, $nombre_codigo_6, array("title" => $textos["AYUDA_FLETES"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_flete", $datos->codigo_contable_fletes)
            ),
            array(
                HTML::campoTextoCorto("*selector7", $textos["IVA_FLETES"], 50, 255, $nombre_codigo_7, array("title" => $textos["AYUDA_IVA_FLETES"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_iva_flete", $datos->codigo_contable_iva_fletes)
            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
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
        $existe = SQL::existeItem("tipos_devoluciones_compra", "codigo", $url_valor,"codigo != '".$url_id."'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    // Validar descripcion
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("tipos_devoluciones_compra", "descripcion", $url_valor,"codigo != '".$url_id."'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

// Adicionar los datos provenientes del formulario
}elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

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

    }elseif($existe = SQL::existeItem("tipos_devoluciones_compra", "codigo", $forma_codigo,"codigo != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];

    }elseif($existe = SQL::existeItem("tipos_devoluciones_compra", "descripcion", $forma_descripcion,"codigo != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];

    }else {
        // Insertar datos
        $datos = array(
            "codigo"                         => $forma_codigo,
            "descripcion"                    => $forma_descripcion,
            "concepto_compra"                => $forma_concepto_compra,
            "codigo_contable_cuentas_pagar"  => $forma_codigo_contable_cuentas_pagar,
            "codigo_contable_retefuente"     => $forma_codigo_contable_retefuente,
            "codigo_contable_reteiva"        => $forma_codigo_contable_reteiva,
            "codigo_contable_seguro"         => $forma_codigo_contable_seguro,
            "codigo_contable_fletes"         => $forma_codigo_contable_iva_seguro,
            "codigo_contable_iva_seguro"     => $forma_codigo_contable_flete,
            "codigo_contable_iva_fletes"     => $forma_codigo_contable_iva_flete
        );
        $modificar = SQL::modificar("tipos_devoluciones_compra", $datos, "codigo = '".$forma_id."'");

        // Error de insercion
        if (!$modificar) {
            $error   = false;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
