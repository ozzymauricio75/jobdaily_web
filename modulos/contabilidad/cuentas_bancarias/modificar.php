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

/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_plan_contable_flujo_bancos", $url_q);
    }
    exit;
}

/*** Devolver datos para completar la informacion requerida ***/
if (isset($url_recargarDatosCuenta)) {
    $datos = array();
    /*** Validar que la cuenta del plan contable seleccionada no este asignada a otra cuenta bancaria ***/
    $asignada   = SQL::existeItem("cuentas_bancarias", "codigo_plan_contable", "$url_id");
    if (!$asignada) {
        /*** Obtener datos de la cuenta ***/
        $anexo      = SQL::obtenerValor("plan_contable", "codigo_anexo_contable", "codigo_contable = '$url_id'");
        $consulta   = SQL::seleccionar(array("seleccion_auxiliares_contables"), array("id","descripcion"), "anexo_contable = '".$anexo."'");
        if (SQL::filasDevueltas($consulta)) {
            while ($datos_auxiliar = SQL::filaEnObjeto($consulta)) {
                $datos[$datos_auxiliar->id] = $datos_auxiliar->descripcion;
            }
        } else {
            $datos[0] = 0;
        }
    } else {
        $datos[0] = $textos["ASIGNADA"];

    }

    HTTP::enviarJSON($datos);
    exit;
}

