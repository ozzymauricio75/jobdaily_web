<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
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

if (!empty($url_fechadescripcion)) {

    $meses = array(
        "1"  => $textos["ENERO"],
        "2"  => $textos["FEBRERO"],
        "3"  => $textos["MARZO"],
        "4"  => $textos["ABRIL"],
        "5"  => $textos["MAYO"],
        "6"  => $textos["JUNIO"],
        "7"  => $textos["JULIO"],
        "8"  => $textos["AGOSTO"],
        "9"  => $textos["SEPTIEMBRE"],
        "10" => $textos["OCTUBRE"],
        "11" => $textos["NOVIEMBRE"],
        "12" => $textos["DICIEMBRE"]
    );
    $dias = array(
        "Sunday"    => $textos["DOMINGO"],
        "Monday"    => $textos["LUNES"],
        "Tuesday"   => $textos["MARTES"],
        "Wednesday" => $textos["MIERCOLES"],
        "Thursday"  => $textos["JUEVES"],
        "Friday"    => $textos["VIERNES"],
        "Saturday"  => $textos["SABADO"]
    );

    $date_ = getdate(strtotime($url_fecha));
    $respuesta =$meses[$date_["mon"]]." ".$date_["mday"].$textos["DE"].$date_["year"];
    HTTP::enviarJSON($respuesta);
}

if(!empty($url_generar)){

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if(empty($url_id)){
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    }else{


        $meses = array(
            "1"  => $textos["ENERO"],
            "2"  => $textos["FEBRERO"],
            "3"  => $textos["MARZO"],
            "4"  => $textos["ABRIL"],
            "5"  => $textos["MAYO"],
            "6"  => $textos["JUNIO"],
            "7"  => $textos["JULIO"],
            "8"  => $textos["AGOSTO"],
            "9"  => $textos["SEPTIEMBRE"],
            "10" => $textos["OCTUBRE"],
            "11" => $textos["NOVIEMBRE"],
            "12" => $textos["DICIEMBRE"]
        );

        $dias = array(
            "Sunday"    => $textos["DOMINGO"],
            "Monday"    => $textos["LUNES"],
            "Tuesday"   => $textos["MARTES"],
            "Wednesday" => $textos["MIERCOLES"],
            "Thursday"  => $textos["JUEVES"],
            "Friday"    => $textos["VIERNES"],
            "Saturday"  => $textos["SABADO"]
        );

        $consultaF = SQL::seleccionar(array("domingos_festivos"), array("*"), "anio = '$url_id' AND tipo = '2'");//Dias Festivos
        $consultaD = SQL::seleccionar(array("domingos_festivos"), array("*"), "anio = '$url_id' AND tipo = '1'");//Dias Domingos
        $error     = "";
        $titulo    = $componente->nombre;
        $festivos    = array();
        $domingos    = array();
        $consecutivo = 0;
        $anio = date("Y");
        if(SQL::filasDevueltas($consultaF)){
            while($datos = SQL::filaEnObjeto($consultaF)){
                $date           = getdate(strtotime($datos->fecha));
                $fecha_completa = $meses[$date["mon"]]." ".$date["mday"].$textos["DE"].$date["year"];
                $ocultos        = HTML::campoOculto("fecha_festivo[".$consecutivo."]", $datos->fecha, array("class" => "fecha_festivo"));
                $ocultos       .= HTML::campoOculto("descripcionTabla[".$consecutivo."]", $datos->descripcion, array("class" => "descripcionTabla"));
                $remover = HTML::boton("botonRemover", "", "removerItem(this);", "eliminar", array("id" => "botonRemover"));
                $celda   = $ocultos.$remover;
                $festivos[] = array(
                    $consecutivo,
                    $celda,
                    $fecha_completa,
                    $datos->descripcion
                );
                $consecutivo++;
            }
        }
        $consecutivo = 0;
        if (SQL::filasDevueltas($consultaD)){
            while($datos = SQL::filaEnObjeto($consultaD)){
                $date_          = getdate(strtotime($datos->fecha));
                $fecha_completa = $meses[$date_["mon"]]." ".$date_["mday"].$textos["DE"].$date_["year"];
                $ocultos        = HTML::campoOculto("fecha_domingo[".$consecutivo."]", $datos->fecha, array("class" => "fecha_domingo"));

                $domingos[] = array(
                    $consecutivo,
                    $ocultos.$fecha_completa,
                    $datos->descripcion
                );
                $consecutivo++;
            }
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_FESTIVOS"] = array(
            array(
                HTML::campoTextoCorto("*fecha", $textos["FECHA"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA"], "class" => "selectorFecha")),
                HTML::campoTextoCorto("descripcion", $textos["DESCRIPCION"], 20, 50,"", array("title" => $textos["AYUDA_FECHA"]))
            ),
            array(
                HTML::boton("botonAgregar", $textos["AGREGAR"], "generarFestivo();", "adicionar"),
                HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerTable(this);", "eliminar"),
                HTML::contenedor(
                    HTML::boton("botonRemover", "","removerItem(this);", "eliminar"),
                    array("id" => "botonRemover", "style" => "display: none")
                )
            ),
            array(
                HTML::generarTabla(
                    array("id","", "FECHA","DESCRIPCION"),
                    $festivos,
                    array("I", "I","I"),
                    "listaFestivos",
                    false
                )
            ),
            array(
                HTML::campoOculto("mensaje",$textos["ERROR_EXISTE_FECHA"]).
                HTML::campoOculto("mensaje_domingo",$textos["ERROR_EXISTE_FECHA_DOMINGO"]).
                HTML::campoOculto("mensaje_base",$textos["ERROR_EXISTE_FECHA_BASE"]).
                HTML::campoOculto("anio",$anio)
            )
        );

        $formularios["PESTANA_DOMINGOS"] = array(
            array(
                HTML::generarTabla(array("id","FECHA","DESCRIPCION"),$domingos,array("I","I"),"listaDomingos",false)
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem($url_id);", "aceptar")
        );
        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

}elseif(!empty($forma_procesar)){
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    SQL::eliminar("domingos_festivos","anio=$forma_id AND tipo=2");

    for($id = 0;!empty($forma_fecha_festivo[$id]); $id++){
        $datos = array (
            "anio"  => $forma_anio,
            "fecha" => $forma_fecha_festivo[$id],
            "tipo"  => 2,
            "descripcion" => $forma_descripcionTabla[$id]
        );

        $insertar = SQL::insertar("domingos_festivos", $datos);
        /*** Error de insercón ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    for($id = 0;!empty($forma_fecha_domingo[$id]); $id++){
        $datos = array (
            "anio"        => $forma_anio,
            "fecha"       => $forma_fecha_domingo[$id],
            "tipo"        => 1,
            "descripcion" => "Domingo"
        );

        $insertar = SQL::reemplazar("domingos_festivos", $datos);
        /*** Error de insercón ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
            $mensaje = mysql_error();
        }
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);

}
?>
