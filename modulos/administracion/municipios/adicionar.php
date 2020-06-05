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
    $indice_pais   = "CO";
    $error         = "";
    $titulo        = $componente->nombre;
    $paises        = HTML::generarDatosLista("paises", "codigo_iso", "nombre","codigo_iso != ''");
    $departamentos = HTML::generarDatosLista("departamentos", "codigo_dane_departamento", "nombre", "codigo_iso = '".$indice_pais."'");

    /*** Definición de pestañas ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("*pais", $textos["PAIS"], $paises,$indice_pais, array("onChange" => "recargarLista('pais','departamento');", "title" => $textos["AYUDA_PAIS"]))
        ),
        array(
            HTML::listaSeleccionSimple("*departamento", $textos["DEPARTAMENTO"], $departamentos,"", array("title" => $textos["AYUDA_DEPARTAMENTO"]))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, "", array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*codigo_dane_municipio", $textos["CODIGO_DANE"], 3, 3, "", array("title" => $textos["AYUDA_CODIGO_DANE"], "onblur" => "validarItem(this);")),
            HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 4, 4, "", array("title" => $textos["AYUDA_CODIGO_INTERNO"], "onblur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
        ),
        array(
            HTML::campoTextoCorto("comunas", $textos["COMUNAS"], 3, 3, 0, array("title" => $textos["AYUDA_COMUNAS"], "onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::marcaChequeo("capital", $textos["CAPITAL"], 1)
        )
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Recargar un elemento del formulario ***/
} elseif (!empty($url_recargar)) {

    if ($url_elemento == "departamento") {
       $respuesta = HTML::generarDatosLista("departamentos", "codigo_dane_departamento", "nombre", "codigo_iso = '$url_origen'","","nombre");
    }

    HTTP::enviarJSON($respuesta);

/*** Validación en línea de los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("municipios", "nombre", $url_valor,"nombre !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_NOMBRE"];
        }
    }

    if ($url_item == "codigo_dane" && $url_valor) {
        $existe = SQL::existeItem("municipios", "codigo_dane_municipio", $url_valor,"codigo_dane_municipio !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO_DANE"];

        } elseif (!Cadena::validarNumeros($url_valor, 3, 3)) {
            $respuesta =  $textos["ERROR_FORMATO_CODIGO_DANE"];
        }
    }

    if ($url_item == "codigo_interno" && $url_valor) {
        $existe = SQL::existeItem("municipios", "codigo_interno", $url_valor, "codigo_interno != '0'");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO_INTERNO"];

        } elseif (!Cadena::validarNumeros($url_valor)) {
            $respuesta =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_departamento)){
        $error   =  true;
        $mensaje = $textos["DEPARTAMENTO_VACIO"];
    }elseif(empty($forma_nombre)){
        $error   =  true;
        $mensaje = $textos["NOMBRE_VACIO"];
    }elseif(empty($forma_codigo_dane_municipio)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
    } elseif (SQL::existeItem("municipios", "nombre",$forma_nombre, "codigo_dane_departamento = '$forma_departamento'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];
    } elseif (!empty($forma_codigo_dane_municipio) && SQL::existeItem("municipios", "codigo_dane_municipio", $forma_codigo_dane_municipio, "codigo_dane_departamento = '$forma_departamento'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_DANE"];
    } elseif (!empty($forma_codigo_interno) && SQL::existeItem("municipios", "codigo_interno", $forma_codigo_interno, "codigo_dane_departamento = '$forma_departamento'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_INTERNO"];
    } elseif (!empty($forma_codigo_interno) && !Cadena::validarNumeros($forma_codigo_interno)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];
    } elseif (!empty($forma_comunas) && !Cadena::validarNumeros($forma_comunas)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_COMUNAS"];
    }elseif(isset($forma_capital) && SQL::existeItem("municipios","codigo_iso",$forma_pais,"codigo_dane_departamento = '$forma_departamento' AND capital='1'")){
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CAPITAL"];
    }else{

        if(!isset($forma_capital)) {
        $forma_capital=0;
         }


        /*** Insertar datos ***/
        $datos = array(
            "codigo_iso"               => $forma_pais,
            "codigo_dane_departamento" => $forma_departamento,
            "codigo_dane_municipio"    => $forma_codigo_dane_municipio,
            "nombre"                   => $forma_nombre,
            "codigo_interno"           => $forma_codigo_interno,
            "comunas"                  => $forma_comunas,
            "capital"                  => $forma_capital
        );
        $insertar = SQL::insertar("municipios", $datos);

        /*** Error de inserción ***/
        if (!$insertar) {
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
