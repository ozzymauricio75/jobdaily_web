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

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
    exit;
}


// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a modificar
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error         = "";
        $titulo        = $componente->nombre;

        // Obtener los datos de la tabla de terceros
        $vistaConsulta = "entidades_parafiscales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        // Definicion de pestana basica
        if ($datos->pension == '1'){
            $pension = true;
        } else {
            $pension = false;
        }
        if ($datos->salud == '1'){
            $salud = true;
        } else {
            $salud = false;
        }
        if ($datos->cesantias == '1'){
            $cesantias = true;
        } else {
            $cesantias = false;
        }
        if ($datos->caja == '1'){
            $caja = true;
        } else {
            $caja = false;
        }
        if ($datos->sena == '1'){
            $sena = true;
        } else {
            $sena = false;
        }
        if ($datos->icbf == '1'){
            $icbf = true;
        } else {
            $icbf = false;
        }
        if ($datos->riesgos_profesionales == '1'){
            $riesgos_profesionales = true;
        } else {
            $riesgos_profesionales = false;
        }

        $nombre_tercero = SQL::obtenerValor("seleccion_terceros","nombre","id = '$datos->documento_identidad_tercero'");
        $nombre_tercero = explode("|",$nombre_tercero);
        $nombre_tercero = $nombre_tercero[0];

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 10, 8, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);", "onkeypress"=>"return campoEntero(event)")),
                HTML::campoOculto("llave", $datos->codigo)
            ),
            array(
                HTML::campoTextoCorto("codigo_ruaf", $textos["CODIGO_RUAF"], 10, 8, $datos->codigo_ruaf, array("title" => $textos["AYUDA_CODIGO_RUAF"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 50, 100, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["NOMBRE_TERCERO"], 50, 100, $nombre_tercero, array("title" => $textos["AYUDA_NOMBRE_TERCERO"], "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_tercero",$datos->documento_identidad_tercero)
            ),
            array(
                HTML::marcaChequeo("cesantias", $textos["CESANTIAS"],1,$cesantias),
                HTML::marcaChequeo("caja", $textos["CAJA"],1,$caja),
                HTML::marcaChequeo("pension", $textos["PENSION"],1,$pension),
                HTML::marcaChequeo("salud", $textos["SALUD"],1,$salud),
                HTML::marcaChequeo("sena", $textos["SENA"],1,$sena),
                HTML::marcaChequeo("icbf", $textos["ICBF"],1,$icbf),
                HTML::marcaChequeo("riesgos_profesionales", $textos["RIESGOS_PROFESIONALES"],1,$riesgos_profesionales)
            )
        );
        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem($url_id);", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

    $respuesta = "";
    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("entidades_parafiscales", "codigo", $url_valor,"codigo !=0 AND codigo !='$url_id'");
        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }

    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("entidades_parafiscales", "codigo_ruaf", $url_valor,"codigo !=0 AND codigo !='$url_id'");
        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO_RUAF"];
        }
    }

    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("entidades_parafiscales", "nombre", $url_valor,"nombre !='' AND nombre !='$url_id'");
        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE"];
        }
    }
    HTTP::enviarJSON($respuesta);

// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    if(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO"];
    }else if(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["ERROR_NOMBRE"];
    } else if($forma_documento_identidad_tercero == ""){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD"];
    } else if(SQL::existeItem("entidades_parafiscales","codigo","$forma_codigo","codigo != 0 AND codigo != $forma_llave")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    } else if(SQL::existeItem("entidades_parafiscales","codigo_ruaf","$forma_codigo_ruaf","codigo != 0 AND codigo != $forma_llave")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_RUAF"];
    } else if(SQL::existeItem("entidades_parafiscales","nombre","$forma_nombre","codigo != 0 AND codigo != $forma_llave")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
    } else {

        if(isset($forma_salud)){
            $forma_salud = "1";
        }else{
            $forma_salud = "0";
        }
        if(isset($forma_pension)){
            $forma_pension = "1";
        }else{
            $forma_pension = "0";
        }
        if(isset($forma_cesantias)){
            $forma_cesantias = "1";
        }else{
            $forma_cesantias = "0";
        }
        if(isset($forma_caja)){
            $forma_caja = "1";
        }else{
            $forma_caja = "0";
        }
        if(isset($forma_icbf)){
            $forma_icbf = "1";
        }else{
            $forma_icbf = "0";
        }
        if(isset($forma_sena)){
            $forma_sena = "1";
        }else{
            $forma_sena = "0";
        }

        if(isset($forma_riesgos_profesionales)){
            $forma_riesgos_profesionales = "1";
        }else{
            $forma_riesgos_profesionales = "0";
        }

        $datos = array (
            "codigo"                      => $forma_codigo,
            "codigo_ruaf"                 => $forma_codigo_ruaf,
            "nombre"                      => $forma_nombre,
            "documento_identidad_tercero" => $forma_documento_identidad_tercero,
            "salud"                       => $forma_salud,
            "pension"                     => $forma_pension,
            "cesantias"                   => $forma_cesantias,
            "caja"                        => $forma_caja,
            "icbf"                        => $forma_icbf,
            "sena"                        => $forma_sena,
            "riesgos_profesionales"       => $forma_riesgos_profesionales
        );

        $modificar = SQL::modificar("entidades_parafiscales", $datos, "codigo = '$forma_llave'");

        // Error de modificacion
        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
