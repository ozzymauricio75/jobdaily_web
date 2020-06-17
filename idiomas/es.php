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

$textos = array(
    ""                                  => "",
    "NO_APLICA"                         => "No aplica",
    "SI_NO_0"                           => "No",
    "SI_NO_1"                           => "Si",
    "ACTIVO_0"                          => "Inactivo",
    "ACTIVO_1"                          => "Activo",
    "ACEPTAR"                           => "Aceptar",
    "BUSCAR"                            => "Buscar",
    "ADICIONAR"                         => "Adicionar",
    "AGREGAR"                           => "Agregar",
    "EXISTENTE"                         => "Existente",
    "CONSULTAR"                         => "Consultar",
    "MODIFICAR"                         => "Modificar",
    "ANULAR"                            => "Anular",
    "ELIMINAR"                          => "Eliminar",
    "EXPORTAR"                          => "Listar",
    "CANCELAR"                          => "Cancelar",
    "DESCARGAR"                         => "[Descargar]",
    "REMOVER"                           => "Remover",
    "SIN_REGISTROS"                     => "No se encontraron datos",
    "REGISTRO"                          => "%n registro encontrado",
    "REGISTROS"                         => "%i a %f de %r registros encontrados",
    "PAGINAS"                           => "Página %n de %t",
    "MENUINSE"                          => "Iniciar sesión",
    "CAMPO_REQUERIDO"                   => "Campo obligatorio",
    "CAMPO_REQUERIDO_TABLA"             => "Campo obligatorio para la tabla",
    "ITEM_ADICIONADO"                   => "El registro ha sido adicionado correctamente",
    "ITEM_MODIFICADO"                   => "El registro ha sido modificado correctamente",
    "ITEM_ELIMINADO"                    => "El registro ha sido eliminado correctamente",
    "ITEM_AUTORIZADO"                   => "El registro ha sido autorizado correctamente",
    "DATOS_EXPORTADOS"                  => "Los datos han sido exportados correctamente",
    "ERROR_DATOS_INCOMPLETOS"           => "Por favor ingrese los campos requeridos",
    "ERROR_REFERENCIA"                  => "Error al eliminar referencia",
    "ERROR_ADICIONAR_ITEM"              => "El registro no pudo ser adicionado",
    "ERROR_MODIFICAR_ITEM"              => "El registro no pudo ser modificado",
    "ERROR_ELIMINAR_ITEM"               => "El registro no pudo ser eliminado",
    "ERROR_CONSULTAR_VACIO"             => "Por favor seleccione el elemento que desea consultar",
    "ERROR_ANULAR_VACIO"                => "Por favor seleccione el elemento que desea anular",
    "ERROR_MODIFICAR_VACIO"             => "Por favor seleccione el elemento que desea modificar",
    "ERROR_ELIMINAR_VACIO"              => "Por favor seleccione el elemento que desea eliminar",
    "ERROR_PERIODO_CONTABLE"            => "El componente se encuentra deshabilitado dentro del periodo contable actual",
    "SIMBOLO_MONEDA"                    => "$",
    "CODIGO_CONTABLE"                   => "Codigo contable",
    "DEBITO"                            => "Debito",
    "CREDITO"                           => "Credito",
    "SIN_INFORMACION"                   => "No se genero información",
    "NO_CONTABILIZADO"                  => " (No contabilzada)",
    "ERROR_SINTAXIS_CORREO"             => "La sintáxis del correo electrónico es incorrecta",
    "USUARIO_SIN_PRIVILEGIOS"           => "Usuario no tiene priviliegios de almacenes para esta operación",
    "NO_EXISTEN_SUCURSALES"             => "No existen almacenes creados, debe crear por lo menos uno",
    "ORDEN_CONSULTA"                    => ". Orden listados: ",
    "CREAR_EMPRESAS"                    => "No existen empresas, debe crear por lo menos una",
    "CREAR_SUCURSALES"                  => "No existen almacenes, debe crear por lo menos uno",
    "CREAR_TIPOS_COMPROBANTES"          => "No existen tipos de comprobantes, debe crear por lo menos uno",
    "CREAR_FORMATOS_DIAN"               => "No existen formatos DIAN, debe crear por lo menos uno",
    "CREAR_TASAS"                       => "No existen tasas, debe crear por lo menos una",
    "CREAR_PROVEEDORES"                 => "No existen proveedores, debe crear por lo meno uno",
    "CREAR_MARCAS"                      => "No existen marcas, debe crear por lo meno una",
    "ERROR_USUARIO_INGRESO"             => "Ya hay otro usuario en uso, verifique otras ventanas o pestañas de su navegador",
    "ARCHIVO_GENERADO"                  => "Archivo generado con exito",
    "PESTANA_GENERAL"                   => "General",
    "FECHA"                             => "Fecha",
    "ERROR_GENERAR_ARCHIVO"             => "No hay datos para generar el archivo",
    "NO_EXISTEN_DATOS"                  => "No existen datos en las siguientes tablas:\n",
    "CREAR_SUCURSAL"                    => "\n-Almacenes",
    "CREAR_TIPO_DOCUMENTO"              => "\n-Tipos de documentos",
    "CREAR_PLANILLAS"                   => "\n-Planillas",
    "CREAR_DATOS"                       => "\n\nDebe crear por o menos un registro para cada tabla.",
    "ARCHIVO_PLANO"                     => "Archivo plano para excel",
    "ARCHIVO_PDF"                       => "Pdf",
    "TIPO_LISTADO"                      => "Tipo de listado",
    "AYUDA_TIPO_LISTADO"                => "Seleccione el tipo de listado a generar",
    "SELECCIONAR_TODOS"                 => "Seleccionar todo",
    "VERIFICAR_DATOS"                   => "Verifique los siguientes datos:\n\n",
    "ERROR_SUCURSALES"                  => "No existen almacenes en la empresa\n",
    "ERROR_PLANILLAS"                   => "No existen tipos de planillas\n",
    "ERROR_FECHAS_PLANILLAS"            => "No existen fechas para generación de planillas\n",
    "ERROR_EMPLEADOS"                   => "No existen empleados en la empresa\n",
    "ERROR_SUCURSAL_VACIA"              => "Seleccione por lo menos un almacen",
    "ERROR_CODIGO_PLANILLA"             => "Seleccione una planilla",
    "ERROR_SALARIO_MINIMO"              => "No existen datos en la tabla de salario minimo\n",
    "ERROR_AUXILIO_TRANSPORTE"          => "No existen datos en la tabla de auxilio de trasnporte\n",
    "ERROR_FECHA_SALARIO_MINIMO"        => "No existe un registro de salario minimo para esta fecha de pago\n",
    "ERROR_FECHA_AUXILIO_TRANSPORTE"    => "No existe un registro de auxilio de transporte para esta fecha de pago\n",
    "IMPRIMIR"                          => "Imprimir",
    "ELABORADO_POR"                     => "Elaborado por",
    "ENERO"                             => "Enero",
    "FEBRERO"                           => "Febrero",
    "MARZO"                             => "Marzo",
    "ABRIL"                             => "Abril",
    "MAYO"                              => "Mayo",
    "JUNIO"                             => "Junio",
    "JULIO"                             => "Julio",
    "AGOSTO"                            => "Agosto",
    "SEPTIEMBRE"                        => "Septiembre",
    "OCTUBRE"                           => "Octubre",
    "NOVIEMBRE"                         => "Noviembre",
    "DICIEMBRE"                         => "Diciembre"

);
?>
