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
if(isset($url_verificarCodigo) && isset($url_codigo) && isset($url_sucursal)){
    $existe_codigo = SQL::obtenerValor("bodegas","codigo","codigo_sucursal = '$url_sucursal' AND codigo = '$url_codigo'");
    if($existe_codigo){
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
        HTTP::enviarJSON($mensaje);
    }
}

/*** Validar descripcion ***/
if(isset($url_verificarNombre) && isset($url_nombre) && isset($url_sucursal)){
    $existe_nombre = SQL::obtenerValor("bodegas","nombre","codigo_sucursal = '$url_sucursal' AND nombre = '$url_nombre'");
    if($existe_nombre){
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
        HTTP::enviarJSON($mensaje);
    }
}

/*** Generar el formulario para la captura de datos ***/

if (!empty($url_generar)) {
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = "";

    $datos_tipos_bodegas    = SQL::seleccionar(array("tipos_bodegas"), array("*"), "codigo > '0'");
    $cantidad_tipos_bodegas = SQL::filasDevueltas($datos_tipos_bodegas);

    $datos_sucursales    = SQL::seleccionar(array("sucursales"), array("*"), "codigo > '0'");
    $cantidad_sucursales = SQL::filasDevueltas($datos_sucursales);

    if ($cantidad_tipos_bodegas == 0) {
        $error = $textos["CREAR_TIPOS_BODEGAS"];

    } else if ($cantidad_sucursales == 0) {
        $error = $textos["CREAR_SUCURSALES"];

    } else {

        $tipo_inventario = array(
            "1" => $textos["INVENTARIO"],
            "2" => $textos["OBSEQUIO"],
            "3" => $textos["CONSIGNACION"],
            "4" => $textos["PRESTAMO"],
            "5" => $textos["SERVICIO_TECNICO"],
            "6" => $textos["CONSIGNACION_CLIENTES"],
            "7" => $textos["PRESTAMO_TERCEROS"]
        );
        
        /*** Definicion de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0"), $sesion_sucursal, array("title" => $textos["AYUDA_ALMACEN"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 40, 60, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarNombre();"))
            ),
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, "", array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarCodigo();", "onKeyPress" => "return campoEntero(event)")),
                HTML::listaSeleccionSimple("*id_tipo_bodega", $textos["TIPO_BODEGA"], HTML::generarDatosLista("tipos_bodegas", "codigo", "nombre","codigo != 0"), "", array("title" => $textos["AYUDA_TIPO_BODEGA"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 40, 60, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_inventario", $textos["TIPO_INVENTARIO"], $tipo_inventario, "", array("title" => $textos["AYUDA_TIPO_INVENTARIO"]))
            )
        );

        /*** Definicion de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );
        
        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    
/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_sucursal)){
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

    }elseif(SQL::existeItem("bodegas", "codigo", $forma_codigo,"codigo !='' AND codigo_sucursal='$forma_sucursal'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
    
     }elseif(SQL::existeItem("bodegas", "nombre", $forma_nombre,"nombre !='' AND codigo_sucursal='$forma_sucursal'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
        
    }elseif(SQL::existeItem("bodegas", "descripcion", $forma_descripcion,"descripcion !='' AND codigo_sucursal='$forma_sucursal'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
    
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_sucursal"        => $forma_sucursal,
            "codigo"          => $forma_codigo,
            "nombre"                 => $forma_nombre,
            "descripcion"            => $forma_descripcion,
            "codigo_tipo_bodega"     => $forma_id_tipo_bodega,
			"tipo_inventario"        => $forma_tipo_inventario
        );
        $insertar = SQL::insertar("bodegas", $datos);

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }    
    /*** Enviar datos con la respuesta del proceso al script que origin? la petici?n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
