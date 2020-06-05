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
* de APTITUD PARA UN PROP�ITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$textos = array(
    "PREFEMPR"                                      => "Empresas",
    "MODIPREM"                                      => "Modificar Preferencias",
    "CODIGO"                                        => "C�digo",
    "RAZON_SOCIAL"                                  => "Empresa",
    "PAIS"                                          => "Pa�s",
    "TIPO"                                          => "Tipo",
    "VARIABLE"                                      => "Variable",
    "VALOR"                                         => "Valor",
    "USUARIO"                                       => "Usuario",
    "SUCURSAL"                                      => "Almac�n",
    "PERFIL"                                        => "Perfil",
    "PESTANA_GENERAL"                               => "Informaci�n general",
    "PESTANA_COMPONENTES"                           => "Componentes",
    "AYUDA_USUARIO"                                 => "Nombre de usuario al que se le asignar�n los Preferencias",
    "AYUDA_SUCURSAL"                                => "Sucursal en la que se asignar�n los Preferencias",
    "AYUDA_PERFIL"                                  => "Perfil a partir del cual se asignar�n los Preferencias iniciales",
    "ERROR_EXISTE_PERFIL"                           => "Ya existe un perfil para ese usuario en ese almac�n",
    "ERROR_COMPONENTES"                             => "Seleccione por lo menos un componente",
    "PESTANA_ARTICULOS"                             => "Articulos",
    "TIPO_ARTICULO"                                 => "Tipo de �rticulo",
    "PRODUCTO_TERMINADO"                            => "Producto terminado",
    "OBSEQUIO"                                      => "Obsequio",
    "ACTIVO_FIJO"                                   => "Activo fijo",
    "MATERIA_PRIMA"                                 => "Materia prima",
    "UNIDAD_COMPRA"                                 => "Unidad de compra",
    "UNIDAD_VENTA"                                  => "Unidad de venta",
    "UNIDAD_PRESENTACION"                           => "Unidad de presentaci�n",
    "IMPUESTO_COMPRA"                               => "Tasa IVA Compra",
    "IMPUESTO_VENTA"                                => "Tasa IVA Venta",
    "PESTANA_ORDENES_COMPRAS"                       => "Ordenes de compra",
    "SUCURSAL"                                      => "Sucursales",
    "SUCURSAL_ORDEN_COMPRA"                         => "Sucursal ordenes de compra",
    "PESTANA_ENTRADAS_MERCANCIA"                    => "Entradas de Mercancia",
    "TIPO_DOCUMENTO"                                => "Tipo de documento",
    "ENTRADA_TRASLADO"                              => "Entrada por traslado",
    "SALIDA_TRASLADO"                               => "Salida por traslado",
    "TIPO_DOCUMENTO_DEVOLUCIONES_COMPRAS"           => "Tipo de documento para devoluciones por compras",
    "TIPO_DOCUMENTO_CONTABILIZACION_DEVOLUCIONES"   => "Tipo de documento para contabilizaci�n de devoluciones",
    "TIPO_DOCUMENTO_FACTURA"                        => "Tipo documento factura venta",
    "SUCURSAL_DETALLISTA_ORDEN_COMPRA"              => "Sucursal detallista para listas de precios",
    "PESTANA_LISTAS_PRECIOS"                        => "Listas de precios",
    "INCENTIVO_CREDITO_DETALLISTA"                  => "Incentivo cr�dito detallista",
    "INCENTIVO_JEFES"                               => "Incentivo para administradores",
    "AYUDA_INCENTIVO_CREDITO_DETALLISTA"            => "Porcentaje del incentivo para las ventas a cr�dito de detallistas",
    "AYUDA_FINCENTIVO_JEFES"                        => "Porcentaje del incentivo para los adminstradores del almacen",
    "AYUDA_ENTRADA_TRASLADO"                        => "Seleccione el tipo de documento para manejar las entradas por traslados",
    "AYUDA_SALIDA_TRASLADO"                         => "Seleccione el tipo de documento para manejar las salidas por traslados",
    "AYUDA_TIPO_DOCUMENTO_FACTURA"                  => "Seleccione el tipo de documento para la facturacion de ventas a clientes mayoristas",
    "VENTAS_DESPACHOS"                              => "Ventas despachos",
    "RETENCION_ANOS_ANTERIORES"                     => "Valida la retefuente de a�os anteriores para devoluciones en compras",
    "TIPOS_TARJETAS"                                => "Tipos Tarjetas",
    "PORCENTAJE_RETEIVA"                            => "Porcentaje ReteIVA",
    "PORCENTAJE_IVA"                                => "Porcentaje IVA",
    "RECIBO_PROVISIONAL"                            => "Recibos Provisionales",
    "TIPO_DOCUMENTO_RECIBO"                         => "Tipo Documento",
    "AYUDA_TIPO_DOCUMENTO_RECIBO"                   => "Seleccione el tipo de documento para el recibo provisional",
    "PESTANA_MERCANCIA_TRANSITO"                    => "Mercancia En Transito",
    "PLAN_CONTABLE_COMPRA"                          => "C�digo contable compra",
    "AYUDA_PLAN_CONTABLE_COMPRA"                    => "Digite la descripci�n o el c�digo contable compra",
    "PLAN_CONTABLE_IVA_COMPRA"                      => "C�digo contable IVA compra",
    "AYUDA_PLAN_CONTABLE_IVA_COMPRA"                => "Digite la descripci�n o el c�digo contable IVA compra",
    "AYUDA_ENTRADA_TRASLADOS"                       => "Seleccione el tipo de documento para las entradas por traslado",
    "AYUDA_SALIDA_TRASLADOS"                        => "Seleccione el tipo de documento para las salidas por traslado",
    "BODEGA_RECEPCION_TRASLADOS"                    => "Bodega traslado",
    "AYUDA_BODEGA_RECEPCION_TRASLADOS"              => "Seleccione la la bodega para las entradas por traslado",
    "AYUDA_PORCENTAJE_RETEIVA"                      => "Ingrese el porcentaje para el reteiva",
    "AYUDA_PORCENTAJE_IVA"                          => "Ingrese el porcentaje para el IVA",
    "PESTANA_FACTURACION"                           => "Facturaci�n",
    "PESTANA_ORDENES_PUBLICIDAD"                    => "Publicidad",
    "VALOR_BASE_CUADRE_FACTURACION"                 => "Valor base de cuadre en facturaci�n",
    "AYUDA_VALOR_BASE_CUADRE_FACTURACION"           => "Valor base de cuadre en liquidaci�n de facturas de proveedores",
    "TIPO_DOCUMENTO_DEBITO"                         => "Tipo documento nota debito",
    "TIPO_DOCUMENTO_CREDITO"                        => "Tipo documento nota credito",
    "NOTAS_VARIAS"                                  => "Notas varias",
    "PESTANA_PROVEEDORES"                           => "Proveedores-1",
    "PESTANA_PROVEEDORES_2"                         => "Proveedores-2",
    "PESTANA_CLIENTES"                              => "Clientes",
    "FACTURACION_PROVEEDORES"                       => "Facturacion proveedores",
    "TIPO_DOCUMENTO_FACTURACION_PROVEEDORES"        => "Tipo documento facturaci�n proveedores",
    "TIPO_COMPROBANTE_FACTURACION_PROVEEDORES"      => "Tipo comprobante facturaci�n proveedores",
    "DEVOLUCIONES_COMPRA"                           => "Devoluciones compras",
    "TIPO_DOCUMENTO_DEVOLUCIONES_COMPRA"            => "Tipo documento devoluciones compras",
    "TIPO_COMPROBANTE_DEVOLUCIONES_COMPRA"          => "Tipo comprobante devoluciones compras",
    "PESTANA_INVENTARIOS"                           => "Inventario",
    "DOCUMENTO_PROVISION"                           => "Documento Provisi�n",
    "CRUCE_DEVOLUCIONES_COMPRA"                     => "Cruce devoluciones compra",
    "TIPO_DOCUMENTO_CRUCE_DEVOLUCION"               => "Tipo documento cruce devolucion",
    "ERROR_TABLAS"                                  => "Las siguientes tablas necesitan al menos un registro para configurar las preferencias por empresa:\n\n",
    "TABLA_VACIA_SUCURSALES"                        => "- Almacen (Administracion)",
    "TABLA_VACIA_TIPOS_DOCUMENTOS"                  => "- Tipos documentos (Contabilidad)",
    "TABLA_VACIA_TIPOS_COMPROBANTES"                => "- Tipos comprobantes (Contabilidad)",
    "TABLA_VACIA_PLAN_CONTABLE"                     => "- Plan contable - Cuentas de movimiento (Contabilidad)",

    "PESTANA_NOMINA"                                => "Nomina",
    "VALOR_CUOTA_MINIMA_PAGO"                       => "Valor cuota minima",
    "AYUDA_VALOR_CUOTA_MINIMA_PAGO"                 => "Digite el valor de la cuota minima para los prestamos a empleados",
);
?>
