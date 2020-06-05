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

/*** Generar el formulario para la captura de datos ***/


if(isset($url_validarItemsllaves))
{
    if($url_item=="codigo" && !empty($url_valor))
    {

        $llave_primaria=explode("|", $url_valor);
        $llave_primaria =str_pad($llave_primaria[0],3,"0", STR_PAD_LEFT)."|".$llave_primaria[1]."|".str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT);

        $existe = SQL::existeItem("buscador_auxiliares_contables","id",$llave_primaria,"id !='$url_id'");
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO"];
            HTTP::enviarJSON($mensaje);
        }
    }
}


if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {


        $llave_primaria= explode("|",$url_id);
        $codigo_empresa = $llave_primaria[0];
        $codigo_anexo   = $llave_primaria[1];
        $codigo_        = $llave_primaria[2];


        $vistaConsulta = "auxiliares_contables";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$codigo_ ' AND codigo_empresa='$codigo_empresa' AND codigo_anexo_contable='$codigo_anexo'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $campo_llave_primaria="codigo_empresa|codigo_anexo_contable|codigo";

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0"),$codigo_empresa, array("title" => $textos["AYUDA_EMPRESA"])),
                HTML::listaSeleccionSimple("*codigo_anexo_contable", $textos["ANEXO_CONTABLE"], HTML::generarDatosLista("anexos_contables", "codigo", "descripcion","codigo != ''"),$codigo_anexo, array("title" => $textos["AYUDA_ANEXO"]))
            ),
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 8, 8, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItemsllaves(this,'$campo_llave_primaria','$url_id')","onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 20, 255, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
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
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

        $llave_primaria= explode("|",$forma_id);
        $codigo_empresa = $llave_primaria[0];
        $codigo_anexo   = $llave_primaria[1];
        $codigo_        = $llave_primaria[2];

    /*** Validar campos requeridos ***/

    $llave_primaria =str_pad($forma_codigo_empresa,3,"0", STR_PAD_LEFT)."|".$forma_codigo_anexo_contable."|".str_pad($forma_codigo,8,"0", STR_PAD_LEFT);

   if(SQL::existeItem("buscador_auxiliares_contables","id",$llave_primaria,"id !='$forma_id' ")){
        
		$error   = true;
		$mensaje = $textos["ERROR_EXISTE_CODIGO"];

	}elseif(empty($forma_codigo)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
		
	}elseif(empty($forma_codigo_anexo_contable)) {
		$error   = true;
		$mensaje = $textos["ANEXO_VACIO"];
	
	}elseif(empty($forma_descripcion)) {
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"];
		
    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_empresa"        => $forma_codigo_empresa,
            "codigo_anexo_contable" => $forma_codigo_anexo_contable,
            "codigo"                => $forma_codigo,
            "descripcion"           => $forma_descripcion
        );
        $consulta = SQL::modificar("auxiliares_contables", $datos, "codigo='$codigo_' AND codigo_empresa='$codigo_empresa' AND codigo_anexo_contable='$codigo_anexo'");
		
		/*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
