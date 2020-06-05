<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Jiovanny Andrés Ibargüen <jiovanny007@gmail.com>
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
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = "";

    $tablas     = array(
        "c" => "sucursales"
    );
    $columnas = array(
        "codigo" => "c.codigo",
        "nombre" => "c.nombre_corto"
    );
    $condicion = "codigo != 0";
    $consulta = SQL::seleccionar($tablas, $columnas, $condicion);        

    $existen_sucursales = true;
    if (SQL::filasDevueltas($consulta)) {
        $sucursales = array();

        while ($datos_sucursal = SQL::filaEnObjeto($consulta)) {
            $idSucursal          = $datos_sucursal->codigo;
            $nombreSucursal      = $datos_sucursal->nombre;            
            $sucursales[$idSucursal]   = $datos_sucursal->nombre;

        }
    } else {
        $existen_sucursales = false;
    }

    if (!$existen_sucursales){
        $error = $textos["CREAR_SUCURSALES"];

    } else if(!$sucursales){
        $error = $textos["PRIVILEGIO_SUCURSAL"];

    } else {
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*ip", $textos["IP"], 15, 15, "", array("title" => $textos["AYUDA_IP"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"], $sucursales, "", array("title" => $textos["AYUDA_SUCURSAL"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre_netbios", $textos["NOMBRE_NETBIOS"], 30, 50, "", array("title" => $textos["AYUDA_NOMBRE_NETBIOS"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre_tcpip", $textos["NOMBRE_TCPIP"], 30, 30, "", array("title" => $textos["AYUDA_NOMBRE_TCPIP"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 30, 50, "", array("title" => $textos["AYUDA_DESCRIPCION"]))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar dirección ip ***/
    if ($url_item == "ip") {
        $existe = SQL::existeItem("servidores", "ip", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_IP"]);
        }

        if (!Cadena::validarDireccionIP($url_valor)){
            HTTP::enviarJSON($textos["ERROR_VALIDAR_IP"]);
        }
    }

    /*** Validar codigo_interno ***/
    if ($url_item == "nombre_netbios") {
        $existe = SQL::existeItem("servidores", "nombre_netbios", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NETBIOS"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre_tcpip") {
        $existe = SQL::existeItem("servidores", "nombre_tcpip", $url_valor);

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_TCPIP"]);
        }
    }

/*** Procesar los datos del formulario ***/
}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

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

        $existe = SQL::existeItem("servidores", "ip", $forma_ip);

        if ($existe) {
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_IP"];
        } else if ($existe = SQL::existeItem("servidores", "nombre_netbios", $forma_nombre_netbios)){
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_NETBIOS"];
        } else if ($existe = SQL::existeItem("servidores", "nombre_tcpip", $forma_nombre_tcpip)){
            $error = true;
            $mensaje = $textos["ERROR_EXISTE_TCPIP"];
        } else if ($existe = SQL::existeItem("servidores", "descripcion", $forma_descripcion)){
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

            $insertar = SQL::insertar("servidores", $datos);

            /*** Error de inserción ***/
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
