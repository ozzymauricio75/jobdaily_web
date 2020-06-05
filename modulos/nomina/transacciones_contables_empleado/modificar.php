<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Walter Andrés Márquez Gutiérrez <walteramg@gmail.com>
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

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "transacciones_contables_empleado";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error  = "";
        $titulo = $componente->nombre;

        $plan_contable  = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(codigo_contable,'|',1)", "id = '".$datos->codigo_contable."'");

        $sentido= array(
            "C" => $textos["CREDITO"],
            "D" => $textos["DEBITO"]
        );

        $certificado_ingresos = array(
            "1" => "",
            "2" => $textos["SALARIOS_OTROS_INGRESOS"],
            "3" => $textos["RETENCIONES_PRACTICADAS"],
            "4" => $textos["DESCUENTOS_SALUD_PENSION"]
        );

        $tipo_retencion = array(
            "" => "",
            "1" => "",
            "2" => $textos["RETE_SALARIOS"],
            "3" => $textos["RETE_VACACIONES"]
        );

        $columnas = array(
            "" => ""
        );
        $consultaColumnas = SQL::seleccionar(array("titulos_planillas"),array("*"),"");
        while($datosColumnas = SQL::filaEnObjeto($consultaColumnas)){
            $columnas[$datosColumnas->columna] = $datosColumnas->nombre;
        }

        if($datos->acumula_cesantias == 1){
            $cesantias_si  = true;
            $cesantias_no  = false;
        } else{
            $cesantias_si  = false;
            $cesantias_no  = true;
        }

        if($datos->acumula_prima == 1){
            $prima_si  = true;
            $prima_no  = false;
        } else{
            $prima_si  = false;
            $prima_no  = true;
        }

        if($datos->acumula_vacaciones == 1){
            $vacaciones_si  = true;
            $vacaciones_no  = false;
        }
        else{
            $vacaciones_si  = false;
            $vacaciones_no  = true;
        }

        if($datos->ibc_salud == 1 ){
            $salud_si = true;
            $salud_no = false;
            $salud_40 = false;
        }elseif($datos->ibc_salud == 0 ){
            $salud_si = false;
            $salud_no = true;
            $salud_40 = false;
        }else{
            $salud_si = false;
            $salud_no = false;
            $salud_40 = true;
        }

        if($datos->ibc_pension == 1 ){
            $pension_si = true;
            $pension_no = false;
            $pension_40 = false;
        }elseif($datos->ibc_pension == 0 ){
            $pension_si = false;
            $pension_no = true;
            $pension_40 = false;
        }else{
            $pension_si = false;
            $pension_no = false;
            $pension_40 = true;
        }

        if($datos->ibc_arp == 1 ){
            $arp_si = true;
            $arp_no = false;
        }
        else{
            $arp_si = false;
            $arp_no = true;
        }

        if($datos->ibc_icbf == 1 ){
            $icbf_si = true;
            $icbf_no = false;
        }
        else{
            $icbf_si = false;
            $icbf_no = true;
        }

        if($datos->ibc_sena == 1 ){
            $sena_si = true;
            $sena_no = false;
        }
        else{
            $sena_si = false;
            $sena_no = true;
        }

        if($datos->ibc_caja_compensacion == "1" ){
            $caja_compensacion_si = true;
            $caja_compensacion_no = false;
        }
        else{
            $caja_compensacion_si = false;
            $caja_compensacion_no = true;
        }

        if ($datos->codigo_concepto_transaccion_contable == 37){
            $oculto = "";
        } else {
            $oculto = "oculto";
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 20, 8, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onkeypress"=>"return campoEntero(event)", "onblur"=>"validarItem(this)"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 25, 25, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"], "onblur"=>"validarItem(this)"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 25, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"]))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["CODIGO_CONTABLE"], 30, 255, $plan_contable, array("title" => $textos["AYUDA_CODIGO_CONTABLE"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_contable", $datos->codigo_contable),
                HTML::listaSeleccionSimple("*sentido", $textos["SENTIDO"], $sentido, $datos->sentido, array("title" => $textos["AYUDA_SENTIDO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_concepto_transaccion_contable", $textos["CONCEPTO_CONTABLE"], HTML::generarDatosLista("conceptos_transacciones_contables_empleado", "codigo", "descripcion"), $datos->codigo_concepto_transaccion_contable, array("title" => $textos["AYUDA_CONCEPTO_CONTABLE"], "onchange" => "activarTipoRetencion()")),
                HTML::listaSeleccionSimple("tipo_retencion", $textos["RETENCION"], $tipo_retencion, $datos->tipo_retencion, array("title" => $textos["AYUDA_RETENCION"], "class"=>$oculto))
            ),
             array(
                HTML::listaSeleccionSimple("certificado_ingresos_retenciones", $textos["CERTIFICADO_INGRESOS"], $certificado_ingresos, $datos->certificado_ingresos_retenciones, array("title" => $textos["AYUDA_CONCEPTO_CONTABLE"]))
            ),
            array(
                HTML::listaSeleccionSimple("columna_planilla", $textos["PLANILLA_PAGO"], $columnas, $datos->columna_planilla, array("title" => $textos["AYUDA_PLANILLA_PAGO"]))
            )
        );

        // Definicion de pestana de ubicacion del empleado
        $formularios["PESTANA_CONTABLE"] = array(
            array(
                (HTML::mostrarDato("acumula_cesantias", $textos["CESANTIAS"], "")),
                HTML::marcaSeleccion("cesantias", $textos["SI"], 1, $cesantias_si, array("id" => "cesantias_si")),
                HTML::marcaSeleccion("cesantias", $textos["NO"], 0, $cesantias_no, array("id" => "cesantias_no"))
            ),
            array(
                HTML::mostrarDato("acumula_prima", $textos["PRIMA"], ""),
                HTML::marcaSeleccion("prima", $textos["SI"], 1, $prima_si, array("id" => "prima_si")),
                HTML::marcaSeleccion("prima", $textos["NO"], 0, $prima_no, array("id" => "prima_no"))
            ),
            array(
                HTML::mostrarDato("acumula_vaciones", $textos["VACACIONES"], ""),
                HTML::marcaSeleccion("vacaciones", $textos["SI"], 1, $vacaciones_si, array("id" => "vacaciones_si")),
                HTML::marcaSeleccion("vacaciones", $textos["NO"], 0, $vacaciones_no, array("id" => "vacaciones_no"))
            ),
            array(
                HTML::mostrarDato("ibc_salud", $textos["IBC_SALUD"], ""),
                HTML::marcaSeleccion("salud", $textos["SI"], '1', $salud_si, array("title" => $textos["AYUDA_SALUD"], "id" => "salud_si")),
                HTML::marcaSeleccion("salud", $textos["NO"], '0', $salud_no, array("title" => $textos["AYUDA_SALUD"], "id" => "salud_no")),
                HTML::marcaSeleccion("salud", $textos["MAYOR_40"], '2', $salud_40, array("title" => $textos["AYUDA_SALUD"], "id" => "salud_40"))
            ),
            array(
                HTML::mostrarDato("ibc_pension", $textos["IBC_PENSION"], ""),
                HTML::marcaSeleccion("pension", $textos["SI"], '1', $pension_si, array("title" => $textos["AYUDA_PENSION"], "id" => "pension_si")),
                HTML::marcaSeleccion("pension", $textos["NO"], '0', $pension_no, array("title" => $textos["AYUDA_PENSION"], "id" => "pension_no")),
                HTML::marcaSeleccion("pension", $textos["MAYOR_40"], '2', $pension_40, array("title" => $textos["AYUDA_PENSION"], "id" => "pension_40"))
            ),
            array(
                HTML::mostrarDato("ibc_arp", $textos["IBC_ARP"], ""),
                HTML::marcaSeleccion("arp", $textos["SI"], 1, $arp_si, array("title" => $textos["AYUDA_ARP"], "id" => "arp_si")),
                HTML::marcaSeleccion("arp", $textos["NO"], 0, $arp_no, array("title" => $textos["AYUDA_ARP"], "id" => "arp_no"))
            ),
            array(
                HTML::mostrarDato("ibc_icbf", $textos["IBC_ICBF"], ""),
                HTML::marcaSeleccion("icbf", $textos["SI"], 1, $icbf_si, array("title" => $textos["AYUDA_ICBF"], "id" => "icbf_si")),
                HTML::marcaSeleccion("icbf", $textos["NO"], 0, $icbf_no, array("title" => $textos["AYUDA_ICBF"], "id" => "icbf_no"))
            ),
            array(
                HTML::mostrarDato("ibc_sena", $textos["IBC_SENA"], ""),
                HTML::marcaSeleccion("sena", $textos["SI"], 1, $sena_si, array("title" => $textos["AYUDA_SENA"], "id" => "sena_si")),
                HTML::marcaSeleccion("sena", $textos["NO"], 0, $sena_no, array("title" => $textos["AYUDA_SENA"], "id" => "sena_no"))
            ),
            array(
                HTML::mostrarDato("ibc_caja_compensacion", $textos["IBC_CAJA_COMPENSACION"], ""),
                HTML::marcaSeleccion("caja_compensacion", $textos["SI"], 1, $caja_compensacion_si, array("title" => $textos["AYUDA_CAJA_COMPENSACION"], "id" => "caja_compesacion_si")),
                HTML::marcaSeleccion("caja_compensacion", $textos["NO"], 0, $caja_compensacion_no, array("title" => $textos["AYUDA_CAJA_COMPENSACION"], "id" => "caja_compesacion_no")),
            )
        );
        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($url_validar)) {

    /*** Validar numero de documento ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("transacciones_contables_empleado", "codigo", $url_valor,"codigo > 0 AND codigo !='$url_id'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    if ($url_item == "descripcion") {
        $existe = SQL::existeItem("transacciones_contables_empleado", "descripcion", $url_valor,"codigo !='$url_id'");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_TRANSACCION"]);
        }
    }


/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

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

    } else if(SQL::existeItem("transacciones_contables_empleado","codigo",$forma_codigo,"codigo!='$forma_codigo'")) {
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

    } else if(SQL::existeItem("transacciones_contables_empleado","descripcion",$forma_descripcion,"codigo!='$forma_codigo'")) {
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

        $consulta = SQL::modificar("transacciones_contables_empleado", $datos, "codigo = '$forma_id'");

        if(!$consulta){
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
