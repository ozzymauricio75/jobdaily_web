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

        $id_           = explode("|", $url_id); // genera un arreglo en donde se obtienen las llaves primarias
        $vistaConsulta = "buscador_departamentos";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_dane= '$id_[1]'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;
        $paises        = HTML::generarDatosLista("paises","codigo_iso", "nombre","codigo_iso !=''");

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*pais", $textos["PAIS"], $paises,$id_[0], array("title" => $textos["AYUDA_PAIS"]))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("codigo_dane_departamento", $textos["CODIGO_DANE"], 2, 2, $datos->codigo_dane, array("title" => $textos["AYUDA_CODIGO_DANE"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 3, 3, $datos->codigo_interno, array("title" => $textos["AYUDA_CODIGO_INTERNO"], "onBlur" => "validarItem(this);"))
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

/*** Validar los datos provenientes del formulario ***/
}elseif (!empty($url_validar)) {


    $id_       = explode("|", $url_id); // genera un arreglo en donde se obtienen las llaves primarias
    $respuesta = "";

    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("departamentos", "nombre", $url_valor, "codigo_dane_departamento != '$id_[1]' AND nombre !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_NOMBRE"];
        }
    }

    if ($url_item == "codigo_dane_departamento" && $url_valor) {
        $existe = SQL::existeItem("departamentos", "codigo_dane_departamento", $url_valor, "codigo_dane_departamento != '$id_[1]' AND codigo_dane_departamento !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO_DANE"];

        } 
    }

    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
}elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_pais)){
	    $error   = true;
            $mensaje = $textos["PAIS_VACIO"];
	}elseif(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
	}elseif(empty($forma_codigo_dane_departamento)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
    } elseif (!Cadena::validarNumeros($forma_codigo_dane_departamento, 2, 2)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_DANE"];
    } elseif (!empty($forma_codigo_interno) && !Cadena::validarNumeros($forma_codigo_interno)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_iso"               => $forma_pais,
            "nombre"                   => $forma_nombre,
            "codigo_dane_departamento" => $forma_codigo_dane_departamento,
            "codigo_interno"           => $forma_codigo_interno
        );

        $id_forma = explode("|", $forma_id); // genera un arreglo en donde se obtienen las llaves primarias
        //echo var_dump($id_forma);
        $consulta = SQL::modificar("departamentos", $datos, "codigo_dane_departamento = '$id_forma[1]'");

        /*** Error de inserción ***/
        if (!$consulta) {
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
