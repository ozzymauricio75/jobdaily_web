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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a modificar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta   = "resoluciones_dian";
        $llave           = explode("|",$url_id);
        $codigo_sucursal = $llave[0];
        $numero          = $llave[1];
        $columnas        = SQL::obtenerColumnas($vistaConsulta);
        $consulta        = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '$codigo_sucursal' AND numero='$numero'");
        $datos           = SQL::filaEnObjeto($consulta);
        $error           = "";
        $titulo          = $componente->nombre;

        $tipo_resolucion = array(
            "1" => $textos["AUTORIZADA"],
            "2" => $textos["HABILITADA"],
            "3" => $textos["TRAMITE"],
        );

        $estado = array(
            "0" => $textos["INACTIVA"],
            "1" => $textos["ACTIVA"]
        );

        $campo_llave_primaria="codigo_sucursal|numero";

        /*** Consultar los tipos de documentos ***/
        $consulta_documentos = SQL::seleccionar(array("tipos_documentos"), array("codigo","descripcion"),"codigo > 0");

        /*** Verificar si el usuario tiene privilegios en las sucursales autorizadas para despachar pedidos mayoristas ***/
        if (SQL::filasDevueltas($consulta_documentos)) {

            while ($datos_documento = SQL::filaEnObjeto($consulta_documentos)) {
                $tipos_documento[$datos_documento->codigo] = $datos_documento->descripcion;
            }
        }

        /*** Consultar Sucursales ***/
        $consulta = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo!='0'");

        if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
            while ($datos_usuario = SQL::filaEnObjeto($consulta)){
                $sucursales[$datos_usuario->codigo] = $datos_usuario->nombre;
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
                while ($datos_usuario = SQL::filaEnObjeto($consulta)) {
                    $sucursales[$datos_usuario->codigo] = $datos_usuario->nombre;
                }
            }
        }
        $fecha_inicia  = str_replace("-","/",$datos->fecha_inicia);
        $fecha_termina = str_replace("-","/",$datos->fecha_termina);
        $fecha_inicial = $fecha_inicia." - ".$fecha_termina;

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL"], $sucursales, $datos->codigo_sucursal, array("title" => $textos["AYUDA_SUCURSAL"], "onchange" => "validarItemsllaves(this,'$campo_llave_primaria')")),
                HTML::campoTextoCorto("*numero", $textos["NUMERO"], 20, 20, $datos->numero, array("title" => $textos["AYUDA_NUMERO"], "onblur" => "validarItemsllaves(this,'$campo_llave_primaria')"))
            ),
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_INICIA"].'  -  '.$textos["FECHA_TERMINA"], 18, 18, $fecha_inicial, array("title" => $textos["AYUDA_FECHAS"], "class" => "fechaRango"))
            ),
            array(
                HTML::listaSeleccionSimple("*codigo_tipo_documento", $textos["TIPO_DOCUMENTO"], $tipos_documento, $datos->codigo_tipo_documento, array("title" => $textos["AYUDA_TIPO_DOCUMENTO"]))
            ),
            array(
                /*** insertar los datos en la tabla conceptos resoluciones dian (factura papel, factura computador,factura pos y factura electronica) ***/
                HTML::listaSeleccionSimple("*codigo_concepto_resolucion_dian", $textos["CONCEPTO"], HTML::generarDatosLista("conceptos_resoluciones_dian","codigo","nombre","codigo != 0"),$datos->codigo_concepto_resolucion_dian, array("title" => $textos["AYUDA_CONCEPTO"]))
            ),
            array(
                HTML::campoTextoCorto("prefijo", $textos["PREFIJO"], 4, 4, $datos->prefijo, array("title" => $textos["AYUDA_PREFIJO"])),
                HTML::campoTextoCorto("*factura_inicial", $textos["FACTURA_INICIAL"], 8, 8, $datos->factura_inicial, array("title" => $textos["AYUDA_FACTURA_INICIAL"],"onKeyPress" => "return campoEntero(event)")),
                HTML::campoTextoCorto("*factura_final", $textos["FACTURA_FINAL"], 8, 8, $datos->factura_final, array("title" => $textos["AYUDA_FACTURA_FINAL"],"onKeyPress" => "return campoEntero(event)"))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_resolucion", $textos["TIPO_RESOLUCION"], $tipo_resolucion, $datos->tipo_resolucion, array("title" => $textos["AYUDA_TIPO_RESOLUCION"])),
                HTML::campoTextoCorto("rango", $textos["RANGO"], 10, 10, $datos->rango, array("title" => $textos["AYUDA_RANGO"]))
            ),
            array(
                HTML::listaSeleccionSimple("*estado", $textos["ESTADO"], $estado, $datos->estado, array("title" => $textos["AYUDA_ESTADO"]))
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

     /*** Validar numero de resolución ***/
    if ($url_item == "numero") {
        $existe = SQL::existeItem("resoluciones_dian", "numero", $url_valor,"numero !='' AND id != '$url_id' ");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_NUMERO"]);
        }
    }
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

    }elseif($existe = SQL::existeItem("resoluciones_dian", "numero", $forma_numero, "codigo_sucursal != '$forma_codigo_sucursal'")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_RESOLUCION"];

    }else{
        /*** Asumir por defecto que no hubo error ***/
        $error   = false;
        $mensaje = $textos["ITEM_MODIFICADO"];

        $llave           = explode("|",$forma_id);
        $codigo_sucursal = $llave[0];
        $numero          = $llave[1];

        $fechas        = explode('-',$forma_fechas);
        $fecha_inicia  = trim($fechas[0]);
        $fecha_termina = trim($fechas[1]);

        $datos = array(
            "codigo_sucursal"                 => $forma_codigo_sucursal,
            "numero"                          => $forma_numero,
            "codigo_concepto_resolucion_dian" => $forma_codigo_concepto_resolucion_dian,
            "codigo_tipo_documento"           => $forma_codigo_tipo_documento,
            "fecha_inicia"                    => $fecha_inicia,
            "fecha_termina"                   => $fecha_termina,
            "prefijo"                         => $forma_prefijo,
            "factura_inicial"                 => $forma_factura_inicial,
            "factura_final"                   => $forma_factura_final,
            "rango"                           => $forma_rango,
            "tipo_resolucion"                 => $forma_tipo_resolucion,
            "estado"                          => $forma_estado
        );

        $consulta = SQL::modificar("resoluciones_dian", $datos, "codigo_sucursal='$forma_codigo_sucursal' AND numero='$numero'");

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
