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
if(isset($url_validarItemsllaves))
{
    if($url_item=="codigo" && !empty($url_valor))
    {

        $llave_primaria=explode("|", $url_valor);
        $llave_primaria =str_pad($llave_primaria[0],4,"0", STR_PAD_LEFT)."|".str_pad($llave_primaria[1],4,"0", STR_PAD_LEFT);
        
        $existe = SQL::existeItem("buscador_secciones_departamentos","id",$llave_primaria);
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO"];
            HTTP::enviarJSON($mensaje);
        }
    }

    if($url_item=="codigo_departamento_empresa" && !empty($url_valor))
    {

        $llave_primaria=explode("|", $url_valor);
        $llave_primaria =str_pad($llave_primaria[0],3,"0", STR_PAD_LEFT)."|".$llave_primaria[1]."|".str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT);
        
        $existe = SQL::existeItem("buscador_secciones_departamanetos","id",$llave_primaria);
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO_DEPARTAMENTO"];
            HTTP::enviarJSON($mensaje);
        }
    }
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    
    $departamentos  = HTML::generarDatosLista("departamentos_empresa","codigo","nombre","codigo>0");
    
    if ($departamentos){
        $campo_llave_primaria = "codigo|codigo_departamento_empresa";
        $codigo = SQL::obtenerValor("secciones_departamentos","MAX(codigo)","codigo!=0");
        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("title" => $textos["AYUDA_SECCION"], "onBlur" => "validarItemsllaves(this,'$campo_llave_primaria')"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_departamento_empresa", $textos["CODIGO_DEPARTAMENTO"], $departamentos, array("title" => $textos["AYUDA_DEPARTAMENTO"], "onBlur" => "validarItemsllaves(this,'$campo_llave_primaria')"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 25, 255, "", array("title" => $textos["AYUDA_NOMBRE_SECCION"]))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );
        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $error     = $textos["ERROR_DEPARTAMENTOS"];
        $titulo    = "";
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    
/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if (empty($forma_codigo)){
        $error = true;
        $mensaje = $textos["ERROR_CODIGO"];        

    } else if(empty($forma_nombre)){
        $error = true;
        $mensaje = $textos["ERROR_NOMBRE"];        

    } else if(SQL::existeItem("secciones_departamentos", "codigo", $forma_codigo,"codigo != '' AND codigo = '$forma_codigo' AND  codigo_departamento_empresa = '$forma_codigo_departamento_empresa'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_DEPARTAMENTO"];
    
    } else if(SQL::existeItem("secciones_departamentos", "nombre", $forma_nombre,"codigo != '' AND codigo = '$forma_codigo' AND  codigo_departamento_empresa = '$forma_codigo_departamento_empresa' AND nombre='$forma_nombre'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_SECCION"];
    
    } else {
        /*** Insertar datos ***/
        $datos = array (
            "codigo_departamento_empresa" => $forma_codigo_departamento_empresa,
            "codigo"                      => $forma_codigo,
            "nombre"	                  => $forma_nombre
        );
        $insertar = SQL::insertar("secciones_departamentos", $datos);

        /*** Error de insercón ***/
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
