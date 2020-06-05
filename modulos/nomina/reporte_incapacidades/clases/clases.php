<?php

function generar_Fechas($fecha_comienzo,$diasFechas){//Genera un listado de fechas a partir de la fecha dada hasta tantos dias despues dados
    $fecha_comienzo = strtotime($fecha_comienzo);
    $listaFechas    = array();
    for($i=0;$i<$diasFechas;$i++){
        $fecha_generada = mktime(0, 0, 0, date("m", $fecha_comienzo), date("d", $fecha_comienzo)+$i, date("Y", $fecha_comienzo));
        $listaFechas[]  = date('Y-m-d', $fecha_generada);
    }
    return $listaFechas;
}

function validar_existencia_con_DB($fechas_generadas,$empleado,$fechas_Tabla){// true si existe algun cruce y else si no
    $consulta = SQL::seleccionar(array("reporte_incapacidades"), array("fecha_incapacidad"), "documento_identidad_empleado = '".$empleado."'");
    $tam      = count($fechas_generadas);
    $tam2     = count($fechas_Tabla);
    $existe   = false;
    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)){
            $fecha_compara = $datos->fecha_incapacidad;
            for($i=0;$i<$tam;$i++){
                if($fecha_compara==$fechas_generadas[$i]){
                    $existe = true;
                    $i      = $tam;
                }
            }
        }
    }
    if(!$existe){//Si no hay en la base => Busquemos en la tabla
        for($i=0;$i<$tam2;$i++){
            for($j=0;$j<$tam;$j++){
                if($fechas_Tabla[$i]==$fechas_generadas[$j]){
                    $existe = true;
                    $j      = $tam;
                    $i      = $tam2;
                }
            }
        }
    }
    return $existe;
}

function validar_existencia_con_DB_Modificar($fechas_generadas,$empleado,$fechas_Tabla,$fecha_inicial){// true si existe algun cruce y else si no
    $consulta = SQL::seleccionar(array("reporte_incapacidades"), array("fecha_incapacidad"), "documento_identidad_empleado = '".$empleado."' AND fecha_inicial_incapacidad!='".$fecha_inicial."'");
    $tam      = count($fechas_generadas);
    $tam2     = count($fechas_Tabla);
    $existe   = false;
    if (SQL::filasDevueltas($consulta)) {
        while($datos = SQL::filaEnObjeto($consulta)){
            $fecha_compara = $datos->fecha_incapacidad;
            for($i=0;$i<$tam;$i++){
                if($fecha_compara==$fechas_generadas[$i]){
                    $existe = true;
                    $i      = $tam;
                }
            }
        }
    }
    if(!$existe){//Si no hay en la base => Busquemos en la tabla
        for($i=0;$i<$tam2;$i++){
            for($j=0;$j<$tam;$j++){
                if($fechas_Tabla[$i]==$fechas_generadas[$j]){
                    $existe = true;
                    $j      = $tam;
                    $i      = $tam2;
                }
            }
        }
    }
    return $existe;
}

function validar_Prorroga($fecha_inicio,$motivo,$empleado,$fechas_Tabla,$motivos_Tabla,$textos){// True si se puede prorroga y false si hay un inconveniente
    $fecha_inicio = strtotime($fecha_inicio);
    $fecha_inicio = mktime(0, 0, 0, date("m", $fecha_inicio), date("d", $fecha_inicio)-1, date("Y", $fecha_inicio));
    $fecha_inicio = date('Y-m-d', $fecha_inicio);

    $consulta          = SQL::seleccionar(array("reporte_incapacidades"), array("codigo_motivo_incapacidad"), "documento_identidad_empleado = '".$empleado."' AND fecha_incapacidad ='".$fecha_inicio."'");
    $motivoenBaseDatos =  "";

    $tam = count($fechas_Tabla);

    if (SQL::filasDevueltas($consulta)) {
        $datos             = SQL::filaEnObjeto($consulta);
        $motivoenBaseDatos = $datos->codigo_motivo_incapacidad;

        if($motivo==$motivoenBaseDatos){
            $mensaje[0] = true;
            $mensaje[1] = "";
        }else{
            $mensaje[0] = false;
            $mensaje[1] = $textos["MOTIVOS_DIFERENTES"];
        }
    }else{//Si no hay en la base => Busquemos en la tabla
        $browser=false;
        for($i=0;$i<$tam;$i++){
            if($fechas_Tabla[$i]==$fecha_inicio){
                $j       = $i;
                $browser = true;//Si encontro fecha en tabla
                $i       = $tam;
            }
        }
        if($browser){
            if($motivo==$motivos_Tabla[$j]){
            $mensaje[0] = true;
            $mensaje[1] = "";
            }else{
                $mensaje[0] = false;
                $mensaje[1] = $textos["MOTIVOS_DIFERENTES"];
            }
        }else{
            $mensaje[0] = false;
            $mensaje[1] = $textos["NO_EXISTE_INCAPACIDAD"];
        }
    }
    return $mensaje;
}

