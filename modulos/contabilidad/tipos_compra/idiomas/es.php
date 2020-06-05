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

$textos = array(
    "GESTTCOM"                          => "Tipos de compras",
    "ADICTCOM"                          => "Adicionar tipo de compra",
    "CONSTCOM"                          => "Consultar tipo de compra",
    "MODITCOM"                          => "Modificar tipo de compra",
    "ELIMTCOM"                          => "Eliminar tipo de compra",
    "DESCRIPCION"                       => "Descripción",
    "MUNICIPIO"                         => "Municipio",
    "PAIS"                              => "Pais",
    "DEPARTAMENTO"                      => "Departamento",
    "AYUDA_DESCRIPCION"                 => "Detalle que describe al competidor",
    "AYUDA_MUNICIPIO"                   => "Municipio donde se encuentra ubicado el competidor",
    "ERROR_EXISTE_CODIGO"               => "Ya existe un tipo de compra con ese codigo",
    "ERROR_EXISTE_DESCRIPCION"          => "Ya existe un tipo de compra con esa descripcion",
    "PESTANA_GENERAL"                   => "General",
    "CODIGO"                            => "Código",
    "NOMBRE"                            => "Nombre",
    "CONCEPTO"                          => "Concepto",
    "AYUDA_CODIGO"                      => "Es el código interno con el que la empresa identifica el tipo de compra",
    "PESTANA_CODIGOS_CONTABLES"         => "Cuentas",
    "CUENTAS_PAGAR"                     => "Cuentas por pagar",
    "AYUDA_CUENTAS_PAGAR"               => "Digite el código contable o la descripción de la cuenta por pagar",
    "RETEFUENTE"                        => "Retención en la fuente",
    "AYUDA_RETEFUENTE"                  => "Digite el código contable o la descripción de la retención en la fuente",
    "TASA_RETEFUENTE"                   => "Tasa de la retención en la fuente",
    "AYUDA_TASA_RETEFUENTE"             => "Seleccione la tasa de retención en la fuente",
    "RETEIVA"                           => "ReteIVA",
    "AYUDA_RETEIVA"                     => "Digite el código contable o la descripción para el ReteIVA",
    "SEGURO"                            => "Seguro",
    "FLETES"                            => "Fletes",
    "IVA_SEGURO"                        => "Iva del seguro",
    "IVA_FLETES"                        => "Iva del flete",
    "AYUDA_SEGURO"                      => "Digite el código contable o la descripción del seguro",
    "AYUDA_FLETES"                      => "Digite el código contable o la descripción del flete",
    "AYUDA_IVA_SEGURO"                  => "Digite el código contable o la descripción para el iva del seguro",
    "AYUDA_IVA_FLETES"                  => "Digite el código contable o la descripción para el iva del flete",
    "CONCEPTO"                          => "Concepto",
    "AYUDA_CONCEPTO"                    => "Concepto de la compra",
    "TIPO_TRANSACCION"                  => "Tipo de transacción",
    "AYUDA_TIPO_TRANSACCION"            => "Tipo de transacción que se realiza",
    "PESTANA_NOTAS_DEBITO"              => "Notas debito",
    "PESTANA_NOTAS_CREDITO"             => "Notas crédito",
    "CUENTA_COMPRA"                     => "Compra",
    "AYUDA_CUENTA_COMPRA"               => "Es el codigo de la cuenta a la que se relacionan la compras",
    "IVA"                               => "IVA",
    "AYUDA_IVA"                         => "Es la cuenta a la que se relaciona el valor del IVA",
    "RETEICA"                           => "ICA",
    "AYUDA_RETEICA"                     => "Es la cuenta a la que se asigna el valor del reteICA",
    "VALOR_BASE"                        => "Valor mínimo",
    "AYUDA_VALOR_BASE"                  => "Es el valor mínimo de diferencia sobre el que se genera la nota",
    "TIPO_DOCUMENTO"                    => "Tipo de documento",
    "AYUDA_TIPO_DOCUMENTO"              => "Seleccione el tipo de documento",
    "CODIGO_VACIO"                      => "Debe ingresar el código",
    "DESCRIPCION_VACIO"                 => "Debe ingresar la decripción",
    "CONCEPTO_VACIO"                    => "Debe ingresar el concepto",
    "TASA_RETEFUENTE_VACIO"             => "Debe seleccionar la tasa de retención en la fuente",
    "PLAN_CXP_VACIO"                    => "Debe ingresar el código contable de la cuenta por pagar",
    "PLAN_RETEFUENTE_VACIO"             => "Debe ingresar el código contable de la retención en la fuente",
    "PLAN_IVA_VACIO"                    => "Debe ingresar el código contable del reteIVA",
    "PLAN_SEGURO_VACIO"                 => "Debe ingresar el código contable del seguro",
    "PLAN_IVA_SEGURO_VACIO"             => "Debe ingresar el código contable del iva del seguro",
    "PLAN_FLETE_VACIO"                  => "Debe ingresar el código contable del flete",
    "PLAN_IVA_FLETE_VACIO"              => "Debe ingresar el código contable del iva del flete",
    "PESTANA_PROVISION"                 => "Provisión",
    "CUENTA_INVENTARIO"                 => "Cuenta inventarios",
    "AYUDA_CUENTA_INVENTARIO"           => "Seleccione el código contable o la descripción para la cuenta de inventarios",
    "CUENTA_PUENTE"                     => "Cuenta puente",
    "AYUDA_CUENTA_PUENTE"               => "Seleccione el código contable o la descripción para la cuenta puente",
    "PLAN_INVENTARIO_PROVISION_VACIO"   => "Debe ingresar el código contable de la provision de inventario",
    "PLAN_PUENTE_PROVISION_VACIO"       => "Debe ingresar el código contable puente de la provsion",
    "PLAN_RETEFUENTE_PROVISION_VACIO"   => "Debe ingresar el código contable de la provsion de la retefuente",
    "ASIGNAR_TASA"                      => "El codigo contable no tiene tasas asignadas: ",
    "ERROR_TIPOS_DOCUMENTOS"            => "No existen tipos de documentos, debe crear por lo menos un tipo de documento",
    "COMPRAS_DIRECTAS"                  => "Compras directas",
    "COMPRAS_OBSEQUIO"                  => "Compras obsequio",
    "COMPRAS_FILIALES"                  => "Compras filiales",
    "COMPRAS_CANJE"                     => "Compras canje",
    "COMPRAS_CONSIGNACION"              => "Compras en consignacion",
    "IVA_DIFERENCIA"                    => "IVA diferencia",
    "IVA_DIFERENCIA_VACIO"              => "Debe ingresar el código contable de IVA diferencia",
    "AYUDA_IVA_DIFERENCIA"              => "Digite el código contable o la descripción de IVA diferencia",
    "VALOR_DEBITO_VACIO"                => "El valor minimo de la nota debito no debe quedar vacio ni en cero (0)",
    "VALOR_CREDITO_VACIO"               => "El valor minimo de la nota credito no debe quedar vacio ni en cero (0)",
    "CONCEPTO_1"                        => "Compras directas",
    "CONCEPTO_2"                        => "Compras obsequio",
    "CONCEPTO_3"                        => "Compras filiales",
    "CONCEPTO_4"                        => "Compras canje",
    "CONCEPTO_5"                        => "Compras en consignacion"
);
?>
