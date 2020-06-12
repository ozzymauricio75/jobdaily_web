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
* modificarlo  bajo los t�rminos de la Licencia Pï¿½blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versiï¿½n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
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

//Determinar periodos de descutos
  $periodos_descuento = array(
      "0" => $textos["DESCUENTO_ILIMITADO"],
      "1" => $textos["DESCUENTO_FECHA"],
      "2" => $textos["DESCUENTO_TOPE"]
  );

 $estado = array(
     "0" => $textos["ESTADO_0"],
     "1" => $textos["ESTADO_1"],
     "2" => $textos["ESTADO_2"]
 );

 $forma_pago_mensual = array(
     "1" => $textos["MENSUAL"]
 );
 $forma_pago_semanal = array(
     "4" => $textos["PRIMERA_SEMANA"],
     "5" => $textos["SEGUNDA_SEMANA"],
     "6" => $textos["TERCERA_SEMANA"],
     "7" => $textos["CUARTA_SEMANA"],
     "8" => $textos["QUINTA_SEMANA"]
 );
 $forma_pago_quincenal = array(
     "2" => $textos["PRIMERA_QUINCENA"],
     "3" => $textos["SEGUNDA_QUINCENA"],
     "9" => $textos["PROPOCIONAL"],
 );

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)){

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if(empty($url_id)){
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{

        $error         = "";
        $titulo        = $componente->nombre;

        $llave_principal              = explode("|",$url_id);
        $codigo_empresa               = $llave_principal[0];
        $documento_identidad_empleado = $llave_principal[1];
        $obligacion                   = $llave_principal[2];

        $condicion  = "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND obligacion='$obligacion'";
        $consulta_control_prestamo     = SQL::seleccionar(array("control_prestamos_terceros"),array("*"),$condicion);
        $datos_control_prestamo        = SQL::filaEnObjeto($consulta_control_prestamo);
        //echo var_dump($datos_control_prestamo);
        ////Obtego datos////
        $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='$datos_control_prestamo->codigo_sucursal'");
        $nombre_empleado    = SQL::obtenerValor("menu_control_prestamos_terceros","EMPLEADO","id='$url_id'");
        $tercero            = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '$datos_control_prestamo->documento_identidad_tercero'");
        
        $nombre_transaccion_contable_descuento = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo='$datos_control_prestamo->transaccion_contable_descuento'");
        $nombre_transaccion_contable_empleado  = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo='$datos_control_prestamo->transaccion_contable_empleado'");
        $nombre_transaccion_contable_pagar     = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo='$datos_control_prestamo->transaccion_contable_pagar_tercero'");
        $nombre_transaccion_contable_pago      = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo='$datos_control_prestamo->transaccion_contable_pago_tercero'");

        /// Definici�n de pesta�a basica ///
        $codigo_planilla = SQL::obtenerValor("sucursal_contrato_empleados","codigo_planilla","documento_identidad_empleado='$documento_identidad_empleado' ORDER BY fecha_ingreso_sucursal DESC LIMIT 0,1");
        $periodo_empleado = SQL::obtenerValor("planillas","periodo_pago","codigo='$codigo_planilla'");
        $limite_descuento = $datos_control_prestamo->limite_descuento;

        if($limite_descuento=='0'){
           $datos_limite_descueto = array(
                HTML::mostrarDato("limite_descuento",$textos["LIMITE_DESCUENTO"],$periodos_descuento[$limite_descuento])
           );
        }elseif($limite_descuento=='1'){
           $datos_limite_descueto = array(
                HTML::mostrarDato("limite_descuento",$textos["LIMITE_DESCUENTO"],$periodos_descuento[$limite_descuento]),
                HTML::mostrarDato("fecha_limite_pago",$textos["FECHA_HASTA_DESCONTAR"],$datos_control_prestamo->fecha_limite_descuento)
           );
        }elseif($limite_descuento=='2'){
           $datos_limite_descueto = array(
                HTML::mostrarDato("limite_descuento",$textos["LIMITE_DESCUENTO"],$periodos_descuento[$limite_descuento]),
                HTML::mostrarDato("valor_tope_descuento",$textos["VALOR_TOPE_DESCUENTO"],$datos_control_prestamo->valor_tope_descuento)
           );
        }

       if($periodo_empleado=='1'){
            $forma_pago = $forma_pago_mensual;
            $valor_descuento_1 = number_format($datos_control_prestamo->valor_descontar_mensual);
            $datos = array(
                HTML::mostrarDato("valor_descuento_1",$textos["VALOR_DESCUENTO"],$valor_descuento_1)
             );
       }else{
            $forma_pago = $forma_pago_quincenal;
            $valor_descuento_1 = $datos_control_prestamo->valor_descontar_primera_quincena;
            $valor_descuento_2 = $datos_control_prestamo->valor_descontar_segunda_quincena;
            $periodo_pago      = $datos_control_prestamo->periodo_pago;
            if($periodo_pago=='9'){
                $datos = array(
                    HTML::mostrarDato("valor_descuento_1",$textos["VALOR_DESCUENTO_PRIMERA_QUINCENA"],number_format($valor_descuento_1)),
                    HTML::mostrarDato("valor_descuento_2",$textos["VALOR_DESCUENTO_SEGUNDA_QUINCENA"],number_format($valor_descuento_2))
                );
            }else{
                $valor_descuento_1 = number_format($valor_descuento_1 + $valor_descuento_2);
                $datos = array(
                HTML::mostrarDato("valor_descuento_1",$textos["VALOR_DESCUENTO"],$valor_descuento_1)
                );
            }
       }

       $arreglo = array();
        
       $arreglo[] = array(
                HTML::mostrarDato("fecha_inicio_descuento", $textos["FECHA_INICIO_DESCUENTO"],$datos_control_prestamo->fecha_inicio_descuento),
                HTML::mostrarDato("estado", $textos["ESTADO"],$estado[$datos_control_prestamo->estado])
        );
        
        $arreglo[] = array(
                HTML::mostrarDato("codigo_sucursal",  $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                HTML::mostrarDato("documento_empleado", $textos["EMPLEADO"],$nombre_empleado),
                HTML::mostrarDato("documento_tercero", $textos["NOMBRE_TERCERO"],$tercero)
            );
        $arreglo[] = array(
                HTML::mostrarDato("obligacion",  $textos["OBLIGACION"],$obligacion),
            );
        $arreglo[] = $datos_limite_descueto;
        
        $arreglo[] = array(
                HTML::mostrarDato("periodo_pago",$textos["PERIODO_PAGO"],$forma_pago[$datos_control_prestamo->periodo_pago])
         );
        $arreglo[] = $datos;

        $formularios["PESTANA_BASICA"] = $arreglo;


        $formularios["TRANSACCION_CONTABLE"] = array(
            array(
                 HTML::mostrarDato("transaccion_contable_descuento",  $textos["TRANSACCION_CONTABLE_DESCUENTO"],$nombre_transaccion_contable_descuento),
            ),
            array(
                 HTML::mostrarDato("transaccion_contable_empleado",  $textos["TRANSACCION_CONTABLE_EMPLEADO"],$nombre_transaccion_contable_empleado),
            ),
            array(
                HTML::mostrarDato("transaccion_contable_pagar",  $textos["TRANSACCION_CONTABLE_PAGAR_TERCERO"],$nombre_transaccion_contable_pagar),
            ),
            array(
                HTML::mostrarDato("transaccion_contable_pago",  $textos["TRANSACCION_CONTABLE_PAGO_TERCERO"],$nombre_transaccion_contable_pago),
             )

       );

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
