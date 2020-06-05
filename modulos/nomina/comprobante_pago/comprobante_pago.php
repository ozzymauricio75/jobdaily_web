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

if(isset($url_verificar)){
    $condicion_extra = "id_sucursal IN (".$url_sucursales.")";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}

if (!empty($url_generar)) {

    $error           = "";
    $titulo          = $componente->nombre;
    $error_continuar = false;

    $empresa   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."' AND codigo>0");

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

    $listaSucursales = array();
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
        $listaSucursales = implode(",",$listaSucursales);
    } else {
        $error_continuar = 0;
    }

    $consulta_planillas = SQL::seleccionar(array("planillas"),array("*"),"codigo != 0");
    if (SQL::filasDevueltas($consulta_planillas)){

        $planillas[0] = '';

        while ($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }

    } else {
        $error_continuar = 1;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla != 0");
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

        $tipo_listado = array(
            "1" => "PDF",
            "2" => "PLANO"
        );

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
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
                HTML::campoTextoCorto("selector1", $textos["EMPLEADO"], 40, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onfocus" => "autocompletableListaPlanilla(this);", "onKeyUp" => "limpiar_oculto_Autocompletable(this, documento_identidad)")).
                HTML::campoOculto("documento_identidad","").
                HTML::campoOculto("listaSucursales",$listaSucursales)
            ),
            array(
                HTML::campoOculto("tipo_listado",1)
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

        $consecutivo = SQL::obtenerValor("archivos","MAX(consecutivo)","codigo_sucursal='".$sesion_sucursal."'");
        if ($consecutivo){
            $consecutivo++;
        } else {
            $consecutivo = 1;
        }
        $consecutivo = (int)$consecutivo;
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            if ($forma_tipo_listado == 1){
                $nombre = $sesion_sucursal.$cadena.".pdf";
            } else {
                $nombre = $sesion_sucursal.$cadena;
            }
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $fechaReporte = date("Y-m-d H:i:s");
        if ($forma_tipo_listado == 1){
            $archivo = new PDF("P","mm","Letter");
            $archivo->textoPiePagina = "";
            $genero_pdf = false;
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
        $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$forma_codigo_planilla."'");

        $genero_pdf     = false;
        $tituloColumnas = array($textos["NOMBRE_TRANSACCION"],$textos["TOTAL_DEVENGADO"],$textos["TOTAL_DEDUCIDO"]);
        $anchoColumnas  = array(61,35,30);
        include("clases/escribir.php");

        $numero_linea = 1;
        $enter = chr(13);

        $partePagina     = 1;
        $contadorPaginas = 1;

        foreach ($forma_sucursales AS $codigo_sucursal){

            if ($numero_linea > 33 && $numero_linea <67){
                for($i=$numero_linea;$i++;$i<=66){
                    $registro = $enter;
                    $numero_linea = escribir_plano($archivo,$textos,$numero_linea,$registro,"","",$fechaReporte,$codigo_sucursal,"");

                }
                $numero_linea = 1;
            } else if($numero_linea > 1){
                for($i=$numero_linea;$i++;$i<=33){
                    $registro = $enter;
                    $numero_linea = escribir_plano($archivo,$textos,$numero_linea,$registro,"","",$fechaReporte,$codigo_sucursal,"");
                }
                $numero_linea = 34;
            }

            $condicion_planilla  = "ano_generacion = '".$forma_ano_generacion."' ";
            $condicion_planilla .= "AND mes_generacion = '".$forma_mes_generacion."' ";
            $condicion_planilla .= "AND periodo_pago = '".$forma_periodo."' ";
            $condicion_planilla .= "AND codigo_planilla = '".$forma_codigo_planilla."' ";
            $condicion_planilla .= "AND codigo_sucursal = '".$codigo_sucursal."' ";
            $condicion_planilla .= "AND columna_planilla >0";

            if(!empty($forma_documento_identidad)){
                $condicion_planilla .= " AND documento_identidad_empleado = '".$forma_documento_identidad."'";
            }

            $consulta_planilla = SQL::seleccionar(array("consulta_datos_planilla"),array("*","SUM(valor_movimiento) AS valor"),$condicion_planilla,"documento_identidad_empleado,codigo_transaccion_contable,sentido","departamento_empresa,codigo_sucursal,documento_identidad_empleado,codigo_transaccion_contable,fecha_registro,sentido");

            if (SQL::filasDevueltas($consulta_planilla)) {

                $cedulaActual    = "";
                $total_deducido  = 0;
                $total_devengado = 0;

                while ($datos = SQL::filaEnObjeto($consulta_planilla)) {

                    if($cedulaActual != $datos->documento_identidad_empleado){

                        if($total_deducido != 0 || $total_devengado != 0){

                            if($forma_tipo_listado == 1){
                                $archivo->Ln(3);
                                $archivo->SetFont('Courier', '', 10);
                                $archivo->Cell(60, 3, $textos["TOTALES"], 0, 0, "R", false,"",true);
                                $archivo->SetFont('Courier', '', 10);
                                $archivo->Cell(35, 3, number_format($total_devengado,0), 0, 0, "R", false,"",true);
                                $archivo->Cell(30, 3, number_format($total_deducido,0), 0, 0, "R", false,"",true);
                                $archivo->Ln(3);
                                $archivo->SetFont('Courier', '', 10);
                                $archivo->Cell(60, 3, $textos["NETO"], 0, 0, "R", false,"",true);
                                $archivo->SetFont('Courier', '', 10);
                                $archivo->Cell(35, 3, number_format($total_devengado-$total_deducido,0), 0, 0, "R", false,"",true);
                                $archivo->Ln(3);
                            } else {

                                $espacio = " ";
                                for($i=1;$i<=22;$i++){
                                    $espacio .= " ";
                                }
                                $devengado = number_format($total_devengado,0);
                                $longitud_devengado = strlen($devengado);
                                $espacio2 = " ";
                                if ($longitud_devengado < 15){
                                    $diferencia = 15 - $longitud_devengado;
                                    for($i=1;$i<=$diferencia;$i++){
                                        $espacio2 .= " ";
                                    }
                                }
                                $deducido  = number_format($total_deducido,0);
                                $registro = $espacio.$textos["TOTALES"]."    ".$devengado.$espacio2.$deducido.$enter;
                                $numero_linea = escribir_plano($archivo,$textos,$numero_linea,$registro,$cedulaActual,$nombreActual,$fechaReporte,$codigo_sucursal,"");

                                $espacio = " ";
                                for($i=1;$i<=22;$i++){
                                    $espacio .= " ";
                                }
                                $neto = number_format($total_devengado-$total_deducido,0);
                                $registro = $espacio.$textos["NETO"]."     ".$neto.$enter;
                                $numero_linea = escribir_plano($archivo,$textos,$numero_linea,$registro,$cedulaActual,$nombreActual,$fechaReporte,$codigo_sucursal,"");
                                if ($numero_linea > 33 && $numero_linea <67){
                                    for($i=$numero_linea;$i<=60;$i++){
                                        $numero_linea = escribir_plano($archivo,$textos,$i,$enter,"",$nombreActual,$fechaReporte,$codigo_sucursal,"");
                                    }
                                    $numero_linea++;
                                } else {
                                    for($i=$numero_linea;$i<=27;$i++){
                                        $numero_linea = escribir_plano($archivo,$textos,$i,$enter,"",$nombreActual,$fechaReporte,$codigo_sucursal,"");
                                    }
                                    $numero_linea++;
                                }
                            }
                            $total_deducido  = 0;
                            $total_devengado = 0;
                        }

                        if ($forma_tipo_listado==1){
                            ////////// CABECERA //////////
                            if($partePagina == 2){
                                $archivo->SetXY(10,130);
                                $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$codigo_sucursal'");
                                $nombre_empresa = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
                                $nit            = SQL::obtenerValor("empresas","documento_identidad_tercero","codigo='$codigo_empresa'");
                                $direccion      = SQL::obtenerValor("terceros","direccion_principal","documento_identidad='$nit'");
                                $telefono       = SQL::obtenerValor("terceros","telefono_principal","documento_identidad='$nit'");
                                $direccion      = $direccion." ".$textos["TELEFONO"]." ".$telefono;
                                $nit            = $textos["NIT"]." ".$nit;
                                $pesos          = array(3,7,13,17,19,23,29,37,41,43,47,53,59,67,71);
                                $suma           = 0;
                                $ciclo          = 0;
                                $resto          = 0;
                                $digito         = 0;
                                for ($i = strlen($nit); $i > 0; $i--) {
                                    $suma += $nit[$i-1] * $pesos[$ciclo];
                                    $ciclo++;
                                }

                                $resto = $suma % 11;
                                if ($resto == 0 || $resto == 1 ) {
                                    $digito = $resto;
                                } else {
                                    $digito = 11 - $resto;
                                }
                                $nit            = $nit."-".$digito;
                                $titulo         = $textos["LISTCOPA"]." ".$periodo[$forma_periodo]." ".$forma_ano_generacion."/".$forma_mes_generacion;
                                $archivo->Cabecera(132,$nombre_empresa,$nit,$direccion,$titulo);
                                $archivo->SetFont('Courier','', 10);
                                $archivo->Cell(190, 6, $datos->documento_identidad_empleado." - ".$datos->nombre_empleado, 0, 0, "L", false,"",true);
                                $archivo->Ln(5);
                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas,0,"L",false);
                                $archivo->Ln(4);
                                $partePagina = 1;
                            }else{
                                $nombreSucursal          = SQL::obtenerValor("sucursales", "nombre", "codigo='".$codigo_sucursal."'");
                                $archivo->textoTitulo    = $textos["LISTCOPA"] . " - " . $periodo[$forma_periodo] . " " . $forma_ano_generacion . "/" . $forma_mes_generacion;
                                $archivo->textoCabecera  = $textos["FECHA_GENERACION"]." ".date("Y-m-d");
                                $usuario                 = SQL::obtenerValor("usuarios", "nombre", "usuario='".$sesion_usuario."'");
                                $archivo->textoPiePagina = $textos["USUARIO"] . " " . $usuario;
                                $archivo->AddPage('','',false,false);
                                $codigo_empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='$codigo_sucursal'");
                                $nombre_empresa = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
                                $nit            = SQL::obtenerValor("empresas","documento_identidad_tercero","codigo='$codigo_empresa'");
                                $direccion      = SQL::obtenerValor("terceros","direccion_principal","documento_identidad='$nit'");
                                $telefono       = SQL::obtenerValor("terceros","telefono_principal","documento_identidad='$nit'");
                                $direccion      = $direccion." ".$textos["TELEFONO"]." ".$telefono;
                                $nit            = $textos["NIT"]." ".$nit;
                                $pesos          = array(3,7,13,17,19,23,29,37,41,43,47,53,59,67,71);
                                $suma           = 0;
                                $ciclo          = 0;
                                $resto          = 0;
                                $digito         = 0;
                                for ($i = strlen($nit); $i > 0; $i--) {
                                    $suma += $nit[$i-1] * $pesos[$ciclo];
                                    $ciclo++;
                                }

                                $resto = $suma % 11;
                                if ($resto == 0 || $resto == 1 ) {
                                    $digito = $resto;
                                } else {
                                    $digito = 11 - $resto;
                                }
                                $nit            = $nit."-".$digito;
                                $titulo         = $textos["LISTCOPA"]." ".$periodo[$forma_periodo]." ".$forma_ano_generacion."/".$forma_mes_generacion;
                                $archivo->Cabecera(10,$nombre_empresa,$nit,$direccion,$titulo);
                                $archivo->SetFont('Courier', '', 10);
                                $archivo->Cell(190, 6, $datos->documento_identidad_empleado." - ".$datos->nombre_empleado, 0, 0, "L", false,"",true);
                                $archivo->Ln(5);
                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas,0,"L",false);
                                $archivo->Ln(4);
                                $partePagina = 2;
                            }
                            //////////////////////////////
                        } else {
                            $planilla     = $textos["LISTCOPA"] . " - " . $periodo[$forma_periodo] . " " . $forma_ano_generacion . "/" . $forma_mes_generacion;
                            $numero_linea = escribir_plano($archivo,$textos,67,$enter,$datos->documento_identidad_empleado,$datos->nombre_empleado,$fechaReporte,$codigo_sucursal,$planilla);
                        }
                    }

                    if($forma_tipo_listado==1){
                        if($archivo->breakCell(5)){
                            $archivo->AddPage('','',false,false);
                            $archivo->SetFont('Courier', '', 10);
                            $archivo->Cell(190, 6, $datos->documento_identidad_empleado." - ".$datos->nombre_empleado, 0, 0, "L", false,"",true);
                            $archivo->Ln(5);
                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas,0,"L",false);
                            $archivo->Ln(4);
                            $partePagina = 1;
                        }
                    }

                    if((int)$datos->codigo_transaccion_contable != 0){
                        if((int)$datos->tabla != 9){
                            $transaccion        = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo = '".$datos->codigo_transaccion_contable."'");
                            $codigo_transaccion = str_pad($datos->codigo_transaccion_contable,4,"0",STR_PAD_LEFT);

                        }else{
                            $codigo_transaccion = (int)SQL::obtenerValor("movimiento_cuenta_por_pagar_tercero","codigo_transaccion_contable","documento_identidad_empleado = '".$datos->documento_identidad_empleado."' AND codigo_sucursal = '".$datos->codigo_sucursal."' AND obligacion = '".$datos->obligacion."' AND fecha_pago_planilla= '".$datos->fecha_pago_planilla."'");
                            $transaccion        = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo = '".$codigo_transaccion."'");
                            $codigo_transaccion = str_pad($codigo_transaccion,4,"0",STR_PAD_LEFT);
                        }

                        if($datos->sentido == 'D'){
                            $valor_debito    = number_format($datos->valor,0);
                            $valor_credito   = "";
                            $total_devengado+= round($datos->valor);
                        }else{
                            $valor_credito  = number_format($datos->valor,0);
                            $valor_debito   = "";
                            $total_deducido+= round($datos->valor);
                        }

                        if ($forma_tipo_listado==1){
                            $archivo->SetFont('Courier', '', 10);
                            $archivo->Cell(60, 3, $codigo_transaccion." ".$transaccion, 0, 0, "L", false,"",true);
                            $archivo->Cell(35, 3, $valor_debito, 0, 0, "R", false,"",true);
                            $archivo->Cell(30, 3, $valor_credito, 0, 0, "R", false,"",true);
                            $archivo->Ln(3);
                            if($archivo->getY()>=130 && $partePagina == 2){
                                $partePagina == 1;
                            }
                        } else {
                            $transaccion = $codigo_transaccion." ".$transaccion;
                            $transaccion = substr($transaccion,0,32);
                            $longitud_transaccion = strlen($transaccion);
                            $espacio = " ";
                            if ($longitud_transaccion < 32){
                                $diferencia = 32 - $longitud_transaccion;
                                for ($i=0;$i<$diferencia;$i++){
                                    $espacio .=" ";
                                }
                            }
                            $espacio2 = " ";
                            $longitud_debito = strlen($valor_debito);
                            if ($longitud_debito<15){
                                $diferencia = 15 - $longitud_debito;
                                for($i=1;$i<=$diferencia;$i++){
                                    $espacio2 .= " ";
                                }
                            }
                            $registro     = $transaccion.$espacio.$valor_debito.$espacio2.$valor_credito.$enter;
                            $numero_linea = escribir_plano($archivo,$textos,$numero_linea,$registro,$datos->documento_identidad_empleado,$datos->nombre_empleado,$fechaReporte,$codigo_sucursal,"");
                        }
                    }
                    $cedulaActual = $datos->documento_identidad_empleado;
                    $nombreActual = $datos->nombre_empleado;
                    $contadorPaginas++;
                }

                if($total_deducido != 0 || $total_devengado != 0){
                    if ($forma_tipo_listado==1){
                        $archivo->Ln(3);
                        $archivo->SetFont('Courier', '', 10);
                        $archivo->Cell(60, 3, $textos["TOTALES"], 0, 0, "R", false,"",true);
                        $archivo->SetFont('Courier', '', 10);
                        $archivo->Cell(35, 3, number_format($total_devengado,0), 0, 0, "R", false,"",true);
                        $archivo->Cell(30, 3, number_format($total_deducido,0), 0, 0, "R", false,"",true);
                        $archivo->Ln(3);
                        $archivo->SetFont('Courier', '', 10);
                        $archivo->Cell(60, 3, $textos["NETO"], 0, 0, "R", false,"",true);
                        $archivo->SetFont('Courier', '', 10);
                        $archivo->Cell(35, 3, number_format($total_devengado-$total_deducido,0), 0, 0, "R", false,"",true);
                        $archivo->Ln(3);
                        $total_deducido  = 0;
                        $total_devengado = 0;
                    } else {
                        $espacio = " ";
                        for($i=1;$i<=22;$i++){
                            $espacio .= " ";
                        }
                        $devengado = number_format($total_devengado,0);
                        $longitud_devengado = strlen($devengado);
                        $espacio2 = " ";
                        if ($longitud_devengado < 15){
                            $diferencia = 15 - $longitud_devengado;
                            for($i=1;$i<=$diferencia;$i++){
                                $espacio2 .= " ";
                            }
                        }
                        $deducido = number_format($total_deducido,0);
                        $registro = $espacio.$textos["TOTALES"]."    ".$devengado.$espacio2.$deducido.$enter;
                        $numero_linea = escribir_plano($archivo,$textos,$numero_linea,$registro,$cedulaActual,$nombreActual,$fechaReporte,$codigo_sucursal,"");

                        $espacio = " ";
                        for($i=1;$i<=22;$i++){
                            $espacio .= " ";
                        }
                        $neto = number_format($total_devengado-$total_deducido,0);
                        $registro = $espacio.$textos["NETO"]."     ".$neto.$enter;
                        $numero_linea = escribir_plano($archivo,$textos,$numero_linea,$registro,$cedulaActual,$nombreActual,$fechaReporte,$codigo_sucursal,"");

                        $total_deducido  = 0;
                        $total_devengado = 0;

                        if ($numero_linea > 33 && $numero_linea <66){
                            for($i=$numero_linea;$i<=60;$i++){
                                $numero_linea = escribir_plano($archivo,$textos,$i,$enter,"",$nombreActual,$fechaReporte,$codigo_sucursal,"");
                            }
                            $numero_linea++;
                        } else {
                            for($i=$numero_linea;$i<=27;$i++){
                                $numero_linea = escribir_plano($archivo,$textos,$i,$enter,"",$nombreActual,$fechaReporte,$codigo_sucursal,"");
                            }
                            $numero_linea++;
                        }
                    }
                }
                $genero_pdf = true;
            }
        }

        if ($forma_tipo_listado==1){
            $archivo->Output($nombreArchivo, "F",false);
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
