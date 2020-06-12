<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
*
* Este archivo es parte de:
* PANCE :: Plataforma para la Administraciï¿½n del Nexo Cliente-Empresa
*
* Este programa es software libre: usted puede redistribuirlo y/o
* modificarlo  bajo los tï¿½rminos de la Licencia Pï¿½blica General GNU
* publicada por la Fundaciï¿½n para el Software Libre, ya sea la versiï¿½n 3
* de la Licencia, o (a su elecciï¿½n) cualquier versiï¿½n posterior.
*
* Este programa se distribuye con la esperanza de que sea ï¿½til, pero
* SIN GARANTï¿½A ALGUNA; ni siquiera la garantï¿½a implï¿½cita MERCANTIL o
* de APTITUD PARA UN PROPï¿½SITO DETERMINADO. Consulte los detalles de
* la Licencia Pï¿½blica General GNU para obtener una informaciï¿½n mï¿½s
* detallada.
*
* Deberï¿½a haber recibido una copia de la Licencia Pï¿½blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_ELIMINAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        
        /*** Obtener los datos de la tabla de Reportes de incapacidad ***/
        
        $error         = "";
        $titulo        = $componente->nombre;
        $vistaConsulta = "control_prestamos_empleados";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "id = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
                
        $tranContable   = SQL::obtenerValor("transacciones_contables","nombre"," id = '".$datos->id_transaccion_contable."'");
        $sucursal       = SQL::obtenerValor("sucursales","nombre"," id = '".$datos->id_sucursal."'");
        $empleado       = SQL::obtenerValor("seleccion_empleados", "SUBSTRING_INDEX(SUBSTRING_INDEX(empleado,'|',1),'-',-1)", "id = '".$datos->id_empleado."'");
        $concepto       = SQL::obtenerValor("conceptos_prestamos","descripcion"," id = '".$datos->concepto_prestamo."'");

           
        /*** Definici�n de pesta�a basica ***/
        $arreglo = array();
        
        $arreglo[] = array(
                HTML::mostrarDato("id_sucursal",  $textos["SUCURSAL_LABORA"], $sucursal),
                HTML::mostrarDato("id_empleado", $textos["EMPLEADO"], $empleado)
            );
        $arreglo[] = array(
                HTML::mostrarDato("transaccion_contable",  $textos["TRANSACCION_CONTABLE"], $tranContable),
                HTML::mostrarDato("concepto_prestamo", $textos["CONCEPTO_PRESTAMO"], $concepto)
            );
        $arreglo[] = array(
                HTML::mostrarDato("valor_total",  $textos["VALOR_PRESTAMO"], $datos->valor_total),
                HTML::mostrarDato("concepto_prestamo", $textos["OBSERVACIONES"], $datos->observaciones)
            );
            
        if(!empty($datos->pago_mensual)){
            $arreglo[] = array(
                HTML::mostrarDato("valor_mensual", $textos["VALOR_MENSUAL"], $datos->pago_mensual)
            );
        }
        
        if(!empty($datos->pago_primera_quincena) || !empty($datos->pago_segunda_quincena)){
            $arreglo[] = array(
                HTML::mostrarDato("valor_segunda_quincena", $textos["VALOR_SEGUNDA_QUINCENA"], $datos->pago_primera_quincena),
                HTML::mostrarDato("valor_primera_quincena", $textos["VALOR_PRIMERA_QUINCENA"], $datos->pago_segunda_quincena)
            );
        }
        
        if(!empty($datos->pago_primera_quincena) || !empty($datos->pago_segunda_quincena)){
            $arreglo[] = array(
                HTML::mostrarDato("valor_primera_semana", $textos["VALOR_PRIMERA_SEMANA"], $datos->pago_primera_semana),
                HTML::mostrarDato("valor_segunda_semana", $textos["VALOR_SEGUNDA_SEMANA"], $datos->pago_segunda_semana),
                HTML::mostrarDato("valor_tercera_semana", $textos["VALOR_TERCERA_SEMANA"], $datos->pago_tercera_semana),
                HTML::mostrarDato("valor_cuarta_semana", $textos["VALOR_CUARTA_SEMANA"], $datos->pago_cuarta_semana)
            );
        }
        
        $arreglo[] = array(
                HTML::campoTextoLargo("observaciones", $textos["OBSERVACIONES"], 4, 40, $datos->observaciones, array("disabled" => true))
            );
        
        $formularios["PESTANA_BASICA"] = $arreglo;

            /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

            $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Eliminar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    
    //$empleado = SQL::obtenerValor("reporte_incapacidades","id_empleado","id = '$forma_id'");
    
    $consulta = SQL::eliminar("control_prestamos_empleados", "id = '$forma_id'");
    
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
