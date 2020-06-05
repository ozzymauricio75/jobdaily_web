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
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
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
        
        $llave_primaria= explode("|",$url_id);
        $codigo_empresa = $llave_primaria[0];
        $codigo_anexo   = $llave_primaria[1];
        $codigo_        = $llave_primaria[2];
        

        $vistaConsulta = "auxiliares_contables";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$codigo_ ' AND codigo_empresa='$codigo_empresa' AND codigo_anexo_contable='$codigo_anexo'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $nombre_empresa = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
        $nombre_anexo   = SQL::obtenerValor("anexos_contables","descripcion","codigo= '$codigo_anexo'");

   
        /*** Definici�n de pesta�as ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("empresa", $textos["EMPRESA"],$nombre_empresa),
                HTML::mostrarDato("anexo", $textos["ANEXO_CONTABLE"],$nombre_anexo)
            ),
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo),
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
