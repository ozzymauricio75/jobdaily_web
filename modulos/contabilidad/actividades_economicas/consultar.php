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
        $error         = "";
        $titulo        = $componente->nombre;

        $forma_id_municipio         = explode("|",$url_id);
        $codigo_iso                 = $forma_id_municipio[0];
        $codigo_dane_departamento   = $forma_id_municipio[1];
        $codigo_dane_municipio      = $forma_id_municipio[2];
        $codigo_dian                = $forma_id_municipio[3];
        $codigo_actividad_municipio = $forma_id_municipio[4];

        $vistaConsulta = "actividades_economicas";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $condicion     = "codigo_iso ='$codigo_iso' AND codigo_dane_departamento='$codigo_dane_departamento' AND codigo_dane_municipio ='$codigo_dane_municipio'";
        $condicion     .= " AND codigo_dian ='$codigo_dian' AND codigo_actividad_municipio='$codigo_actividad_municipio'";
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        
        $condicion        = "id = '".$codigo_iso.'|'.$codigo_dane_departamento.'|'.$codigo_dane_municipio."'";
        $nombre_municipio = SQL::obtenerValor("seleccion_municipios","nombre", $condicion);
        $nombre_municipio = explode("|", $nombre_municipio);
        $nombre_municipio = $nombre_municipio[0];
        
        
        /*** Definici�n de pesta�as ***/
        $formularios["PESTANA_GENERAL"] = array(
             array(
                HTML::mostrarDato("*selector1", $textos["MUNICIPIOS"],$nombre_municipio)
            ),
            array(
                HTML::mostrarDato("*codigo_actividad_municipio", $textos["ACTIVIDAD_MUNICIPIO"], $datos->codigo_actividad_municipio)
            ),
            array(
                HTML::mostrarDato("codigo_dian", $textos["CODIGO_DIAN"], $datos->codigo_dian)
            ),
            array(
                HTML::mostrarDato("codigo_interno", $textos["CODIGO_INTERNO"], $datos->codigo_interno)
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
