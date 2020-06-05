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

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "usuarios";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        $consulta      = SQL::seleccionar(array("imagenes"), array("*"), "id_asociado = '$url_id' AND categoria = '1'");
        $imagen        = SQL::filaEnObjeto($consulta);
        $consulta      = SQL::seleccionar(array("imagenes"), array("*"), "id_asociado = '$url_id' AND categoria = '3'");
        $firma_digital = SQL::filaEnObjeto($consulta);

        if ($imagen) {
            $llave = $imagen->id_asociado."|".$imagen->categoria;
            $imagen = HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$llave, array("width" => $imagen->ancho, "height" => $imagen->alto));
        } else {
            $imagen = "";
        }
        if ($firma_digital) {
            $llave = $firma_digital->id_asociado."|".$firma_digital->categoria;
            $firma_digital = HTML::imagen(HTTP::generarURL("VISUIMAG")."&id=".$llave, array("width" => $firma_digital->ancho, "height" => $firma_digital->alto));
        } else {
            $firma_digital = "";
        }

        /*** Definición de pestañas ***/
        if ($datos->codigo>0){
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 30, 50, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onBlur" => "validarItem(this);")),
                    HTML::campoOculto("id", $datos->codigo)
                )
            );
        } else {
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("*codigo", $textos["CODIGO"], $datos->codigo),
                    HTML::campoOculto("codigo", $datos->codigo),
                    HTML::campoOculto("id", $datos->codigo)
                )
            );
        }

        $resto[] = array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 50, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
        );
        $resto[] = array(
            HTML::campoTextoCorto("*correo", $textos["CORREO"], 30, 255, $datos->correo, array("title" => $textos["AYUDA_CORREO"]))
        );
        $resto[] = array(
            HTML::campoTextoCorto("*usuario", $textos["USUARIO"], 12, 12, $datos->usuario, array("title" => $textos["AYUDA_USUARIO"], "onBlur" => "validarItem(this);", "onBlur" => "validarItem(this);"))
        );
        $resto[] = array(
            HTML::campoTextoClave("*contrasena1", $textos["CONTRASENA"], 12, 12, "", array("title" => $textos["AYUDA_CONTRASENA1"], "onBlur" => "validarItem(this);"))
        );
        $resto[] = array(
            HTML::campoTextoClave("*contrasena2", $textos["VERIFICAR_CONTRASENA"], 12, 12, "", array("title" => $textos["AYUDA_CONTRASENA2"], "onBlur" => "validarItem(this);"))
        );

        $formularios["PESTANA_GENERAL"] = array_merge($formularios["PESTANA_GENERAL"],$resto);

        $formularios["PESTANA_ACCESO"] = array(
            array(
                HTML::marcaChequeo("cambiar_contrasena", $textos["CAMBIAR_CONTRASENA"], 1, $datos->cambiar_contrasena)
            ),
            array(
                HTML::campoTextoCorto("cambio_contrasena_minimo", $textos["CAMBIO_CONTRASENA_MINIMO"], 3, 3, $datos->cambio_contrasena_minimo, array("title" => $textos["AYUDA_CAMBIO_CONTRASENA_MINIMO"], "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("cambio_contrasena_maximo", $textos["CAMBIO_CONTRASENA_MAXIMO"], 3, 3, $datos->cambio_contrasena_maximo, array("title" => $textos["AYUDA_CAMBIO_CONTRASENA_MINIMO"], "onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("fecha_expiracion", $textos["FECHA_EXPIRACION"], 10, 10, substr($datos->fecha_expiracion, 0, 10), array("class" => "selectorFecha"))
            )
        );

        $formularios["PESTANA_IMAGEN"] = array(
            array(
                HTML::selectorArchivo("imagen", $textos["IMAGEN"], array("title" => $textos["AYUDA_IMAGEN"]))
            ),
            array(
                HTML::mostrarDato("imagen_actual", "", $imagen)
            )
        );

        $formularios["PESTANA_FIRMA_DIGITAL"] = array(
            array(
                HTML::selectorArchivo("firma_digital", $textos["FIRMA_DIGITAL"], array("title" => $textos["AYUDA_FIRMA_DIGITAL"]))
            ),
            array(
                HTML::mostrarDato("firma_actual", "", $firma_digital)
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    exit();

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar datos ***/
    if ($url_item == "usuario") {
        $existe = SQL::existeItem("usuarios", "codigo", $url_valor,"codigo !='$url_id'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_USUARIO"]);
        }
    }
    if ($url_item == "usuario") {
        $existe = SQL::existeItem("usuarios", "usuario", $url_valor,"codigo !='$url_id'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_USUARIO"]);
        }
    }
    if ($url_item == "usuario") {
        $existe = SQL::existeItem("usuarios", "nombre", $url_valor, "codigo !='$url_id'");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_USUARIO"]);
        }
    }

    exit();

/*** Modificar el elemento seleccionado ***/
}

