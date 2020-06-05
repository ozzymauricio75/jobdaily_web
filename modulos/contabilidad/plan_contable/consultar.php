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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {

        $vista_plan    = "plan_contable";
        $columnas_plan = SQL::obtenerColumnas($vista_plan);
        $consulta_plan = SQL::seleccionar(array($vista_plan), $columnas_plan, "codigo_contable = '$url_id'");
        $datos_plan    = SQL::filaEnObjeto($consulta_plan);
        $error         = "";
        $titulo        = $componente->nombre;

        $naturaleza_cuenta = array(
            "D" => $textos["DEBITO"],
            "C" => $textos["CREDITO"]
        );

        $tipo_cuenta = array(
            "1" => $textos["BALANCE"],
            "2" => $textos["GANANCIAS_Y_PERDIDAS"],
            "3" => $textos["CUENTA_ORDEN"]
        );

        $clase_cuenta = array(
            "1" => $textos["CUENTA_MOVIMIENTO"],
            "2" => $textos["CUENTA_MAYOR"]
        );

        if($datos_plan->tipo_certificado == "1"){
            $tipo_certificado = $textos["NO_APLICA"];
        }
        elseif($datos_plan->tipo_certificado == "2"){
            $tipo_certificado = $textos["RETENCION_FUENTE"];
        }
        elseif($datos_plan->tipo_certificado == "3"){
            $tipo_certificado = $textos["RETENCION_ICA"];
        }
        elseif($datos_plan->tipo_certificado == "4"){
            $tipo_certificado = $textos["RETENCION_IVA"];
        }else{
            $tipo_certificado = "";
        }

        $flujo_efectivo = array(
            "1" => $textos["NO_AFECTA_FLUJO"],
            "2" => $textos["CAJA"],
            "3" => $textos["BANCOS"]
        );

        /*** Obtener valores ***/
        $cuenta_padre      = SQL::obtenerValor("seleccion_plan_contable", "codigo_contable", "id = '$datos_plan->codigo_contable_padre'");
        $cuenta_padre      = explode("|", $cuenta_padre);
        $cuenta_padre      = $cuenta_padre[0];
        $anexo_contable    = SQL::obtenerValor("anexos_contables", "descripcion", "codigo = '$datos_plan->codigo_anexo_contable'");
        $tasa_aplicar_1    = SQL::obtenerValor("tasas", "descripcion", "codigo = '$datos_plan->codigo_tasa_aplicar_1'");
        $tasa_aplicar_2    = SQL::obtenerValor("tasas", "descripcion", "codigo = '$datos_plan->codigo_tasa_aplicar_2'");
        $concepto_dian     = SQL::obtenerValor("conceptos_dian", "descripcion", "codigo = '$datos_plan->codigo_concepto_dian'");
        $cuenta_consolida  = SQL::obtenerValor("seleccion_plan_contable", "codigo_contable", "id = '$datos_plan->codigo_contable_consolida'");
        $cuenta_consolida  = explode("|", $cuenta_consolida);
        $cuenta_consolida  = $cuenta_consolida[0];
        $sucursal          = SQL::obtenerValor("sucursales", "nombre_corto", "codigo = '$datos_plan->codigo_sucursal'");
        $moneda_extranjera = SQL::obtenerValor("tipos_moneda", "nombre", "codigo = '$datos_plan->codigo_moneda_extranjera'");

        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo_contable", $textos["CODIGO_CONTABLE"], $datos_plan->codigo_contable)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos_plan->descripcion)
            ),
            array(
                HTML::mostrarDato("naturaleza_cuenta", $textos["NATURALEZA_CUENTA"], $naturaleza_cuenta[$datos_plan->naturaleza_cuenta]),
                HTML::mostrarDato("clase_cuenta", $textos["CLASE_CUENTA"], $clase_cuenta[$datos_plan->clase_cuenta]),
                HTML::mostrarDato("tipo_cuenta", $textos["TIPO_CUENTA"], $tipo_cuenta[$datos_plan->tipo_cuenta])
            )
        );

        /*** Definicion pestaña cuenta padre ***/
        $formularios["PESTANA_CUENTA"] = array(
            array(
                HTML::arbolContable("arbolContable", $datos_plan->codigo_contable_padre, $datos_plan->codigo_contable_padre)
            )
        );

        /*** Definición pestaña movimiento ***/
        if (($datos_plan->clase_cuenta) == 1) {

            $formularios["PESTANA_MOVIMIENTO"] = array(
                array(
                    HTML::mostrarDato("maneja_tercero", $textos["MANEJA_TERCERO"], $textos["SI_NO_".intval($datos_plan->maneja_tercero)]),
                    HTML::mostrarDato("maneja_saldos", $textos["MANEJA_SALDOS"], $textos["SI_NO_".intval($datos_plan->maneja_saldos)]),
                    HTML::mostrarDato("maneja_subsistema", $textos["MANEJA_SUBSISTEMA"], $textos["SI_NO_".intval($datos_plan->maneja_subsistema)])
                )
            );

            if ($anexo_contable !=""){

                $campos[] = array(HTML::mostrarDato("anexo_contable", $textos["ANEXO_CONTABLE"], $anexo_contable));
                $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$campos);
            }

            if ($datos_plan->codigo_tasa_aplicar_1 >0){

                $tasas[] = array(HTML::mostrarDato("tasa_aplicar_1", $textos["TASA_APLICAR_1"], $tasa_aplicar_1));
                $tasas[] = array(HTML::mostrarDato("tasa_aplicar_2", $textos["TASA_APLICAR_2"], $tasa_aplicar_2));

                $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$tasas);
            }

            if ($datos_plan->codigo_concepto_dian >0){

                $concepto[] = array(HTML::mostrarDato("concepto_dian", $textos["CONCEPTO_DIAN"], $concepto_dian));
                $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$concepto);
            }

            if ($tipo_certificado !=""){
                $tipo[] = array(HTML::mostrarDato("tipo_certificado", $textos["TIPO_CERTIFICADO"], $tipo_certificado));
                $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$tipo);
            };

            $causacion[] = array(
                HTML::mostrarDato("causacion_automatica", $textos["CAUSACION_AUTOMATICA"], $textos["SI_NO_".intval($datos_plan->causacion_automatica)]),
                HTML::mostrarDato("flujo_efectivo", $textos["FLUJO_EFECTIVO"], $flujo_efectivo[$datos_plan->flujo_efectivo])
            );
            $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$causacion);

            if ($datos_plan->codigo_contable_consolida >0){

                if ($datos_plan->codigo_sucursal >0){
                    $sucursal[] = array(
                        HTML::mostrarDato("codigo_contable_consolida", $textos["CUENTA_CONSOLIDA"], $cuenta_consolida),
                        HTML::mostrarDato("codigo_sucursal", $textos["SUCURSAL"], $sucursal)
                    );
                } else {
                    $sucursal[] = array(
                        HTML::mostrarDato("codigo_contable_consolida", $textos["CUENTA_CONSOLIDA"], $cuenta_consolida)
                    );
                }

                $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$sucursal);
            } else {
                if ($datos_plan->codigo_sucursal >0){
                    $sucursal[] = array(
                        HTML::mostrarDato("codigo_sucursal", $textos["SUCURSAL"], $sucursal)
                    );
                    $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$sucursal);
                }
            }

            if ($datos_plan->codigo_moneda_extranjera >0){

                $moneda[] =array(
                    HTML::mostrarDato("moneda_extranjera", $textos["MONEDA_EXTRANJERA"], $moneda_extranjera)
                );
                $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$moneda);
            }

            if ($datos_plan->equivalencia != ""){
                $arreglo[] = array(
                    HTML::mostrarDato("equivalecia", $textos["EQUIVALENCIA"], $datos_plan->equivalencia)
                );

                $formularios["PESTANA_MOVIMIENTO"] = array_merge($formularios["PESTANA_MOVIMIENTO"],$arreglo);
            }
        }

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generaci�n del formulario al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
