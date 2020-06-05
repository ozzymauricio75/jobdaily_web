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
require("clases/clases.php");

if(isset($url_determinar_rango_dias_bloquear)){
    //////Determinar si se ha hecho anteriores liquidaciones de prestaciones sociales//////
    $consulta_causaciones_prestaciones_sociales = SQL::seleccionar(array("causaciones_prestaciones_sociales"),array("*"),"codigo_empresa='$url_codigo_empresa'","","fecha_liquidacion DESC",0,1);
    $datos_causaciones_prestaciones_sociales    = SQL::filaEnObjeto($consulta_causaciones_prestaciones_sociales);
    $fecha_actual = date("Y-m-d");
    if(SQL::filasDevueltas($consulta_causaciones_prestaciones_sociales)){
        $rango_fecha  = (strtotime($fecha_actual) - strtotime($datos_causaciones_prestaciones_sociales->fecha_liquidacion))/(60*60*24);
    }else{
        $rango_fecha  = 0;
    }
    $respuesta   = array();
    $respuesta[] = SQL::filasDevueltas($consulta_causaciones_prestaciones_sociales);
    $respuesta[] = $rango_fecha;
    
    HTTP::enviarJSON($respuesta);
    exit;
}

if(isset($url_informacion_empleado)){
    
    $datos_causaciones = InformacionCausacionEmpresa($url_codigo_empresa,$url_fecha_liquidacion,'3');
    $datos_envio       = cargarDatosTabla($datos_causaciones,$textos);
    HTTP::enviarJSON($datos_envio);
    exit;
}

