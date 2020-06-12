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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;
    $codigo = SQL::obtenerValor("departamentos_empresa","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo=1;
    }

    /*** Definición de pestaña personal ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO_DEPARTAMENTO"], 5, 4, $codigo, array("title" => $textos["AYUDA_DEPARTAMENTO_EMPRESA"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE_DEPARTAMENTO"], 25, 255, "", array("title" => $textos["AYUDA_NOMBRE_DEPARTAMENTO"], "onblur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*riesgos_profesionales", $textos["RIESGOS_PROFESIONALES"], 10, 10, "", array("title" => $textos["AYUDA_RIESGOS_PROFESIONALES"],"onKeyPress" => "return campoDecimal(event)")),
        ),
        array(
            HTML::listaSeleccionSimple("*codigo_gasto", $textos["GASTO"], HTML::generarDatosLista("gastos_prestaciones_sociales", "codigo", "descripcion","codigo!='0'"),"",array("title" => $textos["AYUDA_GASTO"]))
        )
    );

/*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero de documento ***/
    if (isset($url_valor)) {
        $existe        = SQL::existeItem("departamentos_empresa", "codigo", $url_valor,"codigo != 0");
        $existe_nombre = SQL::existeItem("departamentos_empresa", "nombre", $url_valor,"codigo != 0");
	
        if ($existe) {
	    HTTP::enviarJSON($textos["ERROR_EXISTE_DEPARTAMENTO"]);
        }
         
        if ($existe_nombre) {
	    HTTP::enviarJSON($textos["ERROR_EXISTE"]);
        } 
    }

    
/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    if(empty($forma_codigo) ||(empty($forma_nombre)) || (empty($forma_riesgos_profesionales))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    }elseif($existe = SQL::existeItem("departamentos_empresa", "codigo", $forma_codigo)){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_DEPARTAMENTO"];
    
    }elseif($existe = SQL::existeItem("departamentos_empresa", "nombre", $forma_nombre)){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE"];
        
    } else {
        /*** Insertar datos ***/
        $datos = array (
            "codigo"          	    => $forma_codigo,
            "nombre"          	    => $forma_nombre,
            "riesgos_profesionales" => $forma_riesgos_profesionales,
            "codigo_gasto"          => $forma_codigo_gasto
        );
        $insertar = SQL::insertar("departamentos_empresa", $datos);

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
