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
* modificarlo  bajo los términos de la Licencia Pública General GNU
* publicada por la Fundación para el Software Libre, ya sea la versión 3
* de la Licencia, o (a su elección) cualquier versión posterior.
*
* Este programa se distribuye con la esperanza de que sea útil, pero
* SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita MERCANTIL o
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

require("clases/clases.php");

if(isset($url_cargar_dias_por_tomar) && !empty($url_dias) && !empty($url_documento_identidad_empleado)){

    $error     =  false;
    $mensaje   = "";
    $contenido = "";
    $esta_dentro_rango = false;
    
    $condicion = "codigo_sucursal='$url_codigo_sucursal' AND documento_identidad_empleado='$url_documento_identidad_empleado' AND ('$url_fecha_inicio_tiempo' BETWEEN fecha_inicio_tiempo AND fecha_final_tiempo)";

    $existe_registro = SQL::existeItem("movimiento_liquidacion_vacaciones","codigo_sucursal",$url_codigo_sucursal,$condicion);
    
    $esta_dentro_rango = determinarEstaRangoFecha($url_codigo_sucursal,$url_fecha_inicio_tiempo,$url_dias);
    
    
    $condicion    = "codigo_sucursal='$url_codigo_sucursal' AND documento_identidad_empleado='$url_documento_identidad_empleado'";
    $datos_empleado = generarInformacionEmpleado($url_fecha_inicio_tiempo,$url_documento_identidad_empleado);
    
    $dias_tomados = diasTomadosAnio($datos_empleado[0],$url_fecha_inicio_tiempo,$condicion);
    $contenido = determinarDatosVacacion($url_fecha_inicio_tiempo,$url_dias);
    
    $dias_pendientes = (15-(int)$dias_tomados);

    if($dias_pendientes==0){
        $error     =  true;
        $mensaje   = $textos["ERROR_NO_TIENE_PENDIENTES"];
    }elseif($existe_registro || $esta_dentro_rango){
        $error     =  true;
        $mensaje   = $textos["EXISTE_DATOS_FECHA"];
    }elseif((int)$url_dias>(int)$dias_pendientes){
        $error     =  true;
        $mensaje   = $textos["ERROR_DIAS_PENDIENTES"];
    }
    
    $respuesta = array();
    $respuesta[] = $error;
    $respuesta[] = $mensaje;
    $respuesta[] = $contenido[0];
    $respuesta[] = $contenido[1];
    $respuesta[] = $dias_pendientes;
   
    HTTP::enviarJSON($respuesta);
    exit;
}

////////////////Determino de sueldos por pagar//////////////////
if(isset($url_determino_salarios_pendientes) && !empty ($url_fecha_liquidacion) && !empty ($url_documento_empleado) && !empty ($url_dias_tomados)){
    $respuesta = calculoLiquidacionVacaciones($url_fecha_liquidacion,$url_documento_empleado,$url_codigo_sucursal,$url_dias_tomados,$url_forma_liquidacion,$textos,true);
    HTTP::enviarJSON($respuesta);
    exit;
}

