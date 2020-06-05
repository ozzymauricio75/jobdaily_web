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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a consultar
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
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

        $nombre_codigo_1 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_cuentas_pagar."'");
        $nombre_codigo_2 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_retefuente."'");
        $nombre_codigo_3 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_reteiva."'");
        $nombre_codigo_4 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_seguro."'");
        $nombre_codigo_6 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_fletes."'");
        $nombre_codigo_5 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_iva_seguro."'");
        $nombre_codigo_7 = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable_iva_fletes."'");

        // Definicion de pestanas
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["NOMBRE"], $datos->descripcion)
            ),
            array(
                HTML::mostrarDato("concepto_compra", $textos["CONCEPTO"], $vector_conceptos[$datos->concepto_compra])
            )
        );

        $formularios["PESTANA_CODIGOS_CONTABLES"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CUENTAS_PAGAR"], $nombre_codigo_1)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["RETEFUENTE"], $nombre_codigo_2)
            ),
            array(
                HTML::mostrarDato("tipo_tasa_retefuente", $textos["RETEIVA"], $nombre_codigo_3)
            ),
            array(
                HTML::mostrarDato("tipos_transacciones", $textos["SEGURO"], $nombre_codigo_4)
            ),
            array(
                HTML::mostrarDato("tipos_transacciones", $textos["IVA_SEGURO"], $nombre_codigo_6)
            ),
            array(
                HTML::mostrarDato("concepto_compra", $textos["FLETES"], $nombre_codigo_5)
            ),
            array(
                HTML::mostrarDato("tipos_transacciones", $textos["IVA_FLETES"], $nombre_codigo_7)
            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('".$url_id."');", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
} elseif (!empty($forma_procesar)) {
    $consulta = SQL::eliminar("tipos_devoluciones_compra", "codigo = '".$forma_id."'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
