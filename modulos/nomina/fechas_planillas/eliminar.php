<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
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

require("clases/clases.php");

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a consultar
    if(empty($url_id)){
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    }else{
        $meses = array(
        "1" => $textos["ENERO"],
        "2" => $textos["FEBRERO"],
        "3" => $textos["MARZO"],
        "4" => $textos["ABRIL"],
        "5" => $textos["MAYO"],
        "6" => $textos["JUNIO"],
        "7" => $textos["JULIO"],
        "8" => $textos["AGOSTO"],
        "9" => $textos["SEPTIEMBRE"],
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
        $llave_pricipal   = explode("|",$url_id);
        $vistaConsultaPla = "planillas";
        $columnasPla      = SQL::obtenerColumnas($vistaConsultaPla);
        $consultaPla      = SQL::seleccionar(array($vistaConsultaPla), $columnasPla, "codigo = '".$llave_pricipal[0]."'");
        $datosPla         = SQL::filaEnObjeto($consultaPla);
        $error            = "";
        $titulo           = $componente->nombre;

        $condicionFecha = "codigo_planilla ='".$llave_pricipal[0]."' AND ano = '".$llave_pricipal[1]."'";
        $consulta       = SQL::seleccionar(array("seleccion_fechas_planillas"),array("*"),$condicionFecha);
        $items          = array();
        if(SQL::filasDevueltas($consulta)){
            $contador = 0;
            while($datos_item = SQL::filaEnObjeto($consulta)){
                $date_r  = getdate(strtotime($datos_item->fecha));
                $oculto = HTML::campoOculto("fechasPago[]",$datos_item->fecha);
                $items[] = array(
                    $contador,
                    $oculto,
                    $meses[$date_r["mon"]],
                    $dias[$date_r["weekday"]],
                    $datos_item->fecha
                );
                $contador++;
            }
        }

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("planilla", $textos["PLANILLA"], $datosPla->descripcion)
            ),
            array(
                HTML::generarTabla(
                    array("id","","MES","DIA","DIA_CRONOLOGICO"),
                    $items,
                    array("","I","I","I"),
                    "listaDiasPagos",
                    false
                )
            )
        );
        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('".$url_id."');", "aceptar")
        );
        $contenido = HTML::generarPestanas($formularios, $botones);
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
    // Eliminar el elemento seleccionado
} elseif(!empty($forma_procesar)){
    $llave_pricipal = explode("|",$forma_id);

    $listaFechasNoEliminadas = array();
    foreach($forma_fechasPago AS $fecha){
        $condicion = determinarPeriodoFechas($llave_pricipal[0],$fecha);
        $consulta  = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),$condicion);
        if(!SQL::filasDevueltas($consulta)){
            $consulta = SQL::eliminar("fechas_planillas", "codigo_planilla = '".$llave_pricipal[0]."' AND fecha = '".$fecha."'");
        }else{
            $listaFechasNoEliminadas[] = $fecha;
        }
    }
    if($consulta){
        $error   = false;
        $cantidad = count($listaFechasNoEliminadas);
        if($cantidad > 0){
            $mensaje = $textos["FECHAS_NO_ELIMINADAS"].implode("\n",$listaFechasNoEliminadas);
        }else{
            $mensaje = $textos["ITEM_ELIMINADO"];
        }
    }else{
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
