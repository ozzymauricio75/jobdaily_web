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
        $error            = "";
        $titulo           = $componente->nombre;

        $tercero          = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_tercero."'");
        $sucursal_genera  = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");
        $comprobante      = SQL::obtenerValor("tipos_comprobantes","descripcion","codigo = '".$datos->codigo_tipo_comprobante."'");
        $documento        = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$datos->codigo_tipo_documento."'");

        $consecutivo_documento = $datos->consecutivo_documento;

        $anulado = array('');

        if($datos->estado == '2'){
            $anulado = array(
                HTML::mostrarDato("estado",$textos["ESTADO"],$textos["ANULADO"])
            );
        }

        if($tercero == ''){
            $tercero = $textos["NO_APLICA"];
        }

        $lista_items = array();

        $vistaConsulta = "items_movimientos_contables";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_sucursal = '".$llave_movimiento[0]."' AND documento_identidad_tercero = '".$llave_movimiento[1]."' AND codigo_tipo_comprobante = '".$llave_movimiento[2]."' AND numero_comprobante = '".$llave_movimiento[3]."' AND codigo_tipo_documento = '".$llave_movimiento[4]."' AND consecutivo_documento = '".$llave_movimiento[5]."' AND fecha_contabilizacion = '".$llave_movimiento[6]."'");

        $lista_PDF       = "";
        $alto_celdas_PDF = "";

        if(SQL::filasDevueltas($consulta)){
            $consecutivo    = 0;
            $total_debitos  = 0;
            $total_creditos = 0;
            while($datos_items = SQL::filaEnObjeto($consulta)){
                $consecutivo++;
                $sucursal_contabiliza = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos_items->codigo_sucursal_contabiliza."'");
                $cuenta               = SQL::obtenerValor("seleccion_plan_contable_transacciones","SUBSTRING_INDEX(cuenta,'|',1)","id = '".$datos_items->codigo_plan_contable."'");
                $sentido_cuenta       = SQL::obtenerValor("plan_contable","naturaleza_cuenta","codigo_contable = '".$datos_items->codigo_plan_contable."'");
                $maneja_saldos        = SQL::obtenerValor("plan_contable","maneja_saldos","codigo_contable = '".$datos_items->codigo_plan_contable."'");
                $tercero_cuenta       = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos_items->documento_identidad_tercero_saldo."'");
                $documento_cruce      = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$datos_items->codigo_tipo_documento_soporte."'");
                $documento_bancario   = SQL::obtenerValor("tipos_documentos_bancarios","descripcion","codigo = '".$datos_items->codigo_tipo_documento_bancario."'");
                $opciones             = "";

                $opciones_PDF         = "";
                $celda_opciones_PDF   = 0;

                if($datos_items->codigo_tipo_documento_bancario!='0'){
                    $opciones          .= '<span class="etiqueta">'.$textos["DOCUMENTO_BANCARIO"].': </span>'.$documento_bancario.' '.$textos["NUMERO"].' '.$datos_items->numero_documento_bancario.'<br/>';
                    $opciones_PDF      .= $textos["DOCUMENTO_BANCARIO"].":\n".$documento_bancario." ".$textos["NUMERO"]." ".$datos_items->numero_documento_bancario."\n";
                    $celda_opciones_PDF = $celda_opciones_PDF + 6;
                }
                if((($datos_items->sentido=='D' && $sentido_cuenta=='D')||($datos_items->sentido=='C' && $sentido_cuenta=='C')) && $maneja_saldos=='1'){

                    $opciones          .= '<span class="etiqueta">'.$textos["TOTAL_SALDO"].': </span>$'.number_format($datos_items->valor).'<br/>';
                    $opciones_PDF      .= $textos["TOTAL_SALDO"].":\n".number_format($datos_items->valor)."\n";
                    $celda_opciones_PDF = $celda_opciones_PDF + 6;

                }elseif((($datos_items->sentido=='C' && $sentido_cuenta=='D')||($datos_items->sentido=='D' && $sentido_cuenta=='C')) && $maneja_saldos=='1'){

                    $opciones          .= '<span class="etiqueta">'.$textos["TIPO_DOCUMENTO_SOPORTE"].': </span>'.$documento_cruce.' '.$textos["NUMERO"].' '.$datos_items->numero_documento_soporte.'<br/>';
                    $opciones_PDF      .= $textos["TIPO_DOCUMENTO_SOPORTE"].":\n".$documento_cruce." ".$textos["NUMERO"]." ".$datos_items->numero_documento_soporte."\n";
                    $celda_opciones_PDF = $celda_opciones_PDF + 6;

                }

                $consulta_cheque = SQL::seleccionar(array("consecutivo_cheques"),array("*"),"codigo_sucursal = '".$datos_items->codigo_sucursal_cheque."' AND codigo_tipo_documento = '".$datos_items->codigo_tipo_documento_cheque."' AND codigo_banco = '".$datos_items->codigo_banco_cheque."' AND numero = '".$datos_items->numero_cheque."' AND consecutivo = '".$datos_items->consecutivo_cheque."'");

                if(SQL::filasDevueltas($consulta_cheque)){
                    $cheque         = SQL::filaEnObjeto($consulta_cheque);
                    $id_plan_cuenta = SQL::obtenerValor("cuentas_bancarias", "codigo_plan_contable", "codigo_sucursal = '".$cheque->codigo_sucursal."' AND codigo_tipo_documento = '".$cheque->codigo_tipo_documento."' AND codigo_sucursal_banco = '".$cheque->codigo_sucursal_banco."' AND codigo_iso = '".$cheque->codigo_iso_cuenta."' AND codigo_dane_departamento = '".$cheque->codigo_dane_departamento_cuenta."' AND codigo_dane_municipio = '".$cheque->codigo_dane_municipio_cuenta."' AND codigo_banco = '".$cheque->codigo_banco."' AND numero = '".$cheque->numero."'");
                    if($id_plan_cuenta==$datos_items->codigo_plan_contable){

                        $banco              = SQL::obtenerValor("bancos","descripcion","codigo = '".$cheque->codigo_banco."'");
                        $opciones          .= '<span class="etiqueta">'.$textos["CHEQUE"].': </span>'.$textos["BANCO"].': '.$banco.',<br/>'.$textos["NUMERO_CUENTA"].': '.$cheque->numero.',<br/>'.$textos["CONSECUTIVO"].': '.$cheque->consecutivo.'<br/>';
                        $opciones_PDF      .= $textos["CHEQUE"].":\n".$textos["BANCO"].": ".$banco.",\n".$textos["NUMERO_CUENTA"].": ".$cheque->numero.",\n".$textos["CONSECUTIVO"].": ".$cheque->consecutivo."\n";
                        $celda_opciones_PDF = $celda_opciones_PDF + 12;

                    }
                }
                if($datos_items->sentido=='D'){
                    $total_debitos += $datos_items->valor;
                    $debitos        = number_format($datos_items->valor);
                    $creditos       = '';
                }else{
                    $total_creditos += $datos_items->valor;
                    $debitos         = '';
                    $creditos        = number_format($datos_items->valor);
                }
                if($tercero==''){
                    $tercero = $textos["NO_APLICA"];
                }

                $lista_items[]=array(
                    $consecutivo,
                    $sucursal_contabiliza,
                    $cuenta,
                    $tercero_cuenta,
                    $opciones,
                    $debitos,
                    $creditos
                );

                $lista_PDF[]=array(
                    $sucursal_contabiliza,
                    substr($cuenta, 0, 26),
                    substr($tercero_cuenta, 0, 26),
                    $debitos,
                    $creditos,
                    $opciones_PDF
                );

                if ($celda_opciones_PDF == 0) {
                    $celda_opciones_PDF = 3;
                }
                $alto_celdas_PDF[] = $celda_opciones_PDF;
            }
            $consecutivo++;
            $lista_items[]  = array($consecutivo,
                                    '<span class="etiqueta" style="text-align:center">-</span>',
                                    '<span class="etiqueta" style="text-align:center">-</span>',
                                    '<span class="etiqueta" style="text-align:center">-</span>',
                                    '<span class="etiqueta" style="text-align:right">'.$textos["TOTALES"].':</span>',
                                    '<span class="dato">'.number_format($total_debitos).'</span>',
                                    '<span class="dato">'.number_format($total_creditos).'</span>'
                                    );
        }

        $imprimir = array("");

        $archivo_consecutivo = SQL::obtenerValor("consecutivo_documentos", "consecutivo_archivo", "codigo_sucursal='".$llave_movimiento[0]."' AND codigo_tipo_documento='".$llave_movimiento[4]."' AND fecha_registro='".$llave_movimiento[6]."' AND consecutivo='".$llave_movimiento[5]."'");
        $archivo_sucursal    = SQL::obtenerValor("consecutivo_documentos", "codigo_sucursal_archivo", "codigo_sucursal='".$llave_movimiento[0]."' AND codigo_tipo_documento='".$llave_movimiento[4]."' AND fecha_registro='".$llave_movimiento[6]."' AND consecutivo='".$llave_movimiento[5]."'");
        $nombre_arc          = SQL::obtenerValor("archivos", "nombre", "codigo_sucursal='".$archivo_sucursal."' AND consecutivo='".$archivo_consecutivo."'");
        $nombreArchivo       = $rutasGlobales["archivos"]."/".$nombre_arc;

        if(($archivo_consecutivo!='0') && is_file($nombreArchivo)){
            $id_archivo = $archivo_sucursal."|".$archivo_consecutivo;
        }else{

            $consecutivo_archivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$sesion_sucursal."'");
            if ($consecutivo_archivo){
                $consecutivo_archivo++;
            } else {
                $consecutivo_archivo = 1;
            }
            $consecutivo_archivo = (int)$consecutivo_archivo;
            do {
                $cadena         = Cadena::generarCadenaAleatoria(8);
                $nombre_arc     = $sesion_sucursal.$cadena.".pdf";
                $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre_arc;
            } while (is_file($nombreArchivo));

            $datos_archivo = array(
            "codigo_sucursal" => $sesion_sucursal,
            "consecutivo"     => $consecutivo_archivo,
            "nombre"          => $nombre_arc
            );
            SQL::insertar("archivos", $datos_archivo);

            $datos_cd = array(
                "codigo_sucursal_archivo" => $sesion_sucursal,
                "consecutivo_archivo"     => $consecutivo_archivo
            );

            SQL::modificar("consecutivo_documentos",$datos_cd,"codigo_sucursal='".$llave_movimiento[0]."' AND codigo_tipo_documento='".$llave_movimiento[4]."' AND fecha_registro='".$llave_movimiento[6]."' AND consecutivo='".$llave_movimiento[5]."'");

            $id_archivo = $sesion_sucursal."|".$consecutivo_archivo;
        }
        include("clases/imprimir.php");
        $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo;
        $imprimir     = HTML::boton("botonAceptar", $textos["IMPRIMIR_PDF"], "window.open('".$ruta_archivo."', '_blank');", "imprimir");

        $formularios["PESTANA_GENERAL"] = array(
            $anulado,
            array(
                HTML::mostrarDato("sucursal_genera",$textos["SUCURSAL_GENERA"],$sucursal_genera),
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

        $formularios["PESTANA_MOVIMIENTOS"] = array(
            array(
                HTML::generarTabla( array("id","SUCURSAL","CUENTA","TERCERO_CUENTA","OPCIONES","DEBITOS","CREDITOS"),
                $lista_items,array("I","I","I","I","C","C"),"listaItems",false,false)
            )
        );

        $botones = array($imprimir);
        $contenido = HTML::generarPestanas($formularios,$botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
