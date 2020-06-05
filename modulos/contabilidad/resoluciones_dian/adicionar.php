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
if(isset($url_validarItemsllaves))
{
    if(($url_item=="codigo_sucursal" || $url_item=="numero") && !empty($url_valor))
    {

        $llave_primaria = explode("|", $url_valor);
        $llave_primaria = $llave_primaria[0]."|".$llave_primaria[1];

        $existe = SQL::existeItem("buscador_resoluciones_dian","id",$llave_primaria);
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_RESOLUCION"];
            HTTP::enviarJSON($mensaje);
        }
    }
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error  = "";
    $titulo = $componente->nombre;

    /*** Consultar los tipos de documentos ***/
    $consulta_documentos = SQL::seleccionar(array("tipos_documentos"), array("codigo","descripcion"),"codigo > 0");

    /*** Verificar si el usuario tiene privilegios en las sucursales autorizadas para despachar pedidos mayoristas ***/
    if (SQL::filasDevueltas($consulta_documentos)) {

        while ($datos_documento = SQL::filaEnObjeto($consulta_documentos)) {
            $tipos_documentos[$datos_documento->codigo] = $datos_documento->descripcion;
        }
    }


    /*** Consultar Sucursales ***/
    $consulta = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo!='0'");

    if (SQL::filasDevueltas($consulta)){

        if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
            while ($datos = SQL::filaEnObjeto($consulta)){
                $sucursales[$datos->codigo] = $datos->nombre;
            }
        } else {
            // Obtener lista de sucursales para seleccion
            $tablas     = array(
                "a" => "perfiles_usuario",
                "b" => "componentes_usuario",
                "c" => "sucursales"
            );
            $columnas = array(
                "codigo" => "c.codigo",
                "nombre" => "c.nombre"
            );
            $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
                        AND a.codigo_usuario = '".$sesion_codigo_usuario."'
                        AND b.id_componente = '".$componente->id."'";

            $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

            if (SQL::filasDevueltas($consulta)) {
                while ($datos = SQL::filaEnObjeto($consulta)) {
                    $sucursales[$datos->codigo] = $datos->nombre;
                }
            }
        }
    }

    if (isset($tipos_documentos) && isset($sucursales)){

        list($ano,$mes,$dia) = split("-",date("Y-m-d"));
        $fecha_dos_anos = mktime(0,0,0,$mes,$dia,$ano+2);
        $fecha_dos_anos = date("Y-m-d",$fecha_dos_anos);
        $campo_llave_primaria="codigo_sucursal|numero";

        $estado = array(
            "1" => $textos["AUTORIZADA"],
            "2" => $textos["HABILITADA"],
            "3" => $textos["TRAMITE"]
        );

        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"], $sucursales,"", array("title" => $textos["AYUDA_SUCURSAL"], "onchange" => "validarItemsllaves(this,'$campo_llave_primaria')")),
                HTML::campoTextoCorto("*numero", $textos["NUMERO"], 20, 20, "", array("title" => $textos["AYUDA_NUMERO"], "onKeyPress" => "return campoEntero(event)", "onblur" => "validarItemsllaves(this,'$campo_llave_primaria')")),
            ),
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_INICIA"].'  -  '.$textos["FECHA_TERMINA"], 18, 18, $fecha_inicial, array("title" => $textos["AYUDA_FECHAS"], "class" => "fechaRango"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO"], $tipos_documentos,"", array("title" => $textos["AYUDA_TIPO_DOCUMENTO"]))
            ),
            array(
                /*** insertar los datos en la tabla pance_conceptos_resoluciones_dian (factura papel, factura computador, factura pos y factura electronica) ***/
                HTML::listaSeleccionSimple("*codigo_concepto_resolucion_dian", $textos["CONCEPTO"], HTML::generarDatosLista("conceptos_resoluciones_dian","codigo","nombre","codigo != 0"),"", array("title" => $textos["AYUDA_CONCEPTO"]))
            ),
            array(
                HTML::campoTextoCorto("prefijo", $textos["PREFIJO"], 4, 4, "", array("title" => $textos["AYUDA_PREFIJO"])),
                HTML::campoTextoCorto("*factura_inicial", $textos["FACTURA_INICIAL"], 8, 8, "", array("title" => $textos["AYUDA_FACTURA_INICIAL"],"onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("*factura_final", $textos["FACTURA_FINAL"], 8, 8, "", array("title" => $textos["AYUDA_FACTURA_FINAL"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::listaSeleccionSimple("tipo_resolucion", $textos["TIPO_RESOLUCION"], $estado, "",array("title" => $textos["AYUDA_TIPO_RESOLUCION"])),
                HTML::campoTextoCorto("rango", $textos["RANGO"], 10, 10, "", array("title" => $textos["AYUDA_RANGO"]))
            )
        );

        /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

    } else {

        $error = $textos["NO_EXISTEN_DATOS"];

        if (!isset($sucursales)){
            $error .= $textos["CREAR_SUCURSAL"];
        }

        if (!isset($tipos_documento)){
            $error .= $textos["CREAR_TIPO_DOCUMENTO"];
        }

        $error     .= $textos["CREAR_SUCURSAL"];
        $contenido = "";
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
}  elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    /*** Validar el ingreso de los datos requeridos para el tercero ***/
    if(empty($forma_numero)){
		$error   = true;
		$mensaje = $textos["NUMERO_VACIO"];

	}elseif(empty($forma_codigo_sucursal)){
		$error   = true;
		$mensaje = $textos["SUCURSAL_VACIO"];

	}elseif(empty($forma_factura_inicial)){
		$error   = true;
		$mensaje = $textos["FACTURA_INICIAL_VACIO"];

	}elseif(empty($forma_factura_final)){
		$error   = true;
		$mensaje = $textos["FACTURA_FINAL_VACIO"];

    }elseif($existe = SQL::existeItem("resoluciones_dian", "numero", $forma_numero, "codigo_sucursal='$forma_codigo_sucursal'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_RESOLUCION"];

    }else{
        /*** Asumir por defecto que no hubo error ***/
        $error   = false;
        $mensaje = $textos["ITEM_ADICIONADO"];

        $fechas        = explode('-',$forma_fechas);
        $fecha_inicia  = trim($fechas[0]);
        $fecha_termina = trim($fechas[1]);

        $datos = array(
            "codigo_sucursal"                 => $forma_codigo_sucursal,
            "numero"                          => $forma_numero,
            "fecha_inicia"                    => $fecha_inicia,
            "fecha_termina"                   => $fecha_termina,
            "prefijo"                         => $forma_prefijo,
            "factura_inicial"                 => $forma_factura_inicial,
            "factura_final"                   => $forma_factura_final,
            "codigo_concepto_resolucion_dian" => $forma_codigo_concepto_resolucion_dian,
            "codigo_tipo_documento"           => $forma_codigo_tipo_documento,
            "rango"                           => $forma_rango,
            "tipo_resolucion"                 => $forma_tipo_resolucion,
            "estado"                          => "1"
        );

        $insertar = SQL::insertar("resoluciones_dian", $datos);

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
