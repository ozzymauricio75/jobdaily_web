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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
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

        /*** Definici�n de pesta�as ***/
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
    exit();

} elseif (!empty($url_validar)) {

    /*** Validar datos ***/
    if ($url_item == "contrasena") {
        
        if (!$url_valor){
            $contrasena = md5($url_valor);
            $existe = SQL::existeItem("usuarios", "contrasena", $contrasena,"codigo='$url_id'");
            if (!$existe) {
                HTTP::enviarJSON($textos["ERROR_CONTRASE�A_INCORRECTA"]);
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
        $mensaje = $textos["ERROR_CONTRASE�A_INCORRECTA"];

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

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
?>
