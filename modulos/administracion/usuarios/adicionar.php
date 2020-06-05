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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $codigo = SQL::obtenerValor("usuarios","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo = 1;
    }
    // Definicion de pestanas
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("title" => $textos["AYUDA_CODIGO"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 50, "", array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*correo", $textos["CORREO"], 30, 255, "", array("title" => $textos["AYUDA_CORREO"]))
        ),
        array(
            HTML::campoTextoCorto("*usuario", $textos["USUARIO"], 12, 12, "", array("title" => $textos["AYUDA_USUARIO"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoClave("*contrasena1", $textos["CONTRASENA"], 12, 12, "", array("title" => $textos["AYUDA_CONTRASENA1"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoClave("*contrasena2", $textos["VERIFICAR_CONTRASENA"], 12, 12, "", array("title" => $textos["AYUDA_CONTRASENA2"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::selectorArchivo("firma_digital", $textos["FIRMA_DIGITAL"], array("title" => $textos["AYUDA_FIRMA_DIGITAL"]))
        )
    );

    $formularios["PESTANA_ACCESO"] = array(
        array(
            HTML::marcaChequeo("cambiar_contrasena", $textos["CAMBIAR_CONTRASENA"], 1, true, array("title" => $textos["AYUDA_CAMBIAR_CONTRASENA"]))
        ),
        array(
            HTML::campoTextoCorto("cambio_contrasena_minimo", $textos["CAMBIO_CONTRASENA_MINIMO"], 3, 3, "0", array("title" => $textos["AYUDA_CAMBIO_CONTRASENA_MINIMO"], "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("cambio_contrasena_maximo", $textos["CAMBIO_CONTRASENA_MAXIMO"], 3, 3, "0", array("title" => $textos["AYUDA_CAMBIO_CONTRASENA_MAXIMO"], "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("fecha_expiracion", $textos["FECHA_EXPIRACION"], 10, 10, "", array("class" => "selectorFecha", "title" => $textos["AYUDA_FECHA_EXPIRACION"]))
        )
    );

    $formularios["PESTANA_IMAGEN"] = array(
        array(
            HTML::selectorArchivo("imagen", $textos["IMAGEN"], array("title" => $textos["AYUDA_IMAGEN"]))
        )
    );

    // Definicion de botones
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    exit();

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

    // Validar datos
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("usuarios", "codigo", $url_valor,"codigo>0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }
    if ($url_item == "usuario") {
        $existe = SQL::existeItem("usuarios", "usuario", $url_valor);
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_USUARIO"]);
        }
    }
    exit();

// Adicionar los datos provenientes del formulario
}
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    // Validar el ingreso de los datos requeridos
    if(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
    } else if(empty($forma_usuario)){
        $error   = true;
        $mensaje = $textos["USUARIO_VACIO"];
    }elseif(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
    }elseif(empty($forma_correo)){
        $error   = true;
        $mensaje = $textos["CORREO_VACIO"];
    }elseif(empty($forma_contrasena1)){
        $error   = true;
        $mensaje = $textos["CONTRASENA1_VACIO"];
    }elseif(empty($forma_contrasena2)){
        $error   = true;
        $mensaje = $textos["CONTRASENA2_VACIO"];
    }elseif($existe = SQL::existeItem("usuarios", "codigo", $forma_codigo,"codigo > 0")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    }elseif($existe = SQL::existeItem("usuarios", "usuario", $forma_usuario,"usuario !=''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_USUARIO"];
    } elseif (!Cadena::validarCorreo($forma_correo)) {
        $error   = true;
        $mensaje =  $textos["ERROR_SINTAXIS_CORREO"];
    } elseif (($forma_contrasena1) != ($forma_contrasena2)) {
        $error   = true;
        $mensaje =  $textos["ERROR_CONTRASENA"];
    } else {
        // Insertar datos
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

        $insertar = SQL::insertar("usuarios", $datos);

        // Error de insercion
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        } else {

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
                        "categoria"   => 1,
                        "id_asociado" => $forma_codigo,
                        "contenido"   => file_get_contents($temporal),
                        "tipo"        => $tipo,
                        "extension"   => $extension,
                        "ancho"       => $ancho,
                        "alto"        => $alto
                    );

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

                } else {
                    list($ancho, $alto, $tipo) = getimagesize($temporal);

                    $datos   = array(
                        "categoria"   => 3,
                        "id_asociado" => $forma_codigo,
                        "contenido"   => file_get_contents($temporal),
                        "tipo"        => $tipo,
                        "extension"   => $extension,
                        "ancho"       => $ancho,
                        "alto"        => $alto
                    );

                    $insertar = SQL::insertarArchivo("imagenes", $datos);
                }
            }
        }

    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
?>
