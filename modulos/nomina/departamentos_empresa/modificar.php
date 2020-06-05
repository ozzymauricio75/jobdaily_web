<?php

/**
*
* Copyright (C) 2008 Sistemas de Apoyo Empresarial Ltda
* 
*
* Este archivo es parte de:
* PANCE :: Plataforma para la Administraci�n del Nexo Cliente-Empresa
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

/*** Devolver datos para autocompletar la b�squeda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
	echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    if (($url_item) == "selector2") {
	echo SQL::datosAutoCompletar("seleccion_cargos", $url_q);
    }
    exit;
}


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

     /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    } else {
        $error         = "";
		    $titulo        = $componente->nombre;
        
		    $vistaConsulta = "departamentos_empresa";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);

        /*** Definici�n de pesta�a personal ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("codigo", $textos["CODIGO_DEPARTAMENTO"], 5, 4, $datos->codigo, array("title" => $textos["AYUDA_DEPARTAMENTO_EMPRESA"], "onblur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
                .HTML::campoOculto("llave_principal",$datos->codigo)
            ),
            array(
                HTML::campoTextoCorto("nombre", $textos["NOMBRE_DEPARTAMENTO"], 25, 255, $datos->nombre, array("title" => $textos["AYUDA_NOMBRE_DEPARTAMENTO"], "onblur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("riesgos_profesionales", $textos["RIESGOS_PROFESIONALES"], 10, 10, number_format($datos->riesgos_profesionales,2), array("title" => $textos["AYUDA_RIESGOS_PROFESIONALES"])),
            ),
            array(
            HTML::listaSeleccionSimple("*codigo_gasto", $textos["GASTO"], HTML::generarDatosLista("gastos_prestaciones_sociales", "codigo", "descripcion","codigo!='0'"),$datos->codigo_gasto,array("title" => $textos["AYUDA_GASTO"]))
            )
        );

		    /*** Definici�n de botones ***/
		    $botones = array(
			      HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
		    );

		    $contenido = HTML::generarPestanas($formularios, $botones);
    }
    
    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    /*** Validar numero de documento ***/
    if (isset($url_valor)) {
        $existe       = SQL::existeItem("departamentos_empresa", "codigo", $url_valor, "codigo != '$url_id' AND codigo != 0");
        $existenombre = SQL::existeItem("departamentos_empresa", "nombre", $url_valor, "codigo != '$url_id' AND codigo != 0");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_DEPARTAMENTO"]);
        } 
        if ($existenombre) {
            HTTP::enviarJSON($textos["ERROR_EXISTE"]);
        } 
    }
    
/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];
    
    if(empty($forma_codigo) ||(empty($forma_nombre)) || (empty($forma_riesgos_profesionales))){
        $error = true;
        $mensaje = $textos["ERROR_DATOS_INCOMPLETOS"];

    }elseif($existe = SQL::existeItem("departamentos_empresa", "codigo", $forma_codigo, "codigo != '$forma_id'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE_DEPARTAMENTO"];
    
    }elseif($existe = SQL::existeItem("departamentos_empresa", "nombre", $forma_nombre,"codigo != '$forma_id'")){
        $error = true;
        $mensaje = $textos["ERROR_EXISTE"];

    } else {
        /*** Insertar datos ***/
        $datos = array (
             "codigo"	          	 => $forma_codigo,
             "nombre"	             => $forma_nombre,
             "riesgos_profesionales" => $forma_riesgos_profesionales,
             "codigo_gasto"          => $forma_codigo_gasto
        );
        $modificar = SQL::modificar("departamentos_empresa", $datos, "codigo = '$forma_llave_principal'");

        /*** Error de modificacion ***/
        if (!$modificar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }
    }
    
    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>

