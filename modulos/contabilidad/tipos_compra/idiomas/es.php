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
    "DESCRIPCION"                       => "Descripci�n",
    "MUNICIPIO"                         => "Municipio",
    "PAIS"                              => "Pais",
    "DEPARTAMENTO"                      => "Departamento",
    "AYUDA_DESCRIPCION"                 => "Detalle que describe al competidor",
    "AYUDA_MUNICIPIO"                   => "Municipio donde se encuentra ubicado el competidor",
    "ERROR_EXISTE_CODIGO"               => "Ya existe un tipo de compra con ese codigo",
    "ERROR_EXISTE_DESCRIPCION"          => "Ya existe un tipo de compra con esa descripcion",
    "PESTANA_GENERAL"                   => "General",
    "CODIGO"                            => "C�digo",
    "NOMBRE"                            => "Nombre",
    "CONCEPTO"                          => "Concepto",
    "AYUDA_CODIGO"                      => "Es el c�digo interno con el que la empresa identifica el tipo de compra",
    "PESTANA_CODIGOS_CONTABLES"         => "Cuentas",
    "CUENTAS_PAGAR"                     => "Cuentas por pagar",
    "AYUDA_CUENTAS_PAGAR"               => "Digite el c�digo contable o la descripci�n de la cuenta por pagar",
    "RETEFUENTE"                        => "Retenci�n en la fuente",
    "AYUDA_RETEFUENTE"                  => "Digite el c�digo contable o la descripci�n de la retenci�n en la fuente",
    "TASA_RETEFUENTE"                   => "Tasa de la retenci�n en la fuente",
    "AYUDA_TASA_RETEFUENTE"             => "Seleccione la tasa de retenci�n en la fuente",
    "RETEIVA"                           => "ReteIVA",
    "AYUDA_RETEIVA"                     => "Digite el c�digo contable o la descripci�n para el ReteIVA",
    "SEGURO"                            => "Seguro",
    "FLETES"                            => "Fletes",
    "IVA_SEGURO"                        => "Iva del seguro",
    "IVA_FLETES"                        => "Iva del flete",
    "AYUDA_SEGURO"                      => "Digite el c�digo contable o la descripci�n del seguro",
    "AYUDA_FLETES"                      => "Digite el c�digo contable o la descripci�n del flete",
    "AYUDA_IVA_SEGURO"                  => "Digite el c�digo contable o la descripci�n para el iva del seguro",
    "AYUDA_IVA_FLETES"                  => "Digite el c�digo contable o la descripci�n para el iva del flete",
    "CONCEPTO"                          => "Concepto",
    "AYUDA_CONCEPTO"                    => "Concepto de la compra",
    "TIPO_TRANSACCION"                  => "Tipo de transacci�n",
    "AYUDA_TIPO_TRANSACCION"            => "Tipo de transacci�n que se realiza",
    "PESTANA_NOTAS_DEBITO"              => "Notas debito",
    "PESTANA_NOTAS_CREDITO"             => "Notas cr�dito",
    "CUENTA_COMPRA"                     => "Compra",
    "AYUDA_CUENTA_COMPRA"               => "Es el codigo de la cuenta a la que se relacionan la compras",
    "IVA"                               => "IVA",
    "AYUDA_IVA"                         => "Es la cuenta a la que se relaciona el valor del IVA",
    "RETEICA"                           => "ICA",
    "AYUDA_RETEICA"                     => "Es la cuenta a la que se asigna el valor del reteICA",
    "VALOR_BASE"                        => "Valor m�nimo",
    "AYUDA_VALOR_BASE"                  => "Es el valor m�nimo de diferencia sobre el que se genera la nota",
    "TIPO_DOCUMENTO"                    => "Tipo de documento",
    "AYUDA_TIPO_DOCUMENTO"              => "Seleccione el tipo de documento",
    "CODIGO_VACIO"                      => "Debe ingresar el c�digo",
    "DESCRIPCION_VACIO"                 => "Debe ingresar la decripci�n",
    "CONCEPTO_VACIO"                    => "Debe ingresar el concepto",
    "TASA_RETEFUENTE_VACIO"             => "Debe seleccionar la tasa de retenci�n en la fuente",
    "PLAN_CXP_VACIO"                    => "Debe ingresar el c�digo contable de la cuenta por pagar",
    "PLAN_RETEFUENTE_VACIO"             => "Debe ingresar el c�digo contable de la retenci�n en la fuente",
    "PLAN_IVA_VACIO"                    => "Debe ingresar el c�digo contable del reteIVA",
    "PLAN_SEGURO_VACIO"                 => "Debe ingresar el c�digo contable del seguro",
    "PLAN_IVA_SEGURO_VACIO"             => "Debe ingresar el c�digo contable del iva del seguro",
    "PLAN_FLETE_VACIO"                  => "Debe ingresar el c�digo contable del flete",
    "PLAN_IVA_FLETE_VACIO"              => "Debe ingresar el c�digo contable del iva del flete",
    "PESTANA_PROVISION"                 => "Provisi�n",
    "CUENTA_INVENTARIO"                 => "Cuenta inventarios",
    "AYUDA_CUENTA_INVENTARIO"           => "Seleccione el c�digo contable o la descripci�n para la cuenta de inventarios",
    "CUENTA_PUENTE"                     => "Cuenta puente",
    "AYUDA_CUENTA_PUENTE"               => "Seleccione el c�digo contable o la descripci�n para la cuenta puente",
    "PLAN_INVENTARIO_PROVISION_VACIO"   => "Debe ingresar el c�digo contable de la provision de inventario",
    "PLAN_PUENTE_PROVISION_VACIO"       => "Debe ingresar el c�digo contable puente de la provsion",
    "PLAN_RETEFUENTE_PROVISION_VACIO"   => "Debe ingresar el c�digo contable de la provsion de la retefuente",
    "ASIGNAR_TASA"                      => "El codigo contable no tiene tasas asignadas: ",
    "ERROR_TIPOS_DOCUMENTOS"            => "No existen tipos de documentos, debe crear por lo menos un tipo de documento",
    "COMPRAS_DIRECTAS"                  => "Compras directas",
    "COMPRAS_OBSEQUIO"                  => "Compras obsequio",
    "COMPRAS_FILIALES"                  => "Compras filiales",
    "COMPRAS_CANJE"                     => "Compras canje",
    "COMPRAS_CONSIGNACION"              => "Compras en consignacion",
    "IVA_DIFERENCIA"                    => "IVA diferencia",
    "IVA_DIFERENCIA_VACIO"              => "Debe ingresar el c�digo contable de IVA diferencia",
    "AYUDA_IVA_DIFERENCIA"              => "Digite el c�digo contable o la descripci�n de IVA diferencia",
    "VALOR_DEBITO_VACIO"                => "El valor minimo de la nota debito no debe quedar vacio ni en cero (0)",
    "VALOR_CREDITO_VACIO"               => "El valor minimo de la nota credito no debe quedar vacio ni en cero (0)",
    "CONCEPTO_1"                        => "Compras directas",
    "CONCEPTO_2"                        => "Compras obsequio",
    "CONCEPTO_3"                        => "Compras filiales",
    "CONCEPTO_4"                        => "Compras canje",
    "CONCEPTO_5"                        => "Compras en consignacion"
);
?>
