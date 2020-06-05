<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Walter Andrés Márquez Gutiérrez <walteramg@gmail.com>
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


/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "transacciones_tiempo";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);        
        $error         = "";
        $titulo        = $componente->nombre;
        
        
        $transaccion_contable	= SQL::obtenerValor("seleccion_transacciones_contables_empleado", "SUBSTRING_INDEX(nombre,'|',1)", "id = '$datos->codigo_transaccion_contable'");
        $concepto_tiempo        = SQL::obtenerValor("conceptos_transacciones_tiempo", "descripcion", "codigo = '$datos->codigo_concepto_transaccion_tiempo'");
        $tipo_concepto          = SQL::obtenerValor("conceptos_transacciones_tiempo", "tipo", "codigo = '$datos->codigo_concepto_transaccion_tiempo'");
    
        
        if($tipo_concepto == 1){
            $tipo_concepto = $textos["HORAS_LABORALES"];
        }
        
        else if ($tipo_concepto == 2){
            $tipo_concepto = $textos["LICENCIAS_SUSPENSIONES"];
        }
        
        else if ($tipo_concepto == 3){
            $tipo_concepto = $textos["INCAPACIDADES"];
        }
        if($datos->tasa == NULL){
            $clase = "oculto";
            $clase2 = "";
        }
        
        else{
            $clase  = "";
            $clase2 = "oculto";
        }
        
        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_BASICA"] = array(
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            array(
                HTML::mostrarDato("descripcion", $textos["DESCRIPCION"], $datos->descripcion)
            ),
            array(
                HTML::mostrarDato("plan_contable", $textos["TRANSACCION_CONTABLE"], $transaccion_contable)
            ),
            array(
                HTML::mostrarDato("tipo_concepto", $textos["TIPO_CONCEPTO"], $tipo_concepto)
            ),
            array(
                HTML::mostrarDato("concepto_contable", $textos["CONCEPTO_TIEMPO"], $concepto_tiempo),
                HTML::contenedor(
                    HTML::mostrarDato("retencion", $textos["TASA"], $datos->tasa),
                    array("id" => "contenedor_tasa", "class" => $clase)
                ),
                HTML::contenedor(
                    HTML::mostrarDato("dividendo", $textos["DIVIDENDO"], $datos->tasa),
                    array("id" => "contenedor_dividendo", "class" => $clase2)
                ),
                HTML::contenedor(HTML::mostrarDato("divisor", $textos["DIVISOR"], $datos->tasa),
                    array("id" => "contenedor_divisor", "class" => $clase2)
                )
            )
        );
        
        if($datos->resta_salario == "1" )
            {$salario = $textos["SI"];}
        else{
            $salario = $textos["NO"];
        }
        
        if($datos->resta_auxilio_transporte == "1" ){
            $auxilio = $textos["SI"];
        }
        else{
            $auxilio = $textos["NO"];
        }
        
        if($datos->resta_cesantias == "1" ){
            $cesantias = $textos["SI"];
        }
        else{
            $cesantias = $textos["NO"];
        }
        
        if($datos->resta_prima == "1" ){
            $prima = $textos["SI"];
        }
        else{
            $prima = $textos["NO"];
        }
        
        if($datos->resta_vacaciones == "1" ){
            $vacaciones = $textos["SI"];
        }
        else{
            $vacaciones = $textos["NO"];
        }

        /*** Definición de pestaña personal ***/
        $formularios["PESTANA_CONTABLE"] = array(
            array(
                HTML::mostrarDato("salario", $textos["SALARIO"], $salario)
            ),
            array(
                HTML::mostrarDato("auxilio", $textos["AUXILIO_TRANSPORTE"], $auxilio)               
            ),    
            array(
                HTML::mostrarDato("cesantias", $textos["CESANTIAS"], $cesantias)
            ),
            array(
                HTML::mostrarDato("prima", $textos["PRIMA"], $prima)   
            ),    
            array(
                HTML::mostrarDato("vacaciones", $textos["VACACIONES"], $vacaciones)
            )
        );

        $contenido = HTML::generarPestanas($formularios);
    }

    /*** Enviar datos para la generación del formulario al script que originï¿½ la peticiï¿½n ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
}
?>
