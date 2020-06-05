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

if(isset($url_eliminarSucursal) && isset($url_id)){
    $eliminar_sucursal = SQL::eliminar("sucursales_bancos", "codigo = '$url_id' AND codigo_banco = '".$url_banco."'");

    if($eliminar_sucursal){
        $error   = 2;
        $mensaje = $textos["SUCURSAL_ELIMINADA"];
    }else{
        $error   = 3;
        $mensaje = $textos["ERROR_ELIMINAR_SUCURSAL"];
    }
    
    $datos   = array();
    $datos[0]= $error;
    $datos[1]= $mensaje;
        
    HTTP::enviarJSON($datos);
    exit();
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "bancos";
        $condicion     = "codigo = '$url_id'";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, $condicion);
        $datos         = SQL::filaEnObjeto($consulta);
        $error         = "";
        $titulo        = $componente->nombre;

        $tercero = explode('|',SQL::obtenerValor("seleccion_terceros","nombre","id = '".$datos->documento_identidad_tercero."'"));
        $tercero = $tercero[0];        
                    
        $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 2, 2, $datos->codigo, array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)"))
            .HTML::campoOculto("codigo_banco", $datos->codigo),
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 30, 30, $datos->descripcion, array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))            
        ),
        array(
            HTML::campoTextoCorto("*selector1", $textos["TERCERO"], 50, 255, $tercero, array("title" => $textos["AYUDA_TERCERO"], "class" => "autocompletable"))
            .HTML::campoOculto("documento_identidad_tercero", $datos->documento_identidad_tercero)
        )
    );
        
        /*** Obtener sucursales relacionadas con las sucursales del banco ***/
        $orden_lista_sucursales = 0;
        $item_sucursal = '';
        $consulta_sucursales = SQL::seleccionar(array("sucursales_bancos"), array("*"), "codigo_banco = '$url_id'","","codigo");
        if (SQL::filasDevueltas($consulta_sucursales)) {

            while ($datos_sucursal = SQL::filaEnObjeto($consulta_sucursales)) {

                $codigo_sucursal = $datos_sucursal->codigo;
                $nombre          = $datos_sucursal->nombre_sucursal;
                $llave_municipio = $datos_sucursal->codigo_iso.'|'.$datos_sucursal->codigo_dane_departamento.'|'.$datos_sucursal->codigo_dane_municipio;
                $municipio       = explode('|',SQL::obtenerValor("seleccion_municipios","nombre","id= '".$llave_municipio."'"));
                $municipio       = $municipio[0];
                $direccion       = $datos_sucursal->direccion;
                $telefono        = $datos_sucursal->telefono;
                $contacto        = $datos_sucursal->contacto;
                $celular         = $datos_sucursal->celular;
                $correo          = $datos_sucursal->correo;

                $co1  = HTML::campoOculto("itemTabla[".$codigo_sucursal."]", $codigo_sucursal, array("class"=>"itemTabla"));
                $co2  = HTML::campoOculto("nombres_sucursales[".$codigo_sucursal."]", $nombre, array("class"=>"nombres_sucursales"));
                $co3  = HTML::campoOculto("municipios_sucursales[".$codigo_sucursal."]", $llave_municipio, array("class"=>"municipios_sucursales"));
                $co4  = HTML::campoOculto("direcciones_sucursales[".$codigo_sucursal."]", $direccion, array("class"=>"direcciones_sucursales"));
                $co5  = HTML::campoOculto("telefonos_sucursales[".$codigo_sucursal."]", $telefono, array("class"=>"telefonos_sucursales"));
                $co6  = HTML::campoOculto("contactos[".$codigo_sucursal."]", $contacto, array("class"=>"contactos"));
                $co7  = HTML::campoOculto("nombres_municipios[".$codigo_sucursal."]", $municipio, array("class"=>"nombres_municipios"));
                $co8  = HTML::campoOculto("celulares_sucursales[".$codigo_sucursal."]", $celular, array("class"=>"celulares_sucursales"));
                $co9  = HTML::campoOculto("correos_sucursales[".$codigo_sucursal."]", $correo, array("class"=>"correos_sucursales"));
                $co10 = HTML::campoOculto("estadoModificar[".$codigo_sucursal."]", '1', array("class"=>"estadoModificar"));

                $remover = HTML::boton("botonRemoverSucursal", "", "removerItemTotal(this);", "eliminar")
                           .HTML::boton("botonModificar", "", "modificarItems2(this);","modificar");
                $celda = $co1.$co2.$co3.$co4.$co5.$co6.$co7.$co8.$co9.$co10.$remover;

                $item_sucursal[] = array( $codigo_sucursal,
                                          $celda,
                                          $nombre,
                                          $municipio,
                                          $direccion,
                                          $telefono,
                                          $contacto,
                                          $celular,
                                          $correo
                );
            }
            $orden_lista_sucursales = $codigo_sucursal+1;
        }
        
        /*** Definición de pestaña Sucursales ***/
        $formularios["PESTANA_SUCURSALES"] = array(
            array(
            HTML::campoTextoCorto("*nombre_sucursal", $textos["NOMBRE_SUCURSAL"], 50, 255, "", array("title" => $textos["AYUDA_NOMBRE_SUCURSAL"]))
            .HTML::campoOculto("contador_sucursal", $orden_lista_sucursales)
            .HTML::campoOculto("error_datos", $textos["ERROR_DATOS_SUCURSALES"])
            .HTML::campoOculto("confirmar", $textos["CONFIRMAR"])
            .HTML::campoOculto("confirmar2", $textos["CONFIRMAR2"])
            .HTML::campoOculto("estadoRegistros", "ADD")
            .HTML::campoOculto("id_sucursal", "")
            .HTML::campoOculto("id_fila", "")
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
            HTML::boton("botonAgregarSucursal", $textos["AGREGAR"], "agregarItemSucursal2();", "adicionar"),
            HTML::contenedor(HTML::boton("botonRemoverSucursal", "", "removerItemTotal(this);", "eliminar"), array("id" => "removedorSucursal", "style" => "display: none"))
            .HTML::contenedor(HTML::boton("botonModificar", "", "modificarItems2(this); " , "modificar"), array("id" => "botonModificar", "style" => "display: none"))
            ),
            array(
                HTML::generarTabla( array("id","","NOMBRE_SUCURSAL","MUNICIPIO_SUCURSAL","DIRECCION_SUCURSAL","TELEFONO_SUCURSAL","CONTACTO","CELULAR_SUCURSAL","CORREO_SUCURSAL"),
                                    $item_sucursal,
                                    array("C","I","I","I","D","I","D","I"),
                                    "lista_items_sucursales",
                                    false)
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
        $existe = SQL::existeItem("bancos", "codigo", $url_valor,"codigo != '".$url_id."'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }

    /*** Validar descripcion ***/
    if ($url_item == "descripcion" && $url_valor) {
        $existe = SQL::existeItem("bancos", "descripcion", $url_valor,"codigo != '".$url_id."'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_DESCRIPCION"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Modificar el elemento seleccionado ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    /*** Validar el ingreso de campos requeridos ***/
   if(empty($forma_codigo)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
	}elseif(empty($forma_descripcion)){
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"];
    }elseif($existe = SQL::existeItem("bancos", "codigo", $forma_codigo,"codigo !='".$forma_id."'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];
     }elseif($existe = SQL::existeItem("bancos", "descripcion", $forma_descripcion,"codigo != '".$forma_id."'")){
	    $error   = true;
        $mensaje = $textos["ERROR_EXISTE_DESCRIPCION"];
	}else{
        
        $datos = array(
            "codigo"                      => $forma_codigo,
            "documento_identidad_tercero" => $forma_documento_identidad_tercero,
            "descripcion"                 => $forma_descripcion
        );
        $insertar = SQL::modificar("bancos", $datos,"codigo = '".$forma_id."'");

        /*** Error de insercion ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
        }elseif(isset($forma_itemTabla)) {

            foreach ($forma_itemTabla as $id_sucursales) {

                $consecutivo = (int)SQL::obtenerValor("sucursales_bancos","max(codigo)","codigo_banco = '".$forma_codigo."'");
                if($consecutivo){
                    $consecutivo++;
                }else{
                    $consecutivo=1;
                }

                $municipio = split('[|,]',$forma_municipios_sucursales[$id_sucursales]);

                //echo var_dump($municipio);

                if($forma_estadoModificar[$id_sucursales]=='1'){
                    $consecutivo = $id_sucursales;
                }

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
                if($forma_estadoModificar[$id_sucursales]=='0'){
                    $insertar = SQL::insertar("sucursales_bancos", $datos);
                }else{
                    $insertar = SQL::modificar("sucursales_bancos", $datos,"codigo = '".$consecutivo."' AND codigo_banco = '".$forma_codigo."'");
                }

                /*** Error de insercón ***/
                if (!$insertar) {
                    $error   = true;
                    $mensaje = $textos["ERROR_MODIFICAR_ITEM"];
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
