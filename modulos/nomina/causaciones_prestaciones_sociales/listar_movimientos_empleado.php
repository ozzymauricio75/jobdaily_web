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

if(isset($url_verificar)){
    $condicion_extra = "id_empresa='$url_codigo_empresa'";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

///Generar el formulario para la captura de datos
    $mensaje   = $textos["MENSAJE"];
    $continuar = true;
    //echo var_dump(rangoDias("2011-04-02","2011-02-15"));
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
                        HTML::listaSeleccionSimple("*codigo_empresa", $textos["EMPRESAS"],$listado_empresa,"",array("title" => $textos["AYUDA_EMPRESA"])),
                        HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "acLocalEmpleados(this);","onKeyUp" => "limpiar_oculto_Autocompletable(this,documento_identidad_empleado)"))
                       .HTML::campoOculto("documento_identidad_empleado",""),
                    ),array(
                        HTML::campoTextoCorto("*fecha_rango", $textos["RANGO_FECHA"],20, 20,"", array("title" => $textos["AYUDA_FECHA_LIQUIDACION"], "class" => "fechaRango"))
                    )
                   
                );
                // Definicion de botones
                $url_id = 'adicionar';
                $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar"));
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

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    $ruta_archivo = "";
    $condicion    = "1";
    $error        = false;
    $mensaje      = $textos["ARCHIVO_GENERADO"];

    // ====================== Inicio de condiciones ====================== //

    if((int)$forma_codigo_empresa != 0){
        $condicion .= " AND codigo_empresa = '".$forma_codigo_empresa."'";
    }

    if($forma_fecha_rango != ""){
        $fechas = explode("-",$forma_fecha_rango);
        $fecha1 = trim($fechas[0]);
        $fecha2 = trim($fechas[1]);
        $fecha1 = str_replace("/","-",$fecha1);
        $fecha2 = str_replace("/","-",$fecha2);
        $condicion .= " AND (fecha_liquidacion BETWEEN '".$fecha1."' AND '".$fecha2."')";
    }

    if($forma_documento_identidad_empleado != ""){
        $condicion .= " AND documento_identidad_empleado <= ".$forma_documento_identidad_empleado;
    }
    if($forma_documento_identidad_empleado != "" && $forma_documento_identidad_empleado == ""){
        $error   = true;
        $mensaje = $textos["ERROR_DOCUMENTO_IDENTIDAD_1"];
        $ruta    = "";
    }

    // ======================= Fin  de condiciones ======================= //
    
    $nombre         = "";
    $nombreArchivo  = "";
    do {
        $cadena         = Cadena::generarCadenaAleatoria(8);
        $nombre = $sesion_sucursal.$cadena.".pdf";
        $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
    } while (is_file($nombreArchivo));

    $archivo                 = new PDF("L","mm","Letter");
    $archivo->textoTitulo    = $textos["LISTCAPE"];
    $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoPiePagina = "";
    $archivo->AddPage();


    $i = 0;
    
    
    $listado_causaciones = array();
    $agrupar = "codigo_empresa,documento_identidad_empleado,fecha_liquidacion,codigo_transaccion_contable";
    $consulta_causaciones_prestaciones_sociales = SQL::seleccionar(array("causaciones_prestaciones_sociales"),array("*"),$condicion,$agrupar,"sentido ASC");

    while($datos_causaciones = SQL::filaEnObjeto($consulta_causaciones_prestaciones_sociales) ){

        $fecha_liquidacion            = $datos_causaciones->fecha_liquidacion;
        $codigo_transaccion_contable  = $datos_causaciones->codigo_transaccion_contable;
        $nombre_transaccion = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$codigo_transaccion_contable'");

        if(!isset($listado_causaciones[$fecha_liquidacion][$codigo_transaccion_contable])){
            $listado_causaciones[$fecha_liquidacion][$codigo_transaccion_contable]= array("codigo_contable" => $datos_causaciones->codigo_plan_contable,"transaccion" => $nombre_transaccion,"sentido" => $datos_causaciones->sentido,"valor_movimiento" => $datos_causaciones->valor_movimiento);
        }
    }
    $archivo->generarCabeceraTabla(array(""),array(130),0,"C",false);
    $archivo->Ln(4);

    $nombre_empleado    = explode("-",$forma_selector1);
    $archivo->Cell(130, 4,$textos["EMPLEADO"]." : ".$nombre_empleado[1],1, 0, "L", false,"",true);
    $archivo->Ln(4);
    $archivo->Cell(130, 4,$textos["CEDULA"]." : ".$forma_documento_identidad_empleado,1, 0, "L", false,"",true);
    $archivo->Ln(4);
   
    $tituloColumnas = array($textos["CODIGO_CONTABLE"],$textos["TRANSACCION"],$textos["DEBITOS"],$textos["CREDITOS"]);
    $anchoColumnas  = array(30,60,20,20);
    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
    $archivo->Ln(4);

    if(count($listado_causaciones)){

        foreach($listado_causaciones AS $llave_fecha => $datos_causacion){
            $archivo->SetFont('Arial',"",6);
            $archivo->Cell(130, 4,$textos["FECHA_LIQUIDACION"]." : ".$llave_fecha, 1, 0, "L", true,"",true);
            $archivo->Ln(4);
            
           foreach($datos_causacion AS $datos_causaciones_transaccion){

               $datos_causaciones_transaccion = (object)$datos_causaciones_transaccion;
               //echo var_dump($datos_causaciones_transaccion);
               if($archivo->breakCell(5)){
                    $archivo->AddPage();
                    $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                    $archivo->Ln(4);
                }

                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                }else{
                    $archivo->SetFillColor(240,240,240);
                }

                $valor_credito =" ";
                $valor_debito  =" ";
                if($datos_causaciones_transaccion->sentido=='C'){
                   $valor_credito = number_format($datos_causaciones_transaccion->valor_movimiento,0);
                }else{
                   $valor_debito =  number_format($datos_causaciones_transaccion->valor_movimiento,0);
                }

                $archivo->Cell($anchoColumnas[0], 4,$datos_causaciones_transaccion->codigo_contable , 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[1], 4, $datos_causaciones_transaccion->transaccion, 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[2], 4,$valor_debito, 1, 0, "R", true,"",true);
                $archivo->Cell($anchoColumnas[3], 4,$valor_credito, 1, 0, "R", true,"",true);
                $archivo->Ln(4);
                $i++;
            }
        }
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
    HTTP::enviarJSON($respuesta);
}
?>
