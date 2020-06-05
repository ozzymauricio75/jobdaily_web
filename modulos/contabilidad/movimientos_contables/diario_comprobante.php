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

include("clases/diario.php");

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    $consulta       = SQL::seleccionar(array("sucursales"), array("*"), "codigo != '0'");
    $sucursales_ver = SQL::filasDevueltas($consulta);

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

        $fecha_inicial = date("Y/m/d")." - ".date("Y/m/d");
        
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::campoTextoCorto("*fechas", $textos["FECHA_DESDE"].'  -  '.$textos["FECHA_HASTA"], 25, 25,$fecha_inicial, array("title" => $textos["RANGO_FECHAS"], "class" => "fechaRango"))
            ),
            array(
                HTML::listaSeleccionSimple("*tipo_comprobante", $textos["TIPO_COMPROBANTE"], HTML::generarDatosLista("tipos_comprobantes", "codigo", "descripcion", "codigo > 0"))
            ),
        );

        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "imprimirItem('0');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

} else if (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error          = false;
    $mensaje        = $textos["ITEM_ADICIONADO"];
    $ruta_archivo   = "";

    $fechas            = explode('-',$forma_fechas);
    $forma_fecha_desde = trim($fechas[0]);
    $forma_fecha_hasta = trim($fechas[1]);

    if (!isset($forma_sucursales)) {
        $error   = true;
        $mensaje = $textos["SUCURSAL_VACIO"];

    } else {

        $nombre         = "";
        $nombreArchivo  = "";
        do {
            $cadena         = Cadena::generarCadenaAleatoria(8);
            $nombre         = $sesion_sucursal.$cadena.".pdf";
            $nombreArchivo  = $rutasGlobales["archivos"]."/".$nombre;
        } while (is_file($nombreArchivo));

        $archivo                = new PDF("P","mm","Letter");
        $archivo->textoCabecera = $textos["FECHA"].": ".date("Y-m-d");
        $archivo->textoPiePagina = "";

        $condicion_comprobante = "AND codigo_tipo_comprobante = '".$forma_tipo_comprobante."'";
        if ($forma_tipo_comprobante == 0) {
            $condicion_comprobante = "";
        }

        $sucursales_reporte = array();
        foreach ($forma_sucursales AS $sucursal) {
            if (!isset($sucursales_reporte[$forma_consolidar[$sucursal]])){
                $sucursales_reporte[$forma_consolidar[$sucursal]] = "'".$sucursal."',";
            } else {
                $sucursales_reporte[$forma_consolidar[$sucursal]] .= "'".$sucursal."',";
            }
        }

        $total_debito_empresa      = 0;
        $total_credito_empresa     = 0;

        $contador_datos = 0;

        foreach ($sucursales_reporte AS $sucursal => $condicion_sucursal) {

            $condicion_sucursal = trim($condicion_sucursal, ",");
            $consolidadas       = explode(",", $condicion_sucursal);

            $consulta   = SQL::seleccionar(array("movimientos_contables_consolidados"),array("*"),"(fecha_contabilizacion BETWEEN '".$forma_fecha_desde."' AND '".$forma_fecha_hasta."') AND codigo_sucursal_genera IN (".$condicion_sucursal.") ".$condicion_comprobante,"","fecha_contabilizacion ASC, numero_comprobante ASC");

            $total_debito_sucursal     = 0;
            $total_credito_sucursal    = 0;
            $cabecera                  = true;
            
            if (SQL::filasDevueltas($consulta)) {
                
                cabeceraComprobante($archivo,$textos,$sucursal,$forma_fecha_desde,$forma_fecha_hasta,$consolidadas,"REPODIMC");
                
                while ($datos = SQL::filaEnObjeto($consulta)) {

                    if($archivo->breakCell(6)){
                        $cabecera = true;
                        cabeceraComprobante($archivo,$textos,$sucursal,$forma_fecha_desde,$forma_fecha_hasta,$consolidadas);
                    }
                    
                    if($cabecera){
                        cabeceraTabla($archivo,$textos,$datos);
                        $cabecera = false;
                    }

                    colorCeldas($archivo);

                    $cuenta_movimiento = $datos->codigo_contable."-".SQL::obtenerValor("plan_contable","descripcion","codigo_contable = '".$datos->codigo_contable."'");
                    $archivo->SetFont('Arial','',6);
                    $archivo->Cell(30,4,$datos->numero_comprobante,0,0,'L',true);
                    $archivo->Cell(30,4,$datos->fecha_contabilizacion,0,0,'C',true);
                    $archivo->Cell(80,4,$cuenta_movimiento,0,0,'L',true,"",true);
                    if($datos->sentido_movimiento == "D"){
                        $debito  = "$".number_format($datos->valor,0);
                        $credito = "$0";
                        $total_debito_sucursal += $datos->valor;
                        $total_debito_empresa  += $datos->valor;
                    }else{
                        $credito = "$".number_format($datos->valor,0);
                        $debito  = "$0";
                        $total_credito_sucursal += $datos->valor;
                        $total_credito_empresa  += $datos->valor;
                    }
                    
                    $archivo->Cell(30,4,$debito,0,0,'R',true);
                    $archivo->Cell(30,4,$credito,0,0,'R',true);
                    $archivo->Ln(4);
                    $contador_datos++;
                }
                totalSucursal($archivo,$textos,$total_debito_sucursal,$total_credito_sucursal);                
            }
        }

        //totalEmpresa($archivo,$textos,$total_debito_empresa,$total_credito_empresa);
        
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if($contador_datos > 0){
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
        }else{
            $error   = true;
            $mensaje = $textos["ERROR_GENERAR_ARCHIVO"];
        }
    }

    // Enviar datos con la respuesta del proceso al script que origino la petición
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    $respuesta[2] = $ruta_archivo;
    HTTP::enviarJSON($respuesta);
}
?>
