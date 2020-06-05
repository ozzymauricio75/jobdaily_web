<?php

/**
*
* Copyright (C) 2020 Jobdaily
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
* SIN GARANÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Debería haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = "";

    $consulta_servidores = SQL::seleccionar(array("servidores"),array("*"),"id > 0");

    if (!SQL::filasDevueltas($consulta_servidores)){
        $error = $textos["CREAR_SERVIDORES"];

    } else {

        $servidores = HTML::generarDatosLista("servidores", "id", "CONCAT(ip,' - ',descripcion)");
        $servidores = HTML::generarDatosLista("servidores", "id", "CONCAT(ip,' - ',descripcion)");

        /*** Definici�n de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*servidor", $textos["SERVIDOR"], $servidores, "", array("title" => $textos["AYUDA_SERVIDOR"]))
            ),
            array(
                HTML::campoTextoCorto("*ip", $textos["IP"], 15, 15, "", array("title" => $textos["AYUDA_IP"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre_netbios", $textos["NOMBRE_NETBIOS"], 30, 50, "", array("title" => $textos["AYUDA_NOMBRE_NETBIOS"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre_tcpip", $textos["NOMBRE_TCPIP"], 30, 30, "", array("title" => $textos["AYUDA_NOMBRE_TCPIP"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 30, 30, "", array("title" => $textos["AYUDA_DESCRIPCION"]))
            )
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

	/*** Validar direcci�n ip ***/
    if ($url_item == "ip") {
        $existe = SQL::existeItem("terminales", "ip", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }

        if (!Cadena::validarDireccionIP($url_valor)){
            HTTP::enviarJSON($textos["ERROR_VALIDAR_IP"]);
        }
    }

	/*** Validar codigo_interno ***/
    if ($url_item == "nombre_netbios") {
        $existe = SQL::existeItem("terminales", "nombre_netbios", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre_tcpip") {
        $existe = SQL::existeItem("terminales", "nombre_tcpip", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Procesar los datos del formulario ***/
}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_ip) ||
        empty($forma_nombre_netbios) || empty($forma_nombre_tcpip)) {
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    } elseif (!Cadena::validarDireccionIP($forma_ip)){
        HTTP::enviarJSON($textos["ERROR_VALIDAR_IP"]);

    } else {

        $datos = array(
            "ip"             => $forma_ip,
            "id_servidor"    => $forma_servidor,
            "nombre_netbios" => $forma_nombre_netbios,
            "nombre_tcpip"   => $forma_nombre_tcpip,
            "descripcion"    => $forma_descripcion
        );

        $insertar = SQL::insertar("terminales", $datos);

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
