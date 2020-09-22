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

//include("clases/diario.php");
/*** Devolver datos para autocompletar la búsqueda ***/
if (isset($url_completar)) {
    if (($url_item) == "selector1") {
        echo SQL::datosAutoCompletar("seleccion_municipios", $url_q);
    }
    
    if (($url_item) == "selector2") {
        echo SQL::datosAutoCompletar("seleccion_proyectos", $url_q);
    }

    if (($url_item) == "selector3") {
        echo SQL::datosAutoCompletar("menu_cuentas_bancarias", $url_q);
    }

    if (($url_item) == "selector4") {
        echo SQL::datosAutoCompletar("seleccion_proveedores", $url_q);
    }

    exit;
/*** Cargar cuenta de banco ***/
} elseif (!empty($url_cargarCuenta)) {
    $cuenta       = $url_cuenta;
    $consulta     = SQL::seleccionar(array("cuentas_bancarias"), array("*"), "numero='$cuenta'");
    $datos_cuenta = SQL::filaEnObjeto($consulta);
    $banco        = $datos_cuenta->codigo_banco;
    $sucursal     = $datos_cuenta->codigo_sucursal;

    $nombre_banco = SQL::obtenerValor("bancos","descripcion","codigo='$banco'");
    $tercero      = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");

    if($nombre_banco){
        $datos = array(
            $nombre_banco,
            $tercero
        );
    }else{
        $datos = "";
    }
    
    HTTP::enviarJSON($datos);
    exit; 

    /*** Verificar si existe saldo inicial ***/
} elseif (!empty($url_saldoCuenta)) {
    $cuenta       = $url_cuenta;
    $consulta     = SQL::seleccionar(array("saldo_inicial_cuentas"), array("*"), "cuenta_origen='$cuenta'");

    if($consulta){
        $indicador = 1;
    }else{
        $indicador = 0;
    }
    
    HTTP::enviarJSON($indicador);
    exit; 

/*** Mostrar los conceptos de tesoreria ***/
} elseif(isset($url_recargar_conceptos)){
    
    $lista = HTML::generarDatosLista("conceptos_tesoreria","codigo","nombre_concepto","codigo_grupo_tesoreria = '".$url_codigo_grupo."'");
    
    if(empty($lista)){
        $lista = array("0" => $textos["CONCEPTO_SIN_GRUPO"]);
    }

    HTTP::enviarJSON($lista);
    exit;

// Generar el formulario para la captura de datos
} if (!empty($url_generar)) {

    $consulta   = SQL::seleccionar(array("bancos"), array("*"), "codigo != '0'");
    $bancos_ver = SQL::filasDevueltas($consulta);

    if($bancos_ver==0){
        $error     = $textos["ERROR_BANCOS_VACIOS"];
        $titulo    = "";
        $contenido = "";
    }else{

        $error  = "";
        $titulo = $componente->nombre;

        $consulta = SQL::seleccionar(array("bancos"), array("codigo, descripcion"), "codigo!='0'");

        // Obtener lista de sucursales para seleccion
        if (SQL::filasDevueltas($consulta)) {
            while ($datos = SQL::filaEnObjeto($consulta)) {
                $bancos[$datos->codigo] = $datos->descripcion;
            }
        }

        $pestana_bancos   = array();
        $pestana_bancos[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todos_bancos();", "", array()));

        foreach($bancos AS $llave => $valor){

            $idBancos = $llave;

            $pestana_bancos[] = array(
                HTML::marcaChequeo("bancos[".$llave."]", $valor, $llave, false, array("title" => $textos["AYUDA_SUCURSAL_PRINCIPAL"], "codigo" => "bancos_".$llave))
            );
        }
        
        $grupos    = HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo","codigo>0");
        $conceptos = HTML::generarDatosLista("conceptos_tesoreria", "codigo_grupo_tesoreria", "nombre_concepto","codigo_grupo_tesoreria = '".array_shift(array_keys($grupos))."'");

        // Definicion de pestanas
        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_DESDE"].'  -  '.$textos["FECHA_HASTA"], 25, 25,$fecha_inicial, array("title" => $textos["RANGO_FECHAS"], "class" => "fechaRango"))
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            //HTML::marcaChequeo("todas_cuentas",$textos["TODAS_CUENTAS"], 1, false, array("title"=>$textos["AYUDA_POR_TODAS_CUENTAS"], "class"=>"por_todas_cuentas","onClick"=>"activaCamposCuentas(this)")),

                            HTML::marcaChequeo("por_cuenta",$textos["POR_CUENTA"], 1, false, array("title"=>$textos["AYUDA_POR_CUENTAS"], "class"=>"por_cuenta","onClick"=>"activaCamposCuentas(this)"))
                            .HTML::campoOculto("por_cuenta_activo", "")
                        ),
                        array(
                            HTML::campoTextoCorto("selector3",$textos["NUMERO_CUENTA"], 15, 15, "", array("title"=>$textos["AYUDA_NUMERO_CUENTA"],"class" => "autocompletable por_banco oculto", "onChange"=>"cargarCuenta(),saldoCuenta()")),

                            HTML::campoTextoCorto("banco", $textos["BANCO"], 37, 40, "", array("title" => $textos["AYUDA_BANCO"],"class" => "por_banco oculto", "onBlur" => "validarItem(this);")),

                            HTML::campoTextoCorto("tercero", $textos["TERCERO"], 37, 40, "", array("title" => $textos["AYUDA_TERCERO"],"class" => "por_banco oculto", "onBlur" => "validarItem(this);"))
                        )
                    ),
                    $textos["CUENTAS"]
                )
            ),
            array(
                /*HTML::agrupador(
                    array(
                        array(
                            HTML::marcaChequeo("todos_bancos",$textos["TODOS_BANCOS"], 1, false, array("title"=>$textos["AYUDA_POR_TODOS_BANCOS"], "class"=>"por_todos_bancos","onClick"=>"activaCamposArticulos(this)")),

                            HTML::marcaChequeo("por_banco",$textos["POR_BANCO"], 1, false, array("title"=>$textos["AYUDA_POR_BANCO"], "class"=>"por_banco","onClick"=>"activaCamposArticulos(this)")),
                        ),
                        array(
                            HTML::campoTextoCorto("selector1",$textos["BANCO"], 45, 255, "", array("title"=>$textos["AYUDA_BANCO"],"class" => "autocompletable", "onChange"=>"cargarCuenta(),saldoCuenta()"))

                            //HTML::marcaSeleccion("bancos", $textos["TODOS_BANCOS"], 1, true, array("title"=>$textos["AYUDA_POR_TODOS_BANCOS"],"class"=>"por_totales","onChange"=>"activaCamposArticulos(this)")),

                            //HTML::marcaSeleccion("bancos", $textos["POR_BANCO"], 0, false, array("title"=>$textos["AYUDA_POR_BANCO"],"class"=>"por_totales","onChange"=>"activaCamposTotales(this)"))
                        )
                    ),
                    $textos["BANCOS"]
                ),*/
                HTML::agrupador(
                    array(
                        array(
                            //HTML::marcaChequeo("todos_proyectos",$textos["TODOS_PROYECTOS"], 1, false, array("title"=>$textos["AYUDA_POR_TODOS_PROYECTOS"], "class"=>"por_todos","onClick"=>"activaCamposArticulos(this)")),

                            HTML::marcaChequeo("por_proyecto",$textos["POR_PROYECTO"], 1, false, array("title"=>$textos["AYUDA_POR_PROYECTO"], "class"=>"por_proyecto","onClick"=>"activaCamposProyectos(this)"))
                            .HTML::campoOculto("por_proyecto_activo", "")
                        ),
                        array(    
                            HTML::campoTextoCorto("selector2", $textos["PROYECTO"], 45, 255, "", array("title" => $textos["AYUDA_PROYECTO"], "class" => "autocompletable por_proyecto_seleccionado oculto" ))
                        )
                    ),
                    $textos["PROYECTO"]
                ),
                HTML::agrupador(
                    array(
                        array(
                            //HTML::marcaChequeo("todos_proveedores",$textos["TODOS_PROVEEDORES"], 1, false, array("title"=>$textos["AYUDA_POR_TODOS_PROVEEDORES"], "class"=>"todos_proveedores","onClick"=>"activaCamposArticulos(this)")),

                            HTML::marcaChequeo("por_proveedor",$textos["POR_PROVEEDOR"], 1, false, array("title"=>$textos["AYUDA_POR_PROVEEDOR"], "class"=>"por_proveedor","onClick"=>"activaCamposProveedores(this)"))
                            .HTML::campoOculto("por_proveedor_activo", "")
                        ),
                        array(
                            HTML::campoTextoCorto("selector4",$textos["PROVEEDOR"], 45, 45, "", array("title"=>$textos["AYUDA_PROVEEDOR"],"class" => "autocompletable oculto por_proveedor_seleccionado", "onBlur"=>"cargarCuentaProveedor()"))
                        )
                    ),
                    $textos["PROVEEDOR"]
                ),
            ),
            array(
                HTML::agrupador(
                    array(
                        array(
                            //HTML::marcaChequeo("todos_conceptos",$textos["TODOS_CONCEPTOS"], 1, false, array("title"=>$textos["AYUDA_POR_TODOS_CONCEPTOS"], "class"=>"por_todos_conceptos","onClick"=>"activaCamposArticulos(this)")),

                            HTML::marcaChequeo("por_concepto",$textos["POR_CONCEPTO"], 1, false, array("title"=>$textos["AYUDA_POR_CONCEPTO"], "class"=>"por_concepto","onClick"=>"activaCamposConceptos(this)"))
                            .HTML::campoOculto("por_concepto_activo", "")
                        ),
                        array(
                            HTML::listaSeleccionSimple("codigo_grupo", $textos["GRUPO_TESORERIA"], HTML::generarDatosLista("grupos_tesoreria", "codigo", "nombre_grupo"), "", array("title" => $textos["AYUDA_GRUPO_TESORERIA"], "class"=>"por_concepto_seleccionado oculto", "onChange" => "verificarConceptos();")),

                            HTML::listaSeleccionSimple("codigo_concepto", $textos["CONCEPTO_TESORERIA"], $concepto, "",array("title" => $textos["AYUDA_CONCEPTO_TESORERIA"],"class"=>"por_concepto_seleccionado oculto"))
                        )
                    ),
                    $textos["CONCEPTO"]
                )
            )
        );

        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Adicionar los datos provenientes del formulario
} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error          = false;
    $mensaje        = $textos["ITEM_ADICIONADO"];
    $ruta_archivo   = "";
