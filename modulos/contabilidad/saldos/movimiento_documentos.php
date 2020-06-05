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
* modificarlo  bajo los terminos de la Licencia Publica General GNU
* publicada por la Fundacion para el Software Libre, ya sea la version 3
* de la Licencia, o (a su eleccion) cualquier version posterior.
*
* Este programa se distribuye con la esperanza de que sea util, pero
* SIN GARANTIA ALGUNA; ni siquiera la garantia implicita MERCANTIL o
* de APTITUD PARA UN PROPOSITO DETERMINADO. Consulte los detalles de
* la Licencia Publica General GNU para obtener una informacion mas
* detallada.
*
* Deberia haber recibido una copia de la Licencia Publica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

include("clases/saldos.php");

// Devolver datos para autocompletar la busqueda
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_terceros", $url_q);
    }
    if(($url_item == "selector2") || ($url_item == "selector3")){
        echo SQL::datosAutoCompletar("seleccion_plan_contable_saldos_terceros", $url_q);
    }
    exit;
}

// Generar el formulario para la captura de datos
else if (!empty($url_generar)) {

    $consulta       = SQL::seleccionar(array("sucursales"), array("*"), "codigo != '0'");
    $sucursales_ver = SQL::filasDevueltas($consulta);

    $consulta       = SQL::seleccionar(array("plan_contable"), array("*"), "codigo_contable != '' AND maneja_saldos = '1' AND maneja_tercero = '1'");
    $plan_ver       = SQL::filasDevueltas($consulta);

    $consulta       = SQL::seleccionar(array("tipos_documentos"), array("*"), "codigo != '0'");
    $documentos_ver = SQL::filasDevueltas($consulta);

    if($sucursales_ver==0 || $plan_ver==0 || $documentos_ver==0){

        $mensaje=$textos["ERROR_TABLAS_VACIAS"];
        $listaTablas = array();

        if($sucursales_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_SUCURSALES"];
        }

        if($plan_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_PLAN_CONTABLE"];
        }

        if($documentos_ver==0){
            $listaTablas[] = $textos["ERROR_TABLA_VACIA_TIPOS_DOCUMENTOS"];
        }

        $tablas = implode("",$listaTablas);
        $mensaje.=$tablas;
        $error     = $mensaje;
        $titulo    = "";
        $contenido = "";

    }else{

        $error  = "";
        $titulo = $componente->nombre;

        $nivelDetalle = array(
            "1" => $textos['CUENTA_MAYOR'],
            "2" => $textos['GRUPO'],
            "3" => $textos['SUBGRUPO'],
            "4" => $textos['CUENTA'],
            "5" => $textos['CUENTA_AUXILIAR']
        );

        $consulta = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo!='0'");

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

        $pestana_sucursales   = array();
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));

        foreach($sucursales AS $llave => $valor){

            $idSucursal = $llave;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[".$llave."]", $valor, $llave, false, array("title" => $textos["AYUDA_SUCURSAL_PRINCIPAL"], "id" => "sucursales_".$llave, "class" => "sucursales_electrodomesticos")),
                HTML::listaSeleccionSimple("consolidar[".$llave."]", $textos["CONSOLIDAR_EN"], HTML::generarDatosLista("sucursales", "codigo", "nombre","codigo!='0'"), $llave, array("title" => $textos["AYUDA_SUCURSAL_CONSOLIDA"]))
            );
        }

        // Definicion de pestana de sucursales
        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        // Seleccionar los tipos de documento
        $consulta_documentos = SQL::seleccionar(array("tipos_documentos"),array("codigo", "descripcion"),"codigo > 0");
        $tipos_documentos = array();
        if(SQL::filasDevueltas($consulta_documentos)) {
            while ($datos_documentos = SQL::filaEnObjeto($consulta_documentos)) {
                $tipos_documentos[$datos_documentos->codigo] = $datos_documentos->descripcion;
            }
        }

        // Definicion de pestana general
        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_DESDE"].'  -  '.$textos["FECHA_HASTA"], 25, 25,$fecha_inicial, array("title" => $textos["FECHAS"], "class" => "fechaRango"))
                .HTML::campoOculto("error_cuentas",$textos["ERROR_CUENTAS"])
                .HTML::campoOculto("error_numeros",$textos["ERROR_NUMEROS"])
            ),
            array(
                HTML::campoTextoCorto("*selector2", $textos["CUENTA_DESDE"], 50, 50, "", array("title" => $textos["AYUDA_CUENTA_DESDE"],"class" => "autocompletable"))
                .HTML::campoOculto("cuenta_desde","")
            ),
            array(
                HTML::campoTextoCorto("*selector3", $textos["CUENTA_HASTA"], 50, 50, "", array("title" => $textos["AYUDA_CUENTA_HASTA"],"class" => "autocompletable"))
                .HTML::campoOculto("cuenta_hasta","")
            ),
             array(
                HTML::marcaChequeo("todos_documentos",$textos["SELECCIONAR_TODOS_DOCUMENTOS"],1 , false, array("title" => $textos["AYUDA_SELECCIONAR_TODOS_DOCUMENTOS"],"id" => "todos_documentos","onChange" => "todosDocumentos();")),
                HTML::marcaChequeo("observaciones",$textos["OBSERVACIONES"],1 , false, array("title" => $textos["AYUDA_OBSERVACIONES"]))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_documento", $textos["DOCUMENTO_DESDE"], $tipos_documentos, "", array("title" => $textos["AYUDA_DOCUMENTO_DESDE"])),
                HTML::marcaChequeo("todos_consecutivos",$textos["SELECCIONAR_TODOS_CONSECUTIVOS"],1 , false, array("title" => $textos["AYUDA_SELECCIONAR_TODOS_CONSECUTIVOS"],"id" => "todos_consecutivos","onChange" => "todosConsecutivos();"))
            ),
            array(
                HTML::campoTextoCorto("*numero_desde", $textos["NUMERO_DESDE"], 10, 10, "", array("title" => $textos["AYUDA_NUMERO_DESDE"],"onKeyPress" => "return campoEntero(event)","onKeyUp" => "validarNumeros();")),
                HTML::campoTextoCorto("*numero_hasta", $textos["NUMERO_HASTA"], 10, 10, "", array("title" => $textos["AYUDA_NUMERO_HASTA"],"onKeyPress" => "return campoEntero(event)","onKeyUp" => "validarNumeros();"))
            ),
            array(
                HTML::marcaChequeo("todos_terceros",$textos["SELECCIONAR_TODOS_TERCEROS"],1 , false, array("title" => $textos["AYUDA_SELECCIONAR_TODOS"],"id" => "todos_terceros","onChange" => "todosTercero();"))
            ),
            array(
                HTML::campoTextoCorto("*selector1", $textos["TERCERO"], 50, 50, "", array("title" => $textos["AYUDA_NIT_DESDE"],"class" => "autocompletable"))
                .HTML::campoOculto("id_tercero","")
            )
        );

        $botones = array(HTML::boton("boton_balance_general", $textos["GENERAR"], "imprimirItem('1');", "aceptar"));

        $contenido = HTML::generarPestanas($formularios,$botones);
    }

    // Enviar datos para la generacion del formulario al script que origina la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

}

