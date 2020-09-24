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

$textos = array(
    "SUBMMOTE"                      => "Movimientos de tesoreria",
    "ADICMVTE"                      => "Adicionar movimientos de tesoreria",
    "CONSMVTE"                      => "Consultar movimientos de tesoreria",
    "MODIMVTE"                      => "Modificar movimientos de tesoreria",
    "ELIMMVTE"                      => "Eliminar movimientos de tesoreria",
    "ANULMVTE"                      => "Anular movimientos de tesoreria",
    "REPOMVTE"                      => "Reportes de movimientos tesoreria",
    "REPORTE"                       => "Reporte",
    "BANCOS"                        => "Bancos",
    "CUENTAS"                       => "Cuentas",
    "PROYECTO"                      => "Proyecto",
    "PROVEEDOR"                     => "Proveedor",
    "CONCEPTO"                      => "Concepto",
    "TODOS_BANCOS"                  => "Todos los bancos",
    "POR_BANCO"                     => "Por banco",
    "TODAS_CUENTAS"                 => "Todas las cuentas",
    "POR_CUENTA"                    => "Por cuenta",
    "TODOS_PROYECTOS"               => "Todos los proyectos",
    "POR_PROYECTO"                  => "Por proyecto",
    "TODOS_PROVEEDORES"             => "Todos los proveedores",
    "POR_PROVEEDOR"                 => "Por proveedor",
    "TODOS_CONCEPTOS"               => "Todos los conceptos",
    "POR_CONCEPTO"                  => "Por concepto",
    "GRUPO_TESORERIA"               => "Grupo tesoreria",
    "FECHA_DESDE"                   => "Fecha desde",
    "FECHA_HASTA"                   => "Fecha hasta",
    "CONCEPTO_TESORERIA"            => "Concepto tesoreria",
    "ESTADO"                        => "Estado",
    "ESTADO_0"                      => "Activo",
    "ESTADO_1"                      => "Anulado",
    "NOMBRE"                        => "Descripcion",
    "PROVEEDOR"                     => "Proveedor",
    "FECHA_MOVIMIENTO"              => "Fecha movimiento",
    "CODIGO"                        => "Código",
    "SALDO_FECHA"                   => "Saldo a esa fecha",
    "PROYECTO"                      => "Proyecto",
    "DEBITO"                        => "Debito",
    "CREDITO"                       => "Credito",
    "CUENTA_ORIGEN"                 => "Cuenta origen",
    "CUENTA_DESTINO"                => "Cuenta destino",
    "NUMERO_CUENTA"                 => "Numero de cuenta",
    "FECHA_INICIO"                  => "Fecha inicio",
    "FECHA_REGISTRO"                => "Fecha registro",
    "SALDO_INICIAL"                 => "Saldo inicial",
    "VALOR_MOVIMIENTO"              => "Valor movimiento",
    "DATOS_MOVIMIENTO"              => "Datos del movimiento",
    "CONCEPTO_SIN_GRUPO"            => "No existe grupo con ese concepto",
    "BANCO"                         => "Banco",
    "TERCERO"                       => "Tercero",
    "OBSERVACIONES"                 => "Observaciones",
    "CUENTA_VACIO"                  => "Número de cuenta esta vacio",
    "VALOR_VACIO"                   => "Valor del movimiento vacio",
    "FECHA_VACIO"                   => "Fecha del movimiento vacio",
    "ITEM_ANULADO"                  => "Item anulado",
    "ERROR_EXISTE_SALDO"            => "Error, ya existe un saldo inicial en esa cuenta, Verifique!!",
    "ERROR_ANULAR_ITEM"             => "Error, no se pudo anular el registro",
    "AYUDA_GRUPO_TESORERIA"         => "Seleccione el grupo de tesoreria",
    "AYUDA_CONCEPTO_TESORERIA"      => "Seleccione el concepto de tesoreria",
    "AYUDA_BANCO"                   => "Banco al que pertenece la empresa",
    "AYUDA_POR_TODOS_BANCOS"        => "Seleccione para todos los bancos",
    "AYUDA_POR_BANCO"               => "Seleccione para escoger un solo banco",
    "AYUDA_POR_TODAS_CUENTAS"       => "Seleccione para todas las cuentas",
    "AYUDA_POR_CUENTAS"             => "Seleccione para escoger una sola cuenta",
    "AYUDA_POR_TODOS_PROYECTOS"     => "Seleccione para todos los proyectos",
    "AYUDA_POR_PROYECTOS"           => "Seleccione para escoger un solo proyecto",
    "AYUDA_POR_TODOS_PROVEEDORES"   => "Seleccione para todos los proveedores",
    "AYUDA_POR_PROVEEDORES"         => "Seleccione para escoger un solo proveedor",
    "AYUDA_TERCERO"                 => "Tercero al cual pertenece la cuenta",
    "AYUDA_NUMERO"                  => "Numero de la cuenta bancaria",
    "AYUDA_NOMBRE"                  => "Nombre del grupo",
    "AYUDA_PROVEEDOR"               => "Nombre o Nit del proveedor",
    "AYUDA_PROYECTO"                => "Proyecto del cual pertenece el movimiento de tesoreria",
    "AYUDA_FECHA_MOVIMIENTO"        => "Fecha en la cual se realiza el movimiento",
    "AYUDA_NUMERO_CUENTA"           => "Numero de la cuenta bancaria",
    "CREAR_GRUPOS_TESORERIA"        => "Error, debe existir grupos de tesoreria creados",
    "CREAR_CONCEPTOS_TESORERIA"     => "Error, debe existir conceptos de tesoreria creados",
    "PROVEEDOR_SIN_CUENTA"          => "Error, el proveedor no tiene una cuenta de banco creada",
    "ERROR_EXISTE_NOMBRE"           => "Error, ya existe un nombre para el concepto de tesoreria",
    "ERROR_ADICIONAR_ITEM"          => "Error, No se pudo adicionar el item",
    "ERROR_SALDO_INICIAL_CUENTA"    => "Error, No existe saldo inicial en esa cuenta, Verifique!!"
);
?>
