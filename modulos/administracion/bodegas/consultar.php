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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
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
		
        /*** Definición de pestañas ***/
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

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
