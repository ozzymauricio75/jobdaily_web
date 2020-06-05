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

        /*** Definici�n de pesta�a general ***/
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
        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    $llave           = explode('|',$forma_id);
    $codigo_sucursal = $llave[0];
    $id_modulo       = $llave[1];
    $fecha_inicio    = $llave[2];
    $fecha_fin       = $llave[3];

    $consulta = SQL::eliminar("periodos_contables", "codigo_sucursal='$codigo_sucursal' AND id_modulo='$id_modulo'AND fecha_inicio='$fecha_inicio' AND fecha_fin='$fecha_fin'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
