<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* SEM :: Software empresarial a la medida
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
$indicador = 0;
$tabla                      = "usuarios";
$columnas                   = SQL::obtenerColumnas($tabla);
$consulta                   = SQL::seleccionar(array($tabla), $columnas, "usuario = '$sesion_usuario'");
$datos                      = SQL::filaEnObjeto($consulta);
$sesion_id_usuario_ingreso  = $datos->codigo;
/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_localidades", $url_q);
    }
    if ($url_item == "selector3") {
        echo SQL::datosAutoCompletar("seleccion_bancos", $url_q);
    }
    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("menu_proveedores", $url_q);
    }
    if (($url_item) == "selector5") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }
    if (($url_item) == "selector6") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    if (($url_item) == "selector7") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }
    if (($url_item) == "selector8") {
        echo SQL::datosAutoCompletar("seleccion_referencias_proveedor", $url_q);
    }
    exit;

} elseif (isset($url_cargarDatosVendedor)) {
    if (!empty($url_codigo_vendedor)) {

        $consulta = SQL::seleccionar(array("vendedores_proveedor"), array("*"), "codigo = '$url_codigo_vendedor'", "", "");
        $tabla    = array();

        if (SQL::filasDevueltas($consulta)) {
            $datos = SQL::filaEnObjeto($consulta);

            $tabla = array(
                $datos->codigo,
                $datos->documento_proveedor,
                $datos->primer_nombre,
                $datos->segundo_nombre,
                $datos->primer_apellido,
                $datos->segundo_apellido,
                $datos->celular,
                $datos->correo,
                $datos->activo
            );
        } else {
            $tabla[] = "";
        }
        HTTP::enviarJSON($tabla);
    }
    exit;

} elseif (isset($url_cargarProveedor)) {
    if (!empty($url_nit_proveedor)) {

        $codigo_proyecto = $url_codigo_proyecto;
        $tipo_persona    = SQL::obtenerValor("terceros", "tipo_persona", "documento_identidad = '$url_nit_proveedor' AND activo = '1'");
        
        if ($tipo_persona == '2' || $tipo_persona == '4') {

            //Genera digito de verificacion en nit
            $nit     = $url_nit_proveedor;
            $array   = array(1 => 3, 4 => 17, 7 => 29, 10 => 43, 13 => 59, 2 => 7, 5 => 19, 8 => 37, 11 => 47, 14 => 67, 3 => 13,
                             6 => 23, 9 => 41, 12 => 53, 15 => 71);
            $x       = 0;
            $y       = 0;
            $z       = strlen($nit);
            $digitoV = '';
    
            for ($i = 0; $i < $z; $i++) {
                $y  = substr($nit, $i, 1);
                $x += ($y*$array[$z-$i]);
            }
    
            $y = $x%11;
    
            if ($y > 1) {
                $digitoV = 11-$y;
                //return $digitoV;
            } else {
                $digitoV = $y;
            }

            $nombre_proveedor = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '$url_nit_proveedor' AND activo = '1'");
   
        } else{
            $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad ='$url_nit_proveedor' AND activo ='1'");
            $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad ='$url_nit_proveedor' AND activo ='1'");
            $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad ='$url_nit_proveedor' AND activo ='1'");
            $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad ='$url_nit_proveedor' AND activo='1'");
            $nombre_proveedor = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
        }

        $codigo_vendedor       = SQL::obtenerValor("vendedores_proveedor", "codigo", "documento_proveedor ='$url_nit_proveedor' AND activo='1'");
        $nombre_vendedor       = SQL::obtenerValor("menu_vendedores_proveedor", "NOMBRE_COMPLETO", "DOCUMENTO ='$url_nit_proveedor'");
        $direccion             = SQL::obtenerValor("terceros", "direccion_principal", "documento_identidad ='$url_nit_proveedor' AND activo='1'");
        $correo_electronico    = SQL::obtenerValor("vendedores_proveedor", "correo", "documento_proveedor ='$url_nit_proveedor' AND activo='1'");
        $celular               = SQL::obtenerValor("vendedores_proveedor", "celular", "documento_proveedor ='$url_nit_proveedor' AND activo='1'");

        $codigo_dane_departamento = SQL::obtenerValor("terceros", "codigo_dane_departamento_localidad", "documento_identidad ='$url_nit_proveedor' AND activo='1'");
        $codigo_dane_municipio    = SQL::obtenerValor("terceros", "codigo_dane_municipio_localidad", "documento_identidad ='$url_nit_proveedor' AND activo='1'");
        $municipios               = SQL::obtenerValor("municipios","nombre","codigo_dane_municipio = '$codigo_dane_municipio' AND codigo_dane_departamento = '$codigo_dane_departamento' LIMIT 0,1");

        //Asignar codigo siguiente de la tabla 
        $prefijo_orden_compra  = SQL::obtenerValor("proyectos","codigo","codigo = '$codigo_proyecto'");
        $numero_orden_compra   = SQL::obtenerValor("ordenes_compra","MAX(numero_consecutivo)","codigo>0");

        if ($numero_orden_compra){
            intval($numero_orden_compra++);
        } else {
            intval($numero_orden_compra = 1);
        }

        $tabla = array();
        $tabla = array(
          $nombre_proveedor,
          $digitoV,
          $vendedor_proveedor,
          $nombre_vendedor,
          $direccion,
          $correo_electronico,
          $celular,
          $municipios,
          $prefijo_orden_compra,
          $numero_orden_compra
        );    

        HTTP::enviarJSON($tabla);
        exit();
    }

} elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."' AND codigo !='0'");
    }

    HTTP::enviarJSON($respuesta);
    exit();

} elseif (isset($url_cargarDatosArticuloCreado)) {

    if (!empty($url_referencia_carga)) {
        $nit_proveedor   = $url_nit_proveedor;   
        $respuesta       = array();

        $codigo_articulo = SQL::obtenerValor("referencias_proveedor", "codigo_articulo", "referencia = '$url_referencia_carga' AND principal = '1' LIMIT 1");
        $activo                        = SQL::obtenerValor("articulos", "activo", "codigo = '$codigo_articulo'");
        $estructura_grupos             = SQL::obtenerValor("articulos", "codigo_estructura_grupo", "codigo = '$codigo_articulo'");
        $nodo_estructura_grupos        = SQL::obtenerValor("estructura_grupos", "codigo_padre", "codigo = '$estructura_grupos'");
        $grupo_estructura_grupos       = SQL::obtenerValor("estructura_grupos", "codigo_grupo", "codigo = '$estructura_grupos'");

        $codigo_barras                 = SQL::obtenerValor("referencias_proveedor", "codigo_barras", "referencia = '$url_referencia_carga' AND principal = '1'");
        //$documento_identidad_proveedor = SQL::obtenerValor("referencias_proveedor", "documento_identidad_proveedor", "referencia = '$url_referencia_carga' AND principal = '1'");     

        //$documento_identidad_proveedor = SQL::obtenerValor("seleccion_proveedores", "nombre", "id = '$documento_identidad_proveedor'");
 
        // Realiza consulta para mandar datos a java script y cargar si codigo existe - faltan algunos no esenciales
        $consulta                = SQL::seleccionar(array("articulos"), array("*"), "codigo = '$codigo_articulo' AND activo = '1'", "", "codigo", 1);
        $codigo_impuesto_compra  = SQL::obtenerValor("articulos", "codigo_impuesto_compra", "codigo = '$codigo_articulo'");
        $nombre_impuesto_compra  = SQL::obtenerValor("tasas", "descripcion", "codigo = '$codigo_impuesto_compra'");
        $codigo_impuesto_venta   = SQL::obtenerValor("articulos", "codigo_impuesto_venta", "codigo = '$codigo_articulo'");
        $nombre_impuesto_venta   = SQL::obtenerValor("tasas", "descripcion", "codigo = '$codigo_impuesto_venta'");
        $codigo_unidad_compra    = SQL::obtenerValor("articulos", "codigo_unidad_compra", "codigo = '$codigo_articulo'");
        $nombre_unidad_compra    = SQL::obtenerValor("tipos_unidades", "nombre", "codigo = '$codigo_unidad_compra'");
        $codigo_estructura_grupo = SQL::obtenerValor("articulos", "codigo_estructura_grupo", "codigo = '$codigo_articulo'");
        $codigo_padre            = SQL::obtenerValor("estructura_grupos", "codigo_padre", "codigo = '$codigo_estructura_grupo'");
        $codigo_grupo            = SQL::obtenerValor("estructura_grupos", "codigo_grupo", "codigo = '$codigo_estructura_grupo'");
        $costo                   = SQL::obtenerValor("lista_precio_articulos", "costo", "codigo_articulo = '$codigo_articulo'");
        $consulta_imagen         = SQL::seleccionar(array("imagenes"), array("id_asociado","categoria","ancho","alto"), "id_asociado = '$codigo_articulo' AND categoria ='2'");
        $imagen                  = SQL::filaEnObjeto($consulta_imagen);
        if ($imagen) {
            $id_asociado = $imagen->id_asociado;
            $categoria   = $imagen->categoria;
            $ancho       = $imagen->ancho;
            $alto        = $imagen->alto;
        }

        if (SQL::filasDevueltas($consulta)) {

            $datos         = SQL::filaEnObjeto($consulta);
            $codigo_marca  = SQL::obtenerValor("marcas", "codigo", "codigo = '$datos->codigo'");
            $nombre_marca  = SQL::obtenerValor("marcas", "descripcion", "codigo = '$datos->codigo'");
          
            if($datos->codigo != ""){
                $respuesta[0]  = $datos->codigo;
                $respuesta[1]  = $datos->descripcion;
                $respuesta[2]  = $datos->tipo_articulo;
                $respuesta[3]  = $datos->ficha_tecnica;
                $respuesta[4]  = $datos->alto;
                $respuesta[5]  = $datos->ancho;
                $respuesta[6]  = $datos->profundidad;
                $respuesta[7]  = $datos->peso;
                $respuesta[8]  = $datos->codigo_impuesto_compra;
                $respuesta[9]  = $datos->codigo_impuesto_venta;
                $respuesta[10] = $datos->codigo_marca;
                $respuesta[11] = $datos->codigo_estructura_grupo;
                $respuesta[12] = $datos->manejo_inventario;
                $respuesta[13] = $datos->codigo_unidad_venta;
                $respuesta[14] = $datos->codigo_unidad_compra;
                $respuesta[15] = $datos->codigo_unidad_presentacion;
                $respuesta[16] = $datos->codigo_iso;
                $respuesta[17] = $datos->activo;
                $respuesta[18] = $datos->imprime_listas;
                $respuesta[19] = $datos->fecha_creacion;
                $respuesta[20] = $documento_identidad_proveedor;
                $respuesta[21] = $codigo_barras;
                $respuesta[22] = $codigo_marca;
                $respuesta[23] = $nombre_marca;
                $respuesta[24] = $nombre_tipo_articulo;
                $respuesta[25] = $nombre_impuesto_compra;
                $respuesta[26] = $nombre_impuesto_venta;
                $respuesta[27] = $nombre_unidad_compra;
                $respuesta[28] = $codigo_estructura_grupo;
                $respuesta[29] = $codigo_padre;
                $respuesta[30] = $codigo_grupo;
                $respuesta[31] = $costo;
                $respuesta[32] = $id_asociado;
                $respuesta[33] = $categoria;
                $respuesta[34] = $ancho;
                $respuesta[35] = $alto;
            } else{
                $respuesta[0]  = "";
                HTTP::enviarJSON($respuesta);
                exit();
            }
        }
        HTTP::enviarJSON($respuesta);
        exit();
    }

} elseif (isset($url_recargarComprador)) {

    if (!empty($url_empresa)) {

        $empresa              = $url_empresa;
        $razon_social_empresa = SQL::obtenerValor("empresas", "razon_social", "codigo = '$empresa'");
        $consulta             = SQL::seleccionar(array("menu_compradores"), array("*"), "RAZON_SOCIAL = '$razon_social_empresa'");
        $tabla                = array();

        if (SQL::filasDevueltas($consulta)) {
            while($datos = SQL::filaEnObjeto($consulta)){
                $documento.= $datos->DOCUMENTO."-";
                $nombre.= $datos->NOMBRE_COMPLETO."-";
            }   
        }
        $documento = trim($documento,"-");
        $nombre    = trim($nombre,"-");
    /*******************************************************/

    $elementos[0] = $documento;
    $elementos[1] = $nombre;
    
    HTTP::enviarJSON($elementos);
    
    }
    exit;

} elseif (isset($url_recargarVendedor)) {

    if (!empty($url_nit_proveedor)) {

        $proveedor = $url_nit_proveedor;
        $consulta  = SQL::seleccionar(array("menu_vendedores_proveedor"), array("*"), "DOCUMENTO = '$proveedor'");

        if (SQL::filasDevueltas($consulta)) {
            while($datos = SQL::filaEnObjeto($consulta)){
                $id.= $datos->id."-";
                $nombre.= $datos->NOMBRE_COMPLETO."-";
            }   
        }
        $id     = trim($id,"-");
        $nombre = trim($nombre,"-");
    /*******************************************************/
        $elementos[0] = $id;
        $elementos[1] = $nombre;
        HTTP::enviarJSON($elementos);
    }
    exit;    

} elseif (!empty($url_recargarProyecto)) {

    $respuesta = HTML::generarDatosLista("proyectos", "codigo", "nombre", "codigo_empresa_ejecuta = '".$url_codigo."'");
    HTTP::enviarJSON($respuesta);
    exit();

/*} elseif (isset($url_recargarProyecto)) {

    if (!empty($url_empresa)) {

        $empresa_ejecuta      = $url_empresa;
        $sucursal_ejecuta     = $url_sucursal;
        $consulta             = SQL::seleccionar(array("proyectos"), array("*"), "codigo_empresa_ejecuta = '$empresa_ejecuta' AND codigo_sucursal_ejecuta='$sucursal_ejecuta'");
        if (SQL::filasDevueltas($consulta)) {
            while($datos = SQL::filaEnObjeto($consulta)){
                $tabla = array(
                    $codigo = $datos->codigo,
                    $nombre = $datos->nombre
                );
            } 
        }
    }
    HTTP::enviarJSON($tabla);
    exit;
*/
}  elseif (isset($url_cargarNit)) {
    if (!empty($url_codigo_empresa)) {

        $empresa  = $url_codigo_empresa;
        $nit      = SQL::obtenerValor("empresas", "documento_identidad_tercero", "codigo = '$empresa'");

        HTTP::enviarJSON($nit);
    }
    exit;

} elseif (isset($url_grabarEncabezado)) {
    if (!empty($url_nit_proveedor)) {

        $codigo_proyecto     = $url_codigo_proyecto;
        $sucursal            = $url_sucursal;
        $fecha_documento     = $url_fecha_documento;
        $numero_orden        = $url_numero_orden;
        $codigo_comprador    = $url_codigo_comprador;
        $codigo_moneda       = $url_codigo_moneda;
        $dias_pago           = $url_dias_pago;
        $documento_proveedor = $url_nit_proveedor;
        $tipo_documento      = $url_tipos_documento;
        $solicitante         = $url_solicitante;
        $descuento           = $url_descuento;
        $respuesta           = array();

        $descuento        = str_replace(",", ".", $descuento);
        $estado_orden     = SQL::obtenerValor("ordenes_compra", "estado", "numero_consecutivo = '$numero_orden' AND prefijo_codigo_proyecto = '$codigo_proyecto'");
        $codigo_comprador = SQL::obtenerValor("compradores", "codigo", "documento_identidad = '$codigo_comprador'");

        if($estado_orden == false){

            $datos_encabezado = array(                         
                "codigo_sucursal"                   => $sucursal,   
                "codigo_tipo_documento"             => $tipo_documento,
                "fecha_documento"                   => $fecha_documento,
                "prefijo_codigo_proyecto"           => $codigo_proyecto,
                "numero_consecutivo"                => $numero_orden,
                "documento_identidad_proveedor"     => $documento_proveedor,
                "codigo_comprador"                  => $codigo_comprador,
                "cantidad_registros"                => 0,
                "cantidad_cumplidos"                => 0,
                "estado"                            => 4,
                "codigo_usuario_orden_compra"       => $sesion_id_usuario_ingreso,
                "codigo_usuario_anula"              => "",
                "estado_aprobada"                   => 0,
                "codigo_usuario_aprueba"            => "",
                "codigo_moneda"                     => $codigo_moneda,
                "descuento_global1"                 => $descuento,
                "descuento_global2"                 => 0,
                "descuento_global3"                 => 0,
                "descuento_financiero_fijo"         => 0,
                "descuento_financiero_pronto_pago"  => 0,
                "numero_dias_pronto_pago"           => 0,
                "iva_incluido"                      => 0,
                "codigo_numero_dias_pago"           => $dias_pago, 
                "observaciones"                     => " ",
                "imprimio"                          => 0,
                "fecha_registra"                    => date("Y-m-d H:i:s"),
                "fecha_modificacion"                => "",
                "solicitante"                       => $solicitante
            );

            $insertar_orden = SQL::insertar("ordenes_compra", $datos_encabezado);    

            if($insertar_orden = false){
                $respuesta[0]  = false;
                $respuesta[1]  = $textos["ERROR_GRABAR_ENCABEZADO"];
            }
            HTTP::enviarJSON($respuesta);
            exit();
        }
    }

} elseif (isset($forma_insertar_movimiento)){
    //$grabar_movimiento       = true;
    $numero_orden            = $forma_numero_orden;
    $referencia              = $forma_referencia;
    $descripcion             = $forma_descripcion;
    $cantidad_total_articulo = $forma_cantidad_total_articulo;
    $unidad_compra           = $forma_codigo_unidad_compra;
    $costo_unitario          = $forma_costo_unitario;
    $subtotal                = $forma_subtotal;
    $observaciones_articulo  = $forma_observaciones_articulo;
    $id_sucursal_orden       = $forma_id_sucursal_orden;
    $nit_proveedor           = $forma_nit_proveedor;
    $fecha_entrega_orden     = $forma_fecha_entrega_orden;
    $indice                  = $forma_indice;
    $vendedor_proveedor      = $forma_vendedor_proveedor;
    $descuento               = $forma_descuento;

    /*** Quitar separador de miles a un numero ***/
    function quitarMiles($cadena){
        $valor = array();
        for ($i = 0; $i < strlen($cadena); $i++) {
            if (substr($cadena, $i, 1) != ",") {
                $valor[$i] = substr($cadena, $i, 1);
            }
        }
        $valor = implode($valor);
        return $valor;
    }

    $codigo_orden_compra     = SQL::obtenerValor("ordenes_compra", "codigo", "numero_consecutivo = '$numero_orden'");
    $descuento               = SQL::obtenerValor("ordenes_compra", "descuento_global1", "numero_consecutivo = '$numero_orden'");
    $costo_unitario          = quitarMiles($costo_unitario);
    $subtotal                = quitarMiles($subtotal);
    $valor_descuento_global1 = ($subtotal * $descuento)/100;
    
    $codigo_articulo         = SQL::obtenerValor("referencias_proveedor", "codigo_articulo", "referencia = '$referencia' LIMIT 0,1");
    $tasa_iva                = SQL::obtenerValor("articulos", "codigo_impuesto_compra", "codigo = '$codigo_articulo'");
    $porcentaje_impuesto     = SQL::obtenerValor("vigencia_tasas", "porcentaje", "codigo_tasa = '$tasa_iva'");
    
    $valor_iva               = (($subtotal - $valor_descuento_global1) * $porcentaje_impuesto) /100;
    $neto_pagar              = ($subtotal - $valor_descuento_global1) + $valor_iva;
    $consecutivo             = SQL::obtenerValor("movimiento_ordenes_compra", "MAX(consecutivo)", "codigo_orden_compra = '$codigo_orden_compra'");
    $nombre_unidad_compra    = SQL::obtenerValor("tipos_unidades", "nombre", "codigo = '$unidad_compra'");

    if ($consecutivo){
            intval($consecutivo++);
    } else {
            intval($consecutivo = 1);
    }

    $datos_movimiento = array(                         
        "codigo_orden_compra"      => $codigo_orden_compra,   
        "consecutivo"              => $consecutivo,
        "codigo_articulo"          => $codigo_articulo,
        "referencia_articulo"      => $referencia,
        "codigo_sucursal_destino"  => $id_sucursal_orden, 
        "estado"                   => 4,
        "codigo_unidad_medida"     => $unidad_compra,
        "cantidad_total"           => $cantidad_total_articulo,
        "valor_total"              => $subtotal,
        "descuento_global1"        => $descuento,
        "valor_descuento_global1"  => $valor_descuento_global1, 
        "descuento_global2"        => 0, 
        "valor_descuento_global2"  => 0,
        "descuento_global3"        => 0,
        "valor_descuento_global3"  => 0,
        "valor_unitario"           => $costo_unitario,
        "neto_pagar"               => $neto_pagar,
        "valor_iva"                => $valor_iva,
        "codigo_tasa_impuesto"     => $tasa_iva,
        "porcentaje_impuesto"      => $porcentaje_impuesto,
        "iva_incluido"             => 0,
        "fecha_entrega"            => $fecha_entrega_orden,
        "observaciones"            => $observaciones_articulo,
        "fecha_registra"           => date("Y-m-d H:i:s"),
        "fecha_modificacion"       => 0,
        "codigo_usuario_registra"  => $sesion_id_usuario_ingreso,
        "codigo_vendedor"          => $vendedor_proveedor   
    );
    
    $id_movimiento_orden = SQL::insertar("movimiento_ordenes_compra", $datos_movimiento,true);  
    
    $datos_encabezado = array(  
        "descuento_global1" => $descuento,
        "estado" => '0'
    );  

    $descuento_global1   = SQL::insertar("ordenes_compra", $datos_encabezado,true);  

    $tabla = array();
    if ($id_movimiento_orden){
        $respuesta[0]  = true;
        $respuesta[1]  = $codigo_orden_compra;
        $respuesta[2]  = $numero_orden;
        $respuesta[3]  = $referencia;
        $respuesta[4]  = $descripcion;
        $respuesta[5]  = number_format($cantidad_total_articulo,0);
        $respuesta[6]  = $nombre_unidad_compra;
        $respuesta[7]  = number_format($costo_unitario,2);
        $respuesta[8]  = number_format($subtotal,2);
        $respuesta[9]  = $observaciones_articulo;
        $respuesta[10] = $consecutivo;
        $respuesta[11] = number_format($valor_descuento_global1,2);
        $respuesta[12] = number_format($valor_iva,2);

    } else {
        $eliminar_encabezado = SQL::eliminar("ordenes_compra", "numero_consecutivo = '$numero_orden'");
        $respuesta[0]        = false;
        $respuesta[1]        = $textos["ERROR_GRABAR_MOVIMIENTO"];
    }       
    if (!isset($respuesta)){
         $respuesta = false;
    }

    HTTP::enviarJSON($respuesta);
    exit();  

} elseif (isset($url_actualiza_movimiento)){

    $grabar_movimiento       = true;
    $numero_orden            = $url_numero_orden;
    $consecutivo             = $url_id_tabla;

    $codigo_orden_compra = SQL::obtenerValor("ordenes_compra", "codigo", "numero_consecutivo = '$numero_orden'");
    $consulta_movimiento = SQL::seleccionar(array("movimiento_ordenes_compra"), array("*"), "consecutivo = '$consecutivo' AND codigo_orden_compra = '$codigo_orden_compra'");
    $tabla = array();

    if (SQL::filasDevueltas($consulta_movimiento)) {
        $datos = SQL::filaEnObjeto($consulta_movimiento);

        $referencia           = SQL::obtenerValor("referencias_proveedor", "referencia", "codigo_articulo = '$datos->codigo_articulo' AND principal = '1'");
        $descripcion          = SQL::obtenerValor("articulos", "descripcion", "codigo = '$datos->codigo_articulo'");
        $nombre_unidad_compra = SQL::obtenerValor("unidades", "nombre", "codigo_tipo_unidad = '$datos->codigo_unidad_medida'");
    
        $datos = array(
            $consecutivo,
            $referencia,
            $descripcion,
            $datos->codigo_unidad_medida,
            $nombre_unidad_compra,
            $datos->valor_unitario,
            $datos->cantidad_total,
            $datos->valor_total,
            $datos->observaciones
        );
    }

    HTTP::enviarJSON($datos);
    exit;

} elseif (isset($url_actualiza_movimiento_tabla)){

    $numero_orden            = $url_numero_orden;
    $referencia              = $url_referencia;
    $descripcion             = $url_descripcion;
    $cantidad_total_articulo = $url_cantidad_total_articulo;
    $unidad_compra           = $url_codigo_unidad_compra;
    $costo_unitario          = $url_costo_unitario;
    $subtotal                = $url_subtotal;
    $observaciones_articulo  = $url_observaciones_articulo;
    $id_sucursal_orden       = $url_id_sucursal_orden;
    $nit_proveedor           = $url_nit_proveedor;
    $fecha_entrega_orden     = $url_fecha_entrega_orden;
    $indice                  = $url_indice_tabla;
    $nit_proveedor           = $url_nit_proveedor;
    $respuesta[0]            = true;

    /*** Quitar separador de miles a un numero ***/
    function quitarMiles($cadena){
        $valor = array();
        for ($i = 0; $i < strlen($cadena); $i++) {
            if (substr($cadena, $i, 1) != ",") {
                $valor[$i] = substr($cadena, $i, 1);
            }
        }
        $valor = implode($valor);
        return $valor;
    }

    $codigo_orden_compra     = SQL::obtenerValor("ordenes_compra", "codigo", "numero_consecutivo = '$numero_orden'");
    $costo_unitario          = quitarMiles($costo_unitario);
    $subtotal                = quitarMiles($subtotal);
    $cantidad_total_articulo = quitarMiles($cantidad_total_articulo);

    $codigo_articulo         = SQL::obtenerValor("referencias_proveedor", "codigo_articulo", "referencia = '$referencia' AND documento_identidad_proveedor='$nit_proveedor' AND principal = '1'");
    $tasa_iva                = SQL::obtenerValor("articulos", "codigo_impuesto_compra", "codigo = '$codigo_articulo'");
    $porcentaje_impuesto     = SQL::obtenerValor("vigencia_tasas", "porcentaje", "codigo_tasa = '$tasa_iva'");
    $valor_iva               = ($subtotal * $porcentaje_impuesto) /100;
    $consecutivo             = SQL::obtenerValor("movimiento_ordenes_compra", "MAX(consecutivo)", "codigo_orden_compra = '$codigo_orden_compra'");
    $nombre_unidad_compra    = SQL::obtenerValor("tipos_unidades", "nombre", "codigo = '$unidad_compra'");

    $datos_movimiento_tabla = array(                         
        "cantidad_total"           => $cantidad_total_articulo,
        "valor_total"              => $subtotal,
        "valor_unitario"           => $costo_unitario,
        "neto_pagar"               => $subtotal,
        "valor_iva"                => $valor_iva,
        "observaciones"            => $observaciones_articulo,
        "fecha_modificacion"       => date("Y-m-d H:i:s"),
        "codigo_usuario_registra"  => $sesion_id_usuario_ingreso   
    );

    $modifica_movimiento_orden = SQL::modificar("movimiento_ordenes_compra", $datos_movimiento_tabla, "consecutivo = '$indice' AND codigo_orden_compra = '$codigo_orden_compra'",true);    
     
    if (!$modifica_movimiento_orden){
        $respuesta[0] = false;
        $respuesta[1] = $textos["ERROR_MODIFICAR_MOVIMIENTO"];
    }

    HTTP::enviarJSON($respuesta);
    exit;

} elseif (isset($url_eliminarMovimiento)){
    $eliminar_movimiento = SQL::eliminar("movimiento_ordenes_compra","consecutivo='$url_id_tabla'");
    $respuesta[0]        = true;

    if (!$eliminar_movimiento){
        $respuesta[0] = false;
        $respuesta[1] = $textos["ERROR_ELIMINAR_MOVIMIENTO"];
    }
    HTTP::enviarJSON($respuesta);
    exit;

}elseif (isset($url_total_pedido)){

    $observaciones_orden  = $url_observaciones_orden;
    $codigo_orden_compra  = SQL::obtenerValor("ordenes_compra","codigo","numero_consecutivo='$url_numero_orden'");
    $unidades             = SQL::obtenerValor("movimiento_ordenes_compra","SUM(cantidad_total)","codigo_orden_compra='$codigo_orden_compra'");
    $subtotal             = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_total)","codigo_orden_compra='$codigo_orden_compra'");
    $porcentaje_descuento = SQL::obtenerValor("movimiento_ordenes_compra","descuento_global1","codigo_orden_compra='$codigo_orden_compra'");
    $valor_descuento      = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_descuento_global1)","codigo_orden_compra='$codigo_orden_compra'");
    $total_iva            = SQL::obtenerValor("movimiento_ordenes_compra","SUM(valor_iva)","codigo_orden_compra='$codigo_orden_compra'");
    $total_orden          = SQL::obtenerValor("movimiento_ordenes_compra","SUM(neto_pagar)","codigo_orden_compra='$codigo_orden_compra'");
    $total_items          = SQL::obtenerValor("movimiento_ordenes_compra","COUNT(codigo_articulo)","codigo_orden_compra='$codigo_orden_compra'");

    $consulta_encabezado = SQL::seleccionar(array("ordenes_compra"), array("*"), "numero_consecutivo = '$url_numero_orden' AND documento_identidad_proveedor = '$url_nit_proveedor'");
    
    if (SQL::filasDevueltas($consulta_encabezado)) {
        while($datos = SQL::filaEnObjeto($consulta_encabezado)){
            $prefijo_codigo_proyecto = $datos->prefijo_codigo_proyecto;
            $nit_proveedor           = $datos->documento_identidad_proveedor;
        }   
    }
    $nombre_proveedor = SQL::obtenerValor("terceros","razon_social","documento_identidad='$nit_proveedor'"); 
    $proyecto         = SQL::obtenerValor("proyectos","nombre","codigo='$prefijo_codigo_proyecto'"); 

    $respuesta = array();
    if ($unidades){
        $respuesta[0]  = true;     
        $respuesta[1]  = number_format($unidades,0);
        $respuesta[2]  = number_format($subtotal,0);
        $respuesta[3]  = number_format($total_iva,0);
        $respuesta[4]  = number_format($total_orden,0);
        $respuesta[5]  = $nit_proveedor;
        $respuesta[6]  = $nombre_proveedor;
        $respuesta[7]  = $proyecto;
        $respuesta[8]  = $prefijo_codigo_proyecto;        
        $respuesta[9]  = $url_numero_orden;
        $respuesta[10] = $total_items;
        $respuesta[11] = number_format($valor_descuento);
    } else {
        $respuesta[0]  = false;
        $respuesta[1]  = number_format(0,0);
        $respuesta[2]  = number_format(0,0);
        $respuesta[3]  = number_format(0,0);
        $respuesta[4]  = number_format(0,0);
        $respuesta[11] = number_format(0,0);
    }  
    HTTP::enviarJSON($respuesta);
    exit();  

} 

