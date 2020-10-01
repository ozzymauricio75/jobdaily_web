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

/*** Generar el formulario para la captura de datos ***/
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

        $tipo_listado = array(
            "1" => "PDF",
            "2" => "EXCEL"
        );

        // Definicion de pestanas
        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_DESDE"].'  -  '.$textos["FECHA_HASTA"], 25, 25, $fecha_inicial, array("title" => $textos["RANGO_FECHAS"], "class" => "fechaRango")),

                HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"], $tipo_listado,1,array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
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

    $fechas            = explode('-',$forma_fechas);
    $forma_fecha_desde = trim($fechas[0]);
    $forma_fecha_desde = str_replace("/","-",$forma_fecha_desde);
    $forma_fecha_hasta = trim($fechas[1]);
    $forma_fecha_hasta = str_replace("/","-",$forma_fecha_hasta);

    $llave_proyecto    = explode("-", $forma_selector2);
    $codigo_proyecto   = $llave_proyecto[0];

    $llave                         = explode("-", $forma_selector4);
    $documento_identidad_proveedor = $llave[0];

    $nombre         = "";
    $nombreArchivo  = "";

    do {
        $cadena = Cadena::generarCadenaAleatoria(8);
        if ($forma_tipo_listado=="1"){
            $nombre = $cadena.".pdf";

        } else{
            $nombre = $cadena.".csv";
        }
        $nombreArchivo = $rutasGlobales["movimientos"]."/movimiento".$nombre;
    } while (is_file($nombreArchivo));
        
    if (file_exists($nombreArchivo)){
        unlink($nombreArchivo);
        $archivo = fopen($nombreArchivo,"a+");
    } else {
        $archivo = fopen($nombreArchivo,"a+");
    } 

    if($forma_por_cuenta_activo==2){
        $condicion_cuenta = " AND cuenta_origen='$forma_selector3'";
    } else{
        $condicion_cuenta = "";
    }

    if($forma_por_proyecto_activo==2){
        $condicion_proyecto = " AND codigo_proyecto='$codigo_proyecto'";
    } else{
        $condicion_proyecto = "";
    }

    if($forma_por_proveedor_activo==2){
        $condicion_proveedor = " AND documento_identidad_tercero='$documento_identidad_proveedor'";
    } else{
        $condicion_proveedor = "";
    }

    if($forma_por_concepto_activo==2){
        $condicion_concepto = " AND codigo_concepto_tesoreria='$forma_codigo_concepto'";
    } else{
        $condicion_concepto = "";
    }

    //Titulos segun tipo listado
    if ($forma_tipo_listado=="1"){
        //////////////////////////////ENCABEZADO DEL DOCUMENTO PDF REPORTE DE MOVIMIENTO/////////////////////////////
        $anchoColumnas           = array(20,50);
        $alineacionColumnas      = array("I","I");
        $saldo_actual            = 0;

        $archivo                 = new PDF("L","mm","Letter");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoTitulo    = $textos["REPOMVTO"];
        $archivo->textoPiePagina = $textos["PIE_PAGINA_EMPRESA"];

        $archivo->AddPage();
        $archivo->SetFont('Arial','B',8);

        $archivo->SetFont('Arial','B',8);
        $archivo->Ln(0);
        $archivo->Cell(35,4,$textos["FECHA_DESDE_HASTA"]." :",0,0,'L');
        $archivo->SetFont('Arial','',8);
        $archivo->Cell(70,4,"".$forma_fecha_desde."  ".$forma_fecha_hasta,0,0,'L');
        $archivo->Cell(40,4,"",0,1,'R');  
  
        $archivo->SetFont('Arial','B',6);
        $archivo->SetFillColor(225,225,225);

        $archivo->Ln(6);
        $archivo->Cell(40,4,$textos["BANCOS"],1,0,'C',true);
        $archivo->Cell(20,4,$textos["CUENTA_ORIGEN"],1,0,'C',true);
        $archivo->Cell(40,4,$textos["CONCEPTO"],1,0,'C',true);
        $archivo->Cell(20,4,$textos["VALOR"],1,0,'C',true);
        $archivo->Cell(20,4,$textos["FECHA_MOVIMIENTO"],1,0,'C',true);
        $archivo->Cell(20,4,$textos["SALDO"],1,0,'C',true);
        $archivo->Cell(30,4,$textos["PROYECTO"],1,0,'C',true);
        $archivo->Cell(50,4,$textos["PROVEEDOR"],1,0,'C',true);
        $archivo->Cell(20,4,$textos["CUENTA_DESTINO"],1,0,'C',true);
        //////////////////////////////FIN ENCABEZADO DEL DOCUMENTO PDF ORDEN DE COMPRA/////////////////////////////
        
        /*** Obtener los datos de la tabla movimientos tesoreria ***/
        $condicion_fecha       = "fecha_registra>='$forma_fecha_desde' AND fecha_registra<='$forma_fecha_hasta'";
        $condiciones           = $condicion_fecha.$condicion_cuenta.$condicion_proyecto.$condicion_concepto.$condicion_proveedor;
        $movimientos_tesoreria = SQL::seleccionar(array("movimientos_tesoreria"),array("*"),"$condiciones");

        if (SQL::filasDevueltas($movimientos_tesoreria)){
            while($datos_movimiento = SQL::filaEnObjeto($movimientos_tesoreria)){
                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                } else{
                    $archivo->SetFillColor(240,240,240);
                }
                //Se lee el movimiento de la tabla movimientos
                $codigo_banco      = SQL::obtenerValor("cuentas_bancarias","codigo_banco","numero='$datos_movimiento->cuenta_origen'");
                $nombre_banco      = SQL::obtenerValor("bancos","descripcion","codigo='$codigo_banco'");
                $saldo_fecha       = SQL::obtenerValor("saldos_movimientos","saldo","codigo_movimiento='$datos_movimiento->codigo'");
                $nombre_proyecto   = SQL::obtenerValor("proyectos","nombre","codigo='$datos_movimiento->codigo_proyecto'");
                $nombre_concepto   = SQL::obtenerValor("conceptos_tesoreria","nombre_concepto","codigo='$datos_movimiento->codigo_concepto_tesoreria'");
                $tipo_persona      = SQL::obtenerValor("terceros","tipo_persona","documento_identidad='$datos_movimiento->documento_identidad_tercero'");

                if($tipo_persona==1){
                    $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $nombre_proveedor = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
                } else{
                    $nombre_proveedor  = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'"); 
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////
                $archivo->Ln(4);
                $archivo->Cell(40,4,$nombre_banco,1,0,'L',true);
                $archivo->Cell(20,4,$datos_movimiento->cuenta_origen,1,0,'L',true);
                $archivo->Cell(40,4,$nombre_concepto,1,0,'L',true);
                $archivo->Cell(20,4,""."$ ".number_format($datos_movimiento->valor_movimiento,0),1,0,'R',true);
                $archivo->Cell(20,4,$datos_movimiento->fecha_registra,1,0,'C',true);
                $archivo->Cell(20,4,""."$ ".number_format($saldo_fecha,0),1,0,'R',true);
                $archivo->Cell(30,4,$nombre_proyecto,1,0,'L',true);
                $archivo->Cell(50,4,$nombre_proveedor,1,0,'L',true);
                $archivo->Cell(20,4,$datos_movimiento->cuenta_proveedor,1,0,'L',true);

                /////////////////////////////////////////////////////////////////////////////////////////////////
                $imprime_cabecera = $archivo->breakCell(8);

                if($imprime_cabecera){
                    $archivo->Ln(4);
                    $archivo->SetFont('Arial','B',8);
                    $archivo->Ln(0);
                    $archivo->Cell(35,4,$textos["FECHA_DESDE_HASTA"]." :",0,0,'L');
                    $archivo->SetFont('Arial','',8);
                    $archivo->Cell(70,4,"".$forma_fecha_desde."-".$forma_fecha_hasta,0,0,'L');
                    $archivo->Cell(40,4,"",0,1,'R');  
  
                    $archivo->SetFont('Arial','B',6);
                    $archivo->SetFillColor(225,225,225);

                    $archivo->Ln(6);
                    $archivo->Cell(40,4,$textos["BANCOS"],1,0,'C',true);
                    $archivo->Cell(20,4,$textos["CUENTA_ORIGEN"],1,0,'C',true);
                    $archivo->Cell(40,4,$textos["CONCEPTO"],1,0,'C',true);
                    $archivo->Cell(20,4,$textos["VALOR"],1,0,'C',true);
                    $archivo->Cell(20,4,$textos["FECHA_MOVIMIENTO"],1,0,'C',true);
                    $archivo->Cell(20,4,$textos["SALDO"],1,0,'C',true);
                    $archivo->Cell(30,4,$textos["PROYECTO"],1,0,'C',true);
                    $archivo->Cell(50,4,$textos["PROVEEDOR"],1,0,'C',true);
                    $archivo->Cell(20,4,$textos["CUENTA_DESTINO"],1,0,'C',true);
                }
                $i++;
                $item++;
            }
        }
        if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                $archivo->SetFillColor(255,255,255);
        } else{
                $archivo->SetFillColor(240,240,240);
        }
        $archivo->Output($nombreArchivo, "F");
        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$datos_encabezado->codigo_sucursal."'");

        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }

        $datos_archivo = array(
            "codigo_sucursal" => $datos_encabezado->codigo_sucursal,
            "consecutivo"     => $consecutivo,
            "nombre"          => $nombre
        );
        SQL::insertar("archivos", $datos_archivo);
        $mensaje = HTML::enlazarPagina($textos["GENERAR_PLANO"], $nombreArchivo, array("target" => "_new"));
        
    } else{
        //Se crean los titulos del archivo excel
        $titulos_plano = "BANCOS;CUENTA ORIGEN;CONCEPTO;VALOR;FECHA MOVIMIENTO;SALDO;PROYECTO;PROVEEDOR;CUENTA DESTINO\n";
            fwrite($archivo, $titulos_plano);

        /*** Obtener los datos de la tabla movimientos tesoreria ***/
        $condicion_fecha       = "fecha_registra>='$forma_fecha_desde' AND fecha_registra<='$forma_fecha_hasta'";
        $condiciones           = $condicion_fecha.$condicion_cuenta.$condicion_proyecto.$condicion_concepto.$condicion_proveedor;
        $movimientos_tesoreria = SQL::seleccionar(array("movimientos_tesoreria"),array("*"),"$condiciones");

        if (SQL::filasDevueltas($movimientos_tesoreria)){
            while($datos_movimiento = SQL::filaEnObjeto($movimientos_tesoreria)){
                //Se lee el movimiento de la tabla movimientos
                $codigo_banco      = SQL::obtenerValor("cuentas_bancarias","codigo_banco","numero='$datos_movimiento->cuenta_origen'");
                $nombre_banco      = SQL::obtenerValor("bancos","descripcion","codigo='$codigo_banco'");
                $saldo_fecha       = SQL::obtenerValor("saldos_movimientos","saldo","codigo_movimiento='$datos_movimiento->codigo'");
                $nombre_proyecto   = SQL::obtenerValor("proyectos","nombre","codigo='$datos_movimiento->codigo_proyecto'");
                $nombre_concepto   = SQL::obtenerValor("conceptos_tesoreria","nombre_concepto","codigo='$datos_movimiento->codigo_concepto_tesoreria'");
                $tipo_persona      = SQL::obtenerValor("terceros","tipo_persona","documento_identidad='$datos_movimiento->documento_identidad_tercero'");

                if($tipo_persona==1){
                    $primer_nombre    = SQL::obtenerValor("terceros", "primer_nombre", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $segundo_nombre   = SQL::obtenerValor("terceros", "segundo_nombre", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $primer_apellido  = SQL::obtenerValor("terceros", "primer_apellido", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $segundo_apellido = SQL::obtenerValor("terceros", "segundo_apellido", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'");
                    $nombre_proveedor = $primer_nombre." ".$segundo_nombre." ".$primer_apellido." ".$segundo_apellido;
                } else{
                    $nombre_proveedor  = SQL::obtenerValor("terceros", "razon_social", "documento_identidad = '".$datos_movimiento->documento_identidad_tercero."'"); 
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////
                $cuenta_origen    = $datos_movimiento->cuenta_origen;
                $valor_movimiento = $datos_movimiento->valor_movimiento;
                $fecha_registra   = $datos_movimiento->fecha_registra;
                $cuenta_destino   = $datos_movimiento->cuenta_proveedor;

                //Contenido del archivo
                $contenido = "$nombre_banco;$cuenta_origen;$nombre_concepto;$valor_movimiento;$fecha_registra;$saldo_fecha;$nombre_proyecto;$nombre_proveedor;$cuenta_destino\n";
                $guardarArchivo = fwrite($archivo,$contenido);
            }
        }
       
        $archivo->Output($nombreArchivo, "F");
        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$datos_encabezado->codigo_sucursal."'");

        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }

        $datos_archivo = array(
            "codigo_sucursal" => $datos_encabezado->codigo_sucursal,
            "consecutivo"     => $consecutivo,
            "nombre"          => $nombre
        );
        SQL::insertar("archivos", $datos_archivo);
        $mensaje = HTML::enlazarPagina($textos["GENERAR_PLANO"], $nombreArchivo, array("target" => "_new"));    
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;
    HTTP::enviarJSON($respuesta);
}
?>
