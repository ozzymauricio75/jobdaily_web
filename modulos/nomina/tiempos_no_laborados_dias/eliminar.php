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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/


    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{
 
        /// Obtener los datos de la tabla de Reportes de incapacidad
        $datos               = explode('|',$url_id);

        $documento_identidad = $datos[0];
        $fecha_inicial       = $datos[1];
        $codigo_sucursal     = $datos[2];

        $condicion = "documento_identidad_empleado = '$documento_identidad' AND fecha_inicio_tiempo = '$fecha_inicial' AND codigo_sucursal='$codigo_sucursal'";

        $vistaConsulta       = "movimiento_tiempos_no_laborados_dias";
        $columnas            = SQL::obtenerColumnas($vistaConsulta);
        $consulta_reporte_tiempo_no_laborado  = SQL::seleccionar(array($vistaConsulta), $columnas,$condicion);
        $contabilizado = false;

        //////Verificar que no se haya generado ningun evento////////
        while ($datos = SQL::filaEnObjeto($consulta_reporte_tiempo_no_laborado)){
            $contabilizar = $datos->contabilizado;
            if($contabilizar=='2' || $contabilizar=='1'){
                $respuesta[0] = $textos["MENSAJE_LEIDO_SALARIO"];
                $respuesta[1] = "";
                $respuesta[2] = "";
                $contabilizado = true ;
                break;
            }
        }
        ////////////////////////////////////////////////////////////

        if(!$contabilizado){

            $vistaConsulta       = "movimiento_tiempos_no_laborados_dias";
            $columnas            = SQL::obtenerColumnas($vistaConsulta);
            $consulta_reporte_tiempo_no_laborado  = SQL::seleccionar(array($vistaConsulta), $columnas,$condicion);

            $error               = "";
            $titulo              = $componente->nombre;
            $items               = array();
            $valor_movimiento    = 0;

            if (SQL::filasDevueltas($consulta_reporte_tiempo_no_laborado)) {
                $dias = 0;
                while ($datos = SQL::filaEnObjeto($consulta_reporte_tiempo_no_laborado)){
                   
                    $nombre_transaccion       = SQL::obtenerValor("transacciones_tiempo","nombre","codigo='$datos->codigo_transaccion_tiempo'");
                    $nombre_motivo_no_laboral = SQL::obtenerValor("motivos_tiempo_no_laborado","descripcion","codigo='$datos->codigo_motivo_no_laboral'");

                    $items[] = array(
                        $dias,
                        $datos->fecha_tiempo,
                        $nombre_transaccion,
                        $nombre_motivo_no_laboral
                    );
                    $dias++;
                }
            }

            $empleado    = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)"," id = '$documento_identidad'");
            $sucursal    = SQL::obtenerValor("sucursales","nombre"," codigo = '$codigo_sucursal'");
            $fecha_inicial = explode(" ",$fecha_inicial);
            /*** Definici�n de pesta�a basica ***/
            $formularios["PESTANA_BASICA"] = array(
                array(
                    HTML::mostrarDato("sucursal",$textos["SUCURSAL_LABORA"], $sucursal),
                    HTML::mostrarDato("empleado",$textos["EMPLEADO"], $empleado)
                ),
                array(
                    HTML::mostrarDato("fecha_inicial",$textos["FECHA_INICIAL"], $fecha_inicial[0]),
                    HTML::mostrarDato("dias_incapacidad",$textos["CANTIDAD_DIAS"], $dias),
                ),
                /*array(
                     //HTML::mostrarDato("motivo",$textos["MOTIVO_TIEMPO_NO_LABORADO"], $motivo),
                    /*HTML::mostrarDato("anexo",$textos["ANEXO_CONTABLE"], $anexo),
                    HTML::mostrarDato("auxiliar",$textos["AUXILIAR_CONTABLE"], $auxiliar)
                ),*/
                /*array(
                    HTML::mostrarDato("valor_dia",$textos["VALOR_DIA"], $textos["SIMBOLO_MONEDA"].number_format($valor_dia,0)),
                    HTML::mostrarDato("dividendo",$textos["DIVIDENDO"], $dividendo),
                    HTML::mostrarDato("divisor",$textos["DIVISOR"], $divisor),
                    HTML::mostrarDato("valor_movimiento",$textos["VALOR_MOVIMIENTO"], $textos["SIMBOLO_MONEDA"].number_format($valor_movimiento,0))
                ),*/
                array(
                    HTML::generarTabla(
                            array("id","FECHA_TIEMPO","TIPO_TRANSACCION","MOTIVO_TIEMPO_NO_LABORADO"),
                            $items,
                            array("C","I","C"),
                            "listaItemsTiempo",
                            false
                        )
                )
            );
            /*** Definici�n de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios,$botones);

            $respuesta    = array();
            $respuesta[0] = $error;
            $respuesta[1] = $titulo;
            $respuesta[2] = $contenido;
            
        }
     }

    HTTP::enviarJSON($respuesta);
    
} elseif (!empty($forma_procesar)) {

    $datos               = explode('|',$forma_id);
    $documento_identidad = $datos[0];
    $fecha_inicial       = $datos[1];
    $codigo_sucursal     = $datos[2];

    $condicion = "documento_identidad_empleado = '$documento_identidad' AND fecha_inicio_tiempo = '$fecha_inicial' AND codigo_sucursal='$codigo_sucursal'";
    
    $consulta = SQL::eliminar("movimiento_tiempos_no_laborados_dias",$condicion);
    
    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
