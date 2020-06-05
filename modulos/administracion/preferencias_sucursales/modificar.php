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

    $cantidad_resoluciones_dian = SQL::filasDevueltas(SQL::seleccionar(array("resoluciones_dian"),array("*"),"codigo_concepto_resolucion_dian = 1 AND estado ='1'"));

    if($cantidad_resoluciones_dian == 0){
        $mensaje       = $textos["ERROR_TABLAS"];
        $listaMensajes = array();

        if($cantidad_resoluciones_dian == 0){
            $listaMensajes[] = $textos["TABLA_VACIA_RESOLUCIONES"];
        }

        $tablas    = implode("\n",$listaMensajes);
        $mensaje  .= $tablas;
        $error     = $mensaje;
        $titulo    = "";
        $contenido = "";
    }else{

        // Verificar que se haya enviado el ID del elemento a modificar
        if (empty($url_id)) {
            $error     = $textos["ERROR_MODIFICAR_VACIO"];
            $titulo    = "";
            $contenido = "";
        } else {
            $error     = "";
            $idActual  = $componente->id;
            $titulo    = $componente->nombre;

            $preferencias = array();
            $preferencias["codigo_resolucion_dian_computador"] = 0;

            $preferencias_sucursal = SQL::seleccionar(array("preferencias"),array("valor", "variable"),"tipo_preferencia='3' AND codigo_sucursal='".$url_id."'");
            if(SQL::filasDevueltas($preferencias_sucursal)){
                while ($datos = SQL::filaEnObjeto($preferencias_sucursal)) {
                    $preferencias[$datos->variable] = $datos->valor;
                }
            }

            $tipo_resolucion = array(
                "1" => $textos["AUTORIZADA"],
                "2" => $textos["HABILITADA"],
                "3" => $textos["TRAMITE"]
            );

            $consulta = SQL::seleccionar(array("resoluciones_dian"),array("*"),"codigo_concepto_resolucion_dian = 1 AND estado ='1'");
            if (SQL::filasDevueltas($consulta)){
                while($datos_resolucion = SQL::filaEnObjeto($consulta)){

                    $llave       = $datos_resolucion->codigo_sucursal."|".$datos_resolucion->numero;
                    $descripcion = $textos["NUMERO"].": ".$datos_resolucion->numero." - ".$textos["FECHA"].": ".$datos_resolucion->fecha_inicia;
                    $descripcion .= "/".$datos_resolucion->fecha_termina." - ".$textos["PREFIJO"].": ".$datos_resolucion->prefijo." - ";
                    $descripcion .= $textos["FACTURAS"].": ".number_format($datos_resolucion->factura_inicial)." - ".number_format($datos_resolucion->factura_final);
                    $descripcion .= " - ".$tipo_resolucion[$datos_resolucion->tipo_resolucion];

                    $resoluciones_computador[$llave] = $descripcion;
                }
            } else {
                $resoluciones_computador[] = "";
            }

            $formularios["PESTANA_CLIENTES"] = array(
                array(
                    HTML::listaSeleccionSimple("*codigo_resolucion_dian_computador", $textos["RESOLUCION_DIAN_COMPUTADOR"], $resoluciones_computador, $preferencias["codigo_resolucion_dian_computador"])
                )
            );

            // Definicion de botones
            $botones = array(HTML::boton("botonAceptar", $textos["ACEPTAR"], "modificarItem('".$url_id."');", "aceptar"));

            $componente = new Componente($idActual);
            $contenido  = HTML::generarPestanas($formularios, $botones);
        }
    }

    // Enviar datos para la generacion del formulario al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);

// Modificar el elemento seleccionado
} elseif (!empty($forma_procesar)) {

    // Asumir por defecto que no hubo error
    $error   = false;
    $mensaje = $textos["ITEM_MODIFICADO"];

    $elementos_preferencias = array();
    $elementos_preferencias["codigo_resolucion_dian_computador"] = $forma_codigo_resolucion_dian_computador;

    foreach($elementos_preferencias AS $id_vector => $valor_vector){
        $datos = array(
            "codigo_empresa"   => 0,
            "codigo_sucursal"  => $forma_id,
            "codigo_usuario"   => 0,
            "tipo_preferencia" => "3",
            "variable"         => $id_vector,
            "valor"            => $valor_vector
        );
        $modificar = SQL::reemplazar("preferencias", $datos);
    }

    // Enviar datos con la respuesta del proceso al script que origino la peticion
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
