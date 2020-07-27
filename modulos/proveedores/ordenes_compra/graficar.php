<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* Jobdaily :: Software empresarial a la medida
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
$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_proyectos", $url_q);
    }
    exit;

} elseif (!empty($url_cargaGrafica)){
    $llave_proyecto  = explode("-", $url_proyecto);
    $codigo_proyecto = $llave_proyecto[0];

    /*** Obtener los datos de la tabla ***/
    $ordenes_compra   = SQL::seleccionar(array("ordenes_compra"),array("*"),"$condicion_estado prefijo_codigo_proyecto='$codigo_proyecto'");
    $datos_encabezado = SQL::filaEnObjeto($ordenes_compra);

    $nombre_proyecto  = SQL::obtenerValor("proyectos","nombre","codigo='$datos_encabezado->prefijo_codigo_proyecto'");
    $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$datos_encabezado->codigo_sucursal'");
    $nombre_sucursal  = SQL::obtenerValor("sucursales","nombre","codigo='$datos_encabezado->codigo_sucursal'");
    $nombre_empresa   = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
    $valor_proyecto   = SQL::obtenerValor("proyectos","valor_proyecto","codigo='$datos_encabezado->prefijo_codigo_proyecto'");
    /*** Obtener los datos de la tabla ***/
    $ordenes_compra = SQL::seleccionar(array("ordenes_compra"),array("*"),"$condicion_estado prefijo_codigo_proyecto='$codigo_proyecto'");
    //Se lee el movimiento de la tabla movimientos
    if (SQL::filasDevueltas($ordenes_compra)){
        while($datos_encabezado=SQL::filaEnObjeto($ordenes_compra)){
            
            $movimiento_ordenes  = SQL::seleccionar(array("movimiento_ordenes_compra"),array("*"),"codigo_orden_compra='$datos_encabezado->codigo'");    
            
            $id_sucursal     = SQL::obtenerValor("ordenes_compra","codigo_sucursal","prefijo_codigo_proyecto='$codigo_proyecto' LIMIT 0,1");
            $codigo_empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$id_sucursal'");
            $nombre_empresa  = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
            $numero_orden    = $datos_encabezado->numero_consecutivo;
            $nit             = SQL::obtenerValor("ordenes_compra","documento_identidad_proveedor","numero_consecutivo='$numero_orden'");
            $proveedor       = SQL::obtenerValor("menu_proveedores","PROVEEDOR","DOCUMENTO_PROVEEDOR ='$nit'");
            $grabar_registro = 1;
            
            if ($grabar_registro==1){

                $consorciado = SQL::obtenerValor("sucursales","nombre","codigo='$datos_encabezado->codigo_sucursal'");
                
                if ($datos_encabezado->estado == '0'){
                   $estado_orden = $textos["ESTADO_0"];
                }
                if ($datos_encabezado->estado == '1'){
                   $estado_orden = $textos["ESTADO_1"];
                }
                if ($datos_encabezado->estado == '2'){
                   $estado_orden = $textos["ESTADO_2"];
                }
                if ($datos_encabezado->estado == '3'){
                   $estado_orden = $textos["ESTADO_3"];
                }
                if ($datos_encabezado->estado == '4'){
                   $estado_orden = $textos["ESTADO_4"];
                }
                
                $subtotal = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                $acumulado_subtotal = $subtotal + $acumulado_subtotal;

                $unidades  = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                $unidades  = number_format($unidades, 0);
                $acumulado_unidades  = $unidades + $acumulado_unidades;
                
                $descuento = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","codigo_orden_compra='$datos_encabezado->codigo'");
                $acumulado_descuento = $descuento + $acumulado_descuento;

                $total_iva = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","codigo_orden_compra='$datos_encabezado->codigo'");
                $acumulado_iva = $total_iva + $acumulado_iva;

                $total_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$datos_encabezado->codigo'");
                $acumulado_total = $total_orden + $acumulado_total;

                /*** Ordenes ***/
                $fecha_orden  = $datos_encabezado->fecha_documento;
                $estado       = $datos_encabezado->estado;
                $subtotal     = number_format($subtotal, 0);
                $subtotal     = str_replace(',', '.', $subtotal);  
                $descuento    = number_format($descuento, 0);
                $descuento    = str_replace(',', '.', $descuento); 
                $total_iva    = number_format($total_iva, 0);
                $total_iva    = str_replace(',', '.', $total_iva);
                $total_orden  = number_format($total_orden, 0);
                $total_orden  = str_replace(',', '.', $total_orden);
            }
        }
    }
    
    $acumulado_subtotal    = number_format($acumulado_subtotal, 0);
    $acumulado_subtotal    = str_replace(',', '.', $acumulado_subtotal);  
    $acumulado_descuento   = number_format($acumulado_descuento, 0);
    $acumulado_descuento   = str_replace(',', '.', $acumulado_descuento); 
    $acumulado_iva         = number_format($acumulado_iva, 0);
    $acumulado_iva         = str_replace(',', '.', $acumulado_iva);
    $acumulado_total       = number_format($acumulado_total, 0);
    $acumulado_total       = str_replace(',', '.', $acumulado_total);
    $datos = array(
        $dato1 ='1',
        $dato2 ='2',
    );
    
    HTTP::enviarJSON($datos);
    exit;
}
/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;

    $tipo_orden = array(
        "0" => $textos["ORDEN_GRABADAS"],
        "1" => $textos["ORDEN_PARCIALES"],
        "2" => $textos["ORDEN_ANULADAS"],
        "3" => $textos["ORDEN_CUMPLIDAS"],
        "4" => $textos["TODAS"]
    );

    /*** Obtener lista de sucursales para selección ***/
    $tablas = array(
        "a" => "perfiles_usuario",
        "c" => "sucursales"
    );
    $columnas = array(
        "codigo" => "a.codigo_sucursal",
        "nombre" => "c.nombre"
    );

    $tipo_listado = array(
        "1" => "PDF",
        "2" => "EXCEL"
    );

    $condicion = "c.codigo = a.codigo_sucursal AND a.codigo_usuario = '$sesion_id_usuario_ingreso'";
    $consulta_privilegios = SQL::seleccionar($tablas, $columnas, $condicion, "", "");
    $sucursales = array();

    /*** Definición de pestañas para datos del tercero***/
    $formularios["PESTANA_REPORTE"] = array(
        array(
            HTML::contenedor(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector5", $textos["PROYECTO"], 40, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable")),
                            HTML::boton("botonAgregarArticulo", $textos["GRAFICAR"], "grafica()", "graficar","","etiqueta"),
                            HTML::figura("highcharts-figure",
                                HTML::div("container",""),""
                            ) 
                        )
                    ),$textos["ANALISIS"]
                )
            )
        ) 
    );
    $formularios["PESTANA_REPORTE"] = array_merge($formularios["PESTANA_REPORTE"],$sucursales);
    /*** Definición de botones ***/
    $contenido = HTML::generarPestanas($formularios);

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $ruta_archivo = "";
    $cargaPdf     = 0;
    $ruta         = "";
    $error        = false;
    $mensaje      = $textos["PLANO_GENERADO"];
    
    $llave_proyecto  = explode("-", $forma_selector5);
    $codigo_proyecto = $llave_proyecto[0];
    
    /*** Validar ingreso de campo fecha al formulario ***/
    if (empty($forma_selector5)){
        $error   = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];
    } else {
        /*** Obtener los datos de la tabla ***/
        $ordenes_compra   = SQL::seleccionar(array("ordenes_compra"),array("*"),"$condicion_estado prefijo_codigo_proyecto='$codigo_proyecto'");
        $datos_encabezado = SQL::filaEnObjeto($ordenes_compra);

        $nombre_proyecto  = SQL::obtenerValor("proyectos","nombre","codigo='$datos_encabezado->prefijo_codigo_proyecto'");
        $codigo_empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$datos_encabezado->codigo_sucursal'");
        $nombre_sucursal  = SQL::obtenerValor("sucursales","nombre","codigo='$datos_encabezado->codigo_sucursal'");
        $nombre_empresa   = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
        $valor_proyecto   = SQL::obtenerValor("proyectos","valor_proyecto","codigo='$datos_encabezado->prefijo_codigo_proyecto'");
        /*** Obtener los datos de la tabla ***/
        $ordenes_compra = SQL::seleccionar(array("ordenes_compra"),array("*"),"$condicion_estado prefijo_codigo_proyecto='$codigo_proyecto'");
        //Se lee el movimiento de la tabla movimientos
        if (SQL::filasDevueltas($ordenes_compra)){
            while($datos_encabezado=SQL::filaEnObjeto($ordenes_compra)){
                
                $movimiento_ordenes  = SQL::seleccionar(array("movimiento_ordenes_compra"),array("*"),"codigo_orden_compra='$datos_encabezado->codigo'");    
                
                $id_sucursal     = SQL::obtenerValor("ordenes_compra","codigo_sucursal","prefijo_codigo_proyecto='$codigo_proyecto' LIMIT 0,1");
                $codigo_empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$id_sucursal'");
                $nombre_empresa  = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
                $numero_orden    = $datos_encabezado->numero_consecutivo;
                $nit             = SQL::obtenerValor("ordenes_compra","documento_identidad_proveedor","numero_consecutivo='$numero_orden'");
                $proveedor       = SQL::obtenerValor("menu_proveedores","PROVEEDOR","DOCUMENTO_PROVEEDOR ='$nit'");
                $grabar_registro = 1;
                
                if ($grabar_registro==1){

                    $consorciado = SQL::obtenerValor("sucursales","nombre","codigo='$datos_encabezado->codigo_sucursal'");
                    
                    if ($datos_encabezado->estado == '0'){
                       $estado_orden = $textos["ESTADO_0"];
                    }
                    if ($datos_encabezado->estado == '1'){
                       $estado_orden = $textos["ESTADO_1"];
                    }
                    if ($datos_encabezado->estado == '2'){
                       $estado_orden = $textos["ESTADO_2"];
                    }
                    if ($datos_encabezado->estado == '3'){
                       $estado_orden = $textos["ESTADO_3"];
                    }
                    if ($datos_encabezado->estado == '4'){
                       $estado_orden = $textos["ESTADO_4"];
                    }
                    
                    $subtotal = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                    $acumulado_subtotal = $subtotal + $acumulado_subtotal;

                    $unidades  = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$datos_encabezado->codigo'");
                    $unidades  = number_format($unidades, 0);
                    $acumulado_unidades  = $unidades + $acumulado_unidades;
                    
                    $descuento = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","codigo_orden_compra='$datos_encabezado->codigo'");
                    $acumulado_descuento = $descuento + $acumulado_descuento;

                    $total_iva = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","codigo_orden_compra='$datos_encabezado->codigo'");
                    $acumulado_iva = $total_iva + $acumulado_iva;

                    $total_orden = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$datos_encabezado->codigo'");
                    $acumulado_total = $total_orden + $acumulado_total;

                    /*** Ordenes ***/
                    $fecha_orden  = $datos_encabezado->fecha_documento;
                    $estado       = $datos_encabezado->estado;
                    $subtotal     = number_format($subtotal, 0);
                    $subtotal     = str_replace(',', '.', $subtotal);  
                    $descuento    = number_format($descuento, 0);
                    $descuento    = str_replace(',', '.', $descuento); 
                    $total_iva    = number_format($total_iva, 0);
                    $total_iva    = str_replace(',', '.', $total_iva);
                    $total_orden  = number_format($total_orden, 0);
                    $total_orden  = str_replace(',', '.', $total_orden);
                }
            }
        }
        
        $acumulado_subtotal    = number_format($acumulado_subtotal, 0);
        $acumulado_subtotal    = str_replace(',', '.', $acumulado_subtotal);  
        $acumulado_descuento   = number_format($acumulado_descuento, 0);
        $acumulado_descuento   = str_replace(',', '.', $acumulado_descuento); 
        $acumulado_iva         = number_format($acumulado_iva, 0);
        $acumulado_iva         = str_replace(',', '.', $acumulado_iva);
        $acumulado_total       = number_format($acumulado_total, 0);
        $acumulado_total       = str_replace(',', '.', $acumulado_total);
    }    

    /*** Enviar datos con la respuesta del proceso al script que origino la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