/*** Mostrar los clientes con pedidos pendientes para la sucursal ***/
if(isset($url_recargar_sucursales) && isset($url_id_banco)){
    
    $lista = HTML::generarDatosLista("seleccion_sucursales_bancos","id","nombre_sucursal","codigo_banco = '".$url_id_banco."'");
    
    if(empty($lista)){
        $lista = array("0" => $textos["BANCO_SIN_SUCURSALES"]);
    }

    HTTP::enviarJSON($lista);
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    }else{
        $error  = "";
        $titulo = $componente->nombre;

        $llave_cuenta   = explode('|',$url_id);
        $vistaConsulta  = "cuentas_bancarias";
        $columnas       = SQL::obtenerColumnas($vistaConsulta);
        $consulta       = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
        $datos          = SQL::filaEnObjeto($consulta);

        $auxiliar       = array();
        if($datos->codigo_auxiliar_contable != 0){
            $anexo      = SQL::obtenerValor("plan_contable", "codigo_anexo_contable", "codigo_contable = '".$datos->codigo_plan_contable."'");
            $codigo_aux = $datos->codigo_empresa_auxiliar.'|'.$datos->codigo_anexo_contable.'|'.$datos->codigo_auxiliar_contable;
            $lista_aux  = HTML::generarDatosLista("seleccion_auxiliares_contables","id","descripcion","anexo_contable = '".$anexo."'");
            $auxiliar   = HTML::listaSeleccionSimple("auxiliar_contable", $textos["AUXILIAR_CONTABLE"], $lista_aux, $codigo_aux);
        }else{
            $auxiliar   = HTML::listaSeleccionSimple("auxiliar_contable", $textos["AUXILIAR_CONTABLE"], array("0||0" => " "), "", array("class" => "oculto"));
        }

        $estado = array (
            "1" => $textos["ACTIVA"],
            "0" => $textos["INACTIVA"]
        );    

        $bancos               = HTML::generarDatosLista("bancos", "codigo", "descripcion","codigo > 0");
        $tipos_documentos     = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","codigo > 0");
        $listar_sucursales    = HTML::generarDatosLista("seleccion_sucursales_bancos", "id", "nombre_sucursal","codigo_banco = '".array_shift(array_keys($bancos))."'");
        $llave_sucursal_banco = $datos->codigo_sucursal_banco.'|'.$datos->codigo_banco.'|'.$datos->codigo_iso.'|'.$datos->codigo_dane_departamento.'|'.$datos->codigo_dane_municipio;

        $plan_contable = explode('|',SQL::obtenerValor("seleccion_plan_contable_flujo_bancos","codigo_contable","id = '".$datos->codigo_plan_contable."'"));
        
        /*** Definicion de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre"), $datos->codigo_sucursal, array("title" => $textos["AYUDA_SUCURSAL"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO"], $tipos_documentos, $datos->codigo_tipo_documento, array("title" => $textos["AYUDA_TIPO_DOCUMENTO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_banco", $textos["BANCO"], $bancos, $datos->codigo_banco,array("title" => $textos["AYUDA_BANCO"],"onChange" => "verificarSucursales();"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_sucursal_banco", $textos["SUCURSALES_BANCOS"], $listar_sucursales, $llave_sucursal_banco)
            ),
            array(
                HTML::campoTextoCorto("*numero", $textos["NUMERO"], 40, 30, $datos->numero, array("title" => $textos["AYUDA_NUMERO"]))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["PLAN_CONTABLE"], 40, 255, $plan_contable[0], array("title" => $textos["AYUDA_PLAN_CONTABLE"], "class" => "autocompletable"))
                .HTML::campoOculto("codigo_plan_contable", $plan_contable[1])
            ),
            array(
                $auxiliar
            ),
            array(
                HTML::listaSeleccionSimple("estado", $textos["ESTADO"], $estado,"'".$datos->estado."'",array("title" => $textos["AYUDA_ESTADO"]))
            )
        );

        $formularios["PESTANA_PLANTILLA"] = array(
            array(
                HTML::campoTextoLargo("plantilla", $textos["PLANTILLA"], 34, 76, $datos->plantilla, array("class" => "plantilla"))
            ),
            array(
                HTML::mostrarDato("ano", "AAAA", $textos["ANO"])
                .HTML::mostrarDato("mes", "MM", $textos["MES"])
                .HTML::mostrarDato("dia", "DD", $textos["DIA"])
                .HTML::mostrarDato("valor_cheque", "VVVVVVVVVVV", $textos["VALOR_CHEQUE"])
                .HTML::mostrarDato("paguese", "P", $textos["PAGUESE"])
                .HTML::mostrarDato("suma", "S", $textos["SUMA"])
                .HTML::mostrarDato("comprobante", "C", $textos["COMPROBANTE"])
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


/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

/*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $llave   = $forma_codigo_sucursal.'|'.$forma_codigo_tipo_documento.'|'.$forma_codigo_banco.'|'.$forma_codigo_sucursal_banco.'|'.$forma_numero;

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_numero)){
		$error   = true;
		$mensaje = $textos["NUMERO_VACIO"];
	}elseif(empty($forma_codigo_plan_contable) || empty($forma_selector1)){
		$error   = true;
		$mensaje = $textos["PLAN_CONTABLE_VACIO"];
	}elseif(empty($forma_codigo_sucursal)){
		$error   = true;
		$mensaje = $textos["SUCURSAL_VACIO"];
	}elseif(empty($forma_codigo_banco)){
		$error   = true;
		$mensaje = $textos["BANCO_VACIO"];
	}elseif(empty($forma_codigo_tipo_documento)){
		$error   = true;
		$mensaje = $textos["TIPO_DOCUMENTO_VACIO"];
	}elseif(empty($forma_codigo_sucursal_banco)){
		$error   = true;
		$mensaje = $textos["SUCURSAL_BANCO_VACIO"];
	}elseif(empty($forma_plantilla)) {
        $error   = true;
        $mensaje = $textos["PLANTILLA_VACIA"];
    }elseif(SQL::existeItem("menu_cuentas_bancarias","id",$llave,"id != '".$forma_id."'")){
        $error   = true;
        $mensaje = $textos["CUENTA_EXISTE"];
    }else{
        $sucursal = explode('|',$forma_codigo_sucursal_banco);
        $auxiliar = explode('|',$forma_auxiliar_contable);
        $datos = array(
            "codigo_sucursal"          => $forma_codigo_sucursal,
            "codigo_tipo_documento"    => $forma_codigo_tipo_documento,
            "codigo_sucursal_banco"    => $sucursal[0],
            "codigo_iso"               => $sucursal[2],
            "codigo_dane_departamento" => $sucursal[3],
            "codigo_dane_municipio"    => $sucursal[4],
            "codigo_banco"             => $sucursal[1],
            "numero"                   => $forma_numero,
            "codigo_plan_contable"     => $forma_codigo_plan_contable,
            "codigo_empresa_auxiliar"  => $auxiliar[0],
            "codigo_anexo_contable"    => $auxiliar[1],
            "codigo_auxiliar_contable" => $auxiliar[2],
            "estado"                   => $forma_estado,
            "plantilla"                => $forma_plantilla
        );

        $llave_cuenta = explode('|',$forma_id);
        $modificar    = SQL::modificar("cuentas_bancarias", $datos, "codigo_sucursal = '".$llave_cuenta[0]."' AND codigo_tipo_documento = '".$llave_cuenta[1]."' AND codigo_sucursal_banco = '".$llave_cuenta[2]."' AND codigo_banco = '".$llave_cuenta[6]."' AND numero = '".$llave_cuenta[7]."'");
        
        if ($modificar) {
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
