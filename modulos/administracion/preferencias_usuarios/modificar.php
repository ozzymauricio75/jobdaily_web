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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a modificar
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    } else {

        $error  = "";
        $titulo = $componente->nombre;

        $preferencias = array();

        $preferencias_usuario = SQL::seleccionar(array("preferencias"),array("valor", "variable"),"tipo_preferencia='4' AND codigo_usuario='".$url_id."'");

        if(SQL::filasDevueltas($preferencias_usuario)){
            while ($datos = SQL::filaEnObjeto($preferencias_usuario)) {
            $preferencias[$datos->variable] = $datos->valor;
            }
        }

        $formularios["PESTANA_ARTICULOS"] = array();

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    //$elementos_preferencias = array();
    //$elementos_preferencias[""] = $forma_;

    /*foreach($elementos_preferencias AS $id_vector => $valor_vector){
        $datos = array(
            "codigo_empresa"    => 0,
            "codigo_sucursal"   => 0,
            "codigo_usuario"    => $forma_id,
            "tipo_preferencia"  => "4",
            "variable"          => $id_vector,
            "valor"             => $valor_vector
        );

        $reemplazar = SQL::reemplazar("preferencias", $datos);
    }*/

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
