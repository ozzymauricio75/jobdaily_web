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

        // Definicion de pestana general
        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");
        
        $formularios["PESTANA_BALANCE_COMPROBACION"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_INICIAL_PYG"].'   -   '.$textos["FECHA_FINAL_PYG"], 25, 25,$fecha_inicial, array("title" => $textos["FECHAS"], "class" => "fechaRango"))
            ), array(
                HTML::listaSeleccionSimple("nivel_detalle", $textos["NIVEL_DETALLE"], $nivelDetalle, 5, array("title" => $textos["AYUDA_NIVEL_DETALLE"], "onChange" => "activarManejoDetalles(this)"))
            ), array (
                HTML::contenedor(
                    (
                        HTML::marcaChequeo("detalla_auxiliares", $textos["DETALLA_AUXILIARES"], "1", false, array("title" => $textos["AYUDA_DETALLA_AUXILIARES"]))
                        .HTML::marcaChequeo("detalla_terceros", $textos["DETALLA_TERCEROS"], "1", false, array("title" => $textos["AYUDA_DETALLA_TERCEROS"]))
                    ), array("id" => "detalles_balance")
                )
            ), array (
                HTML::boton("boton_balance_general", $textos["GENERAR"], "imprimirItem('1');", "aceptar")
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

    $datos_incompletos= false;

    $fechas                  = explode('-',$forma_fechas);
    $forma_fecha_inicial_pyg = trim($fechas[0]);
    $forma_fecha_final_pyg   = trim($fechas[1]);

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
        $id_auxiliar = "";
        if(isset($forma_detalla_auxiliares) && $forma_nivel_detalle==5){
            $detalla_auxiliar = true;
            $id_auxiliar      = ", codigo_empresa_auxiliar, codigo_anexo_contable, codigo_auxiliar_contable";
        }

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $cargaPdf = 0;
        $mensaje  = $textos["ERROR_GENERAR_ARCHIVO"];

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

        $imprime_pdf = false;
        include("clases/balances.php");

        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            $condicion_sucursal       = trim($condicion_sucursal, ",");
            $consolidadas             = explode(",", $condicion_sucursal);

            $cuenta_balance           = array();
            $saldo_inicial            = array();
            $saldo_inicial_cuenta     = array();
            $saldo_balance            = array();
            $saldo_debito_balance     = array();
            $saldo_credito_balance    = array();
            $nombre_cuentas           = array();
            $auxiliar_cuenta          = array();
            $separa_movimiento        = false;
            $auxiliar_debito_cuenta   = array();
            $auxiliar_credito_cuenta  = array();
            $tercero_cuenta           = array();
            $tercero_debito_cuenta    = array();
            $tercero_credito_cuenta   = array();
            $numero_cuenta_movimiento = array();
            $numero_cuenta            = array();

            $fecha         = array();
            $fecha[]       = $forma_fecha_inicial_pyg;
            $fecha_inicial = $forma_fecha_inicial_pyg;
            $fecha_final   = $forma_fecha_final_pyg;

            $tablas    = array("movimientos_contables_consolidados");
            $condicion = "estado = 1 AND fecha_contabilizacion >= '".$fecha_inicial."' AND fecha_contabilizacion <= '".$fecha_final."'AND codigo_sucursal_genera IN (".$condicion_sucursal.")";
            $datos     = array(
                "codigo_contable",
                "descripcion_cuenta",
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

                $numero_consultas = 1;
                while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento)) {

                    $codigo_contable    = $datos_movimiento->codigo_contable;
                    $sentido_cuenta     = $datos_movimiento->sentido_cuenta;
                    $sentido_movimiento = $datos_movimiento->sentido_movimiento;
                    $saldo              = $datos_movimiento->valor;
                    if ($detalla_tercero){
                        $tercero = $datos_movimiento->documento_identidad_tercero;
                    } else {
                        $tercero = 0;
                    }
                    if ($detalla_auxiliar){
                        $auxiliar = $datos_movimiento->codigo_empresa_auxiliar.'|'.$datos_movimiento->codigo_anexo_contable.'|'.$datos_movimiento->codigo_auxiliar_contable;
                    } else {
                        $auxiliar = 0;
                    }

                    if ($forma_nivel_detalle==5){

                        $cuenta_movimiento = $codigo_contable;
                        $cuenta_movimiento = "'".$cuenta_movimiento."'";

                        if ($detalla_auxiliar && $auxiliar>0){

                            if (!isset($auxiliar_cuenta[$cuenta_movimiento][$auxiliar][$forma_fecha_inicial_pyg])){
                                $descripcion = SQL::obtenerValor("buscador_auxiliares_contables","descripcion","id='".$auxiliar."'");
                                $nombre_auxiliar[$auxiliar] = $descripcion;
                                if ($sentido_cuenta != $sentido_movimiento){
                                    $auxiliar_cuenta[$cuenta_movimiento][$auxiliar] = $saldo * (-1);
                                } else {
                                    $auxiliar_cuenta[$cuenta_movimiento][$auxiliar] = $saldo;
                                }
                            } else {
                                if ($sentido_cuenta != $sentido_movimiento){
                                    $auxiliar_cuenta[$cuenta_movimiento][$auxiliar] -= $saldo;
                                } else {
                                    $auxiliar_cuenta[$cuenta_movimiento][$auxiliar] += $saldo;
                                }
                            }

                            if ($sentido_movimiento=='D'){
                                if (isset($auxiliar_debito_cuenta[$cuenta_movimiento][$auxiliar])){
                                    $auxiliar_debito_cuenta[$cuenta_movimiento][$auxiliar] += $saldo;
                                } else {
                                    $auxiliar_debito_cuenta[$cuenta_movimiento][$auxiliar] = $saldo;
                                }
                                if (!isset($auxiliar_credito_cuenta[$cuenta_movimiento][$auxiliar])){
                                    $auxiliar_credito_cuenta[$cuenta_movimiento][$auxiliar] = 0;
                                }
                            } else {
                                if (isset($tercero_credito_cuenta[$cuenta_movimiento][$auxiliar])){
                                    $auxiliar_credito_cuenta[$cuenta_movimiento][$auxiliar] += $saldo;
                                } else {
                                    $auxiliar_credito_cuenta[$cuenta_movimiento][$auxiliar] = $saldo;
                                }
                                if (!isset($auxiliar_debito_cuenta[$cuenta_movimiento][$auxiliar])){
                                    $auxiliar_debito_cuenta[$cuenta_movimiento][$auxiliar] = 0;
                                }
                            }

                            if (!isset($saldo_inicial_auxiliar[$cuenta_movimiento][$auxiliar])){

                                $llave_auxiliar = explode('|',$auxiliar);

                                $condicion_debito  = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'D' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_empresa_auxiliar = '".$llave_auxiliar[0]."' AND codigo_anexo_contable = '".$llave_auxiliar[1]."' AND codigo_auxiliar_contable = '".$llave_auxiliar[2]."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";
                                $condicion_credito = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'C' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_empresa_auxiliar = '".$llave_auxiliar[0]."' AND codigo_anexo_contable = '".$llave_auxiliar[1]."' AND codigo_auxiliar_contable = '".$llave_auxiliar[2]."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";

                                $saldo_debito_auxiliar = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_debito);
                                if (!$saldo_debito_auxiliar){
                                    $saldo_debito_auxiliar = 0;
                                }

                                $saldo_credito_auxiliar = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_credito);
                                if (!$saldo_credito_auxiliar){
                                    $saldo_credito_auxiliar = 0;
                                }

                                if ($sentido_cuenta == 'D'){
                                    $saldo_inicial_auxiliar[$cuenta_movimiento][$auxiliar] = $saldo_debito_auxiliar - $saldo_credito_auxiliar;
                                } else {
                                    $saldo_inicial_auxiliar[$cuenta_movimiento][$auxiliar] = $saldo_credito_auxiliar - $saldo_debito_auxiliar;
                                }
                            }
                        }

                        if ($detalla_tercero){

                            $maneja_saldos_tercero = SQL::obtenerValor("plan_contable","maneja_tercero","codigo_contable='".$codigo_contable."'");
                            if ($maneja_saldos_tercero==1){
                                if (!isset($tercero_cuenta[$cuenta_movimiento][$tercero])){
                                    if ($tercero==0){
                                        $nombre_completo = "Sin nombre";
                                    } else {
                                        $nombre_completo = SQL::obtenerValor("menu_terceros","NOMBRE_COMPLETO","id='".$tercero."'");
                                    }
                                    $nombre_tercero[$tercero] = $nombre_completo;
                                    if ($sentido_cuenta != $sentido_movimiento){
                                        $tercero_cuenta[$cuenta_movimiento][$tercero] = $saldo * (-1);
                                    } else {
                                        $tercero_cuenta[$cuenta_movimiento][$tercero] = $saldo;
                                    }
                                } else {
                                    if ($sentido_cuenta != $sentido_movimiento){
                                        $tercero_cuenta[$cuenta_movimiento][$tercero] -= $saldo;
                                    } else {
                                        $tercero_cuenta[$cuenta_movimiento][$tercero] += $saldo;
                                    }
                                }

                                if ($sentido_movimiento=='D'){
                                    if (isset($tercero_debito_cuenta[$cuenta_movimiento][$tercero])){
                                        $tercero_debito_cuenta[$cuenta_movimiento][$tercero] += $saldo;
                                    } else {
                                        $tercero_debito_cuenta[$cuenta_movimiento][$tercero] = $saldo;
                                    }
                                    if (!isset($tercero_credito_cuenta[$cuenta_movimiento][$tercero])){
                                        $tercero_credito_cuenta[$cuenta_movimiento][$tercero] = 0;
                                    }
                                } else {
                                    if (isset($tercero_credito_cuenta[$cuenta_movimiento][$tercero])){
                                        $tercero_credito_cuenta[$cuenta_movimiento][$tercero] += $saldo;
                                    } else {
                                        $tercero_credito_cuenta[$cuenta_movimiento][$tercero] = $saldo;
                                    }
                                    if (!isset($tercero_debito_cuenta[$cuenta_movimiento][$tercero])){
                                        $tercero_debito_cuenta[$cuenta_movimiento][$tercero] = 0;
                                    }
                                }
                                if (!isset($saldo_inicial_tercero[$cuenta_movimiento][$tercero])){

                                    $llave_auxiliar = explode('|',$auxiliar);

                                    $condicion_debito  = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'D' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_empresa_auxiliar = '".$llave_auxiliar[0]."' AND codigo_anexo_contable = '".$llave_auxiliar[1]."' AND codigo_auxiliar_contable = '".$llave_auxiliar[2]."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";
                                    $condicion_credito = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'C' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_empresa_auxiliar = '".$llave_auxiliar[0]."' AND codigo_anexo_contable = '".$llave_auxiliar[1]."' AND codigo_auxiliar_contable = '".$llave_auxiliar[2]."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";

                                    $saldo_inicial_debito  = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_debito);
                                    if (!$saldo_inicial_debito){
                                        $saldo_inicial_debito = 0;
                                    }

                                    $saldo_inicial_credito = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_credito);
                                    if (!$saldo_inicial_credito){
                                        $saldo_inicial_credito = 0;
                                    }

                                    if ($sentido_cuenta == 'D'){
                                        $saldo_inicial_tercero[$cuenta_movimiento][$tercero] = $saldo_inicial_debito - $saldo_inicial_credito;
                                    } else {
                                        $saldo_inicial_tercero[$cuenta_movimiento][$tercero] = $saldo_inicial_credito - $saldo_inicial_debito;
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

                        $lineaPadres = array_reverse($lineaPadres);
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
                    $id_padre_codigo                   = SQL::obtenerValor("plan_contable","codigo_contable_padre","codigo_contable='".$cuenta_movimiento."'");
                    $codigos_padres                    = array();
                    $cuenta_movimiento                 = "'".$cuenta_movimiento."'";
                    $numero_padres[$cuenta_movimiento] = 0;
                    $posicion_padre                    = 1;

                    while ($id_padre_codigo!=NULL){

                        $codigo_padre    = $id_padre_codigo;
                        $nombre_cuenta   = SQL::obtenerValor("plan_contable","descripcion","codigo_contable='".$id_padre_codigo."'");
                        $id_padre_codigo = SQL::obtenerValor("plan_contable","codigo_contable_padre","codigo_contable='".$codigo_padre."' AND codigo_contable!=''");

                        $codigo_padre = "'".$codigo_padre."'";

                        if (!isset($saldo_inicial_cuenta[$codigo_contable][$tercero][$auxiliar])){

                            $condicion_debito  = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'D' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";
                            $condicion_credito = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'C' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";

                            $saldo_inicial_debito  = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_debito);
                            if (!$saldo_inicial_debito){
                                $saldo_inicial_debito = 0;
                            }

                            $saldo_inicial_credito = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_credito);
                            if (!$saldo_inicial_credito){
                                $saldo_inicial_credito = 0;
                            }

                            if (isset($saldo_inicial[$codigo_padre])){

                                if ($sentido_cuenta == 'D'){
                                    $saldo_inicial[$codigo_padre] += $saldo_inicial_debito - $saldo_inicial_credito;
                                } else {
                                    $saldo_inicial[$codigo_padre] += $saldo_inicial_credito - $saldo_inicial_debito;
                                }
                            } else {

                                if ($sentido_cuenta == 'D'){
                                    $saldo_inicial[$codigo_padre] = $saldo_inicial_debito - $saldo_inicial_credito;
                                } else {
                                    $saldo_inicial[$codigo_padre] = $saldo_inicial_credito - $saldo_inicial_debito;
                                }
                            }
                        }

                        if (!isset($nombre_cuentas[$codigo_padre])){
                            $nombre_cuentas[$codigo_padre] = $nombre_cuenta;
                        }

                        if (isset($saldo_balance[$codigo_padre])){
                            if ($sentido_cuenta != $sentido_movimiento){
                                $saldo_balance[$codigo_padre] -= $saldo;
                            } else {
                                $saldo_balance[$codigo_padre] += $saldo;
                            }
                        } else {
                            if ($sentido_cuenta != $sentido_movimiento){
                                $saldo_balance[$codigo_padre] = $saldo * (-1);
                            } else {
                                $saldo_balance[$codigo_padre] = $saldo;
                            }
                        }

                        if ($sentido_movimiento == 'D'){
                            if (isset($saldo_debito_balance[$codigo_padre])){
                                $saldo_debito_balance[$codigo_padre] += $saldo;
                            } else {
                                $saldo_debito_balance[$codigo_padre] = $saldo;
                            }
                            if (!isset($saldo_credito_balance[$codigo_padre])){
                                $saldo_credito_balance[$codigo_padre] = 0;
                            }
                        } else {
                            if (isset($saldo_credito_balance[$codigo_padre])){
                                $saldo_credito_balance[$codigo_padre] += $saldo;
                            } else {
                                $saldo_credito_balance[$codigo_padre] = $saldo;
                            }
                            if (!isset($saldo_debito_balance[$codigo_padre])){
                                $saldo_debito_balance[$codigo_padre] = 0;
                            }
                        }

                        $codigos_padres[$codigo_padre]= $codigo_padre;
                        $numero_padres[$cuenta_movimiento]++;
                        $padre[$cuenta_movimiento][$posicion_padre]=$codigo_padre;
                        $posicion_padre++;
                    }

                    asort($codigos_padres);
                    if (!isset($padres_cuenta[$cuenta_movimiento])){
                        $padres_cuenta[$cuenta_movimiento] = $codigos_padres;
                    }
                    // Fin de hallar los padres de la cuenta

                    if (!isset($cuenta_balance[$cuenta_movimiento])){
                        $cuenta_balance[$cuenta_movimiento]           = $cuenta_movimiento;
                        $numero_cuenta_movimiento[$cuenta_movimiento] = $numero_consultas;
                        $numero_cuenta[$numero_consultas]             = $cuenta_movimiento;
                        $numero_consultas++;
                    }

                    if (isset($saldo_balance[$cuenta_movimiento])){
                        if ($sentido_cuenta != $sentido_movimiento){
                            $saldo_balance[$cuenta_movimiento] -= $saldo;
                        } else {
                            $saldo_balance[$cuenta_movimiento] += $saldo;
                        }
                    } else {
                        if ($sentido_cuenta != $sentido_movimiento){
                            $saldo_balance[$cuenta_movimiento] = $saldo * (-1);
                        } else {
                            $saldo_balance[$cuenta_movimiento] = $saldo;
                        }
                    }

                    if ($sentido_movimiento == 'D'){
                        if (isset($saldo_debito_balance[$cuenta_movimiento])){
                            $saldo_debito_balance[$cuenta_movimiento] += $saldo;
                        } else {
                            $saldo_debito_balance[$cuenta_movimiento] = $saldo;
                        }
                        if (!isset($saldo_credito_balance[$cuenta_movimiento])){
                            $saldo_credito_balance[$cuenta_movimiento] = 0;
                        }
                    } else {
                        if (isset($saldo_credito_balance[$cuenta_movimiento])){
                            $saldo_credito_balance[$cuenta_movimiento] += $saldo;
                        } else {
                            $saldo_credito_balance[$cuenta_movimiento] = $saldo;
                        }
                        if (!isset($saldo_debito_balance[$cuenta_movimiento])){
                            $saldo_debito_balance[$cuenta_movimiento] = 0;
                        }
                    }

                    if (!isset($saldo_inicial_cuenta[$cuenta_movimiento][$tercero][$auxiliar])){

                        $condicion_debito  = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'D' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";
                        $condicion_credito = "codigo_contable = '".$codigo_contable."' AND sentido_movimiento = 'C' AND fecha_contabilizacion < '".$fecha_inicial."' AND codigo_sucursal_genera IN (".$condicion_sucursal.")";

                        $saldo_inicial_debito  = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_debito);
                        if (!$saldo_inicial_debito){
                            $saldo_inicial_debito = 0;
                        }

                        $saldo_inicial_credito = SQL::obtenerValor("movimientos_contables_consolidados","SUM(valor)",$condicion_credito);
                        if (!$saldo_inicial_credito){
                            $saldo_inicial_credito = 0;
                        }

                        if (isset($saldo_inicial[$cuenta_movimiento])){
                            if ($sentido_cuenta == 'D'){
                                $saldo_inicial[$cuenta_movimiento] += $saldo_inicial_debito - $saldo_inicial_credito;
                            } else {
                                $saldo_inicial[$cuenta_movimiento] += $saldo_inicial_credito - $saldo_inicial_debito;
                            }
                        } else {
                            if ($sentido_cuenta == 'D'){
                                $saldo_inicial[$cuenta_movimiento] = $saldo_inicial_debito - $saldo_inicial_credito;
                            } else {
                                $saldo_inicial[$cuenta_movimiento] = $saldo_inicial_credito - $saldo_inicial_debito;
                            }
                        }
                    }
                    // Final Agregar codigos contables y saldos de cuentas
                } //Final while ($datos_movimiento = SQL::filaEnObjeto($consulta_movimiento))
                $separa_movimiento = true;
                $imprime_pdf = true;
            } // Final: if (SQL::filasDevueltas($consulta_movimiento))

            if ($separa_movimiento){

                // Generar PDF
                $condicion_sucursal = trim($condicion_sucursal, ",");
                $consolidadas       = explode(",", $condicion_sucursal);

                $archivo->Ln(4);
                $nombreSucursal         = SQL::obtenerValor("sucursales","nombre","codigo='".$sucursal."'");
                $archivo->textoTitulo   = $textos["BALANCE_COMPROBACION"]." ".$nombreSucursal;
                $archivo->textoCabecera = $textos["FECHA_INICIAL_PYG"].": ".$forma_fecha_inicial_pyg. " ".$textos["FECHA_FINAL_PYG"].": ".$forma_fecha_final_pyg;
                $archivo->SetFont('Arial','B',6);

                if ((count($consolidadas) > 1) || (count($consolidadas) == 1 && $consolidadas[0] == $sucursal)){
                    $nombres_consolidadas = "";
                    foreach ($consolidadas AS $consolidada) {
                        $nombres_consolidadas .= SQL::obtenerValor("sucursales", "nombre", "codigo = '".$consolidada."'").", ";
                    }
                    $nombres_consolidadas = trim($nombres_consolidadas, ", ");
                }// Final: if ((count($consolidadas) > 1) || (count($consolidadas) == 1 && $consolidadas[0] == $sucursal))

                $archivo->AddPage();
                $archivo->textoPiePagina = $textos["CONSOLIDADAS"].": ".$nombres_consolidadas;

                $archivo->SetFont('Arial','B',6);
                $archivo->Ln(4);
                $tituloColumnas = array($textos["CODIGO"],$textos["NOMBRE_CUENTA"],$textos["SALDO_INICIAL"],$textos["DEBE"],$textos["HABER"],$textos["SALDO_FINAL"]);
                $anchoColumnas  = array(20,80,25,25,25,25);
                $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);

                $indicador_colores = 0;
                $primer_item       = true;
                $auxiliar          = array();
                $tercero           = array();
                $imprime_total_pdf = false;

                foreach ($cuenta_balance AS $codigo_cuenta){

                    if ($primer_item){

                        foreach ($padres_cuenta[$codigo_cuenta] AS $indice_padre_cuenta_movimiento => $padre_cuenta_movimiento){
                            $indicador_colores = imprime_detalle_comprobacion($padre_cuenta_movimiento, $archivo, $nombre_cuentas[$padre_cuenta_movimiento], $indicador_colores, $textos);
                        }
                        $primer_item=false;
                    }

                    if (isset($auxiliar_cuenta[$codigo_cuenta])){

                        $indicador_colores = imprime_detalle_comprobacion($codigo_cuenta, $archivo, $nombre_cuentas[$codigo_cuenta], $indicador_colores, $textos);

                        foreach($auxiliar_cuenta[$codigo_cuenta] AS $auxiliar => $saldo_auxiliar){

                            if (isset($tercero_cuenta[$codigo_cuenta])){

                                $indicador_colores = imprime_detalle_comprobacion(" *Auxiliar", $archivo, $nombre_auxiliar[$auxiliar], $indicador_colores, $textos);

                                foreach($tercero_cuenta[$codigo_cuenta] AS $tercero => $saldo_tercero){
                                    $indicador_colores = imprime_total_comprobacion($indicador_colores, $archivo, " **Tercero", $nombre_tercero[$tercero], $saldo_inicial_tercero[$codigo_cuenta][$tercero], $tercero_debito_cuenta[$codigo_cuenta][$tercero], $tercero_credito_cuenta[$codigo_cuenta][$tercero], $tercero_cuenta[$codigo_cuenta][$tercero], $textos,0);
                                }
                                $indicador_colores = imprime_total_comprobacion($indicador_colores, $archivo, " *Auxiliar", $nombre_auxiliar[$auxiliar], $saldo_inicial_auxiliar[$codigo_cuenta][$auxiliar], $auxiliar_debito_cuenta[$codigo_cuenta][$auxiliar], $auxiliar_credito_cuenta[$codigo_cuenta][$auxiliar], $auxiliar_cuenta[$codigo_cuenta][$auxiliar], $textos,0);
                            } else {
                                $indicador_colores = imprime_total_comprobacion($indicador_colores, $archivo, " *Auxiliar", $nombre_auxiliar[$auxiliar], $saldo_inicial_auxiliar[$codigo_cuenta][$auxiliar], $auxiliar_debito_cuenta[$codigo_cuenta][$auxiliar], $auxiliar_credito_cuenta[$codigo_cuenta][$auxiliar], $auxiliar_cuenta[$codigo_cuenta][$auxiliar], $textos,0);
                            }
                        }
                        $imprime_codigo_cuenta = 0;
                    } else if (isset($tercero_cuenta[$codigo_cuenta])){

                        $indicador_colores = imprime_detalle_comprobacion($codigo_cuenta, $archivo, $nombre_cuentas[$codigo_cuenta], $indicador_colores, $textos);

                        foreach($tercero_cuenta[$codigo_cuenta] AS $tercero => $saldo_tercero){
                            $indicador_colores = imprime_total_comprobacion($indicador_colores, $archivo, " **Tercero", $nombre_tercero[$tercero], $saldo_inicial_tercero[$codigo_cuenta][$tercero], $tercero_debito_cuenta[$codigo_cuenta][$tercero], $tercero_credito_cuenta[$codigo_cuenta][$tercero], $tercero_cuenta[$codigo_cuenta][$tercero], $textos,0);
                        }
                        $imprime_codigo_cuenta = 0;
                    } else {
                        $imprime_codigo_cuenta = 1;
                    }
                    $indicador_colores = imprime_total_comprobacion($indicador_colores, $archivo, $codigo_cuenta, $nombre_cuentas[$codigo_cuenta], $saldo_inicial[$codigo_cuenta], $saldo_debito_balance[$codigo_cuenta], $saldo_credito_balance[$codigo_cuenta], $saldo_balance[$codigo_cuenta], $textos, $imprime_codigo_cuenta);

                    $numero = $numero_cuenta_movimiento[$codigo_cuenta];
                    $numero++;
                    if (isset($numero_cuenta[$numero])){
                        $cuenta_movimiento_siguiente = $numero_cuenta[$numero];
                    } else {
                        $cuenta_movimiento_siguiente = false;
                    }

                    if ($cuenta_movimiento_siguiente){
                        $numero_padres_cuenta_actual = $numero_padres[$codigo_cuenta];
                        $contador_ciclo              = 1;

                        while($contador_ciclo <= $numero_padres_cuenta_actual){
                            $padre_codigo_contable = $padre[$codigo_cuenta][$contador_ciclo];
                            $imprima_total_padre   = true;

                            foreach ($padres_cuenta[$cuenta_movimiento_siguiente] AS $indice_padre_cuenta_movimiento => $padre_siguiente_movimiento){
                                if ($padre_codigo_contable == $padre_siguiente_movimiento){
                                    $imprima_total_padre = false;
                                }                                    
                            }
                            if ($imprima_total_padre){
                                $indicador_colores = imprime_total_comprobacion($indicador_colores, $archivo, $padre_codigo_contable, $nombre_cuentas[$padre_codigo_contable], $saldo_inicial[$padre_codigo_contable], $saldo_debito_balance[$padre_codigo_contable], $saldo_credito_balance[$padre_codigo_contable], $saldo_balance[$padre_codigo_contable], $textos, 0);
                            }
                            $contador_ciclo++;
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
                                $indicador_colores = imprime_detalle_comprobacion($padre_codigo_contable, $archivo, $nombre_cuentas[$padre_codigo_contable], $indicador_colores, $textos);
                            }
                            $numero_padres_cuenta_siguiente--;
                        }
                    }
                }

                $numero_padres_cuenta_actual = $numero_padres[$codigo_cuenta];
                $contador_ciclo              = 1;                    
                while($contador_ciclo <= $numero_padres_cuenta_actual){

                    $padre_codigo_contable = $padre[$codigo_cuenta][$contador_ciclo];
                    $indicador_colores = imprime_total_comprobacion($indicador_colores, $archivo, $padre_codigo_contable, $nombre_cuentas[$padre_codigo_contable], $saldo_inicial[$padre_codigo_contable], $saldo_debito_balance[$padre_codigo_contable], $saldo_credito_balance[$padre_codigo_contable], $saldo_balance[$padre_codigo_contable], $textos,0);
                    $contador_ciclo++;
                }
                $archivo->SetFont('Arial','B',6);
            } // Final: if ($separa_movimiento)
        } //Final: foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal)
    }// Final: if (!isset($forma_sucursales))

    // Enviar datos con la respuesta del proceso al script que originó la petición
    $respuesta    = array();
    if ($datos_incompletos){
        $respuesta[0] = $error;
        $respuesta[1] = $mensaje;
    } else if(count($forma_sucursales)!=0 && $imprime_pdf) {
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
