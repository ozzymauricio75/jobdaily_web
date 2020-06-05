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

if(isset($url_validarItemsllaves))
{
    if($url_item=="codigo" && !empty($url_valor))
    {

        $llave_primaria=explode("|", $url_valor);
        $llave_primaria =str_pad($llave_primaria[0],3,"0", STR_PAD_LEFT)."|".$llave_primaria[1]."|".str_pad($llave_primaria[2],8,"0", STR_PAD_LEFT);
        
        $existe = SQL::existeItem("buscador_auxiliares_contables","id",$llave_primaria);
        if ($existe) {
            $mensaje = $textos["ERROR_EXISTE_CODIGO"];
            HTTP::enviarJSON($mensaje);
        }
    }
}


if (!empty($url_generar)) {
    $error     = "";
    $titulo    = $componente->nombre;
    $contenido = "";

    $consulta_empresas = SQL::seleccionar(array("empresas"),array("*"),"codigo>0");
    $consulta_anexos   = SQL::seleccionar(array("anexos_contables"),array("*"),"codigo!=''");

    if (SQL::filasDevueltas($consulta_empresas)){
        $existen_empresas = true;
    }
    if (SQL::filasDevueltas($consulta_anexos)){
        $existen_anexos = true;
    }
    if (isset($existen_empresas) && isset($existen_anexos)){
        if ($sesion_usuario == $datosGlobales["usuarioMaestro"]){

            while($datos_empresa = SQL::filaEnObjeto($consulta_empresas)){
                $empresas[$datos_empresa->codigo] = $datos_empresa->razon_social;
            }
        } else {

            $tablas     = array(
                "a" => "perfiles_usuario",
                "b" => "componentes_usuario",
                "c" => "sucursales"
            );
            $columnas = array(
                "codigo" => "c.codigo",
                "nombre" => "c.nombre_corto"
            );
            $condicion = "c.codigo = a.codigo_sucursal AND a.id = b.id_perfil
                        AND a.codigo_usuario = '$sesion_codigo_usuario'
                        AND b.id_componente = '".$componente->id."'";

            $consulta = SQL::seleccionar($tablas, $columnas, $condicion);

            if (SQL::filasDevueltas($consulta)) {
                while ($datos = SQL::filaEnObjeto($consulta)) {
                    $codigo_sucursal[] = $datos->codigo;
                }

                $condicion = "codigo IN (".implode(",", $codigo_sucursal).")";
                $consulta_empresas = SQL::seleccionar(array("sucursales"),array("codigo_empresa","nombre"),$condicion);
                if (SQL::filasDevueltas($consulta_empresas)){
                    while($datos_empresa = SQL::filaEnObjeto($consulta_empresas)){
                        $empresas[$datos_empresa->codigo_empresa] = $datos_empresa->nombre;
                        $privilegios = true;
                    }
                }
            }
        }
        
        if (isset($empresas)){
            $campo_llave_primaria="codigo_empresa|codigo_anexo_contable|codigo";

            /*** Definición de pestañas general ***/
            $formularios["PESTANA_GENERAL"] = array(
                array(
                    HTML::listaSeleccionSimple("*codigo_empresa", $textos["EMPRESA"], $empresas,"", array("title" => $textos["AYUDA_EMPRESA"])),
                    HTML::listaSeleccionSimple("*codigo_anexo_contable", $textos["ANEXO_CONTABLE"], HTML::generarDatosLista("anexos_contables", "codigo", "descripcion","codigo != ''"),"", array("title" => $textos["AYUDA_ANEXO"]))
                ),
                array(
                    HTML::campoTextoCorto("*codigo", $textos["CODIGO"], 8, 8, "", array("title" => $textos["AYUDA_CODIGO"],"onBlur" => "validarItemsllaves(this,'$campo_llave_primaria')","onKeyPress" => "return campoEntero(event)")),
                    HTML::campoTextoCorto("*descripcion", $textos["DESCRIPCION"], 20, 255, "", array("title" => $textos["AYUDA_DESCRIPCION"],"onBlur" => "validarItem(this);"))
                )
            );

            /*** Definición de botones ***/
            $botones = array(
                HTML::boton("botonAceptar", $textos["ACEPTAR"], "adicionarItem();", "aceptar")
            );

            $contenido = HTML::generarPestanas($formularios, $botones);
        } else {
            $error = $textos["USUARIO_SIN_PRIVILEGIOS"];
        }
    } else {

        $error = $textos["NO_EXISTEN"];
        if (!isset($existen_empresas)){
            $error .= $textos["CREAR_EMPRESA"];
        }
        if (!isset($existen_anexos)){
            $error .= $textos["CREAR_ANEXO"];
        }
        $error .= $textos["CREAR"];
    }

    /*** Enviar datos para la generación del formulario al script que originó la petición ***/
    $respuesta[0] = $error;
    $respuesta[1] = $titulo;
    $respuesta[2] = $contenido;
    HTTP::enviarJSON($respuesta);
 
/*** Adicionar los datos provenientes del formulario ***/
} elseif (!empty($forma_procesar)) {
    /*** Asumir por defecto que no hubo error ***/
    $error   = false;
    $mensaje = $textos["ITEM_ADICIONADO"];
    
    /*** Validar campos requeridos ***/

     //str_pad($forma_id_empresa,3,"0", STR_PAD_LEFT) permite rellenar los campos con ceros
     $llave_primaria =str_pad($forma_codigo_empresa,3,"0", STR_PAD_LEFT)."|".$forma_codigo_anexo_contable."|".str_pad($forma_codigo,8,"0", STR_PAD_LEFT);

    if(SQL::existeItem("buscador_auxiliares_contables","id",$llave_primaria)){
		$error   = true;
		$mensaje = $textos["ERROR_EXISTE_CODIGO"];

	}elseif(empty($forma_codigo)){
		$error   = true;
		$mensaje = $textos["CODIGO_VACIO"];
		
	}elseif(empty($forma_codigo_anexo_contable)) {
		$error   = true;
		$mensaje = $textos["ANEXO_VACIO"];
	
	}elseif(empty($forma_descripcion)) {
		$error   = true;
		$mensaje = $textos["DESCRIPCION_VACIO"];

    } else {
		/*** Insertar datos ***/
        $datos = array(
            "codigo_empresa"        => $forma_codigo_empresa,
            "codigo_anexo_contable" => $forma_codigo_anexo_contable,
            "codigo"                => $forma_codigo,
            "descripcion"           => $forma_descripcion
        );
        $insertar = SQL::insertar("auxiliares_contables", $datos);

        /*** Error de inserción ***/
        if (!$insertar) {
            $error   = true;
            $mensaje = $textos["ERROR_ADICIONAR_ITEM"];
        }
    }
    /*** Enviar datos con la respuesta del proceso al script que originó la petición ***/
    $respuesta    = array();
    $respuesta[0] = $error;
    $respuesta[1] = $mensaje;
    HTTP::enviarJSON($respuesta);
}
?>
