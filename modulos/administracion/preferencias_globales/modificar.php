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

if (isset($url_completar)){
    if ($url_item == "selector1"){
        echo SQL::datosAutoCompletar("seleccion_plan_contable_credito", $url_q);
    }
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $consulta1  = SQL::filasDevueltas(SQL::seleccionar(array("tasas"),array("codigo"),"codigo != 0"));
    $consulta2  = SQL::filasDevueltas(SQL::seleccionar(array("unidades"),array("codigo"),"codigo != 0"));
    $consulta3  = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_tiempo"),array("codigo"),"codigo != 0 "));
    $consulta4  = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_tiempo"),array("codigo"),"codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)"));
    $consulta5  = SQL::filasDevueltas(SQL::seleccionar(array("tipos_documento_identidad"),array("codigo"),"codigo != 0"));
    $consulta6  = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_contables_empleado"),array("codigo"),"codigo_concepto_transaccion_contable = 5"));
    $consulta7  = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_contables_empleado"),array("codigo"),"codigo_concepto_transaccion_contable = 41"));
    $consulta8  = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_contables_empleado"),array("codigo"),"codigo_concepto_transaccion_contable = 42"));
    $consulta9  = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_contables_empleado"),array("codigo"),"codigo_concepto_transaccion_contable = 6"));
    $consulta10 = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_contables_empleado"),array("codigo"),"codigo_concepto_transaccion_contable = 44"));
    $consulta11 = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_contables_empleado"),array("codigo"),"codigo_concepto_transaccion_contable = 45"));
    $consulta12 = SQL::filasDevueltas(SQL::seleccionar(array("plan_contable"),array("codigo_contable"),"codigo_contable != ''"));
    $consulta13 = SQL::filasDevueltas(SQL::seleccionar(array("transacciones_contables_empleado"),array("codigo"),"codigo_concepto_transaccion_contable = 7"));

    if($consulta1 == 0 || $consulta2 == 0 || $consulta3 == 0 || $consulta4 == 0 || $consulta5 == 0 || $consulta6 == 0 || $consulta7 == 0 || $consulta8 == 0 || $consulta9 == 0 || $consulta10 == 0 || $consulta11 == 0 || $consulta12==0 || $consulta13 == 0){

        $mensaje   = $textos["ERROR_TABLAS"];
        $listaMensajes = array();

        if($consulta1 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TASAS"];
        }

        if($consulta2 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_UNIDADES"];
        }

        if($consulta3 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TIEMPO"];
        }

        if($consulta4 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_INCAPACIDADES"];
        }

        if($consulta5 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_DOCUMENTOS"];
        }

        if($consulta6 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_5"];
        }

        if($consulta7 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_41"];
        }

        if($consulta8 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_42"];
        }

        if($consulta9 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_6"];
        }

        if($consulta10 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_44"];
        }

        if($consulta11 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_45"];
        }

        if($consulta12 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_4"];
        }

        if($consulta13 == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_TC_7"];
        }
        $tablas    = implode("\n",$listaMensajes);
        $mensaje  .= $tablas;
        $error     = $mensaje;
        $titulo    = "";
        $contenido = "";
    }else{

        $error     = "";
        $idActual  = $componente->id;
        $titulo    = $componente->nombre;

        $preferencias = array();
        $preferencias["nomina_por_pagar"]                                   = "";
        $preferencias["codigo_transaccion_tiempo_incapacidad_tres_dias"]    = 0;
        $preferencias["codigo_transaccion_tiempo_incapacidad_general"]      = 0;
        $preferencias["tasa_salud"]                                         = 0;
        $preferencias["tasa_pension"]                                       = 0;
        $preferencias["tasa_fondo_solidaridad_pension"]                     = 0;
        $preferencias["codigo_transaccion_nomina_pagar_salud"]              = 0;
        $preferencias["codigo_transaccion_nomina_pagar_pension"]            = 0;
        $preferencias["codigo_transaccion_cuenta_pagar_salud"]              = 0;
        $preferencias["codigo_transaccion_cuenta_pagar_pension"]            = 0;
        $preferencias["transaccion_cancelacion_nomina_pagar_salud"]         = 0;
        $preferencias["transaccion_cancelacion_nomina_pagar_pension"]       = 0;
        $preferencias["codigo_transaccion_fondo_solidaridad_pension"]       = 0;
        $preferencias["tipo_documento_identidad"]                           = 0;
        $preferencias["codigo_tipo_articulo"]                               = 0;
        $preferencias["codigo_unidad_compra"]                               = 0;
        $preferencias["codigo_unidad_venta"]                                = 0;
        $preferencias["codigo_unidad_presentacion"]                         = 0;
        $preferencias["codigo_impuesto_compra"]                             = 0;
        $preferencias["codigo_impuesto_venta"]                              = 0;
        $preferencias["equivale_salario_integral"]                          = "";
        $preferencias["valor_minimo_ingresos_varios"]                       = "";
        $preferencias["factor_fondo_solidaridad_pension"]                   = 0;



        $preferencias_sucursal = SQL::seleccionar(array("preferencias"),array("valor", "variable"),"tipo_preferencia='1'");
        if(SQL::filasDevueltas($preferencias_sucursal)){
            while ($datos = SQL::filaEnObjeto($preferencias_sucursal)) {
                $preferencias[$datos->variable] = $datos->valor;
            }
        }

        $codigo_contable = "";
        if ($preferencias["nomina_por_pagar"] != ""){
            $codigo_contable = SQL::obtenerValor("seleccion_plan_contable_credito","codigo_contable","id='".$preferencias["nomina_por_pagar"]."'");
            if ($codigo_contable){
                $codigo_contable = explode("|",$codigo_contable);
                $codigo_contable = $codigo_contable[0];
            } else {
                $preferencias["nomina_por_pagar"] = "";
            }
        }

        $tipo_articulo= array(
            "1" => $textos["PRODUCTO_TERMINADO"],
            "2" => $textos["OBSEQUIO"],
            "3" => $textos["ACTIVO_FIJO"],
            "4" => $textos["MATERIA_PRIMA"]
        );

        $unidades = HTML::generarDatosLista("unidades", "codigo", "nombre");

        $condicion     = "codigo='0' OR codigo_concepto_transaccion_tiempo in (select codigo from job_conceptos_transacciones_tiempo where tipo=1)";
        $consulta      = SQL::seleccionar(array("transacciones_tiempo"), array("codigo", "nombre"),$condicion);
        $transacciones = array();
        while ($fila = SQL::filaEnArreglo($consulta)) {
            $transacciones[$fila[0]] = $fila[1];
        }

        $formularios["PESTANA_INVENTARIOS"] = array(
            array(
                HTML::listaSeleccionSimple("codigo_tipo_articulo", $textos["TIPO_ARTICULO"], $tipo_articulo, $preferencias["codigo_tipo_articulo"])
            ),
            array(
                HTML::listaSeleccionSimple("codigo_impuesto_compra", $textos["IMPUESTO_COMPRA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $preferencias["codigo_impuesto_compra"]),
            ),
            array(
                HTML::listaSeleccionSimple("codigo_impuesto_venta", $textos["IMPUESTO_VENTA"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $preferencias["codigo_impuesto_venta"]),
            ),
            array(
                HTML::listaSeleccionSimple("codigo_unidad_compra", $textos["UNIDAD_COMPRA"], $unidades, $preferencias["codigo_unidad_compra"])
            ),
            array(
                HTML::listaSeleccionSimple("codigo_unidad_venta", $textos["UNIDAD_COMPRA"], $unidades, $preferencias["codigo_unidad_venta"])
            ),
            array(
                HTML::listaSeleccionSimple("codigo_unidad_presentacion", $textos["UNIDAD_PRESENTACION"], $unidades, $preferencias["codigo_unidad_presentacion"])
            )
        );

        $formularios["PESTANA_NOMINA"] = array(
            array(
                HTML::mostrarDato("pagos_nomina",$textos["PAGOS"],"")
            ),
            array(
                HTML::campoTextoCorto("selector1",$textos["NOMINA_POR_PAGAR"], 50, 50, $codigo_contable, array("class"=>"autocompletable","title" => $textos[
                "AYUDA_NOMINA_POR_PAGAR"])).
                HTML::campoOculto("nomina_por_pagar",$preferencias["nomina_por_pagar"])
            ),
            array(
                HTML::mostrarDato("incapacidades",$textos["INCAPACIDADES"],"")
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_transaccion_tiempo_incapacidad_tres_dias", $textos["TRANSACCION_TIEMPO_TRES_DIAS"], HTML::generarDatosLista("transacciones_tiempo", "codigo", "nombre"), $preferencias["codigo_transaccion_tiempo_incapacidad_tres_dias"]),
                HTML::listaSeleccionSimple("*codigo_transaccion_tiempo_incapacidad_general", $textos["TRANSACCION_TIEMPO_GENERAL"], HTML::generarDatosLista("transacciones_tiempo", "codigo", "nombre"), $preferencias["codigo_transaccion_tiempo_incapacidad_general"])
            ),
            array(
                HTML::mostrarDato("entidades",$textos["ENTIDADES"],"")
            ),
            array(
                HTML::listaSeleccionSimple("*tasa_salud", $textos["TASA_SALUD"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $preferencias["tasa_salud"])
            ),
            array(
                HTML::listaSeleccionSimple("*tasa_pension", $textos["TASA_PENSION"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $preferencias["tasa_pension"])
            ),
            array(
                HTML::listaSeleccionSimple("*tasa_fondo_solidaridad_pension", $textos["TASA_FONDO_SOLIDARIDAD"], HTML::generarDatosLista("tasas", "codigo", "descripcion"), $preferencias["tasa_fondo_solidaridad_pension"])
            ),
            array(
                HTML::mostrarDato("aspirantes",$textos["ASPIRANTES"],"")
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_documento_aspirante", $textos["TIPOS_DOCUMENTO_IDENTIDAD"], HTML::generarDatosLista("tipos_documento_identidad", "codigo", "descripcion"), $preferencias["tipo_documento_identidad"],array("title" => $textos["AYUDA_TIPOS_DOCUMENTO_IDENTIDAD"]))
            )
        );

        $formularios["PESTANA_NOMINA_2"] = array(
            array(
                HTML::listaSeleccionSimple("codigo_transaccion_nomina_pagar_salud",$textos["NOMINA_PAGAR_SALUD"],HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable = 5 OR codigo = 0"), $preferencias["codigo_transaccion_nomina_pagar_salud"], array("title" => $textos["AYUDA_NOMINA_PAGAR_SALUD"]))
            ),
            array(
                HTML::listaSeleccionSimple("transaccion_cancelacion_nomina_pagar_salud",$textos["CANCELACION_NOMINA_PAGAR_SALUD"],HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable = 41 OR codigo = 0"), $preferencias["transaccion_cancelacion_nomina_pagar_salud"], array("title" => $textos["AYUDA_CANCELACION_NOMINA_PAGAR_SALUD"]))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_transaccion_cuenta_pagar_salud",$textos["CUENTA_PAGAR_SALUD"],HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable = 42 OR codigo = 0"), $preferencias["codigo_transaccion_cuenta_pagar_salud"], array("title" => $textos["AYUDA_CUENTA_PAGAR_SALUD"]))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_transaccion_nomina_pagar_pension",$textos["NOMINA_PAGAR_PENSION"],HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable = 6 OR codigo = 0"), $preferencias["codigo_transaccion_nomina_pagar_pension"], array("title" => $textos["AYUDA_NOMINA_PAGAR_PENSION"]))
            ),
            array(
                HTML::listaSeleccionSimple("transaccion_cancelacion_nomina_pagar_pension",$textos["CANCELACION_NOMINA_PAGAR_PENSION"],HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable = 44 OR codigo = 0"), $preferencias["transaccion_cancelacion_nomina_pagar_pension"], array("title" => $textos["AYUDA_CANCELACION_NOMINA_PAGAR_PENSION"]))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_transaccion_cuenta_pagar_pension",$textos["CUENTA_PAGAR_PENSION"],HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable = 45 OR codigo = 0"), $preferencias["codigo_transaccion_cuenta_pagar_pension"], array("title" => $textos["AYUDA_CUENTA_PAGAR_SALUD"]))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_transaccion_fondo_solidaridad_pension",$textos["FONDO_SOLIDARIDAD"],HTML::generarDatosLista("transacciones_contables_empleado","codigo","descripcion","codigo_concepto_transaccion_contable = 7 OR codigo = 0"), $preferencias["codigo_transaccion_fondo_solidaridad_pension"], array("title" => $textos["AYUDA_FONDO_SOLIDARIDAD"])),
                HTML::campoTextoCorto("factor_fondo_solidaridad_pension",$textos["FACTOR_FONDO_SOLIDARIDAD"],5,5,$preferencias["factor_fondo_solidaridad_pension"],array("onKeyPress" => "return campoDecimal(event);")),

            ),
            array(
                HTML::campoTextoCorto("equivale_salario_integral",$textos["EQUIVALE_SALARIO_INTEGRAL"],15,11,$preferencias["equivale_salario_integral"],array("onKeyPress" => "return campoEntero(event);")),
                HTML::campoTextoCorto("valor_minimo_ingresos_varios",$textos["VALOR_MINIMO_INGRESOS_VARIOS"],15,11,$preferencias["valor_minimo_ingresos_varios"],array("onKeyPress" => "return campoEntero(event);"))
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $componente = new Componente($idActual);
        $contenido  = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $elementos_preferencias = array();
    $elementos_preferencias["nomina_por_pagar"]                                 = $forma_nomina_por_pagar;
    $elementos_preferencias["codigo_transaccion_tiempo_incapacidad_tres_dias"]  = $forma_codigo_transaccion_tiempo_incapacidad_tres_dias;
    $elementos_preferencias["codigo_transaccion_tiempo_incapacidad_general"]    = $forma_codigo_transaccion_tiempo_incapacidad_general;
    $elementos_preferencias["tasa_salud"]                                       = $forma_tasa_salud;
    $elementos_preferencias["tasa_pension"]                                     = $forma_tasa_pension;
    $elementos_preferencias["tasa_fondo_solidaridad_pension"]                   = $forma_tasa_fondo_solidaridad_pension;
    $elementos_preferencias["codigo_transaccion_nomina_pagar_salud"]            = $forma_codigo_transaccion_nomina_pagar_salud;
    $elementos_preferencias["codigo_transaccion_cuenta_pagar_salud"]            = $forma_codigo_transaccion_cuenta_pagar_salud;
    $elementos_preferencias["transaccion_cancelacion_nomina_pagar_salud"]       = $forma_transaccion_cancelacion_nomina_pagar_salud;
    $elementos_preferencias["codigo_transaccion_nomina_pagar_pension"]          = $forma_codigo_transaccion_nomina_pagar_pension;
    $elementos_preferencias["codigo_transaccion_cuenta_pagar_pension"]          = $forma_codigo_transaccion_cuenta_pagar_pension;
    $elementos_preferencias["transaccion_cancelacion_nomina_pagar_pension"]     = $forma_transaccion_cancelacion_nomina_pagar_pension;
    $elementos_preferencias["codigo_transaccion_fondo_solidaridad_pension"]     = $forma_codigo_transaccion_fondo_solidaridad_pension;
    $elementos_preferencias["tipo_documento_identidad"]                         = $forma_tipo_documento_aspirante;
    $elementos_preferencias["codigo_tipo_articulo"]                             = $forma_codigo_tipo_articulo;
    $elementos_preferencias["codigo_unidad_compra"]                             = $forma_codigo_unidad_compra;
    $elementos_preferencias["codigo_unidad_venta"]                              = $forma_codigo_unidad_venta;
    $elementos_preferencias["codigo_unidad_presentacion"]                       = $forma_codigo_unidad_presentacion;
    $elementos_preferencias["codigo_impuesto_compra"]                           = $forma_codigo_impuesto_compra;
    $elementos_preferencias["codigo_impuesto_venta"]                            = $forma_codigo_impuesto_venta;
    $elementos_preferencias["equivale_salario_integral"]                        = $forma_equivale_salario_integral;
    $elementos_preferencias["valor_minimo_ingresos_varios"]                     = $forma_valor_minimo_ingresos_varios;
    $elementos_preferencias["factor_fondo_solidaridad_pension"]                 = $forma_factor_fondo_solidaridad_pension;

    foreach($elementos_preferencias AS $id_vector => $valor_vector){
        $datos = array(
            "codigo_empresa"   => 0,
            "codigo_sucursal"  => 0,
            "codigo_usuario"   => 0,
            "tipo_preferencia" => 1,
            "variable"         => $id_vector,
            "valor"            => $valor_vector,
        );
        $insertar = SQL::reemplazar("preferencias", $datos);
    }

    Sesion::registrar("preferencias_globales", $elementos_preferencias);
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
