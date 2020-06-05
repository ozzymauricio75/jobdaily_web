<?php

/**
*
* Copyright (C) 2008 Felinux Ltda
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
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error    = "";
    $titulo   = $componente->nombre;
    
    $consulta_empresas = SQL::seleccionar(array("empresas"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_empresas)){

        $activo = array(
            "0" => $textos["ESTADO_INACTIVA"],
            "1" => $textos["ESTADO_ACTIVA"]
        );

        $tipoEmpresa = array(
            "1" => $textos["EMPRESA_DISTRIBUIDORA_MAYORISTA"],
            "2" => $textos["EMPRESA_VENTAS_PUBLICO"],
            "3" => $textos["EMPRESA_AMBAS"],
            "4" => $textos["EMPRESA_SOPORTE"]
        );

        $indicador = array(
            "0" => $textos["INDICADOR_NO"],
            "1" => $textos["INDICADOR_SI"]
        );

         /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, "", array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
                HTML::listaSeleccionSimple("*id_empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0"), "", array("title" => $textos["AYUDA_EMPRESAS"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 40, 60, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("nombre_corto", $textos["NOMBRE_CORTO"], 10, 10, "", array("title" => $textos["AYUDA_NOMBRE_CORTO"],"onBlur" => "validarItem(this);")),
                HTML::listaSeleccionSimple("activo", $textos["ESTADO"], $activo, 1, array("title" => $textos["AYUDA_ACTIVO"],"onBlur" => "validarItem(this);"))
            )
        );

         /*** Definición de pestañas general ***/
        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 40, 255, "", array("title" => $textos["AYUDA_MUNICIPIOS"], "class" => "autocompletable")).HTML::campoOculto("id_municipio", "")
            ),
            array(
                HTML::campoTextoCorto("*direccion_residencia", $textos["DIRECCION_RESIDENCIA"], 40, 60, "", array("title" => $textos["AYUDA_DIRECCION"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*telefono_1", $textos["TELEFONO_1"], 15, 15, "", array("title" => $textos["AYUDA_TELEFONO_1"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("telefono_2", $textos["TELEFONO_2"], 15, 15, "", array("title" => $textos["AYUDA_TELEFONO_2"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("celular", $textos["CELULAR"], 15, 15, "", array("title" => $textos["AYUDA_CELULAR"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definición de pestañas tributaria ***/
        $formularios["PESTANA_CONTABLE"] = array(
            array(
                HTML::campoTextoCorto("codigo_empresa_consolida", $textos["EMPRESA_CONSOLIDA"], 3, 3, "", array("title" => $textos["AYUDA_EMPRESA_CONSOLIDA"], "onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("codigo_sucursal_consolida", $textos["ALMACEN_CONSOLIDA"], 5, 5, "", array("title" => $textos["AYUDA_ALMACEN_CONSOLIDA"], "onKeyPress" => "return campoEntero(event)")),
                HTML::listaSeleccionSimple("tipo_empresa", $textos["TIPO_EMPRESA"], $tipoEmpresa, "", array("title" => $textos["AYUDA_TIPO_EMPRESA"]))
            ),
            array(
                HTML::campoTextoCorto("orden", $textos["ORDEN"], 3, 3, "", array("title" => $textos["AYUDA_ORDEN"], "onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("fecha_cierre", $textos["FECHA_CIERRE"], 8, 8, "", array("class" => "selectorFecha"), array("title" => $textos["AYUDA_FECHA_CIERRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::marcaChequeo("maneja_kardex", $textos["MANEJA_KARDEX"])
            ),
            array(
                HTML::marcaChequeo("realiza_orden_compra", $textos["REALIZA_ORDEN_COMPRA"])
            ),
            array(
                HTML::marcaChequeo("inventarios_mercancia", $textos["MANEJA_INVENTARIOS_MERCANCIA"])
            ),
            array(
                HTML::marcaChequeo("cartera_clientes_mayoristas", $textos["CARTERA_CLIENTES_MAYORISTAS"])
            ),
            array(
                HTML::marcaChequeo("cartera_clientes_detallistas", $textos["CARTERA_CLIENTES_DETALLISTAS"])
            ),
            array(
                HTML::marcaChequeo("cuentas_pagar_proveedores", $textos["CUENTAS_PAGAR_PROVEEDORES"])
            ),
            array(
                HTML::marcaChequeo("nomina", $textos["NOMINA"])
            ),
            array(
                HTML::marcaChequeo("contabilidad", $textos["CONTABILIDAD"])
            )
        );

        /*** Definicion de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        $contenido = "";
        $error     = $textos["CREAR_EMPRESAS"];
    }

    /*** Enviar datos para la generacion del formulario al script que origino la peticion ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    $respuesta = "";

    /*** Validar codigo ***/
    if ($url_item == "codigo") {
        $existe = SQL::existeItem("sucursales", "codigo", $url_valor, "codigo != '0'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("sucursales", "nombre", $url_valor, "nombre !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE"];
        }
    }

    /*** Validar nombre corto ***/
    if ($url_item == "nombre_corto" && $url_valor) {
        $existe = SQL::existeItem("sucursales", "nombre_corto", $url_valor, "nombre_corto !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE_CORTO"];
        }
    }

    HTTP::enviarJSON($respuesta);

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos ***/
    if(empty($forma_codigo)){
        $error   = true;
        $mensaje = $textos["CODIGO_VACIO"];

    }elseif(empty($forma_id_empresa)){
        $error   = true;
        $mensaje = $textos["EMPRESA_VACIO"];

    }elseif(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];

    }elseif(empty($forma_id_municipio)){
        $error   = true;
        $mensaje = $textos["MUNICIPIO_VACIO"];

    }elseif(empty($forma_direccion_residencia)){
        $error   = true;
        $mensaje = $textos["DIRECCION_VACIO"];

    }elseif(empty($forma_telefono_1)){
        $error   = true;
        $mensaje = $textos["TELEFONO_VACIO"];

    }/*elseif(empty($forma_codigo_empresa_consolida)){
        $error   = true;
        $mensaje = $textos["CODIGO_EMPRESA_VACIO"];

    }elseif(empty($forma_codigo_sucursal_consolida)) {
        $error   = true;
        $mensaje = $textos["CODIGO_SUCURSAL_VACIO"];

    }*/elseif(SQL::existeItem("sucursales", "codigo", $forma_codigo,"codigo !=''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

     }elseif(SQL::existeItem("sucursales", "nombre", $forma_nombre,"nombre !=''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];

    }elseif(SQL::existeItem("sucursales", "nombre_corto", $forma_nombre_corto,"nombre_corto !=''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE_CORTO"];

    } else {

        /*** Validar campor vacios ***/
        if (!isset($forma_maneja_kardex)) {
            $forma_maneja_kardex = "0";
        }
        if (!isset($forma_codigo_empresa_consolida)) {
            $forma_codigo_empresa_consolida = "0";
        }
        if (!isset($forma_codigo_sucursal_consolida)) {
            $forma_codigo_sucursal_consolida = "0";
        }
        if(!isset($forma_realiza_orden_compra))
            $forma_realiza_orden_compra="0";

        if(!isset($forma_inventarios_mercancia))
            $forma_inventarios_mercancia="0";

        if(!isset($forma_cartera_clientes_mayoristas))
            $forma_cartera_clientes_mayoristas="0";

        if(!isset($forma_cartera_clientes_detallistas))
            $forma_cartera_clientes_detallistas="0";

        if(!isset($forma_cuentas_pagar_proveedores))
            $forma_cuentas_pagar_proveedores="0";

        if(!isset($forma_nomina))
            $forma_nomina="0";

        if(!isset($forma_contabilidad))
            $forma_contabilidad="0";


         $vistaConsulta = "seleccion_municipios";
         $columnas = SQL::obtenerColumnas($vistaConsulta);
         $consulta = SQL::seleccionar(array($vistaConsulta), $columnas, "llave_primaria= '$forma_id_municipio'");
         $datos_municipio = SQL::filaEnObjeto($consulta);

         $codigo_iso                 = $datos_municipio -> pais;
         $codigo_dane_departamento   = $datos_municipio -> departamento;
         $codigo_dane_municipio      = $datos_municipio -> codigo;


        /*** Insertar datos ***/
        $datos = array(
            "codigo"                       => $forma_codigo,
            "codigo_empresa"               => $forma_id_empresa,
            "nombre"                       => $forma_nombre,
            "nombre_corto"                 => $forma_nombre_corto,
            "fecha_cierre"                 => $forma_fecha_cierre,
            "activo"                       => $forma_activo,
            "codigo_iso"                   => $codigo_iso,
            "codigo_dane_departamento"     => $codigo_dane_departamento,
            "codigo_dane_municipio"        => $codigo_dane_municipio,
            "direccion_residencia"         => $forma_direccion_residencia,
            "telefono_1"                   => $forma_telefono_1,
            "telefono_2"                   => $forma_telefono_2,
            "celular"                      => $forma_celular,
            "codigo_empresa_consolida"     => $forma_codigo_empresa_consolida,
            "codigo_sucursal_consolida"    => $forma_codigo_sucursal_consolida,
            "tipo_empresa"                 => $forma_tipo_empresa,
            "orden"                        => $forma_orden,
            "maneja_kardex"                => $forma_maneja_kardex,
            "realiza_orden_compra"         => $forma_realiza_orden_compra,
            "inventarios_mercancia"        => $forma_inventarios_mercancia,
            "cartera_clientes_mayoristas"  => $forma_cartera_clientes_mayoristas,
            "cartera_clientes_detallistas" => $forma_cartera_clientes_detallistas,
            "cuentas_pagar_proveedores"    => $forma_cuentas_pagar_proveedores,
            "nomina"                       => $forma_nomina,
            "contabilidad"                 => $forma_contabilidad
        );
        $insertar = SQL::insertar("sucursales", $datos);

        /*** Error de insercion ***/
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
