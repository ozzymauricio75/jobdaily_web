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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$textos = array(
    "GESTCORR"                          => "Correspondencia",
    "ADICCORR"                          => "Adicionar Correspondencia",
    "CONSCORR"                          => "Consultar Correspondencia",
    "MODICORR"                          => "Modificar Correspondencia",
    "ANULCORR"                          => "Anular Correspondencia",
    "ELIMCORR"                          => "Eliminar Correspondencia",
    "CODIGO"                            => "C�digo",
    "ESTADO"                            => "Estado orden de compra",
    "ESTADO_0"                          => "Recepcionado",
    "ESTADO_1"                          => "Entregado",
    "ESTADO_2"                          => "Anulado",
    "ESTADO_3"                          => "Cumplida",
    "PROYECTO"                          => "Proyecto",
    "NIT_PROVEEDOR"                     => "Nit proveedor",
    "RAZON_SOCIAL"                      => "Proveedor",
    "FECHA_RECEPCION"                   => "Fecha recepci�n",
    "FECHA_VENCIMIENTO"                 => "Fecha vencimiento",
    "FECHA_ENVIO"                       => "Fecha envio",
    "OBSERVACIONES"                     => "Observaciones",
    "VALOR_DOCUMENTO"                   => "Valor del documento",
    "VALOR"                             => "Valor",
    "RAZON_SOCIAL_PROVEEDOR"            => "Raz�n social proveedor",
    "TIPO_DOCUMENTO"                    => "Tipo de documento",
    "DOCUMENTO_SOPORTE"                 => "N�mero Factura o Remisi�n",
    "VALOR_PROYECTO"                    => "Valor proyecto",
    "EMPRESA"                           => "Empresa",
    "CONSORCIO"                         => "Consorcio o Uni�n temporal",
    "TIPO"                              => "Tipo",
    "TEXTO_CONSORCIO"                   => "Consorcio",
    "TEXTO_UNION_TEMPORAL"              => "Uni�n temporal",
    "NOMBRE"                            => "Nombre",
    "NOMBRE_CORTO"                      => "Nombre corto",
    "MUNICIPIO"                         => "Municipio",
    "DIRECCION_RESIDENCIA"              => "Direccion residencia",
    "DIRECCION_PROYECTO"                => "Direccion proyecto",
    "TELEFONO_1"                        => "Telefono 1",
    "TELEFONO_2"                        => "Telefono 2",
    "CELULAR"                           => "Celular",
    "FECHA_CIERRE"                      => "Fecha cierre",
    "EMPRESA_CONSOLIDA"                 => "Empresa consolida",
    "ALMACEN_CONSOLIDA"                 => "Consorcio consolida",
    "TIPO_EMPRESA"                      => "Tipo empresa",
    "TIPO_NEGOCIO"                      => "Tipo negocio",
    "TIPO"                              => "Tipo",
    "UNION_TEMPORAL"                    => "Union temporal",
    "PRINCIPAL"                         => "Principal",
    "ORDEN"                             => "Orden",
    "MANEJA_KARDEX"                     => "Maneja kardex",
    "TERCERO"                           => "Tercero",
    "NOMBRE_COMPLETO"                   => "Tercero",
    "ESTADO_ACTIVA"                     => "Abierto",
    "ESTADO_INACTIVA"                   => "Cerrado",
    "EMPRESA_DISTRIBUIDORA_MAYORISTA"   => "Distribuidora mayorista",
    "EMPRESA_VENTAS_PUBLICO"            => "Ventas publico",
    "EMPRESA_AMBAS"                     => "Ambas",
    "EMPRESA_SOPORTE"                   => "Empresa soporte",
    "VALOR_PROYECTO"                    => "Valor proyecto",
    "INDICADOR_NO"                      => "No",
    "INDICADOR_SI"                      => "Si",
    "ITEM_ANULADO"                      => "El documento ha sido anulado.",
    "ITEM_ELIMINADO"                    => "El documento ha sido eliminado.",
    "AYUDA_PROYECTO"                    => "Seleccione un proyecto",
    "AYUDA_OBSERVACIONES"               => "Digite las observaciones",
    "AYUDA_FECHA_RECEPCION"             => "Seleccione la Fecha recepci�n",
    "AYUDA_FECHA_VENCIMIENTO"           => "Seleccione la Fecha vencimiento",
    "AYUDA_FECHA_ENVIO"                 => "Seleccione la Fecha envio",
    "AYUDA_NIT_PROVEEDOR"               => "Digite Nit proveedor",
    "AYUDA_VALOR_DOCUMENTO"             => "Digite el valor del documento",
    "AYUDA_DOCUMENTO_SOPORTE"           => "Digite el numero del documento que entrega el proveedor",
    "AYUDA_TIPO_DOCUMENTO"              => "Seleccione el tipo de documento",
    "RAZON_SOCIAL_PROVEEDOR"            => "Raz�n social proveedor",
    "AYUDA_VALOR_PROYECTO"              => "Digite el valor del proyecto",
    "AYUDA_DIRECCION"                   => "Direccion donde se encuentra el consorcio",
    "AYUDA_CODIGO"                      => "Codigo del consorcio",
    "AYUDA_ACTIVO"                      => "Estado actual del consorcio activo-inactivo",
    "AYUDA_EMPRESAS"                    => "Seleccione la empresa",
    "AYUDA_NOMBRE"                      => "Nombre del proyecto",
    "AYUDA_NOMBRE_CORTO"                => "Nombre corto del consorcio",
    "AYUDA_MUNICIPIOS"                  => "Municipio donde se encuentra el consorcio",
    "AYUDA_TELEFONO_1"                  => "Telefono principal",
    "AYUDA_TELEFONO_2"                  => "Telefono secundario",
    "AYUDA_CELULAR"                     => "Numero del celular",
    "AYUDA_FECHA_CIERRE"                => "Fecha de cierre del consorcio",
    "AYUDA_EMPRESA_CONSOLIDA"           => "Empresa en la cual se consolida el consorcio",
    "AYUDA_ALMACEN_CONSOLIDA"           => "Consorcio en el cual se consolida",
    "AYUDA_CONSORCIO"                   => "Consorcio o uni�n temporal que desarrolla el proyecto",
    "AYUDA_TIPO_EMPRESA"                => "Tipo de empresa al cual pertenece el consorcio",
    "AYUDA_TIPO_NEGOCIO"                => "Tipo de negocio al cual pertenece el consorcio",
    "AYUDA_MANEJA_KARDEX"               => "Almac�n maneja kardex de inventarios",
    "AYUDA_ORDEN"                       => "Orden para los listados",
    "PESTANA_GENERAL"                   => "General",
    "PESTANA_CONTABLE"                  => "Contabilidad",
    "PESTANA_UBICACION"                 => "Ubicaci�n",
    "ERROR_ANULAR_ITEM"                 => "No se pudo anular el documento",
    "ERROR_ELIMINAR_ITEM"               => "No se pudo eliminar el documento",
    "ERROR_ORDEN_ESTADO"                => "La operaci�n no puede realizar debido a que el documento esta entregado o anulado.",
    "ERROR_EXISTE_CODIGO"               => "Ya existe un almac�n con ese c�digo",
    "ERROR_EXISTE_NOMBRE"               => "Ya existe un almac�n con ese nombre",
    "ERROR_EXISTE_NOMBRE_CORTO"         => "Ya existe un almac�n con ese nombre corto",
    "ERROR_EXISTE_DIRECCION_RESIDENCIA" => "Ya existe un almac�n con esa direcci�n",
    "ERROR_EXISTE_TELEFONO_1"           => "Ya existe un almac�n con ese numero telef�nico",
    "ERROR_FORMATO_CODIGO"              => "El codigo debe contener solo numeros",
    "REALIZA_ORDEN_COMPRA"              => "Autorizado para realizar ordenes de compra",
    "MANEJA_INVENTARIOS_MERCANCIA"      => "Maneja inventarios de mercanc�a",
    "CARTERA_CLIENTES_MAYORISTAS"       => "Maneja cartera de clientes mayoristas",
    "CARTERA_CLIENTES_DETALLISTAS"      => "Maneja cartera de clientes detallistas",
    "CUENTAS_PAGAR_PROVEEDORES"         => "Manejo de cuentas por pagar a proveedores",
    "NOMINA"                            => "Manejo de n�mina",
    "CONTABILIDAD"                      => "Manejo contable",
    "ORDEN"                             => "Orden",
    "PROYECTO_VACIO"                    => "Debe ingresar el proyecto asociado al documento",
    "PROVEEDOR_VACIO"                   => "Debe ingresar uin proveedor",
    "TIPO_DOCUMENTO_VACIO"              => "Debe ingresar el tipo del documento",
    "DOCUMENTO_SOPORTE_VACIO"           => "Debe ingresar el documento soporte del proveedor",
    "VALOR_VACIO"                       => "Debe ingresar el valor del documento",
    "FECHA_RECEPCION_VACIO"             => "Debe ingresar la fecha de recepcion",
    "FECHA_VENCIMIENTO_VACIO"           => "Debe ingresar la fecha de vencimiento",
    "FECHA_ENVIO_VACIO"                 => "Debe ingresar la fecha de envio",
    "CODIGO_SUCURSAL_VACIO"             => "Debe ingresar el c�digo del consorcio que consolida",
    "CODIGO_EMPRESA_VACIO"              => "Debe ingresar el codigo de la empresa que consolida",
    "CREAR_PROYECTOS"                   => "No existen proyectos, debe crear por lo menos uno."
);
/*$textos = array(
    "GESTSUCU"                          => "Almacenes",
    "ADICSUCU"                          => "Adicionar almacenes",
    "CONSSUCU"                          => "Consultar almacenes",
    "MODISUCU"                          => "Modificar almacenes",
    "ELIMSUCU"                          => "Eliminar almacenes",
    "CODIGO"                            => "C�digo",
    "SUCURSAL"                          => "Almac�n",
    "EMPRESA"                           => "Empresa",
    "NOMBRE"                            => "Nombre",
    "NOMBRE_CORTO"                      => "Nombre corto",
    "MUNICIPIO"                         => "Municipio",
    "DIRECCION_RESIDENCIA"              => "Direccion residencia",
    "TELEFONO_1"                        => "Telefono 1",
    "TELEFONO_2"                        => "Telefono 2",
    "CELULAR"                           => "Celular",
    "FECHA_CIERRE"                      => "Fecha cierre",
    "ESTADO"                            => "Estado",
    "EMPRESA_CONSOLIDA"                 => "Empresa consolida",
    "ALMACEN_CONSOLIDA"                 => "Almacen consolida",
    "TIPO_EMPRESA"                      => "Tipo empresa",
    "TIPO_NEGOCIO"                      => "Tipo negocio",
    "ORDEN"                             => "Orden",
    "MANEJA_KARDEX"                     => "Maneja kardex",
    "TERCERO"                           => "Tercero",
    "NOMBRE_COMPLETO"                   => "Tercero",
    "ESTADO_ACTIVA"                     => "Activa",
    "ESTADO_INACTIVA"                   => "Inactiva",
    "EMPRESA_DISTRIBUIDORA_MAYORISTA"   => "Distribuidora mayorista",
    "EMPRESA_VENTAS_PUBLICO"            => "Ventas publico",
    "EMPRESA_AMBAS"                     => "Ambas",
    "EMPRESA_SOPORTE"                   => "Empresa soporte",
    "INDICADOR_NO"                      => "No",
    "INDICADOR_SI"                      => "Si",
    "AYUDA_DIRECCION"                   => "Direccion donde se encuentra el almac�n",
    "AYUDA_CODIGO"                      => "Codigo de la empresa",
    "AYUDA_ACTIVO"                      => "Estado actual del almacen activo-inactivo",
    "AYUDA_EMPRESAS"                    => "Empresa a la que pertenece el almac�n",
    "AYUDA_NOMBRE"                      => "Nombre del almacen",
    "AYUDA_NOMBRE_CORTO"                => "Nombre corto del almacen",
    "AYUDA_MUNICIPIOS"                  => "Municipio donde se encuentra el almac�n",
    "AYUDA_TELEFONO_1"                  => "Telefono principal",
    "AYUDA_TELEFONO_2"                  => "Telefono secundario",
    "AYUDA_CELULAR"                     => "Numero del celular",
    "AYUDA_FECHA_CIERRE"                => "Fecha de cierre del almac�n",
    "AYUDA_EMPRESA_CONSOLIDA"           => "Empresa en la cual se consolida el almac�n",
    "AYUDA_ALMACEN_CONSOLIDA"           => "Almac�n en el cual se consolida",
    "AYUDA_TIPO_EMPRESA"                => "Tipo de empresa al cual pertenece al almac�n",
    "AYUDA_TIPO_NEGOCIO"                => "Tipo de negocio al cual pertenece la empresa",
    "AYUDA_MANEJA_KARDEX"               => "Almac�n maneja kardex de inventarios",
    "AYUDA_ORDEN"                       => "Orden para los listados",
    "PESTANA_GENERAL"                   => "General",
    "PESTANA_CONTABLE"                  => "Contabilidad",
    "PESTANA_UBICACION"                 => "Ubicaci�n",
    "ERROR_EXISTE_CODIGO"               => "Ya existe un almac�n con ese c�digo",
    "ERROR_EXISTE_NOMBRE"               => "Ya existe un almac�n con ese nombre",
    "ERROR_EXISTE_NOMBRE_CORTO"         => "Ya existe un almac�n con ese nombre corto",
    "ERROR_EXISTE_DIRECCION_RESIDENCIA" => "Ya existe un almac�n con esa direcci�n",
    "ERROR_EXISTE_TELEFONO_1"           => "Ya existe un almac�n con ese numero telef�nico",
    "ERROR_FORMATO_CODIGO"              => "El codigo debe contener solo numeros",
    "REALIZA_ORDEN_COMPRA"              => "Autorizado para realizar ordenes de compra",
    "MANEJA_INVENTARIOS_MERCANCIA"      => "Maneja inventarios de mercanc�a",
    "CARTERA_CLIENTES_MAYORISTAS"       => "Maneja cartera de clientes mayoristas",
    "CARTERA_CLIENTES_DETALLISTAS"      => "Maneja cartera de clientes detallistas",
    "CUENTAS_PAGAR_PROVEEDORES"         => "Manejo de cuentas por pagar a proveedores",
    "NOMINA"                            => "Manejo de n�mina",
    "CONTABILIDAD"                      => "Manejo contable",
    "ORDEN"                             => "Orden",
    "CODIGO_VACIO"                      => "Debe ingresar el c�digo",
    "EMPRESA_VACIO"                     => "Debe seleccionar una empresa",
    "NOMBRE_VACIO"                      => "Debe ingresar el nombre para el almacen",
    "MUNICIPIO_VACIO"                   => "Debe ingresar el municipio",
    "DIRECCION_VACIO"                   => "Debe ingresar la direcci�n",
    "TELEFONO_VACIO"                    => "Debe ingresar el numero telefonico",
    "CODIGO_SUCURSAL_VACIO"             => "Debe ingresar el c�digo del almacen que consolida",
    "CODIGO_EMPRESA_VACIO"              => "Debe ingresar el codigo de la empresa que consolida",
    "CREAR_EMPRESAS"                    => "No existen empresas, debe crear por lo menos una"
);*/
?>
