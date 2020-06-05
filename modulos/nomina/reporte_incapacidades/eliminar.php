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
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        
        /*** Obtener los datos de la tabla de Reportes de incapacidad ***/
        $datos               = explode('|',$url_id);
        $documento_identidad = $datos[0];
        $fecha_inicial       = $datos[1];
        $vistaConsulta       = "reporte_incapacidades";
        $columnas            = SQL::obtenerColumnas($vistaConsulta);
        $contabilizado       = SQL::obtenerValor("reporte_incapacidades", "contabilizado", "documento_identidad_empleado = '".$documento_identidad."' AND fecha_inicial_incapacidad = '".$fecha_inicial."' LIMIT 0,1");
        if($contabilizado=='0'){
            $consulta            = SQL::seleccionar(array($vistaConsulta), $columnas, "documento_identidad_empleado = '".$documento_identidad."' AND fecha_inicial_incapacidad = '".$fecha_inicial."'");
            $error               = "";
            $titulo              = $componente->nombre;
            $items               = array();

            if (SQL::filasDevueltas($consulta)) {
                $consecutivo=0;
                while ($datos = SQL::filaEnObjeto($consulta)){

                    $codigo_transaccion         = $datos->codigo_transaccion_tiempo;
                    $codigo_motivo              = $datos->codigo_motivo_incapacidad;
                    $fecha_reporte              = $datos->fecha_reporte_incapacidad;
                    $fecha_inicial              = $datos->fecha_inicial_incapacidad;
                    $dias                       = $datos->dias_incapacidad;
                    $numero                     = $datos->numero_incapacidad;
                    $codigo_sucursal            = $datos->codigo_sucursal;
                    $codigo_anexo               = $datos->codigo_anexo_contable;
                    $codigo_auxiliar            = $datos->codigo_auxiliar_contable;
                    $codigo_empresa_auxiliar    = $datos->codigo_empresa_auxiliar;
                    $valor_dia                  = $datos->valor_dia;
                    $dividendo                  = $datos->dividendo;
                    $divisor                    = $datos->divisor;
                    $valor_movimiento           = $datos->valor_movimiento;

                    $transaccion = SQL::obtenerValor("transacciones_tiempo","nombre"," codigo = '".$codigo_transaccion."'");
                    $motivo      = SQL::obtenerValor("motivos_incapacidad","descripcion"," codigo = '".$codigo_motivo."'");
                    $anexo       = SQL::obtenerValor("anexos_contables","descripcion"," codigo = '".$codigo_anexo."'");
                    $auxiliar    = SQL::obtenerValor("auxiliares_contables","descripcion"," codigo = '".$codigo_auxiliar."' AND codigo_empresa='".$codigo_empresa_auxiliar."' AND codigo_anexo_contable='".$codigo_anexo."'");

                    $items[] = array(
                        $consecutivo,
                        $datos->fecha_incapacidad
                    );
                    $consecutivo++;
                }            
            }
            
            $empleado    = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)"," id = '".$documento_identidad."'");
            $sucursal    = SQL::obtenerValor("sucursales","nombre"," codigo = '".$codigo_sucursal."'");
            
            /*** Definición de pestaña basica ***/
            $formularios["PESTANA_BASICA"] = array(
                array(
                    HTML::mostrarDato("sucursal",$textos["SUCURSAL_LABORA"], $sucursal),
                    HTML::mostrarDato("empleado",$textos["EMPLEADO"], $empleado)
                ),
                array(
                    HTML::mostrarDato("fecha_reporte",$textos["FECHA_REPORTE"], $fecha_reporte),
                    HTML::mostrarDato("fecha_inicial",$textos["FECHA_INICIAL"], $fecha_inicial),
                    HTML::mostrarDato("dias_incapacidad",$textos["CANTIDAD_DIAS"], $dias),
                    HTML::mostrarDato("numero_incapacidad",$textos["NUMERO_INCAPACIDAD"], $numero)
                ),
                array(
                    HTML::mostrarDato("transaccion",$textos["TIPO_TRANSACCION"], $transaccion),
                    HTML::mostrarDato("motivo",$textos["MOTIVO_INCAPACIDAD"], $motivo),
                    HTML::mostrarDato("anexo",$textos["ANEXO_CONTABLE"], $anexo),
                    HTML::mostrarDato("auxiliar",$textos["AUXILIAR_CONTABLE"], $auxiliar)
                ),
                array(
                    HTML::mostrarDato("valor_dia",$textos["VALOR_DIA"], $valor_dia),
                    HTML::mostrarDato("dividendo",$textos["DIVIDENDO"], $dividendo),
                    HTML::mostrarDato("divisor",$textos["DIVISOR"], $divisor),
                    HTML::mostrarDato("valor_movimiento",$textos["VALOR_MOVIMIENTO"], $valor_movimiento)
                ),
                array(
                    HTML::generarTabla(
                        array("id","FECHAS_INCAPACIDAD"),
                        $items,
                        array("C"),
                        "listaItemsIncapacidad",
                        false
                    )
                )
            );

            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios,$botones);
        }else{
            $error     = $textos["NO_MODIFICAR_NO_ELIMINAR"];
            $titulo    = $contabilizado;
            $contenido = "";
        }
    }

    /*** Enviar datos para la generaciÃ³n del formulario al script que originÃ¯Â¿Â½ la peticiÃ¯Â¿Â½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    $datos               = explode('|',$forma_id);
    $documento_identidad = $datos[0];
    $fecha_inicial       = $datos[1];
    
    $consulta = SQL::eliminar("reporte_incapacidades", "documento_identidad_empleado = '".$documento_identidad."' AND fecha_inicial_incapacidad = '".$fecha_inicial."'");
    
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
