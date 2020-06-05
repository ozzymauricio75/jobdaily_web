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

if (!empty($url_generar)) {

    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error  = "";
        $titulo = $componente->nombre;
        
        $vistaConsulta = "tipos_contrato";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $termino_contrato = array(
            "1" => $textos["TERMINO_FIJO"],
            "2" => $textos["TERMINO_INDEFINIDO"],
            "3" => $textos["SIN_RELACION_LABORAL"],
            "4" => $textos["OBRA_LABOR"]
        );
    
        $tipo_contratacion = array(
            "1" => $textos["BASICO"],
            "2" => $textos["INTEGRAL"],
            "3" => $textos["DESTAJO"],
            "4" => $textos["PRACTICANTE"],
            "5" => $textos["PASANTIAS"],
            "6" => $textos["PRESTACION_SERVICIOS"],
            "7" => $textos["COOPERATIVA_TRABAJO_ASOCIADO"]
        );
        
        if($datos->sueldo_ajusta_minimo == 1){
            $estado_si = true;
            $estado_no = false;
        }else{
            $estado_si = false;
            $estado_no = true;
        }
        
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 5, 3, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),        
            array( 
                HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION_TIPO_CONTRATO"], 25, 255, $datos->descripcion, array("title" => $textos["AYUDA_NOMBRE_TIPO_CONTRATO"], "onblur" => "validarItem(this);"))
            ),
            array(
            HTML::listaSeleccionSimple("*termino_contrato", $textos["TERMINO_CONTRATO"], $termino_contrato, $datos->termino_contrato, array("title" => $textos["AYUDA_TERMINO_CONTRATO"], ))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_contratacion", $textos["TIPO_CONTRATACION"], $tipo_contratacion, $datos->tipo_contratacion, array("title" => $textos["AYUDA_TIPO_CONTRATACION"]))
            ),
            array(
                HTML::mostrarDato("se_ajusta_minimo", $textos["AJUSTA_MINIMO"], ""),
                HTML::marcaSeleccion("ajusta_minimo", $textos["AJUSTA_MINIMO_SI"], 1, $estado_si, array("id" => "ajusta_minimo_si")),
                HTML::marcaSeleccion("ajusta_minimo", $textos["AJUSTA_MINIMO_NO"], 0, $estado_no, array("id" => "ajusta_minimo_no"))
            )
        );

        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

        }

        $respuesta[0] = $error;
        $respuesta[1] = $titulo;
        $respuesta[2] = $contenido;
        HTTP::enviarJSON($respuesta);

} elseif (!empty($url_validar)) {

    if ($url_item == "codigo") {
        $existe            = SQL::existeItem("tipos_contrato", "codigo", $url_valor, "codigo != '".$url_id."' AND codigo != 0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_TIPO_CONTRATO"]);
        }
    }else if($url_item == "descripcion"){
        $existeDescripcion = SQL::existeItem("tipos_contrato", "descripcion", $url_valor, "codigo != '".$url_id."' AND codigo != 0");
        if ($existeDescripcion) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DESCRIPCION_TIPO_CONTRATO"]);
        } 
    }
    
} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if(empty($forma_descripcion)){
        $error = true;
        $mensaje = $textos["ERROR_DESCRPCION_VACIO"];
    }elseif(SQL::existeItem("tipos_contrato", "descripcion", $forma_descripcion,"codigo != 0 AND codigo != '".$forma_id."'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_DESCRIPCION_TIPO_CONTRATO"];            
    }elseif(SQL::existeItem("tipos_contrato", "codigo", $forma_codigo,"codigo != 0 AND codigo != '".$forma_id."'")) {
        $error   = true;
        $mensaje =  $textos["ERROR_EXISTE_TIPO_CONTRATO"];            
    }else{

        $datos = array (
            "codigo"                => $forma_codigo,
            "descripcion"           => $forma_descripcion,
            "termino_contrato"      => $forma_termino_contrato,
            "tipo_contratacion"     => $forma_tipo_contratacion,
            "sueldo_ajusta_minimo"  => $forma_ajusta_minimo
        );
	  
        $modificar = SQL::modificar("tipos_contrato", $datos, "codigo = '".$forma_id."'");


        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
       
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

