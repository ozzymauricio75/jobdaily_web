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

$periodos = array(
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

if(!empty($url_generar)){

    if(empty($url_id)){

    $error     = $textos["ERROR_CONSULTAR_VACIO"];
    $titulo    = "";
    $contenido = "";

    }else{

    $llave_movimiento = explode("|",$url_id);
    $ano = $llave_movimiento[0];
    $mes = $llave_movimiento[1];
    $codigo_planilla = $llave_movimiento[2];
    $periodo   = $llave_movimiento[3];
    $documento_identidad_empleado = $llave_movimiento[4];
    $consecutivo = $llave_movimiento[5];
    $sucursal    = $llave_movimiento[6];
    $fecha_pago_planilla = $llave_movimiento[7];

    $condicion  = " ano_generacion='$ano' AND mes_generacion='$mes' AND codigo_planilla='$codigo_planilla'";
    $condicion .= " AND periodo_pago='$periodo' AND documento_identidad_empleado='$documento_identidad_empleado'";

    $consulta_movimiento = SQL::seleccionar(array("movimiento_novedades_manuales"),array("*"),$condicion." AND contabilizado = '0'");
    if(SQL::filasDevueltas($consulta_movimiento)){
        $error  = "";
        $titulo = $componente->nombre;

        $items_novedades_manuales = array();
        $consulta_movimiento = SQL::seleccionar(array("movimiento_novedades_manuales"),array("*"),$condicion);

        while($movimiento_novedad = SQL::filaEnObjeto($consulta_movimiento)){

            $nombre_transaccion = SQL::obtenerValor("transacciones_contables_empleado","nombre","codigo='$movimiento_novedad->codigo_transaccion_contable'");
            $nombre_anexo       = SQL::obtenerValor("anexos_contables","descripcion","codigo='$movimiento_novedad->codigo_anexo_contable'");
            $nombre_auxiliar    = SQL::obtenerValor("auxiliares_contables","descripcion","codigo='$movimiento_novedad->codigo_auxiliar_contable' AND codigo_empresa='$movimiento_novedad->codigo_empresa_auxiliar' AND codigo_anexo_contable='$movimiento_novedad->codigo_anexo_contable'");

            $items_novedades_manuales[] = array(
             "",
             $nombre_transaccion,
             $movimiento_novedad->valor_movimiento,
             $nombre_anexo,
             $nombre_auxiliar
            );
        }
        //echo var_dump($sucursal);
        $nombre_susursal = SQL::obtenerValor("sucursales","nombre","codigo='$sucursal'");
        $nombre_empleado = SQL::obtenerValor("menu_movimiento_novedades_manuales","EMPLEADO","id='$url_id'");
        //// Definición de pestaña Basica ////
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("nombre_sucursal", $textos["SUCURSAL"], $nombre_susursal),
                HTML::mostrarDato("nombre_empleado", $textos["EMPLEADO"], $nombre_empleado)
           ),
          array(
                HTML::mostrarDato("fecha_pago", $textos["FECHA_PAGO"],$fecha_pago_planilla),
                HTML::mostrarDato("nombre_periodo",$textos["PERIODO"],$periodos[$periodo])
           ),
            array(
                HTML::generarTabla(
                    array("id","TRANSACCION_CONTABLE","VALOR","ANEXO_CONTABLE","AUXILIAR_CONTABLE"),
                    $items_novedades_manuales,
                    array("C","C","C","C"),
                    "novedades_manuales",
                    false
                )
            )
        );

        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);

        }else{
            $error     = $textos["CONTABILIZADO_MOVIMIENTO"];
            $titulo    = "";
            $contenido = "";
        }
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}elseif(!empty($forma_procesar)) {

    $llave_movimiento = explode("|",$forma_id);
    $ano = $llave_movimiento[0];
    $mes = $llave_movimiento[1];
    $codigo_planilla = $llave_movimiento[2];
    $periodo   = $llave_movimiento[3];
    $documento_identidad_empleado = $llave_movimiento[4];
    $consecutivo = $llave_movimiento[5];
    $sucursal    = $llave_movimiento[6];
    $fecha_pago_planilla = $llave_movimiento[7];

    $condicion  = " ano_generacion='$ano' AND mes_generacion='$mes' AND codigo_planilla='$codigo_planilla'";
    $condicion .= " AND periodo_pago='$periodo' AND documento_identidad_empleado='$documento_identidad_empleado' AND consecutivo ='$consecutivo'";

    $consulta = SQL::eliminar("movimiento_novedades_manuales",$condicion);
    if($consulta){
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    }else{
        $error   = true;
        $mensaje = $textos["ERROR_ELIMINAR_ITEM"];
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