//Procesar los datos para generar el pdf
else if(isset($forma_procesar)){

    $ruta_archivo = "";
    $error        = false;
    $mensaje      = $textos["ARCHIVO_GENERADO"];

    $fechas            = explode('-',$forma_fechas);
    $forma_fecha_desde = trim($fechas[0]);
    $forma_fecha_hasta = trim($fechas[1]);

    //Validar los datos del Formulario
    if(!isset($forma_sucursales)){
        $error = true;
        $mensaje = $textos["SUCURSAL_VACIO"];
    }else if((!isset($forma_todos_consecutivos) && !isset($forma_todos_documentos)) && (empty($forma_numero_desde) || empty($forma_numero_hasta))){
        $error = true;
        $mensaje = $textos["NUMEROS_VACIO"];
    }else if(!isset($forma_todos_terceros) && empty($forma_id_tercero)){
        $error = true;
        $mensaje = $textos["TERCERO_VACIO"];
    }else if(empty($forma_cuenta_desde)){
        $error    = true;
        $mensaje  = $textos["CUENTA_DESDE_VACIO"];
    }else if(empty($forma_cuenta_hasta)){
        $error    = true;
        $mensaje  = $textos["CUENTA_HASTA_VACIO"];
    }else{
        // Seleccionar las cuentas del plan contable que manejan saldos por documento
        $consulta_cuentas = SQL::seleccionar(array("plan_contable"),array("codigo_contable"),"codigo_contable BETWEEN '".$forma_cuenta_desde."' AND '".$forma_cuenta_hasta."'");
        $cuentas_saldo = array();
        if(SQL::filasDevueltas($consulta_cuentas)){
            while($datos_cuenta = SQL::filaEnObjeto($consulta_cuentas)){
                $cuentas_saldo[] = "'".$datos_cuenta->codigo_contable."'";
            }
        }

        $lista_cuentas = implode(",",$cuentas_saldo);

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $fechaReporte = date("Y-m-d");
        $archivo      = new PDF("L","mm","Letter");
        $archivo->textoPiePagina = "";
        $archivo->textoTitulo    = $textos["REPOSMDO"];
        $archivo->textoCabecera  = $textos["FECHA"].": ".$fechaReporte;

        $id_tabla = SQL::obtenerValor("tablas","id","nombre_tabla = 'movimientos_contables'");

        $sucursales_reporte = array();
        foreach ($forma_sucursales AS $sucursal) {
            if (!isset($sucursales_reporte[$forma_consolidar[$sucursal]])){
                $sucursales_reporte[$forma_consolidar[$sucursal]] = "'".$sucursal."',";
            } else {
                $sucursales_reporte[$forma_consolidar[$sucursal]] .= "'".$sucursal."',";
            }
        }

        $contador_saldos = 0;

        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            $lista_Sucursales   = trim($condicion_sucursal,",");
            $lista_consolidadas = explode(",", $lista_Sucursales);

            $condicion = "";

            if(!isset($forma_todos_terceros)){
                $condicion .= " AND id_tercero = '".$forma_id_tercero."'";
            }
            if(!isset($forma_todos_documentos)){
                $condicion .= " AND id_tipo_documento = '".$forma_tipo_documento."'";
            }
            if(!isset($forma_todos_consecutivos) && !isset($forma_todos_documentos)){
                $condicion .= " AND (CONSECUTIVO_DOCUMENTO BETWEEN '".$forma_numero_desde."' AND '".$forma_numero_hasta."')";
            }
            $primerPagina = true;
            $consulta_movimientos = SQL::seleccionar(array("menu_movimientos_contables"),array("*"),"(FECHA BETWEEN '".$forma_fecha_desde."' AND '".$forma_fecha_hasta."') AND id_sucursal IN(".$lista_Sucursales.")".$condicion);
            while($movimiento = SQL::filaEnObjeto($consulta_movimientos)){
                $consulta_items = SQL::seleccionar(array("seleccion_items_movimientos_contables"),array("*"),"id_movimiento = '".$movimiento->id."' AND codigo_plan_contable IN (".$lista_cuentas.")");
                while($item_movimiento = SQL::filaEnObjeto($consulta_items)){
                    $consulta_saldos = SQL::seleccionar(array("buscador_saldos_movimientos_contables"),array("*","SUM(saldo) AS total_saldo"),"id_item_movimiento = '".$item_movimiento->id."'","id_item_movimiento");
                    while($saldos_item_movimiento = SQL::filaEnObjeto($consulta_saldos)){
                        if($primerPagina){
                            imprimirCabeceraSMD($textos,$archivo,$forma_fecha_desde,$forma_fecha_hasta,$lista_consolidadas,$sucursal,$forma_cuenta_desde,$forma_cuenta_hasta);
                            $primerPagina = false;
                        }
                        if($archivo->breakCell(25)){
                            imprimirCabeceraSMD($textos,$archivo,$forma_fecha_desde,$forma_fecha_hasta,$lista_consolidadas,$sucursal,$forma_cuenta_desde,$forma_cuenta_hasta);
                        }
                        $archivo->SetFont('Arial','B',8);
                        $archivo->Cell(30,4,$textos["DETALLE"].":",0,0,'L');
                        $archivo->SetFont('Arial','',8);
                        $documento          = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$movimiento->id_tipo_documento."'");
                        $documento         .= "-".$movimiento->CONSECUTIVO_DOCUMENTO;
                        $tercero_movimiento = SQL::obtenerValor("seleccion_terceros", "SUBSTRING_INDEX(nombre,'|',1)", "id = '".$movimiento->id_tercero."'");
                        $cuenta_movimiento = $item_movimiento->codigo_plan_contable."-".SQL::obtenerValor("plan_contable","descripcion","codigo_contable = '".$item_movimiento->codigo_plan_contable."'");
                        $detalle_saldo = $cuenta_movimiento." / ".$tercero_movimiento." / ".$documento." / ".$movimiento->FECHA;
                        $archivo->Cell(100,4,$detalle_saldo,0,0,'L');
                        $archivo->Ln(4);
                        $archivo->SetFont('Arial','B',8);
                        $archivo->Cell(30,4,$textos["SALDO_INICIAL"].":",0,0,'L');
                        $archivo->SetFont('Arial','',8);
                        $archivo->Cell(50,4,"$".number_format($saldos_item_movimiento->total_saldo,0),0,0,'L');
                        $archivo->Ln(4);
                        if(isset($forma_observaciones) && !empty($movimiento->id_observaciones)){
                            $archivo->SetFont('Arial','B',8);
                            $archivo->Cell(30,6,$textos["OBSERVACIONES"].":",0,0,'L');
                            $archivo->SetFont('Arial','',8);
                            $archivo->Cell(180,6,$movimiento->id_observaciones,0,0,'L',false,"",true);
                            $archivo->Ln(6);
                        }
                        $contador_saldos++;
                    }
                    $cabecera = true;
                    $total_abono = 0;
                    $total_saldo = 0;
                    $consulta_saldos = SQL::seleccionar(array("buscador_saldos_movimientos_contables"),array("*"),"id_item_movimiento = '".$item_movimiento->id."'");
                    if(SQL::filasDevueltas($consulta_saldos)){
                        while($saldos_item_movimiento = SQL::filaEnObjeto($consulta_saldos)){
                            $consulta_abonos = SQL::seleccionar(array("buscador_abonos_movimientos_contables"),array("*"),"id_saldo = '".$saldos_item_movimiento->id_saldo."' AND fecha_pago <= '".$forma_fecha_hasta."'");
                            if(SQL::filasDevueltas($consulta_abonos)){
                                if($cabecera){
                                    $archivo->Ln(5);
                                    $archivo->SetFont('Arial','B',8);
                                    $archivo->SetFillColor(230,230,230);
                                    $archivo->Cell(70,5,$textos["DOCUMENTO_ABONO"],1,0,'C',true);
                                    $archivo->Cell(30,5,$textos["FECHA"],1,0,'C',true);
                                    $archivo->Cell(40,5,$textos["VALOR_ABONO"],1,0,'C',true);
                                    $archivo->Ln(5);
                                    $cabecera = false;
                                }
                                while($abonos_item_movimiento = SQL::filaEnObjeto($consulta_abonos)){
                                    $archivo->SetFont('Arial','',8);
                                    $archivo->SetFillColor(230,230,230);
                                    $documento = SQL::obtenerValor("tipos_documentos","descripcion","codigo = '".$abonos_item_movimiento->codigo_tipo_documento."'");
                                    $documento .= "-".$abonos_item_movimiento->consecutivo_documento;
                                    $archivo->Cell(70,4,$documento,1,0,'C',false);
                                    $archivo->Cell(30,4,$abonos_item_movimiento->fecha_pago,1,0,'C',false);
                                    $archivo->Cell(40,4,"$".number_format($abonos_item_movimiento->valor),1,0,'C',false);
                                    $archivo->Ln(4);
                                    $total_abono += $abonos_item_movimiento->valor;
                                }

                            }
                            $total_saldo += $saldos_item_movimiento->saldo;
                        }
                        if($total_abono > 0){
                            $archivo->SetFont('Arial','B',8);
                            $archivo->Cell(100,4,$textos["TOTAl_ABONO"]." ".$textos["HASTA_FECHA"].":",1,0,'R',false);
                            $archivo->Cell(40,4,"$".number_format($total_abono),1,0,'C',false);
                            $archivo->Ln(4);
                        }
                        $archivo->Ln(3);
                        $archivo->SetFont('Arial','B',8);
                        $archivo->Cell(40,4,$textos["SALDO_TOTAL"]." ".$textos["HASTA_FECHA"].":",0,0,'L');
                        $archivo->SetFont('Arial','',8);
                        $archivo->Cell(40,4,"$".number_format($total_saldo-$total_abono,0),0,0,'L');
                        $archivo->Ln(15);
                    }
                }
            }
        }
        if($contador_saldos>0){
            $archivo->Output($nombreArchivo, "F");

            $consecutivo_arc = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$sesion_sucursal."'");
            if ($consecutivo_arc){
                $consecutivo_arc++;
            } else {
                $consecutivo_arc = 1;
            }
            $consecutivo_arc = (int)$consecutivo_arc;

            $datos_archivo = array(
                "codigo_sucursal" => $sesion_sucursal,
                "consecutivo"     => $consecutivo_arc,
                "nombre"          => $nombre
            );
            SQL::insertar("archivos", $datos_archivo);
            $id_archivo   = $sesion_sucursal."|".$consecutivo_arc;
            $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
        }else{
            $error   = true;
            $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;
    HTTP::enviarJSON($respuesta);
}

?>
