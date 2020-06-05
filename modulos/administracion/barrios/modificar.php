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

    if(isset($url_validarItemsllaves))
{
    $mensaje="";
    if($url_item=="nombre" && !empty($url_valor))
    {
        $llave_primaria=explode("|", $url_valor);
        $llave_primaria =$llave_primaria[0]."|".$llave_primaria[1]."|".$llave_primaria[2]."|B|"; //str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT)
        $existe = SQL::existeItem("buscador_barrios","nombre",$url_valor_item,"id != '$url_id' AND nombre!='' AND codigo='$llave_primaria'");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_NOMBRE"];

        }
    }elseif($url_item=="codigo_dane_localidad" && !empty($url_valor))
    {
         $llave_primaria=explode("|", $url_valor);
        $llave_primaria =$llave_primaria[0]."|".$llave_primaria[1]."|".$llave_primaria[2]."|B|".$llave_primaria[3]; //str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT)
        $existe = SQL::existeItem("buscador_barrios","codigo_localidad",$url_valor_item,"id != '$url_id' AND codigo_localidad!=''");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO_DANE"];

        }
    }elseif($url_item=="codigo_interno" && !empty($url_valor))
    {
         $llave_primaria=explode("|", $url_valor);
        $llave_primaria =$llave_primaria[0]."|".$llave_primaria[1]."|".$llave_primaria[2]."|B|".$llave_primaria[3]; //str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT)
        $existe = SQL::existeItem("buscador_barrios","codigo_interno",$url_valor_item,"id != '$url_id' codigo_interno!=0");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];

        }
    }

      HTTP::enviarJSON($mensaje);
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $id_                      = explode("|",$url_id);
        $codigo_iso               = $id_[0];
        $codigo_dane_departamento = $id_[1];
        $codigo_dane_municipio    = $id_[2];
        $tipo                     = $id_[3];
        $codigo_dane_localidad    = $id_[4];

        $vistaConsulta = "localidades";
        $condicion     = "codigo_iso ='$codigo_iso' AND codigo_dane_departamento='$codigo_dane_departamento'";
        $condicion    .= " AND codigo_dane_municipio ='$codigo_dane_municipio' AND tipo='$tipo' AND codigo_dane_localidad='$codigo_dane_localidad'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        $departamento  = SQL::obtenerValor("municipios", "codigo_dane_municipio", "codigo_dane_municipio = ' $codigo_dane_municipio'");
        $paises        = HTML::generarDatosLista("paises", "codigo_iso", "nombre","codigo_iso !=''");

        $departamentos = HTML::generarDatosLista("departamentos", "codigo_dane_departamento", "nombre", "codigo_iso = '$codigo_iso'");
        $municipios    = HTML::generarDatosLista("municipios", "codigo_dane_municipio", "nombre");

        $campo_llave_primaria="pais|departamento|municipio|codigo_dane_localidad";

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*pais", $textos["PAIS"], $paises,$codigo_iso, array("title" => $textos["AYUDA_PAIS"], "onChange" => "recargarLista('pais','departamento');recargarListaMunicipios();"))
            ),
            array(
                HTML::listaSeleccionSimple("*departamento", $textos["DEPARTAMENTO"], $departamentos, $codigo_dane_departamento, array("title" => $textos["AYUDA_DEPARTAMENTO"], "onChange" => "recargarListaMunicipios();"))
            ),
            array(
                HTML::listaSeleccionSimple("*municipio", $textos["MUNICIPIO"], $municipios, $codigo_dane_municipio, array("title" => $textos["AYUDA_MUNICIPIO"]))
            ),
            array(
                HTML::campoTextoCorto("*codigo_dane_localidad", $textos["CODIGO_DANE"], 4, 4, $codigo_dane_localidad, array("title" => $textos["AYUDA_CODIGO_DANE"],"validarItemsllaves(this,'$campo_llave_primaria','$url_id')"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItemsllaves(this,'$campo_llave_primaria','$url_id')"))
            ),
            array(
                HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 4, 4, $datos->codigo_interno, array("title" => $textos["AYUDA_CODIGO_INTERNO"], "onBlur" => "validarItemsllaves(this,'$campo_llave_primaria','$url_id')", "onKeyPress" => "return campoEntero(event)")),
            ),
            array(
                HTML::campoTextoCorto("estrato", $textos["ESTRATO"], 2, 2, $datos->estrato, array("title" => $textos["AYUDA_ESTRATO"], "onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("comuna", $textos["COMUNA"], 2, 2, $datos->comuna, array("title" => $textos["AYUDA_COMUNA"], "onKeyPress" => "return campoEntero(event)"))
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

/*** Recargar un elemento del formulario ***/
} elseif (!empty($url_recargar)) {

    if ($url_elemento == "departamento") {
       $respuesta = HTML::generarDatosLista("departamentos", "codigo_dane_departamento", "nombre", "codigo_iso = '$url_origen'");
    }

    if ($url_elemento == "municipio") {
       $respuesta = HTML::generarDatosLista("municipios", "codigo_dane_municipio", "nombre", "codigo_dane_departamento = '".$url_codigo_dane_departamento."' AND codigo_iso = '".$url_codigo_iso."'");
    }

    HTTP::enviarJSON($respuesta);

/*** Validación en línea de los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $id_                      = explode("|",$forma_id);
    $codigo_iso               = $id_[0];
    $codigo_dane_departamento = $id_[1];
    $codigo_dane_municipio    = $id_[2];
    $tipo                     = $id_[3];
    $codigo_dane_localidad    = $id_[4];

    $llave_primaria =$codigo_iso."|".$codigo_dane_departamento."|".$codigo_dane_municipio."|".$tipo;

    /*** Validar el ingreso de los datos requeridos ***/
     if (empty($forma_nombre)) {
        $error   = true;
        $mensaje = $textos["ERROR_NOMBRE_VACIO"];

    } elseif (empty($forma_codigo_dane_localidad) && $forma_codigo_dane_localidad !=0) {
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_DANE"];

    }  elseif (SQL::existeItem("buscador_barrios","nombre",$forma_nombre,"id != '$forma_id' AND codigo='$llave_primaria'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

    } elseif (!empty($forma_codigo_interno) && SQL::existeItem("buscador_barrios","codigo_interno",$forma_codigo_interno,"id != '$forma_id'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_INTERNO"];

    } elseif (!empty( $forma_codigo_dane_localidad) && SQL::existeItem("buscador_barrios","codigo_localidad",$forma_codigo_dane_localidad,"id != '$forma_id'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_DANE"];

    } elseif (!empty($forma_codigo_interno) && !Cadena::validarNumeros($forma_codigo_interno)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];

    } else {

        if (!isset($forma_codigo_dane_localidad)){
            $forma_codigo_dane_localidad = 0;
        }
        $datos = array(
            "codigo_iso"               => $forma_pais,
            "codigo_dane_departamento" => $forma_departamento,
            "codigo_dane_municipio"    => $forma_municipio,
            "codigo_dane_localidad"    => $forma_codigo_dane_localidad,
            "nombre"                   => $forma_nombre,
            "codigo_interno"           => $forma_codigo_interno,
            "comuna"                   => $forma_comuna,
            "estrato"                  => $forma_estrato,
            "tipo"                     => 'B'
        );

        //$consulta = SQL::modificar("localidades", $datos, "id = '$forma_id'");
        $condicion     = "codigo_iso ='$codigo_iso' AND codigo_dane_departamento='$codigo_dane_departamento'";
        $condicion    .= " AND codigo_dane_municipio ='$codigo_dane_municipio' AND tipo='$tipo' AND codigo_dane_localidad='$codigo_dane_localidad'";
        $consulta = SQL::modificar("localidades", $datos, $condicion);

        /*** Error de actualización ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
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
}
?>
