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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
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
        $vistaConsulta = "conceptos_dian";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;
        
        $valor_a_informar = array(
            "1" => $textos["SALDO"],
            "2" => $textos["ACUMULADO"],
            "3" => $textos["ACUMULADO_DB_CR"]
        );
        
        /*** Obtener valores ***/
        $formato_dian = SQL::obtenerValor("formatos_dian", "descripcion", "codigo = '$datos->codigo_formato_dian'");
        
        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO_DIAN"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("codigo_formato_dian", $textos["FORMATO_DIAN"], $formato_dian)
            ),
            array(            
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion),
                HTML::mostrarDato("valor_base", $textos["VALOR_BASE"], $datos->valor_base)
            ),
            array(
                HTML::mostrarDato("valor_a_informar", $textos["VALOR_INFORMAR"], $valor_a_informar[$datos->valor_a_informar]),
                HTML::mostrarDato("identificacion_valores_mayores", $textos["IDENTIFICAION_VALORES"], $datos->identificacion_valores_mayores)            
            ),
            array(
                HTML::mostrarDato("concepto_razon_social", $textos["CONCEPTO_RAZON_SOCIAL"], $datos->concepto_razon_social),
                HTML::mostrarDato("tipo_documento", $textos["TIPO_DOCUMENTO"], $datos->tipo_documento)
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
