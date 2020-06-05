<?php
/**
*
* Copyright (C) 2020 Jobdaily
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
    $error  = "";
    $titulo = $componente->nombre;

    $consecutivo = (int)SQL::obtenerValor("planillas","max(codigo)","");
    if($consecutivo){
        $consecutivo++;
    }else{
        $consecutivo=1;
    }

    /*** Definici�n de pesta�a personal ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 3, $consecutivo, array("title" => $textos["AYUDA_PLANILLA"], "onblur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 25, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);"))
        ),
        array(
            HTML::mostrarDato("periodo_pago", $textos["PERIODO_PAGO"], "")
        ),
        array(
            HTML::marcaSeleccion("pagos", $textos["MENSUAL"], 1, true, array("id" => "mensual")),
            HTML::marcaSeleccion("pagos", $textos["QUINCENAL"], 2, false, array("id" => "quincenal")),
            HTML::marcaSeleccion("pagos", $textos["SEMANAL"], 3, false, array("id" => "semanal")),
            HTML::marcaSeleccion("pagos", $textos["FECHA_UNICA"], 4, false, array("id" => "fecha_unica"))
        )
);

/*** Definici�n de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo y nombre del deporte ***/
    if (isset($url_valor)) {
        $existe = SQL::existeItem("planillas", "descripcion", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_PLANILLA"]);
        }

    }


/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_descripcion) || (empty($forma_pagos)))
    {
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } else {

        $datos = array (
            "codigo"       => $forma_codigo,
            "descripcion"  => $forma_descripcion,
            "periodo_pago" => $forma_pagos
        );

        $insertar = SQL::insertar("planillas", $datos);

        /// Error de inserc�n
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
