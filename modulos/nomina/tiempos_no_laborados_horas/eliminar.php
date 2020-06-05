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
require("clases/clases.php");

$dias = array(
    '0' => 'domingo',
    '1' => 'lunes',
    '2' => 'martes',
    '3' => 'miercoles',
    '4' => 'jueves',
    '5' => 'viernes',
    '6' => 'sabado'
);

if(!empty($url_generar)){
    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if(empty($url_id)){
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    }else{
        /*** Obtener los datos de la tabla de terceros ***/

        $error               = "";
        $titulo              = $componente->nombre;

        $llave_primaria = explode("|", $url_id);

        $codigo_sucursal = $llave_primaria[0];
        $hora_fin        = $llave_primaria[1];
        $hora_inicio     = $llave_primaria[2];
        $fecha_reporte   = $llave_primaria[3];
        $documento_identidad_empleado = $llave_primaria[4];

        $condicion = "codigo_sucursal='$codigo_sucursal' AND hora_inicio='$hora_inicio' AND hora_fin='$hora_fin' AND fecha_registro='$fecha_reporte' AND documento_identidad_empleado='$documento_identidad_empleado'";
        $consulta_movimiento_tiempos_no_laborados_horas = SQL::seleccionar(array("movimiento_tiempos_no_laborados_horas"),array("*"),$condicion);
        $datos_movimiento_tiempos_no_laborados_horas = SQL::filaEnObjeto($consulta_movimiento_tiempos_no_laborados_horas);

        $nombre_sucursal = SQL::obtenerValor("sucursales","nombre"," codigo = '$codigo_sucursal'");
        $nombre_empleado = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)"," id = '$documento_identidad_empleado'");
        $nombre_anexo    = SQL::obtenerValor("anexos_contables","descripcion","codigo='$datos_movimiento_tiempos_no_laborados_horas->codigo_anexo_contable'");
        $nombre_motivo   = SQL::obtenerValor("motivos_tiempo_no_laborado","descripcion"," codigo = '$datos_movimiento_tiempos_no_laborados_horas->codigo_motivo_no_laboral'");
        $nombre_auxiliar = SQL::obtenerValor("auxiliares_contables","descripcion"," codigo = '$datos_movimiento_tiempos_no_laborados_horas->codigo_auxiliar_contable'");


        $informacion_turno = verificarHoraDentroTurno($documento_identidad_empleado,$datos_movimiento_tiempos_no_laborados_horas->hora_inicio,$datos_movimiento_tiempos_no_laborados_horas->hora_fin,$datos_movimiento_tiempos_no_laborados_horas->fecha_registro,$codigo_sucursal,$dias,$textos);

        $nombre_transaccion_tiempo =  SQL::obtenerValor("transacciones_tiempo","descripcion"," codigo = '$datos_movimiento_tiempos_no_laborados_horas->codigo_transaccion_tiempo'");
        /*** DefiniciÃ³n de pestaÃ±a personal ***/
         $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("codigo_sucursal", $textos["SUCURSAL"],$nombre_sucursal),
                HTML::mostrarDato("empleado", $textos["EMPLEADO"],$nombre_empleado),
                HTML::mostrarDato("anexos_contables", $textos["ANEXO_CONTABLE"],$nombre_anexo),
                HTML::mostrarDato("auxiliares_contables", $textos["AUXILIAR_CONTABLE"],$nombre_auxiliar),
                HTML::mostrarDato("transaccion_tiempo", $textos["TIPO_TRANSACCION"], $nombre_transaccion_tiempo),
                HTML::mostrarDato("motivo_tiempo", $textos["MOTIVO_TIEMPO_NO_LABORADO"],$nombre_motivo),
                HTML::mostrarDato("fecha_reporte", $textos["FECHA_RFEPORTE"],$datos_movimiento_tiempos_no_laborados_horas->fecha_registro),
                HTML::mostrarDato("turno_laborar",$textos["TURNO_LABORAL"],$informacion_turno[0])
            ),
            array(
                HTML::mostrarDato("hora_inicio", $textos["HORA_INICIO"],$datos_movimiento_tiempos_no_laborados_horas->hora_inicio),
                HTML::mostrarDato("hora_fin", $textos["HORA_FIN"],$datos_movimiento_tiempos_no_laborados_horas->hora_fin)
            ),
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );
        
        $contenido = HTML::generarPestanas($formularios,$botones);
    }
    /*** Enviar datos para la generaciÃ³n del formulario al script que originÃ¯Â¿Â½ la peticiÃ¯Â¿Â½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
} elseif (!empty($forma_procesar)) {

    $llave_primaria = explode("|", $forma_id);

    $codigo_sucursal = $llave_primaria[0];
    $hora_fin        = $llave_primaria[1];
    $hora_inicio     = $llave_primaria[2];
    $fecha_reporte   = $llave_primaria[3];
    $documento_identidad_empleado = $llave_primaria[4];

    $condicion = "codigo_sucursal='$codigo_sucursal' AND hora_inicio='$hora_inicio' AND hora_fin='$hora_fin' AND fecha_registro='$fecha_reporte' AND documento_identidad_empleado='$documento_identidad_empleado'";;
   
    $consulta = SQL::eliminar("movimiento_tiempos_no_laborados_horas",$condicion);

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
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