function validar_Prorroga_Modificar($fecha_inicial,$motivo,$empleado,$fechas_Tabla,$motivos_Tabla,$textos){// True si se puede prorroga y false si hay un inconveniente
    $fecha_inicio = strtotime($fecha_inicial);
    $fecha_inicio = mktime(0, 0, 0, date("m", $fecha_inicio), date("d", $fecha_inicio)-1, date("Y", $fecha_inicio));
    $fecha_inicio = date('Y-m-d', $fecha_inicio);

    $consulta          = SQL::seleccionar(array("reporte_incapacidades"), array("codigo_motivo_incapacidad"), "documento_identidad_empleado = '".$empleado."' AND fecha_incapacidad ='".$fecha_inicio."' AND fecha_inicial_incapacidad!='".$fecha_inicial."'");
    $motivoenBaseDatos =  "";

    $tam = count($fechas_Tabla);

    if (SQL::filasDevueltas($consulta)) {
        $datos             = SQL::filaEnObjeto($consulta);
        $motivoenBaseDatos = $datos->codigo_motivo_incapacidad;

        if($motivo==$motivoenBaseDatos){
            $mensaje[0] = true;
            $mensaje[1] = "";
        }else{
            $mensaje[0] = false;
            $mensaje[1] = $textos["MOTIVOS_DIFERENTES"];
        }
    }else{//Si no hay en la base => Busquemos en la tabla
        $browser=false;
        for($i=0;$i<$tam;$i++){
            if($fechas_Tabla[$i]==$fecha_inicio){
                $j       = $i;
                $browser = true;//Si encontro fecha en tabla
                $i       = $tam;
            }
        }
        if($browser){
            if($motivo==$motivos_Tabla[$j]){
            $mensaje[0] = true;
            $mensaje[1] = "";
            }else{
                $mensaje[0] =false;
                $mensaje[1] =$textos["MOTIVOS_DIFERENTES"];
            }
        }else{
            $mensaje[0] = false;
            $mensaje[1] = $textos["NO_EXISTE_INCAPACIDAD"];
        }
    }
    return $mensaje;
}

