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

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["NOMBRE"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            array(
                HTML::mostrarDato("usuario", $textos["USUARIO"], $datos->usuario)
            ),
            array(
                HTML::campoTextoClave("*contrasena", $textos["CONTRASENA_ACTUAL"], 12, 12, "", array("title" => $textos["AYUDA_CONTRASENA_ACTUAL"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoClave("*contrasena1", $textos["CONTRASENA"], 12, 12, "", array("title" => $textos["AYUDA_CONTRASENA1"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoClave("*contrasena2", $textos["VERIFICAR_CONTRASENA"], 12, 12, "", array("title" => $textos["AYUDA_CONTRASENA2"], "onBlur" => "validarItem(this);"))
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

} elseif (!empty($url_validar)) {

    /*** Validar datos ***/
    if ($url_item == "contrasena") {
        
        if (!$url_valor){
            $contrasena = md5($url_valor);
            $existe = SQL::existeItem("usuarios", "contrasena", $contrasena,"codigo='$url_id'");
            if (!$existe) {
                HTTP::enviarJSON($textos["ERROR_CONTRASEÑA_INCORRECTA"]);
            }
        }
    }
    exit();
/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Validar el ingreso de los datos requeridos ***/
    $contrasena_actual          = md5($forma_contrasena);
    $contrasena_actual_correcta = SQL::existeItem("usuarios", "contrasena", $contrasena_actual, "codigo='$forma_id'");

    if (empty($forma_contrasena)){
        $error   = true;
        $mensaje = $textos["ERROR_CONTRASENA_VACIO"];

    } else if (empty($forma_contrasena1)){
        $error   = true;
        $mensaje = $textos["ERROR_CONTRASENA1_VACIO"];

    } else if (empty($forma_contrasena2){
        $error   = true;
        $mensaje = $textos["ERROR_CONTRASENA2_VACIO"];

    } else if (!$contrasena_actual_correcta){
        $error   = true;
        $mensaje = $textos["ERROR_CONTRASEÑA_INCORRECTA"];

    } else if (stristr($forma_contrasena1,$forma_contrasena2) == false){
        $error   = true;
        $mensaje = $textos["ERROR_VERIFIQUE_CONTRASENA"];

    } else {

        $datos = array(
            "contrasena" => md5($forma_contrasena1)
        );

        $modificar = SQL::modificar("usuarios", $datos, "codigo = '$forma_id'");
        if (!$modificar){
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
