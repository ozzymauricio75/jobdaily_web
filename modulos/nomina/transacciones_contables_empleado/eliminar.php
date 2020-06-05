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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "transacciones_contables_empleado";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;


        $plan_contable          = SQL::obtenerValor("seleccion_plan_contable", "SUBSTRING_INDEX(id,'|',1)", "id = '$datos->codigo_contable'");
        $concepto_contable      = SQL::obtenerValor("conceptos_transacciones_contables_empleado", "descripcion", "codigo = '$datos->codigo_concepto_transaccion_contable'");

        $certificado_ingresos = array(
            "1" => "",
            "2" => $textos["SALARIOS_OTROS_INGRESOS"],
            "3" => $textos["RETENCIONES_PRACTICADAS"],
            "4" => $textos["DESCUENTOS_SALUD_PENSION"]
        );
        $certificado = $certificado_ingresos[$datos->certificado_ingresos_retenciones];

        if($datos->sentido == "C" )
            {$sentido = $textos["CREDITO"];}
        else{
            $sentido = $textos["DEBITO"];
        }

        $tipo_retencion = array(
            "" => "",
            "1" => "",
            "2" => $textos["RETE_SALARIOS"],
            "3" => $textos["RETE_VACACIONES"]
        );
        $retencion = $tipo_retencion[$datos->tipo_retencion];
        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
            ),
            array(
                HTML::mostrarDato("plan_contable", $textos["CODIGO_CONTABLE"], $plan_contable),
                HTML::mostrarDato("sentido", $textos["SENTIDO"], $sentido)
            )
        );

        if ($datos->codigo_concepto_transaccion_contable==37){
            $concepto[] = array(
                HTML::mostrarDato("concepto_contable", $textos["CONCEPTO_CONTABLE"], $concepto_contable),
                HTML::mostrarDato("retencion", $textos["RETENCION"], $retencion)
            );
            $formularios["PESTANA_BASICA"] = array_merge($formularios["PESTANA_BASICA"],$concepto);
        } else {
            $concepto[] = array(
                HTML::mostrarDato("concepto_contable", $textos["CONCEPTO_CONTABLE"], $concepto_contable)
            );
            $formularios["PESTANA_BASICA"] = array_merge($formularios["PESTANA_BASICA"],$concepto);
        }

        if ($datos->certificado_ingresos_retenciones !='1'){
            $datos_certificado[] = array(
                HTML::mostrarDato("certificado", $textos["CERTIFICADO_INGRESOS"], $certificado)
            );
            $formularios["PESTANA_BASICA"] = array_merge($formularios["PESTANA_BASICA"],$datos_certificado);
        }

        if ($datos->columna_planilla !=''){
            $resto[] = array(
                HTML::mostrarDato("planilla", $textos["PLANILLA_PAGO"], SQL::obtenervalor("titulos_planillas","nombre","columna = ".$datos->columna_planilla))
            );
            $formularios["PESTANA_BASICA"] = array_merge($formularios["PESTANA_BASICA"],$resto);
        }

        if($datos->acumula_cesantias == "1" ){
            $cesantias = $textos["SI"];
        }
        else{
            $cesantias = $textos["NO"];
        }

        if($datos->acumula_prima == "1" ){
            $prima = $textos["SI"];
        }
        else{
            $prima = $textos["NO"];
        }

        if($datos->acumula_vacaciones == "1" ){
            $vacaciones = $textos["SI"];
        }
        else{
            $vacaciones = $textos["NO"];
        }

        if($datos->ibc_salud == "1" ){
            $salud = $textos["SI"];
        }
        else if($datos->ibc_salud == "0"){
            $salud = $textos["NO"];
        }else {
            $salud = $textos["MAYOR_40"];
        }

        if($datos->ibc_pension == "1"){
            $pension = $textos["SI"];
        }
        else if($datos->ibc_pension == "0"){
            $pension = $textos["NO"];
        }else {
            $pension = $textos["MAYOR_40"];
        }

        if($datos->ibc_arp == "1" ){
            $arp = $textos["SI"];
        }
        else{
            $arp = $textos["NO"];
        }

        if($datos->ibc_icbf == "1" ){
            $icbf = $textos["SI"];
        }
        else{
            $icbf = $textos["NO"];
        }

        if($datos->ibc_sena == "1" ){
            $sena = $textos["SI"];
        }
        else{
            $sena = $textos["NO"];
        }

        if($datos->ibc_caja_compensacion == "1" ){
            $caja_compensacion = $textos["SI"];
        }
        else{
            $caja_compensacion = $textos["NO"];
        }

        if($datos->tipo_retencion == "1" ){
            $retencion = "";
        } else if($datos->tipo_retencion == "2" ){
            $retencion = $textos["RETE_SALARIOS"];
        } else {
            $retencion = $textos["RETE_VACACIONES"];
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_CONTABLE"] = array(
            array(
                HTML::mostrarDato("acumula_cesantias", $textos["CESANTIAS"], ""),
                HTML::mostrarDato("acumula_cesantias", $textos[""], $cesantias)
            ),
            array(
                HTML::mostrarDato("acumula_prima", $textos["PRIMA"], ""),
                HTML::mostrarDato("acumula_prima", $textos[""], $prima)
            ),
            array(
                HTML::mostrarDato("acumula_vaciones", $textos["VACACIONES"], ""),
                HTML::mostrarDato("acumula_vaciones", $textos[""], $vacaciones)
            ),
            array(
                HTML::mostrarDato("salud", $textos["IBC_SALUD"], ""),
                HTML::mostrarDato("salud", $textos[""], $salud)
            ),
            array(
                HTML::mostrarDato("pension", $textos["IBC_PENSION"], ""),
                HTML::mostrarDato("pension", $textos[""], $pension)
            ),
            array(
                HTML::mostrarDato("arp", $textos["IBC_ARP"], ""),
                HTML::mostrarDato("arp", $textos[""], $arp)
            ),
            array(
                HTML::mostrarDato("icbf", $textos["IBC_ICBF"], ""),
                HTML::mostrarDato("icbf", $textos[""], $icbf)
            ),
            array(
                HTML::mostrarDato("sena", $textos["IBC_SENA"], ""),
                HTML::mostrarDato("sena", $textos[""], $sena)
            ),
            array(
                HTML::mostrarDato("caja_compensacion", $textos["IBC_CAJA_COMPENSACION"], ""),
                HTML::mostrarDato("caja_compensacion", $textos[""], $caja_compensacion)
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

    }
    /*** Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
}elseif (!empty($forma_procesar)) {

    $consulta = SQL::eliminar("transacciones_contables_empleado", "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];

    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
