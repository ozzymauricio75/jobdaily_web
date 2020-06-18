<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
* 
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la versiï¿½n 3
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
        $error         = "";
        $titulo        = $componente->nombre;
        
        $vistaConsulta = "departamentos_empresa";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        $gasto = SQL::obtenerValor("gastos_prestaciones_sociales","descripcion","codigo = '".$datos->codigo_gasto."'");

        /*** Definición de pestaña personal ***/
         $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("id_departamentos", $textos["CODIGO_DEPARTAMENTO"], $datos->codigo),
                HTML::mostrarDato("nombre", $textos["NOMBRE_DEPARTAMENTO"], $datos->nombre)
            ),
            array(
                HTML::mostrarDato("riesgos_profesionales", $textos["RIESGOS_PROFESIONALES"], number_format($datos->riesgos_profesionales,2)." "."%")
            ),
            array(
                HTML::mostrarDato("codigo_gasto", $textos["GASTO"], $gasto)
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
