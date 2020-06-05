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
        $vistaConsulta = "buscador_referencias_por_proveedor";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $llave_principal = explode("|",$url_id);
        /*** Obtener valores de la tabla referencias por proveedor ***/
        $codigo_barras = SQL::obtenerValor("referencias_por_proveedor", "codigo_barras", "codigo_interno_articulo = '".$llave_principal[0]."' AND referencia = '".$llave_principal[1]."'");
        $principal     = SQL::obtenerValor("referencias_por_proveedor", "principal", "codigo_interno_articulo = '".$llave_principal[0]."' AND referencia = '".$llave_principal[1]."'");
        
        /*** Definici�n de pesta�as ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo_interno_articulo", $textos["CODIGO_ARTICULO"], $datos->codigo_interno)
            ),
            array(
                HTML::mostrarDato("nombre_proveedor", $textos["PROVEEDOR"], $datos->proveedor)
            ),
            array(
                HTML::mostrarDato("referencia", $textos["REFERENCIA"], $datos->referencia)
            ),
            array(
                HTML::mostrarDato("codigo_barras", $textos["CODIGO_BARRAS"], $codigo_barras)
            ),
            array(
                HTML::mostrarDato("principal", $textos["PRINCIPAL"], $textos["SI_NO_".intval($principal)])
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
