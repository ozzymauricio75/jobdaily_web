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
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    $consecutivo = (int)SQL::obtenerValor("bancos","max(codigo)","");
    if($consecutivo){
        $consecutivo++;
    }else{
        $consecutivo=1;
    }

    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 2, 2, $consecutivo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 30, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))            
        ),
        array(
            HTML::campoTextoCorto("*selector1", $textos["TERCERO"], 50, 255, "", array("title" => $textos["AYUDA_TERCERO"], "class" => "autocompletable"))
            .HTML::campoOculto("documento_identidad_tercero", "")
        )
    );

    $formularios["PESTANA_SUCURSALES"] = array(
        array(
            HTML::campoTextoCorto("*nombre_sucursal", $textos["NOMBRE_SUCURSAL"], 50, 255, "", array("title" => $textos["AYUDA_NOMBRE_SUCURSAL"]))
            .HTML::campoOculto("contador_sucursal", 0)
            .HTML::campoOculto("error_datos", $textos["ERROR_DATOS_SUCURSALES"])
        ),
        array(
            HTML::campoTextoCorto("*selector2", $textos["MUNICIPIO_SUCURSAL"], 50, 255, "", array("title" => $textos["AYUDA_MUNICIPIO_SUCURSAL"], "class" => "autocompletable"))
            .HTML::campoOculto("codigo_municipio_sucursal", "")
        ),
        array(
            HTML::campoTextoCorto("*direccion", $textos["DIRECCION_SUCURSAL"], 50, 50, "", array("title" => $textos["AYUDA_DIRECCION_SUCURSAL"]))
        ),
        array(
            HTML::campoTextoCorto("*telefono", $textos["TELEFONO_SUCURSAL"], 15, 15, "", array("title" => $textos["AYUDA_TELEFONO_SUCURSAL"])),
            HTML::campoTextoCorto("contacto", $textos["CONTACTO"], 28, 60, "", array("title" => $textos["AYUDA_CONTACTO"]))
        ),
        array(
            HTML::campoTextoCorto("celular", $textos["CELULAR_SUCURSAL"], 15, 15, "", array("title" => $textos["AYUDA_CELULAR_SUCURSAL"])),
            HTML::campoTextoCorto("correo", $textos["CORREO_SUCURSAL"], 28, 40, "", array("title" => $textos["AYUDA_CORREO_SUCURSAL"]))
        ),
        array(
            HTML::boton("botonAgregarSucursal", $textos["AGREGAR"], "agregarItemSucursal();", "adicionar"),
            HTML::contenedor(HTML::boton("botonRemoverSucursal", "", "removerItem(this);", "eliminar"), array("id" => "removedorSucursal", "style" => "display: none"))
            .HTML::contenedor(HTML::boton("botonModificar", "", "modificarItems(this); " , "modificar"), array("id" => "botonModificar", "style" => "display: none"))
        ),
        array(
            HTML::generarTabla( array("id","ACCIONES","NOMBRE_SUCURSAL","MUNICIPIO_SUCURSAL","DIRECCION_SUCURSAL","TELEFONO_SUCURSAL","CONTACTO","CELULAR_SUCURSAL","CORREO_SUCURSAL"),
                "",
                array("I","I","I","I","D","I","C","I"),
                "lista_items_sucursales",
                false)
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

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    /*** Validar codigo ***/
    if ($url_item == "codigo" && $url_valor) {
        $existe = SQL::existeItem("bancos", "codigo", $url_valor);

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }

    /*** Validar descripcion ***/
    if ($url_item == "descripcion" && $url_valor) {
        $existe = SQL::existeItem("bancos", "descripcion", $url_valor);

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_DESCRIPCION"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

   /*** Validar el ingreso de campos requeridos ***/
   if(empty($forma_codigo)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
	}elseif(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"];
    }elseif($existe = SQL::existeItem("bancos", "codigo", $forma_codigo,"codigo !=''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
     }elseif($existe = SQL::existeItem("bancos", "descripcion", $forma_descripcion,"descripcion !=''")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
	}else{
        
        $datos = array(
            "codigo"                      => $forma_codigo,
            "documento_identidad_tercero" => $forma_documento_identidad_tercero,
            "descripcion"                 => $forma_descripcion
        );
        $insertar = SQL::insertar("bancos", $datos);

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }elseif(isset($forma_itemTabla)) {

            foreach ($forma_itemTabla as $id_sucursales) {

                $consecutivo = (int)SQL::obtenerValor("sucursales_bancos","max(codigo)","codigo_banco = '".$forma_codigo."'");
                if($consecutivo){
                    $consecutivo++;
                }else{
                    $consecutivo=1;
                }

                $municipio = explode(",",$forma_municipios_sucursales[$id_sucursales]);

                $datos = array(
                    "codigo"                   => $consecutivo,
                    "codigo_iso"               => $municipio[0],
                    "codigo_dane_departamento" => $municipio[1],
                    "codigo_dane_municipio"    => $municipio[2],
                    "codigo_banco"             => $forma_codigo,
                    "nombre_sucursal"          => $forma_nombres_sucursales[$id_sucursales],
                    "direccion"                => $forma_direcciones_sucursales[$id_sucursales],
                    "telefono"                 => $forma_telefonos_sucursales[$id_sucursales],
                    "contacto"                 => $forma_contactos[$id_sucursales],
                    "correo"                   => $forma_correos_sucursales[$id_sucursales],
                    "celular"                  => $forma_celulares_sucursales[$id_sucursales]
                );
                $insertar = SQL::insertar("sucursales_bancos", $datos);

                /*** Error de insercón ***/
                if (!$insertar) {
                    $error   = true;
                    $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                }
            }
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
