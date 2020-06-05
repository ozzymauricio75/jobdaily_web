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

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a consultar
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $error         = "";
        $titulo        = $componente->nombre;

        $vistaConsulta = "gastos_prestaciones_sociales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        // Definicion de pestana personal
         $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo",  $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("descripcion",  $textos["DESCRIPCION"], $datos->descripcion)
            )
        );
        $formularios["PESTANA_CESANTIAS"] = array(
            array(
                HTML::mostrarDato("cesantia_pago_prestacion",  $textos["CESANTIA_PAGO_PRESTACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->cesantia_pago_prestacion."'"))
            ),
            array(
                HTML::mostrarDato("cesantia_pago_gasto",  $textos["CESANTIA_PAGO_GASTO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->cesantia_pago_gasto."'"))
            ),
            array(
                HTML::mostrarDato("cesantia_traslado_fondo",  $textos["CESANTIA_TRASLADO_FONDO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->cesantia_traslado_fondo."'"))
            ),
            array(
                HTML::mostrarDato("cesantia_causacion_prestacion",  $textos["CESANTIA_CAUSACION_PRESTACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->cesantia_causacion_prestacion."'"))
            ),
            array(
                HTML::mostrarDato("cesantia_causacion_gasto",  $textos["CESANTIA_CAUSACION_GASTO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->cesantia_causacion_gasto."'"))
            )
        );
        $formularios["PESTANA_INTERESES"] = array(
            array(
                HTML::mostrarDato("intereses_pago_prestacion",  $textos["INTERESES_PAGO_PRESTACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->intereses_pago_prestacion."'"))
            ),
            array(
                HTML::mostrarDato("intereses_pago_gasto",  $textos["INTERESES_PAGO_GASTO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->intereses_pago_gasto."'"))
            ),
            array(
                HTML::mostrarDato("intereses_causacion_prestacion",  $textos["INTERESES_CAUSACION_PRESTACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->intereses_causacion_prestacion."'"))
            ),
            array(
                HTML::mostrarDato("intereses_causacion_gasto",  $textos["INTERESES_CAUSACION_GASTO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->intereses_causacion_gasto."'"))
            )
        );
        $formularios["PESTANA_PRIMAS"] = array(
            array(
                HTML::mostrarDato("prima_pago_prestacion",  $textos["PRIMA_PAGO_PRESTACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->prima_pago_prestacion."'"))
            ),
            array(
                HTML::mostrarDato("prima_pago_gasto",  $textos["PRIMA_PAGO_GASTO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->prima_pago_gasto."'"))
            ),
            array(
                HTML::mostrarDato("prima_causacion_prestacion",  $textos["PRIMA_CAUSACION_PRESTACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->prima_causacion_prestacion."'"))
            ),
            array(
                HTML::mostrarDato("prima_causacion_gasto",  $textos["PRIMA_CAUSACION_GASTO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->prima_causacion_gasto."'"))
            )
        );
        $formularios["PESTANA_VACACIONES"] = array(
            array(
                HTML::mostrarDato("vacacion_pago_prestacion_disfrute",  $textos["VACACION_PAGO_PRESTACION_DISFRUTE"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->vacacion_pago_prestacion_disfrute."'"))
            ),
            array(
                HTML::mostrarDato("vacacion_pago_gasto_disfrute",  $textos["VACACION_PAGO_GASTO_DISFRUTE"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->vacacion_pago_gasto_disfrute."'"))
            ),
            array(
                HTML::mostrarDato("vacacion_pago_prestacion_liquidacion",  $textos["VACACION_PAGO_PRESTACION_LIQUIDACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->vacacion_pago_prestacion_liquidacion."'"))
            ),
            array(
                HTML::mostrarDato("vacacion_pago_gasto_liquidacion",  $textos["VACACION_PAGO_GASTO_LIQUIDACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->vacacion_pago_gasto_liquidacion."'"))
            ),
            array(
                HTML::mostrarDato("vacacion_causacion_prestacion",  $textos["VACACION_CAUSACION_PRESTACION"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->vacacion_causacion_prestacion."'"))
            ),
            array(
                HTML::mostrarDato("vacacion_causacion_gasto",  $textos["VACACION_CAUSACION_GASTO"], SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo = '".$datos->vacacion_causacion_gasto."'"))
            )
        );
        $contenido = HTML::generarPestanas($formularios);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
