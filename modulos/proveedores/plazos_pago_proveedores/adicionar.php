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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $cuotas = array(
        "1"	    => "1",
        "30"	=> "30",
        "60"	=> "60",
        "90"	=> "90",
        "120"	=> "120",
        "150"	=> "150",
        "180"	=> "180",
        "210"	=> "210",
        "240"	=> "240",
        "270"	=> "270",
        "300"	=> "300",
        "330"	=> "330",
        "360"	=> "360"
    );

    $codigo = SQL::obtenerValor("plazos_pago_proveedores","MAX(codigo)","codigo>0");
    if ($codigo){
        $codigo++;
    } else {
        $codigo = 1;
    }
    /*** Definición de pestañas ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("codigo", $textos["CODIGO"], 2, 2, $codigo, array("title" => $textos["AYUDA_CODIGO"],"onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 15, 15, "", array("title" => $textos["AYUDA_NOMBRE"], "onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 50, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"]))
        ),
        array(
            HTML::listaSeleccionSimple("*inicial", $textos["INICIAL"], $cuotas, 1, array("title" => $textos["AYUDA_INICIAL"], "onchange" => "recargarPlazo();")),
            HTML::listaSeleccionSimple("*final", $textos["FINAL"], $cuotas, 1, array("title" => $textos["AYUDA_FINAL"]))
        ),
        array(
            HTML::campoTextoCorto("*periodo", $textos["PERIODO"], 2, 2, "30", array("title" => $textos["AYUDA_PERIODO"],"onKeyPress" => "return campoEntero(event)"))
        ),
        array(
            HTML::campoTextoCorto("orden", $textos["ORDEN"], 2, 2, "", array("title" => $textos["AYUDA_ORDEN"],"onKeyPress" => "return campoEntero(event)"))
        )
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("plazos_pago_proveedores", "codigo", $url_valor,"codigo !=0");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO"]);
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("plazos_pago_proveedores", "nombre", $url_valor,"nombre !=''");
        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_NOMBRE"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    /*** Validar el ingreso de campos requeridos ***/
    if(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    }elseif(empty($forma_nombre)){
		$error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
	
	}elseif(empty($forma_descripcion)){
		$error   = true;
        $mensaje = $textos["DESCRIPCION_VACIO"];
	}elseif($forma_inicial > $forma_final){
		$error   = true;
        $mensaje = $textos["ERROR_DIAS"];
        
	}elseif(empty($forma_periodo) || $forma_periodo == 0){
		$error   = true;
        $mensaje = $textos["PERIODO_VACIO"];
    
    }elseif($existe = SQL::existeItem("plazos_pago_proveedores", "codigo", $forma_nombre,"codigo !=0")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

    }elseif($existe = SQL::existeItem("plazos_pago_proveedores", "nombre", $forma_nombre,"nombre !=''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
    
    }else {
		/*** Calcular el numero de cuotas ***/
		if($forma_inicial == '1'){
            $forma_inicial = '0';
            if($forma_final == '0'){
                $forma_final = '0';
            }
			$diferencia = (int)$forma_final - (int)$forma_inicial;
			if($diferencia < 0){
				$diferencia = $diferencia *(-1);
			}
            if ($diferencia==0){
                $cuotas = 1;
            } else {
                $cuotas = ((int)$diferencia/(int)$forma_periodo)+1;
            }
		}else{
			$diferencia = (int)$forma_inicial - (int)$forma_final;
			if($diferencia < 0){
				$diferencia = $diferencia *(-1);
			}
			$cuotas     = ((int)$diferencia/(int)$forma_periodo)+1;
		}
		
		/***  Insertar datos***/ 
        $datos = array(
            "codigo"	    => $forma_codigo,
            "nombre"	    => $forma_nombre,
            "descripcion"   => $forma_descripcion,
            "periodo"	    => $forma_periodo,
            "inicial"	    => $forma_inicial,
            "final"		    => $forma_final,
			"numero_cuotas" => $cuotas,
            "orden"		    => $forma_orden,
        );
        $insertar = SQL::insertar("plazos_pago_proveedores", $datos);

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }     
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
