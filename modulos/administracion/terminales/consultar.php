<?php

/**
*
* Copyright (C) 2020 Jobdaily
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
* SIN GARANTIA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información mÃ¡s
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
        $vistaConsulta = "terminales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        //Obtener valor servidor
        $ip_servidor          = SQL::obtenerValor("servidores","ip","id = '$datos->id_servidor'");
        $descripcion_servidor = SQL::obtenerValor("servidores","descripcion","id = '$datos->id_servidor'");
        $servidor             = $ip_servidor." - ".$descripcion_servidor;

        /*** Definición de pestaÃ±as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("servidor", $textos["SERVIDOR"], $servidor)
            ),
            array(
                HTML::mostrarDato("ip", $textos["IP"], $datos->ip)
            ),
            array(
                HTML::mostrarDato("nombre_netbios", $textos["NOMBRE_NETBIOS"], $datos->nombre_netbios)
            ),
            array(
                HTML::mostrarDato("nombre_tcpip", $textos["NOMBRE_TCPIP"], $datos->nombre_tcpip)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
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
