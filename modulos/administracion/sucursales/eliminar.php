<?php

/**
*
* Copyright (C) 2008 Felinux Ltda
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

/*** Generar el formulario para la captura de datos ***/
if (!empty($url_generar)) {

    /*** Verificar que se haya enviado el ID del elemento a consultar ***/
    if (empty($url_id)) {
        $error     = $textos["ERROR_CONSULTAR_VACIO"];
        $titulo    = "";
        $contenido = "";

    } else {
        $vistaConsulta = "sucursales";
        $columnas      = SQL::obtenerColumnas($vistaConsulta);
        $consulta      = SQL::seleccionar(array($vistaConsulta), $columnas, "codigo = '$url_id'");
        $datos         = SQL::filaEnObjeto($consulta);
        $tipo          = $datos->tipo;

        if ($tipo == 1){
            $tipo = $textos["SUCURSAL"];
        }elseif ($tipo == 2) {
            $tipo = $textos["UNION_TEMPORAL"];
        }else{
            $tipo = $textos["PRINCIPAL"];
        }

        $error         = "";
        $titulo        = $componente->nombre;

        $activo = array(
            "0" => $textos["ESTADO_INACTIVA"],
            "1" => $textos["ESTADO_ACTIVA"]
        );

        $tipoEmpresa = array(
            "1" => $textos["EMPRESA_DISTRIBUIDORA_MAYORISTA"],
            "2" => $textos["EMPRESA_VENTAS_PUBLICO"],
            "3" => $textos["EMPRESA_AMBAS"],
            "4" => $textos["EMPRESA_SOPORTE"]
        );

        $indicador = array(
            "0" => $textos["INDICADOR_NO"],
            "1" => $textos["INDICADOR_SI"]
        );

        /*** Obtener valores ***/

        $llave_primaria_municipio = $datos -> codigo_iso.",".$datos->codigo_dane_departamento.",".$datos -> codigo_dane_municipio;

        $municipio = SQL::obtenerValor("seleccion_municipios","nombre","llave_primaria = '$llave_primaria_municipio'");
        $municipio = explode("|",$municipio);
        $municipio = $municipio[0];
              // echo var_dump($municipio);

        $empresa   = SQL::obtenerValor("empresas","razon_social","codigo = '$datos->codigo_empresa'");




        /*** Definición de pestañas general ***/
        $formularios["PESTANA_GENERAL"] = array(
            array(
                HTML::mostrarDato("codigo", $textos["CODIGO"], $datos->codigo)
            ),
            array(
                HTML::mostrarDato("empresa", $textos["EMPRESA"], $empresa)
            ),
            array(
                HTML::mostrarDato("nombre", $textos["NOMBRE"], $datos->nombre)
            ),
            array(
                HTML::mostrarDato("nombre_corto", $textos["NOMBRE_CORTO"], $datos->nombre_corto),
                HTML::mostrarDato("activo", $textos["ESTADO"], $textos["ACTIVO_".intval($datos->activo)])
            ),
            array(
                HTML::mostrarDato("tipo", $textos["TIPO"], $tipo),
            )
        );

        $formularios["PESTANA_UBICACION"] = array(
            array(
                HTML::mostrarDato("municipio", $textos["MUNICIPIO"], $municipio)
            ),
            array(
                HTML::mostrarDato("*direccion_residencia", $textos["DIRECCION_RESIDENCIA"], $datos->direccion_residencia)
            ),
            array(
                HTML::mostrarDato("telefono_1", $textos["TELEFONO_1"], $datos->telefono_1)
            ),
            array(
                HTML::mostrarDato("telefono_2", $textos["TELEFONO_2"], $datos->telefono_2)
            ),
            array(
                HTML::mostrarDato("celular", $textos["CELULAR"], $datos->celular)
            )
        );

        /*** Definición de pestañas tributaria ***/
        /*$formularios["PESTANA_CONTABLE"] = array(
            array(
                HTML::mostrarDato("codigo_empresa_consolida", $textos["EMPRESA_CONSOLIDA"], $datos->codigo_empresa_consolida),
                HTML::mostrarDato("codigo_sucursal_consolida", $textos["ALMACEN_CONSOLIDA"], $datos->codigo_sucursal_consolida),
                HTML::mostrarDato("tipo_empresa", $textos["TIPO_EMPRESA"], $tipoEmpresa[$datos->tipo_empresa])
            ),
            array(
                HTML::mostrarDato("orden", $textos["ORDEN"], $datos->orden),
                HTML::mostrarDato("fecha_cierre", $textos["FECHA_CIERRE"], $datos->fecha_cierre)
            ),
            array(
                HTML::mostrarDato("maneja_kardex", $textos["MANEJA_KARDEX"], $textos["SI_NO_".intval($datos->maneja_kardex)])
            ),
            array(
                HTML::mostrarDato("realiza_orden_compra", $textos["REALIZA_ORDEN_COMPRA"], $textos["SI_NO_".intval($datos->realiza_orden_compra)])
            ),
            array(
                HTML::mostrarDato("inventarios_mercancia", $textos["MANEJA_INVENTARIOS_MERCANCIA"], $textos["SI_NO_".intval($datos->inventarios_mercancia)])
            ),
            array(
                HTML::mostrarDato("cartera_clientes_mayoristas", $textos["CARTERA_CLIENTES_MAYORISTAS"], $textos["SI_NO_".intval($datos->cartera_clientes_mayoristas)])
            ),
            array(
                HTML::mostrarDato("cartera_clientes_detallistas", $textos["CARTERA_CLIENTES_DETALLISTAS"], $textos["SI_NO_".intval($datos->cartera_clientes_detallistas)])
            ),
            array(
                HTML::mostrarDato("cuentas_pagar_proveedores", $textos["CUENTAS_PAGAR_PROVEEDORES"], $textos["SI_NO_".intval($datos->cuentas_pagar_proveedores)])
            ),
            array(
                HTML::mostrarDato("nomina", $textos["NOMINA"], $textos["SI_NO_".intval($datos->nomina)])
            ),
            array(
                HTML::mostrarDato("contabilidad", $textos["CONTABILIDAD"], $textos["SI_NO_".intval($datos->contabilidad)])
            )
        );*/

         /*** Definición de botones ***/
        $botones = array(
            HTML::boton("botonAceptar", $textos["ACEPTAR"], "eliminarItem('$url_id');", "aceptar")
        );

        $contenido = HTML::generarPestanas($formularios, $botones);
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
} elseif (!empty($forma_procesar)) {
    $consulta = SQL::eliminar("sucursales", "codigo = '$forma_id'");

    if ($consulta) {
        $error   = false;
        $mensaje = $textos["ITEM_ELIMINADO"];
    } else {
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