if(!empty($url_generar)){
    ///Generar el formulario para la captura de datos
    $mensaje   = $textos["MENSAJE"];
    $continuar = true;
   
    $consulta_sucursales          = SQL::seleccionar(array("sucursales"),array("*"),"codigo !='0'");
    $tipo_comprobante             = SQL::seleccionar(array("tipos_comprobantes"),array("*"),"codigo !='0'");
    $consulta_tipos_documentos    = SQL::seleccionar(array("tipos_documentos"),array("*"),"manejo_automatico='2' AND sentido_contable!='0'");
    $consulta_tablas_del_sistema  = SQL::seleccionar(array("tablas"),array("*"),"nombre_tabla = 'causaciones_prestaciones_sociales'");

    if(SQL::filasDevueltas($consulta_tablas_del_sistema)== 0 ){
        $mensaje  .= $textos["NO_EXISTE_TABLA"];
        $continuar = false;
    }if(SQL::filasDevueltas($consulta_sucursales)== 0 ){
        $mensaje  .= $textos["SUCURSALES"];
        $continuar = false;
    }if(SQL::filasDevueltas($tipo_comprobante)== 0 ){
        $mensaje  .= $textos["MENSAJE_TIPO_COMPROBANTES"];
        $continuar = false;
    }if(SQL::filasDevueltas($consulta_tipos_documentos)== 0 ){
        $mensaje  .= $textos["MENSAJE_TIPO_DOCUMENTOS"];
        $continuar = false;
    }

    if(!$continuar){
        $respuesta    = array();
        $respuesta[0] = $mensaje;
        $respuesta[1] = "";
        $respuesta[2] = "";
     }else{
        $error  = "";
        $titulo = $componente->nombre;

        $tasa_salud   = (int)SQL::obtenerValor("preferencias","valor","variable='tasa_salud' AND tipo_preferencia=1");
        $tasa_pension = (int)SQL::obtenerValor("preferencias","valor","variable='tasa_pension' AND tipo_preferencia=1");

        if(!$tasa_salud || !$tasa_pension){
            $listaMensajes = array();
            $mensaje       = $textos["ERROR_PREFERENCIAS_TASAS"];

            if(!$tasa_salud){
                $listaMensajes[] = $textos["ERROR_TASA_SALUD"];
            }
            if(!$tasa_pension){
                $listaMensajes[] = $textos["ERROR_TASA_PENSION"];
            }
            $mensaje  .= implode("\n",$listaMensajes);
            $error     = $mensaje;
            $titulo    = "";
            $contenido = "";
        }else{
            ///Obtener lista de sucursales para selección///
            $codigo_empresa = SQL::obtenerValor("sucursales", "codigo_empresa", "codigo = '$sesion_sucursal'");

            if($sesion_usuario == $datosGlobales["usuarioMaestro"]){
                $consulta_empresas = SQL::seleccionar(array("empresas"), array("codigo,razon_social"),"codigo!=''");
            }else{
                // Obtener lista de sucursales para seleccion
                $tablas    = array("a" => "perfiles_usuario","b" => "componentes_usuario","c" => "sucursales");
                $columnas  = array("codigo" => "c.codigo_empresa");
                $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil AND a.codigo_usuario = '".$sesion_codigo_usuario."'
                AND b.id_componente = '".$componente->id."'";
                $consulta_sucursales    = SQL::seleccionar($tablas, $columnas, $condicion);
                $datos_sucursales       = SQL::filaEnArreglo($consulta_sucursales);
                $cadena_codigo_empresas = implode(",",$datos_sucursales);
                $consulta_empresas      =  SQL::seleccionar(array("empresas"), array("codigo,razon_social"),"codigo IN ($cadena_codigo_empresas)");
            }

            /////////////Cargo las empresas creadas///////////////
            $error_empresas = false;
            if(SQL::filasDevueltas($consulta_sucursales)){

                $pestana_empresas   = array();
                $pestana_empresas[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_empresas();", "", array()));
                $listado_empresa    = array();
                $listado_empresa[' '] = "";
                while($datos_empresas = SQL::filaEnObjeto($consulta_empresas)){

                    $codigo_empresa  = $datos_empresas->codigo;
                    $nombre_empresa  = $datos_empresas->razon_social;

                    $listado_empresa[$codigo_empresa] = $nombre_empresa;

                    $pestana_empresas[] = array(
                        HTML::marcaChequeo("empresas[".$codigo_empresa."]",$nombre_empresa,$codigo_empresa, false, array("title" => $textos["AYUDA_EMPRESA"], "id" => "empresas_".$codigo_empresa, "class" => "total_empresa"))
                    );
                }
            }else{
                $error_empresas = true;
            }
            if(!$error_empresas){

                /////////////////////////////////////////////////////
                $tipo_comprobante       = HTML::generarDatosLista("tipos_comprobantes","codigo","descripcion","codigo !='0'");
                $listado_tipo_documento = HTML::generarDatosLista("tipos_documentos","codigo","descripcion","manejo_automatico='2' AND sentido_contable!='0'");
                /*** Definición de pestaña Basica ***/
                //$formularios["PESTANA_EMPRESAS"] = $pestana_empresas;
                $formularios["BASICO"] = array(
                    array(
                        HTML::listaSeleccionSimple("*codigo_empresa", $textos["EMPRESAS"],$listado_empresa,"",array("title" => $textos["AYUDA_EMPRESA"],"onchange"=>"informacionEmpleado();actualizarEventosCalendario();")),
                        HTML::campoTextoCorto("*fecha_liquidacion", $textos["FECHA_LIQUIDACION"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_LIQUIDACION"], "class" => "selectorFecha")),
                        HTML::campoTextoCorto("*fecha_contabilizacion", $textos["FECHA_CONTABILIZACION"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_CONTABILIZACION"], "class" => "selectorFecha")),
                    ),
                    array(
                        HTML::listaSeleccionSimple("codigo_tipo_documento",$textos["TIPO_DOCUMENTO"],$listado_tipo_documento,""),
                        HTML::listaSeleccionSimple("codigo_tipo_comprobante",$textos["TIPO_COMPROBANTE"],$tipo_comprobante,""),
                        HTML::campoTextoCorto("*numero_comprobate",$textos["NUMERO_COMPROBATE"],10,10, "",array("onKeyPress" => "return campoEntero(event)"))
                    ),
                    array(
                        HTML::boton("generar",$textos["LISTADO_PRESTACIONES_EMPREADOS"],"imprimirItem('0');","adicionar",array("title" => $textos["AYUDA_LISTADO_PRESTACIONES_EMPREADOS"])),
                        HTML::campoOculto("titulo_codigo_contable",$textos["CODIGO_CONTABLE"]),
                        HTML::campoOculto("titulo_transaccion",$textos["TRANSACCION"]),
                        HTML::campoOculto("titulo_debitos",$textos["DEBITOS"]),
                        HTML::campoOculto("titulo_creditos",$textos["CREDITOS"]),
                    ),
                    array(
                        HTML::generarTabla(
                            array("id","CODIGO_CONTABLE","TRANSACCION","DEBITOS","CREDITOS"),
                            "",
                            array("C","C","C","C"),
                            "listaItemsPrestaciones",
                            false
                       )
                    )
                );
                // Definicion de botones
                $url_id = 'adicionar';
                $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"],  "modificarItem('$url_id');", "aceptar"));
                $contenido = HTML::generarPestanas($formularios, $botones);
            }else{
                $error = $textos["ERROR_CAGAR_EMPRESAS"];
            }
            /// Enviar datos para la generación del formulario al script que originó la petición
            $respuesta[0] = $error;
            $respuesta[1] = $titulo;
            $respuesta[2] = $contenido;
        }
    }

    HTTP::enviarJSON($respuesta);
     
}elseif(!empty($forma_procesar)){

    if(empty($forma_id)){

        $ruta_archivo = "";
        $condicion    = "1";
        $error        = false;
        $mensaje      = $textos["ARCHIVO_GENERADO"];
        $contador     = 1;

        $nombre         = "";
        $nombreArchivo  = "";

        do{
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        }while (is_file($nombreArchivo));

        $archivo                 = new PDF("L","mm","letter");
        $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoPiePagina = "";

        $i = 0;

        $arreglo_prestaciones             = array("prima","vacaciones","cesantias");
        $consulta_prestaciones_prima      = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'1');
        $consulta_prestaciones_vacaciones = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'2');
        $consulta_prestaciones_cesantias  = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'3');

        foreach($arreglo_prestaciones AS $nombre_pretacion){
            
            $valor_tota_prestacion         = 0;
            $valor_tota_prestacion_interes = 0;

            if(count(${"consulta_prestaciones_".$nombre_pretacion})){

                if($contador==1){
                    $titulo = $textos["PRIMA"];
                }elseif($contador==2){
                    $titulo = $textos["VACACIONES"];
                }else{
                    $titulo = $textos["CESANTIAS"];
                }

                $anchoColumnas  = array(20,15,50,20,15,20,20,30,10,20,20);

                if($contador=='1' || $contador=='2'){
                    $tituloColumnas = array($textos["SUCURSAL"],$textos["CEDULA"],$textos["EMPLEADO"],$textos["FECHA_INGRESO"],$textos["SALARIO_BASE"],$textos["AUXILO_TRANSPORTE"],$textos["PROMEDIO_MOVIENTOS"],$textos["FECHA_INICIO_CALCULO"],$textos["DIAS_PRESTACION"],$textos["VALOR_BASE_CALCULO"],$titulo);
                }else{
                    $tituloColumnas = array($textos["SUCURSAL"],$textos["CEDULA"],$textos["EMPLEADO"],$textos["FECHA_INGRESO"],$textos["SALARIO_BASE"],$textos["AUXILO_TRANSPORTE"],$textos["PROMEDIO_MOVIENTOS"],$textos["FECHA_INICIO_CALCULO"],$textos["DIAS_PRESTACION"],$textos["VALOR_BASE_CALCULO"],$titulo,$textos["VALOR_INTERESES"]);
                    $anchoColumnas [] = 20;
                }

                $archivo->textoTitulo = $textos["REPORTE_CAUSACION"].$titulo;
                $archivo->AddPage();
                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                $archivo->Ln(4);

                foreach(${"consulta_prestaciones_".$nombre_pretacion} AS $arreglo_pretaciones){

                    $datos = (object) $arreglo_pretaciones;

                    if($archivo->breakCell(5)){
                        $archivo->AddPage();
                        $archivo->generarCabeceraTabla($tituloColumnas,$anchoColumnas);
                        $archivo->Ln(4);
                    }

                    if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                        $archivo->SetFillColor(255,255,255);
                    }else{
                        $archivo->SetFillColor(240,240,240);
                    }

                    $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo = '$datos->codigo_sucursal_actual'");
                    $nombre_empleado    = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '$datos->documento_identidad'");
                    $nombre_empleado    = explode(",",$nombre_empleado);

                    $archivo->SetFont('Arial',"",6);
                    $archivo->Cell($anchoColumnas[0], 4, $nombre_sucursal, 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[1], 4, $datos->documento_identidad, 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[2], 4, $nombre_empleado[1], 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[3], 4, $datos->fecha_ingreso_empleado, 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[4], 4, $datos->salario_base, 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[5], 4, number_format($datos->auxilio_transporte), 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[6], 4, number_format($datos->promedio_movimientos), 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[7], 4, $datos->fecha_inicio_calculo, 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[8], 4, $datos->dias_total_calculo, 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[9], 4, number_format($datos->base_prestacion), 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[10], 4, number_format($datos->valor_prestacion), 1, 0, "L", true,"",true);
                    
                    $valor_tota_prestacion += $datos->valor_prestacion;
                    if($contador=='3'){
                        $archivo->Cell($anchoColumnas[10], 4, number_format($datos->valor_intereses), 1, 0, "L", true,"",true);
                        $valor_tota_prestacion_interes += $datos->valor_intereses;
                    }
                    $archivo->Ln(4);
                    $i++;
                }
                $archivo->Cell(200, 4,"",0, 0, "L", false,"",true);
                $archivo->Cell(20, 4,$textos["TOTAL"],1, 0, "R", false,"",true);
                $archivo->Cell(20, 4, number_format($valor_tota_prestacion),1, 0, "L", false,"",true);
                if($contador=='3'){
                    $archivo->Cell(20, 4, number_format($valor_tota_prestacion_interes),1, 0, "L", false,"",true);
                }
            }
            $contador++;
        }

        $cargaPdf = 0;
        if($i>0 && !$error) {
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
            $cargaPdf = 1;
        }
        // Enviar datos con la respuesta del proceso al script que origino la peticion
        if ($cargaPdf == 1) {
            $ruta    = $ruta_archivo;
        } else if($cargaPdf == 0 && !$error){
            $error = true;
            $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
            $ruta = "";
        }

        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        $respuesta[2] = $ruta;
    }else{
        $error   = false;
        $mensaje = $textos["ITEM_ADICIONADO"];
        
        if(strtotime($forma_fecha_liquidacion)>strtotime($forma_fecha_contabilizacion)){
            $error   = true;
            $mensaje = $textos["ERROR_FECHA_LIQUIDACION"];
        }elseif(empty($forma_numero_comprobate)){
            $error   = true;
            $mensaje = $textos["ERROR_NUMERO_COMPROBANTE"];
        }else{
            $fecha_generacion = date("Y-m-d H:i:s");
            $fecha_registro   = date("Y-m-d");
           
            $arreglo_prestaciones = array("prima" => "1","vacaciones" => "2","cesantias" => "3","intereses"=>"4");
            $consulta_prestaciones_prima      = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'1');
            $consulta_prestaciones_vacaciones = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'2');
            $consulta_prestaciones_cesantias  = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'3');
            $consulta_prestaciones_intereses  = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'4');

            $consulta_prestaciones            = array_merge($consulta_prestaciones_prima,$consulta_prestaciones_vacaciones);
            $consulta_prestaciones            = array_merge($consulta_prestaciones,$consulta_prestaciones_cesantias);
            $consulta_prestaciones            = array_merge($consulta_prestaciones,$consulta_prestaciones_intereses);

            $sucursales_genero_documento = array();
            $consulta_contrato_empleado = SQL::seleccionar(array("consulta_contrato_empleado"), array("codigo_sucursal"),"codigo_empresa='$forma_codigo_empresa'  AND fecha_ingreso_sucursal <= '$forma_fecha_liquidacion' AND estado= '1' ","","codigo_sucursal DESC");
            while($datos_contrato_sucursal_empleado = SQL::filaEnObjeto($consulta_contrato_empleado)){
                /////////////Generó la llave de la tabla////////////////
                $id_tabla   = SQL::obtenerValor("tablas", "id", "nombre_tabla = 'causaciones_prestaciones_sociales'");
                $consecutivo_documento = SQL::obtenerValor("consecutivo_documentos", "MAX(consecutivo)", "codigo_sucursal = '$datos_contrato_sucursal_empleado->codigo_sucursal' AND codigo_tipo_documento = '$forma_codigo_tipo_documento'");
                if(!$consecutivo_documento){
                    $consecutivo_documento = 1;
                }else{
                    $consecutivo_documento++;
                }

                $llave_tabla = $forma_codigo_empresa.'|0|'.$forma_codigo_tipo_comprobante.'|0|'.$forma_codigo_tipo_documento.'|'.str_pad($consecutivo_documento,9,"0", STR_PAD_LEFT).'|'.$fecha_registro;

                $datos = array(
                    "codigo_sucursal"             => $datos_contrato_sucursal_empleado->codigo_sucursal,
                    "codigo_tipo_documento"       => $forma_codigo_tipo_documento,
                    "fecha_registro"              => $fecha_registro,
                    "documento_identidad_tercero" => '0',
                    "consecutivo"                 => $consecutivo_documento,
                    "id_tabla"                    => $id_tabla,
                    "llave_tabla"                 => $llave_tabla,
                    "codigo_sucursal_archivo"     => '0',
                    "consecutivo_archivo"         => '0'
                );

                $insertar = SQL::insertar("consecutivo_documentos", $datos);
                if (!$insertar) {
                    break;
                }else{
                    $sucursales_genero_documento[$datos_contrato_sucursal_empleado->codigo_sucursal] = array(
                        $consecutivo_documento
                    );
                }
            }
 
            if (!$insertar) {
                $error   = true;
                $mensaje = $textos["EXISTE_CONSECUTIVO_DOCUMENTO"];
            }else{

                $transacciones_cesantias = array(
                    "cesantia_pago_prestacion",
                    "cesantia_causacion_gasto"
                );
                $transacciones_intereses = array(
                    "intereses_pago_prestacion",
                    "intereses_causacion_prestacion"
                );
                $transacciones_prima = array(
                    "prima_pago_prestacion",
                    "prima_causacion_prestacion"
                );
                $transacciones_vacaciones = array(
                    "vacacion_pago_prestacion_disfrute",
                    "vacacion_causacion_prestacion"
                );

                foreach($consulta_prestaciones AS $arreglo_pretaciones){
                    $datos_prestacion = (object) $arreglo_pretaciones;

                    foreach(${"transacciones_".$datos_prestacion->nombre_prestacion} AS $nombre_campo){

                        $consulta_departamento = SQL::seleccionar(array("departamentos_empresa"),array("*"),"codigo='$datos_prestacion->codigo_departamento'");
                        $datos_departamento    = SQL::filaEnObjeto($consulta_departamento);
                        $codigo_gasto          = $datos_departamento->codigo_gasto;

                        $transaccion_contable_prestacion = SQL::obtenerValor("gastos_prestaciones_sociales",$nombre_campo,"codigo='$codigo_gasto'");
                        $descripcion     = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$transaccion_contable_prestacion'");
                        $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable_prestacion'");
                        $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable_prestacion'");

                        $condicion_datos_causaciones  = " codigo_empresa='$forma_codigo_empresa' AND documento_identidad_empleado='$datos_prestacion->documento_identidad'";
                        $condicion_datos_causaciones .= " AND concepto='".$arreglo_prestaciones[$datos_prestacion->nombre_prestacion]."' AND fecha_liquidacion!='$forma_fecha_liquidacion'";

                        $valor_acumulado_causaciones  = SQL::obtenerValor("agrupar_causaciones_prestaciones_sociales","SUM(valor_movimiento)",$condicion_datos_causaciones);

                        if($valor_acumulado_causaciones!=null || $valor_acumulado_causaciones){
                            $valor_movimiento = $datos_prestacion->valor_prestacion - $valor_acumulado_causaciones;
                        }else{
                            $valor_movimiento = $datos_prestacion->valor_prestacion;
                        }

                        $consecutivo_documento = $sucursales_genero_documento[$datos_prestacion->codigo_sucursal_actual][0];

                        $datos = array(
                            "concepto"                          => $datos_prestacion->concepto,
                            /////////fechas de control//////////////////////////////////
                            "fecha_generacion"                  => $fecha_generacion,
                            "fecha_liquidacion"                 => $forma_fecha_liquidacion,
                            "fecha_contabilizacion"             => $forma_fecha_contabilizacion,
                            ////contrato_sucursal_empleado//////////////////////////////
                            "codigo_empresa"                    => $forma_codigo_empresa,
                            "documento_identidad_empleado"      => $datos_prestacion->documento_identidad,
                            "fecha_ingreso"                     => $datos_prestacion->fecha_ingreso_empleado,
                            "codigo_sucursal"                   => $datos_prestacion->codigo_sucursal_actual,
                            "fecha_ingreso_sucursal"            => $datos_prestacion->fecha_ingreso_sucursal,
                            ///Datos consecutivo documento//////////////////////////////
                            "codigo_sucursal_documento"         => $datos_prestacion->codigo_sucursal_actual,
                            "codigo_tipo_documento"             => $forma_codigo_tipo_documento,
                            "identidad_tercero_documento"       => '0',
                            "fecha_generacion_consecutivo"      => $fecha_registro,
                            "consecutivo_documento"             => $consecutivo_documento,
                            //////Tipo de comprobante///////////////////////////////////
                            "codigo_tipo_comprobante"           => $forma_codigo_tipo_comprobante,
                            "numero_comprobante"                => $forma_numero_comprobate,
                            /////Datos Auxiliar contable///////////////////////////////
                            "codigo_empresa_auxiliar"           => "",
                            "codigo_anexo_contable"             => "",
                            "codigo_auxiliar_contable"          => "0",
                            ///////////Informacion Prestaciones Sociales//////////////
                            "fecha_inicio"                      => $datos_prestacion->fecha_inicio_calculo,
                            "fecha_final"                       => $forma_fecha_liquidacion,
                            "dias_liquidados"                   => $datos_prestacion->dias_total_calculo,
                            "salario_base"                      => $datos_prestacion->base_prestacion,
                            "periodo_pago"                      => '0',
                            ///////Informacion Contable///////////////////////////////
                            "codigo_transaccion_contable"       => $transaccion_contable_prestacion,
                            "codigo_plan_contable"              => $codigo_contable,
                            "sentido"                           => $sentido,
                            "valor_movimiento"                  => $valor_movimiento,
                            //////Informacion Adicional///////////////////////////////
                            "codigo_usuario_registra"           => $sesion_codigo_usuario,
                            "codigo_usuario_modifica"           => $sesion_codigo_usuario
                        );

                        $condicion  = " codigo_empresa='$forma_codigo_empresa' AND documento_identidad_empleado='$datos_prestacion->documento_identidad'";
                        $condicion .= " AND fecha_liquidacion='$forma_fecha_liquidacion' AND codigo_transaccion_contable='$transaccion_contable_prestacion'";

                        if(SQL::existeItem("causaciones_prestaciones_sociales","codigo_transaccion_contable",$transaccion_contable_prestacion,$condicion)){
                             $insertar = SQL::modificar("causaciones_prestaciones_sociales",$datos,$condicion);
                        }else{
                             $insertar = SQL::insertar("causaciones_prestaciones_sociales",$datos);
                        }
                        if(!$insertar){
                            $error   = true;
                            $mensaje = $textos["ERROR_INSERTAR_CAUSACIONES"];
                        }
                    }
                }
            }
        }
        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
      }
    HTTP::enviarJSON($respuesta);
}
?>
