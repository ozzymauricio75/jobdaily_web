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
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $error         = "";
        $titulo        = $componente->nombre;

        $llave_principal              = explode("|",$url_id);
        $documento_identidad_empleado = $llave_principal[0];
        $fecha_generacion             = $llave_principal[1];
        $consecutivo                  = $llave_principal[2];
        $concepto_prestamo            = $llave_principal[3];

        $forma_pago = array(
            "1" => $textos["MENSUAL"],
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"],
            "9" => $textos["PROPOCIONAL"]
        );


        $condicion  = "documento_identidad_empleado= '$documento_identidad_empleado' AND consecutivo='$consecutivo' AND fecha_generacion='$fecha_generacion' AND concepto_prestamo='$concepto_prestamo'";
        $consulta_fechas_prestamo      = SQL::seleccionar(array("fechas_prestamos_empleados"),array("*"),$condicion);
        $consulta_control_prestamo     = SQL::seleccionar(array("control_prestamos_empleados"),array("*"),$condicion);
        $datos_control_prestamo        = SQL::filaEnObjeto($consulta_control_prestamo);
        /////Armar datos de tabla////
        $items_tabla = array();
        while($dato_prestamo = SQL::filaEnObjeto($consulta_fechas_prestamo))
        {
          $items_tabla[] = array(
           "",
           $dato_prestamo->fecha_pago,
           number_format($dato_prestamo->valor_saldo),
           number_format($dato_prestamo->valor_descuento)
          );
        }

        ////Obtego datos////
        $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='$datos_control_prestamo->codigo_sucursal'");
        $nombre_empleado    = SQL::obtenerValor("seleccion_empleados","SUBSTRING_INDEX(nombre_completo,'|',1)","id = '$documento_identidad_empleado'");


        $nombre_transaccion_descontar = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_control_prestamo->codigo_transaccion_contable_descontar'");
        $codigo_contable_descontar    = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$datos_control_prestamo->codigo_transaccion_contable_descontar'");

        $nombre_transaccion_cobrar = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_control_prestamo->codigo_transaccion_contable_cobrar'");
        $codigo_contable_cobrar    = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$datos_control_prestamo->codigo_transaccion_contable_cobrar'");


        $nombre_concepto    = SQL::obtenerValor("conceptos_prestamos","descripcion","codigo='$datos_control_prestamo->concepto_prestamo'");
        /*** Definici�n de pesta�a basica ***/
        $arreglo = array();

        $arreglo[] = array(
                HTML::mostrarDato("mostra_fecha_prestamo", $textos["FECHA_PRESTAMO"],$datos_control_prestamo->fecha_generacion),
                HTML::mostrarDato("codigo_sucursal",  $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                HTML::mostrarDato("documento_empleado", $textos["EMPLEADO"],$nombre_empleado)
        );
        $arreglo[] = array(
                HTML::mostrarDato("codigo_transaccion_descontar",  $textos["TRANSACCION_CONTABLE_DESCONTAR"],$codigo_contable_descontar.' - '.$nombre_transaccion_descontar),
                HTML::mostrarDato("codigo_transaccion_cobrar",  $textos["TRANSACCION_CONTABLE_COBRAR"],$codigo_contable_cobrar.' - '.$nombre_transaccion_cobrar),
                HTML::mostrarDato("concepto_prestamo", $textos["CONCEPTO_PRESTAMO"],$nombre_concepto)
        );
        $arreglo[] = array(
            HTML::mostrarDato("forma_pago", $textos["FORMA_PAGO"],$forma_pago[$datos_control_prestamo->forma_pago])
        );
        $arreglo[] = array(
                HTML::mostrarDato("valor_total",  $textos["VALOR_PRESTAMO"],number_format($datos_control_prestamo->valor_total))
        );

         $arreglo[] = array(
            HTML::mostrarDato("valor_cuota",  $textos["VALOR_CUOTA"],  number_format($datos_control_prestamo->valor_pago))
        );


        $arreglo[] = array(
                HTML::contenedor(HTML::generarTabla(
                                array("id","FECHA_DESCUENTO", "SALDO_ACTUAL", "VALOR_CUOTA"),
                                $items_tabla,
                                array("I","I", "I"),
                                "listaItemsPagos",
                                false
                        )
                )
            );

            $formularios["PESTANA_BASICA"] = $arreglo;

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
