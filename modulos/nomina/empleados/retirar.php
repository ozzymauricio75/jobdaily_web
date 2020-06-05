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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/
/* * Generar el formulario para la captura de datos ** */
if (!empty($url_generar)) {
    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if(empty($url_id)){
        $error = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo = "";
        $contenido = "";
    }else{
        $llave_pricipal = explode("|", $url_id);
        $codigo_empresa = $llave_pricipal[0];
        $documento_identidad_empleado = $llave_pricipal[1];
        $fecha_ingreso = $llave_pricipal[2];
        $estado = SQL::obtenerValor("ingreso_empleados","estado","codigo_empresa='".$codigo_empresa."' AND documento_identidad_empleado='".$documento_identidad_empleado."' AND fecha_ingreso='".$fecha_ingreso."'");
        if($estado==1){
            $titulo = $componente->nombre;
            $error = "";
            $formas_pago = array("1" => $textos["PRIMERA_QUINCENA"], "2" => $textos["SEGUNDA_QUINCENA"], "3" => $textos["PROPORCIONAL_QUINCENA"]);
            $formas_pago2= array("1" => $textos["MENSUAL"], "2" => $textos["PRIMERA_QUINCENA"], "3" => $textos["SEGUNDA_QUINCENA"],"23" => $textos["PROPORCIONAL_QUINCENA"],"4" => $textos["MENSUAL"]);

            $MANEJO_AUXILIO_TRANAPORTE= array(
                "1" => $textos["PAGUELO_POR_LEY_CON_DESCUENTO"],
                "2" => $textos["PAGUELO_POR_LEY_SIN_DESCUENTO"],
                "3" => $textos["PAGELE_MAYOR_2_SALARIO_CON_DESCUENTO"],
                "4" => $textos["PAGELE_MAYOR_2_SALARIO_SIN_DESCUENTO"]
            );
            $llave_sucursal = SQL::obtenerValor("buscador_ingreso_empleados", "codigo_sucursal", "id='".$url_id."'");

            $llave_sucursal = explode("|", $llave_sucursal);
            $codigo_sucursal = $llave_sucursal[0];
            $fecha_ingreso_sucursal = $llave_sucursal[1];
            $vistaConsulta = "ingreso_empleados";
            $columnas = SQL::obtenerColumnas($vistaConsulta);
            $consulta_ingreso_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso'");
            $datos_ingreso_empleados = SQL::filaEnObjeto($consulta_ingreso_empleados);

            $nombre_empleado = SQL::obtenerValor("terceros", "CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)", "documento_identidad ='$documento_identidad_empleado'");
            $fecha_inicio = $datos_ingreso_empleados -> fecha_ingreso;

            $vistaConsulta = "cargo_contrato_empleados";
            $columnas = SQL::obtenerColumnas($vistaConsulta);
            $consulta_cargo_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso'","", "fecha_inicia_cargo  DESC", 0, 1);
            $datos_cargo_contrato_empleados = SQL::filaEnObjeto($consulta_cargo_contrato_empleados);

            $nombre_cargo = SQL::obtenerValor("cargos","nombre","codigo='$datos_cargo_contrato_empleados->codigo_cargo'");

            if($datos_ingreso_empleados->manejo_auxilio_transporte!='5')
            {
                $recibe_auxilio_trasporte = $textos["SI"];
                $manejo_auxilio=$MANEJO_AUXILIO_TRANAPORTE[$datos_ingreso_empleados->manejo_auxilio_transporte];
            }else
                {
                $recibe_auxilio_trasporte = $textos["NO"];
                $manejo_auxilio="";
            }

            $vistaConsulta = "contrato_empleados";
            $columnas = SQL::obtenerColumnas($vistaConsulta);
            $consulta_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso'", "", "fecha_contrato DESC", 0, 1);
            $datos_contrato_empleados = SQL::filaEnObjeto($consulta_contrato_empleados);

            $codigo_tipo_contrato = SQL::obtenerValor("tipos_contrato", "descripcion", "codigo='$datos_contrato_empleados->codigo_tipo_contrato'");
            $terminio_contrato = SQL::obtenerValor("tipos_contrato", "termino_contrato", "codigo='$datos_contrato_empleados->codigo_tipo_contrato'");

            if($terminio_contrato == 1 || $terminio_contrato == 3){
                $fecha_cambio_contrato = $datos_contrato_empleados -> fecha_cambio_contrato;
            }else{
                $fecha_cambio_contrato = "Indefinida";
            }

            $vistaConsulta = "sucursal_contrato_empleados";
            $columnas = SQL::obtenerColumnas($vistaConsulta);
            $consulta_sucursal_contrato_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso' AND codigo_sucursal='$codigo_sucursal'AND fecha_ingreso_sucursal='$fecha_ingreso_sucursal'");
            $datos_sucursal_contrato_empleados = SQL::filaEnObjeto($consulta_sucursal_contrato_empleados);

            $sucursal = SQL::obtenerValor("sucursales", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_sucursal'");
            $anexo_contable = SQL::obtenerValor("anexos_contables", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_anexo_contable'");
            $codigo_auxiliar = SQL::obtenerValor("auxiliares_contables", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_auxiliar' AND codigo_anexo_contable='$datos_sucursal_contrato_empleados->codigo_anexo_contable'");
            $planilla = SQL::obtenerValor("planillas", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_planilla'");
            $turno_laboral = SQL::obtenerValor("turnos_laborales", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_turno_laboral'");

            $formularios["PESTANA_RETIRO"] = array(
                array(
                    HTML::campoTextoCorto("fecha_retiro", $textos["FECHA_RETIRO"], 10, 10, date("Y-m-d"), array("title" => $textos["AYUDA_FECHA_RETIRO"], "class" => "fechaAntigua"))
                ),
                array(
                    HTML::listaSeleccionSimple("motivo_retiro", $textos["MOTIVO_RETIRO"], HTML::generarDatosLista("motivos_retiro", "codigo", "descripcion", "codigo > '0'"), "", array("title" => $textos["AYUDA_MOTIVO_RETIRO"]))
                )
            );

            $formularios["PESTANA_BASICA"] = array(
                array(
                    HTML::mostrarDato("*codigo_empresa", $textos["EMPRESA"], $codigo_empresa),
                    HTML::mostrarDato("*sucursal_labora", $textos["SUCURSAL_LABORA"], $sucursal),
                    HTML::mostrarDato("*nombre", $textos["NOMBRE_COMPLETO"], $nombre_empleado)
                ),
                array(
                    HTML::mostrarDato("fecha_inicio", $textos["FECHA_INICIAL"], $fecha_inicio),
                    HTML::mostrarDato("*id_turno_laboral", $textos["TURNO_LABORAL"], $turno_laboral),
                ),
                array(
                    HTML::mostrarDato("*id_planilla", $textos["PLANILLA"], $planilla),
                )
            );

            $vistaConsulta = "departamento_seccion_contrato_empleado";
            $columnas = SQL::obtenerColumnas($vistaConsulta);
            $consulta_departamento_seccion_contrato_empleado = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso' AND codigo_sucursal='$codigo_sucursal'AND fecha_ingreso_sucursal='$fecha_ingreso_sucursal'","","fecha_inicia_departamento_seccion DESC",0,1);
            $datos_departamento_seccion_contrato_empleado = SQL::filaEnObjeto($consulta_departamento_seccion_contrato_empleado);

            $departamento_empresa = SQL::obtenerValor("departamentos_empresa","nombre","codigo='$datos_departamento_seccion_contrato_empleado->codigo_departamento_empresa'");
            $seccion_departamento = SQL::obtenerValor("secciones_departamentos","nombre","codigo='$datos_departamento_seccion_contrato_empleado->codigo_seccion_empresa'");
            $nombre_jefe          = SQL::obtenerValor("terceros","CONCAT(primer_nombre,' ',segundo_nombre,' ',primer_apellido,' ',segundo_apellido)","documento_identidad='$datos_cargo_contrato_empleados->documento_identidad_jefe_inmediato'");
            $formularios["PESTANA_CONTRATO"] = array(
                array(
                    HTML::mostrarDato("tipo_contrato", $textos["TIPO_CONTRATO"], $codigo_tipo_contrato) . "<br/><br/>" .
                    HTML::mostrarDato("departamento", $textos["DEPARTAMENTOS"], $departamento_empresa),
                    HTML::mostrarDato("cargo", $textos["CARGO"], $nombre_cargo) . "<br/><br/>" .
                    HTML::mostrarDato("seccion", $textos["SECCION"], $seccion_departamento),
                    HTML::mostrarDato("riesgo_profesional", $textos["RIESGO_PROFESIONAL"], $datos_ingreso_empleados -> riesgo_profesional),
                    HTML::contenedor(HTML::mostrarDato("*fecha_vencimiento_contrato", $textos["FECHA_VENCIMIENTO"], $fecha_cambio_contrato))
                ),
                array(
                    HTML::mostrarDato("selector7", $textos["NOMBRE_JEFE"],$nombre_jefe),
                    HTML::mostrarDato("anexos_contable", $textos["ANEXO_CONTABLE"], $anexo_contable),
                    HTML::mostrarDato("auxiliar_contable", $textos["AUXILIAR_CONTABLE"], $codigo_auxiliar)
                ),
                array(
                    HTML::mostrarDato("salario_mensual", $textos["SALARIOS_MENSUAL"], $datos_sucursal_contrato_empleados -> salario_mensual),
                    HTML::mostrarDato("salario_diario", $textos["SALARIO_DIARIO"], $datos_sucursal_contrato_empleados -> valor_hora)
                ),
                array(
                    HTML::mostrarDato("auxilio_transporte", $textos["AUXILIO_TRANSPORTE"],$recibe_auxilio_trasporte)

                ) ,
                array(
                    HTML::mostrarDato("manejo_auxilio_trasnporte","",$manejo_auxilio)
                )
            );

            $vistaConsulta = "entidades_salud_empleados";
            $columnas = SQL::obtenerColumnas($vistaConsulta);
            $consulta_entidades_salud_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso'");
            $datos_entidades_salud_empleados = SQL::filaEnObjeto($consulta_entidades_salud_empleados);

            $entidad_salud =SQL::obtenerValor("entidades_parafiscales","nombre","codigo=$datos_entidades_salud_empleados->codigo_entidad_salud");

            $vistaConsulta              = "entidades_pension_empleados";
            $columnas                   = SQL::obtenerColumnas($vistaConsulta);
            $consulta_pension_empleados = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso'");
            $datos_pension_empleados    = SQL::filaEnObjeto($consulta_pension_empleados);

            if($datos_pension_empleados){
                $codigo_entidad_pension     = $datos_pension_empleados->codigo_entidad_pension;
                $entidad_pension =SQL::obtenerValor("entidades_parafiscales","nombre","codigo=$codigo_entidad_pension");
                $nombre_entidad_pension = $datos_pension_empleados->fecha_inicio_pension;
                $campo = HTML::mostrarDato("codigo_entidad_pension", $textos["ENTIDAD_PENSION"], $entidad_pension);
                $campo .= HTML::mostrarDato("fecha_inicial_pension", $textos["FECHA_INICIA"], $nombre_entidad_pension);
            }else{
                $campo="";
            }

            $formularios["PESTANA_PARAFISCALES"] = array(
                array(
                    HTML::mostrarDato("entidad_salud", $textos["ENTIDAD_SALUD"],$entidad_salud),
                    HTML::mostrarDato("inicial_salud", $textos["FECHA_INICIA"],$datos_entidades_salud_empleados->fecha_inicio_salud),
                ),
                array(
                    HTML::mostrarDato("direccion_atienden", $textos["DIRECCION_ATIENDEN"],$datos_entidades_salud_empleados->direccion_atencion)
                ),
                array(
                    HTML::mostrarDato("direccion_urgencia", $textos["DIRECCION_URGENCIA"], $datos_entidades_salud_empleados->direccion_urgencia)
                ),
                array(
                    $campo
                )
            );

            $transaccion_salario = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_salario'");
            $transaccion_auxilio_transporte = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_auxilio_transporte'");
            $transaccion_salud = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_salud'");
            $transaccion_pension = SQL::obtenerValor("transacciones_contables_empleado", "descripcion", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_pension'");

            if($datos_sucursal_contrato_empleados -> codigo_planilla == 2){
                $forma_pago_auxilio = $formas_pago[$datos_sucursal_contrato_empleados -> forma_pago_auxilio];
                $forma_descuento_salud = $formas_pago[$datos_sucursal_contrato_empleados -> forma_descuento_salud];
                $forma_descuento_pension = $formas_pago[$datos_sucursal_contrato_empleados -> forma_descuento_pension];
            }else{
                $forma_pago_auxilio = $textos["MENSUAL"];
                $forma_descuento_salud = $textos["MENSUAL"];
                $forma_descuento_pension = $textos["MENSUAL"];

                $estado = "oculto";
            }
            $formularios["TRANSACCION_FIJAS"] = array(
                array(
                    HTML::mostrarDato("codigo_transaccion_salario", $textos["TRANSACCION_CONTABLE_SALARIO"], $transaccion_salario)),
                array(
                    HTML::mostrarDato("codigo_transaccion_auxilio_transporte", $textos["TRANSACCION_CONTABLE_AUXILIO"], $transaccion_auxilio_transporte)
                    , HTML::mostrarDato("periodo_transaccion_auxilio_transporte", $textos["PERIODO_PAGO"], $forma_pago_auxilio)
                ),
                array(HTML::mostrarDato("codigo_transaccion_salud", $textos["TRANSACCION_CONTABLE_SALUD"], $transaccion_salud)
                    , HTML::mostrarDato("periodo_transaccion_salud", $textos["PERIODO_PAGO"], $forma_descuento_salud)
                ),
                array(HTML::mostrarDato("codigo_transaccion_pension", $textos["TRANSACCION_CONTABLE_PENSION"], $transaccion_pension)
                    , HTML::mostrarDato("periodo_transaccion_pension", $textos["PERIODO_PAGO"], $forma_descuento_pension)
                )
            );
            $vistaConsulta = "ingresos_varios_empleados";
            $consulta_varios_empleados = SQL::seleccionar(array($vistaConsulta),array("*"), "codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$documento_identidad_empleado' AND fecha_ingreso='$fecha_ingreso' AND estado ='1'");
            $varios_empleados = array();
            if(SQL::filasDevueltas($consulta_varios_empleados)){
                while($transaccion_tiempo = SQL::filaEnObjeto($consulta_varios_empleados)){
                    $otras_transaccion =  SQL::obtenerValor("transacciones_contables_empleado", "nombre", "codigo='$transaccion_tiempo->codigo_transaccion_tiempo'");
                    $varios_empleados[]=array(
                        "id",
                        $formas_pago2[$transaccion_tiempo->periodo_pago],
                        $otras_transaccion
                    );
                }
            }

            $formularios["PESTANA_TRANSACCIONES_TIEMPO"] = array();
            $transaccion_normales = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_normales'");
            $transaccion_extras = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_extras'");
            $transaccion_recargo_nocturno = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_recargo_nocturno'");
            $transaccion_extras_nocturnas = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_extras_nocturnas'");
            $transaccion_dominicales = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_dominicales'");
            $transaccion_extras_dominicales = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_extras_dominicales'");
            $transaccion_recargo_noche_dominicales = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_recargo_noche_dominicales'");
            $transaccion_extras_noche_dominicales = SQL::obtenerValor("transacciones_tiempo", "nombre", "codigo='$datos_sucursal_contrato_empleados->codigo_transaccion_extras_noche_dominicales' ");
            $campo = array();
            if(!empty($transaccion_normales)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_normales", $textos["HORAS_NORMALES"], $transaccion_normales)
                );
            }if(!empty($transaccion_extras)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_normales", $textos["HORAS_EXTRAS"], $transaccion_extras),
                );
            }
            if(!empty($transaccion_recargo_nocturno)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_recargo_nocturno", $textos["HORAS_RECARGO_NOCTURNA"], $transaccion_recargo_nocturno)
                );
            }
            if(!empty($transaccion_extras_nocturnas)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_extras_nocturnas", $textos["HORAS_EXTRAS_NOCTURNAS"], $transaccion_extras_nocturnas)
                );
            }if(!empty($transaccion_dominicales)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_dominicales", $textos["HORAS_DOMINICALES"], $transaccion_dominicales),
                );
            }if (!empty($transaccion_extras_dominicales)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_extras_dominicales", $textos["HORAS_EXTRAS_DOMINGOS_FESTIVOS"], $transaccion_extras_dominicales),
                );
            }if (!empty($transaccion_recargo_noche_dominicales)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_recargo_noche_dominicales", $textos["HORAS_RECARGO_NOCHE_DOMINICAL"], $transaccion_recargo_noche_dominicales)
                );
            }if(!empty($transaccion_extras_noche_dominicales)){
                $campo[] = array(
                    HTML::mostrarDato("transaccion_extras_noche_dominicales", $textos["HORAS_EXTRAS_DOMINGOS_FESTIVOS_NOCHE"], $transaccion_extras_noche_dominicales)
                );
            }
            $formularios["PESTANA_TRANSACCIONES_TIEMPO"] = array_merge($formularios["PESTANA_TRANSACCIONES_TIEMPO"], $campo);
            /*** Definici�n de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["CONFIRMAR_RETIRO"], "modificarItem('$url_id');", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios,$botones);
        }else{
            $error = $textos["ERROR_EMPLEADO_RETIRADO"];
            $titulo = "";
            $contenido = "";
        }
    }
    /* * * Enviar datos para la generaci�n del formulario al script que origina la peticion ***/
    $respuesta = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

}elseif(!empty($forma_procesar)){
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["EMPLEADO_RETIRADO"];

    $llave_pricipal                 = explode("|", $forma_id);
    $codigo_empresa                 = $llave_pricipal[0];
    $documento_identidad_empleado   = $llave_pricipal[1];
    $fecha_ingreso                  = $llave_pricipal[2];

    $datos = array(
        "fecha_retiro"          => $forma_fecha_retiro,
        "codigo_motivo_retiro"  => $forma_motivo_retiro,
        "estado"                => 2
    );
    $retiro = SQL::modificar("ingreso_empleados",$datos,"codigo_empresa='".$codigo_empresa."' AND documento_identidad_empleado='".$documento_identidad_empleado."' AND fecha_ingreso='".$fecha_ingreso."'");
    if(!$retiro){
    $error   = true;
    $mensaje = $textos["FALLA_EMPLEADO_RETIRADO"];
    }

    /*** Enviar datos con la respuesta del proceso al script que origin� la petici�n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