function generar_Incapacidades($empleado,$fecha_reporte,$fecha_inicio,$dias,$motivo,$codigo_transaccion,$fechas_Tabla,$motivos_Tabla,$codigo_sucursal,$estado_anexo,$anexoF,$auxiliarF,$textos){

    $puedeIngresar = SQL::obtenerValor("sucursal_contrato_empleados","codigo_auxiliar","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");

    if($puedeIngresar){
        $concepto_tiempo = SQL::obtenerValor("transacciones_tiempo", "codigo_concepto_transaccion_tiempo", "codigo = '".$codigo_transaccion."'");
        $dividendo       = SQL::obtenerValor("transacciones_tiempo", "dividendo", "codigo = '".$codigo_transaccion."'");
        $divisor         = SQL::obtenerValor("transacciones_tiempo", "divisor", "codigo = '".$codigo_transaccion."'");
        $listaFechas     = generar_Fechas($fecha_inicio,$dias);
        $tam             = count($listaFechas);
        $prorroga        = validar_Prorroga($fecha_inicio,$motivo,$empleado,$fechas_Tabla,$motivos_Tabla,$textos);

        $preferencias_globales = array();
        $preferencias_globales["codigo_transaccion_tiempo_incapacidad_tres_dias"] = SQL::obtenerValor("preferencias","valor","variable='codigo_transaccion_tiempo_incapacidad_tres_dias' AND tipo_preferencia=1");

        $empresaF   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$codigo_sucursal."'");
        $auxiliarDB = SQL::obtenerValor("sucursal_contrato_empleados","codigo_auxiliar","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");
        $anexoDB    = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");
        $salario    = SQL::obtenerValor("consulta_contrato_empleado","salario","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal,fecha_salario DESC DESC lIMIT 0,1");

        if(empty($dividendo)){
            $dividendo = 1;
        }
        if(empty($divisor)){
            $divisor   = 1;
        }

        $valor_dia = $salario/30;

        $valor_movimiento = ($valor_dia*$dividendo)/$divisor;

        if($estado_anexo==1){
            $anexo    = "";
            $auxiliar = 0;
            $empresa  = 0;
        }elseif($estado_anexo==2){
            $anexo    = $anexoDB;
            $auxiliar = $auxiliarDB;
            $empresa  = $empresaF;
        }elseif($estado_anexo==3){
            $anexo    = $anexoF;
            $auxiliar = $auxiliarF;
            $empresa  = $empresaF;
        }

        $bandera = true;//Decide si no hay error de limite de dias

        if($dias==0){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_CERO"];
        }elseif($concepto_tiempo=="14" && $dias>3){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_3"];
        }elseif($concepto_tiempo=="16" && $dias>89){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_89"];
        }elseif($concepto_tiempo=="17" && $dias>179){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_179"];
        }elseif($concepto_tiempo=="18" && $dias<180){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_180"];
        }

        if($bandera){
            if(!validar_existencia_con_DB($listaFechas,$empleado,$fechas_Tabla)){//Si hubo cruce de fechas con la base
                if($concepto_tiempo == "014" || $concepto_tiempo == "015"){ //Si es tres dias o ambulatoria
                    $mensaje[0] = true;
                    for($i=0;$i<$tam;$i++){
                        if($i<3){
                            $mensaje[$i+1] = $listaFechas[$i]."|".$preferencias_globales["codigo_transaccion_tiempo_incapacidad_tres_dias"]."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                        }else{
                            $mensaje[$i+1] = $listaFechas[$i]."|".$codigo_transaccion."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                        }
                    }
                }elseif($concepto_tiempo == "019" || $concepto_tiempo == "020"){//Este es si es hospirtalizacion o atep
                    $mensaje[0] = true;
                    for($i=0;$i<$tam;$i++){
                        $mensaje[$i+1] = $listaFechas[$i]."|".$codigo_transaccion."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                    }
                }elseif(($concepto_tiempo == "016" || $concepto_tiempo == "017" || $concepto_tiempo == "018") && $prorroga[0] == true){//Si es prorroga y se puede
                    $mensaje[0] = true;
                    for($i=0;$i<$tam;$i++){
                        $mensaje[$i+1] = $listaFechas[$i]."|".$codigo_transaccion."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                    }
                }else{
                    $mensaje = $prorroga;//Aqui dice motivos por el cual no se genera prorroga
                }
            }else{
                $mensaje[0] = false;
                $mensaje[1] = $textos["EXISTE_CRUCE"];
            }
        }
    }else{
        $mensaje[0] = false;
        $mensaje[1] = $textos["FECHA_INGRESO_NO_PERMITIDA"];
    }
    return $mensaje;
}

function generar_Incapacidades_Modificar($empleado,$fecha_reporte,$fecha_inicio,$dias,$motivo,$codigo_transaccion,$fechas_Tabla,$motivos_Tabla,$codigo_sucursal,$estado_anexo,$anexoF,$auxiliarF,$textos){

    $puedeIngresar = SQL::obtenerValor("sucursal_contrato_empleados","codigo_auxiliar","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");

    if($puedeIngresar){
        $concepto_tiempo = SQL::obtenerValor("transacciones_tiempo", "codigo_concepto_transaccion_tiempo", "codigo = '".$codigo_transaccion."'");
        $dividendo       = SQL::obtenerValor("transacciones_tiempo", "dividendo", "codigo = '".$codigo_transaccion."'");
        $divisor         = SQL::obtenerValor("transacciones_tiempo", "divisor", "codigo = '".$codigo_transaccion."'");
        $listaFechas     = generar_Fechas($fecha_inicio,$dias);
        $tam             = count($listaFechas);
        $prorroga        = validar_Prorroga_Modificar($fecha_inicio,$motivo,$empleado,$fechas_Tabla,$motivos_Tabla,$textos);

        $preferencias_globales = array();
        $preferencias_globales["codigo_transaccion_tiempo_incapacidad_tres_dias"] = SQL::obtenerValor("preferencias","valor","variable='codigo_transaccion_tiempo_incapacidad_tres_dias' AND tipo_preferencia=1");

        $empresaF   = SQL::obtenerValor("sucursales","codigo_empresa","codigo='".$codigo_sucursal."'");
        $auxiliarDB = SQL::obtenerValor("sucursal_contrato_empleados","codigo_auxiliar","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");
        $anexoDB    = SQL::obtenerValor("sucursal_contrato_empleados","codigo_anexo_contable","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal DESC lIMIT 0,1");
        $salario    = SQL::obtenerValor("consulta_contrato_empleado","salario","documento_identidad_empleado='".$empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal<='".$fecha_inicio."' ORDER BY fecha_ingreso_sucursal,fecha_salario DESC DESC lIMIT 0,1");

        if(empty($dividendo)){
            $dividendo = 1;
        }
        if(empty($divisor)){
            $divisor = 1;
        }

        $valor_dia = $salario/30;

        $valor_movimiento = ($valor_dia*$dividendo)/$divisor;

        if($estado_anexo==1){
            $anexo    = "";
            $auxiliar = 0;
            $empresa  = 0;
        }elseif($estado_anexo==2){
            $anexo    = $anexoDB;
            $auxiliar = $auxiliarDB;
            $empresa  = $empresaF;
        }elseif($estado_anexo==3){
            $anexo    = $anexoF;
            $auxiliar = $auxiliarF;
            $empresa  = $empresaF;
        }

        $bandera = true;//Decide si no hay error de limite de dias

        if($dias==0){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_CERO"];
        }elseif($concepto_tiempo=="14" && $dias>3){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_3"];
        }elseif($concepto_tiempo=="16" && $dias>89){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_89"];
        }elseif($concepto_tiempo=="17" && $dias>179){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_179"];
        }elseif($concepto_tiempo=="18" && $dias<180){
            $bandera    = false;
            $mensaje[0] = false;
            $mensaje[1] = $textos["DIAS_180"];
        }

        if($bandera){
            if(!validar_existencia_con_DB_Modificar($listaFechas,$empleado,$fechas_Tabla,$fecha_inicio)){//Si hubo cruce de fechas con la base
                if($concepto_tiempo == "014" || $concepto_tiempo == "015"){ //Si es tres dias o ambulatoria
                    $mensaje[0] = true;
                    for($i=0;$i<$tam;$i++){
                        if($i<3){
                            $mensaje[$i+1] = $listaFechas[$i]."|".$preferencias_globales["codigo_transaccion_tiempo_incapacidad_tres_dias"]."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                        }else{
                            $mensaje[$i+1] = $listaFechas[$i]."|".$codigo_transaccion."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                        }
                    }
                }elseif($concepto_tiempo == "019" || $concepto_tiempo == "020"){//Este es si es hospirtalizacion o atep
                    $mensaje[0] = true;
                    for($i=0;$i<$tam;$i++){
                        $mensaje[$i+1] = $listaFechas[$i]."|".$codigo_transaccion."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                    }
                }elseif(($concepto_tiempo == "016" || $concepto_tiempo == "017" || $concepto_tiempo == "018") && $prorroga[0] == true){//Si es prorroga y se puede
                    $mensaje[0] = true;
                    for($i=0;$i<$tam;$i++){
                        $mensaje[$i+1] = $listaFechas[$i]."|".$codigo_transaccion."|".$empresa."|".$anexo."|".$auxiliar."|".$valor_dia."|".$divisor."|".$dividendo."|".$valor_movimiento;
                    }
                }else{
                    $mensaje = $prorroga;//Aqui dice motivos por el cual no se genera prorroga
                }
            }else{
                $mensaje[0] = false;
                $mensaje[1] = $textos["EXISTE_CRUCE"];
            }
        }
    }else{
        $mensaje[0] = false;
        $mensaje[1] = $textos["FECHA_INGRESO_NO_PERMITIDA"];
    }
    return $mensaje;
}

?>
