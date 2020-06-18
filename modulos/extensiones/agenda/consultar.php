<?php

/**
*
* Copyright (C) 2020 Jobdaily
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los términos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n más
* detallada.
*
* Debería haber recibido una copia de la Licencia P�blica General GNU
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
        $vistaConsulta = "agenda";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        
		$duracionH = intval($datos->duracion/60);
		$duracionM = $datos->duracion%60;
        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("hora_inicio", $textos["HORA_INICIO"], $datos->hora_inicio)
            ),
            array(
                HTML::mostrarDato("fecha", $textos["FECHA"], $datos->fecha)
            ),
            array(
                HTML::mostrarDato("duracion", $textos["DURACION"], $duracionH.":".$duracionM)
            ),
            array(
                HTML::mostrarDato("titulo", $textos["TITULO"], $datos->titulo)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
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
