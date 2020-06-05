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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    
    $consulta_proveedores = SQL::seleccionar(array("proveedores"), array("*"),"documento_identidad!=''");
    $consulta_marcas = SQL::seleccionar(array("marcas"), array("*"),"codigo>0");
    
    if (SQL::filasDevueltas($consulta_proveedores) && SQL::filasDevueltas($consulta_marcas)){

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["PROVEEDOR"], 50, 50, "", array("title" => $textos["AYUDA_PROVEEDOR"], "class" => "autocompletable")).
                HTML::campoOculto("documento_identidad_proveedor", "")
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_marca", $textos["MARCA"], HTML::generarDatosLista("marcas", "codigo", "descripcion","codigo>0"), "", array("title" => $textos["AYUDA_MARCA"]))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error = $textos["NO_REGISTROS"];
        if (!SQL::filasDevueltas($consulta_proveedores)){
            $error .= $textos["CREAR_PROVEEDOR"];
        }
        if (!SQL::filasDevueltas($consulta_marcas)){
            $error .= $textos["CREAR_MARCA"];
        }
        $error .= $textos["INGRESAR_REGISTROS"];
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originá la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    /*** Validar campos requeridos ***/
    if(empty($forma_documento_identidad_proveedor)){
		$error   = true;
        $mensaje = $textos["PROVEEDOR_VACIO"];
        
	}elseif(empty($forma_codigo_marca)){
        $error   = true;
        $mensaje = $textos["MARCA_VACIO"];
    
    }elseif($existe = SQL::existeItem("proveedores_marcas", "codigo_marca", $forma_codigo_marca,"codigo_marca='$forma_codigo_marca' AND documento_identidad_proveedor='$forma_documento_identidad_proveedor'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_MARCA"]; 
    
    }else {
		/*** Insertar datos ***/
        $datos = array(
            "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
            "codigo_marca"                  => $forma_codigo_marca
        );
        $insertar = SQL::insertar("proveedores_marcas", $datos);

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originá la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
