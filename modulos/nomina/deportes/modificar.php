<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
* 
*
* Este archivo es parte de:
* PANCE :: Plataforma para la Administraciï¿½n del Nexo Cliente-Empresa
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los tï¿½rminos de la Licencia Pï¿½blica General GNU
* publicada por la Fundaciï¿½n para el Software Libre, ya sea la versiï¿½n 3
* de la Licencia, o (a su elecciï¿½n) cualquier versiï¿½n posterior.
*
* Este programa se distribuye con la esperanza de que sea ï¿½til, pero
* SIN GARANTï¿½A ALGUNA; ni siquiera la garantï¿½a implï¿½cita MERCANTIL o
* de APTITUD PARA UN PROPï¿½SITO DETERMINADO. Consulte los detalles de
* la Licencia Pï¿½blica General GNU para obtener una informaciï¿½n mï¿½s
* detallada.
*
* Deberï¿½a haber recibido una copia de la Licencia Pï¿½blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error  = "";
        $titulo = $componente->nombre;
    
        $vistaConsulta = "deportes";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
		
        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_GENERAL"] = array(
            array( 
                HTML::campoTextoCorto("*codigo", $textos["CODIGO_DEPORTE"], 5, 4, $datos->codigo, array("title" => $textos["AYUDA_DEPORTE"], "onblur" => "validarItem(this);"))
                .HTML::campoOculto("id",$datos->codigo)
            ),
            array(
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION_DEPORTE"], 25, 255, $datos->descripcion, array("title" => $textos["AYUDA_NOMBRE_DEPORTE"], "onblur" => "validarItem(this);"))
            )
        
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero de documento ***/
    if (isset($url_valor)) {
        $existe = SQL::existeItem("deportes", "codigo", $url_valor, "codigo != '$url_id' AND codigo != '' ");
        $existenombre = SQL::existeItem("deportes", "descripcion", $url_valor, "codigo != '$url_id' AND descripcion != ''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DEPORTE"]);
        } 
        if ($existenombre) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE_DEPORTE"]);
        } 
    }
    
/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if(empty($forma_codigo) || (empty($forma_descripcion))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    }elseif($existe = SQL::existeItem("deportes", "codigo", $forma_codigo,"codigo != '$forma_id' AND codigo != ''")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_DEPORTE"];
    
    }elseif($existe = SQL::existeItem("deportes", "descripcion", $forma_descripcion,"codigo != '$forma_id' AND descripcion != ''")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_DEPORTE"];

    } else {
        /*** Insertar datos ***/
        $datos = array (
             "codigo"      => $forma_codigo,
             "descripcion" => $forma_descripcion
        );
        $modificar = SQL::modificar("deportes", $datos, "codigo = '$forma_id'");

        /*** Error de modificacion ***/
        if (!$modificar) {
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

