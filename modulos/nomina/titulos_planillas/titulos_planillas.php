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

    $error  = "";
    $titulo = $componente->nombre;

    $devengados = array();
    for($i=1;$i<=8;$i++){
        $nombre      = SQL::obtenervalor("titulos_planillas","nombre","columna = ".$i);
        $descripcion = SQL::obtenervalor("titulos_planillas","descripcion","columna = ".$i);
        $devengados[] = array(
            HTML::campoTextoCorto("*nombre_columna_".$i, $textos["NOMBRE"]." ".$textos["COLUMNA"]." ".$i, 20, 15, $nombre, array("title" => $textos["AYUDA_COLUMNA"], "onblur" => "validarItem(this);")),
            HTML::campoTextoCorto("descripcion_columna_".$i, $textos["DESCRIPCION"]." ".$textos["COLUMNA"]." ".$i, 35, 255, $descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);"))
        );
    }
    $formularios["DEVENGADOS"] = $devengados;

    $deducidos = array();
    for($i=9;$i<=12;$i++){
        $nombre      = SQL::obtenervalor("titulos_planillas","nombre","columna = ".$i);
        $descripcion = SQL::obtenervalor("titulos_planillas","descripcion","columna = ".$i);
        $deducidos[] = array(
            HTML::campoTextoCorto("*nombre_columna_".$i, $textos["NOMBRE"]." ".$textos["COLUMNA"]." ".$i, 20, 15, $nombre, array("title" => $textos["AYUDA_COLUMNA"], "onblur" => "validarItem(this);")),
            HTML::campoTextoCorto("descripcion_columna_".$i, $textos["DESCRIPCION"]." ".$textos["COLUMNA"]." ".$i, 35, 255, $descripcion, array("title" => $textos["AYUDA_DESCRIPCION"], "onblur" => "validarItem(this);"))
        );
    }
    $formularios["DEDUCIDOS"]  = $deducidos;

    // Definicion de botones
    $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));

    $contenido = HTML::generarPestanas($formularios, $botones);

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {

    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $para = false;

    for($i=1;$i<=13;$i++){
        if($i != 9){
            $campo_nombre = "forma_nombre_columna_".$i;
            if(empty($$campo_nombre)){
                $error   = true;
                $mensaje = $textos["ERROR_NOMBRE_VACIO"]." ".$i;
                break;
            }else{
                for($j=1;$j<=13;$j++){
                    if($j != 9 && $j != $i){
                        $campo_nombre_2 = "forma_nombre_columna_".$j;
                        if($$campo_nombre == $$campo_nombre_2){
                            $error   = true;
                            $mensaje = $textos["ERROR_NOMBRE_EXISTE"]." ".$i." ".$textos["TABLA"];
                            $para = true;
                            break;
                        }
                    }
                }
            }
        }
        if($para){
            break;
        }
    }

    if(!$error){
        for($i=1;$i<=12;$i++){
            $campo_nombre      = "forma_nombre_columna_".$i;
            $campo_descripcion = "forma_descripcion_columna_".$i;
            $datos = array(
                "nombre"      => $$campo_nombre,
                "descripcion" => $$campo_descripcion
            );
            $modificar = SQL::modificar("titulos_planillas",$datos,"columna =".$i);
            if(!$modificar){
                $error   = true;
                $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
                $break;
            }
        }
    }

    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
