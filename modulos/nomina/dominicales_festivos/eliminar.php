<?php

/**
*
* Copyright (C) 2020 Jobdaily
*  Leonardo Silva Medina <flownormal@hotmail.com>
*  Jhon Jairo Diaz Soto <jhon27verde@hotmail.com>
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

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if(empty($url_id)){
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{
        $consultaF = SQL::seleccionar(array("domingos_festivos"), array("*"), "anio = '$url_id' AND tipo = '2'");//Dias Festivos
        $consultaD = SQL::seleccionar(array("domingos_festivos"), array("*"), "anio = '$url_id' AND tipo = '1'");//Dias Domingos
        $error     = "";
        $titulo    = $componente->nombre;

        $festivos    = array();
        $domingos    = array();
        $consecutivo = 0;

        if (SQL::filasDevueltas($consultaF)){
            while($datos = SQL::filaEnObjeto($consultaF)){
                $date_ = getdate(strtotime($datos->fecha));
                $respuesta =$meses[$date_["mon"]]." ".$date_["mday"].$textos["DE"].$date_["year"];
                $festivos[] = array(
                    $consecutivo,
                    $respuesta,
                    $datos->descripcion
                );
                $consecutivo++;
            }
        }
        $consecutivo = 0;
        if (SQL::filasDevueltas($consultaD)){
            while($datos = SQL::filaEnObjeto($consultaD)){
                $date_ = getdate(strtotime($datos->fecha));
                $respuesta =$meses[$date_["mon"]]." ".$date_["mday"].$textos["DE"].$date_["year"];
                $domingos[] = array(
                    $consecutivo,
                    $respuesta,
                    $datos->descripcion
                );
                $consecutivo++;
            }
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_FESTIVOS"] = array(
            array(
                HTML::mostrarDato("mensaje",$textos["ADVERTENCIA"],$textos["MENSAJE_ADVERTENCIA"])
            ),
            array(
                HTML::generarTabla(array("id","FECHA","DESCRIPCION"),$festivos,array("I","I"),"listaFestivos",false)
            )
        );

        $formularios["PESTANA_DOMINGOS"] = array(
            array(HTML::generarTabla(array("id","FECHA","DESCRIPCION"),$domingos,array("I","I"),"listaDomingos",false))
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios,$botones);
    }

    /*** Enviar datos para la generacion del formulario al script que original la peticion ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
/*** Eliminar el elemento seleccionado ***/
}elseif (!empty($forma_procesar)){
    $eliminar = SQL::eliminar("domingos_festivos", "anio=$forma_id");

    if($eliminar){
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    }else{
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
