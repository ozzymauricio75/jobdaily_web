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
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
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
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $llave_primaria_bodega = explode("|",$url_id);
        $codigo_bodega         = $llave_primaria_bodega[0];
        $codigo_sucursal       = $llave_primaria_bodega[1];

        $vistaConsulta = "bodegas";
        $condicion     = "codigo = '$codigo_bodega' AND codigo_sucursal=$codigo_sucursal";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas,$condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;
		
		if($datos->tipo_inventario == 1){
			$tipo_inventario =  $textos["INVENTARIO"];
			
		}elseif($datos->tipo_inventario == 2){
			$tipo_inventario =  $textos["OBSEQUIO"];
			
		}elseif($datos->tipo_inventario == 3){
			$tipo_inventario =  $textos["CONSIGNACION"];
			
		}elseif($datos->tipo_inventario == 4){
			$tipo_inventario =  $textos["PRESTAMO"];
			
		}elseif($datos->tipo_inventario == 5){
			$tipo_inventario =  $textos["SERVICIO_TECNICO"];
		
		}elseif($datos->tipo_inventario == 6){
			$tipo_inventario =  $textos["CONSIGNACION_CLIENTES"];
		
		}elseif($datos->tipo_inventario == 7){
			$tipo_inventario =  $textos["PRESTAMO_TERCEROS"];
		}
		
		$sucursal = SQL::obtenerValor("sucursales","nombre","codigo = '$datos->codigo_sucursal'");
		
        /*** Definici�n de pesta�as ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"],$datos->codigo),
                HTML::mostrarDato("nombre", $textos["NOMBRE"],$datos->nombre)
            ),
			array(
                HTML::mostrarDato("id_sucursal", $textos["SUCURSAL"], $sucursal)
            ),
            array(
                HTML::mostrarDato("tipo_bodega", $textos["TIPO_BODEGA"], SQL::obtenerValor("tipos_bodegas", "nombre", "codigo = $datos->codigo_tipo_bodega"))
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"],$datos->descripcion)
            ),
			array(
            	HTML::mostrarDato("*tipo_inventario", $textos["TIPO_INVENTARIO"], $tipo_inventario)
			)
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
