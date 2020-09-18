<?php

/**
*
* Copyright (C) 2020 Jobdaily
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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "movimientos_tesoreria";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener valores ***/
        $grupo_tesoreria    = SQL::obtenerValor("grupos_tesoreria","nombre_grupo","codigo='$datos->codigo_grupo_tesoreria'");
        $concepto_tesoreria = SQL::obtenerValor("conceptos_tesoreria","nombre_concepto","codigo='$datos->codigo_concepto_tesoreria'");
        $sucursal           = SQL::obtenerValor("cuentas_bancarias","codigo_sucursal","numero='$datos->cuenta_origen'");
        $sucursal           = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");
        $nit_proveedor      = SQL::obtenerValor("cuentas_bancarias_proveedores","documento_identidad_proveedor","cuenta='$datos->cuenta_proveedor'");
        $tipo_persona       = SQL::obtenerValor("terceros","tipo_persona","documento_identidad='$proveedor'");

        if($tipo_persona==1){
            $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$nit_proveedor."'");
            $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad = '".$nit_proveedor."'");
            $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad = '".$nit_proveedor."'");
            $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad = '".$nit_proveedor."'");
            $nombre_proveedor = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
        }else{
           $nombre_proveedor  = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '".$nit_proveedor."'"); 
        }

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("nombre_grupo", $textos["GRUPO_TESORERIA"], $grupo_tesoreria),
                HTML::mostrarDato("nombre_concepto", $textos["CONCEPTO_TESORERIA"], $concepto_tesoreria)
            ),
            array(
                HTML::mostrarDato("cuenta_origen", $textos["CUENTA_ORIGEN"], $datos->cuenta_origen),
                HTML::mostrarDato("sucursal", $textos["TERCERO"], $sucursal)
            ),
            array(    
                HTML::mostrarDato("cuenta_destino", $textos["CUENTA_DESTINO"], $datos->cuenta_proveedor),
                HTML::mostrarDato("proveedor", $textos["TERCERO"], $nombre_proveedor)
            )
        );
        
        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
