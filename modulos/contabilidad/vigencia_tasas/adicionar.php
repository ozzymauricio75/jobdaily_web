<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
* Mauricio Oidor L. <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
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
    $error  = "";
    $titulo = $componente->nombre;

    /*** Definición de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
    	array(
    		HTML::listaSeleccionSimple("*tasa", $textos["TASA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"))
    	),
        array(
            HTML::campoTextoCorto("*fecha", $textos["FECHA_VIGENCIA"], 10, 10, "", array("class" => "selectorFecha", "title" => $textos["AYUDA_FECHA"]))
        ),
        array(
            HTML::campoTextoCorto("*porcentaje", $textos["PORCENTAJE"], 5, 5, "", array("title" => $textos["AYUDA_PORCENTAJE"])),
            HTML::campoTextoCorto("valor_base", $textos["VALOR_BASE"], 15, 15, "", array("title" => $textos["AYUDA_VALOR"]))
        )
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_tasa)|| empty($forma_fecha)){
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    }else {
        $datos = array(
            "codigo_tasa" => $forma_tasa,
            "fecha"       => $forma_fecha,
            "porcentaje"  => $forma_porcentaje,
            "valor_base"  => $forma_valor_base
        );

        $insertar = SQL::insertar("vigencia_tasas", $datos);

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