if (!empty($url_generar)){

    $error  = "";
    $titulo = $componente->nombre;

    if ($sesion_usuario_maestro_ingreso){
        $sucursales  = SQL::obtenerValor("sucursales","codigo","codigo > '0' AND activo ='0'");
        $compradores = SQL::obtenerValor("compradores","codigo","codigo > '0' AND activo ='1'");
        $vendedores  = SQL::obtenerValor("vendedores_proveedor","codigo","codigo > '0' AND activo ='1'");
    } else {
        $tablas_sucursales = array(
            "s"   => "sucursales",
            "pus" => "perfiles_usuario"
        );
        $columnas_sucursales  = array(
            "codigo"          => "s.codigo",
            "nombre"          => "s.nombre_corto",
            "codigo_sucursal" => "pus.codigo_sucursal",
        );
        $condicion           = "s.codigo > '0' AND s.activo = '1' AND pus.codigo_sucursal = s.codigo";
        $consulta_sucursales = SQL::seleccionar($tablas_sucursales, $columnas_sucursales, $condicion, "", "", 0, 1);

        if (SQL::filasDevueltas($consulta_sucursales)){
            $sucursales = true;
        } else {
            $sucursales = false;
        }

        $tablas_compradores = array(
            "cpr"   => "compradores",
            "mt"    => "menu_terceros"
        );
        $columnas_compradores = array(
            "documento_identidad" => "cpr.documento_identidad",
            "id"                  => "mt.id",
            "tercero"             => "mt.NOMBRE_COMPLETO"
        );

        $condicion_compradores  = "mt.id = cpr.documento_identidad";
        $consulta_compradores   = SQL::seleccionar($tablas_compradores, $columnas_compradores, $condicion_compradores, "", "", 0, 1);
 
        if (SQL::filasDevueltas($consulta_compradores)){
            $compradores = true;
        } else {
            $compradores = false;
        }
    }

    $tipos_documentos     = SQL::obtenerValor("tipos_documentos","codigo","codigo > '0' LIMIT 0,1");
    $municipios           = SQL::obtenerValor("municipios","codigo_iso","codigo_dane_departamento > '0' LIMIT 0,1");
    $unidades             = SQL::obtenerValor("unidades","codigo","codigo > '0' LIMIT 0,1");
    $estructuras          = SQL::obtenerValor("estructura_grupos", "codigo","codigo > '0' LIMIT 0,1");
    $tasas                = SQL::obtenerValor("tasas", "codigo", "codigo > '0' LIMIT 0,1");
    $monedas              = SQL::obtenerValor("tipos_moneda", "codigo", "codigo > '0' LIMIT 0,1");
    $proyectos            = SQL::obtenerValor("proyectos", "codigo", "codigo > '0' LIMIT 0,1");
    
    if ($sucursales && $compradores && $tipos_documentos && $municipios && $unidades && $estructuras && $tasas && $monedas && $proyectos){

        if ($sesion_usuario_maestro_ingreso){
            $sucursales  = HTML::generarDatosLista("sucursales","codigo","nombre_corto","codigo > '0' AND activo = '0'");
            $compradores = HTML::generarDatosLista("menu_compradores","id","NOMBRE_COMPLETO","id > '0' AND id_activo = 'Activo'");
            $vendedores  = HTML::generarDatosLista("menu_vendedores_proveedor","id","NOMBRE_COMPLETO","id > '0' AND ACTIVO = Activo'");
        } 

        $tipos_documentos      = HTML::generarDatosLista("tipos_documentos","codigo","descripcion","codigo > '0'");
        $unidades              = HTML::generarDatosLista("unidades","codigo","nombre","codigo > '0'");
        $monedas               = HTML::generarDatosLista("tipos_moneda","codigo","nombre","codigo > '0'");
        $tasas                 = HTML::generarDatosLista("tasas", "codigo", "descripcion","codigo > '0'");
        $marcas                = HTML::generarDatosLista("marcas", "codigo", "descripcion","codigo > '0'");
        $tipo_comprobante      = HTML::generarDatosLista("tipos_comprobantes", "codigo", "descripcion","codigo > '0'");
        $tipos_documento_orden = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '1' AND abreviaturas = 'OC'");
        $compradores           = HTML::generarDatosLista("menu_compradores", "id", "NOMBRE_COMPLETO","id > '0'");

        $regimen = array(
            "1" => $textos["COMUN"],
            "2" => $textos["SIMPLIFICADO"]
        );

        $encabezado["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("nit_proveedor_mostrar",$textos["NIT_PROVEEDOR"], "", "", array("class"=>"oculto")),
                HTML::mostrarDato("razon_social_proveedor_mostrar",$textos["RAZON_SOCIAL_PROVEEDOR"], "", "", array("class"=>"oculto")),
                HTML::mostrarDato("numero_propuesta",$textos["NUMERO_PROPUESTA"], "", "", array("class"=>"oculto"))
            ),
            array(
                //HTML::campoTextoCorto("*nit_proveedor",$textos["NIT_PROVEEDOR"], 15, 15, "", array("title"=>$textos["AYUDA_NIT_PROVEEDOR"],"onKeyPress"=>"return campoEntero(event)", "onBlur"=>"cargarProveedor()")),

                HTML::campoTextoCorto("*razon_social_proveedor",$textos["RAZON_SOCIAL_PROVEEDOR"], 45, 255, "", array("title"=>$textos["AYUDA_RAZON_SOCIAL_PROVEEDOR"],"class"=>"autocompletable", "onFocus" => "acProveedor(this)"))
            )
        );
        //$funciones["PESTANA_DATOS_PEDIDO"]   = "cargarProveedor()";
        $formularios["PESTANA_DATOS_PEDIDO"] = array(
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::mostrarDato("prefijo_orden",$textos["PREFIJO_ORDEN_COMPRA"], "", "", array("class"=>"oculto")),
                            HTML::campoTextoCorto("numero_orden", $textos["NUMERO_ORDEN_COMPRA"], 9, 9, "", array("readonly" => "true", "disabled" => "true")),
                            HTML::campoTextoCorto("fecha_documento",$textos["FECHA_DOCUMENTO"], 8, 8, date("Y-m-d"), array("title"=>$textos["AYUDA_FECHA"],"", "readOnly"=>"true"))
                                .HTML::campoOculto("minDate", date("Y-m-d")),
                                //array("id"=>"fecha_pedido","class"=>"fecha_pedido"),

                            HTML::campoTextoCorto("fecha_entrega", $textos["FECHA_ENTREGA"], 10, 10, date("Y-m-d"), array("class"=>"selectorFechaBloquear"), array("title" => $textos["AYUDA_FECHA_ENTREGA"], "onBlur" => "validarItem(this);"))
                        ),
                        array(    
                            HTML::listaSeleccionSimple("*dias_pago", $textos["NUMERO_DIAS_PAGO"], HTML::generarDatosLista("plazos_pago_proveedores", "codigo", "descripcion", "codigo!='0'"), "", array("title",$textos["AYUDA_NUMERO_DIAS_PAGO"])),
                            
                            HTML::listaSeleccionSimple("*id_moneda",$textos["MONEDA"], $monedas, 1, array("title",$textos["AYUDA_MONEDA"])),

                            HTML::listaSeleccionSimple("*tipos_documento",$textos["TIPO_DOCUMENTO"], HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","descripcion='ORDEN DE COMPRA' OR descripcion='orden de compra'"), "", array("disabled" => "true"), array("title",$textos["AYUDA_TIPO_DOCUMENTO"]))
                        ),
                        array(    
                            HTML::listaSeleccionSimple("*empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social",""), "", array("title" => $textos["AYUDA_EMPRESAS"], "class"=>"empresa", "onBlur" => "validarItem(this);","onChange" => "recargarListaEmpresas(),recargarProyectos()", "onClick" => "recargarComprador(),cargaNit()")),
                           
                            HTML::campoTextoCorto("*nit_empresa",$textos["NIT"], 13, 15, "", array("disabled" => "true"), array("title",$textos["AYUDA_NIT"], "", "")) 
                        ),
                        array(
                            HTML::listaSeleccionSimple("*sucursal", $textos["CONSORCIO"], HTML::generarDatosLista("sucursales", "codigo", "nombre",""), "", "", array("title" => $textos["AYUDA_CONSORCIO"], "onBlur" => "validarItem(this)")),
                            HTML::campoOculto("id_sucursal", ""),

                            HTML::listaSeleccionSimple("*codigo_comprador",$textos["COMPRADOR"], "", "", array("disabled" => "true"), array("title",$textos["AYUDA_COMPRADOR"])),

                            HTML::marcaChequeo("aplica_descuento",$textos["DESCUENTO"], 1, false, array("title"=>$textos["AYUDA_APLICA_DESCUENTO"], "class"=>"descuento_linea modificar","onClick"=>"activaCampos(4,0)")),

                            HTML::campoTextoCorto("descuento",$textos["PORCENTAJE"], 2, 8, "", array("title"=>$textos["AYUDA_APLICA_DESCUENTO"], "class"=>"linea oculto modificar"))
                        ),
                        array(
                           HTML::listaSeleccionSimple("*proyecto", $textos["PROYECTO"], "", "", array("disabled" => "true"), array("title" => $textos["AYUDA_PROYECTOS"], "")),

                           HTML::campoTextoCorto("*solicitante",$textos["SOLICITANTE"], 40, 255, "", array("disabled" => "true"), array("title",$textos["AYUDA_SOLICITANTE"]))                       
                        ),
                    ),
                    $textos["DATOS_FACTURACION"]
                )
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            HTML::campoTextoCorto("*selector4",$textos["NIT_PROVEEDOR"], 15, 15, "", array("title"=>$textos["AYUDA_NIT_PROVEEDOR"],"class" => "autocompletable", "onKeyPress"=>"return campoEntero(event)", "onBlur"=>"cargarProveedor()")),

                            HTML::campoTextoCorto("digito_verificacion", $textos["DIGITO_VERIFICACION"], 1, 1, "", array("readonly" => "true","Class" => "oculto"))
                            .HTML::campoOculto("documento_identidad_proveedor", ""),

                            HTML::campoTextoCorto("*razon_social_proveedor",$textos["RAZON_SOCIAL_PROVEEDOR"], 45, 255, "", array("readonly" => "true"), array("title"=>$textos["AYUDA_RAZON_SOCIAL_PROVEEDOR"], "")),

                            HTML::listaSeleccionSimple("*vendedor_proveedor", $textos["VENDEDOR"], "", "", array("title",$textos["AYUDA_VENDEDOR"],"onClick" => "recargarVendedores(), cargarDatosVendedor()"))
                        ),
                        array(
                            HTML::campoTextoCorto("*direccion",$textos["DIRECCION"], 40, 255, "", array("readonly" => "true"), array("title",$textos["AYUDA_DIRECCION"])),
                            
                            HTML::campoTextoCorto("*selector1",$textos["MUNICIPIO"], 40, 255, "", array("readonly" => "true"), array("title",$textos["AYUDA_MUNICIPIO"], "class"=>"autocompletable"))
                            .HTML::campoOculto("id_municipio","")
                        ),    
                        array(
                            HTML::campoTextoCorto("*correo_electronico",$textos["CORREO_ELECTRONICO"], 40, 255, "", array("readonly" => "true"), array("title",$textos["AYUDA_CORREO_ELECTRONICO"])),

                            HTML::campoTextoCorto("*celular",$textos["CELULAR"], 15, 15, "", array("readonly" => "true"), array("title",$textos["AYUDA_CELULAR"]))
                        )
                    ),
                    $textos["DATOS_PROVEEDOR"]
                )
            ),
            array(
                //HTML::campoTextoLargo("observaciones",$textos["OBSERVACIONES"], 2, 95, "", array("title"=>$textos["AYUDA_OBSERVACIONES"])),
                HTML::campoOculto("id_detalle",0),
                HTML::campoOculto("error_nit_proveedor",$textos["ERROR_NIT_PROVEEDOR"]),
                HTML::campoOculto("error_celular_vendedor",$textos["ERROR_CELULAR_VENDEDOR"]),
                HTML::campoOculto("error_correo_electronico",$textos["ERROR_CORREO_ELECTRONICO"]),
                HTML::campoOculto("error_razon_social_proveedor",$textos["ERROR_RAZON_SOCIAL_PROVEEDOR"]),
                HTML::campoOculto("error_dias_pago",$textos["ERROR_DIAS_PAGO"]),
                HTML::campoOculto("error_empresa",$textos["ERROR_EMPRESA"]),
                HTML::campoOculto("error_moneda",$textos["ERROR_MONEDA"]),
                HTML::campoOculto("error_solicitante",$textos["ERROR_SOLICITANTE"]),
                HTML::campoOculto("error_vendedor_proveedor",$textos["ERROR_VENDEDOR_PROVEEDOR"]),
                HTML::campoOculto("error_articulo",$textos["ERROR_EXISTE_ARTICULO"]),
                HTML::campoOculto("error_articulo_proveedor",$textos["ERROR_EXISTE_ARTICULO"]),
                HTML::campoOculto("error_municipio",$textos["ERROR_MUNICIPIO"]),
                HTML::campoOculto("error_direccion",$textos["ERROR_DIRECCION"]),
                HTML::campoOculto("error_telefono",$textos["ERROR_TELEFONO"]),
                HTML::campoOculto("error_fecha_orden",$textos["ERROR_FECHA_ORDEN"]),
                HTML::campoOculto("error_fecha_final_entregas",$textos["ERROR_FECHA_FINAL_ENTREGAS"]),
                HTML::campoOculto("error_articulo",$textos["ERROR_ARTICULO"]),
                HTML::campoOculto("error_estructura_grupo",$textos["ERROR_ESTRUCTURA_GRUPO"]),
                HTML::campoOculto("error_referencia",$textos["ERROR_REFERENCIA"]),
                HTML::campoOculto("error_detalle",$textos["ERROR_DETALLE"]),
                HTML::campoOculto("error_tasa",$textos["ERROR_TASA"]),
                HTML::campoOculto("error_cantidad_total",$textos["ERROR_CANTIDAD_TOTAL"]),
                HTML::campoOculto("error_valor_unitario",$textos["ERROR_VALOR_UNITARIO"]),
                HTML::campoOculto("error_porcentaje_descuento_linea",$textos["ERROR_PORCENTAJE_DESCUENTO_LINEA"]),
                HTML::campoOculto("error_cantidad_detalle",$textos["ERROR_CANTIDAD_DETALLE"]),
                HTML::campoOculto("error_concepto",$textos["ERROR_CONCEPTO"]),
                HTML::campoOculto("error_color",$textos["ERROR_COLOR"]),
                HTML::campoOculto("error_fecha_entrega_articulo",$textos["ERROR_FECHA_ENTREGA_ARTICULO"]),
                HTML::campoOculto("error_descuento_financiero_fijo",$textos["ERROR_DESCUENTO_FINANCIERO_FIJO"]),
                HTML::campoOculto("error_descuento_financiero_pronto_pago",$textos["ERROR_DESCUENTO_FINANCIERO_PRONTO_PAGO"]),
                HTML::campoOculto("error_descuento_global1",$textos["ERROR_DESCUENTO_GLOBAL1"]),
                HTML::campoOculto("error_descuento_global2",$textos["ERROR_DESCUENTO_GLOBAL2"]),
                HTML::campoOculto("error_descuento_global3",$textos["ERROR_DESCUENTO_GLOBAL3"]),
                HTML::campoOculto("error_cantidad_detalle_mayor",$textos["ERROR_CANTIDAD_DETALLE_MAYOR"]),
                HTML::campoOculto("id_unidad_medida_pedidos",$id_unidad_medida_pedidos),
                HTML::campoOculto("no_maneja_conceptos",$textos["NO_MANEJA_CONCEPTOS"])
            ),
            array(
                HTML::campoOculto("error_articulo",$textos["ERROR_EXISTE_ARTICULO"]),
                HTML::campoOculto("error_articulo_proveedor",$textos["ERROR_EXISTE_ARTICULO"]),
                HTML::campoOculto("error_celular_vendedor",$textos["ERROR_CELULAR_VENDEDOR"]),
                HTML::campoOculto("error_correo_electronico",$textos["ERROR_CORREO_ELECTRONICO"]),
                HTML::campoOculto("id_sucursal_pedidos",$id_sucursal_pedidos),
                HTML::campoOculto("id_unidad_actual",0),
                HTML::campoOculto("aplica_descuento_global1_actual",0),
                HTML::campoOculto("aplica_descuento_global2_actual",0),
                HTML::campoOculto("aplica_descuento_global3_actual",0),
                HTML::campoOculto("aplica_descuento_linea_actual",0),
                HTML::campoOculto("referencia_actual",""),
                HTML::campoOculto("detalle_actual",""),
                HTML::campoOculto("id_tasa_actual",0),
                HTML::campoOculto("valor_unitario_actual",0),
                HTML::campoOculto("descuento_linea_actual",0),
                HTML::campoOculto("descuento_global1_actual",0),
                HTML::campoOculto("descuento_global2_actual",0),
                HTML::campoOculto("descuento_global3_actual",0),
                HTML::campoOculto("regimen_actual","1"),
                HTML::campoOculto("id_propuesta_pedido",0)
            )
        );
        
        $formularios["PESTANA_ARTICULOS"] = array(  
            array(
                HTML::contenedor(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::campoTextoCorto("+selector7",$textos["REFERENCIA"], 30, 30, "", array("title"=>$textos["AYUDA_REFERENCIA_PROVEEDOR"],"class"=>"autocompletable articulo_existe modificar","onblur" => "cargarDatosArticulo()"))
                                .HTML::campoOculto("codigo_articulo","")  
                            ),
                            array(
                                HTML::campoTextoCorto("+descripcion",$textos["DESCRIPCION"], 50, 255, "", array("title"=>$textos["AYUDA_DETALLE"],"class"=>"articulo_existe modificar_articulo oculto")),
                            ),
                            array(
                                HTML::contenedor(
                                    HTML::agrupador(
                                        array(
                                            array(
                                                HTML::contenedor(HTML::arbolGrupos("arbolGrupos", "", "", "codigo_estructura_grupo"))
                                            )
                                        ),
                                        $textos["ESTRUCTURA_GRUPO"]
                                    ),
                                    array("id"=>"estructura_grupo","class"=>"articulo_nuevo modificar_detalle oculto")
                                ),
                            ),
                            array(
                                HTML::campoTextoCorto("+selector8",$textos["REFERENCIA"], 30, 30, "", array("title"=>$textos["AYUDA_REFERENCIA_PROVEEDOR"],"class"=>"autocompletable oculto modificar articulo_nuevo")),

                                HTML::listaSeleccionSimple("+id_tasa", $textos["TASA"], $tasas, "", array("title" => $textos["AYUDA_TASA"], "class"=>"oculto modificar_detalle articulo_nuevo")),

                                HTML::listaSeleccionSimple("+id_marca", $textos["MARCA"], $marcas, "", array("title" => $textos["AYUDA_MARCA"], "class"=>"oculto modificar_detalle articulo_nuevo")),
                            ),
                            array(
                                HTML::campoTextoCorto("+descripcion",$textos["DESCRIPCION"], 50, 255, "", array("title"=>$textos["AYUDA_DETALLE"],"class"=>"oculto articulo_nuevo modificar_detalle")),
                            ),
                            array(
                                HTML::listaSeleccionSimple("+id_unidad_compra",$textos["UNIDAD_MEDIDA"], "", "",array("title"=>$textos["AYUDA_UNIDAD"],"class"=>"oculto")),

                                HTML::campoTextoCorto("+costo_unitario",$textos["COSTO_UNITARIO"], 15, 15, "", array("readonly" => "true"), array("title"=>$textos["AYUDA_COSTO_UNITARIO"], "onKeyPress"=>"return numero(event)","onkeyup"=>"formatoMiles(this)", "onchange"=>"formatoMiles(this)")),
                                HTML::campoOculto("cantidad_total_control",0),

                                HTML::campoTextoCorto("+cantidad_total_articulo",$textos["CANTIDAD_TOTAL"], 5, 15, "", array("title"=>$textos["AYUDA_CANTIDAD_TOTAL"], "onKeyPress"=>"return campoEntero(event)", "onKeyUp"=>"activaDetalle()", "onKeyUp"=>"calcularSubtotal()", "class"=>"movimiento")),

                                HTML::campoTextoCorto("+subtotal",$textos["SUBTOTAL"], 15, 15, "", array("readonly" => "true"), array("title"=>$textos["AYUDA_SUBTOTAL"]))
                            ),
                            array(    
                                HTML::campoTextoCorto("observaciones_articulo",$textos["OBSERVACIONES_ARTICULO"], 50, 78, "",array("title"=>$textos["AYUDA_OBSERVACIONES"], "class"=>"movimiento oculto")),
                            ),
                            array(    
                                HTML::boton("botonAgregarArticulo", $textos["AGREGAR_ARTICULO"], "agregarItemArticulo(), mostrarTotales(), totalPedido()", "adicionar",array("class"=>"agregar_articulo"),"etiqueta"),

                                HTML::boton("botonModificarArticuloTabla", $textos["MODIFICAR_ARTICULO"], "modificarArticuloTabla()", "modificar",array("class"=>" modificar_articulo_tabla oculto"),"etiqueta")
                                .HTML::campoOculto("indice_tabla",0)
                            ),
                            array(
                                HTML::contenedor(
                                    HTML::agrupador(
                                        array(
                                            array(
                                                HTML::campoTextoCorto("+cantidad_detalle",$textos["CANTIDAD_DETALLE"], 5, 15, "", array("title"=>$textos["AYUDA_CANTIDAD_DETALLE"], "onKeyPress"=>"return campoDecimal(event)")),
                                                HTML::mostrarDato("cantidad_pendiente",$textos["CANTIDAD_PENDIENTE"], ""),

                                                //HTML::boton("botonAgregarArticulo", $textos["AGREGAR_ARTICULO"], "agregarArticulo();", "adicionar",array("class"=>"agregar_articulo"),"etiqueta"),

                                                HTML::contenedor(
                                                    "",
                                                    array("id"=>"indicadorEsperaFormulario")
                                                ),
                                                HTML::contenedor(
                                                    HTML::boton("botonRemoverArticulo", "", "removerArticulo(this),reCalculaTotalPedido()", "eliminar", array("class"=>"removerArticuloTabla")),
                                                    array("id" => "removerArticulo", "style" => "display: none")
                                                ),
                                                HTML::contenedor(
                                                    HTML::boton("botonModificarArticulo", "", "modificarArticulo(this);", "modificar", array("class"=>"modificarArticuloTabla")),
                                                    array("id" => "modificarArticulo", "style" => "display: none")
                                                )
                                            ),
                                        ),
                                        $textos["DETALLE_PEDIDO"]
                                    ),
                                    array("id"=>"detalle_pedido","class"=>"detalle_pedido oculto movimiento")
                                )
                            )
                        ),
                        $textos["DETALLE_ARTICULO"]
                    ),
                    array("id"=>"datos_totales_articulo")
                ),
                HTML::generarTabla(
                    array("id","ELIMINAR","REFERENCIA","DESCRIPCION","CANTIDAD","UNIDAD_MEDIDA","VALOR_UNITARIO","SUBTOTAL","DESCUENTO","IVA","OBSERVACIONES"),"",array("I","I","I","D","C","D","D","D","D","I"),"listaArticulos",false
                )
            ),
            array(
                HTML::campoOculto("error_articulo",$textos["ERROR_EXISTE_ARTICULO"]),
                HTML::campoOculto("error_articulo_proveedor",$textos["ERROR_EXISTE_ARTICULO"]),
                HTML::campoOculto("error_celular_vendedor",$textos["ERROR_CELULAR_VENDEDOR"]),
                HTML::campoOculto("error_nit_proveedor",$textos["ERROR_NIT_PROVEEDOR"]),
                HTML::campoOculto("error_razon_social_proveedor",$textos["ERROR_RAZON_SOCIAL_PROVEEDOR"]),
                HTML::campoOculto("error_dias_pago",$textos["ERROR_DIAS_PAGO"]),
                HTML::campoOculto("error_empresa",$textos["ERROR_EMPRESA"]),
                HTML::campoOculto("error_moneda",$textos["ERROR_MONEDA"]),
                HTML::campoOculto("error_solicitante",$textos["ERROR_SOLICITANTE"]),
                HTML::campoOculto("error_correo_electronico",$textos["ERROR_CORREO_ELECTRONICO"]),
                HTML::campoOculto("id_articulo_modificar",""),
                HTML::campoOculto("id_pedido_detalle_modificar",""),
                HTML::campoOculto("fecha_entrega_modificar",""),
                HTML::campoOculto("id_unidad_modificar",""),
                HTML::campoOculto("digite_codigo",$textos["CODIGO_VACIO"]),
                HTML::campoOculto("digite_codigo_alfanumerico",$textos["CODIGO_ALFANUMERICO_VACIO"]),
                HTML::campoOculto("existe_referencia_codigo",$textos["EXISTE_REFERENCIA_CODIGO"]),
                HTML::campoOculto("existe_referencia",$textos["EXISTE_REFERENCIA"]),
                HTML::campoOculto("existe_referencia_principal",$textos["EXISTE_REFERENCIA_PRINCIPAL"]),
                HTML::campoOculto("digite_referencia",$textos["DIGITE_REFRENCIA"]),
                HTML::campoOculto("principal_si",$textos["SI_NO_1"]),
                HTML::campoOculto("principal_no",$textos["SI_NO_0"]),
                HTML::campoOculto("referencia_diferente",$textos["REFERENCIA_DIFERENTE"]),
                HTML::campoOculto("indice","",0)
            )
        );

        $funciones["PESTANA_TOTAL_PEDIDO"]   = "totalPedido()";
        $opcionesLi["PESTANA_TOTAL_PEDIDO"]  = array("class" => "oculto total_pedido");
        $formularios["PESTANA_TOTAL_PEDIDO"] = array(
            array(
                HTML::contenedor(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::mostrarDato("proyecto_total",$textos["PROYECTO"], "", "")        
                            ),
                            array(
                                HTML::mostrarDato("prefijo_orden_total",$textos["PREFIJO_ORDEN_COMPRA"], "", ""),
                                HTML::mostrarDato("numero_orden_total",$textos["NUMERO_ORDEN_COMPRA"], "", "")
                            ),
                            array(
                                HTML::mostrarDato("nit_proveedor_total",$textos["NIT_PROVEEDOR"], "", ""),
                                HTML::mostrarDato("proveedor_total",$textos["PROVEEDOR"], "", "")
                            ),
                            array(
                                HTML::campoTextoCorto("cantidad_items_total",$textos["CANTIDAD_ITEMS"], 3, 3, "", array("readonly" => "true"), array("title"=>$textos["AYUDA_CANTIDAD_ITEMS"],"", ""))
                            ),
                        ),$textos["RESUMEN_ORDEN"]
                    )
                )
            ),
            array(
                HTML::campoOculto("campo_prefijo_orden",""),
                HTML::campoOculto("campo_numero_orden_total",""), 
                HTML::campoOculto("campo_nit_proveedor",""),
                HTML::campoOculto("campo_sucursal",""), 
                HTML::campoOculto("campo_fecha_documento","") 
            ),
            array(
                HTML::contenedor(
                    HTML::agrupador(
                        array(
                            array(
                                HTML::campoTextoCorto("total_unidades",$textos["TOTAL_UNIDADES"], 10, 10, "", array("readOnly"=>"true")),
                                HTML::campoTextoCorto("subtotal_pedido",$textos["SUBTOTAL"], 15, 15, "", array("readOnly"=>"true")),
                                HTML::campoTextoCorto("descuento_pedido",$textos["DESCUENTO"], 15, 15, "", array("readOnly"=>"true")),
                                HTML::campoTextoCorto("total_iva_pedido",$textos["TOTAL_IVA"], 15, 15, "", array("readOnly"=>"true")),
                                HTML::campoTextoCorto("total_pedido",$textos["TOTAL_PEDIDO"], 15, 15, "", array("readOnly"=>"true"))
                            ),
                            array(
                                HTML::campoTextoLargo("observaciones_orden",$textos["OBSERVACIONES"], 2, 95, "", array("title"=>$textos["AYUDA_OBSERVACIONES"]))
                            ),
                        ),$textos["TOTALES_ORDEN"]
                    )
                )
            ),
            array(
                HTML::campoTextoCorto("total_descuentos_linea_pedido",$textos["TOTAL_DESCUENTOS_LINEA"], 10, 10, "", array("readOnly"=>"true","class"=>"total_descuentos_linea oculto"))
            ),
            array(
                HTML::campoTextoCorto("total_descuentos_globales_pedido",$textos["TOTAL_DESCUENTOS_GLOBALES"], 10, 10, "", array("readOnly"=>"true","class"=>"total_descuentos_globales oculto"))
            ),
            array(
                HTML::campoTextoCorto("total_descuentos_financieros_pedido",$textos["TOTAL_DESCUENTOS_FINANCIEROS"], 10, 10, "", array("readOnly"=>"true","class"=>"total_descuentos_financieros oculto"))
            )
        );

        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR_PEDIDO"], "imprimirItem('1')", "aceptar",array("class"=>"terminar_orden")),
        );

        $contenido = HTML::generarPestanas($formularios, $botones, "", $funciones, $encabezado, $opcionesLi);
    } else {
        $contenido = "";
        if (!$sucursales){
            $error .= $textos["ERROR_PERMISO_SUCURSALES"];
        }
        if (!$compradores){
            $error .= $textos["ERROR_COMPRADORES"];
        }
        if (!$tipos_documentos){
            $error .= $textos["ERROR_TIPOS_DOCUMENTOS"];
        }
        if (!$municipios){
            $error .= $textos["ERROR_MUNICIPIOS"];
        }

        if (!$unidades){
            $error .= $textos["ERROR_UNIDADES"];
        }
        if (!$estructuras){
            $error .= $textos["CREAR_ESTRUCTURA_GRUPOS"];
        }
        if (!$tasas){
            $error .= $textos["CREAR_TASAS"];
        }
        if (!$monedas){
            $error .= $textos["CREAR_MONEDAS"];
        }
        if (!$proyectos){
            $error .= $textos["CREAR_PROYECTOS"];
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

//Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ORDEN_GRABADA"];

    $codigo_orden_compra  = SQL::obtenerValor("ordenes_compra","codigo","numero_consecutivo='$forma_campo_numero_orden_total' AND documento_identidad_proveedor='$forma_campo_nit_proveedor'");
    $condicion_encabezado = "numero_consecutivo='$forma_campo_numero_orden_total'";
    $condicion_movimiento = "codigo_orden_compra='$codigo_orden_compra'";

    $observaciones_orden  = $forma_observaciones_orden;
    
    $datos_encabezado  = array(
        "observaciones"  => $observaciones_orden,
        "estado"         => '0', 
        "fecha_registra" => date("Y-m-d H:i:s"),
    );

    $datos_movimiento = array(
        "estado"         => '0', 
        "fecha_registra" => date("Y-m-d H:i:s"),
    );

    $modificar_encabezado = SQL::modificar("ordenes_compra", $datos_encabezado, $condicion_encabezado);
    $modificar_movimiento = SQL::modificar("movimiento_ordenes_compra", $datos_movimiento, $condicion_movimiento);

    /*** Error de inserción ***/
    if ((!$modificar_movimiento)||(!$modificar_encabezado)) {
        $error     = true;
        $mensaje   = $textos["ERROR_GRABAR_MOVIMIENTO_ORDEN"];
    }else{
        $datos_encabezado        = array(
            "cantidad_registros" => $forma_cantidad_items_total,
            "estado"             => '0', 
            "fecha_registra"     => date("Y-m-d H:i:s")
        );
        
        $condicion_encabezado = "codigo='$codigo_orden_compra' AND numero_consecutivo='$forma_campo_numero_orden_total'";
        $modificar_encabezado = SQL::modificar("ordenes_compra", $datos_encabezado, $condicion_encabezado);
        
        if($modificar_encabezado){
            $forma_id = $forma_campo_sucursal."|".$forma_campo_fecha_documento."|".$forma_campo_numero_orden_total;
            Sesion::registrar("indice_imprimir", $idAsignado);

            include("clases/imprimir.php");
            //$ruta_archivo = HTTP::generarURL("IMPRIMIR_PDF")."&id=".$forma_id."&temporal=0";
            $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=0";

        } else {
            $error     = true;
            $mensaje   = $textos["ERROR_GRABAR_MOVIMIENTO_ORDEN"];
        }   
    }
    
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    if (isset($ruta_archivo)){
        $respuesta[2] = $ruta_archivo;
    }
    HTTP::enviarJSON($respuesta);
}
?>