var_dump("expression",$forma_por_cuenta_activo);
    $fechas            = explode('-',$forma_fechas);
    $forma_fecha_desde = trim($fechas[0]);
    $forma_fecha_hasta = trim($fechas[1]);

    if (!isset($forma_sucursales)) {
        $error   = true;
        $mensaje = $textos["SUCURSAL_VACIO"];

    } else {

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $detalles = false;
        if (isset($forma_mostrar_detalle)) {
            $detalles = true;
        }

        $archivo                 = new PDF("L","mm","Legal");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoPiePagina = "";

        $condicion_comprobante = "AND codigo_tipo_comprobante = '".$forma_tipo_comprobante."'";
        if ($forma_tipo_comprobante == 0) {
            $condicion_comprobante = "";
        }

        $sucursales_reporte = array();
        foreach ($forma_sucursales AS $sucursal) {
            if (!isset($sucursales_reporte[$forma_consolidar[$sucursal]])){
                $sucursales_reporte[$forma_consolidar[$sucursal]] = "'".$sucursal."',";
            } else {
                $sucursales_reporte[$forma_consolidar[$sucursal]] .= "'".$sucursal."',";
            }
        }

        $total_debito_empresa  = 0;
        $total_credito_empresa = 0;

        $contador_registros = 0;

        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            $condicion_sucursal = trim($condicion_sucursal, ",");
            $consolidadas       = explode(",", $condicion_sucursal);

            $total_debito_sucursal  = 0;
            $total_credito_sucursal = 0;

            $total_debito_documento  = 0;
            $total_credito_documento = 0;

            $documento_actual   = '';
            $consecutivo_actual = '';

            $primera = true;

            $consulta   = SQL::seleccionar(array("movimientos_contables_consolidados"),array("*"),"(fecha_contabilizacion BETWEEN '".$forma_fecha_desde."' AND '".$forma_fecha_hasta."')AND codigo_sucursal_genera IN (".$condicion_sucursal.") ".$condicion_comprobante,"","codigo_tipo_documento ASC, numero_consecutivo ASC, codigo_tipo_comprobante ASC, numero_comprobante ASC");

            if (SQL::filasDevueltas($consulta)) {

                cabeceraComprobante($archivo,$textos,$sucursal,$forma_fecha_desde,$forma_fecha_hasta,$consolidadas,"REPODIMD");
                titulosDMD($archivo,$textos);

                while ($datos = SQL::filaEnObjeto($consulta)) {

                    if($archivo->breakCell(10)){
                        cabeceraComprobante($archivo,$textos,$sucursal,$forma_fecha_desde,$forma_fecha_hasta,$consolidadas,"REPODIMD");
                        titulosDMD($archivo,$textos);
                    }

                    if(!$primera && ($documento_actual != $datos->codigo_tipo_documento || $consecutivo_actual != $datos->numero_consecutivo)){
                        $archivo->SetFont('Arial','B',7);
                        $archivo->Cell(225,5,"",0,0,'L');
                        $archivo->Cell(40,5,$textos["TOTAL"].":",0,0,'R');
                        $archivo->Cell(35,5,"$".number_format($total_debito_documento,0),0,0,'R');
                        $archivo->Cell(35,5,"$".number_format($total_credito_documento,0),0,0,'R');
                        $archivo->Ln(5);
                        $total_debito_documento  = 0;
                        $total_credito_documento = 0;
                    }
                    $archivo->SetFont('Arial','',7);
                    $maneja_saldo    = SQL::obtenerValor("plan_contable","maneja_saldos","codigo_contable = '".$datos->codigo_contable."'");
                    $documento_cruce = "";
                    if($maneja_saldo == '1'){
                        $tabla = SQL::obtenerValor("tablas","nombre_tabla","id = '".$datos->id_tabla."'");
                        if($tabla == "movimientos_contables"){
                            if($datos->sentido_cuenta != $datos->sentido_movimiento){
                                $llave_item = $datos->llave_registro."|".str_pad($datos->consecutivo_item,9,"0", STR_PAD_LEFT);
                                $llave_saldo = SQL::obtenerValor("buscador_abonos_movimientos_contables","id_saldo","id_item_movimiento = '".$llave_item."'","","",0,1);
                                if($llave_saldo){
                                    $llave_saldo = explode('|',$llave_saldo);
                                    $documento_cruce = $llave_saldo[4]." - ".$llave_saldo[5];
                                }
                            }
                        }
                    }
                    $tercero = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '".$datos->documento_identidad_tercero."'");
                    $archivo->Cell(30,4,$datos->codigo_tipo_documento." - ".$datos->numero_consecutivo,0,0,'L');
                    $archivo->Cell(30,4,$documento_cruce,0,0,'L');
                    $archivo->Cell(35,4,$datos->codigo_tipo_comprobante." - ".$datos->numero_comprobante,0,0,'L');
                    $archivo->Cell(30,4,$datos->fecha_contabilizacion,0,0,'C');
                    $archivo->Cell(50,4,$tercero,0,0,'L',false,"",true);
                    $archivo->Cell(50,4,$datos->codigo_contable." - ".$datos->descripcion_cuenta,0,0,'L',false,"",true);
                    $archivo->Cell(20,4,$datos->codigo_anexo_contable,0,0,'L');
                    $archivo->Cell(20,4,$datos->codigo_auxiliar_contable,0,0,'L');

                    if($datos->sentido_movimiento == "D"){
                        $debito  = "$".number_format($datos->valor,0);
                        $credito = "$0";
                        $total_debito_sucursal  += $datos->valor;
                        $total_debito_empresa   += $datos->valor;
                        $total_debito_documento += $datos->valor;
                    }else{
                        $credito = "$".number_format($datos->valor,0);
                        $debito  = "$0";
                        $total_credito_sucursal  += $datos->valor;
                        $total_credito_empresa   += $datos->valor;
                        $total_credito_documento += $datos->valor;
                    }

                    $archivo->Cell(35,4,$debito,0,0,'R');
                    $archivo->Cell(35,4,$credito,0,0,'R');
                    $archivo->Ln(4);

                    if($detalles && !empty($datos->detalle)){
                        $archivo->SetFillColor(240,240,240);
                        $archivo->Cell(30,4,$textos["OBSERVACIONES"].":",0,0,'L',true);
                        $archivo->Cell(305,4,$datos->detalle,0,0,'L',true,"",true);
                        $archivo->Ln(4);
                    }

                    $documento_actual   = $datos->codigo_tipo_documento;
                    $consecutivo_actual = $datos->numero_consecutivo;
                    $primera            = false;
                    $contador_registros++;
                }
                $archivo->SetFont('Arial','B',7);
                $archivo->Cell(225,5,"",0,0,'L');
                $archivo->Cell(40,5,$textos["TOTAL"].":",0,0,'R');
                $archivo->Cell(35,5,"$".number_format($total_debito_documento,0),0,0,'R');
                $archivo->Cell(35,5,"$".number_format($total_credito_documento,0),0,0,'R');
                $archivo->Ln(5);
                totalSucursalDMD($archivo,$textos,$total_debito_sucursal,$total_credito_sucursal);
            }
        }

        if($total_credito_empresa > 0 || $total_debito_empresa > 0){
            $archivo->SetFont('Arial','B',7);
            $archivo->Cell(225,5,"",0,0,'L');
            $archivo->Cell(40,5,$textos["TOTAL_EMPRESA"].":",0,0,'R');
            $archivo->Cell(35,5,"$".number_format($total_debito_empresa,0),0,0,'R');
            $archivo->Cell(35,5,"$".number_format($total_credito_empresa,0),0,0,'R');
            $archivo->Ln(5);
            $archivo->Cell(225,5,"",0,0,'L');
            $archivo->Cell(40,5,$textos["DIFERENCIA"].":",0,0,'R');
            $archivo->Cell(70,5,"$".number_format($total_debito_empresa-$total_credito_empresa,0),0,0,'C');
            $archivo->Ln(5);
        }

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

        if($contador_registros > 0){
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
