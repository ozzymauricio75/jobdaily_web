<?php
/**
*
* Copyright (C) 2020 Jobdaily
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

// Generar el formulario para la captura de datos
 if(isset($url_recargarTipoPlanilla))
        {
              $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$url_codigo_planilla."'");
              HTTP::enviarJSON($tipo_planilla);
        }

    if (!empty($url_recargar) && !empty($url_codigo_planilla) && !empty($url_ano_generacion) && !empty($url_mes_generacion) ) {


    $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$url_codigo_planilla."'");

    $periodo = "";
    if ($tipo_planilla == '1'){
        $periodo = array(
            "1" => $textos["MENSUAL"],
        );
    } else if($tipo_planilla == '2') {
        $periodo = array(
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
        );
    } else if($tipo_planilla == '3')  {
        $periodo = array(
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"]
        );
    } else if($tipo_planilla == '4') {
        $periodo = array(
            "9" => $textos["FECHA_UNICA"]
        );
    }

    if ($url_mes_generacion == 2){
        if (($url_ano_generacion % 4 ==0) && ($url_ano_generacion % 100 !=0 || $url_ano_generacion % 400 == 0)){
            $dia_fin = 29;
        } else {
            $dia_fin = 28;
        }
    } else {
            $dia_fin = 31;
    }

    $fecha_inicio = $url_ano_generacion."-".$url_mes_generacion."-01";
    $fecha_fin    = $url_ano_generacion."-".$url_mes_generacion."-".$dia_fin;

    $respuesta = HTML::generarDatosLista("fechas_planillas", "fecha", "fecha", "codigo_planilla='".$url_codigo_planilla."' AND (fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."')");
    $fechas    = SQL::seleccionar(array("fechas_planillas"),array("fecha"),"codigo_planilla='".$url_codigo_planilla."' AND (fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."')");

    if (isset($url_periodo) && SQL::filasDevueltas($fechas)){
        $respuesta = $periodo;
    }

    HTTP::enviarJSON($respuesta);

}

if (!empty($url_generar)) {

    $error           = "";
    $titulo          = $componente->nombre;
    $error_continuar = false;

    $empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."' AND codigo>0");

    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
        $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '".$empresa."'");
    }else{
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
        AND b.id_componente = '".$componente->id."' AND c.codigo_empresa = '".$empresa."'";

        $consulta_sucursales = SQL::seleccionar($tablas, $columnas, $condicion);
    }

    if (SQL::filasDevueltas($consulta_sucursales)){

        $pestana_sucursales   = array();
        if (SQL::filasDevueltas($consulta_sucursales) == 1){
            $chequeo = true;
        } else {
            $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));
            $chequeo = false;
        }

        while ($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){

            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;

            $listaSucursales[] = $codigo_sucursal;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[".$datos_sucursales->codigo."]", $datos_sucursales->nombre, $datos_sucursales->codigo, $chequeo, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_".$datos_sucursales->codigo, "class" => "sucursales_electrodomesticos"))
            );
        }

    } else {
        $error_continuar = 0;
    }

    $consulta_planillas = SQL::seleccionar(array("planillas"),array("*"),"codigo>0");
    if (SQL::filasDevueltas($consulta_planillas)){

        $planillas[0] = '';

        while ($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }

    } else {
        $error_continuar = 1;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla>0");
    if (SQL::filasDevueltas($consulta_fechas_planillas)){

        while ($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)){
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla."|".$datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    } else {
        $error_continuar = 2;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!=''");
    if (!SQL::filasDevueltas($consulta_empleados)){
        $error_continuar = 3;
    }

    if (!$error_continuar){


        $ano = date("Y");
        $ano_planilla = array();
        for ($i=0;$i<=1;$i++){
            $ano_planilla[$ano] = $ano;
            $ano++;
        }
        $ano = date("Y");
        $mes = date("m");

        $meses = array(
            "01" => $textos["ENERO"],
            "02" => $textos["FEBRERO"],
            "03" => $textos["MARZO"],
            "04" => $textos["ABRIL"],
            "05" => $textos["MAYO"],
            "06" => $textos["JUNIO"],
            "07" => $textos["JULIO"],
            "08" => $textos["AGOSTO"],
            "09" => $textos["SEPTIEMBRE"],
            "10" => $textos["OCTUBRE"],
            "11" => $textos["NOVIEMBRE"],
            "12" => $textos["DICIEMBRE"]
        );

        $forma_listar = array(
            "1"  => $textos["CONTABILIZADAS"],
            "2"  => $textos["NO_CONTABILIZADAS"],
            "3"  => $textos["TODAS"],
        );

        $orden_planilla =array(
            "1" => $textos["APELLIDO_NOMBRE"],
            "2" => $textos["NOMBRE_APELLIDO"],
            "3" => $textos["CEDULA"]
        );

        $tipo_listado = array(
            "1" => "PDF",
            "2" => "PLANO"
        );

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*forma_listar", $textos["FORMA_LISTAR"],$forma_listar,3, array("title" => $textos["AYUDA_FORMA_PLANILLAS"])),
                HTML::listaSeleccionSimple("*orden_planilla", $textos["ORDEN_PLANILLA"],$orden_planilla,1, array("title" => $textos["AYUDA_ORDEN_PLANILLA"])),
            ),
            array(
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();"))
            ),
            array(
                HTML::listaSeleccionSimple("codigo_planilla",$textos["PLANILLA"],$planillas,"",array("title"=>$textos["AYUDA_PLANILLA"], "onchange"=>"cargarFechaPago2();"))
            ),
            array(
                HTML::listaSeleccionSimple("fecha_pago",$textos["FECHA_PAGO"], "","",array("title"=>$textos["AYUDA_FECHA_PAGO"],"class"=>"fecha_pago","onclick" => "determinarPeriodo();")),
                HTML::mostrarDato("nombre_periodo",$textos["PERIODO"],"")
            ),
            array(
                HTML::campoOculto("periodo","").
                HTML::campoOculto("mensual",$textos["MENSUAL"]).
                HTML::campoOculto("primera_quincena",$textos["PRIMERA_QUINCENA"]).
                HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"]).
                HTML::campoOculto("fecha_unica",$textos["FECHA_UNICA"])
            ),
            array(
                HTML::listaSeleccionSimple("tipo_listado",$textos["TIPO_LISTADO"],$tipo_listado,1,array("title"=>$textos["AYUDA_TIPO_LISTADO"]))
            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem(1);", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {
        if ($error_continuar == 0){
            $error = $textos["ERROR_SUCURSALES"];
        } else if($error_continuar == 1){
            $error = $textos["ERROR_PLANILLAS"];
        } else if($error_continuar == 2){
            $error = $textos["ERROR_FECHAS_PLANILLAS"];
        } else if($error_continuar == 3){
            $error = $textos["ERROR_EMPLEADOS"];
        }
        $contenido = "";
    }


    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error        = false;
    $mensaje      = $textos["PLANILLA_GENERADA"];
    $genero_pdf   = false;

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];

    } else if (empty($forma_fecha_pago)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];

    } else if (empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO"];

    } else {

        $nombre         = "";
        $nombreArchivo  = "";

        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='$sesion_sucursal'");
        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }
        $consecutivo = (int)$consecutivo;
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            if ($forma_tipo_listado=="1"){
                $nombre = $sesion_sucursal.$cadena.".pdf";
            } else {
                $nombre = $sesion_sucursal.$cadena.".csv";
            }
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $genero_pdf = false;
        if ($forma_tipo_listado=="1"){
            $fechaReporte = date("Y-m-d");
            $archivo = new PDF("L","mm","Legal");
            $archivo->textoPiePagina = "";
        } else {
            $archivo = fopen($nombreArchivo,"a+");
        }

        $periodo = array(
            "1" => $textos["MENSUAL"],
            "2" => $textos["PRIMERA_QUINCENA"],
            "3" => $textos["SEGUNDA_QUINCENA"],
            "4" => $textos["PRIMERA_SEMANA"],
            "5" => $textos["SEGUNDA_SEMANA"],
            "6" => $textos["TERCERA_SEMANA"],
            "7" => $textos["CUARTA_SEMANA"],
            "8" => $textos["QUINTA_SEMANA"],
            "9" => $textos["FECHA_UNICA"]
        );
        $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$forma_codigo_planilla'");
        if ($forma_orden_planilla=="1"){
            $orden_planilla = "apellido_empleado";
        } else if ($forma_orden_planilla=="2"){
            $orden_planilla = "nombre_empleado";
        } else {
            $orden_planilla = "documento_identidad_empleado ASC";
        }
        $orden_empleado = $orden_planilla;

        foreach ($forma_sucursales as $codigo_sucursal){

            $condicion_planilla  = "ano_generacion = '$forma_ano_generacion' AND mes_generacion = '$forma_mes_generacion' AND periodo_pago = '$forma_periodo'";
            $condicion_planilla .= " AND codigo_planilla = '$forma_codigo_planilla' AND codigo_sucursal = '$codigo_sucursal'";
            //echo var_dump($condicion_planilla);

            $contador = 0;
            $total_sucursal  = array();
            $dias_trabajados = array();
            $dias_diferencia = array();

            while($contador<=1) {

                $total_sucursal_columna = array();
                $total_neto_sucursal = false;
                $consulta_planilla = false;
                if ($contador) {
                    if ($forma_forma_listar == '2' || $forma_forma_listar == '3') {
                        $consulta_planilla = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),$condicion_planilla." AND contabilizado!='1' ","","departamento_empresa,$orden_planilla");
                    }
                } else {
                    if ($forma_forma_listar == '1' || $forma_forma_listar == '3') {
                        $consulta_planilla = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),$condicion_planilla." AND contabilizado ='1' ","","departamento_empresa,$orden_planilla");
                    }
                }

                $departamento_empleado      = array();
                $empleado                   = array();
                $salario                    = array();
                $codigo_contable_movimiento = array();
                $total_codigo               = array();
                $total_codigo_credito       = array();
                $columna                    = array();
                $movimiento_tercero_debito  = array();
                $movimiento_tercero         = array();
                $tercero                    = array();

                $contabilizado = true;
                if ($consulta_planilla != false) {

                    if (SQL::filasDevueltas($consulta_planilla)) {

                        while ($datos = SQL::filaEnObjeto($consulta_planilla)) {

                            $registro[$codigo_sucursal] = true;
                            $departamento               = $datos->departamento_empresa;
                            $documento                  = $datos->documento_identidad_empleado;
                            if($forma_orden_planilla==1){
                                $nombre_empleado = $datos->apellido_empleado;
                            } else {
                                $nombre_empleado = $datos->nombre_empleado;
                            }
                            $codigo_contable            = $datos->codigo_contable;
                            $sentido                    = $datos->sentido;
                            $valor_movimiento           = $datos->valor_movimiento;

                            $condicion_salario = "codigo_empresa='$datos->codigo_empresa' AND documento_identidad_empleado='$documento' AND codigo_sucursal='$datos->codigo_sucursal'";
                            $condicion_salario .= " AND fecha_ingreso='$datos->fecha_ingreso_empresa' AND fecha_ingreso_sucursal='$datos->fecha_ingreso_sucursal'";
                            $condicion_salario .= " AND fecha_salario<='$forma_fecha_pago' ORDER BY fecha_salario DESC LIMIT  0,1";

                            $salario_empleado           = SQL::obtenerValor("salario_sucursal_contrato","salario",$condicion_salario);
                            $dias_trabajados_periodo    = $datos->dias_trabajados;
                            $columna_planilla           = $datos->columna_planilla;
                            $fecha_ingreso              = $datos->fecha_ingreso_empresa;

                            if ($datos->contabilizado == 0) {
                                $contabilizado = false;
                            }

                            if (empty($columna_planilla) || $columna_planilla < 1) {
                                $columna_planilla = 1;
                                continue;
                            }

                            $transaccion = $datos->codigo_transaccion_contable;
                            $concepto_transaccion_contable = SQL::obtenerValor("transacciones_contables_empleado", "codigo_concepto_transaccion_contable", "codigo ='$transaccion'");

                            if ($columna_planilla < 9) {

                                if ($sentido == 'D') {

                                    if (!isset($columna[$departamento][$documento][$columna_planilla])) {
                                        $columna[$departamento][$documento][$columna_planilla] = $valor_movimiento;
                                    } else {
                                        $columna[$departamento][$documento][$columna_planilla] += $valor_movimiento;
                                    }

                                    if (!isset($total_devengado[$departamento][$documento])){
                                        $total_devengado[$departamento][$documento] = $valor_movimiento;
                                    } else {
                                        $total_devengado[$departamento][$documento] += $valor_movimiento;
                                    }

                                } else if ($sentido == 'C') {

                                    if (!isset($columna[$departamento][$documento][$columna_planilla])) {
                                        $columna[$departamento][$documento][$columna_planilla] = $valor_movimiento * (-1);
                                    } else {
                                        $columna[$departamento][$documento][$columna_planilla] -= $valor_movimiento;
                                    }

                                    if (!isset($total_devengado[$departamento][$documento])){
                                        $total_devengado[$departamento][$documento] = $valor_movimiento * (-1);
                                    } else {
                                        $total_devengado[$departamento][$documento] -= $valor_movimiento;
                                    }
                                }
                                if (!isset($total_deducido[$departamento][$documento])){
                                    $total_deducido[$departamento][$documento] = 0;
                                }
                            } else {

                                if ($sentido == 'C') {

                                    if (!isset($columna[$departamento][$documento][$columna_planilla])) {
                                        $columna[$departamento][$documento][$columna_planilla] = $valor_movimiento;
                                    } else {
                                        $columna[$departamento][$documento][$columna_planilla] += $valor_movimiento;
                                    }

                                    if (!isset($total_deducido[$departamento][$documento])){
                                        $total_deducido[$departamento][$documento] = $valor_movimiento;
                                    } else {
                                        $total_deducido[$departamento][$documento] += $valor_movimiento;
                                    }

                                } else if ($sentido == 'D') {

                                    if (!isset($columna[$departamento][$documento][$columna_planilla])) {
                                        $columna[$departamento][$documento][$columna_planilla] = $valor_movimiento * (-1);
                                    } else {
                                        $columna[$departamento][$documento][$columna_planilla] -= $valor_movimiento;
                                    }

                                    if (!isset($total_deducido[$departamento][$documento])){
                                        $total_deducido[$departamento][$documento] = $valor_movimiento * (-1);
                                    } else {
                                        $total_deducido[$departamento][$documento] -= $valor_movimiento;
                                    }

                                }
                                if (!isset($total_devengado[$departamento][$documento])){
                                    $total_devengado[$departamento][$documento] = 0;
                                }
                            }

                            if (!isset($ingreso_empleado[$documento])) {
                                $ingreso_empleado[$documento] = $fecha_ingreso;
                            }

                            if (!isset($departamento_empleado[$departamento])) {
                                $departamento_empleado[$departamento] = $departamento;
                            }

                            ////////////////////////////////
                            if (!isset($dias_trabajados[$departamento][$documento]) && $dias_trabajados_periodo > 0 && $datos->tabla == 1) {

                                $dias_trabajados[$departamento][$documento] = $dias_trabajados_periodo;
                                $dias_diferencia[$departamento][$documento] = $dias_trabajados_periodo;
                            }

                            ////////////////////////////////
                            if (!isset($empleado[$departamento][$documento])) {
                                $empleado[$departamento][$documento] = $nombre_empleado;
                            }

                            if (!isset($salario[$departamento][$documento])) {
                                $salario[$departamento][$documento] = $salario_empleado;
                            }
                            ////////////////////////////////
                            if (!isset($codigo_contable_movimiento[$codigo_contable])) {
                                $codigo_contable_movimiento[$codigo_contable] = $codigo_contable;
                            }
                            ////////////////////////////////
                            if ($sentido == 'D') {
                                if (!isset($total_codigo[$codigo_contable])) {
                                    $total_codigo[$codigo_contable] = $valor_movimiento;
                                } else {
                                    $total_codigo[$codigo_contable] += $valor_movimiento;
                                }
                            } else {
                                if (!isset($total_codigo_credito[$codigo_contable])) {
                                    $total_codigo_credito[$codigo_contable] = $valor_movimiento;
                                } else {
                                    $total_codigo_credito[$codigo_contable] += $valor_movimiento;
                                }
                            }
                            ////////////////////////////////
                            if (!isset($total_sucursal[$codigo_sucursal])) {
                                if ($sentido == 'D') {
                                    $total_sucursal[$codigo_sucursal] = $valor_movimiento;
                                } else {
                                    $total_sucursal[$codigo_sucursal] = $valor_movimiento * (-1);
                                }
                            } else {
                                if ($sentido == 'D') {
                                    $total_sucursal[$codigo_sucursal] += $valor_movimiento;
                                } else {
                                    $total_sucursal[$codigo_sucursal] -= $valor_movimiento;
                                }
                            }
                            ////////////////////////////////
                            if ($concepto_transaccion_contable == '5' || $concepto_transaccion_contable == '6') {

                                $fecha = $forma_ano_generacion . $forma_mes_generacion . "31";
                                $condicion = "codigo_empresa = '$datos->codigo_empresa' AND documento_identidad_empleado ='$documento'";

                                if ($concepto_transaccion_contable == '5') {
                                    $condicion .= " AND fecha_ingreso = '$datos->fecha_ingreso_empresa' AND fecha_inicio_salud <='$fecha'";
                                    $consulta_entidad_empleado = SQL::seleccionar(array("entidades_salud_empleados"), array("codigo_entidad_salud AS codigo_entidad"), $condicion, "fecha_inicio_salud DESC", "", 0, 1);
                                } else {
                                    $condicion .= " AND fecha_ingreso = '$datos->fecha_ingreso_empresa' AND fecha_inicio_pension <='$fecha'";
                                    $consulta_entidad_empleado = SQL::seleccionar(array("entidades_pension_empleados"), array("codigo_entidad_pension AS codigo_entidad"), $condicion, "fecha_inicio_pension DESC", "", 0, 1);
                                }

                                if (SQL::filasDevueltas($consulta_entidad_empleado)) {

                                    $datos = SQL::filaEnObjeto($consulta_entidad_empleado);
                                    $entidad_empleado = $datos->codigo_entidad;

                                    $documento_tercero = SQL::obtenerValor("entidades_parafiscales", "documento_identidad_tercero", "codigo='$entidad_empleado'");
                                    $nombre_tercero = SQL::obtenerValor("menu_terceros", "NOMBRE_COMPLETO", "id='$documento_tercero'");

                                    if ($sentido == 'C') {

                                        if (!isset($tercero[$codigo_contable][$documento_tercero])) {
                                            $movimiento_tercero[$codigo_contable][$documento_tercero] = $valor_movimiento;
                                            $tercero[$codigo_contable][$documento_tercero] = " " . $documento_tercero . " " . $nombre_tercero;
                                        } else {

                                            $movimiento_tercero[$codigo_contable][$documento_tercero] += $valor_movimiento;
                                            $tercero[$codigo_contable][$documento_tercero] = " " . $documento_tercero . " " . $nombre_tercero;
                                        }
                                    } else {

                                        if (!isset($tercero_debito[$codigo_contable][$documento_tercero])) {

                                            $movimiento_tercero_debito[$codigo_contable][$documento_tercero] = $valor_movimiento;
                                        } else {

                                            $movimiento_tercero_debito[$codigo_contable][$documento_tercero] += $valor_movimiento;
                                        }
                                    }
                                }
                            }
                        }

                        // generar pdf
                        if(isset($registro[$codigo_sucursal])){

                            $total_devengado_departamento = 0;
                            $total_deducido_departamento  = 0;
                            $total_devengado_sucursal = 0;
                            $total_deducido_sucursal  = 0;

                            if ($forma_tipo_listado=="1"){
                                $archivo->Ln(4);
                                if ($contabilizado) {
                                    $texto_contabilizado = "";
                                } else {
                                    $texto_contabilizado = $textos["NO_CONTABILIZADO"];
                                }

                                $nombreSucursal          = SQL::obtenerValor("sucursales", "nombre", "codigo='$codigo_sucursal'");
                                $archivo->textoTitulo    = $textos["PLANILLA"] . " " . $nombreSucursal . " " . $textos["FECHA"] . ": " . $forma_ano_generacion . "/" . $forma_mes_generacion . " " . $periodo[$forma_periodo] . $texto_contabilizado;
                                $archivo->textoCabecera  = $textos["FECHA"]." ".date("Y-m-d H:i:s");
                                $usuario                 = SQL::obtenerValor("usuarios", "nombre", "usuario='$sesion_usuario'");
                                $archivo->textoPiePagina = $textos["USUARIO"] . " " . $usuario;
                                $archivo->AddPage();
                                $archivo->SetFont('Arial', '', 7);

                                $tituloColumnas = array(
                                    $textos["DOCUMENTO"],
                                    $textos["NOMBRE_EMPLEADO"],
                                    $textos["SALARIO"],
                                    $textos["DIAS_TRABAJADOS"],
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 1"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 2"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 3"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 4"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 5"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 6"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 7"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 8"),
                                    $textos["TOTAL_DEVENGADO"],
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 9"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 10"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 11"),
                                    SQL::obtenervalor("titulos_planillas","nombre","columna = 12"),
                                    $textos["TOTAL_DEDUCIDO"],
                                    $textos["NETO"]
                                );
                            } else {
                                $tituloColumnas = $textos["DOCUMENTO"].";".$textos["NOMBRE_EMPLEADO"].";".$textos["SALARIO"].";".
                                    $textos["DIAS_TRABAJADOS"].";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 1").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 2").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 3").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 4").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 5").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 6").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 7").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 8").";";
                                $tituloColumnas .= $textos["TOTAL_DEVENGADO"].";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 9").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 10").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 11").";";
                                $tituloColumnas .= SQL::obtenervalor("titulos_planillas","nombre","columna = 12").";";
                                $tituloColumnas .= $textos["NETO"].";";
                                $tituloColumnas .= $textos["TOTAL_DEVENGADO"]."\n";
                                fwrite($archivo, $tituloColumnas);
                            }

                            if ($forma_tipo_listado=="1"){
                                $anchoColumnas = array(17, 35, 17, 17, 17, 17,17,17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17);
                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                            }
                            $departamento_anterior = "";
                            $primer_ingreso = true;
                            $total_neto = false;
                            $total_columna = array();

                            foreach ($departamento_empleado AS $departamento_empresa){

                                if ($forma_tipo_listado=="1"){
                                    $archivo->SetFont('Arial', '', 7);
                                    $archivo->Ln(4);
                                    $archivo->Cell(17, 5, $departamento_empresa, 0, 0, "L", false);
                                    $archivo->Ln(2);
                                } else {
                                    $tituloColumnas = $departamento_empresa."\n";
                                    fwrite($archivo, $tituloColumnas);
                                }

                                $empleados = array();
                                $empleados = $empleado[$departamento_empresa];

                                foreach ($empleados AS $documento_identidad => $nombre_empleado) {

                                    for ($i = 1; $i <= 12; $i++) {
                                        if (!isset($columna[$departamento_empresa][$documento_identidad][$i])) {
                                            $columna[$departamento_empresa][$documento_identidad][$i] = 0;
                                        }
                                    }

                                    $neto = $columna[$departamento_empresa][$documento_identidad][1];
                                    $neto += $columna[$departamento_empresa][$documento_identidad][2];
                                    $neto += $columna[$departamento_empresa][$documento_identidad][3];
                                    $neto += $columna[$departamento_empresa][$documento_identidad][4];
                                    $neto += $columna[$departamento_empresa][$documento_identidad][5];
                                    $neto += $columna[$departamento_empresa][$documento_identidad][6];
                                    $neto += $columna[$departamento_empresa][$documento_identidad][7];
                                    $neto += $columna[$departamento_empresa][$documento_identidad][8];
                                    $neto -= $columna[$departamento_empresa][$documento_identidad][9];
                                    $neto -= $columna[$departamento_empresa][$documento_identidad][10];
                                    $neto -= $columna[$departamento_empresa][$documento_identidad][11];
                                    $neto -= $columna[$departamento_empresa][$documento_identidad][12];

                                    if (!isset($dias_trabajados[$departamento_empresa][$documento_identidad]) ||
                                            $dias_trabajados[$departamento_empresa][$documento_identidad] == 0
                                    ) {

                                        $fecha_ingreso = explode("-", $ingreso_empleado[$documento]);
                                        $ano_ingreso = $fecha_ingreso[0];
                                        $mes_ingreso = $fecha_ingreso[1];
                                        $dia_ingreso = $fecha_ingreso[2];

                                        $dias_diferencia[$departamento_empresa][$documento_identidad] = 0;

                                        if ($tipo_planilla == '2' || $tipo_planilla == '3') {
                                            if ($tipo_planilla == '2') {
                                                $dias = 15;
                                            } else {
                                                $dias = 7;
                                            }
                                        } else if ($tipo_planilla=='1'){
                                            $dias = 30;
                                        } else  if ($tipo_planilla=='4'){
                                            $dias = 180;
                                        }
                                        if ($ano_ingreso == $forma_ano_generacion && $mes_ingreso == $forma_mes_generacion) {

                                            if ($tipo_planilla == '2' || $tipo_planilla == '3') {

                                                if ($tipo_planilla == '2') {

                                                    if ($forma_periodo == '2') {

                                                        $dia_inicio_quincena = "01";
                                                        $dia_fin_quincena = 15;
                                                    } else {

                                                        $dia_inicio_quincena = 16;
                                                        if ($forma_mes_generacion == 2) {
                                                            if (($forma_ano_generacion % 4 == 0) && ($forma_ano_generacion % 100 != 0 || $forma_ano_generacion % 400 == 0)) {
                                                                $dia_fin_quincena = 29;
                                                            } else {
                                                                $dia_fin_quincena = 28;
                                                            }
                                                        } else {
                                                            $dia_fin_quincena = 30;
                                                        }
                                                    }
                                                } else {
                                                    $dia_inicio_quincena = "01";
                                                    $dias_fin_quincena = "07";
                                                }
                                            } else {
                                                $dia_inicio_quincena = "01";
                                                $dia_fin_quincena = 30;
                                            }
                                            $fecha_inicio_quincena = $forma_ano_generacion . "-" . $forma_mes_generacion . "-" . $dia_inicio_quincena;
                                            $fecha_fin_quincena = $forma_ano_generacion . "-" . $forma_mes_generacion . "-" . $dia_fin_quincena;

                                            if ($ingreso_empleado[$documento_identidad] > $fecha_inicio_quincena) {
                                                $diferencia = strtotime($ingreso_empleado[$documento]) - strtotime($fecha_inicio_quincena); //Hallo la diferencia de las fechas en segundos

                                                $dias_diferencia[$departamento_empresa][$documento] = $dias - $diferencia / (60 * 60 * 24); //Convierto la diferencia en dias

                                            }
                                        }

                                    }

                                    if($contador==1){

                                        $condicion_incapacidad  = " ano_generacion = '$forma_ano_generacion' AND mes_generacion = '$forma_mes_generacion'";
                                        $condicion_incapacidad .= " AND periodo_pago = '$forma_periodo' AND codigo_planilla = '$forma_codigo_planilla'";
                                        $condicion_incapacidad .= " AND tabla = 5 AND documento_identidad_empleado = '$documento_identidad' AND contabilizado!='2'";
                                        $consulta_incapacidad   = SQL::seleccionar(array("consulta_datos_planilla"), array("*"), $condicion_incapacidad);

                                        $dias_incapacitado = 0;
                                        if (SQL::filasDevueltas($consulta_incapacidad)) {
                                            while ($datos_dias_incapacitado = SQL::filaEnObjeto($consulta_incapacidad)) {
                                                $dias_incapacitado++;

                                            }
                                        }
                                        ////////////////// Tiempo no laborados///////////////////
                                        $condicion_tiempos  = "ano_generacion = '$forma_ano_generacion' AND mes_generacion = '$forma_mes_generacion'";
                                        $condicion_tiempos .= " AND periodo_pago = '$forma_periodo' AND codigo_planilla = '$forma_codigo_planilla'";
                                        $condicion_tiempos .= " AND tabla = '10' AND documento_identidad_empleado = '$documento_identidad' AND contabilizado!='2'";

                                        $consulta_tiempos  = SQL::seleccionar(array("consulta_datos_planilla"), array("*"), $condicion_tiempos);

                                        if (SQL::filasDevueltas($consulta_tiempos)) {
                                            while ($datos_dias_incapacitado = SQL::filaEnObjeto($consulta_tiempos)) {
                                                $dias_incapacitado++;

                                            }
                                        }
                                        /////////////////////////////////////////////////////////

                                        if ($dias_diferencia[$departamento_empresa][$documento_identidad] > 0) {
                                            $dias_trabajados[$departamento_empresa][$documento_identidad] = $dias_diferencia[$departamento_empresa][$documento_identidad] - $dias_incapacitado;
                                        } else {
                                            $dias_trabajados[$departamento_empresa][$documento_identidad] = $dias - $dias_incapacitado;

                                        }

                                        if ($dias_trabajados[$departamento_empresa][$documento_identidad] < 0) {
                                            $dias_trabajados[$departamento_empresa][$documento_identidad] = 0;

                                        }

                                    }

                                    if ($forma_tipo_listado=="1"){
                                        if($archivo->breakCell(7)){
                                            $archivo->AddPage();
                                            $archivo->SetFont('Arial', '', 7);
                                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                        }
                                        $archivo->SetFont('Arial', '', 7);
                                        $archivo->Ln(4);
                                        $nombre_empleado = substr($nombre_empleado, 0, 20);
                                        $archivo->Cell(17, 3, $documento_identidad, 0, 0, "L", false);
                                        $archivo->Cell(35, 3, $nombre_empleado, 0, 0, "L", false);
                                        $archivo->Cell(17, 3, number_format($salario[$departamento_empresa][$documento_identidad], 0), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, $dias_trabajados[$departamento_empresa][$documento_identidad], 0, 0, "C", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][1]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][2]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][3]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][4]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][5]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][6]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][7]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][8]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_devengado[$departamento_empresa][$documento_identidad]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][9]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][10]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][11]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($columna[$departamento_empresa][$documento_identidad][12]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_deducido[$departamento_empresa][$documento_identidad]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($neto, 0), 0, 0, "R", false);
                                    } else {
                                        $tituloColumnas = $documento_identidad.";";
                                        $tituloColumnas .= $nombre_empleado.";";
                                        $tituloColumnas .= (int)$salario[$departamento_empresa][$documento_identidad].";";
                                        $tituloColumnas .= (int)$dias_trabajados[$departamento_empresa][$documento_identidad].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][1].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][2].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][3].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][4].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][5].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][6].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][7].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][8].";";
                                        $tituloColumnas .= (int)$total_devengado[$departamento_empresa][$documento_identidad].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][9].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][10].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][11].";";
                                        $tituloColumnas .= (int)$columna[$departamento_empresa][$documento_identidad][12].";";
                                        $tituloColumnas .= (int)$total_deducido[$departamento_empresa][$documento_identidad].";";
                                        $tituloColumnas .= (int)$neto."\n";
                                        fwrite($archivo, $tituloColumnas);
                                    }
                                    for ($i = 1; $i <= 12; $i++) {
                                        if (!isset($total_columna[$i])) {
                                            $total_columna[$i] = $columna[$departamento_empresa][$documento_identidad][$i];
                                            $total_columna_fin[$i] = $columna[$departamento_empresa][$documento_identidad][$i];
                                        } else {
                                            $total_columna[$i] += $columna[$departamento_empresa][$documento_identidad][$i];
                                            $total_columna_fin[$i] += $columna[$departamento_empresa][$documento_identidad][$i];
                                        }

                                        if (!isset($total_sucursal_columna[$i])) {
                                            $total_sucursal_columna[$i] = $columna[$departamento_empresa][$documento_identidad][$i];
                                            $total_sucursal_fin[$i] = $columna[$departamento_empresa][$documento_identidad][$i];
                                        } else {
                                            $total_sucursal_columna[$i] += $columna[$departamento_empresa][$documento_identidad][$i];
                                            $total_sucursal_fin[$i] += $columna[$departamento_empresa][$documento_identidad][$i];
                                        }
                                    }
                                    if (!$total_neto) {
                                        $total_neto = $neto;
                                        $total_neto_fin = $neto;
                                    } else {
                                        $total_neto += $neto;
                                        $total_neto_fin += $neto;
                                    }
                                    if (!$total_neto_sucursal) {
                                        $total_neto_sucursal = $neto;
                                        $total_neto_sucursal_fin = $neto;
                                    } else {
                                        $total_neto_sucursal += $neto;
                                        $total_neto_sucursal_fin += $neto;
                                    }

                                    $total_devengado_departamento += $total_devengado[$departamento_empresa][$documento_identidad];
                                    $total_deducido_departamento  += $total_deducido[$departamento_empresa][$documento_identidad];
                                    $total_devengado_sucursal += $total_devengado[$departamento_empresa][$documento_identidad];
                                    $total_deducido_sucursal  += $total_deducido[$departamento_empresa][$documento_identidad];

                                }

                                if ($departamento_anterior != $departamento_empresa) {

                                    if ($forma_tipo_listado=="1"){
                                        $archivo->SetFont('Arial', '', 7);
                                        $archivo->Ln(3);
                                        $archivo->Cell(86, 3, $textos["TOTAL_DEPARTAMENTO"], 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[1]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[2]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[3]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[4]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[5]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[6]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[7]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[8]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_devengado_departamento), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[9]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[10]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[11]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_columna[12]), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_deducido_departamento), 0, 0, "R", false);
                                        $archivo->Cell(17, 3, number_format($total_neto, 0), 0, 0, "R", false);
                                    } else {
                                        $tituloColumnas = " ;";
                                        $tituloColumnas .= " ;";
                                        $tituloColumnas .= " ;";
                                        $tituloColumnas .= $textos["TOTAL_DEPARTAMENTO"].";";
                                        $tituloColumnas .= (int)$total_columna[1].";";
                                        $tituloColumnas .= (int)$total_columna[2].";";
                                        $tituloColumnas .= (int)$total_columna[3].";";
                                        $tituloColumnas .= (int)$total_columna[4].";";
                                        $tituloColumnas .= (int)$total_columna[5].";";
                                        $tituloColumnas .= (int)$total_columna[6].";";
                                        $tituloColumnas .= (int)$total_columna[7].";";
                                        $tituloColumnas .= (int)$total_columna[8].";";
                                        $tituloColumnas .= (int)$total_devengado_departamento.";";
                                        $tituloColumnas .= (int)$total_columna[9].";";
                                        $tituloColumnas .= (int)$total_columna[10].";";
                                        $tituloColumnas .= (int)$total_columna[11].";";
                                        $tituloColumnas .= (int)$total_columna[12].";";
                                        $tituloColumnas .= (int)$total_deducido_departamento.";";
                                        $tituloColumnas .= (int)$total_neto."\n";
                                        fwrite($archivo, $tituloColumnas);
                                    }

                                    $total_columna_fin = $total_columna;

                                    $total_devengado_departamento = 0;
                                    $total_deducido_departamento  = 0;

                                    $total_columna = array();
                                    $total_neto = false;
                                    $primer_ingreso = true;
                                }
                                $departamento_anterior = $departamento_empresa;
                            }

                            if ($forma_tipo_listado=="1"){
                                if($archivo->breakCell(5)){
                                    $archivo->AddPage();
                                }

                                $archivo->SetFont('Arial', '', 7);
                                $archivo->Ln(3);
                                $archivo->Cell(86, 3, $textos["TOTAL_SUCURSAL"], 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[1]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[2]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[3]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[4]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[5]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[6]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[7]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[8]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_devengado_sucursal), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[9]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[10]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[11]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_sucursal_columna[12]), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_deducido_sucursal), 0, 0, "R", false);
                                $archivo->Cell(17, 3, number_format($total_neto_sucursal), 0, 0, "R", false);
                            } else {
                                $tituloColumnas = " ;";
                                $tituloColumnas .= " ;";
                                $tituloColumnas .= " ;";
                                $tituloColumnas .= $textos["TOTAL_SUCURSAL"].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[1].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[2].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[3].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[4].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[5].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[6].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[7].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[8].";";
                                $tituloColumnas .= (int)$total_devengado_sucursal.";";
                                $tituloColumnas .= (int)$total_sucursal_columna[9].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[10].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[11].";";
                                $tituloColumnas .= (int)$total_sucursal_columna[12].";";
                                $tituloColumnas .= (int)$total_deducido_sucursal.";";
                                $tituloColumnas .= (int)$total_neto_sucursal."\n";
                                fwrite($archivo, $tituloColumnas);
                            }

                            $total_sucursal_fin = $total_sucursal_columna;
                            $total_devengado_sucursal = 0;
                            $total_deducido_sucursal  = 0;
                            $total_sucursal_columna = array();
                            $total_neto_sucursal = false;

                            if ($forma_tipo_listado=="1"){
                                $archivo->Ln(6);
                                $archivo->SetFont('Arial', 'B', 8);
                                $archivo->Cell(30, 3, $textos["CODIGO_CONTABLE"], 0, 0, "L", false);
                                $archivo->Cell(40, 3, $textos["DEBITO"], 0, 0, "R", false);
                                $archivo->Cell(40, 3, $textos["CREDITO"], 0, 0, "R", false);
                                $archivo->SetFont('Arial', '', 7);
                            } else {
                                $tituloColumnas = "\n";
                                fwrite($archivo,$tituloColumnas);
                                $tituloColumnas = $textos["CODIGO_CONTABLE"].";";
                                $tituloColumnas .= $textos["DEBITO"].";";
                                $tituloColumnas .= $textos["CREDITO"].";";
                                $tituloColumnas .= $textos["TERCERO"]."\n";
                                fwrite($archivo,$tituloColumnas);
                            }
                            foreach ($codigo_contable_movimiento AS $codigo_contable) {

                                $descripcion = SQL::obtenervalor("seleccion_plan_contable", "codigo_contable", "id='$codigo_contable'");
                                $descripcion = explode("|", $descripcion);
                                $descripcion = $descripcion[0];
                                if (!isset($total_codigo[$codigo_contable])) {
                                    $total_codigo[$codigo_contable] = 0;
                                }
                                if (!isset($total_codigo_credito[$codigo_contable])) {
                                    $total_codigo_credito[$codigo_contable] = 0;
                                }

                                if (isset($movimiento_tercero[$codigo_contable]) || isset($movimiento_tercero_debito[$codigo_contable])) {

                                    if (isset($movimiento_tercero[$codigo_contable])) {
                                        $tercero_codigo_contable = $movimiento_tercero[$codigo_contable];
                                    } else if (isset($movimiento_tercero_debito[$codigo_contable])) {
                                        $tercero_codigo_contable = $movimiento_tercero_debito[$codigo_contable];
                                    }

                                    foreach ($tercero_codigo_contable AS $documento_tercero => $valor) {

                                        if (!isset($movimiento_tercero_debito[$codigo_contable][$documento_tercero])) {
                                            $movimiento_tercero_debito[$codigo_contable][$documento_tercero] = 0;
                                        }

                                        if (!isset($movimiento_tercero[$codigo_contable][$documento_tercero])) {
                                            $movimiento_tercero[$codigo_contable][$documento_tercero] = 0;
                                            $datos_tercero = "";
                                        } else {
                                            $datos_tercero = $tercero[$codigo_contable][$documento_tercero];
                                        }

                                        if ($forma_tipo_listado=="1"){
                                            if($archivo->breakCell(5)){
                                                $archivo->AddPage();
                                                $archivo->Ln(4);
                                                $archivo->Cell(30, 3, $textos["CODIGO_CONTABLE"], 0, 0, "L", false);
                                                $archivo->Cell(40, 3, $textos["DEBITO"], 0, 0, "R", false);
                                                $archivo->Cell(40, 3, $textos["CREDITO"], 0, 0, "R", false);
                                            }

                                            $archivo->Ln(4);
                                            $archivo->Cell(30, 3, $descripcion, 0, 0, "L", false);
                                            $archivo->Cell(40, 3, number_format($movimiento_tercero_debito[$codigo_contable][$documento_tercero], 0), 0, 0, "R", false);
                                            $archivo->Cell(40, 3, number_format($movimiento_tercero[$codigo_contable][$documento_tercero], 0), 0, 0, "R", false);
                                            $archivo->Cell(40, 3, $datos_tercero, 0, 0, "I", false);
                                        } else {

                                            $tituloColumnas = $descripcion.";";
                                            $tituloColumnas .= (int)$movimiento_tercero_debito[$codigo_contable][$documento_tercero].";";
                                            $tituloColumnas .= (int)$movimiento_tercero[$codigo_contable][$documento_tercero].";";
                                            $tituloColumnas .= $datos_tercero."\n";
                                            fwrite($archivo,$tituloColumnas);
                                        }
                                    }
                                } else {
                                    if ($forma_tipo_listado=="1"){
                                        if($archivo->breakCell(5)){
                                            $archivo->AddPage();
                                            $archivo->Ln(4);
                                            $archivo->Cell(30, 3, $textos["CODIGO_CONTABLE"], 0, 0, "L", false);
                                            $archivo->Cell(40, 3, $textos["DEBITO"], 0, 0, "R", false);
                                            $archivo->Cell(40, 3, $textos["CREDITO"], 0, 0, "R", false);
                                        }
                                        $archivo->Ln(4);
                                        $archivo->Cell(30, 3, $descripcion, 0, 0, "L", false);
                                        $archivo->Cell(40, 3, number_format($total_codigo[$codigo_contable], 0), 0, 0, "R", false);
                                        $archivo->Cell(40, 3, number_format($total_codigo_credito[$codigo_contable], 0), 0, 0, "R", false);
                                    } else {
                                        $tituloColumnas = $descripcion.";";
                                        $tituloColumnas .= (int)$total_codigo[$codigo_contable].";";
                                        $tituloColumnas .= (int)$total_codigo_credito[$codigo_contable]."\n";
                                        fwrite($archivo,$tituloColumnas);
                                    }
                                }
                            }

                            if ($forma_tipo_listado=="1"){
                                $archivo->Ln(4);
                                $archivo->SetFont('Arial', 'B', 8);
                                $archivo->Cell(70, 3, $textos["TOTAL_PAGAR"], 0, 0, "R", false);
                                $archivo->Cell(40, 3, number_format($total_sucursal[$codigo_sucursal], 0), 0, 0, "R", false);
                            } else {
                                $tituloColumnas = "\n";
                                fwrite($archivo,$tituloColumnas);

                                $tituloColumnas = $textos["TOTAL_PAGAR"].";";
                                $tituloColumnas .= (int)$total_sucursal[$codigo_sucursal]."\n";
                                fwrite($archivo,$tituloColumnas);
                            }
                            $genero_pdf = true;
                        }
                    }
                }
                $contador++;
            }
        }

        if ($forma_tipo_listado=="1"){
            $archivo->Output($nombreArchivo, "F");
        } else {
            fclose($archivo);
        }
        $datos_archivo = array(
            "codigo_sucursal" => $sesion_sucursal,
            "consecutivo"     => $consecutivo,
            "nombre"          => $nombre
        );
        SQL::insertar("archivos", $datos_archivo);
        $id_archivo = $sesion_sucursal."|".$consecutivo;
        $ruta_archivo = HTTP::generarURL("DESCARCH")."&id=".$id_archivo."&temporal=1";
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;

    if ($genero_pdf && !$error) {
        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        $respuesta[2] = $ruta_archivo;
    } else if (!$genero_pdf && !$error){
        $error        = true;
        $mensaje      = $textos["SIN_INFORMACION"];

        $respuesta    = array();
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    }
    HTTP::enviarJSON($respuesta);
}
?>
