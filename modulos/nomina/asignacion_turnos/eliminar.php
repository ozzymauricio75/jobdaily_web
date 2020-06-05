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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
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
       $vistaConsulta  = "asignacion_turnos";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "consecutivo = $url_id");
        $datos          = SQL::filaEnObjeto($consulta);
        $error          = "";
        $titulo         = $componente->nombre;
        
        $empleado       = SQL::obtenerValor("seleccion_empleados", "SUBSTRING_INDEX(nombre_completo,'|',1)", "id = '".$datos->documento_identidad_empleado."'");
        $turno          = SQL::obtenerValor("turnos_laborales", "descripcion", "codigo = '".$datos->codigo_turno."'");
        $sucursal       = SQL::obtenerValor("sucursales", "nombre", "codigo = '".$datos->codigo_sucursal."'");
        
        
        /*** Definici�n de pesta�a personal ***/
         $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("empleado", $textos["EMPLEADO"], $empleado)
            ),
            array(
                HTML::mostrarDato("sucursal", $textos["SUCURSAL"], $sucursal),
                HTML::mostrarDato("turno", $textos["TURNO_LABORAL"], $turno)
            ),
            array(
                HTML::mostrarDato("fecha_inicial", $textos["FECHA_INICIAL"], $datos->fecha_inicial),
                HTML::mostrarDato("fecha_final", $textos["FECHA_FINAL"], $datos->fecha_final)
            )
        );

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
    $consulta = SQL::eliminar("asignacion_turnos", "consecutivo = $forma_id");
    
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