/*** Validar el ingreso de los datos requeridos ***/
if(empty($forma_usuario)){
	$error   = true;
	$mensaje = $textos["USUARIO_VACIO"];
        
}elseif(empty($forma_codigo)){
	$error   = true;
	$mensaje = $textos["CODIGO_VACIO"];

}elseif(empty($forma_nombre)){
	$error   = true;
	$mensaje = $textos["NOMBRE_VACIO"];

}elseif($existe = SQL::existeItem("usuarios", "codigo", $forma_codigo,"codigo !='$forma_id'")){
    $error   = true;
    $mensaje = $textos["ERROR_EXISTE_CODIGO"];

}elseif($existe = SQL::existeItem("usuarios", "usuario", $forma_usuario,"codigo !='$forma_id'")){
    $error   = true;
    $mensaje = $textos["ERROR_EXISTE_USUARIO"];

} elseif (!Cadena::validarCorreo($forma_correo)) {
    $error   = true;
    $mensaje =  $textos["ERROR_SINTAXIS_CORREO"];

} else {

    if (empty($forma_contrasena1)){
        $datos = array(
            "codigo"                   => $forma_codigo,
            "nombre"                   => $forma_nombre,
            "usuario"                  => $forma_usuario,
            "correo"                   => $forma_correo,
            "cambiar_contrasena"       => $forma_cambiar_contrasena,
            "cambio_contrasena_minimo" => $forma_cambio_contrasena_minimo,
            "cambio_contrasena_maximo" => $forma_cambio_contrasena_maximo,
            "fecha_expiracion"         => $forma_fecha_expiracion
        );
    }else {
        $datos = array(
            "codigo"                   => $forma_codigo,
            "nombre"                   => $forma_nombre,
            "usuario"                  => $forma_usuario,
            "contrasena"               => md5($forma_contrasena1),
            "correo"                   => $forma_correo,
            "cambiar_contrasena"       => $forma_cambiar_contrasena,
            "cambio_contrasena_minimo" => $forma_cambio_contrasena_minimo,
            "cambio_contrasena_maximo" => $forma_cambio_contrasena_maximo,
            "fecha_expiracion"         => $forma_fecha_expiracion
        );
    }

    $consulta = SQL::modificar("usuarios", $datos, "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_MODIFICADO"];
        if (!empty($_FILES["imagen"]["name"])) {
            $original   = $_FILES["imagen"]["name"];
            $temporal   = $_FILES["imagen"]["tmp_name"];
            $extension  = strtolower(substr($original, (strrpos($original, ".") - strlen($original)) + 1));

            if (strtolower($extension) != "png" && strtolower($extension) != "jpg" && strtolower($extension) != "gif") {
                $error   = true;
                $mensaje = $textos["ERROR_FORMATO_IMAGEN"];

            } else {
                list($ancho, $alto, $tipo) = getimagesize($temporal);

                $datos   = array(
                    "categoria"   => "1",
                    "id_asociado" => $forma_codigo,
                    "contenido"   => file_get_contents($temporal),
                    "tipo"        => $tipo,
                    "extension"   => $extension,
                    "ancho"       => $ancho,
                    "alto"        => $alto
                );

                $consulta = SQL::eliminar("imagenes", "categoria = '1' AND id_asociado = '$forma_id'");
                $insertar = SQL::insertarArchivo("imagenes", $datos);
            }
        }
        if (!empty($_FILES["firma_digital"]["name"])) {
            $original   = $_FILES["firma_digital"]["name"];
            $temporal   = $_FILES["firma_digital"]["tmp_name"];
            $extension  = strtolower(substr($original, (strrpos($original, ".") - strlen($original)) + 1));

            if (strtolower($extension) != "png" && strtolower($extension) != "jpg" && strtolower($extension) != "gif") {
                $error   = true;
                $mensaje = $textos["ERROR_FORMATO_IMAGEN"];
                $mensaje = "Error en formato firma digital";

            } else {
                list($ancho, $alto, $tipo) = getimagesize($temporal);

                $datos   = array(
                    "categoria"   => "3",
                    "id_asociado" => $forma_codigo,
                    "contenido"   => file_get_contents($temporal),
                    "tipo"        => $tipo,
                    "extension"   => $extension,
                    "ancho"       => $ancho,
                    "alto"        => $alto
                );

                $consulta = SQL::eliminar("imagenes", "categoria = '3' AND id_asociado = '$forma_id'");
                $insertar = SQL::insertarArchivo("imagenes", $datos);
            }
        }
        
        if ($forma_id == $sesion_codigo_usuario && md5($forma_contrasena1) != $sesion_contrasena){
            $mensaje = $textos["INGRESAR"];
        }

    } else {
        $error   = true;
        $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
    }
}

/*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
$respuesta    = array();
$respuesta[0] = $error;
$respuesta[1] = $mensaje;
HTTP::enviarJSON($respuesta);
?>
