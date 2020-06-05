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

if(!empty($url_generar)){
    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if(empty($url_id)){
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    }else{
        /*** Obtener los datos de la tabla de terceros ***/
        $vistaConsulta = "movimiento_tiempos_laborados";
        $llave_primaria = explode("|", $url_id);
        $fecha_generacion = $llave_primaria[0];
        $documento_identidad_empleado = $llave_primaria[1];

        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "fecha_inicio = '$fecha_generacion' AND documento_identidad_empleado='$documento_identidad_empleado'");
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
        $sucursal      = SQL::obtenerValor("sucursales","nombre","codigo='".$datos->codigo_sucursal."'");
        $empleado      = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)"," id = '".$datos->documento_identidad_empleado."'");
        $transacion    = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='".$datos->codigo_transaccion_tiempo."'");
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "fecha_inicio = '$fecha_generacion' AND documento_identidad_empleado='$documento_identidad_empleado'");

        $horas_generadas = array();
            if(SQL::filasDevueltas($consulta)){
                while($datos_movimientos = SQL::filaEnObjeto($consulta)){
                    $hora_inicial = substr($datos_movimientos->hora_inicio,0,5);
                    $hora_fin  = substr($datos_movimientos->hora_fin,0,5);
                    $transacion_tiempo = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$datos_movimientos->codigo_transaccion_tiempo'");
                    $horas_generadas[] = array(
                        "id",
                        $datos_movimientos->fecha_inicio,
                        $hora_inicial,
                        $hora_fin,
                        $transacion_tiempo,
                        conversor_segundos(($datos_movimientos->cantidad_minutos*60),$textos)

                    );
                }
            }
        /*** DefiniciÃ³n de pestaÃ±a personal ***/
         $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("sucursal", $textos["SUCURSAL"], $sucursal),
                HTML::mostrarDato("empleado", $textos["EMPLEADO"], $empleado)
            ),
            array(
            HTML::generarTabla(
                array("id","FECHA_INICIO","HORA_INICIO","HORA_FIN","TRANSACCION","CANTIDAD"),
                $horas_generadas,
                array("C","C","C","I","I"),
                "listaItemsExtras",
                false
                )
               )
        );
        $contenido = HTML::generarPestanas($formularios);
    }
    /*** Enviar datos para la generaciÃ³n del formulario al script que originÃ¯Â¿Â½ la peticiÃ¯Â¿Â½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
