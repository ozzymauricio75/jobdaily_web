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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $id_                      = explode("|", $url_id); // genera un arreglo en donde se obtienen las llaves primarias
        $codigo_iso               = $id_[0];
        $codigo_dane_departamento = $id_[1];
        $codigo_dane_municipio    = $id_[2];
        $codigo_vista             = $codigo_dane_departamento.$codigo_dane_municipio;


        $vistaConsulta = "buscador_municipios";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_dane = '$codigo_vista'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        $comunas       = SQL::obtenerValor("municipios", "comunas", "codigo_dane_municipio = '$codigo_dane_municipio' AND codigo_iso = '$codigo_iso' AND codigo_dane_departamento = '$codigo_dane_departamento'");
        $capital       = SQL::obtenerValor("municipios", "capital", "codigo_dane_municipio = '$codigo_dane_municipio' AND codigo_iso = '$codigo_iso' AND codigo_dane_departamento = '$codigo_dane_departamento'");

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("pais", $textos["PAIS"], $datos->pais)
            ),
            array(
                HTML::mostrarDato("departamento", $textos["DEPARTAMENTO"], $datos->departamento)
            ),
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            array(
                HTML::mostrarDato("codigo_dane", $textos["CODIGO_DANE"], $datos->codigo_dane),
                HTML::mostrarDato("codigo_interno", $textos["CODIGO_INTERNO"], $datos->codigo_interno)
            ),
            array(
                HTML::mostrarDato("comunas", $textos["COMUNAS"], intval($comunas)),
            ),
            array(
                HTML::mostrarDato("capital", $textos["CAPITAL"], $textos["SI_NO_".$capital])
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
