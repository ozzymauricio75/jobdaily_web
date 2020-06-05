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
        $vistaConsulta = "tipos_compra";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
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

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
            ),
            array(
                HTML::mostrarDato("concepto_compra", $textos["CONCEPTO"], $vector_conceptos[$datos->concepto_compra])
            )
        );

        $formularios["PESTANA_CODIGOS_CONTABLES"] = array(
            array(
                HTML::mostrarDato("selector1", $textos["CUENTAS_PAGAR"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_cuentas_pagar."'"))
            ),
            array(
                HTML::mostrarDato("selector2", $textos["RETEFUENTE"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_retefuente."'"))
            ),
            array(
                HTML::mostrarDato("selector3", $textos["RETEIVA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_reteiva."'"))
            ),
            array(
                HTML::mostrarDato("selector4", $textos["SEGURO"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_seguro."'")),
                HTML::mostrarDato("selector5", $textos["IVA_SEGURO"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_iva_seguro."'")),
            ),
            array(
                HTML::mostrarDato("selector6", $textos["FLETES"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_fletes."'")),
                HTML::mostrarDato("selector7", $textos["IVA_FLETES"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_iva_fletes."'"))
            ),
            array(
                HTML::mostrarDato("selector8", $textos["IVA_DIFERENCIA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_iva_diferencia."'"))
            )
        );

        $formularios["PESTANA_NOTAS_DEBITO"] = array(
            array(
                HTML::mostrarDato("tipo_documento_nota_debito", $textos["TIPO_DOCUMENTO"], SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$datos->codigo_tipo_documento_nota_debito."'")),
                HTML::mostrarDato("valor_base_nota_debito", $textos["VALOR_BASE"], "$ ".number_format($datos->valor_base_nota_debito))
            ),
            array(
                HTML::mostrarDato("selector9", $textos["CUENTA_COMPRA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_compra_nota_debito."'"))
            ),
            array(
                HTML::mostrarDato("selector10", $textos["IVA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_iva_nota_debito."'"))
            ),
            array(
                HTML::mostrarDato("selector11", $textos["CUENTAS_PAGAR"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_cuentas_pagar_nota_debito."'"))
            ),
            array(
                HTML::mostrarDato("selector12", $textos["RETEFUENTE"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_retefuente_nota_debito."'"))
            ),
            array(
                HTML::mostrarDato("selector13", $textos["RETEIVA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_reteiva_nota_debito."'")),
                HTML::mostrarDato("selector14", $textos["RETEICA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_reteica_nota_debito."'"))
            )
        );

        $formularios["PESTANA_NOTAS_CREDITO"] = array(
            array(
                HTML::mostrarDato("tipo_documento_nota_credito", $textos["TIPO_DOCUMENTO"], SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$datos->codigo_tipo_documento_nota_credito."'")),
                HTML::mostrarDato("valor_base_nota_credito", $textos["VALOR_BASE"], "$ ".number_format($datos->valor_base_nota_credito))
            ),
            array(
                HTML::mostrarDato("selector15", $textos["CUENTA_COMPRA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_compra_nota_credito."'"))
            ),
            array(
                HTML::mostrarDato("selector16", $textos["IVA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_iva_nota_credito."'"))
            ),
            array(
                HTML::mostrarDato("selector17", $textos["CUENTAS_PAGAR"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_cuentas_pagar_nota_credito."'"))
            ),
            array(
                HTML::mostrarDato("selector18", $textos["RETEFUENTE"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_retefuente_nota_credito."'"))
            ),
            array(
                HTML::mostrarDato("selector19", $textos["RETEIVA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_reteiva_nota_credito."'")),
                HTML::mostrarDato("selector20", $textos["RETEICA"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_reteica_nota_credito."'"))
            )
        );

        $formularios["PESTANA_PROVISION"] = array(
            array(
                HTML::mostrarDato("selector21",$textos["CUENTA_INVENTARIO"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_inventario_provision."'"))
            ),
            array(
                HTML::mostrarDato("selector22",$textos["CUENTA_PUENTE"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_puente_provision."'"))
            ),
            array(
                HTML::mostrarDato("selector23", $textos["RETEFUENTE"], SQL::obtenerValor("seleccion_plan_contable","SUBSTRING_INDEX(codigo_contable,'|',1)","id = '".$datos->codigo_contable_retefuente_provision."'"))
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
    $consulta = SQL::eliminar("tipos_compra", "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
