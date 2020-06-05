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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
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

        /*** Definici�n de pesta�as general ***/
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

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
