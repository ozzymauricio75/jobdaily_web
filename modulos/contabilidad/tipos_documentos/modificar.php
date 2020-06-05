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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "tipos_documentos";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        $error         = "";
        $titulo        = $componente->nombre;

        $manejo = array(
            "1" => $textos["NO_MANEJA"],
            "2" => $textos["MANEJO_AUTOMATICO"],
            "3" => $textos["CONSECUTIVO_MES"]
        );
        $control = array(
            "0" => $textos["NO_IMPRIME"],
            "1" => $textos["IMPRIME"],
        );

        /*** Consulta tipos de comprobante ***/
        $consulta_tipos_comprobantes = SQL::seleccionar(array("tipos_comprobantes"),array("codigo","descripcion"),"codigo > 0");

        if (SQL::filasDevueltas($consulta_tipos_comprobantes)) {

            while ($datos_tipos_comprobantes = SQL::filaEnObjeto($consulta_tipos_comprobantes)) {
                $tipos_comprobantes[$datos_tipos_comprobantes->codigo] = $datos_tipos_comprobantes->descripcion;
            }
        }

        $sentidos_contables = array(
            "0" => $textos["NO_APLICA"],
            "1" => $textos["DEBITO"],
            "2" => $textos["CREDITO"]
        );

        $sentidos_inventario = array(
            "0" => $textos["NO_APLICA"],
            "1" => $textos["ENTRADA"],
            "2" => $textos["SALIDA"]
        );

        if($datos->aplica_notas == "1"){
            $marca_aplica_notas = true;
        }else{
            $marca_aplica_notas = false;
        }

        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);")),
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_comprobante", $textos["COMPROBANTE"], $tipos_comprobantes, $datos->codigo_comprobante,array("title" => $textos["AYUDA_COMPROBANTE"]))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("observaciones", $textos["OBSERVACIONES"], 30, 255, $datos->observaciones, array("title" => $textos["AYUDA_OBSERVACIONES"]))
            ),
            array(
                HTML::campoTextoCorto("*abreviaturas", $textos["ABREVIATURAS"], 3, 3, $datos->abreviaturas, array("title" => $textos["AYUDA_ABREVIATURAS"],"onBlur" => "validarItem(this);")),
                HTML::campoTextoCorto("tipo", $textos["TIPO"], 2, 2, $datos->tipo, array("title" => $textos["AYUDA_TIPO"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*sentido_contable", $textos["SENTIDO_CONTABLE"], $sentidos_contables,$datos->sentido_contable,array("title" => $textos["AYUDA_SENTIDO_CONTABLE"])),
                HTML::listaSeleccionSimple("*sentido_inventario", $textos["SENTIDO_INVENTARIO"], $sentidos_inventario,$datos->sentido_inventario,array("title" => $textos["AYUDA_SENTIDO_INVENTARIO"])),
                HTML::marcaChequeo("aplica_notas", $textos["APLICA_NOTAS"], $datos->aplica_notas, $marca_aplica_notas, array("title"=>$textos["AYUDA_APLICA_NOTAS"]))
            ),
            array(
                HTML::listaSeleccionSimple("manejo_automatico", $textos["MANEJO_AUTOMATICO"], $manejo, $datos->manejo_automatico, array("title" => $textos["AYUDA_MANEJO_AUTOMATICO"])),
                HTML::listaSeleccionSimple("control_titulo", $textos["CONTROL_TITULO"], $control, $datos->control_titulo, array("title" => $textos["AYUDA_CONTROL_TITULO"]))
            ),
            array(
                HTML::campoTextoCorto("equivalencia", $textos["EQUIVALENCIA"], 25, 25, $datos->equivalencia, array("title" => $textos["AYUDA_EQUIVALENCIA"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originá la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo_dian ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("tipos_documentos", "codigo", $url_valor, "codigo != '0' AND codigo != $url_id");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    /*** Validar codigo_interno ***/
    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("tipos_documentos", "descripcion", $url_valor, "descripcion != '' AND codigo != $url_id");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

    /*** Validar descripcion ***/
    if ($url_item == "abreviaturas") {
        $existe = SQL::existeItem("tipos_documentos", "abreviaturas", $url_valor,"abreviaturas != '' AND codigo != $url_id ");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_ABREVIATURA"]);
        }
    }

    /*** Validar equivalencia ***/
    if ($url_item == "equivalencia") {
        $existe = SQL::existeItem("tipos_documentos", "equivalencia", $url_valor,"equivalencia != '' AND codigo != $url_id ");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_EQUIVALENCIA"]);
        }
    }

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Validar campos requeridos ***/
    if(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    }elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];

    }elseif(empty($forma_codigo_comprobante)){
        $error   = true;
        $mensaje = $textos["COMPROBANTE_VACIO"];

    }elseif(empty($forma_abreviaturas)){
        $error   = true;
        $mensaje = $textos["ABREVIATURA_VACIO"];

    }elseif($existe = SQL::existeItem("tipos_documentos", "codigo", $forma_codigo,"codigo != '' AND codigo !='$forma_id'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_INTERNO"];

    }elseif($existe = SQL::existeItem("tipos_documentos", "descripcion", $forma_descripcion,"descripcion != '' AND codigo !='$forma_id'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];

    }elseif($existe = SQL::existeItem("tipos_documentos", "abreviaturas", $forma_abreviaturas,"abreviaturas != '' AND codigo !='$forma_id'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_ABREVIATURA"];

    }elseif($existe = SQL::existeItem("tipos_documentos", "equivalencia", $forma_equivalencia,"equivalencia != '' AND codigo !='$forma_id'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_EQUIVALECIA"];

    }else {
        if(isset($forma_aplica_notas)){
            $forma_aplica_notas = 1;
        }else{
            $forma_aplica_notas = 0;
        }

        /*** Insertra datos ***/
        $datos = array(
            "codigo"             => $forma_codigo,
            "codigo_comprobante" => $forma_codigo_comprobante,
            "descripcion"        => $forma_descripcion,
            "observaciones"      => $forma_observaciones,
            "abreviaturas"       => $forma_abreviaturas,
            "tipo"               => $forma_tipo,
            "manejo_automatico"  => $forma_manejo_automatico,
            "control_titulo"     => $forma_control_titulo,
            "sentido_contable"   => $forma_sentido_contable,
            "sentido_inventario" => $forma_sentido_inventario,
            "aplica_notas"       => $forma_aplica_notas,
            "equivalencia"       => $forma_equivalencia
        );
        $consulta = SQL::modificar("tipos_documentos", $datos, "codigo = '$forma_id'");

        /*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originá la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
