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
* SIN GARANÍA ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n más
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "servidores";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Definici�n de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*ip", $textos["IP"], 15, 15, $datos->ip , array("title" => $textos["AYUDA_IP"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales","codigo","nombre","codigo>0"), $datos->codigo_sucursal , array("title" => $textos["AYUDA_SUCURSAL"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre_netbios", $textos["NOMBRE_NETBIOS"], 30, 50, $datos->nombre_netbios , array("title" => $textos["AYUDA_NOMBRE_NETBIOS"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre_tcpip", $textos["NOMBRE_TCPIP"], 30, 30, $datos->nombre_tcpip , array("title" => $textos["AYUDA_NOMBRE_TCPIP"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 30, 30, $datos->descripcion , array("title" => $textos["AYUDA_DESCRIPCION"]))
            )
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar direcci�n ip ***/
    if ($url_item == "ip") {

        if (!Cadena::validarDireccionIP($url_valor)){
            HTTP::enviarJSON($textos["ERROR_VALIDAR_IP"]);
        } else {
            $existe = SQL::existeItem("servidores", "ip", $url_valor, "id !='$url_id'");

            if ($existe) {
                HTTP::enviarJSON($textos["ERROR_EXISTE_IP"]);
            }
        }
    }

    /*** Validar codigo_interno ***/
    if ($url_item == "nombre_netbios") {
        $existe = SQL::existeItem("servidores", "nombre_netbios", $url_valor, "id !='$url_id'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NETBIOS"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre_tcpip") {
        $existe = SQL::existeItem("servidores", "nombre_tcpip", $url_valor, "id !='$url_id'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_TCPIP"]);
        }
    }
/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if (empty($forma_ip)){
        $error = true;
        $mensaje = $textos["ERROR_IP"];
    } else if (empty($forma_nombre_netbios)){
        $error = true;
        $mensaje = $textos["ERROR_NETBIOS"];
    } else if (empty($forma_nombre_tcpip)){
        $error = true;
        $mensaje = $textos["ERROR_TCPIP"];

    } elseif (!Cadena::validarDireccionIP($forma_ip)){
        HTTP::enviarJSON($textos["ERROR_VALIDAR_IP"]);

    } else {

        $existe = SQL::existeItem("servidores", "ip", $forma_ip, "id !='$forma_id'");

        if ($existe) {
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_IP"];
        } else if ($existe = SQL::existeItem("servidores", "nombre_netbios", $forma_nombre_netbios, "id !='$forma_id'")){
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_NETBIOS"];
        } else if ($existe = SQL::existeItem("servidores", "nombre_tcpip", $forma_nombre_tcpip, "id !='$forma_id'")){
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_TCPIP"];
        } else if ($existe = SQL::existeItem("servidores", "descripcion", $forma_descripcion, "id!='$forma_id'")){
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
        } else {

            $datos = array(
                "ip"              => $forma_ip,
                "codigo_sucursal" => $forma_codigo_sucursal,
                "nombre_netbios"  => $forma_nombre_netbios,
                "nombre_tcpip"    => $forma_nombre_tcpip,
                "descripcion"     => $forma_descripcion
            );

            $consulta = SQL::modificar("servidores", $datos, "id = '$forma_id'");

            if ($consulta) {
                $error   = false;
                $mensaje = $textos["ITEM_MODIFICADO"];
            } else {
                $error   = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            }
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