if(isset($url_verificar)){
    $condicion_extra = "id_sucursal='$url_codigo_sucursal'";
    echo SQL::datosAutoCompletar("seleccion_empleados", $url_q, $condicion_extra);
    exit;
}
/// Generar el formulario para la captura de datos
if (!empty($url_generar)){
 
    $consulta = SQL::seleccionar(array("sucursales"),array("*"),"codigo > 0","","nombre");
    if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){
         while ($datos = SQL::filaEnObjeto($consulta)){
            $sucursales[$datos->codigo] = $datos->nombre;
        }
    }else{
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

    $mensaje    = $textos["MENSAJE_FALTA_DATOS"];
    $continuar  = true;
    $respuesta  = array();

    $anio_actual = date("Y");

    $consulta_empleados          = SQL::seleccionar(array("ingreso_empleados"),array("*"),"documento_identidad_empleado!='0' AND estado='1'");
    $consulta_domingos_festivos  = SQL::seleccionar(array("domingos_festivos"),array("*"),"anio='$anio_actual'");

    if(SQL::filasDevueltas($consulta_empleados) == 0){
        $mensaje   .= $textos["NO_EXISTEN_EMPLEADOS"];
        $continuar  = false;
    }
    if(count($sucursales) == 0){
        $mensaje   .= $textos["NO_EXISTEN_SUCURSALES"];
        $continuar  = false;
    }
    if(SQL::filasDevueltas($consulta_domingos_festivos) == 0){
        $mensaje   .= $textos["NO_EXISTEN_DOMINGOS_FESTIVOS"];
        $continuar  = false;
    }

    $respuesta[0] = $mensaje;
    $respuesta[1] = "";
    $respuesta[2] = "";

    if($continuar){

        $forma_liquidacion = array(
            "1" => $textos["AFECTA_PLANILLA"],
            "2" => $textos["LIQUIDACION_TOTAL"]
        );

        $error  = "";
        $titulo = $componente->nombre;
        $id_modulo = SQL::obtenerValor("componentes","id_modulo","id = '$componente->id'");

        $empresa  = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$sesion_sucursal."'");

        $anexos  = array();
        $consulta = SQL::seleccionar(array("auxiliares_contables,job_anexos_contables"), array("codigo_anexo_contable","job_anexos_contables.descripcion"), "codigo_empresa='".$empresa."' AND codigo_anexo_contable=job_anexos_contables.codigo", "codigo_anexo_contable","job_anexos_contables.descripcion");

        while($fila = SQL::filaEnArreglo($consulta)){
            $anexos[$fila[0]] = $fila[1];
        }
        /// Definici�n de pesta�a Basica 
        $formularios["PESTANA_BASICA"] = array(
                array(
                     HTML::listaSeleccionSimple("*forma_liquidacion", $textos["FORMA_LIQUIDACION"],$forma_liquidacion,"1", array("title" => $textos["AYUDA_FORMA_LIQUIDACION"],"onchange" => "cargarTablaLiquidacion();determinarDatosDias();"))
                ),
                array(
                     HTML::listaSeleccionSimple("*codigo_sucursal", $textos["SUCURSAL_LABORA"],$sucursales, $sesion_sucursal, array("title" => $textos["AYUDA_SUCURSAL_LABORA"],"onchange" => "limpiarCampos();"))
                    .HTML::campoOculto("codigo_sucursal2",$sesion_sucursal),
                     HTML::campoTextoCorto("*selector1", $textos["EMPLEADO"], 45, 255, "", array("title" => $textos["AYUDA_EMPLEADO"], "onFocus" => "acLocalEmpleados(this);","onKeyUp" => "limpiar_oculto_Autocompletable(this,documento_identidad)"))
                    .HTML::campoOculto("documento_identidad","")
                    .HTML::campoOculto("mensaje_fechas_repetidas",$textos["MENSAJE_FECHAS_EXISTE"])
                    .HTML::campoOculto("mensaje_campos_vacios",$textos["MENSAJE_CAMPOS_VACIOS"])
                    .HTML::campoOculto("mensaje_vacio_documento_empleado",$textos["MENSAJE_VACIO_EMPLEADO"])
                    .HTML::campoOculto("mensaje_vacio_dias",$textos["CANTIDAD_DIAS"])
                    .HTML::campoOculto("mensaje_tipo_transaccion",$textos["TIPO_TRANSACCION"])
                    .HTML::campoOculto("id_modulo",$id_modulo)
                ),
                array(

                    HTML::campoTextoCorto("*fecha_inicial", $textos["FECHA_INICIAL"], 10, 10,"", array("title" => $textos["AYUDA_FECHA_INICIAL"])),
                    HTML::campoTextoCorto("*dias_no_laborados", $textos["CANTIDAD_DIAS"], 2, 3, "", array("title" => $textos["AYUDA_CANTIDAD_DIAS"], "onKeyPress" => "return campoEntero(event)","onKeyUp" => "cargarTablaLiquidacion();determinarDatosDias();")),
                    HTML::mostrarDato("fecha_final",$textos["FECHA_FINAL_VACACIONES"],""),
                    HTML::campoOculto("oculto_fecha_final",""),
                    HTML::mostrarDato("dias_disfruta",$textos["DIAS_DISFRUTAR"],""),
                    HTML::mostrarDato("dias_tomados",$textos["CANTIDAD_DIAS_PENDIENTES"],""),
                    HTML::campoOculto("oculto_dias_tomados",""),
                    HTML::campoOculto("oculto_dias_disfruta",""),
                    HTML::campoOculto("mensaje_fechas", $textos["ERROR_FECHA_MENOR"]),
                    HTML::campoOculto("valores_conceptos",""),
                    HTML::campoOculto("fecha_en_rango","")
                   
                   .HTML::contenedor(HTML::boton("botonRemoverIncapacidad", "", "removerItems(this);", "eliminar"), array("id" => "botonRemoverIncapacidad", "style" => "display: none;"))
                ),
                array(
                    HTML::generarTabla(
                        array("id","CONCEPTO","VALOR_MOVIENTO"),
                        "",
                        array("C","C"),
                        "listaItemsVacaciones",
                        false
                    )
                ),
                array(
                    //////////Permitir que se ejecute una funcion al iniciar el formulario//////////
                    HTML::contenedor("<script language='javascript'>adicionarEventoDatePicker();</script>"),
                    HTML::campoOculto("fecha_en_rango",""),
                    HTML::campoOculto("fecha_en_rango","")
                )
            );
        /*** Definici�n de botones ***/
        $botones = array(
                        HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
                    );

        $contenido = HTML::generarPestanas($formularios, $botones);
        /// Enviar datos para la generaci�n del formulario al script que origin� la petici�n
        $respuesta[0] = $error;
        $respuesta[1] = $titulo;
        $respuesta[2] = $contenido;
    }

    HTTP::enviarJSON($respuesta);

