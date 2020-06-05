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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
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
        echo SQL::datosAutoCompletar("seleccion_plan_contable", $url_q);
    }
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_sucursales", $url_q);
    }
    exit;
}

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = "";


    $naturaleza_cuenta = array(
        "D" => $textos["DEBITO"],
        "C" => $textos["CREDITO"]
    );

    $clase_cuenta = array(
        "1" => $textos["CUENTA_MOVIMIENTO"],
        "2" => $textos["CUENTA_MAYOR"]
    );

    $tipo_cuenta = array(
        "1" => $textos["BALANCE"],
        "2" => $textos["GANANCIAS_Y_PERDIDAS"],
        "3" => $textos["CUENTA_ORDEN"]
    );

    $tipo_certificado = array(
        "1" => $textos["NO_APLICA"],
        "2" => $textos["RETENCION_FUENTE"],
        "3" => $textos["RETENCION_ICA"],
        "4" => $textos["RETENCION_IVA"]
    );

    $flujo_efectivo = array(
        "1" => $textos["NO_AFECTA_FLUJO"],
        "2" => $textos["CAJA"],
        "3" => $textos["BANCOS"]
    );

    /*** DefiniciÃ³n de pestaÃ±as general ***/
    $formularios["PESTANA_GENERAL"] = array(
        array(
            HTML::campoTextoCorto("*codigo_contable", $textos["CODIGO_CONTABLE"], 15, 15, "", array("title" => $textos["AYUDA_CODIGO_CONTABLE"],"onBlur" => "validarItem(this);"))
        ),
        array(
            HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 50, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"]))
            //, "onBlur" => "validarItem(this);"
        ),
        array(
            HTML::listaSeleccionSimple("*naturaleza_cuenta", $textos["NATURALEZA_CUENTA"], $naturaleza_cuenta,"",array("title" => $textos["AYUDA_NATURALEZA_CUENTA"])),
            HTML::listaSeleccionSimple("*clase_cuenta", $textos["CLASE_CUENTA"], $clase_cuenta, "1", array("title" => $textos["AYUDA_CLASE_CUENTA"],"onchange" => "activarPestana(this)")),
            HTML::listaSeleccionSimple("*tipo_cuenta", $textos["TIPO_CUENTA"], $tipo_cuenta,"",array("title" => $textos["AYUDA_TIPO_CUENTA"]))
        ),
    );

    /*** Definición pestaña cuenta padre ***/
    $formularios["PESTANA_CUENTA"] = array(
        array(
            HTML::marcaChequeo("cuenta_principal", $textos["CUENTA_PRINCIPAL"], "", false, array("title" => $textos["AYUDA_CUENTA_PRINCIPAL"], "onChange" => "bloquearArbolContable();"))
        ),
        array(
            HTML::arbolContable("arbolContable", "", "", "codigo_contable_padre", true)
        ),
    );

    /*** Definición pestaña para cuentas de movimiento ***/
    $formularios["PESTANA_MOVIMIENTO"] = array(
        array(
            HTML::marcaChequeo("maneja_tercero", $textos["MANEJA_TERCERO"]),
            HTML::marcaChequeo("maneja_saldos", $textos["MANEJA_SALDOS"]),
            HTML::marcaChequeo("maneja_subsistema", $textos["MANEJA_SUBSISTEMA"])
        ),
        array(
            HTML::listaSeleccionSimple("codigo_anexo_contable", $textos["ANEXO_CONTABLE"], HTML::generarDatosLista("anexos_contables", "codigo", "descripcion"),"",array("title" => $textos["AYUDA_ANEXO_CONTABLE"]))
        ),
        array(
            HTML::listaSeleccionSimple("codigo_tasa_aplicar_1", $textos["TASA_APLICAR_1"], HTML::generarDatosLista("tasas", "codigo", "descripcion"),"",array("title" => $textos["AYUDA_TASA_APLICAR_1"])),
            HTML::listaSeleccionSimple("codigo_tasa_aplicar_2", $textos["TASA_APLICAR_2"], HTML::generarDatosLista("tasas", "codigo", "descripcion"),"",array("title" => $textos["AYUDA_TASA_APLICAR_2"]))
        ),
        array(
            HTML::listaSeleccionSimple("*codigo_concepto_dian", $textos["CONCEPTO_DIAN"], HTML::generarDatosLista("conceptos_dian", "codigo", "descripcion"),"",array("title" => $textos["AYUDA_CONCEPTO_DIAN"]))
        ),
        array(
            HTML::listaSeleccionSimple("tipo_certificado", $textos["TIPO_CERTIFICADO"], $tipo_certificado,"",array("title" => $textos["AYUDA_TIPO_CERTIFICADO"]))
        ),
        array(
            HTML::marcaChequeo("causacion_automatica", $textos["CAUSACION_AUTOMATICA"])
        ),
        array(
            HTML::listaSeleccionSimple("flujo_efectivo", $textos["FLUJO_EFECTIVO"], $flujo_efectivo,"",array("title" => $textos["AYUDA_FLUJO_EFECTIVO"])),
            HTML::campoTextoCorto("selector1", $textos["CUENTA_CONSOLIDA"], 15, 15, "", array("title" => $textos["AYUDA_CUENTA_CONSOLIDA"], "class" => "autocompletable")).HTML::campoOculto("codigo_contable_consolida", ""),
            HTML::campoTextoCorto("selector2", $textos["SUCURSAL"], 15, 15, "", array("title" => $textos["AYUDA_SUCURSAL"], "class" => "autocompletable")).HTML::campoOculto("codigo_sucursal", "")
        ),
        array(
            HTML::listaSeleccionSimple("codigo_moneda_extranjera", $textos["MONEDA_EXTRANJERA"], HTML::generarDatosLista("tipos_moneda", "codigo", "nombre"),"",array("title" => $textos["AYUDA_MONEDA_EXTRANJERA"]))
        ),
        array(
            HTML::campoTextoCorto("equivalencia", $textos["EQUIVALENCIA"], 25, 25, "", array("title" => $textos["AYUDA_EQUIVALENCIA"],"onBlur" => "validarItem(this);"))
        )
    );

    /*** Definición de botones ***/
    $botones = array(
        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
    );

    $contenido = HTML::generarPestanas($formularios, $botones);

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

