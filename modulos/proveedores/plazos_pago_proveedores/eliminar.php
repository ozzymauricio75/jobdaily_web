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

    /*** Verificar que se haya enviado el ID del elemento a eliminar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
          $vistaConsulta = "plazos_pago_proveedores";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        if ($datos->inicial=='0'){
            $datos->inicial='1';
        }
        if ($datos->final=='0'){
            $datos->final='1';
        }

        $error         = "";
        $titulo        = $componente->nombre;
        
        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array( 
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array( 
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            array( 
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
            ),
            array( 
                HTML::mostrarDato("inicial", $textos["INICIAL"], $datos->inicial),
                HTML::mostrarDato("final", $textos["FINAL"], $datos->final)
            ),
            array( 
                HTML::mostrarDato("periodo", $textos["PERIODO"], $datos->periodo),
                HTML::mostrarDato("numero_cuotas", $textos["CUOTAS"], $datos->numero_cuotas)
            ),
            array( 
                HTML::mostrarDato("orden", $textos["ORDEN"], $datos->orden)
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    $consulta = SQL::eliminar("plazos_pago_proveedores", "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
