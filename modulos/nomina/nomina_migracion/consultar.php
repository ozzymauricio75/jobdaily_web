<?php
/**
*
* Copyright (C) 2020 Jobdaily
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
if(!empty($url_generar)){
    // Verificar que se haya enviado el ID del elemento a consultar
    if(empty($url_id)){
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";
    }else{
        $llave = explode("|",$url_id);
        $ano_generacion              = $llave[0];
        $mes_generacion              = $llave[1];
        $codigo_planilla             = $llave[2];
        $periodo_pago                = $llave[3];
        $codigo_transaccion_contable = (int)$llave[4];
        $consecutivo                 = $llave[5];

        $vista     = "movimientos_nomina_migracion";
        $columnas  = SQL::obtenerColumnas($vista);
        $condicion = "ano_generacion ='$ano_generacion' AND mes_generacion='$mes_generacion' AND codigo_planilla='$codigo_planilla'";
        $condicion .= " AND periodo_pago='$periodo_pago' AND codigo_transaccion_contable='$codigo_transaccion_contable' AND consecutivo='$consecutivo'";
        $consulta = SQL::seleccionar(array($vista), $columnas, $condicion);
        $datos    = SQL::filaEnObjeto($consulta);

        $sucursal             = SQL::obtenervalor("sucursales","nombre","codigo='$datos->codigo_sucursal'");
        $nombre_empleado      = SQL::obtenervalor("menu_terceros","NOMBRE_COMPLETO","id='$datos->documento_identidad_empleado'");
        $transaccion_contable = SQL::obtenervalor("transacciones_contables_empleado","descripcion","codigo='$datos->codigo_transaccion_contable'");
        $planilla             = SQL::obtenervalor("planillas","descripcion","codigo='$datos->codigo_planilla'");
        $codigo_transaccion_contable = substr($codigo_transaccion_contable,0,4);

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

        $error  = "";
        $titulo = $componente->nombre;

        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo_sucursal", $textos["SUCURSAL"],$sucursal)
            ),
            array(
                HTML::mostrarDato("empleado", $textos["EMPLEADO"], $datos->documento_identidad_empleado." ".$nombre_empleado)
            ),
            array(
                HTML::mostrarDato("transaccion",$textos["TRANSACCION_CONTABLE"],$codigo_transaccion_contable." ".$transaccion_contable)
            ),
            array(
                HTML::mostrarDato("valor_movimiento",$textos["VALOR_MOVIMIENTO"],number_format($datos->valor_movimiento))
            ),
            array(
                HTML::mostrarDato("codigo_planilla", $textos["PLANILLA"],$planilla)
            ),
            array(
                HTML::mostrarDato("periodo", $textos["PERIODO"],$periodo[$datos->periodo_pago])
            ),
            array(
                HTML::mostrarDato("fecha_pago_planilla", $textos["FECHA_PAGO"],$datos->fecha_pago_planilla)
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
}
?>
