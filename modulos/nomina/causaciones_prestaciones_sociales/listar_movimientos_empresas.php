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
                $pestana_empresas[] = array(HTML::campoTextoCorto("*fecha_liquidacion", $textos["FECHA_LIQUIDACION"], 10, 10,date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_LIQUIDACION"], "class" => "selectorFecha")));
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
                $formularios["PESTANA_EMPRESAS"] = $pestana_empresas;
               
                // Definicion de botones
                
                $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"],"imprimirItem('0');", "aceptar"));
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

     if (!isset($forma_empresas)){
        $error   = true;
        $mensaje = $textos["ERROR_EMPRESA_VACIA"];

     }else{

        foreach($forma_empresas as $codigo_empresa){

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

            $nombre_empresa = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");

            $archivo                 = new PDF("L","mm","letter");
            $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
            $archivo->textoTitulo    = $textos["LISTCMPE"]." ( ".$nombre_empresa." ) ";
            $archivo->textoPiePagina = "";
            $archivo->AddPage();

            $tituloColumnas = array($textos["CEDULA"],$textos["NOMBRE_EMPLEADO"],$textos["FECHA_LIQUIDACION"],$textos["SALARIO_BASE"],$textos["CESANTIAS"],$textos["VALOR_INTERESES"],$textos["PRIMA"],$textos["VACACIONES"]);
            $anchoColumnas  = array(30,50,30,25,25,25,25,25);
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);

            $i = 0;

            $consulta_prestaciones_empleado = InformacionPrestacionesEmpleado($codigo_empresa,$forma_fecha_liquidacion);
   
            if(count($consulta_prestaciones_empleado)){

                foreach($consulta_prestaciones_empleado AS $documento_identidad => $datos_prestaciones_empleado) {

                    $datos_prestaciones_empleado = (object)$datos_prestaciones_empleado;
                   
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

                    if(isset($datos_prestaciones_empleado->cesantias) && isset($datos_prestaciones_empleado->prima_servicio)){
                        $valor_cesantias = number_format($datos_prestaciones_empleado->cesantias,0);
                        $valor_intereses = number_format($datos_prestaciones_empleado->intereses,0);
                        $valor_prima     = number_format($datos_prestaciones_empleado->prima_servicio,0);
                    }else{
                        $valor_cesantias = 0;
                        $valor_intereses = 0;
                        $valor_prima     = 0;
                    }

                   // $sucursal    = SQL::obtenerValor("sucursales","nombre","codigo = '".$datos->codigo_sucursal."'");
                    $empleado    = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '$documento_identidad'");
                    $empleado    = explode(",",$empleado);
                    $archivo->SetFont('Arial',"",6);
                    $archivo->Cell($anchoColumnas[0], 4, $documento_identidad, 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[1], 4, $empleado[1], 1, 0, "L", true,"",true);
                    $archivo->Cell($anchoColumnas[2], 4, $forma_fecha_liquidacion, 1, 0, "R", true,"",true);
                    $archivo->Cell($anchoColumnas[3], 4, $datos_prestaciones_empleado->salario_base, 1, 0, "R", true,"",true);
                    $archivo->Cell($anchoColumnas[4], 4, $valor_cesantias, 1, 0, "R", true,"",true);
                    $archivo->Cell($anchoColumnas[5], 4, $valor_intereses, 1, 0, "R", true,"",true);
                    $archivo->Cell($anchoColumnas[6], 4, $valor_prima, 1, 0, "R", true,"",true);
                    $archivo->Cell($anchoColumnas[7], 4, $datos_prestaciones_empleado->vacaciones, 1, 0, "R", true,"",true);
                   
                    $archivo->Ln(4);

                    $i++;
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
        }
    }
   
    HTTP::enviarJSON($respuesta);
}
?>
