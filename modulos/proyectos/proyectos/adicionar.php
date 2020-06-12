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
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    exit;

}elseif (!empty($url_recargar)) {

    if ($url_elemento == "empresa") {
       $respuesta = HTML::generarDatosLista("empresas", "codigo", "nombre", "codigo = '$url_origen'");
    }

    if ($url_elemento == "sucursal") {
       $respuesta = HTML::generarDatosLista("sucursales", "codigo", "nombre", "codigo_empresa = '".$url_codigo."' AND codigo !='0' AND tipo != '0'");
    }

    HTTP::enviarJSON($respuesta);
}

/*** Generar el formulario para la captura de datos ***/
elseif (!empty($url_generar)) {
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
        
        //Asignar codigo siguiente de la tabla 
        $codigo = SQL::obtenerValor("proyectos","MAX(codigo)","codigo>0");

        if ($codigo){
            $codigo++;
        } else {
            $codigo = 1;
        }
         /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 4, 4, $codigo, array("readonly" => "true"), array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItem(this);", "onKeyPress" => "return campoEntero(event)")),
            ),
            array(
                HTML::listaSeleccionSimple("*empresa", $textos["EMPRESA"], HTML::generarDatosLista("empresas", "codigo", "razon_social","codigo != 0"), "", array("title" => $textos["AYUDA_EMPRESAS"],"onChange" => "recargarLista('codigo_empresa','codigo_sucursal');recargarListaEmpresas();")),

                HTML::listaSeleccionSimple("*sucursal", $textos["CONSORCIO"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo != 0 AND tipo != '0'"), "", array("title" => $textos["AYUDA_CONSORCIO"],"onChange" => "recargarListaEmpresas();"))
            ),
            array(
                HTML::campoTextoCorto("*nombre", $textos["NOMBRE"], 40, 60, "", array("title" => $textos["AYUDA_NOMBRE"],"onBlur" => "validarItem(this);"))
            ),
            array(
                HTML::campoTextoCorto("*valor_proyecto", $textos["VALOR_PROYECTO"], 15, 15, "", array("title" => $textos["AYUDA_VALOR_PROYECTO"],"class" => "numero","onBlur" => "validarItem(this);","onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::listaSeleccionSimple("activo", $textos["ESTADO"], $activo, 1, array("title" => $textos["AYUDA_ACTIVO"],"onBlur" => "validarItem(this);"))
            ),
        );

         /*** Definición de pestañas general ***/
        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::campoTextoCorto("*selector1", $textos["MUNICIPIO"], 40, 255, "", array("title" => $textos["AYUDA_MUNICIPIOS"], "class" => "autocompletable")).HTML::campoOculto("id_municipio", "")
            ),
            array(
                HTML::campoTextoCorto("*direccion_proyecto", $textos["DIRECCION_RESIDENCIA"], 40, 60, "", array("title" => $textos["AYUDA_DIRECCION"],"onBlur" => "validarItem(this);"))
            )
        );

        /*** Definición de pestañas tributaria ***/
        /*$formularios["PESTANA_CONTABLE"] = array(
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
        );*/

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
        $existe = SQL::existeItem("proyectos", "codigo", $url_valor, "codigo != '0'");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_CODIGO"];
        }
    }    

    /*** Validar nombre ***/
    if ($url_item == "nombre") {
        $existe = SQL::existeItem("proyectos", "nombre", $url_valor, "nombre !=''");

        if ($existe) {
            $respuesta = $textos["ERROR_EXISTE_NOMBRE"];
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

    }elseif(empty($forma_empresa)){
        $error   = true;
        $mensaje = $textos["EMPRESA_VACIO"];

    }elseif(empty($forma_nombre)){
        $error   = true;
        $mensaje = $textos["NOMBRE_VACIO"];

    }elseif(empty($forma_id_municipio)){
        $error   = true;
        $mensaje = $textos["MUNICIPIO_VACIO"];

    }elseif(empty($forma_direccion_proyecto)){
        $error   = true;
        $mensaje = $textos["DIRECCION_VACIO"];

    }elseif(SQL::existeItem("proyectos", "codigo", $forma_codigo,"codigo !=''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO"];

    }elseif(SQL::existeItem("proyectos", "nombre", $forma_nombre,"nombre !=''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_NOMBRE"];

    } else {

        /*** Validar campor vacios ***/
        if (!isset($forma_empresa)) {
            $forma_empresa = "0";
        }
        if (!isset($forma_sucursal)) {
            $forma_sucursal = "0";
        }

         $vistaConsulta   = "seleccion_municipios";
         $columnas        = SQL::obtenerColumnas($vistaConsulta);
         $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "llave_primaria= '$forma_id_municipio'");
         $datos_municipio = SQL::filaEnObjeto($consulta);

         $codigo_iso                 = $datos_municipio -> pais;
         $codigo_dane_departamento   = $datos_municipio -> departamento;
         $codigo_dane_municipio      = $datos_municipio -> codigo;


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
        $insertar = SQL::insertar("proyectos", $datos);

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
