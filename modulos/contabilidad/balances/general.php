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
* Deberia haber recibido una copia de la Licencia Publica General
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $consulta         = SQL::seleccionar(array("sucursales"), array("*"), "codigo != '0'");
    $sucursales_ver   = SQL::filasDevueltas($consulta);

    if($sucursales_ver==0){
        $error     = $textos["ERROR_SUCURSALES_VACIAS"];
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

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        // Definición de pestaña general
        $formularios["PESTANA_BALANCE_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*fecha[0]", $textos["FECHA_BALANCE_GENERAL"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_BALANCE_GENERAL1"], "class" => "selectorFecha"))
            ),
            array(
                HTML::campoTextoCorto("*fecha[1]", $textos["FECHA_BALANCE_GENERAL"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_BALANCE_GENERAL2"], "class" => "selectorFecha"))
            ),
            array(
                HTML::campoTextoCorto("*fecha[2]", $textos["FECHA_BALANCE_GENERAL"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_BALANCE_GENERAL3"], "class" => "selectorFecha"))
            ),
            array(
                HTML::listaSeleccionSimple("nivel_detalle", $textos["NIVEL_DETALLE"], $nivelDetalle, 5, array("title" => $textos["AYUDA_NIVEL_DETALLE"], "onChange" => "activarManejoDetalles(this)"))
            ),
            array (
                HTML::contenedor(
                    (
                        HTML::marcaChequeo("detalla_auxiliares", $textos["DETALLA_AUXILIARES"], "1", false, array("title" => $textos["AYUDA_DETALLA_AUXILIARES"]))
                        .HTML::marcaChequeo("detalla_terceros", $textos["DETALLA_TERCEROS"], "1", false, array("title" => $textos["AYUDA_DETALLA_TERCEROS"]))
                    ), array("id" => "detalles_balance")
                )
            ), array (
                HTML::boton("boton_balance_general", $textos["GENERAR"], "imprimirItem('0');", "aceptar")
            )
        );
        $contenido = HTML::generarPestanas($formularios);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Exportar los datos
} elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    $datos_incompletos = false;

    if (!isset($forma_sucursales)){
        $error             = true;
        $mensaje           = $textos["ERROR_SUCURSALES"];
        $datos_incompletos = true;
    } else {

        $detalla_tercero = false;
        $id_tercero      = "";
        if(isset($forma_detalla_terceros) && $forma_nivel_detalle==5){
            $detalla_tercero = true;
            $id_tercero      = ", documento_identidad_tercero";
        }

        $detalla_auxiliar = false;
        $id_auxiliar      = "";
        if(isset($forma_detalla_auxiliares) && $forma_nivel_detalle==5){
            $detalla_auxiliar = true;
            $id_auxiliar      = ", codigo_empresa_auxiliar, codigo_anexo_contable, codigo_auxiliar_contable";
        }

        $fecha = array_unique($forma_fecha);
        sort($fecha);

        // Variable para definir cuantas fechas seran cargadas para hacer la consulta SQL
        $cantidad_fechas = 0;
        // Variable para asignar a $cantidad_fechas cuando $cantidad_fechas sea igual a cero
        $numero_consultas = 0;
        foreach ($fecha AS $fecha_ciclo){
            $cantidad_fechas++;
            $numero_consultas++;
        }

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $cargaPdf = 0;
        $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];

        $fechaReporte = date("Y-m-d");
        $archivo      = new PDF("P","mm","Letter");

        $sucursales_reporte = array();

        foreach ($forma_sucursales AS $sucursal) {

            if (!isset($sucursales_reporte[$forma_consolidar[$sucursal]])){
                $sucursales_reporte[$forma_consolidar[$sucursal]] = $sucursal.",";
            } else {
                $sucursales_reporte[$forma_consolidar[$sucursal]] .= $sucursal.",";
            }
        }

        include("clases/balances.php");
        $cargar_pdf = false;

        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            $condicion_sucursal   = trim($condicion_sucursal, ",");
            $consolidadas         = explode(",", $condicion_sucursal);
            $cantidad_fechas      = $numero_consultas; // Se asigna este valor porque $cantidad_fechas va disminuyendo de 1 en 1 en el while y tiene q empezar siempre con el mismo valor inicial
            $cantidad_movimientos = $cantidad_fechas * 2;//Se multiplica por dos para generar movimiento por tipo_cuenta 1 y 3
            $tipo_cuenta          = "1";// Cuenta de balance
            $tipo_cuenta_balance  = array();
            $cuenta_balance       = array();
            $separa_movimiento    = false;
            $nombre_cuentas       = array();
            $saldo_balance        = array();
            $auxiliar_cuenta      = array();
            $tercero_cuenta       = array();
            $total_mayor          = array();

            while ($cantidad_movimientos > 0) {
                $tipo_cuenta_anterior = "";
                $fecha_balance = $fecha[($cantidad_fechas - 1)];
                $cantidad_fechas--;
                if ($cantidad_fechas==0){
                    $cantidad_fechas = $numero_consultas;
                }

                if ($cantidad_movimientos <= $numero_consultas){
                    $tipo_cuenta = "3";
                }

                $cantidad_movimientos--;
                $tablas         = array("movimientos_contables_consolidados");
                $fecha_consulta = $fecha_balance." 23:59:59";
                $condicion      = "estado = 1 AND fecha_contabilizacion <= '".$fecha_consulta."' AND codigo_sucursal_genera IN (".$condicion_sucursal.") AND tipo_cuenta='".$tipo_cuenta."'";
                $datos          = array(
                    "codigo_contable",
                    "descripcion_cuenta",
                    "tipo_cuenta",
                    "codigo_empresa_auxiliar",
                    "codigo_anexo_contable",
                    "codigo_auxiliar_contable",
                    "sentido_cuenta",
                    "sentido_movimiento",
                    "documento_identidad_tercero",
                    "codigo_sucursal_genera",
                    "fecha_contabilizacion",
                    "SUM(valor) AS valor",
                    "SUM(valor_base1) AS valor_base1",
                    "SUM(valor_base2) AS valor_base2"
                );

                $agrupamiento = "codigo_contable, sentido_cuenta, sentido_movimiento".$id_auxiliar.$id_tercero;
                $ordenamiento = "codigo_contable ASC";

                $consulta_movimiento  = SQL::seleccionar($tablas, $datos, $condicion, $agrupamiento, $ordenamiento);

                if (SQL::filasDevueltas($consulta_movimiento)){

                    while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento)) {

                        $tipo_cuenta        = $datos_movimiento->tipo_cuenta;
                        $codigo_contable    = $datos_movimiento->codigo_contable;
                        $sentido_cuenta     = $datos_movimiento->sentido_cuenta;
                        $sentido_movimiento = $datos_movimiento->sentido_movimiento;
                        $saldo              = $datos_movimiento->valor;
                        $tercero            = $datos_movimiento->documento_identidad_tercero;
                        $auxiliar           = $datos_movimiento->codigo_empresa_auxiliar.'|'.$datos_movimiento->codigo_anexo_contable.'|'.$datos_movimiento->codigo_auxiliar_contable;
                        $longitud_cuenta    = strlen($codigo_contable);

                        if ($forma_nivel_detalle==5){

                            $cuenta_movimiento = $codigo_contable;
                            $cuenta_movimiento = "'".$cuenta_movimiento."'";

                            if ($detalla_auxiliar && $auxiliar>0){

                                if (!isset($auxiliar_cuenta[$cuenta_movimiento][$auxiliar][$fecha_balance])){
                                    $descripcion = SQL::obtenerValor("buscador_auxiliares_contables","descripcion","id='".$auxiliar."'");
                                    $nombre_auxiliar[$auxiliar] = $descripcion;
                                    if ($sentido_cuenta != $sentido_movimiento){
                                        $auxiliar_cuenta[$cuenta_movimiento][$auxiliar][$fecha_balance] = $saldo * (-1);
                                    } else {
                                        $auxiliar_cuenta[$cuenta_movimiento][$auxiliar][$fecha_balance] = $saldo;
                                    }
                                } else {
                                    if ($sentido_cuenta != $sentido_movimiento){
                                        $auxiliar_cuenta[$cuenta_movimiento][$auxiliar][$fecha_balance] -= $saldo;
                                    } else {
                                        $auxiliar_cuenta[$cuenta_movimiento][$auxiliar][$fecha_balance] += $saldo;
                                    }
                                }
                            }
                            if ($detalla_tercero){

                                $maneja_saldos_tercero = SQL::obtenerValor("plan_contable","maneja_tercero","codigo_contable='".$codigo_contable."'");
                                if ($maneja_saldos_tercero==1){
                                    if (!isset($tercero_cuenta[$cuenta_movimiento][$tercero][$fecha_balance])){
                                        if ($tercero==0){
                                            $nombre_completo = "Sin nombre";
                                        } else {
                                            $nombre_completo = SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='".$tercero."'");
                                        }
                                        $nombre_tercero[$tercero] = $nombre_completo;
                                        if ($sentido_cuenta != $sentido_movimiento){
                                            $tercero_cuenta[$cuenta_movimiento][$tercero][$fecha_balance] = $saldo * (-1);
                                        } else {
                                            $tercero_cuenta[$cuenta_movimiento][$tercero][$fecha_balance] = $saldo;
                                        }
                                    } else {
                                        if ($sentido_cuenta != $sentido_movimiento){
                                            $tercero_cuenta[$cuenta_movimiento][$tercero][$fecha_balance] -= $saldo;
                                        } else {
                                            $tercero_cuenta[$cuenta_movimiento][$tercero][$fecha_balance] += $saldo;
                                        }
                                    }
                                }
                            }
                            $cuenta_movimiento = $codigo_contable;
                        }else{//Este else calcula el padre de la cuenta segun el nivel de detalle

                            $cuenta_movimiento = $codigo_contable;
                            $lineaPadres       = array($cuenta_movimiento);

                            while($cuenta_movimiento!=''){
                                $consultaPadre     = SQL::obtenerValor("plan_contable","codigo_contable_padre","codigo_contable = '".$cuenta_movimiento."'");
                                $cuenta_movimiento = $consultaPadre;
                                if($cuenta_movimiento!=''){
                                    $lineaPadres[] = $cuenta_movimiento;
                                }
                            }

                            $lineaPadres    = array_reverse($lineaPadres);
                            $tamlineaPadres = count($lineaPadres);

                            if($tamlineaPadres>=$forma_nivel_detalle){
                                $cuenta_movimiento = $lineaPadres[$forma_nivel_detalle-1];
                            }else{
                                $cuenta_movimiento = $lineaPadres[$tamlineaPadres-1];
                            }
                        }

                        if (!isset($nombre_cuentas["'".$cuenta_movimiento."'"])){
                            $nombre_cuenta = SQL::obtenerValor("plan_contable","descripcion","codigo_contable='".$cuenta_movimiento."'");
                            $nombre_cuentas["'".$cuenta_movimiento."'"] = $nombre_cuenta;
                        }
                        // Hallar los padres de la cuenta
                        $id_padre_codigo = SQL::obtenerValor("plan_contable","codigo_contable_padre","codigo_contable='".$cuenta_movimiento."' AND codigo_contable!=''");
                        $codigos_padres = array();
                        $cuenta_numero_padres                 = "'".$cuenta_movimiento."'";
                        $numero_padres[$cuenta_numero_padres] = 0;
                        $posicion_padre                       = 1;

                        while ($id_padre_codigo!=''){

                            $codigo_padre    = $id_padre_codigo;
                            $nombre_cuenta   = SQL::obtenerValor("plan_contable","descripcion","codigo_contable='".$id_padre_codigo."'");
                            $id_padre_codigo = SQL::obtenerValor("plan_contable","codigo_contable_padre","codigo_contable='".$codigo_padre."' AND codigo_contable!=''");

                            $codigo_padre = "'".$codigo_padre."'";
                            if (!isset($nombre_cuentas[$codigo_padre])){
                                $nombre_cuentas[$codigo_padre] = $nombre_cuenta;
                            }

                            if (isset($saldo_balance[$codigo_padre][$fecha_balance])){
                                if ($sentido_cuenta != $sentido_movimiento){
                                    $saldo_balance[$codigo_padre][$fecha_balance] -= $saldo;
                                } else {
                                    $saldo_balance[$codigo_padre][$fecha_balance] += $saldo;
                                }
                            } else {
                                if ($sentido_cuenta != $sentido_movimiento){
                                    $saldo_balance[$codigo_padre][$fecha_balance] = $saldo * (-1);
                                } else {
                                    $saldo_balance[$codigo_padre][$fecha_balance] = $saldo;
                                }
                            }
                            $codigos_padres[$codigo_padre]= $codigo_padre;
                            $numero_padres[$cuenta_numero_padres]++;
                            $padre[$cuenta_numero_padres][$posicion_padre]=$codigo_padre;
                            $posicion_padre++;
                        }

                        $cuenta_movimiento = "'".$cuenta_movimiento."'";
                        asort($codigos_padres);
                        if (!isset($padres_cuenta[$cuenta_movimiento])){
                            $padres_cuenta[$cuenta_movimiento] = $codigos_padres;
                        }
                        // Fin de hallar los padres de la cuenta



                        if (!isset($tipo_cuenta_balance[$tipo_cuenta][$cuenta_movimiento])){
                            $tipo_cuenta_balance[$tipo_cuenta][$cuenta_movimiento] = $cuenta_movimiento;
                        }

                        if (isset($saldo_balance[$cuenta_movimiento][$fecha_balance])){
                            if ($sentido_cuenta != $sentido_movimiento){
                                $saldo_balance[$cuenta_movimiento][$fecha_balance] -= $saldo;
                            } else {
                                $saldo_balance[$cuenta_movimiento][$fecha_balance] += $saldo;
                            }
                        } else {
                            if ($sentido_cuenta != $sentido_movimiento){
                                $saldo_balance[$cuenta_movimiento][$fecha_balance] = $saldo * (-1);
                            } else {
                                $saldo_balance[$cuenta_movimiento][$fecha_balance] = $saldo;
                            }
                        }

                        // Final Agregar codigos contables y saldos de cuentas
                    } //Final while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento))
                    $separa_movimiento = true;
                } // Final: if (SQL::filasDevueltas($consulta_movimiento))1
            } // Final: while ($contador > 0)

            if ($separa_movimiento){

                // Generar PDF
                $cargar_pdf = true;
                // Consolidados //
                $condicion_sucursal = trim($condicion_sucursal, ",");
                $consolidadas       = explode(",", $condicion_sucursal);

                $archivo->Ln(4);
                $nombreSucursal         = SQL::obtenerValor("sucursales","nombre","codigo='".$sucursal."'");
                $archivo->textoTitulo   = $textos["BALANCE_GENERAL"]." ".$nombreSucursal;
                $archivo->textoCabecera = $textos["FECHA"].": ".$fechaReporte;
                $archivo->AddPage();
                $archivo->SetFont('Arial','B',6);

                if ((count($consolidadas) > 1) || (count($consolidadas) == 1 && $consolidadas[0] == $sucursal)){
                    $nombres_consolidadas = "";
                    foreach ($consolidadas AS $consolidada) {
                        $nombres_consolidadas .= SQL::obtenerValor("sucursales", "nombre", "codigo = ".$consolidada).", ";
                    }
                    $nombres_consolidadas = trim($nombres_consolidadas, ", ");
                }// Final: if ((count($consolidadas) > 1) || (count($consolidadas) == 1 && $consolidadas[0] == $sucursal))
                $archivo->textoPiePagina = $textos["CONSOLIDADAS"].": ".$nombres_consolidadas;

                $indicador_colores    = 0;
                $primer_item          = true;
                $auxiliar             = array();
                $tercero              = array();
                $imprime_pdf          = false;
                $tipo_cuenta_anterior = "";

                foreach ($tipo_cuenta_balance AS $tipo_cuenta => $cuenta_movimiento){

                    foreach ($cuenta_movimiento AS $codigo_cuenta){

                        if (($primer_item && $forma_nivel_detalle!=1) || ($tipo_cuenta != $tipo_cuenta_anterior && $tipo_cuenta_anterior!="")){

                            $archivo->SetFont('Arial','B',6);
                            $archivo->Ln(4);
                            if (isset($fecha[0]) && isset($fecha[1]) && isset($fecha[2])){
                                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1], $textos["SALDO"]." ".$fecha[2]);
                                $anchoColumnas  = array(20,80,30,30,30);
                            } else if(isset($fecha[0]) && isset($fecha[1])) {
                                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0], $textos["SALDO"]." ".$fecha[1]);
                                $anchoColumnas  = array(20,80,30,30);
                            } else {
                                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO"]." ".$fecha[0]);
                                $anchoColumnas  = array(20,80,30);
                            }
                            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);

                            foreach ($padres_cuenta[$codigo_cuenta] AS $indice_padre_cuenta_movimiento => $padre_cuenta_movimiento){
                                $indicador_colores = imprime_detalle_cuenta($padre_cuenta_movimiento, $archivo, $nombre_cuentas[$padre_cuenta_movimiento], $indicador_colores, $fecha, $textos);
                            }
                            $primer_item=false;
                        }

                        if (isset($auxiliar_cuenta[$codigo_cuenta])){

                            $indicador_colores = imprime_detalle_cuenta($codigo_cuenta, $archivo, $nombre_cuentas[$codigo_cuenta], $indicador_colores, $fecha, $textos);

                            foreach($auxiliar_cuenta[$codigo_cuenta] AS $auxiliar => $saldo_fecha_auxiliar){

                                if (isset($auxiliar_tercero[$codigo_cuenta])){

                                    $indicador_colores = imprime_detalle_auxiliar_tercero(" *Auxiliar", $nombre_auxiliar[$auxiliar], $archivo, $indicador_colores, $fecha, $textos);

                                    foreach($tercero_cuenta[$codigo_cuenta] AS $tercero => $saldo_fecha_tercero){
                                        $indicador_colores = imprime_total_auxiliar_tercero(" **Tercero", $nombre_tercero[$tercero], $archivo, $indicador_colores, $fecha, $saldo_fecha_tercero, $textos,0);
                                    }
                                    $indicador_colores = imprime_total_auxiliar_tercero(" *Auxiliar", $nombre_auxiliar[$auxiliar], $archivo, $indicador_colores, $fecha, $saldo_fecha_auxiliar, $textos,0);
                                } else {
                                    $indicador_colores = imprime_total_auxiliar_tercero(" *Auxiliar", $nombre_auxiliar[$auxiliar], $archivo, $indicador_colores, $fecha, $saldo_fecha_auxiliar, $textos,0);
                                }
                            }
                            $imprime_codigo_contable = 0;
                        } else if (isset($tercero_cuenta[$codigo_cuenta])){

                            $indicador_colores = imprime_detalle_cuenta($codigo_cuenta, $archivo, $nombre_cuentas[$codigo_cuenta], $indicador_colores, $fecha, $textos);
                            foreach($tercero_cuenta[$codigo_cuenta] AS $tercero => $saldo_fecha_tercero){
                                $indicador_colores = imprime_total_auxiliar_tercero(" *Tercero", $nombre_tercero[$tercero], $archivo, $indicador_colores, $fecha, $saldo_fecha_tercero, $textos,0);
                            }
                            $imprime_codigo_contable = 0;
                        } else {
                            $imprime_codigo_contable = 1;
                        }
                        $indicador_colores = imprime_total_cuenta($saldo_balance[$codigo_cuenta], $codigo_cuenta, $archivo, $nombre_cuentas[$codigo_cuenta], $indicador_colores, $fecha, $textos, $imprime_codigo_contable);

                        $cuenta_movimiento_siguiente = "";
                        $cuenta_movimiento_siguiente = current($cuenta_movimiento);
                        $cuenta_movimiento_siguiente = next($cuenta_movimiento);

                        if ($cuenta_movimiento_siguiente){
                            $numero_padres_cuenta_actual = $numero_padres[$codigo_cuenta];
                            $contador_cilco=1;

                            while($contador_cilco <= $numero_padres_cuenta_actual){
                                $padre_codigo_contable = $padre[$codigo_cuenta][$contador_cilco];
                                $imprima_total_padre   = true;

                                foreach ($padres_cuenta[$cuenta_movimiento_siguiente] AS $indice_padre_cuenta_movimiento => $padre_siguiente_movimiento){
                                    if ($padre_codigo_contable == $padre_siguiente_movimiento){
                                        $imprima_total_padre = false;
                                    }
                                }
                                if ($imprima_total_padre){
                                    $indicador_colores = imprime_total_cuenta($saldo_balance[$padre_codigo_contable], $padre_codigo_contable, $archivo, $nombre_cuentas[$padre_codigo_contable], $indicador_colores, $fecha, $textos,0);
                                }
                                $contador_cilco++;
                            }

                            $numero_padres_cuenta_siguiente = $numero_padres[$cuenta_movimiento_siguiente];
                            while($numero_padres_cuenta_siguiente > 0){
                                $padre_codigo_contable = $padre[$cuenta_movimiento_siguiente][$numero_padres_cuenta_siguiente];
                                $imprima_detalle_padre = true;

                                foreach ($padres_cuenta[$codigo_cuenta] AS $indice_padre_cuenta_actual => $padre_cuenta_actual){
                                    if ($padre_codigo_contable == $padre_cuenta_actual){
                                        $imprima_detalle_padre = false;
                                    }
                                }
                                if ($imprima_detalle_padre){
                                    $indicador_colores = imprime_detalle_cuenta($padre_codigo_contable, $archivo, $nombre_cuentas[$padre_codigo_contable], $indicador_colores, $fecha, $textos);
                                }
                                $numero_padres_cuenta_siguiente--;
                            }
                        }
                    }

                    $numero_padres_cuenta_actual = $numero_padres[$codigo_cuenta];
                    $contador_cilco=1;
                    while($contador_cilco <= $numero_padres_cuenta_actual){
                        $padre_codigo_contable = $padre[$codigo_cuenta][$contador_cilco];
                        $indicador_colores     = imprime_total_cuenta($saldo_balance[$padre_codigo_contable], $padre_codigo_contable, $archivo, $nombre_cuentas[$padre_codigo_contable], $indicador_colores, $fecha, $textos,0);
                        $contador_cilco++;
                    }

                    if ($tipo_cuenta == 1){
                        $archivo->SetFont('Arial','B',8);
                        $archivo->Ln(10);
                        $imprime_cabecera = $archivo->breakCell(20);
                        if ($imprime_cabecera){
                            $archivo->AddPage();
                        }
                        if (isset($fecha[1])){
                            $tituloColumnas = array($textos["FECHA"],$textos["ACTIVO"],$textos["PASIVO"],$textos["PATRIMONIO"],$textos["RESULTADO"]);
                            $anchoColumnas  = array(30,30,30,30,30);
                        } else {
                            $tituloColumnas = array($textos["ACTIVO"],$textos["PASIVO"],$textos["PATRIMONIO"],$textos["RESULTADO"]);
                            $anchoColumnas  = array(30,30,30,30);
                        }
                        $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);

                        $indicador_colores=1;
                        foreach($fecha AS $fecha_resultado){
                            $archivo->Ln(4);
                            $imprime_cabecera = $archivo->breakCell(10);
                            if ($imprime_cabecera){
                                $archivo->SetFont('Arial','B',8);
                                if (isset($fecha[1])){
                                    $tituloColumnas = array($textos["FECHA"],$textos["ACTIVO"],$textos["PASIVO"],$textos["PATRIMONIO"],$textos["RESULTADO"]);
                                    $anchoColumnas  = array(30,30,30,30,30);
                                } else {
                                    $tituloColumnas = array($textos["ACTIVO"],$textos["PASIVO"],$textos["PATRIMONIO"],$textos["RESULTADO"]);
                                    $anchoColumnas  = array(30,30,30,30);
                                }
                                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
                                $archivo->Ln(4);
                            }

                            $indicador_colores++;
                            if($indicador_colores%2==0){
                                $rojo  = 255;
                                $azul  = 255;
                                $verde = 255;
                            } else {
                                $rojo  = 240;
                                $azul  = 240;
                                $verde = 240;
                            }

                            $archivo->SetFont('Arial','',6);
                            $archivo->SetFillColor($rojo,$verde,$azul);

                            if (isset($fecha[1])){
                                $archivo->Cell(30, 4,$fecha_resultado, 1, 0, "C", true);
                            }

                            if (isset($saldo_balance["'1'"][$fecha_resultado])){

                                $saldo_activo = $saldo_balance["'1'"][$fecha_resultado];
                                $saldo1 = (int)$saldo_activo;
                                if ($saldo_activo < 0){
                                    //$archivo->SetTextColor(255,0,0);
                                    $saldo_activo = $saldo_activo *(-1);
                                    $saldo_activo = "$ (".number_format($saldo_activo).")";
                                } else if($saldo_activo>0){
                                    //$archivo->SetTextColor(0,128,0);
                                    $saldo_activo = "$ ".number_format($saldo_activo);
                                }
                            } else {
                                //$archivo->SetTextColor(0,0,0);
                                $saldo_activo = "$ 0";
                                $saldo1       = 0;
                            }
                            $archivo->Cell(30, 4,$saldo_activo, 1, 0, "R", true);

                            if (isset($saldo_balance["'2'"][$fecha_resultado])){

                                $saldo_pasivo = $saldo_balance["'2'"][$fecha_resultado];
                                $saldo2 = (int)$saldo_pasivo;
                                if ($saldo_pasivo < 0){
                                    //$archivo->SetTextColor(255,0,0);
                                    $saldo_pasivo = $saldo_pasivo *(-1);
                                    $saldo_pasivo = "$ (".number_format($saldo_pasivo).")";

                                } else if($saldo_pasivo>0){
                                    //$archivo->SetTextColor(0,128,0);
                                    $saldo_pasivo = "$ ".number_format($saldo_pasivo);
                                }
                            } else {
                                //$archivo->SetTextColor(0,0,0);
                                $saldo_pasivo = "$ 0";
                                $saldo2 = 0;
                            }
                            $archivo->Cell(30, 4,$saldo_pasivo, 1, 0, "R", true);

                            if (isset($saldo_balance["'3'"][$fecha_resultado])){

                                $saldo_patrimonio = $saldo_balance["'3'"][$fecha_resultado];
                                $saldo3 = (int)$saldo_patrimonio;
                                if ($saldo_patrimonio < 0){
                                    //$archivo->SetTextColor(255,0,0);
                                    $saldo_patrimonio = $saldo_patrimonio *(-1);
                                    $saldo_patrimonio = "$ (".number_format($saldo_patrimonio).")";

                                } else if($saldo_patrimonio>0){
                                    //$archivo->SetTextColor(0,128,0);
                                    $saldo_patrimonio = "$ ".number_format($saldo_patrimonio);
                                }
                            } else {
                                //$archivo->SetTextColor(0,0,0);
                                $saldo_patrimonio = "$ 0";
                                $saldo3           = 0;
                            }
                            $archivo->Cell(30, 4,$saldo_patrimonio, 1, 0, "R", true);

                            $resultado = $saldo1 - $saldo2 - $saldo3;
                            if ($resultado < 0){
                                //$archivo->SetTextColor(255,0,0);
                                $resultado = $resultado *(-1);
                                $resultado = "$ (".number_format($resultado).")";
                            } else if($resultado > 0){
                                //$archivo->SetTextColor(0,128,0);
                                $resultado = "$ ".number_format($resultado);
                            } else {
                                $archivo->SetTextColor(0,0,0);
                                $resultado = "$ 0";
                            }
                            $archivo->Cell(30, 4,$resultado, 1, 0, "R", true);

                            //$archivo->SetTextColor(0,0,0);
                        }
                    }
                    $tipo_cuenta_anterior = $tipo_cuenta;
                    $imprime_pdf          = true;
                    $primer_item          = true;
                    $archivo->Ln(10);
                }// Final: foreach ($tipo_cuenta_balance AS $tipo_cuenta => $cuenta_movimiento)
            } // Final: if ($separa_movimiento)
        } //Final: foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal)
    }// Final: if (!isset($forma_sucursales))

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    if ($datos_incompletos){
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    } else if(count($forma_sucursales)!=0 && $cargar_pdf) {
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

        $error        = false;
        $mensaje      = $textos["MENSAJE_EXITO"];
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
        $respuesta[2] = $ruta_archivo;
    } else {
        $error        = true;
        $mensaje      = $textos["MENSAJE_NO_GENERA_PDF"];
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    }

    HTTP::enviarJSON($respuesta);
}
?>