//// Validar los datos provenientes del formulario
}elseif(!empty($forma_procesar)){
    //// Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];

    if($forma_fecha_en_rango == "1"){
        $error   = true;
        $mensaje = $textos["EXISTE_DATOS_FECHA"];
    }elseif(empty($forma_documento_identidad)){
        $error   = true;
        $mensaje = $textos["VACIO_NOMBRE_EMPLEADO"];
    }elseif(empty($forma_fecha_inicial)){
        $error   = true;
        $mensaje = $textos["VACIO_FECHA_INICIAL"];
    }elseif(empty($forma_dias_no_laborados) || (int)$forma_dias_no_laborados==0){
        $error   = true;
        $mensaje = $textos["VACIO_DIAS_TOMADOS"];
    }else{
        $fecha_registro = date("Y-m-d H:i:s");
     
        $consulta_sucursal_contrato = SQL::seleccionar(array("consulta_contrato_empleado"), array("*"), "documento_identidad_empleado='$forma_documento_identidad' AND codigo_sucursal='$forma_codigo_sucursal' AND estado='1' ","","fecha_salario,fecha_ingreso_sucursal,fecha_inicia_departamento_seccion DESC",0,1);
        $datos_sucursal_contrato    = SQL::filaEnObjeto($consulta_sucursal_contrato);

        $codigo_cuenta_gastos              = SQL::obtenerValor("departamentos_empresa","codigo_gasto","codigo='$datos_sucursal_contrato->codigo_departamento_empresa'");

        $vacacion_pago_prestacion_disfrute = SQL::obtenerValor("gastos_prestaciones_sociales","vacacion_pago_prestacion_disfrute","codigo='$codigo_cuenta_gastos'");
        $vacacion_pago_gasto_disfrute      = SQL::obtenerValor("gastos_prestaciones_sociales","vacacion_pago_gasto_disfrute","codigo='$codigo_cuenta_gastos'");

        $transaccion_tiempo = SQL::seleccionar(array("transacciones_tiempo"),array("*"),"codigo_transaccion_contable='$vacacion_pago_prestacion_disfrute'","","",0,1);
        $continuar = true;
        $transaccion_contable = $vacacion_pago_prestacion_disfrute;
        if(SQL::filasDevueltas($transaccion_tiempo)==0){
             $transaccion_tiempo = SQL::seleccionar(array("transacciones_tiempo"),array("*"),"codigo_transaccion_contable='$vacacion_pago_gasto_disfrute'","","",0,1);
             $transaccion_contable = $vacacion_pago_gasto_disfrute;
             if(SQL::filasDevueltas($transaccion_tiempo)==0){
                $continuar = false;
             }
        }

        $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$transaccion_contable'");
        $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$transaccion_contable'");

        $valores_conceptos = explode("|",$forma_valores_conceptos);
        $valor_vacaciones  = $valores_conceptos[0];
  
        if($continuar){
             $datos = array(
                "forma_liquidacion"             => $forma_forma_liquidacion,
                "estado_liquidacion"            => "1",
                "fecha_generacion"              => $fecha_registro,
                "fecha_inicio_tiempo"           => $forma_fecha_inicial,
                "fecha_final_tiempo"            => $forma_oculto_fecha_final,
                /////LLAVE_EN_TABLA_SUCURSAL_CONTRATO/////
                "codigo_empresa"                => $datos_sucursal_contrato->codigo_empresa,
                "documento_identidad_empleado"  => $forma_documento_identidad,
                "fecha_ingreso"                 => $datos_sucursal_contrato->fecha_ingreso,
                "codigo_sucursal"               => $forma_codigo_sucursal,
                "fecha_ingreso_sucursal"        => $datos_sucursal_contrato->fecha_ingreso_sucursal,
                ///////////////////////////////
                "codigo_empresa_auxiliar"       => "0",
                "codigo_anexo_contable"         => "",
                "codigo_auxiliar_contable"      => "0",
                ///////////////////////////////
                "codigo_transaccion_contable"   =>  $transaccion_contable,
                "codigo_contable"               =>  $codigo_contable,
                "sentido"                       =>  $sentido,
                "codigo_transaccion_tiempo"     =>  $transaccion_tiempo,
                ///////////////////////////////
                "dias_tomados"                  => $forma_dias_no_laborados,
                "dias_disfrutado"               => $forma_oculto_dias_disfruta,
                "valor_movimiento"              => $valor_vacaciones,
                "codigo_usuario_registra"       => $sesion_usuario
             );

             $insertar = SQL::insertar("movimiento_liquidacion_vacaciones", $datos);
             if(!$insertar){
                $error   = true;
                $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
             }
        }else{
            $nombre_pago_prestacion_disfrute = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$vacacion_pago_prestacion_disfrute'");
            $nombre_pago_gasto_disfrute      = SQL::obtenerValor("transacciones_contables_empleado","descripcion","codigo='$vacacion_pago_gasto_disfrute'");
            $error   = true;
            $mensaje = $textos["ERROR_TRANSACCIONES_TIEMPO"]."\n - ".$nombre_pago_prestacion_disfrute."\n - ".$nombre_pago_gasto_disfrute;
        }

        if($forma_forma_liquidacion=='2' && $insertar){

            $datos_moviminentos = calculoLiquidacionVacaciones($forma_fecha_inicial,$forma_documento_identidad,$forma_codigo_sucursal,$forma_dias_no_laborados,$forma_forma_liquidacion,$textos,false);
            //echo var_dump($datos_moviminentos);
            for($i=0;$i<count($datos_moviminentos);$i++){

                $datos_movimiento            = explode("|",$datos_moviminentos[$i]);
                $codigo_transaccion_contable = $datos_sucursal_contrato->$datos_movimiento[1];

                $codigo_contable = SQL::obtenerValor("transacciones_contables_empleado","codigo_contable","codigo='$codigo_transaccion_contable'");
                $sentido         = SQL::obtenerValor("transacciones_contables_empleado","sentido","codigo='$codigo_transaccion_contable'");
                $dias_trabajados  = $datos_movimiento[4] - $datos_movimiento[5];
                if((int)$datos_movimiento[2]!=0){
                    $datos = array(
                        "concepto"                      => $datos_movimiento[0],
                        "estado_liquidacion"            => "1",
                        ///////////////////////////////////
                        "fecha_inicio_tiempo"           => $forma_fecha_inicial,
                        "codigo_empresa"                => $datos_sucursal_contrato->codigo_empresa,
                        "documento_identidad_empleado"  => $forma_documento_identidad,
                        "fecha_generacion"              => $fecha_registro,
                        "codigo_transaccion_contable"   => $codigo_transaccion_contable,
                         /////LLAVE_EN_TABLA_SUCURSAL_CONTRATO/////
                        "fecha_ingreso"                 => $datos_sucursal_contrato->fecha_ingreso,
                        "codigo_sucursal"               => $forma_codigo_sucursal,
                        "fecha_ingreso_sucursal"        => $datos_sucursal_contrato->fecha_ingreso_sucursal,
                        ///////////////////////////////
                        "fecha_inicio_pago"             => $datos_movimiento[3],
                        "fecha_hasta_pago"              => $forma_fecha_inicial,
                        ///////////////////////////////
                        "codigo_empresa_auxiliar"       => "0",
                        "codigo_anexo_contable"         => "",
                        "codigo_auxiliar_contable"      => "0",
                        ///////////////////////////////
                        "codigo_contable"               => $codigo_contable,
                        "sentido"                       => $sentido,
                        "dias_trabajados"               => $dias_trabajados,
                        "salario_mensual"               => $datos_sucursal_contrato->salario,
                        "valor_movimiento"              => $datos_movimiento[2],
                        "ibc"                           => $datos_movimiento[6],
                        "porcentaje_tasa"               => $datos_movimiento[7],
                        "codigo_usuario_registra"       => $sesion_usuario,
                    );

                    $insertar = SQL::insertar("liquidaciones_movimientos_conceptos_vacaciones", $datos);
                    if(!$insertar){
                        $condicion = "codigo_sucursal='$forma_codigo_sucursal' AND documento_identidad_empleado='$forma_documento_identidad' AND fecha_inicio_tiempo='$forma_fecha_inicial'";
                        $eliminar = SQL::eliminar("movimiento_liquidacion_vacaciones",$condicion);
                        $error   = true;
                        $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
                        break;
                    }
                }
            }
          
            if($insertar){
                $datos = array("contabilizado " => "2");
                $condicion =  "contabilizado!='2' AND documento_identidad_empleado='$forma_documento_identidad' AND codigo_sucursal='$forma_codigo_sucursal' AND fecha_inicio<='$forma_fecha_inicial'";
                $modificar = SQL::modificar("movimiento_tiempos_laborados",$datos,$condicion);
            }
        }
    }
   
    //// Enviar datos con la respuesta del proceso al script que origin� la petici�n
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
