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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
    exit;
}


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $consulta_terceros = SQL::seleccionar(array("terceros"), array("*"),"documento_identidad !=0");
    $codigo = SQL::obtenerValor("entidades_parafiscales","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo=1;
    }
    
    if (SQL::filasDevueltas($consulta_terceros)){
        /*** Definición de pestaña Basica ***/
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 10, 8, $codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);", "onkeypress"=>"return campoEntero(event)"))
            ),
            array(
                HTML::campoTextoCorto("codigo_ruaf", $textos["CODIGO_RUAF"], 10, 50, "", array("title" => $textos["AYUDA_CODIGO_RUAF"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 50, 100, "", array("title" => $textos["AYUDA_NOMBRE"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["NOMBRE_TERCERO"], 40, 255, "", array("title" => $textos["AYUDA_NOMBRE_TERCERO"],  "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_tercero", 0)
            ),
            array(
                HTML::marcaChequeo("cesantias", $textos["CESANTIAS"],1,false),
                HTML::marcaChequeo("caja", $textos["CAJA"],1,false),
                HTML::marcaChequeo("pension", $textos["PENSION"],1,false),
                HTML::marcaChequeo("salud", $textos["SALUD"],1,false),
                HTML::marcaChequeo("sena", $textos["SENA"],1,false),
                HTML::marcaChequeo("icbf", $textos["ICBF"],1,false),
                HTML::marcaChequeo("riesgos_profesionales", $textos["RIESGOS_PROFESIONALES"],1,false)
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {

        $error = $textos["ERROR_TERCEROS"];
        $titulo    = "";
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("entidades_parafiscales", "codigo", $url_valor,"codigo !=0");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO"];
        }
    }

    if ($url_item == "codigo_ruaf" && $url_valor) {
        $existe = SQL::existeItem("entidades_parafiscales", "codigo_ruaf", $url_valor,"codigo !=0");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_CODIGO_RUAF"];
        }
    }

    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("entidades_parafiscales", "nombre", $url_valor,"nombre !=''");

        if ($existe) {
            $respuesta =  $textos["ERROR_EXISTE_NOMBRE"];
        }
    }
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if (empty($forma_codigo)){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO"];

    } else if (empty($forma_nombre)){
        $error = true;
        $mensaje = $textos["ERROR_NOMBRE"];

    } else if(!isset($forma_salud) && !isset($forma_pension) && !isset($forma_cesantias) && !isset($forma_caja) && !isset($forma_icbf) && !isset($forma_sena)){

        $error = true;
        $mensaje = $textos["ERROR_TIPO_ENTIDAD"];
        
    } else if($existe = SQL::existeItem("entidades_parafiscales","codigo",$forma_codigo)){

        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

    } else if($existe = SQL::existeItem("entidades_parafiscales","codigo_ruaf",$forma_codigo_ruaf,"codigo!=0")){

        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_RUAF"];

    } else if (empty($forma_documento_identidad_tercero)){
        $error = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD"];

    } else {

        if(!isset($forma_salud)){
            $forma_salud = "0";
        }
        if(!isset($forma_pension)){
            $forma_pension = "0";
        }
        if(!isset($forma_cesantias)){
            $forma_cesantias = "0";
        }
        if(!isset($forma_caja)){
            $forma_caja = "0";
        }
        if(!isset($forma_icbf)){
            $forma_icbf = "0";
        }
        if(!isset($forma_sena)){
            $forma_sena = "0";
        }
        if(!isset($forma_riesgos_profesionales)){
            $forma_riesgos_profesionales = "0";
        }
        /*** Insertar datos ***/
        $datos = array (
            "codigo"                      => $forma_codigo,
            "codigo_ruaf"                 => $forma_codigo_ruaf,
            "nombre"                      => $forma_nombre,
            "documento_identidad_tercero" => $forma_documento_identidad_tercero,
            "salud"    	                  => $forma_salud,
            "pension"  	                  => $forma_pension,
            "cesantias"	                  => $forma_cesantias,
            "caja"                        => $forma_caja,
            "icbf"                        => $forma_icbf,
            "sena"                        => $forma_sena,
            "riesgos_profesionales"       => $forma_riesgos_profesionales
        );
      
        $insertar = SQL::insertar("entidades_parafiscales", $datos);

        /*** Error de insercón ***/
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
