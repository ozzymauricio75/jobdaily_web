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

/*** Devolver datos para autocompletar la bÃºsqueda ***/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;


    $vistaConsulta  = "tipos_bodegas";
    $ordenamiento   = SQL::ordenColumnas("CODIGO ASC");
    $columnas       = SQL::obtenerColumnas($vistaConsulta);
    $datos_2 = SQL::seleccionar(array($vistaConsulta), $columnas,"","",$ordenamiento);
    $totalRegistros = SQL::filasDevueltas($datos_2);


    if($totalRegistros!=0)
    {
    /*Permitir obtener un valor de acuerdo a un rango*/
    $consulta = SQL::seleccionar(array($vistaConsulta), $columnas,"","",$ordenamiento,$totalRegistros-1,1);
    $datos    = SQL::filaEnObjeto($consulta);
    /*genero el codigo de acuerdo a un ultimo elemnto*/
    $codigo_generado=($datos->codigo)+1;
    }
    else
    {
        $codigo_generado=1;
    }

    /*** Definicion de pestaÃ±as general ***/

    $formularios["PESTANA_GENERAL"] = array(

         array(
            HTML::mostrarDato("codigo_muestra",  $textos["CODIGO"], $codigo_generado)
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 30, 60, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 60, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);")).
            HTML::campoOculto("codigo", $codigo_generado)
        )

    );

    /*** Definicion de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";
    
    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("tipos_bodegas", "nombre", $url_valor);

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE"];
        }
    }
    
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos para el tercero ***/
    if(empty($forma_nombre)){
		$error   = true;
		$mensaje = $textos["NOMBRE_VACIO"];
		
	}elseif(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];

    } elseif (SQL::existeItem("tipos_bodegas", "nombre", $forma_nombre, "")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_NOMBRE"];

    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo"      => $forma_codigo,
            "nombre"      => $forma_nombre,
            "descripcion" => $forma_descripcion                     
        );

        $insertar = SQL::insertar("tipos_bodegas", $datos);  
                                                        
        /*** Error de inserción ***/                    
        if (!$insertar) {                               
            $error   = true;                            
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"]; 
        } 
    }                                                  
    /*** Enviar datos con la respuesta del proceso al script que origino la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
