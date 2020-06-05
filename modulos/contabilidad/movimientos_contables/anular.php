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

// Generar el formulario para la captura de datos 
if (!empty($url_generar)) {

    // Verificar que se haya enviado el ID del elemento a consultar
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $llave_movimiento = explode('|',$url_id);
        $vistaConsulta    = "movimientos_contables";
        $columnas         = SQL::obtenerColumnas($vistaConsulta);
        $consulta         = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llave_movimiento[0]."' AND documento_identidad_tercero = '".$llave_movimiento[1]."' AND codigo_tipo_comprobante = '".$llave_movimiento[2]."' AND numero_comprobante = '".$llave_movimiento[3]."' AND codigo_tipo_documento = '".$llave_movimiento[4]."' AND consecutivo_documento = '".$llave_movimiento[5]."' AND fecha_contabilizacion = '".$llave_movimiento[6]."'");
        $datos            = SQL::filaEnObjeto($consulta);

        $vistaConsulta    = "items_movimientos_contables";
        $columnas         = SQL::obtenerColumnas($vistaConsulta);
        $consulta         = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llave_movimiento[0]."' AND documento_identidad_tercero = '".$llave_movimiento[1]."' AND codigo_tipo_comprobante = '".$llave_movimiento[2]."' AND numero_comprobante = '".$llave_movimiento[3]."' AND codigo_tipo_documento = '".$llave_movimiento[4]."' AND consecutivo_documento = '".$llave_movimiento[5]."' AND fecha_contabilizacion = '".$llave_movimiento[6]."'");
        $cont             = 0;

        $genera_cheque    = SQL::obtenerValor("tipos_documentos","genera_cheque","codigo = '".$datos->codigo_tipo_documento."'");
        $automatico       = SQL::obtenerValor("tipos_documentos","manejo_automatico","codigo = '".$datos->codigo_tipo_documento."'");

        $estado           = $datos->estado;

        if($estado=='2'){
            $error     = $textos["ERROR_ITEM_ANULADO"];
        }

        if($automatico=='2' && $estado=='1'){
            $error     = $textos["ERROR_ANULAR_AUTO"];
        }
        
        if($genera_cheque=='1' && $automatico!='2' && $estado=='1'){
            $error     = $textos["ERROR_ANULAR_CHEQUE"];
        }

        if(SQL::filasDevueltas($consulta) && $genera_cheque=='0' && $automatico!='2' && $estado=='1'){
            while($datos_item = SQL::filaEnObjeto($consulta)){
                $maneja_saldos      = SQL::obtenerValor("plan_contable","maneja_saldos","codigo_contable = '".$datos_item->codigo_plan_contable."'");
                if($maneja_saldos=='1'){
                    $cont++;
                }
            }
        }
        
        if($cont>0 && $genera_cheque=='0' && $automatico!='2' && $estado=='1'){
            $error = $textos["ERROR_ANULAR_CRUCE"];
        }

        if($cont>0 || $genera_cheque=='1' || $automatico=='2' || $estado=='2'){
            $titulo    = "";
            $contenido = "";
        }else{
            $error            = "";
            $titulo           = $componente->nombre;

            $tercero     = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_tercero."'");
            $sucursal    = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");
            $comprobante = SQL::obtenerValor("tipos_comprobantes","descripcion","codigo = '".$datos->codigo_tipo_comprobante."'");
            $documento   = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$datos->codigo_tipo_documento."'");

            $consecutivo_documento = $datos->consecutivo_documento;

            if($tercero==''){
                $tercero = $textos["NO_APLICA"];
            }

            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::mostrarDato("sucursal_genera",$textos["SUCURSAL_GENERA"],$sucursal),
                    HTML::mostrarDato("fecha_contabilizacion",$textos["FECHA_CONTABILIZACION"],$datos->fecha_contabilizacion)
                ),
                array(
                    HTML::mostrarDato("tercero",$textos["TERCERO"],$tercero),
                ),
                array(
                    HTML::mostrarDato("tipo_comprobante",$textos["TIPO_COMPROBANTE"],$comprobante),
                    HTML::mostrarDato("numero_comprobante",$textos["NUMERO_COMPROBANTE"],$datos->numero_comprobante)
                ),
                array(
                    HTML::mostrarDato("tipo_documento",$textos["TIPO_DOCUMENTO"],$documento),
                    HTML::mostrarDato("consecutivo_documento",$textos["CONSECUTIVO_DOCUMENTO"],$consecutivo_documento),
                    HTML::mostrarDato("fecha_documento",$textos["FECHA_DOCUMENTO"],$datos->fecha_documento)
                ),
                array(
                    HTML::mostrarDato("observaciones", $textos["OBSERVACIONES"], $datos->observaciones)
                )
            );

            $lista_items = array();

            $vistaConsulta = "items_movimientos_contables";
            $columnas      = SQL::obtenerColumnas($vistaConsulta);
            $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llave_movimiento[0]."' AND documento_identidad_tercero = '".$llave_movimiento[1]."' AND codigo_tipo_comprobante = '".$llave_movimiento[2]."' AND numero_comprobante = '".$llave_movimiento[3]."' AND codigo_tipo_documento = '".$llave_movimiento[4]."' AND consecutivo_documento = '".$llave_movimiento[5]."' AND fecha_contabilizacion = '".$llave_movimiento[6]."'");

            if(SQL::filasDevueltas($consulta)){
                $consecutivo    = 0;
                $total_debitos  = 0;
                $total_creditos = 0;
                while($datos = SQL::filaEnObjeto($consulta)){
                    $consecutivo++;
                    $sucursal_contabiliza = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal_contabiliza."'");
                    $cuenta               = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '".$datos->codigo_plan_contable."'");
                    $sentido_cuenta       = SQL::obtenerValor("plan_contable","naturaleza_cuenta","codigo_contable = '".$datos->codigo_plan_contable."'");
                    $maneja_saldos        = SQL::obtenerValor("plan_contable","maneja_saldos","codigo_contable = '".$datos->codigo_plan_contable."'");
                    $tercero              = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_tercero_saldo."'");
                    $documento_cruce      = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$datos->codigo_tipo_documento_soporte."'");
                    $documento_bancario   = SQL::obtenerValor("tipos_documentos_bancarios","descripcion","codigo = '".$datos->codigo_tipo_documento_bancario."'");
                    $opciones             = "";
                    if($datos->codigo_tipo_documento_bancario!='0'){
                        $opciones .= '<span class="etiqueta">Documento Bancario: </span>'.$documento_bancario.'<br/>';
                    }
                    if((($datos->sentido=='D' && $sentido_cuenta=='D')||($datos->sentido=='C' && $sentido_cuenta=='C')) && $maneja_saldos=='1'){
                    $opciones .= '<span class="etiqueta">Total Saldo: </span>$'.number_format($datos->valor).'<br/>';
                    }elseif((($datos->sentido=='C' && $sentido_cuenta=='D')||($datos->sentido=='D' && $sentido_cuenta=='C')) && $maneja_saldos=='1'){
                        $opciones .= '<span class="etiqueta">Abono a documento: </span>'.$documento.' No. '.$consecutivo_documento.'<br/>';
                    }

                    $consulta_cheque = SQL::seleccionar(array("consecutivo_cheques"),array("*"),"codigo_sucursal_item = '".$datos->codigo_sucursal."' AND documento_identidad_tercero = '".$datos->documento_identidad_tercero."' AND codigo_tipo_comprobante = '".$datos->codigo_tipo_comprobante."' AND numero_comprobante = '".$datos->numero_comprobante."' AND codigo_tipo_documento_item = '".$datos->codigo_tipo_documento."' AND consecutivo_documento = '".$datos->consecutivo_documento."' AND fecha_contabilizacion = '".$datos->fecha_contabilizacion."' AND consecutivo_item = '".$datos->consecutivo."'");
                    if(SQL::filasDevueltas($consulta_cheque)){
                        $cheque = SQL::filaEnObjeto($consulta_cheque);
                        $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$cheque->codigo_sucursal."' AND codigo_tipo_documento = '".$cheque->codigo_tipo_documento."' AND codigo_sucursal_banco = '".$cheque->codigo_sucursal_banco."' AND codigo_iso = '".$cheque->codigo_iso."' AND codigo_dane_departamento = '".$cheque->codigo_dane_departamento."' AND codigo_dane_municipio = '".$cheque->codigo_dane_municipio."' AND codigo_banco = '".$cheque->codigo_banco."' AND numero = '".$cheque->numero."'");
                        if($id_plan_cuenta==$datos->codigo_plan_contable){
                            $banco     = SQL::obtenerValor("bancos","descripcion","codigo = '".$cheque->codigo_banco."'");
                            $opciones .= '<span class="etiqueta">Cheque: </span>Banco: '.$banco.',<br/>No. Cuenta: '.$cheque->numero.',<br/>Consecutivo: '.$cheque->consecutivo.'<br/>';
                        }
                    }
                    if($datos->sentido=='D'){
                        $total_debitos += $datos->valor;
                        $debitos        = number_format($datos->valor);
                        $creditos       = '';
                    }else{
                        $total_creditos += $datos->valor;
                        $debitos         = '';
                        $creditos        = number_format($datos->valor);
                    }
                    if($tercero==''){
                        $tercero = $textos["NO_APLICA"];
                    }

                    $lista_items[]=array(
                        $consecutivo,
                        $sucursal_contabiliza,
                        $cuenta,
                        $tercero,
                        $opciones,
                        $documento_cruce,
                        $datos->numero_documento_soporte,
                        $debitos,
                        $creditos
                    );
                }
                $consecutivo++;
                $lista_items[]  = array($consecutivo,
                                        '<span class="etiqueta" style="text-align:center">-</span>',
                                        '<span class="etiqueta" style="text-align:center">-</span>',
                                        '<span class="etiqueta" style="text-align:center">-</span>',
                                        '<span class="etiqueta" style="text-align:center">-</span>',
                                        '<span class="etiqueta" style="text-align:center">-</span>',
                                        '<span class="etiqueta" style="text-align:right">'.$textos["TOTALES"].':</span>',
                                        '<span class="dato">'.number_format($total_debitos).'</span>',
                                        '<span class="dato">'.number_format($total_creditos).'</span>'
                                        );
            }

            $formularios["PESTANA_MOVIMIENTOS"] = array(
                array(
                    HTML::generarTabla( array("id","SUCURSAL","CUENTA","TERCERO_CUENTA","OPCIONES","TIPO_DOCUMENTO_CRUCE","NUMERO_DOCUMENTO_CRUCE","DEBITOS","CREDITOS"),
                    $lista_items,array("I","I","I","I","I","C","C","C"),"listaItems",false,false)
                )
            );

            $botones = array(
                HTML::boton("botonAnular", $textos["ANULAR"], "modificarItem('$url_id');", "anular")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
} elseif (!empty($forma_procesar)) {

    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ANULADO"];

    $datos = array (
        "estado" => "2"
    );

    $llave_movimiento = explode('|',$forma_id);
    $modificar = SQL::modificar("movimientos_contables", $datos, "codigo_sucursal = '".$llave_movimiento[0]."' AND documento_identidad_tercero = '".$llave_movimiento[1]."' AND codigo_tipo_comprobante = '".$llave_movimiento[2]."' AND numero_comprobante = '".$llave_movimiento[3]."' AND codigo_tipo_documento = '".$llave_movimiento[4]."' AND consecutivo_documento = '".$llave_movimiento[5]."' AND fecha_contabilizacion = '".$llave_movimiento[6]."'");
    /*** Error de modificación ***/
    if (!$modificar) {
        $error   = true;
        $mensaje = $textos["ERROR_ANULAR_ITEM"];
    }

    /*** Enviar datos con la respuesta del proceso al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
