<?php

/**
*
* Copyright (C) 2008 Felinux Ltda
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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Validar codigo ***/
if(isset($url_verificarCodigo) && isset($url_codigo) && isset($url_sucursal) && isset($url_id)){

        
       
    
    $existe_codigo = SQL::obtenerValor("bodegas","codigo","codigo_sucursal = '$url_sucursal' AND codigo = '$url_codigo' AND codigo != '$url_id'");
    if($existe_codigo){
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
        HTTP::enviarJSON($mensaje);
    }
}

/*** Validar descripcion ***/
if(isset($url_verificarNombre) && isset($url_nombre) && isset($url_sucursal) && isset($url_id)){
    
       
       


    $existe_nombre = SQL::obtenerValor("bodegas","nombre","codigo_sucursal = '$url_sucursal' AND nombre = '$url_nombre' AND codigo != '$url_id'");
    if($existe_nombre){
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
        HTTP::enviarJSON($mensaje);
    }
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $llave_primaria_bodega = explode("|",$url_id);
        $codigo_bodega         = $llave_primaria_bodega[0];
        $codigo_sucursal       = $llave_primaria_bodega[1];

        
        $vistaConsulta = "bodegas";
        $condicion     = "codigo = '$codigo_bodega' AND codigo_sucursal=$codigo_sucursal";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);



        $error         = "";
        $titulo        = $componente->nombre;
        
		$tipo_inventario = array(
			"1" => $textos["INVENTARIO"],
			"2" => $textos["OBSEQUIO"],
			"3" => $textos["CONSIGNACION"],
			"4" => $textos["PRESTAMO"],
			"5" => $textos["SERVICIO_TECNICO"],
			"6" => $textos["CONSIGNACION_CLIENTES"],
			"7" => $textos["PRESTAMO_TERCEROS"]
		);


        /*** Definición de pestañas ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("mostrar_codigo", $textos["CODIGO"],$datos->codigo,array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarCodigo();"))
               	.HTML::campoOculto("codigo", $datos->codigo)
            ),
            array(

                HTML::listaSeleccionSimple("*sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0"),$datos->codigo_sucursal, array("title" => $textos["AYUDA_ALMACEN"]))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"],40, 60, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarNombre();"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"],40, 60, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"]))
            ),
            array(
               HTML::listaSeleccionSimple("*id_tipo_bodega", $textos["TIPO_BODEGA"], HTML::generarDatosLista("tipos_bodegas", "codigo", "nombre","codigo != 0"),$datos->codigo_tipo_bodega, array("title" => $textos["AYUDA_TIPO_BODEGA"],"onBlur" => "validarItem(this);"))
            ),
			array(
            	HTML::listaSeleccionSimple("*tipo_inventario", $textos["TIPO_INVENTARIO"], $tipo_inventario, $datos->tipo_inventario, array("title" => $textos["AYUDA_TIPO_INVENTARIO"]))
        	)
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    $llave_primaria_bodega = explode("|",$forma_id);
    $codigo_bodega         = $llave_primaria_bodega[0];
    $codigo_sucursal       = $llave_primaria_bodega[1];

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_sucursal)){
		$error   = true;
        $mensaje = $textos["SUCURSAL_VACIO"];
    
    }elseif(empty($forma_id_tipo_bodega)){
		$error   = true;
        $mensaje = $textos["TIPO_BODEGA_VACIO"];
    
	}elseif(empty($forma_nombre)){
		$error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
        
	}elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];

    } elseif (SQL::existeItem("bodegas", "nombre", $forma_nombre, "codigo = '$forma_codigo' AND codigo_sucursal='$forma_sucursal' AND codigo != '$codigo_bodega'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];
    
    } elseif (SQL::existeItem("bodegas", "descripcion", $forma_descripcion, "codigo= '$forma_codigo' AND codigo_sucursal='$forma_sucursal' AND codigo != '$codigo_bodega'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_DESCRIPCION"];    
        
    }elseif (SQL::existeItem("bodegas","codigo_sucursal",$forma_sucursal, "codigo= '$forma_codigo' AND codigo_sucursal!= $codigo_sucursal")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_LLAVE"];

    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_sucursal"        => $forma_sucursal,
            "codigo"                 => $forma_codigo,
            "nombre"                 => $forma_nombre,
            "descripcion"            => $forma_descripcion,
            "codigo_tipo_bodega"     => $forma_id_tipo_bodega,
			"tipo_inventario"        => $forma_tipo_inventario
        );

        
    

        $consulta = SQL::modificar("bodegas",$datos, "codigo = $codigo_bodega AND codigo_sucursal = $codigo_sucursal");
		
		/*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
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
