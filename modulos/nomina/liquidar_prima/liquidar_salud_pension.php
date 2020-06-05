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
 if(isset($url_recargarTipoPlanilla)){
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

    $error     = "";
    $titulo    = $componente->nombre;
    $id_modulo = SQL::obtenerValor("componentes","id_modulo","id='".$componente->id."'");

    $empresa = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."' AND codigo != 0");

    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
        $consulta_sucursales = SQL::seleccionar(array("sucursales"), array("codigo, nombre"), "codigo_empresa = '".$empresa."'");
    } else {
        // Obtener lista de sucursales para seleccion
        $tablas    = array("a" => "perfiles_usuario","b" => "componentes_usuario","c" => "sucursales");
        $columnas  = array("codigo" => "c.codigo","nombre" => "c.nombre");
        $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil AND a.codigo_usuario = '".$sesion_codigo_usuario."'
        AND b.id_componente = '".$componente->id."' AND c.codigo_empresa = '".$empresa."'";

        $consulta_sucursales = SQL::seleccionar($tablas, $columnas, $condicion);
    }

    $error_sucursales = false;
    if (SQL::filasDevueltas($consulta_sucursales)){

        $pestana_sucursales   = array();
        $pestana_sucursales[] = array(HTML::boton("botonSeleccionarTodos", $textos["SELECCIONAR_TODOS"], "seleccionar_todas_sucursales();", "", array()));

        while ($datos_sucursales = SQL::filaEnObjeto($consulta_sucursales)){

            $codigo_sucursal = $datos_sucursales->codigo;
            $nombreSucursal  = $datos_sucursales->nombre;

            $pestana_sucursales[]   = array(
                HTML::marcaChequeo("sucursales[".$datos_sucursales->codigo."]", $datos_sucursales->nombre, $datos_sucursales->codigo, false, array("title" => $textos["AYUDA_SUCURSAL"], "id" => "sucursales_".$datos_sucursales->codigo, "class" => "sucursales_electrodomesticos"))
            );
        }
    } else {
        $error_sucursales = true;
    }

    $consulta_planillas = SQL::seleccionar(array("planillas"),array("*"),"codigo>0");
    $error_planillas = false;
    if (SQL::filasDevueltas($consulta_planillas)){

        $planillas[0] = '';

        while ($datos_planilla = SQL::filaEnObjeto($consulta_planillas)){
            $planillas[$datos_planilla->codigo] = $datos_planilla->descripcion;
        }

    } else {
        $error_planillas = true;
    }

    $consulta_fechas_planillas = SQL::seleccionar(array("fechas_planillas"),array("*"),"codigo_planilla>0");
    $error_fechas_planilla = false;
    if (SQL::filasDevueltas($consulta_fechas_planillas)){

        while ($datos_fechas_planillas = SQL::filaEnObjeto($consulta_fechas_planillas)){
            $fechas_planillas[$datos_fechas_planillas->codigo_planilla."|".$datos_fechas_planillas->fecha] = $datos_fechas_planillas->fecha;
        }
    } else {
        $error_fechas_planilla = true;
    }

    $consulta_empleados = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!=''");
    $error_empleados = false;
    if (!SQL::filasDevueltas($consulta_empleados)){
        $error_empleados = true;
    }

    $codigo_tasa_salud = SQL::obtenerValor("preferencias","valor","variable='tasa_salud' AND tipo_preferencia=1");
    $error_tasa_salud  = false;
    if (!$codigo_tasa_salud || $codigo_tasa_salud==0){
        $error_tasa_salud = true;
    }

    $codigo_tasa_pension = SQL::obtenerValor("preferencias","valor","variable='tasa_pension' AND tipo_preferencia=1");
    $error_tasa_pension  = false;
    if (!$codigo_tasa_pension || $codigo_tasa_pension==0){
        $error_tasa_pension = true;
    }

    $codigo_transaccion_contable_salud = SQL::obtenerValor("preferencias","valor","tipo_preferencia='1' AND variable='codigo_transaccion_nomina_pagar_salud'");
    $error_transaccion_salud = false;
    if (!$codigo_transaccion_contable_salud || $codigo_transaccion_contable_salud==0){
        $error_transaccion_salud = true;
    }
    $codigo_transaccion_contable_pension = SQL::obtenerValor("preferencias","valor","tipo_preferencia='1' AND variable='codigo_transaccion_nomina_pagar_pension'");
    $error_transaccion_pension = false;
    if (!$codigo_transaccion_contable_pension || $codigo_transaccion_contable_pension==0){
        $error_transaccion_pension = true;
    }

    if (!$error_sucursales && !$error_planillas && !$error_fechas_planilla && !$error_empleados && !$error_tasa_salud && !$error_tasa_pension && !$error_transaccion_salud && !$error_transaccion_pension){

        $ano          = date("Y");
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

        $formularios["PESTANA_SUCURSALES"] = $pestana_sucursales;

        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::listaSeleccionSimple("*ano_generacion", $textos["ANO_PLANILLA"],$ano_planilla,$ano, array("title" => $textos["AYUDA_ANO_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();")),
                HTML::listaSeleccionSimple("*mes_generacion", $textos["MES_PLANILLA"],$meses,$mes, array("title" => $textos["AYUDA_MES_PLANILLA"], "class" => "selectorFecha", "onchange"=>"cargarFechaPago2();"))
            ),
            array(
                HTML::mostrarDato("datos_planilla",$textos["DATOS_PLANILLA"],"")
               .HTML::campoOculto("id_modulo", $id_modulo)
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
                HTML::campoOculto("segunda_quincena",$textos["SEGUNDA_QUINCENA"])

            ),
            array(
                HTML::campoOculto("codigo_tasa_salud",$codigo_tasa_salud),
                HTML::campoOculto("codigo_tasa_pension",$codigo_tasa_pension),
                HTML::campoOculto("codigo_transaccion_contable_salud",$codigo_transaccion_contable_salud),
                HTML::campoOculto("codigo_transaccion_contable_pension",$codigo_transaccion_contable_pension)

            )
        );

        // Definicion de botones
        $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar"));

        $contenido = HTML::generarPestanas($formularios, $botones);
    } else {

        $contenido = "";
        $error     = $textos["VERIFICAR_DATOS"];
        if ($error_sucursales){
            $error .= $textos["ERROR_SUCURSALES"];
        }
        if($error_planillas){
            $error .= $textos["ERROR_PLANILLAS"];
        }
        if($error_fechas_planilla){
            $error .= $textos["ERROR_FECHAS_PLANILLAS"];
        }
        if($error_empleados){
            $error .= $textos["ERROR_EMPLEADOS"];
        }
        if ($error_tasa_salud){
            $error .= $textos["ERROR_TASA_SALUD_CERO"];
        }
        if ($error_tasa_pension){
            $error .= $textos["ERROR_TASA_PENSION_CERO"];
        }
        if ($error_transaccion_salud){
            $error .= $textos["ERROR_TRANSACCION_SALUD"];
        }
        if ($error_transaccion_pension){
            $error .= $textos["ERROR_TRANSACCION_PENSION"];
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} elseif (!empty($forma_procesar)) {

    $periodo = array(
        "1" => $textos["MENSUAL"],
        "2" => $textos["PRIMERA_QUINCENA"],
        "3" => $textos["SEGUNDA_QUINCENA"],
        "4" => $textos["PRIMER_SEMANA"],
        "5" => $textos["SEGUNDA_SEMANA"],
        "6" => $textos["TERCERA_SEMANA"],
        "7" => $textos["CUARTA_SEMANA"],
        "8" => $textos["QUINTA_SEMANA"]
    );

    // Asumir por defecto que no hubo error
    $error = false;

    if (!isset($forma_sucursales)){
        $error   = true;
        $mensaje = $textos["ERROR_SUCURSAL_VACIA"];

    } else if (empty($forma_codigo_planilla)){
        $error   = true;
        $mensaje = $textos["ERROR_CODIGO_PLANILLA"];

    } else if (empty($forma_fecha_pago)){
        $error   = true;
        $mensaje = $textos["ERROR_FECHA_PAGO"];

    } else if (empty($forma_periodo)){
        $error   = true;
        $mensaje = $textos["ERROR_PERIODO"];

    } else {

        $porcentajeSalud   = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa='$forma_codigo_tasa_salud' AND fecha <= '$forma_fecha_pago' ORDER BY fecha DESC LIMIT 0,1");
        $porcentajePension = SQL::obtenerValor("vigencia_tasas","porcentaje","codigo_tasa='$forma_codigo_tasa_pension' AND fecha <= '$forma_fecha_pago' ORDER BY fecha DESC LIMIT 0,1");


        if ((!$porcentajeSalud || $porcentajeSalud<=0) && (!$porcentajePension || $porcentajePension<=0)){

            $error = true;
            $mensaje = $textos["VERIFICAR_DATOS"];
            if($porcentajeSalud <= 0 || $porcentajeSalud == false){
                $mensaje .= $textos["ERROR_TASA_SALUD_CERO"];
            }
            if($porcentajePension <= 0 || $porcentajePension == false){
                $mensaje .= $textos["ERROR_TASA_PENSION_CERO"];
            }

        } else {

            $mensaje = $textos["EXITO_LIQUIDACION"].$periodo[$forma_periodo].$textos["EXITO_LIQUIDACION2"].$forma_fecha_pago;

            if ($forma_mes_generacion == 2){
                if (($forma_ano_generacion % 4 ==0) && ($forma_ano_generacion % 100 !=0 || $forma_ano_generacion % 400 == 0)){
                    $dia_fin = 29;
                } else {
                    $dia_fin = 28;
                }
            } else {
                    $dia_fin = 30;
            }

            $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='".$forma_codigo_planilla."'");

            $ano_generacion = $forma_ano_generacion;
            $mes_generacion = $forma_mes_generacion;

            $contadorNO = 0;
            $mensajeS   = $textos["ERROR_PERIODO1"];
            $mensajeS  .= $textos["ERROR_PERIODO2"];

            foreach($forma_sucursales as $codigo_sucursal){
                $consulta = SQL::seleccionar(array("periodos_contables_modulos"),array("*"),"codigo_sucursal='".$codigo_sucursal."' AND ('".$forma_fecha_pago."' BETWEEN fecha_inicio AND fecha_fin) AND id_modulo='".$forma_id_modulo."'");

                if(SQL::filasDevueltas($consulta)){
                }else{
                    $nombreSucursal = SQL::obtenerValor("sucursales","nombre","codigo='".$codigo_sucursal."'");
                    $mensajeS.="- ".$nombreSucursal."\n";
                    $contadorNO+=1;
                }
            }

            if($contadorNO>0){
                $error   = true;
                $mensaje = $mensajeS;
            }else{

                $id_empleados = array();//Array que lleva los ids de los empleados

                foreach ($forma_sucursales AS $codigo_sucursal) {
                    //RECORRER LOS MOVIMIENTOS DE SALARIOS PARA SUMAR EN EL IBC DE SALUD
                    $consultaMS = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND contabilizado != '1'");

                    if(SQL::filasDevueltas($consultaMS)){//Si existen movimientos en esta sucursal para esta planilla en esta tabla entonces siga

                        while($datosMS = SQL::filaEnObjeto($consultaMS)){
                            $consultaESE = SQL::obtenerValor("entidades_salud_empleados","codigo_entidad_salud","documento_identidad_empleado='".$datosMS->documento_identidad_empleado."' AND codigo_empresa='".$datosMS->codigo_empresa."' AND fecha_ingreso='".$datosMS->fecha_ingreso_empresa."' AND fecha_inicio_salud <= '".$forma_fecha_pago."' ORDER BY fecha_inicio_salud DESC LIMIT 0,1");

                            if($consultaESE){//Si el empleado tiene entidad promotora de salud entonces calcule el ibc
                                $estado_ibc_salud = SQL::obtenerValor("transacciones_contables_empleado","ibc_salud","codigo='".$datosMS->codigo_transaccion_contable."'");

                                if($estado_ibc_salud != '0'){//Si el movimiento acumula para salud => siga
                                    $consulta = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='".$datosMS->documento_identidad_empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal <= '".$forma_fecha_pago."' AND IF(fecha_retiro_sucursal != '0000-00-00',fecha_retiro_sucursal >= '".$forma_fecha_pago."',fecha_retiro_sucursal = '0000-00-00') AND estado = 1");

                                    if(SQL::filasDevueltas($consulta)){//Si esta dentro de una sucursal en esa fecha y esta activo siga
                                        $datosSCE = SQL::filaEnObjeto($consulta);

                                        if((($forma_periodo == '2' || $forma_periodo == '3') && $datosSCE->forma_descuento_salud == '1')||
                                            ($forma_periodo == '3' && $datosSCE->forma_descuento_salud == '2')||($forma_periodo != '2' && $forma_periodo != '3')){
                                            //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." ingreso al calculo");

                                            if($estado_ibc_salud == '1'){// Si tiene marca si

                                                if(isset($sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal])){

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                                //echo var_dump("Salud: Adiciona al empleado ".$datosMS->documento_identidad_empleado." en la sucursal ".$codigo_sucursal." valor = ".$datosMS->valor_movimiento." con sentido ".$datosMS->sentido." de la tabla ".$datosMS->tabla);
                                            }else if($estado_ibc_salud == '2'){// Si tiene marca 40%

                                                if(isset($sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal])){
                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                                //echo var_dump("Salud: Adiciona al empleado40 ".$datosMS->documento_identidad_empleado." en la sucursal ".$codigo_sucursal." valor = ".$datosMS->valor_movimiento." con sentido ".$datosMS->sentido." de la tabla ".$datosMS->tabla);
                                            }
                                            $id_empleados[$datosMS->documento_identidad_empleado]                      = $datosMS->documento_identidad_empleado;
                                            $entidades_Salud[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $consultaESE;

                                            //echo var_dump("Al empleado ".$datosMS->documento_identidad_empleado." se le contabilizo para salud en tabla: ".$datosMS->tabla);
                                        }
                                    }else{
                                        //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." no esta dentro del rango de fechas en ninguna sucursal o no esta activo");
                                    }
                                }else{
                                    //echo var_dump("La transaccion ".$datosMS->codigo_transaccion_contable." no aplica para el aporte a salud de empleado ".$datosMS->documento_identidad_empleado);
                                }
                            }else{
                                //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." no tiene entidad de salud");
                            }
                        }
                    }else{
                        //echo var_dump("Filas vacias en planilla para sucursal: ".$codigo_sucursal);
                    }
                }

                $contador_registros_salud = 0;

                /*echo var_dump("Datos Salud");
                echo var_dump("Ids de los empleados");
                echo var_dump($id_empleados);
                echo var_dump("IBC");
                if(isset($sucursal_empleado)){
                    echo var_dump($sucursal_empleado);
                }else{
                    echo var_dump("No se encontro ibc");
                }
                echo var_dump("Base 40");
                if(isset($sucursal_empleado40)){
                    echo var_dump($sucursal_empleado40);
                }else{
                    echo var_dump("No se encontro ibc40");
                }*/

                foreach($id_empleados AS $id){

                    foreach ($forma_sucursales AS $codigo_sucursal) {

                        $consulta  = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='".$id."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal <= '".$forma_fecha_pago."' AND IF(fecha_retiro_sucursal != '0000-00-00',fecha_retiro_sucursal >= '".$forma_fecha_pago."',fecha_retiro_sucursal = '0000-00-00') AND estado = 1");
                        if(SQL::filasDevueltas($consulta)){
                            $datosSCE  = SQL::filaEnObjeto($consulta);

                            $consulta2 = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo='".$forma_codigo_transaccion_contable_salud."'");
                            $datosTCE  = SQL::filaEnObjeto($consulta2);

                            if($forma_periodo == '3' && $datosSCE->forma_descuento_salud == '2'){//Para cuando el pago de salud se efectua en segunda quincena mirar movimientos pendientes de la primera quincena pa acumular en el ibc
                                $consultaMS = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='2' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");

                                if(SQL::filasDevueltas($consultaMS)){

                                    while($datosMS = SQL::filaEnObjeto($consultaMS)){
                                        $consultaCCE      = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='".$id."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal <= '".$datosMS->fecha_pago_planilla."' AND IF(fecha_retiro_sucursal != '0000-00-00',fecha_retiro_sucursal >= '".$datosMS->fecha_pago_planilla."',fecha_retiro_sucursal = '0000-00-00') AND estado = 1");

                                        if(SQL::filasDevueltas($consultaCCE)){

                                            $ibc_salud  = SQL::obtenerValor("transacciones_contables_empleado","ibc_salud","codigo='".$datosMS->codigo_transaccion_contable."'");

                                            if($ibc_salud == '1'){// Si tiene marca si

                                                if(isset($sucursal_empleado[$id][$codigo_sucursal])){

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$id][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$id][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$id][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$id][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                            }else if($estado_ibc_salud == '2'){// Si tiene marca 40%
                                                if(isset($sucursal_empleado40[$id][$codigo_sucursal])){
                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{
                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if ($tipo_planilla == '1'){
                                $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                            } else if ($tipo_planilla == '2'){
                                if ($forma_periodo == '2'){
                                    $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                    $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-15";
                                } else {
                                    $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-16";
                                    $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                }
                            }

                            if($forma_periodo == '3' && $datosSCE->forma_descuento_salud == '2'){
                                $dias_trabajados = SQL::obtenerValor("movimientos_salarios","SUM(dias_trabajados)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                            }else{
                                $dias_trabajados = SQL::obtenerValor("movimientos_salarios","SUM(dias_trabajados)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                            }

                            if(!$dias_trabajados){

                                if($forma_periodo == '1'){
                                    $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_tiempo)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_trabajados   = 30-$dias_incapacidad - $dias_no_laborados;
                                }elseif($forma_periodo == '2' && $datosSCE->forma_descuento_salud == '1'){
                                    $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_tiempo)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_trabajados   = 15-$dias_incapacidad-$dias_no_laborados;
                                }elseif($forma_periodo == '3'){

                                    if($datosSCE->forma_descuento_salud == '1'){
                                        $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_tiempo)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_trabajados   = 15-$dias_incapacidad-$dias_no_laborados;
                                    }else{
                                        $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_tiempo)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_trabajados   = 30-$dias_incapacidad-$dias_no_laborados;
                                    }
                                }
                            }

                            if(!isset($sucursal_empleado[$id][$codigo_sucursal])){
                                $sucursal_empleado[$id][$codigo_sucursal] = 0;
                            }

                            $ibc = $sucursal_empleado[$id][$codigo_sucursal];

                            if(isset($sucursal_empleado40[$id][$codigo_sucursal])){
                                $basecon40       = $sucursal_empleado[$id][$codigo_sucursal] + $sucursal_empleado40[$id][$codigo_sucursal];
                                $valor40debase40 = ($basecon40 * 40)/100;

                                if($sucursal_empleado40[$id][$codigo_sucursal] >= $valor40debase40){
                                    $ibc += $sucursal_empleado40[$id][$codigo_sucursal];
                                }
                            }

                            $valor_movimiento     = round(($ibc*$porcentajeSalud)/100);
                            $codigo_entidad_salud = $entidades_Salud[$id][$codigo_sucursal];

                            $datos = array(
                                "ano_generacion"                => $ano_generacion,
                                "mes_generacion"                => $mes_generacion,
                                "codigo_planilla"               => $forma_codigo_planilla,
                                "periodo_pago"                  => $forma_periodo,
                                "codigo_transaccion_contable"   => $datosSCE->codigo_transaccion_salud,
                                 ///////////////////////////////
                                "codigo_empresa"                => $datosSCE->codigo_empresa,
                                "documento_identidad_empleado"  => $datosSCE->documento_identidad_empleado,
                                "fecha_ingreso_empresa"         => $datosSCE->fecha_ingreso,
                                "codigo_sucursal"               => $datosSCE->codigo_sucursal,
                                "fecha_ingreso_sucursal"        => $datosSCE->fecha_ingreso_sucursal,
                                ///////////////////////////////
                                "fecha_pago_planilla"           => $forma_fecha_pago,
                                "fecha_ingreso_planilla"        => date("Y-m-d"),
                                "fecha_inicio_pago"             => $fecha_inicio_pago,
                                "fecha_hasta_pago"              => $fecha_fin_pago,
                                ///////////////////////////////
                                "codigo_empresa_auxiliar"       => $datosSCE->codigo_empresa_auxiliar,
                                "codigo_anexo_contable"         => $datosSCE->codigo_anexo_contable,
                                "codigo_auxiliar_contable"      => $datosSCE->codigo_auxiliar,
                                ///////////////////////////////
                                "codigo_entidad_salud"          => $codigo_entidad_salud,
                                ///////////////////////////////
                                "codigo_contable"               => $datosTCE->codigo_contable,
                                "sentido"                       => $datosTCE->sentido,
                                "codigo_transaccion_tiempo"     => "0",
                                "dias_trabajados"               => $dias_trabajados,
                                "salario"                       => $datosSCE->salario,
                                "valor_movimiento"              => $valor_movimiento,
                                "ibc_salud"                     => $ibc,
                                "porcentaje_tasa_salud"         => $porcentajeSalud,
                                "contabilizado"                 => "0",
                                "codigo_usuario_genera"         => $sesion_codigo_usuario
                            );

                            if ($datosSCE->codigo_transaccion_salud != 0 && $valor_movimiento > 0){

                                if(!SQL::existeItem("movimientos_salud","documento_identidad_empleado",$id,"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$datosSCE->codigo_sucursal."'")){
                                    $insertar = SQL::insertar("movimientos_salud",$datos);
                                    if(!$insertar){
                                        $error   = true;
                                        $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                    }else{
                                        //echo var_dump("Se inserta en salud el registro con exito para el empleado ".$id." por un valor de ".$valor_movimiento);
                                        $contador_registros_salud += 1;
                                    }
                                }else{
                                    //echo var_dump("Ya existia el registro, entonces lo ignora");
                                }
                            }else{
                                //echo var_dump("No ingreso porque rompe condiciones ".$id);
                            }
                        }
                    }
                }

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                unset($id_empleados);
                unset($sucursal_empleado);
                unset($sucursal_empleado40);

                $id_empleados = array();//Array que lleva los ids de los empleados

                foreach ($forma_sucursales AS $codigo_sucursal) {
                    //RECORRER LOS MOVIMIENTOS DE SALARIOS PARA SUMAR EN EL IBC DE PENSION
                    $consultaMS = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND contabilizado != '1'");
                    if(SQL::filasDevueltas($consultaMS)){//Si existen movimientos en esta sucursal para esta planilla en esta tabla entonces siga

                        while($datosMS = SQL::filaEnObjeto($consultaMS)){
                            $consultaESE = SQL::obtenerValor("entidades_pension_empleados","codigo_entidad_pension","documento_identidad_empleado='".$datosMS->documento_identidad_empleado."' AND codigo_empresa='".$datosMS->codigo_empresa."' AND fecha_ingreso='".$datosMS->fecha_ingreso_empresa."' AND fecha_inicio_pension <= '".$forma_fecha_pago."' ORDER BY fecha_inicio_pension DESC LIMIT 0,1");

                            if($consultaESE){//Si el empleado tiene entidad promotora de pension entonces calcule el ibc
                                $estado_ibc_pension = SQL::obtenerValor("transacciones_contables_empleado","ibc_pension","codigo='".$datosMS->codigo_transaccion_contable."'");

                                if($estado_ibc_pension != '0'){//Si el movimiento acumula para pension => siga
                                    $consulta = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='".$datosMS->documento_identidad_empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal <= '".$forma_fecha_pago."' AND IF(fecha_retiro_sucursal != '0000-00-00',fecha_retiro_sucursal >= '".$forma_fecha_pago."',fecha_retiro_sucursal = '0000-00-00') AND estado = 1");

                                    if(SQL::filasDevueltas($consulta)){//Si esta dentro de una sucursal en esa fecha y esta activo siga
                                        $datosSCE = SQL::filaEnObjeto($consulta);

                                        if((($forma_periodo == '2' || $forma_periodo == '3') && $datosSCE->forma_descuento_pension == '1')||
                                        ($forma_periodo == '3' && $datosSCE->forma_descuento_pension == '2')||($forma_periodo != '2' && $forma_periodo != '3')){
                                            //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." ingreso al calculo");

                                            if($estado_ibc_pension == '1'){// Si tiene marca si

                                                if(isset($sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal])){

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                                //echo var_dump("Pension: Adiciona al empleado ".$datosMS->documento_identidad_empleado." en la sucursal ".$codigo_sucursal." valor = ".$datosMS->valor_movimiento." con sentido ".$datosMS->sentido." de la tabla ".$datosMS->tabla);
                                            }else if($estado_ibc_pension == '2'){// Si tiene marca 40%

                                                if(isset($sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal])){

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                                //echo var_dump("Pension: Adiciona al empleado40 ".$datosMS->documento_identidad_empleado." en la sucursal ".$codigo_sucursal." valor = ".$datosMS->valor_movimiento." con sentido ".$datosMS->sentido." de la tabla ".$datosMS->tabla);
                                            }
                                            $id_empleados[$datosMS->documento_identidad_empleado]                        = $datosMS->documento_identidad_empleado;
                                            $entidades_Pension[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $consultaESE;

                                            //echo var_dump("Al empleado ".$datosMS->documento_identidad_empleado." se le contabilizo para pension en tabla: ".$datosMS->tabla);
                                        }
                                    }/*else{
                                        //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." no esta dentro del rango de fechas en ninguna sucursal o no esta activo");
                                    }*/
                                }/*else{
                                    //echo var_dump("La transaccion ".$datosMS->codigo_transaccion_contable." no aplica para el aporte a pension de empleado ".$datosMS->documento_identidad_empleado);
                                }*/
                            }/*else{
                                //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." no tiene entidad de pension");
                            }*/
                        }
                    }/*else{
                        //echo var_dump("Filas vacias en planilla para sucursal: ".$codigo_sucursal);
                    }*/
                }

                $contador_registros_pension = 0;

                /*echo var_dump("Datos Pension");
                echo var_dump("Ids de los empleados");
                echo var_dump($id_empleados);
                echo var_dump("IBC");
                if(isset($sucursal_empleado)){
                    echo var_dump($sucursal_empleado);
                }else{
                    echo var_dump("No se encontro ibc");
                }
                echo var_dump("Base 40");
                if(isset($sucursal_empleado40)){
                    echo var_dump($sucursal_empleado40);
                }else{
                    echo var_dump("No se encontro ibc40");
                }*/

                foreach($id_empleados AS $id){

                    foreach ($forma_sucursales AS $codigo_sucursal) {

                        $consulta  = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='".$id."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal <= '".$forma_fecha_pago."' AND IF(fecha_retiro_sucursal != '0000-00-00',fecha_retiro_sucursal >= '".$forma_fecha_pago."',fecha_retiro_sucursal = '0000-00-00') AND estado = 1");

                        if(SQL::filasDevueltas($consulta)){
                            $datosSCE  = SQL::filaEnObjeto($consulta);

                            $consulta2 = SQL::seleccionar(array("transacciones_contables_empleado"),array("*"),"codigo='".$forma_codigo_transaccion_contable_pension."'");
                            $datosTCE  = SQL::filaEnObjeto($consulta2);

                            if($forma_periodo == '3' && $datosSCE->forma_descuento_pension == '2'){//Para cuando el pago de pension se efectua en segunda quincena mirar movimientos pendientes de la primera quincena pa acumular en el ibc
                                $consultaMS = SQL::seleccionar(array("consulta_datos_planilla"),array("*"),"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='2' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");

                                if(SQL::filasDevueltas($consultaMS)){

                                    while($datosMS = SQL::filaEnObjeto($consultaMS)){
                                        $consultaCCE      = SQL::seleccionar(array("consulta_contrato_empleado"),array("*"),"documento_identidad_empleado='".$id."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal <= '".$datosMS->fecha_pago_planilla."' AND IF(fecha_retiro_sucursal != '0000-00-00',fecha_retiro_sucursal >= '".$datosMS->fecha_pago_planilla."',fecha_retiro_sucursal = '0000-00-00') AND estado = 1");

                                        if(SQL::filasDevueltas($consultaCCE)){

                                            $ibc_pension = SQL::obtenerValor("transacciones_contables_empleado","ibc_pension","codigo='".$datosMS->codigo_transaccion_contable."'");
                                            if($ibc_pension == '1'){// Si tiene marca si

                                                if(isset($sucursal_empleado[$id][$codigo_sucursal])){

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$id][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$id][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$id][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$id][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                            }else if($estado_ibc_pension == '2'){// Si tiene marca 40%

                                                if(isset($sucursal_empleado40[$id][$codigo_sucursal])){

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{

                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$id][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if ($tipo_planilla == '1'){
                                $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                            } else if ($tipo_planilla == '2'){
                                if ($forma_periodo == '2'){
                                    $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-01";
                                    $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-15";
                                } else {
                                    $fecha_inicio_pago = $forma_ano_generacion."-".$forma_mes_generacion."-16";
                                    $fecha_fin_pago    = $forma_ano_generacion."-".$forma_mes_generacion."-".$dia_fin;
                                }
                            }

                            if($forma_periodo == '3' && $datosSCE->forma_descuento_pension == '2'){
                                $dias_trabajados = SQL::obtenerValor("movimientos_salarios","SUM(dias_trabajados)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                            }else{
                                $dias_trabajados = SQL::obtenerValor("movimientos_salarios","SUM(dias_trabajados)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                            }

                            if(!$dias_trabajados){
                                if($forma_periodo == '1'){
                                    $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_tiempo)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_trabajados   = 30-$dias_incapacidad-$dias_no_laborados;
                                }elseif($forma_periodo == '2' && $datosSCE->forma_descuento_pension == '1'){
                                    $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_temporada)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                    $dias_trabajados   = 15-$dias_incapacidad-$dias_no_laborados;
                                }elseif($forma_periodo == '3'){
                                    if($datosSCE->forma_descuento_pension == '1'){
                                        $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_tiempo)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_trabajados   = 15-$dias_incapacidad;
                                    }else{
                                        $dias_incapacidad  = SQL::obtenerValor("reporte_incapacidades","COUNT(fecha_incapacidad)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_no_laborados = SQL::obtenerValor("movimiento_tiempos_no_laborados_dias","COUNT(fecha_inicio_tiempo)","ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND codigo_sucursal='".$codigo_sucursal."' AND documento_identidad_empleado='".$id."'");
                                        $dias_trabajados   = 30-$dias_incapacidad-$dias_no_laborados;
                                    }
                                }
                            }

                            if(!isset($sucursal_empleado[$id][$codigo_sucursal])){
                                $sucursal_empleado[$id][$codigo_sucursal] = 0;
                            }

                            $ibc = $sucursal_empleado[$id][$codigo_sucursal];

                            if(isset($sucursal_empleado40[$id][$codigo_sucursal])){
                                $basecon40       = $sucursal_empleado[$id][$codigo_sucursal] + $sucursal_empleado40[$id][$codigo_sucursal];
                                $valor40debase40 = ($basecon40 * 40)/100;

                                if($sucursal_empleado40[$id][$codigo_sucursal] >= $valor40debase40){
                                    $ibc += $sucursal_empleado40[$id][$codigo_sucursal];
                                }
                            }

                            $valor_movimiento       = round(($ibc*$porcentajePension)/100);

                            $codigo_entidad_pension = $entidades_Pension[$id][$codigo_sucursal];

                            $datos = array(
                                "ano_generacion"                => $ano_generacion,
                                "mes_generacion"                => $mes_generacion,
                                "codigo_planilla"               => $forma_codigo_planilla,
                                "periodo_pago"                  => $forma_periodo,
                                "codigo_transaccion_contable"   => $datosSCE->codigo_transaccion_pension,
                                 ///////////////////////////////
                                "codigo_empresa"                => $datosSCE->codigo_empresa,
                                "documento_identidad_empleado"  => $datosSCE->documento_identidad_empleado,
                                "fecha_ingreso_empresa"         => $datosSCE->fecha_ingreso,
                                "codigo_sucursal"               => $datosSCE->codigo_sucursal,
                                "fecha_ingreso_sucursal"        => $datosSCE->fecha_ingreso_sucursal,
                                ///////////////////////////////
                                "fecha_pago_planilla"           => $forma_fecha_pago,
                                "fecha_ingreso_planilla"        => date("Y-m-d"),
                                "fecha_inicio_pago"             => $fecha_inicio_pago,
                                "fecha_hasta_pago"              => $fecha_fin_pago,
                                ///////////////////////////////
                                "codigo_empresa_auxiliar"       => $datosSCE->codigo_empresa_auxiliar,
                                "codigo_anexo_contable"         => $datosSCE->codigo_anexo_contable,
                                "codigo_auxiliar_contable"      => $datosSCE->codigo_auxiliar,
                                ///////////////////////////////
                                "codigo_entidad_pension"          => $codigo_entidad_pension,
                                ///////////////////////////////
                                "codigo_contable"               => $datosTCE->codigo_contable,
                                "sentido"                       => $datosTCE->sentido,
                                "codigo_transaccion_tiempo"     => "0",
                                "dias_trabajados"               => $dias_trabajados,
                                "salario"                       => $datosSCE->salario,
                                "valor_movimiento"              => $valor_movimiento,
                                "ibc_pension"                   => $ibc,
                                "porcentaje_tasa_pension"       => $porcentajePension,
                                "contabilizado"                 => "0",
                                "codigo_usuario_genera"         => $sesion_codigo_usuario
                            );

                            if ($datosSCE->codigo_transaccion_pension != 0 && $valor_movimiento > 0){

                                if(!SQL::existeItem("movimientos_pension","documento_identidad_empleado",$id,"ano_generacion='".$forma_ano_generacion."' AND mes_generacion='".$forma_mes_generacion."' AND codigo_planilla='".$forma_codigo_planilla."' AND periodo_pago='".$forma_periodo."' AND codigo_sucursal='".$datosSCE->codigo_sucursal."'")){
                                    $insertar = SQL::insertar("movimientos_pension",$datos);
                                    if(!$insertar){
                                        $error   = true;
                                        $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                                    }else{
                                        //echo var_dump("Se inserta en pension el registro con exito para el empleado ".$id." por un valor de ".$valor_movimiento);
                                        $contador_registros_pension += 1;
                                    }
                                }else{
                                    //echo var_dump("Ya existia el registro, entonces lo ignora");
                                }
                            }else{
                                //echo var_dump("No ingreso porque rompe condiciones ".$id);
                            }
                        }
                    }
                }

                if($error == false && $contador_registros_salud == 0 && $contador_registros_pension == 0){
                    $mensaje = $textos["LIQUIDACION_SALUD_VACIA"];
                }
            }
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
