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
        $llave           = explode('|',$url_id);
        $codigo_sucursal = $llave[0];
        $modulo          = $llave[1];
        $fecha_inicio    = $llave[2];
        $fecha_fin       = $llave[3];
        $vistaConsulta   = "periodos_contables";
        $columnas        = SQL::obtenerColumnas($vistaConsulta);
        $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas,"codigo_sucursal='".$codigo_sucursal."' AND fecha_inicio='".$fecha_inicio."' AND fecha_fin='".$fecha_fin."'");
        $datos           = SQL::filaEnObjeto($consulta);
        $error           = "";
        $titulo          = $componente->nombre;
        $sucursal       = SQL::obtenerValor("sucursales", "nombre", "codigo = '$codigo_sucursal'");

        /*** Definición de pestaña general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("sucursal",$textos["SUCURSAL"],$sucursal)
            ),
            array(
                HTML::mostrarDato("modulo",$textos["MODULO"],$modulo)
            ),
            array(
                HTML::mostrarDato("estado",$textos["ESTADO"], $textos["ESTADO_".$datos->estado])
            )
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
