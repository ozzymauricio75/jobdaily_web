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
        echo SQL::datosAutoCompletar("seleccion_plan_contable", $url_q);
    }
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    $error  = "";
    $titulo = $componente->nombre;

    $error_ingreso = false;
    $plan_contable = SQL::seleccionar(array("seleccion_plan_contable"),array("codigo_contable"),"codigo_contable!=''");
    if (!(SQL::filasDevueltas($plan_contable))){
        $error_ingreso = 1;
    }
    $conceptos     = SQL::seleccionar(array("conceptos_transacciones_contables_empleado"),array("codigo"),"codigo>0");
    if (!(SQL::filasDevueltas($conceptos))){
        $error_ingreso = 2;
    }
    if (!$error_ingreso){

        $sentido= array(
            "C" => $textos["CREDITO"],
            "D" => $textos["DEBITO"]
        );
        $tipo_retencion = array(
            "1" => "",
            "2" => $textos["RETE_SALARIOS"],
            "3" => $textos["RETE_VACACIONES"]
        );
        $certificado_ingresos = array(
            "1" => "",
            "2" => $textos["SALARIOS_OTROS_INGRESOS"],
            "3" => $textos["RETENCIONES_PRACTICADAS"],
            "4" => $textos["DESCUENTOS_SALUD_PENSION"]
        );

        $columnas = array(
            "" => ""
        );
        $consultaColumnas = SQL::seleccionar(array("titulos_planillas"),array("*"),"");
        while($datosColumnas = SQL::filaEnObjeto($consultaColumnas)){
            $columnas[$datosColumnas->columna] = $datosColumnas->nombre;
        }

        $codigo = SQL::obtenerValor("transacciones_contables_empleado","MAX(codigo)","codigo>0");
        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 20, 8, $codigo, array("title" => $textos["AYUDA_CODIGO"],"onkeypress"=>"return campoEntero(event)", "onblur"=>"validarItem(this)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 25, 25, "", array("title" => $textos["AYUDA_NOMBRE"], "onblur"=>"validarItem(this)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 50, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"], "onblur"=>"validarItem(this)"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["CODIGO_CONTABLE"], 30, 255, "", array("title" => $textos["AYUDA_CODIGO_CONTABLE"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable", ""),
                HTML::listaSeleccionSimple("*sentido", $textos["SENTIDO"], $sentido, 0, array("title" => $textos["AYUDA_SENTIDO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_concepto_transaccion_contable", $textos["CONCEPTO_CONTABLE"], HTML::generarDatosLista("conceptos_transacciones_contables_empleado", "codigo", "descripcion"), "", array("title" => $textos["AYUDA_CONCEPTO_CONTABLE"], "onchange" => "activarTipoRetencion()")),
                HTML::listaSeleccionSimple("tipo_retencion", $textos["RETENCION"], $tipo_retencion, 0, array("title" => $textos["AYUDA_RETENCION"],"class"=>"oculto")),
            ),
            array(
                HTML::listaSeleccionSimple("*certificado_ingresos_retenciones", $textos["CERTIFICADO_INGRESOS"], $certificado_ingresos, 0, array("title" => $textos["AYUDA_CERTIFICADO_INGRESO"]))
            ),
            array(
                HTML::listaSeleccionSimple("columna_planilla", $textos["PLANILLA_PAGO"], $columnas, "", array("title" => $textos["AYUDA_PLANILLA_PAGO"]))
            )
        );

        /*** Definición de pestaña de ubicación del empleado ***/
        $formularios["PESTANA_CONTABLE"] = array(
            array(
                HTML::mostrarDato("acumula_cesantias", $textos["CESANTIAS"], ""),
                HTML::marcaSeleccion("cesantias", $textos["SI"], 1, false, array("id" => "cesantias_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("cesantias", $textos["NO"], 0, false, array("id" => "cesantias_no", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("acumula_prima", $textos["PRIMA"], ""),
                HTML::marcaSeleccion("prima", $textos["SI"], '1', false, array("id" => "prima_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("prima", $textos["NO"], '0', false, array("id" => "prima_no", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("acumula_vaciones", $textos["VACACIONES"], ""),
                HTML::marcaSeleccion("vacaciones", $textos["SI"], '1', false, array("id" => "vacaciones_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("vacaciones", $textos["NO"], '0', false, array("id" => "vacaciones_no", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("ibc_salud", $textos["IBC_SALUD"], ""),
                HTML::marcaSeleccion("salud", $textos["SI"], '1', false, array("title" => $textos["AYUDA_SALUD"], "id" => "salud_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("salud", $textos["NO"], '0', false, array("title" => $textos["AYUDA_SALUD"], "id" => "salud_no", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("salud", $textos["MAYOR_40"], '2', false, array("title" => $textos["AYUDA_SALUD"], "id" => "salud_40", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("ibc_pension", $textos["IBC_PENSION"], ""),
                HTML::marcaSeleccion("pension", $textos["SI"], '1', false, array("title" => $textos["AYUDA_PENSION"], "id" => "pension_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("pension", $textos["NO"], '0', false, array("title" => $textos["AYUDA_PENSION"], "id" => "pension_no", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("pension", $textos["MAYOR_40"], '2', false, array("title" => $textos["AYUDA_PENSION"], "id" => "pension_40", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("ibc_arp", $textos["IBC_ARP"], ""),
                HTML::marcaSeleccion("arp", $textos["SI"], '1', false, array("title" => $textos["AYUDA_ARP"], "id" => "arp_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("arp", $textos["NO"], '0', false, array("title" => $textos["AYUDA_ARP"], "id" => "arp_no", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("ibc_icbf", $textos["IBC_ICBF"], ""),
                HTML::marcaSeleccion("icbf", $textos["SI"], '1', false, array("title" => $textos["AYUDA_ICBF"], "id" => "icbf_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("icbf", $textos["NO"], '0', false, array("title" => $textos["AYUDA_ICBF"], "id" => "icbf_no", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("ibc_sena", $textos["IBC_SENA"], ""),
                HTML::marcaSeleccion("sena", $textos["SI"], '1', false, array("title" => $textos["AYUDA_SENA"], "id" => "sena_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("sena", $textos["NO"], '0', false, array("title" => $textos["AYUDA_SENA"], "id" => "sena_no", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::mostrarDato("ibc_caja_compensacion", $textos["IBC_CAJA_COMPENSACION"], ""),
                HTML::marcaSeleccion("caja_compensacion", $textos["SI"], '1', false, array("title" => $textos["AYUDA_CAJA_COMPENSACION"], "id" => "caja_compesacion_si", "onclick"=>"validaContinuar()")),
                HTML::marcaSeleccion("caja_compensacion", $textos["NO"], '0', false, array("title" => $textos["AYUDA_CAJA_COMPENSACION"], "id" => "caja_compesacion_no", "onclick"=>"validaContinuar()"))
            ),
            array(
                HTML::campoOculto("continuar",0)
            )
        );


        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        if ($error_ingreso==1){
            $error     = $textos["ERROR_CODIGO_CONTABLE"];
        } else {
            $error     = $textos["ERROR_CONCEPTOS"];
        }
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

    /*** Validar numero de documento ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("transacciones_contables_empleado", "codigo", $url_valor,"codigo > 0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("transacciones_contables_empleado", "descripcion", $url_valor,"codigo>0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_TRANSACCION"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    } else if(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];

    } else if (empty($forma_codigo_contable)){
        $error   = true;
        $mensaje = $textos["CODIGO_CONTABLE_VACIO"];

    } else if(empty($forma_codigo_concepto_transaccion_contable)) {
        $error   = true;
        $mensaje = $textos["CONCEPTO_CONTABLE_VACIO"];

    } else if(empty($forma_descripcion)) {
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];

    } else if($forma_continuar=='0') {
        $error   = true;
        $mensaje = $textos["PESTANA_CONTABLE_VACIO"];

    } else if(SQL::existeItem("transacciones_contables_empleado","codigo",$forma_codigo)) {
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

    } else if(SQL::existeItem("transacciones_contables_empleado","descripcion",$forma_descripcion)) {
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_TRANSACCION"];

    } else {

        if (empty($forma_cesantias)){
            $forma_cesantias = "0";
        }
        if (empty($forma_prima)){
             $forma_prima = "0";
        }
        if (empty($forma_vacaciones)){
             $forma_vacaciones = "0";
        }
        if (empty($forma_tipo_retencion)){
             $forma_tipo_retencion = "0";
        }
        if (empty($forma_salud)){
             $forma_salud = "0";
        }
        if (empty($forma_pension)){
             $forma_pension = "0";
        }
        if (empty($forma_arp)){
             $forma_arp = "0";
        }
        if (empty($forma_icbf)){
             $forma_icbf = "0";
        }
        if (empty($forma_sena)){
             $forma_sena = "0";
        }
        if (empty($forma_caja_compensacion)){
             $forma_caja_compensacion = "0";
        }
        if (empty($forma_certificado_ingresos_retenciones)){
            $forma_certificado_ingresos_retenciones = "0";
        }

        $datos = array(
            "codigo"                               => $forma_codigo,
            "nombre"                               => $forma_nombre,
            "descripcion"                          => $forma_descripcion,
            "codigo_contable"                      => $forma_codigo_contable,
            "sentido"                              => $forma_sentido,
            "codigo_concepto_transaccion_contable" => $forma_codigo_concepto_transaccion_contable,
            "acumula_cesantias"                    => $forma_cesantias,
            "acumula_prima"                        => $forma_prima,
            "acumula_vacaciones"                   => $forma_vacaciones,
            "tipo_retencion"                       => $forma_tipo_retencion,
            "ibc_salud"                            => $forma_salud,
            "ibc_pension"                          => $forma_pension,
            "ibc_arp"                              => $forma_arp,
            "ibc_icbf"                             => $forma_icbf,
            "ibc_sena"                             => $forma_sena,
            "ibc_caja_compensacion"                => $forma_caja_compensacion,
            "certificado_ingresos_retenciones"     => $forma_certificado_ingresos_retenciones,
            "columna_planilla"                     => $forma_columna_planilla
        );
        $insertar = SQL::insertar("transacciones_contables_empleado", $datos);

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
