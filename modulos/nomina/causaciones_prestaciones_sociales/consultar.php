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

if(!empty($url_generar)){
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    } else {
        $error         = "";
        $titulo        = $componente->nombre;
        /////////////////////////////////////////////////////
        $tipo_comprobante       = HTML::generarDatosLista("tipos_comprobantes","codigo","descripcion","codigo !='0'");
        $listado_tipo_documento = HTML::generarDatosLista("tipos_documentos","codigo","descripcion","manejo_automatico='2' AND sentido_contable!='0'");

        $llave_primaria    = explode("|",$url_id);
        $codigo_empresa    = $llave_primaria[0];

        $fecha_liquidacion = $llave_primaria[1];
        $codigo_empresa    = $llave_primaria[0];

        $fecha_contabilizacion = "";
        $tipo_documento        = "";
        $tipo_comprobante      = "";
        $numero_comprobante    = "";

        $condicion_datos_causaciones  = " codigo_empresa='$codigo_empresa' AND fecha_liquidacion<='$fecha_liquidacion' ";
        $consulta_causaciones_prestaciones_sociales = SQL::seleccionar(array("agrupar_causaciones_prestaciones_sociales"),array("*"),$condicion_datos_causaciones);

        $valores_totales_departamento  = array();
        $arreglo_prestaciones = array("1"=>"prima_servicio","2"=>"vacaciones","3"=>"cesantias","4"=>"intereses");
        while($datos_causaciones = SQL::filaEnObjeto($consulta_causaciones_prestaciones_sociales) ){

            $consulta_contrato_empleado = SQL::seleccionar(array("consulta_contrato_empleado"), array("*"),"codigo_empresa='$codigo_empresa' AND documento_identidad_empleado='$datos_causaciones->documento_identidad_empleado' AND codigo_sucursal='$datos_causaciones->codigo_sucursal' AND fecha_ingreso_sucursal <= '$fecha_liquidacion' AND estado= '1' ","","fecha_ingreso_sucursal,fecha_inicia_departamento_seccion DESC",0,1);
            $datos_contrato_empleado    = SQL::filaEnObjeto($consulta_contrato_empleado );

            $codigo_departamento = $datos_contrato_empleado->codigo_departamento_empresa;
            $concepto_pretacion  = $arreglo_prestaciones[$datos_causaciones->concepto];
            if(!isset($valores_totales_departamento[$codigo_departamento][$concepto_pretacion])){
                $valores_totales_departamento[$codigo_departamento][$concepto_pretacion] = (int)$datos_causaciones->valor_movimiento;
            }else{
                $valores_totales_departamento[$codigo_departamento][$concepto_pretacion] += $datos_causaciones->valor_movimiento;
            }

            $fecha_contabilizacion = $datos_causaciones->fecha_contabilizacion;
            $tipo_documento        = $datos_causaciones->codigo_tipo_documento;
            $tipo_comprobante      = $datos_causaciones->codigo_tipo_comprobante;
            $numero_comprobante    = $datos_causaciones->numero_comprobante;
        }
       //echo var_dump($valores_totales_departamento);
        $datos_envio = cargarDatosTabla($valores_totales_departamento,$textos);
        $datos_envio = implode("¬", $datos_envio);
        $nombre_empresa     = SQL::obtenerValor("empresas","razon_social","codigo='$codigo_empresa'");
        $nombre_documento   = SQL::obtenerValor("tipos_documentos","descripcion","codigo='$tipo_documento'");
        $nombre_comprobante = SQL::obtenerValor("tipos_comprobantes","descripcion","codigo='$tipo_comprobante'");
        /*** Definición de pestaña Basica ***/
        $formularios["BASICO"] = array(
            array(
                HTML::mostrarDato("nombre_empresa", $textos["EMPRESAS"], $nombre_empresa),
                HTML::campoOculto("codigo_empresa",$codigo_empresa),
                HTML::mostrarDato("mostrar_fecha_liquidacion", $textos["FECHA_LIQUIDACION"],$fecha_liquidacion),
                HTML::campoOculto("fecha_liquidacion",$fecha_liquidacion),
                HTML::mostrarDato("fecha_contabilizacion", $textos["FECHA_CONTABILIZACION"],$fecha_contabilizacion)
            ),
            array(
                HTML::mostrarDato("codigo_tipo_documento",$textos["TIPO_DOCUMENTO"],$nombre_documento),
                HTML::mostrarDato("codigo_tipo_comprobante",$textos["TIPO_COMPROBANTE"],$nombre_comprobante),
                HTML::mostrarDato("numero_comprobate",$textos["NUMERO_COMPROBATE"],$numero_comprobante)
            ),
            array(
                HTML::boton("generar",$textos["LISTADO_PRESTACIONES_EMPREADOS"],"imprimirItem('0');","adicionar",array("title" => $textos["AYUDA_LISTADO_PRESTACIONES_EMPREADOS"])),
                HTML::campoOculto("titulo_codigo_contable",$textos["CODIGO_CONTABLE"]),
                HTML::campoOculto("titulo_transaccion",$textos["TRANSACCION"]),
                HTML::campoOculto("titulo_debitos",$textos["DEBITOS"]),
                HTML::campoOculto("titulo_creditos",$textos["CREDITOS"]),
            ),
            array(
                HTML::generarTabla(
                    array("id","CODIGO_CONTABLE","TRANSACCION","DEBITOS","CREDITOS"),
                    "",
                    array("C","C","C","C"),
                    "listaItemsPrestaciones",
                    false
                )
            ),
            array(
                //////////Permitir que se ejecute una funcion al iniciar el formulario//////////
                HTML::contenedor("<script language='javascript'>cargarDatosTabla('$datos_envio');</script>")
            )
        );
        $url_id = 'adicionar';
        $botones   = array(HTML::boton("botonAceptar", $textos["ACEPTAR"],  "modificarItem('$url_id');", "aceptar"));
        $contenido = HTML::generarPestanas($formularios, $botones);
    }
    /// Enviar datos para la generación del formulario al script que originó la petición
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

}elseif(!empty($forma_procesar)){
    
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

    $archivo                 = new PDF("L","mm","letter");
    $archivo->textoCabecera  = $textos["FECHA"].": ".date("Y-m-d");
    $archivo->textoPiePagina = "";

    $i = 0;

    $arreglo_prestaciones             = array("prima","vacaciones","cesantias");
    $consulta_prestaciones_prima      = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'1');
    $consulta_prestaciones_vacaciones = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'2');
    $consulta_prestaciones_cesantias  = InformacionCausacionEmpresaEmpleados($forma_codigo_empresa,$forma_fecha_liquidacion,'3');

    foreach($arreglo_prestaciones AS $nombre_pretacion){

        $valor_tota_prestacion         = 0;
        $valor_tota_prestacion_interes = 0;

        if(count(${"consulta_prestaciones_".$nombre_pretacion})){

            if($contador==1){
                $titulo = $textos["PRIMA"];
            }elseif($contador==2){
                $titulo = $textos["VACACIONES"];
            }else{
                $titulo = $textos["CESANTIAS"];
            }

            $anchoColumnas  = array(20,15,50,20,15,20,20,30,10,20,20);

            if($contador=='1' || $contador=='2'){
                $tituloColumnas = array($textos["SUCURSAL"],$textos["CEDULA"],$textos["EMPLEADO"],$textos["FECHA_INGRESO"],$textos["SALARIO_BASE"],$textos["AUXILO_TRANSPORTE"],$textos["PROMEDIO_MOVIENTOS"],$textos["FECHA_INICIO_CALCULO"],$textos["DIAS_PRESTACION"],$textos["VALOR_BASE_CALCULO"],$titulo);
            }else{
                $tituloColumnas = array($textos["SUCURSAL"],$textos["CEDULA"],$textos["EMPLEADO"],$textos["FECHA_INGRESO"],$textos["SALARIO_BASE"],$textos["AUXILO_TRANSPORTE"],$textos["PROMEDIO_MOVIENTOS"],$textos["FECHA_INICIO_CALCULO"],$textos["DIAS_PRESTACION"],$textos["VALOR_BASE_CALCULO"],$titulo,$textos["VALOR_INTERESES"]);
                $anchoColumnas [] = 20;
            }

            $archivo->textoTitulo = $textos["REPORTE_CAUSACION"].$titulo;
            $archivo->AddPage();
            $archivo->generarCabeceraTabla($tituloColumnas, $anchoColumnas);
            $archivo->Ln(4);

            foreach(${"consulta_prestaciones_".$nombre_pretacion} AS $arreglo_pretaciones){

                $datos = (object) $arreglo_pretaciones;

                if($archivo->breakCell(5)){
                    $archivo->AddPage();
                    $archivo->generarCabeceraTabla($tituloColumnas,$anchoColumnas);
                    $archivo->Ln(4);
                }

                if($archivo->FillColor != sprintf('%.3F %.3F %.3F rg',1,1,1)){
                    $archivo->SetFillColor(255,255,255);
                }else{
                    $archivo->SetFillColor(240,240,240);
                }

                $nombre_sucursal    = SQL::obtenerValor("sucursales","nombre","codigo = '$datos->codigo_sucursal_actual'");
                $nombre_empleado    = SQL::obtenerValor("seleccion_terceros","SUBSTRING_INDEX(nombre,'|',1)","id = '$datos->documento_identidad'");
                $nombre_empleado    = explode(",",$nombre_empleado);

                $archivo->SetFont('Arial',"",6);
                $archivo->Cell($anchoColumnas[0], 4, $nombre_sucursal, 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[1], 4, $datos->documento_identidad, 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[2], 4, $nombre_empleado[1], 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[3], 4, $datos->fecha_ingreso_empleado, 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[4], 4, $datos->salario_base, 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[5], 4, number_format($datos->auxilio_transporte), 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[6], 4, number_format($datos->promedio_movimientos), 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[7], 4, $datos->fecha_inicio_calculo, 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[8], 4, $datos->dias_total_calculo, 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[9], 4, number_format($datos->base_prestacion), 1, 0, "L", true,"",true);
                $archivo->Cell($anchoColumnas[10], 4, number_format($datos->valor_prestacion), 1, 0, "L", true,"",true);

                $valor_tota_prestacion += $datos->valor_prestacion;
                if($contador=='3'){
                    $archivo->Cell($anchoColumnas[10], 4, number_format($datos->valor_intereses), 1, 0, "L", true,"",true);
                    $valor_tota_prestacion_interes += $datos->valor_intereses;
                }
                $archivo->Ln(4);
                $i++;
            }
            $archivo->Cell(200, 4,"",0, 0, "L", false,"",true);
            $archivo->Cell(20, 4,$textos["TOTAL"],1, 0, "R", false,"",true);
            $archivo->Cell(20, 4, number_format($valor_tota_prestacion),1, 0, "L", false,"",true);
            if($contador=='3'){
                $archivo->Cell(20, 4, number_format($valor_tota_prestacion_interes),1, 0, "L", false,"",true);
            }
        }
        $contador++;
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
