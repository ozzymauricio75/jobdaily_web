<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Walter Andr�s M�rquez Guti�rrez <walteramg@gmail.com>
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
    $error  = "";
    $titulo = $componente->nombre;

    $consulta_tasas = SQL::seleccionar(array("tasas"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_tasas)){
        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*tasa", $textos["TASA"], HTML::generarDatosLista("tasas", "codigo", "descripcion","codigo != 0"), "", array("title" => $textos["AYUDA_TASA"]))
            ),
            array(
                HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, date("Y-m-d"), array("class" => "fechaNuevas", "title" => $textos["AYUDA_FECHA"]))
            ),
            array(
                HTML::campoTextoCorto("porcentaje", $textos["PORCENTAJE"], 5, 5, "", array("title" => $textos["AYUDA_PORCENTAJE"], "onKeyPress" => "return campoDecimal(event)")),
                HTML::campoTextoCorto("valor_base", $textos["VALOR_BASE"], 10, 10, "", array("title" => $textos["AYUDA_VALOR"], "onKeyPress" => "return campoDecimal(event)"))
            )
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error     = $textos["CREAR_TASAS"];
        $contenido = "";
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if(empty($forma_tasa)){
		$error   = true;
		$mensaje = $textos["TASA_VACIO"];

	}elseif(empty($forma_fecha)){
        $error   = true;
        $mensaje = $textos["FECHA_VACIO"];

    }else if (SQL::obtenerValor("vigencia_tasas","fecha","codigo_tasa='$forma_tasa' AND fecha='$forma_fecha'")){
        $error   = true;
        $mensaje = $textos["EXISTE_VIGENCIA"];

    }else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_tasa" => $forma_tasa,
            "fecha"       => $forma_fecha,
            "porcentaje"  => $forma_porcentaje,
            "valor_base"  => $forma_valor_base
        );
        $insertar = SQL::insertar("vigencia_tasas", $datos);

        /*** Error de inserci�n ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
