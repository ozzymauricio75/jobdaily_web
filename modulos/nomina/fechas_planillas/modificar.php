<?php
/**
*
* Copyright (C) 2020 Jobdaily
* Raul Mauricio Oidor Lozano <ozzymauricio75@gmail.com>
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

$meses = array(
    "1"  => $textos["ENERO"],
    "2"  => $textos["FEBRERO"],
    "3"  => $textos["MARZO"],
    "4"  => $textos["ABRIL"],
    "5"  => $textos["MAYO"],
    "6"  => $textos["JUNIO"],
    "7"  => $textos["JULIO"],
    "8"  => $textos["AGOSTO"],
    "9"  => $textos["SEPTIEMBRE"],
    "10" => $textos["OCTUBRE"],
    "11" => $textos["NOVIEMBRE"],
    "12" => $textos["DICIEMBRE"]
);
$dias = array(
    "Sunday"    => $textos["DOMINGO"],
    "Monday"    => $textos["LUNES"],
    "Tuesday"   => $textos["MARTES"],
    "Wednesday" => $textos["MIERCOLES"],
    "Thursday"  => $textos["JUEVES"],
    "Friday"    => $textos["VIERNES"],
    "Saturday"  => $textos["SABADO"]
);

if(isset($url_verificar) && isset($url_id_planilla)){
    $planillas = SQL::obtenerValor("planillas","periodo_pago","codigo = '".$url_id_planilla."'");
    HTTP::enviarJSON($planillas);
    exit;
}
// generar automaticamente las fechas de las planillas
if (isset($url_generarDiasPago)){

    $periodo         = SQL::obtenerValor("planillas","periodo_pago","codigo = '".$url_id_planilla."'");
    $fechas          = array();
    $url_lista_tabla = trim($url_lista_tabla,"/");
    if(!empty($url_lista_tabla)){
        $listaTabla = explode("/",$url_lista_tabla);
    }else{
        $listaTabla = array();
    }
    if($periodo == '1'){
        $fechas = generarFechasMes($url_fecha_completa,$url_ano_final,$url_id_planilla,$textos,$meses,$dias,$listaTabla);
    }else if($periodo == '2'){
        $fechas = generarFechasQuincena($url_fecha_completa,$url_ano_final,$url_id_planilla,$textos,$meses,$dias,$listaTabla);
    }else if($periodo == '3'){
        $fechas = generarFechasSemana($url_fecha_completa,$url_ano_final,$url_id_planilla,$textos,$meses,$dias,$listaTabla);
    } else {

        $date_r = getdate(strtotime($url_fecha_completa));
        $fechas = array($url_fecha_completa.",".$meses[$date_r["mon"]].",".$dias[$date_r["weekday"]].",1");
    }
    $fechas = ordenarFechas($fechas);
    HTTP::enviarJSON($fechas);
    exit;
}

// Generar el formulario para la captura de datos
if (!empty($url_generar)) {

    if(empty($url_id)){
        $error     = $textos["ERROR_MODIFICAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    }else{
        $error  = "";
        $titulo = $componente->nombre;

        $llave_principal = explode("|",$url_id);
        $codigo_planilla = $llave_principal[0];
        $ano_planilla    = $llave_principal[1];

        $descripcion   = SQL::obtenerValor("planillas","descripcion", "codigo = '".$codigo_planilla."'");
        $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago", "codigo = '".$codigo_planilla."'");

        $ano = array();
        $ano[(int)date("Y")] = date("Y");
        for($i=1;$i<8;$i++){
            $ano[(int)date("Y")+$i]=date("Y")+$i;
        }

        $estado  = "oculto";
        $estado2 = "oculto";
        if($tipo_planilla == '2'){
            $estado = "";
        }else{
            $estado2 = "";
        }

        //echo var_dump($llave_principal[1]);

        $listaFechas   = cargarFechasModificar($codigo_planilla,$meses,$dias,$llave_principal[1]);
        $tipo_planilla = SQL::obtenerValor("planillas","periodo_pago","codigo='$codigo_planilla'");
        $fecha_pago           = $ano_planilla."-01-01";
        $fecha_pago_quincenal1 = $ano_planilla."/01/15";
        $fecha_pago_quincenal2 = $ano_planilla."/01/30";

        // Definicion de pestana personal
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("planilla", $textos["PLANILLA"],$descripcion)
               .HTML::campoOculto("id_planilla",$codigo_planilla),
                HTML::mostrarDato("ano", $textos["ANO"],$ano_planilla)
               .HTML::campoOculto("ano",$ano_planilla),
            ),
            array(
                HTML::contenedor(
                    HTML::campoTextoCorto("*fecha_unica", $textos["FECHA_PAGO"], 10, 10,$fecha_pago, array("title" => $textos["AYUDA_FECHA_PAGO"], "class" => "selectorFechaBloquear")).HTML::campoOculto("minDate",$fecha_pago),
                    array("id" => "contenedor_fecha_unica" , "class" => $estado2)
                ),
                HTML::contenedor(
                    HTML::campoTextoCorto("*fecha_fin", $textos["FECHA_PAGO"],20,10,$fecha_pago_quincenal1." - ".$fecha_pago_quincenal2, array("title" => $textos["AYUDA_FECHA_PAGO"], "class" => "fechaRangoBloquear")),
                    array("id" => "contenedor_fechas", "class" => $estado)
                )
            ),
            array (
                HTML::boton("botonAgregar", $textos["GENERAR"], "generarDias();", "adicionar"),
                HTML::boton("botonEliminar",$textos["ELIMINAR_TODOS"],"removerTable(this);", "eliminar"),
                HTML::contenedor(HTML::boton("botonRemover", "", "removerItem(this);", "eliminar"), array("id" => "removedor", "style" => "display: none"))
               .HTML::campoOculto("tipo_planilla",$tipo_planilla)
               .HTML::campoOculto("fecha_soporte", "")
               .HTML::campoOculto("existe_fecha", $textos["ERROR_EXISTE_FECHA"])
               .HTML::campoOculto("error_fecha_quincena1", $textos["ERROR_FECHA_QUINCENA1"])
               .HTML::campoOculto("error_fecha_quincena2", $textos["ERROR_FECHA_QUINCENA2"])
               .HTML::campoOculto("fuera_mes", $textos["FUERA_MES"])
               .HTML::campoOculto("error_menor_16", $textos["ERROR_MENOR_16"])
               .HTML::campoOculto("error_mayor_15", $textos["ERROR_MAYOR_15"])
               .HTML::campoOculto("domingo", $textos["DOMINGO"])
               .HTML::campoOculto("lunes", $textos["LUNES"])
               .HTML::campoOculto("martes", $textos["MARTES"])
               .HTML::campoOculto("miercoles", $textos["MIERCOLES"])
               .HTML::campoOculto("jueves", $textos["JUEVES"])
               .HTML::campoOculto("viernes", $textos["VIERNES"])
               .HTML::campoOculto("sabado", $textos["SABADO"])
            ),
            array(
                HTML::contenedor(HTML::boton("botonRemover", "","removerItems(this);", "eliminar"), array("id" => "botonRemover", "style" => "display: none")),
            ),
            array(
                HTML::generarTabla(
                    array("id","","MES","DIA","DIA_CRONOLOGICO"),
                    $listaFechas,
                    array("C","I","I","I"),
                    "listaDiasPagos",
                    false
                )
            )
        );
        // Definicion de botones
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
        );
        $contenido = HTML::generarPestanas($formularios, $botones);
    }
    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Adicionar los datos provenientes del formulario
}elseif (!empty($forma_procesar)) {
    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $condicion = " AND fecha>='$forma_ano-01-01' AND fecha<='$forma_ano-12-31'";
    $eliminar = SQL::eliminar("fechas_planillas","codigo_planilla = '$forma_id_planilla' $condicion");
    for($id = 0;!empty($forma_fecha_tabla[$id]); $id++){
        $datos = array (
            "codigo_planilla" => $forma_id_planilla,
            "fecha"           => $forma_fecha_tabla[$id]
        );
        $insertar = SQL::insertar("fechas_planillas", $datos);
        //$insertar = SQL::reemplazar("fechas_planillas", $datos);
        // Error de insercion
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"]."\n";
            $mensaje.= mysql_error();
        }
    }
    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
