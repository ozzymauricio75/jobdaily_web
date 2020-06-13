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

if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit;

}elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."'");
    }

    HTTP::enviarJSON($respuesta);
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta  = "proyectos";
        $condicion      = "codigo = '$url_id'";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos          = SQL::filaEnObjeto($consulta);
        $valor_proyecto = number_format($datos->valor_proyecto,0);

        $error          = "";
        $titulo         = $componente->nombre;

        $activo = array(
            "0" => $textos["ESTADO_INACTIVA"],
            "1" => $textos["ESTADO_ACTIVA"]
        );
        
        $indicador = array(
            "0" => $textos["INDICADOR_NO"],
            "1" => $textos["INDICADOR_SI"]
        );

        $tipoEmpresa = array(
            "1" => $textos["EMPRESA_DISTRIBUIDORA_MAYORISTA"],
            "2" => $textos["EMPRESA_VENTAS_PUBLICO"],
            "3" => $textos["EMPRESA_AMBAS"],
            "4" => $textos["EMPRESA_SOPORTE"]
        );
        
        /*** Obtener valores ***/
        $llave_primaria_municipio = $datos->codigo_iso.",".$datos->codigo_dane_departamento.",".$datos->codigo_dane_municipio;

        $tipo      = SQL::obtenerValor("sucursales","tipo","codigo = '$datos->codigo_sucursal_ejecuta'");
        $municipio = SQL::obtenerValor("seleccion_municipios","nombre","llave_primaria = '$llave_primaria_municipio'");
        $municipio = explode("|",$municipio);
        $municipio = $municipio[0];

        if ($tipo == '0'){
            $valor_tipo_principal = true;
            $valor_tipo_sucursal  = false;
            $valor_tipo_union     = false;

        }if ($tipo == '1'){
            $valor_tipo_principal = false;
            $valor_tipo_sucursal  = true;
            $valor_tipo_union     = false;
        }if ($tipo == '2'){
            $valor_tipo_principal = false;
            $valor_tipo_sucursal  = false;
            $valor_tipo_union     = true;
        }
        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 9, 9, $datos->codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            ),
           array(
                HTML::listaSeleccionSimple("*empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0"), $datos->codigo_empresa_ejecuta, array("title" => $textos["AYUDA_EMPRESAS"],"onChange" => "recargarLista('codigo_empresa','codigo_sucursal');recargarListaEmpresas();")),

                HTML::listaSeleccionSimple("*sucursal", $textos["CONSORCIO"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0"), $datos->codigo_sucursal_ejecuta, array("title" => $textos["AYUDA_CONSORCIO"],"onChange" => "recargarListaEmpresas();"))
                .HTML::campoOculto("tipo", $tipo),
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 40, 60, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*valor_proyecto", $textos["VALOR_PROYECTO"], 15, 15, $valor_proyecto, array("title" => $textos["AYUDA_VALOR_PROYECTO"],"class" => "numero", "onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)", "onkeyup"=>"formatoMiles(this)", "onchange"=>"formatoMiles(this)"))
            ),
            array(
                HTML::listaSeleccionSimple("activo", $textos["ESTADO"], $activo, $datos->activo, array("title" => $textos["AYUDA_ACTIVO"],"onBlur" => "validarItem(this);"))
            ),
        );

        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 40, 255, $municipio, array("title" => $textos["AYUDA_MUNICIPIOS"], "class" => "autocompletable")).HTML::campoOculto("id_municipio",$llave_primaria_municipio)
            ),
            array(
                HTML::campoTextoCorto("*direccion_proyecto", $textos["DIRECCION_PROYECTO"], 40, 60, $datos->direccion_proyecto, array("title" => $textos["AYUDA_DIRECCION"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    /*** Validar codigo ***/
    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("proyectos", "codigo", $url_valor, "codigo != '$url_id' AND codigo !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }

    /*** Validar nombre ***/ 
    if ($url_item == "nombre" && $url_valor) {
        $existe = SQL::existeItem("proyectos", "nombre", $url_valor, "codigo != '$url_id' AND nombre !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE"];
        }
    }
    
    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_codigo)){
		$error   = true;
        $mensaje = $textos["CODIGO_VACIO"];
        
	}elseif(empty($forma_empresa)){
		$error   = true;
        $mensaje = $textos["EMPRESA_VACIO"];
	}elseif(empty($forma_sucursal)){
        $error   = true;
        $mensaje = $textos["SUCURSAL_VACIO"];
        
    }elseif(empty($forma_nombre)){
		$error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];
        
	}elseif(empty($forma_id_municipio)){
		$error   = true;
        $mensaje = $textos["MUNICIPIO_VACIO"];
        
	}elseif(empty($forma_direccion_proyecto)){
		$error   = true;
        $mensaje = $textos["DIRECCION_VACIO"];
        
	}elseif(SQL::existeItem("proyectos", "codigo", $forma_codigo,"codigo !='' AND codigo !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

     }elseif(SQL::existeItem("proyectos", "nombre", $forma_nombre,"nombre !='' AND codigo !='$forma_id'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];
    } else {

	   $vistaConsulta   = "seleccion_municipios";
       $columnas        = SQL::obtenerColumnas($vistaConsulta);
       $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "llave_primaria= '$forma_id_municipio'");
       $datos_municipio = SQL::filaEnObjeto($consulta);

       $codigo_iso                 = $datos_municipio -> pais;
       $codigo_dane_departamento   = $datos_municipio -> departamento;
       $codigo_dane_municipio      = $datos_municipio -> codigo;

       /*** Quitar separador de miles a un numero ***/
        function quitarMiles($cadena){
            $valor = array();
            for ($i = 0; $i < strlen($cadena); $i++) {
                if (substr($cadena, $i, 1) != ".") {
                    $valor[$i] = substr($cadena, $i, 1);
                }
            }
            $valor = implode($valor);
            return $valor;
        }

        $forma_valor_proyecto = quitarMiles($forma_valor_proyecto);

        /*** Insertar datos ***/
        $datos = array(
            "codigo"                       => $forma_codigo,
            "codigo_empresa_ejecuta"       => $forma_empresa,
            "codigo_sucursal_ejecuta"      => $forma_sucursal,
            "nombre"                       => $forma_nombre,
            "fecha_cierre"                 => $forma_fecha_cierre,
            "activo"                       => $forma_activo,
            "codigo_iso"                   => $codigo_iso,
            "codigo_dane_departamento"     => $codigo_dane_departamento,
            "codigo_dane_municipio"        => $codigo_dane_municipio,
            "direccion_proyecto"           => $forma_direccion_proyecto,
            "valor_proyecto"               => $forma_valor_proyecto
        );

        $consulta = SQL::modificar("proyectos", $datos, "codigo = '$forma_id'");
		
		/*** Error inserción ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
