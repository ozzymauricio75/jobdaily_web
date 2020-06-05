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
        $llave_primaria =$llave_primaria[0]."|".$llave_primaria[1]."|".$llave_primaria[2]."|B|".$llave_primaria[3]; //str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT)
        $existe = SQL::existeItem("buscador_barrios","nombre",$url_valor_item,"id = '$llave_primaria'");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_NOMBRE"];

        }
    }elseif($url_item=="codigo_dane_localidad" && !empty($url_valor))
    {
         $llave_primaria=explode("|", $url_valor);
        $llave_primaria =$llave_primaria[0]."|".$llave_primaria[1]."|".$llave_primaria[2]."|B|".$llave_primaria[3]; //str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT)
        $existe = SQL::existeItem("buscador_barrios","codigo_localidad",$url_valor_item,"id = '$llave_primaria'");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO_DANE"];

        }
    }elseif($url_item=="codigo_interno" && !empty($url_valor))
    {
         $llave_primaria=explode("|", $url_valor);
          //str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT)
        $existe = SQL::existeItem("localidades","codigo_interno",$url_valor_item,"codigo_iso='$llave_primaria[0]' AND codigo_dane_departamento='$llave_primaria[1]' AND codigo_dane_municipio='$llave_primaria[2]' AND tipo='B'");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];

        }
    }

      HTTP::enviarJSON($mensaje);
}



/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error         = "";
    $paisInicial   = "CO";
    $titulo        = $componente->nombre;
    $paises        = HTML::generarDatosLista("paises", "codigo_iso", "nombre","codigo_iso != ''");
    $departamentos = HTML::generarDatosLista("departamentos", "codigo_dane_departamento", "nombre", " codigo_iso= '$paisInicial'");
    $municipios    = HTML::generarDatosLista("municipios", "codigo_dane_municipio", "nombre", "codigo_iso= '".$paisInicial."' AND codigo_dane_departamento = '".array_shift(array_keys($departamentos))."'");

    $campo_llave_primaria="pais|departamento|municipio|codigo_dane_localidad";

    /*** Definición de pestañas ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("*pais", $textos["PAIS"], $paises,$paisInicial, array("title" => $textos["AYUDA_PAIS"], "onChange" => "recargarLista('pais','departamento');recargarListaMunicipios();"))
        ),
        array(
            HTML::listaSeleccionSimple("*departamento", $textos["DEPARTAMENTO"], $departamentos,"", array("title" => $textos["AYUDA_DEPARTAMENTO"], "onChange" => "recargarListaMunicipios();"))
        ),
        array(
            HTML::listaSeleccionSimple("*municipio", $textos["MUNICIPIO"], $municipios, "", array("title" => $textos["AYUDA_MUNICIPIO"]))
        ),
        array(
            HTML::campoTextoCorto("*codigo_dane_localidad", $textos["CODIGO_DANE"], 4, 4, "", array("title" => $textos["AYUDA_CODIGO_DANE"],"onBlur" => "validarItemsllaves(this,'$campo_llave_primaria')"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 255, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItemsllaves(this,'$campo_llave_primaria')"))
        ),
        array(
            HTML::campoTextoCorto("codigo_interno", $textos["CODIGO_INTERNO"], 4, 4, "", array("title" => $textos["AYUDA_CODIGO_INTERNO"],"onBlur" => "validarItemsllaves(this,'$campo_llave_primaria')", "onKeyPress" => "return campoEntero(event)")),
        ),
        array(
            HTML::campoTextoCorto("estrato", $textos["ESTRATO"], 2, 2, "", array("title" => $textos["AYUDA_ESTRATO"], "onKeyPress" => "return campoEntero(event)")),
            HTML::campoTextoCorto("comuna", $textos["COMUNA"], 2, 2, "", array("title" => $textos["AYUDA_COMUNA"], "onKeyPress" => "return campoEntero(event)"))
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
       $respuesta = HTML::generarDatosLista("departamentos", "codigo_dane_departamento", "nombre", "codigo_iso = '$url_origen'");
    }

    if ($url_elemento == "municipio") {
       $respuesta = HTML::generarDatosLista("municipios", "codigo_dane_municipio", "nombre", "codigo_dane_departamento = '".$url_codigo_dane_departamento."' AND codigo_iso = '".$url_codigo_iso."'");
    }

    HTTP::enviarJSON($respuesta);

}elseif (!empty($forma_procesar)) {
    //echo var_dump($forma_municipio);
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

   $condicion = "codigo_iso = '$forma_pais' AND codigo_dane_departamento='$forma_departamento' AND codigo_dane_municipio = '$forma_municipio' AND tipo = 'B' AND codigo_dane_localidad='$forma_codigo_dane_localidad'";

    /*** Validar el ingreso de los datos requeridos ***/
    if (empty($forma_nombre)) {
        $error   = true;
        $mensaje = $textos["ERROR_NOMBRE_VACIO"];

    } elseif (empty($forma_codigo_dane_localidad)) {
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_DANE"];

    }  elseif (SQL::existeItem("localidades", "nombre", $forma_nombre,$condicion)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

    } elseif (!empty($forma_codigo_interno) && SQL::existeItem("localidades","codigo_interno",$forma_codigo_interno,"codigo_iso='$forma_pais' AND codigo_dane_departamento='$forma_departamento' AND codigo_dane_municipio='$forma_municipio' AND tipo='B'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_INTERNO"];

    } elseif (!empty( $forma_codigo_dane_localidad) && SQL::existeItem("localidades", "codigo_dane_localidad", $forma_codigo_dane_localidad,$condicion)) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_CODIGO_DANE"];

    }elseif (!empty($forma_codigo_interno) && !Cadena::validarNumeros($forma_codigo_interno)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_CODIGO_INTERNO"];

    } elseif (!empty($forma_comuna) && !Cadena::validarNumeros($forma_comuna)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_COMUNAS"];
    } elseif (!empty($forma_estrato) && !Cadena::validarNumeros($forma_estrato)) {
        $error   = true;
        $mensaje =  $textos["ERROR_FORMATO_ESTRACTO"];
    } else {
        /*** Insertar datos ***/
        $datos = array(
            "codigo_iso"               => $forma_pais,
            "codigo_dane_departamento" => $forma_departamento,
            "codigo_dane_municipio"    => $forma_municipio,
            "codigo_dane_localidad"    => $forma_codigo_dane_localidad,
            "nombre"                   => $forma_nombre,
            "codigo_interno"           => $forma_codigo_interno,
            "comuna"                   => $forma_comuna,
            "estrato"                  => $forma_estrato,
            "tipo"                     => 'B',
            "codigo_dane_localidad"    => $forma_nombre
        );
        $insertar = SQL::insertar("localidades", $datos);

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
