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

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    echo SQL::datosAutoCompletar("seleccion_plan_contable_transacciones", $url_q);
    exit;
}

if (isset($url_verificarTasas) && isset($url_id_plan_contable)){
    $id_tasa1 = SQL::obtenerValor("plan_contable","codigo_tasa_aplicar_1","codigo_contable = '$url_id_plan_contable'");
    if ($id_tasa1 == 0){
        $mensaje[0] = true;
        $mensaje[1] = $textos["ASIGNAR_TASA"]." ".$url_descripcion;
    } else {
        $mensaje[0] = false;
    }
    HTTP::enviarJSON($mensaje);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    $tipos_devoluciones = SQL::seleccionar(array("tipos_devoluciones_compra"),array("codigo"),"codigo != 0");
    if (SQL::filasDevueltas($tipos_devoluciones)){

        $consecutivo = SQL::obtenerValor("conceptos_devolucion_compras","MAX(codigo)","");
        if(!$consecutivo){
            $consecutivo = 1;
        }else{
            $consecutivo++;
        }

        // Definicion de pestanas
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 4, $consecutivo, array("title" => $textos["AYUDA_CODIGO"], "onBlur" => "validarItem(this);", "OnKeyPress" => "return campoEntero(event);")),
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 40, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"], "onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::mostrarDato("mostrar_regimen_ventas_empresa", $textos["REGIMEN_EMPRESA"],""),
                HTML::marcaSeleccion("regimen_ventas_empresa", $textos["REGIMEN_COMUN"], '1', true, array("id" => "regimen_comun")),
                HTML::marcaSeleccion("regimen_ventas_empresa", $textos["REGIMEN_SIMPLIFICADO"], '2', false, array("id" => "regimen_simplificado"))
            ),
            array(
                HTML::mostrarDato("mostrar_regimen_persona", $textos["REGIMEN_PERSONA"],""),
                HTML::marcaSeleccion("regimen_persona", $textos["REGIMEN_COMUN"], '1', true, array("id" => "persona_comun", "onChange" => "persona_simplificada_cuentas()")),
                HTML::marcaSeleccion("regimen_persona", $textos["REGIMEN_SIMPLIFICADO"], '2', false, array("id" => "persona_simplificada", "onChange" => "persona_simplificada_cuentas()"))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_devolucion", $textos["TIPO_DEVOLUCION"], HTML::generarDatosLista("tipos_devoluciones_compra", "codigo", "descripcion"),0,array("title" => $textos["AYUDA_TIPO_DEVOLUCION"])),
                HTML::listaSeleccionSimple("*tipo_tasa_iva", $textos["TASA_IVA"], HTML::generarDatosLista("tasas", "codigo", "descripcion","codigo != 0"),0,array("title" => $textos["AYUDA_TASA_IVA"]))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["CODIGO_COMPRAS"], 40, 255, "", array("title" => $textos["AYUDA_CODIGO_COMPRAS"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_cuentas_pagar", ""),
            ),
            array(
                HTML::campoTextoCorto("*selector2", $textos["CODIGO_IVA"], 40, 255, "", array("title" => $textos["AYUDA_CODIGO_IVA"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable_iva", ""),
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error     = $textos["ERROR_TIPOS_DEVOLUCIONES"];
        $titulo    = "";
        $contenido = "";
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Validar los datos provenientes del formulario
} elseif (!empty($url_validar)) {

    // Validar codigo
    if ($url_item == "codigo" && !empty($url_valor)) {
        $existe = SQL::existeItem("conceptos_devolucion_compras", "codigo", $url_valor);
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_CODIGO_EXISTE"]);
        }
    }

    // Validar descripcion
    if ($url_item == "descripcion" && !empty($url_valor)) {
        $existe = SQL::existeItem("conceptos_devolucion_compras", "descripcion", $url_valor);
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION"]);
        }
    }

// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $codigo = (int)$forma_codigo;

    // Validar ingreso de campos requeridos
    if (empty($forma_codigo) || $codigo == 0){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];

    }else if (empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];

    }elseif(empty($forma_tipo_devolucion)){
        $error   = true;
        $mensaje = $textos["TIPO_DEVOLUCION_VACIO"];

    }elseif(empty($forma_tipo_tasa_iva)){
        $error   = true;
        $mensaje = $textos["TASA_IVA_VACIO"];

    }elseif(empty($forma_codigo_contable_cuentas_pagar) || empty($forma_selector1)){
        $error   = true;
        $mensaje = $textos["PLAN_CXP_VACIO"];

    }elseif($forma_regimen_persona == '1' && empty($forma_codigo_contable_iva)){
        $error   = true;
        $mensaje = $textos["IVA_VACIO"];

    }elseif($existe = SQL::existeItem("conceptos_devolucion_compras", "codigo", $forma_codigo)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_EXISTE"];

    }elseif($existe = SQL::existeItem("conceptos_devolucion_compras", "descripcion", $forma_descripcion)){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];

    }else {
        // Insertar datos
        $datos = array(
            "codigo"                    => $forma_codigo,
            "descripcion"               => $forma_descripcion,
            "regimen_ventas_empresa"    => $forma_regimen_ventas_empresa,
            "regimen_persona"           => $forma_regimen_persona,
            "codigo_tipo_devolucion"    => $forma_tipo_devolucion,
            "codigo_tasa_iva"           => $forma_tipo_tasa_iva,
            "codigo_contable_compras"   => $forma_codigo_contable_cuentas_pagar,
            "codigo_contable_iva"       => $forma_codigo_contable_iva
        );
        $insertar = SQL::insertar("conceptos_devolucion_compras", $datos);

        // Error de insercion
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
