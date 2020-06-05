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
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta   = "resoluciones_dian";
        $llave           = explode("|",$url_id);
        $codigo_sucursal = $llave[0];
        $numero          = $llave[1];
        $columnas        = SQL::obtenerColumnas($vistaConsulta);
        $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '$codigo_sucursal' AND numero='$numero'");
        $datos           = SQL::filaEnObjeto($consulta);
        $error           = "";
        $titulo          = $componente->nombre;

        $tipo_resolucion = array(
            "1" => $textos["AUTORIZADA"],
            "2" => $textos["HABILITADA"],
            "3" => $textos["TRAMITE"]
        );

        $estado = array(
            "0" => $textos["INACTIVA"],
            "1" => $textos["ACTIVA"]
        );
        /*** Obtener valores ***/
        $sucursal       = SQL::obtenerValor("sucursales", "nombre", "codigo = '$codigo_sucursal'");
        $tipo_documento = SQL::obtenerValor("tipos_documentos", "descripcion", "codigo = '$datos->codigo_tipo_documento'");
        $concepto       = SQL::obtenerValor("conceptos_resoluciones_dian", "nombre", "codigo = '$datos->codigo_concepto_resolucion_dian'");

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("sucursal", $textos["SUCURSAL"], $sucursal),
                HTML::mostrarDato("numero", $textos["NUMERO"], $numero)
            ),
            array(
                HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"], $tipo_documento)
            ),
            array(
                HTML::mostrarDato("concepto", $textos["CONCEPTO"], $concepto)
            ),
            array(
                HTML::mostrarDato("prefijo", $textos["PREFIJO"], $datos->prefijo),
                HTML::mostrarDato("fecha_inicia", $textos["FECHA_INICIA"], $datos->fecha_inicia),
                HTML::mostrarDato("fecha_termina", $textos["FECHA_TERMINA"], $datos->fecha_termina)
            ),
            array(
                HTML::mostrarDato("*factura_inicial", $textos["FACTURA_INICIAL"], number_format($datos->factura_inicial)),
                HTML::mostrarDato("*factura_final", $textos["FACTURA_FINAL"], number_format($datos->factura_final))
            ),
            array(
                HTML::mostrarDato("rango", $textos["RANGO"], number_format($datos->rango)),
                HTML::mostrarDato("tipo_resolucion", $textos["TIPO_RESOLUCION"], $tipo_resolucion[$datos->tipo_resolucion]),
            ),
            array(
                HTML::mostrarDato("estado", $textos["ESTADO"], $estado[$datos->estado]),
            )
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
