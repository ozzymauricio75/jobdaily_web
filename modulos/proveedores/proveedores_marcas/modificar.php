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

if (isset($url_completar)) {

    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
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
        $llave               = explode("|",$url_id);
        $documento_identidad = $llave[0];
        $codigo_marca        = $llave[1];
        $condicion           = "documento_identidad_proveedor ='$documento_identidad' AND codigo_marca='$codigo_marca'";
        
        $vistaConsulta = "proveedores_marcas";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        /*** Obtener Valores ***/
        $proveedor    = SQL::obtenerValor("seleccion_proveedores","nombre", "id = '$documento_identidad'");
        $proveedor    = explode("|", $proveedor);
        $proveedor    = $proveedor[0];

        /*** Definici�n de pesta�as general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["PROVEEDOR"], 50, 50, $proveedor, array("title" => $textos["AYUDA_PROVEEDOR"], "onBlur" => "validarItem(this);", "class" => "autocompletable"))
                .HTML::campoOculto("documento_identidad_proveedor", $documento_identidad)
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_marca", $textos["MARCA"], HTML::generarDatosLista("marcas", "codigo", "descripcion","codigo>0"), $codigo_marca, array("title" => $textos["AYUDA_MARCA"]))
            )
        );

        /*** Definici�n de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    $llave               = explode("|",$forma_id);
    $documento_identidad = $llave[0];
    $codigo_marca        = $llave[1];
    $condicion           = "documento_identidad_proveedor ='$documento_identidad' AND codigo_marca='$codigo_marca'";

    /*** Validar campos requeridos ***/
    if(empty($forma_documento_identidad_proveedor)){
		$error   = true;
        $mensaje = $textos["PROVEEDOR_VACIO"];
        
	}elseif(empty($forma_codigo_marca)){
        $error   = true;
        $mensaje = $textos["MARCA_VACIO"];
    
    }elseif($existe = SQL::existeItem("proveedores_marcas", "codigo_marca", $forma_codigo_marca,"codigo_marca!='$codigo_marca' AND documento_identidad_proveedor='$documento_identidad'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_MARCA"];
    
     }else {

		 /*** Insertar datos ***/
        $datos = array(
            "documento_identidad_proveedor" => $forma_documento_identidad_proveedor,
            "codigo_marca"                  => $forma_codigo_marca
        );
        $consulta = SQL::modificar("proveedores_marcas", $datos, $condicion);
		
		/*** Error inserci�n ***/
        if ($consulta) {
            $error   = false;
            $mensaje = $textos["ITEM_MODIFICADO"];
        } else {
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
