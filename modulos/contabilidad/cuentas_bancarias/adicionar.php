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
    //$asignada   = SQL::existeItem("cuentas_bancarias", "codigo_plan_contable", "$url_id");
    //if (!$asignada) {
        /*** Obtener datos de la cuenta ***/
        $anexo      = SQL::obtenerValor("plan_contable", "codigo_anexo_contable", "codigo_contable = '$url_id'");
        $empresa    = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");
        $consulta   = SQL::seleccionar(array("seleccion_auxiliares_contables"), array("id","descripcion"), "anexo_contable = '".$anexo."' AND empresa = '".$empresa."'");
        if (SQL::filasDevueltas($consulta)) {
            while ($datos_auxiliar = SQL::filaEnObjeto($consulta)) {
                $datos[$datos_auxiliar->id] = $datos_auxiliar->descripcion;
            }
        } else {
            $datos['0||0'] = '';
        }
    //} else {
       // $datos[0] = $textos["SIN_AUXILIARES"];

   // }

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
    $error  = "";
    $titulo = $componente->nombre;

    $estado = array (
        "1" => $textos["ACTIVA"],
        "0" => $textos["INACTIVA"]
    );

    $tipo_cuenta = array(
        "1" => $textos["AHORROS"],
        "2" => $textos["CORRIENTE"]
    );    

    $bancos            = HTML::generarDatosLista("bancos", "codigo", "descripcion","codigo > 0");
    $tipos_documentos  = HTML::generarDatosLista("tipos_documentos", "codigo", "descripcion","codigo > 0");
    $listar_sucursales = HTML::generarDatosLista("seleccion_sucursales_bancos", "id", "nombre_sucursal","codigo_banco = '".array_shift(array_keys($bancos))."'");
    
    /*** Definicion de pestañas general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"], HTML::generarDatosLista("sucursales", "codigo", "nombre"), "", array("title" => $textos["AYUDA_SUCURSAL"]))
        ),
        array(
            HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO"], $tipos_documentos, "", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"]))
        ),
        array(
            HTML::listaSeleccionSimple("*codigo_banco", $textos["BANCO"], $bancos, "",array("title" => $textos["AYUDA_BANCO"],"onChange" => "verificarSucursales();"))
        ),
        array(
            HTML::listaSeleccionSimple("*codigo_sucursal_banco", $textos["SUCURSALES_BANCOS"], $listar_sucursales, "")
        ),
        array(
            HTML::mostrarDato("errorDialogo","","")
        ),
        array(
            HTML::campoTextoCorto("*numero", $textos["NUMERO"], 40, 30, "", array("title" => $textos["AYUDA_NUMERO"])),
            HTML::listaSeleccionSimple("tipo_cuenta", $textos["TIPO_CUENTA"], $tipo_cuenta)
        ),
        array(
            HTML::campoTextoCorto("*selector1", $textos["PLAN_CONTABLE"], 40, 255, "", array("title" => $textos["AYUDA_PLAN_CONTABLE"], "class" => "autocompletable"))
            .HTML::campoOculto("codigo_plan_contable", "")
        ),
        array(
            HTML::listaSeleccionSimple("auxiliar_contable", $textos["AUXILIAR_CONTABLE"], array("0||0" => " "), "", array("class" => "oculto"))
        ),
        array(
            HTML::listaSeleccionSimple("estado", $textos["ESTADO"], $estado,"",array("title" => $textos["AYUDA_ESTADO"]))
        )
    );

    /*$formularios["PESTANA_PLANTILLA"] = array(
        array(
            HTML::campoTextoLargo("plantilla", $textos["PLANTILLA"], 34, 76, $textos["PLANTILLA_INICIAL"], array("class" => "plantilla"))
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

    /*** Definicion de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);
  
    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);


/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

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
	//}elseif(empty($forma_plantilla)) {
    //    $error   = true;
    //    $mensaje = $textos["PLANTILLA_VACIA"];
    } else {
		/*** Insertar datos ***/

        $sucursal        = explode('|',$forma_codigo_sucursal_banco);
        $auxiliar        = explode('|',$forma_auxiliar_contable);
        $forma_plantilla = "AAAA";

        $existe = SQL::existeItem("cuentas_bancarias","numero",$forma_numero,"codigo_sucursal = '".$forma_codigo_sucursal."' AND codigo_tipo_documento = '".$forma_codigo_tipo_documento."' AND codigo_sucursal_banco = '".$sucursal[0]."' AND codigo_iso = '".$sucursal[2]."' AND codigo_dane_departamento = '".$sucursal[3]."' AND codigo_dane_municipio = '".$sucursal[4]."' AND codigo_banco = '".$sucursal[1]."'");

        if($existe){
            $error   = true;
            $mensaje = $textos["ERROR_LLAVE_REPETIDA"];
        }else{
            $existe = false;

            if($auxiliar[2]!='0'){
                $existe = SQL::existeItem("cuentas_bancarias","codigo_plan_contable",$forma_codigo_plan_contable,"codigo_empresa_auxiliar = '".$auxiliar[0]."' AND codigo_anexo_contable = '".$auxiliar[1]."' AND codigo_auxiliar_contable = '".$auxiliar[2]."'");                
            }

            if($existe){
                $error   = true;
                $mensaje = $textos["ERROR_LLAVE_REPETIDA"];
            }else{
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
                    "plantilla"                => $forma_plantilla,
                    "tipo_cuenta"              => $forma_tipo_cuenta
                );
                $insertar = SQL::insertar("cuentas_bancarias", $datos);

                /*** Error de insercion ***/
                if (!$insertar) {
                    $error   = true;
                    $mensaje = $textos["ERROR_AUXILIARES_ASIGNADOS"];
                }
            }
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que origin? la petici?n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
