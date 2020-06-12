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

        $concepto_retiro_cesantias = array(
       "1"  => $textos["VIVIENDA"],
       "2"  => $textos["EDUCACION"]

   );

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
        $consecutivo                  = $llave_principal[1];
        $fecha_generacion             = $llave_principal[2];
        $concepto_retiro              = $llave_principal[3];

        $condicion                     = "documento_identidad_empleado= '$documento_identidad_empleado' AND consecutivo='$consecutivo' AND fecha_generacion='$fecha_generacion' AND concepto_retiro='$concepto_retiro'";
        $consulta_retiro_cesantias     = SQL::seleccionar(array("retiro_cesantias"),array("*"),$condicion);
        $datos_retiro_cesantias        = SQL::filaEnObjeto($consulta_retiro_cesantias);
        ////Obtego datos////
        $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo='$datos_retiro_cesantias->codigo_sucursal'");
        $nombre_empleado    = SQL::obtenerValor("menu_retiro_cesantias","NOMBRE_EMPLEADO","id='$url_id'");
        $nombre_transaccion = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$datos_retiro_cesantias->codigo_transaccion_contable'");
        $nombre_concepto    = $concepto_retiro_cesantias[(int)$datos_retiro_cesantias->concepto_retiro];
        ///// Definici�n de pesta�a basica /////
        $arreglo = array();
        
        $arreglo[] = array(
                HTML::mostrarDato("codigo_sucursal",  $textos["SUCURSAL_LABORA"],$nombre_sucursal),
                HTML::mostrarDato("documento_empleado", $textos["EMPLEADO"],$nombre_empleado)
            );
        $arreglo[] = array(
                HTML::mostrarDato("transaccion_contable",  $textos["TRANSACCION_CONTABLE"],$nombre_transaccion),
                HTML::mostrarDato("concepto_prestamo", $textos["CONCEPTO_PRESTAMO"],$nombre_concepto)
            );
                  
         $arreglo[] = array(
                HTML::mostrarDato("valor_retiro",  $textos["VALOR_RETIRO"],$datos_retiro_cesantias->valor_retiro)
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
