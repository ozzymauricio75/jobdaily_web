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
    if ($id_tasa1==0){
        $mensaje[0] = true;
        $mensaje[1] = $textos["ASIGNAR_TASA"]." ".$url_descripcion;
    } else {
        $mensaje[0] = false;
    }
    HTTP::enviarJSON($mensaje);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a modificar
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        // Obtener valores de las tablas
        $vistaConsulta = "conceptos_contabilizacion_compras";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $cuenta_1 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_compras."'");
        $cuenta_2 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_iva."'");
        $cuenta_3 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_iva_debito."'");
        $cuenta_4 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_iva_credito."'");
        $cuenta_5 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_compras_uvt."'");
        $cuenta_6 = SQL::obtenerValor("seleccion_plan_contable_transacciones", "SUBSTRING_INDEX(cuenta,'|',1)", "id = '".$datos->codigo_contable_iva_uvt."'");

        if($datos->regimen_ventas_empresa == '1'){
            $empresa_comun  = true;
            $empresa_simple = false;
        }else if($datos->regimen_ventas_empresa == '2'){
            $empresa_comun  = false;
            $empresa_simple = true;
        }

        if($datos->regimen_persona == '1'){
            $persona_comun  = true;
            $persona_simple = false;
            $oculto         = "";
        }else if($datos->regimen_persona == '2'){
            $persona_comun                      = false;
            $persona_simple                     = true;
            $oculto                             = "oculto";
            $cuenta_2                           = "";
            $datos->codigo_contable_iva         = "";
            $cuenta_3                           = "";
            $datos->codigo_contable_iva_debito  = "";
            $cuenta_4                           = "";
            $datos->codigo_contable_iva_credito = "";
            $cuenta_6                           = "";
            $datos->codigo_contable_iva_uvt     = "";
        }
        // Definicion de pestanas
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 40, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::mostrarDato("mostrar_regimen_ventas_empresa", $textos["REGIMEN_EMPRESA"],""),
                HTML::marcaSeleccion("regimen_ventas_empresa", $textos["REGIMEN_COMUN"], '1', $empresa_comun, array("id" => "regimen_comun")),
                HTML::marcaSeleccion("regimen_ventas_empresa", $textos["REGIMEN_SIMPLIFICADO"], '2', $empresa_simple, array("id" => "regimen_simplificado"))
            ),
            array(
                HTML::mostrarDato("mostrar_regimen_persona", $textos["REGIMEN_PERSONA"],""),
                HTML::marcaSeleccion("regimen_persona", $textos["REGIMEN_COMUN"], '1', $persona_comun, array("id" => "persona_comun", "onChange" => "persona_simplificada_cuentas()")),
                HTML::marcaSeleccion("regimen_persona", $textos["REGIMEN_SIMPLIFICADO"], '2', $persona_simple, array("id" => "persona_simplificada", "onChange" => "persona_simplificada_cuentas()"))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_compra", $textos["TIPO_COMPRA"], HTML::generarDatosLista("tipos_compra", "codigo", "descripcion", "codigo != '0'"),$datos->codigo_tipo_compra,array("title" => $textos["AYUDA_TIPO_COMPRA"])),
                HTML::listaSeleccionSimple("*tipo_tasa_iva", $textos["TASA_IVA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"),$datos->codigo_tasa_iva,array("title" => $textos["AYUDA_TASA_IVA"]))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["CODIGO_COMPRAS"], 40, 255, $cuenta_1, array("title" => $textos["AYUDA_CODIGO_COMPRAS"], "class" => "autocompletable"))
               .HTML::campoOculto("codigo_contable_cuentas_pagar", $datos->codigo_contable_compras),

                HTML::campoTextoCorto("*selector2", $textos["CODIGO_IVA"], 40, 255, $cuenta_2, array("title" => $textos["AYUDA_CODIGO_IVA"], "class" => "autocompletable ".$oculto))
               .HTML::campoOculto("codigo_contable_iva", $datos->codigo_contable_iva)
            ),
            array(
                HTML::campoTextoCorto("*selector3", $textos["CODIGO_IVA_DEBITO"], 40, 255, $cuenta_3, array("title" => $textos["AYUDA_CODIGO_IVA_DEBITO"], "class" => "autocompletable ".$oculto))
               .HTML::campoOculto("codigo_contable_iva_debito", $datos->codigo_contable_iva_debito),

                HTML::campoTextoCorto("*selector4", $textos["CODIGO_IVA_CREDITO"], 40, 255, $cuenta_4, array("title" => $textos["AYUDA_CODIGO_IVA_CREDITO"], "class" => "autocompletable ".$oculto))
               .HTML::campoOculto("codigo_contable_iva_credito", $datos->codigo_contable_iva_credito)
            ),
            array(
                HTML::campoTextoCorto("*selector5", $textos["CODIGO_COMPRAS_UVT"], 40, 255, $cuenta_5, array("title" => $textos["AYUDA_CODIGO_COMPRAS_UVT"], "class" => "autocompletable"))
               .HTML::campoOculto("codigo_contable_cuentas_pagar_uvt", $datos->codigo_contable_compras_uvt),

                HTML::campoTextoCorto("*selector6", $textos["CODIGO_IVA_UVT"], 40, 255, $cuenta_6, array("title" => $textos["AYUDA_CODIGO_IVA_UVT"], "class" => "autocompletable ".$oculto))
               .HTML::campoOculto("codigo_contable_iva_uvt", $datos->codigo_contable_iva_uvt)
            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

    // Validar descripcion
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("conceptos_contabilizacion_compras", "descripcion", $url_valor,"descripcion != '' AND codigo != '".$url_id."'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }
// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if (empty($forma_descripcion)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_DESCRIPCION"];

    } elseif (empty($forma_codigo_contable_cuentas_pagar)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_COMPRA"];

    } elseif (empty($forma_codigo_contable_cuentas_pagar_uvt)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_COMPRA_UVT"];

    } elseif ($forma_regimen_persona == '1' && empty($forma_codigo_contable_iva_uvt)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_IVA_UVT"];

    } elseif ($forma_regimen_persona == '1' && empty($forma_codigo_contable_iva)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_IVA"];

    } elseif ($forma_regimen_persona == '1' && empty($forma_codigo_contable_iva_debito)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_IVA_DEBITO"];

    } elseif ($forma_regimen_persona == '1' && empty($forma_codigo_contable_iva_credito)) {
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_IVA_CREDITO"];

    } elseif ($existe = SQL::existeItem("conceptos_contabilizacion_compras", "descripcion", $forma_descripcion,"descripcion != '' AND codigo != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];

    } else {

        $datos = array(
            "descripcion"                   => $forma_descripcion,
            "regimen_ventas_empresa"        => $forma_regimen_ventas_empresa,
            "regimen_persona"               => $forma_regimen_persona,
            "codigo_tipo_compra"            => $forma_tipo_compra,
            "codigo_tasa_iva"               => $forma_tipo_tasa_iva,
            "codigo_contable_compras"       => $forma_codigo_contable_cuentas_pagar,
            "codigo_contable_iva"           => $forma_codigo_contable_iva,
            "codigo_contable_iva_debito"    => $forma_codigo_contable_iva_debito,
            "codigo_contable_iva_credito"   => $forma_codigo_contable_iva_credito,
            "codigo_contable_compras_uvt"   => $forma_codigo_contable_cuentas_pagar_uvt,
            "codigo_contable_iva_uvt"       => $forma_codigo_contable_iva_uvt
        );

        $consulta = SQL::modificar("conceptos_contabilizacion_compras", $datos, "codigo = '".$forma_id."'");

        if (!$consulta) {
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