/*** Validar los datos provenientes del formulario ***/
} elseif (!empty($url_validar)) {

    if(Cadena::contieneUTF8($url_valor)){
        $url_valor = utf8_decode($url_valor);
    }

    /*** Validar codigo_cuenta ***/
    if ($url_item == "codigo_contable") {
        $existe = SQL::existeItem("plan_contable", "codigo_contable", $url_valor, "codigo_contable !=''");

        if ($existe) {
           HTTP::enviarJSON($textos["ERROR_EXISTE_CODIGO_CONTABLE"]);
        }
    }

    /*** Validar equivalencia ***/
    if ($url_item == "equivalencia") {
        $existe = SQL::existeItem("plan_contable", "equivalencia", $url_valor,"equivalencia != ''");

        if ($existe) {
            HTTP::enviarJSON($textos["ERROR_EXISTE_EQUIVALENCIA"]);
        }
    }

/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if (empty($forma_codigo_contable)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_VACIO"];

    } else if(empty($forma_descripcion)){
        $error   = true;
        $mensaje = $textos["ERROR_DESCRIPCION_VACIO"];

    }elseif($existe = SQL::existeItem("plan_contable", "codigo_contable", $forma_codigo_contable,"codigo_contable != ''")){
        $error   = true;
        $mensaje = $textos["ERROR_EXISTE_CODIGO_CONTABLE"];

    }else {

        if ($forma_clase_cuenta == "2"){

            $forma_codigo_anexo_contable     = "";
            $forma_codigo_concepto_dian      = "";
            $forma_tipo_certificado          = "1";
            $forma_flujo_efectivo            = "1";
            $forma_codigo_contable_consolida = "";
            $forma_codigo_sucursal           = "";
            $forma_codigo_moneda_extranjera  = "";
            $forma_maneja_tercero            = "0";
            $forma_maneja_saldos             = "0";
        }

        if(!isset($forma_maneja_tercero)){
            $forma_maneja_tercero = "0";
        }

        if(!isset($forma_maneja_saldos)){
            $forma_maneja_saldos = "0";
        }

        if(!isset($forma_maneja_subsistema)){
            $forma_maneja_subsistema = "0";
        }

        if(!isset($forma_causacion_automatica)){
            $forma_causacion_automatica = "0";
        }

        if(!isset($forma_codigo_contable_padre)){
            $forma_codigo_contable_padre = "";
        }

        if(empty($forma_codigo_contable_padre)){
            $forma_codigo_contable_padre = "";
        }

        if(isset($forma_cuenta_principal)){
            $forma_codigo_contable_padre = "";
        }

        $cuenta_mayor = substr($forma_codigo_contable,0,1);
        if (empty($forma_codigo_contable_padre)){

            $consulta = SQL::seleccionar(array("plan_contable"),array("*"),"codigo_contable='$cuenta_mayor' OR codigo_contable_padre='$cuenta_mayor'");
            if (SQL::filasDevueltas($consulta)){
                $dato_plan   = SQL::filaEnObjeto($consulta);
                $error   = true;
                $mensaje = $textos["ERROR_EXISTE_CUENTA_PADRE"]." ".$dato_plan->descripcion;
            }
        } else {
            $cuenta_padre = substr($forma_codigo_contable_padre,0,1);

            if ($cuenta_mayor != $cuenta_padre){

                $descripcion_mayor = SQL::obtenerValor("plan_contable","descripcion","codigo_contable='$cuenta_mayor'");
                $descripcion_padre = SQL::obtenerValor("plan_contable","descripcion","codigo_contable='$cuenta_padre'");

                $error   = true;
                $mensaje = $textos["ERROR_CUENTA_PADRE"]." ".$descripcion_mayor.$textos["ERROR_CUENTA_PADRE2"]." ".$descripcion_padre;
            } else {

                $cantidad_codigo = strlen($forma_codigo_contable);
                $cantidad_padre = strlen($forma_codigo_contable_padre);
                if ($cantidad_padre == $cantidad_codigo){

                    $error   = true;
                    $mensaje = $textos["CODIGO_MAYOR_PADRE"];
                } else {

                    for($i=$cantidad_codigo;$i!=0;$i--){
                        $codigo_padre = substr($forma_codigo_contable,0,$i-1);
                        $codigo_padre = SQL::obtenervalor("plan_contable","codigo_contable","codigo_contable='$codigo_padre'");

                        if ($codigo_padre && $codigo_padre==$forma_codigo_contable_padre){
                            break;
                        } else if($codigo_padre && $codigo_padre!=$forma_codigo_contable_padre){
                            $descripcion  = SQL::obtenervalor("plan_contable","descripcion","codigo_contable='$codigo_padre'");
                            $error   = true;
                            $mensaje =$textos["SELECCIONE_OTRO_PADRE"]." ".$codigo_padre." ".$descripcion;
                            break;
                        }

                    }
                }
            }
        }

        if (!$error){

            $datos = array(
                "codigo_contable"            => $forma_codigo_contable,
                "descripcion"                => $forma_descripcion,
                "codigo_contable_padre"      => $forma_codigo_contable_padre,
                "naturaleza_cuenta"          => $forma_naturaleza_cuenta,
                "clase_cuenta"               => $forma_clase_cuenta,
                "tipo_cuenta"                => $forma_tipo_cuenta,
                "maneja_tercero"             => $forma_maneja_tercero,
                "maneja_saldos"              => $forma_maneja_saldos,
                "maneja_subsistema"          => $forma_maneja_subsistema,
                "codigo_anexo_contable"      => $forma_codigo_anexo_contable,
                "codigo_tasa_aplicar_1"      => $forma_codigo_tasa_aplicar_1,
                "codigo_tasa_aplicar_2"      => $forma_codigo_tasa_aplicar_2,
                "codigo_concepto_dian"       => $forma_codigo_concepto_dian,
                "tipo_certificado"           => $forma_tipo_certificado,
                "causacion_automatica"       => $forma_causacion_automatica,
                "flujo_efectivo"             => $forma_flujo_efectivo,
                "codigo_contable_consolida"  => $forma_codigo_contable_consolida,
                "codigo_sucursal"            => $forma_codigo_sucursal,
                "codigo_moneda_extranjera"   => $forma_codigo_moneda_extranjera,
                "equivalencia"               => $forma_equivalencia
            );

            $insertar = SQL::insertar("plan_contable", $datos);

            /*** Error de inserción ***/
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
            }

        }

    }

    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
