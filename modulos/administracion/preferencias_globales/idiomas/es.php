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
    "PREFGLOB"                                  => "Globales",
    "PESTANA_NOMINA"                            => "Recursos humanos",
    "PAGOS"                                     => "Pagos",
    "NOMINA_POR_PAGAR"                          => "Codigo contable de la nomina por pagar",
    "AYUDA_NOMINA_POR_PAGAR"                    => "Seleccione el codigo contable para la nomina por pagar",
    "INCAPACIDADES"                             => "Incapacidades",
    "TRANSACCION_TIEMPO_TRES_DIAS"              => "Incapacidad tres dias",
    "TRANSACCION_TIEMPO_GENERAL"                => "Incapacidad general",
    "ENTIDADES"                                 => "Tasas de liquidación",
    "TASA_SALUD"                                => "Tasa de Salud",
    "TASA_PENSION"                              => "Tasa de Pension",
    "TASA_FONDO_SOLIDARIDAD"                    => "Tasa fondo de solidaridad pensión",
    "AYUDA_TASA_SALUD"                          => "Digite el porcentaje para la tasa de salud",
    "AYUDA_TASA_PENSION"                        => "Digite el porcentaje para la tasa de pension",
    "HORAS_LABORALES"                           => "Transacciones horas laborales",
    "HORAS_EXTRAS"                              => "Horas extras",
    "HORAS_EXTRAS_NOCTURNAS"                    => "Horas extras nocturnas",
    "HORAS_EXTRAS_DOMINGOS_FESTIVOS"            => "Horas extras domingos y festivos",
    "HORAS_EXTRAS_DOMINGOS_FESTIVOS_NOCHE"      => "Horas extras domingos y festivos nocturnas",
    "HORAS_NORMALES"                            => "Horas normales",
    "HORAS_RECARGO_NOCTURNA"                    => "Horas recargo nocturna",
    "HORAS_DOMINICALES"                         => "Horas domingos y festivos",
    "HORAS_RECARGO_NOCHE_DOMINICAL"             => "Horas recargo domingos y festivos noche ",
    "AYUDA_HORAS_NORMALES"                      => "Seleccione la transaccion de horas normales",
    "AYUDA_HORAS_RECARGO_NOCTURNA"              => "Seleccione la transaccion de horas recargo nocturnas",
    "AYUDA_HORAS_DOMINICALES"                   => "Seleccione la transaccion de horas domingo y festivo",
    "AYUDA_EXTRAS_NORMALES"                     => "Seleccione la transaccion de horas extras normales",
    "AYUDA_EXTRAS_NOCTURNAS"                    => "Seleccione la transaccion de horas extras nocturnas",
    "AYUDA_EXTRAS_DOMINICALES"                  => "Seleccione la transaccion de horas extras dominicales",
    "AYUDA_EXTRAS_DOMINICALES_NOCTURNAS"        => "Seleccione la transaccion de horas extras dominicales y festivos nocturnas",
    "AYUDA_HORAS_RECARGO_NOCHE_DOMINICAL"       => "Seleccione la transaccion de horas recargo nocturnas domingo y festivo",
    "ASPIRANTES"                                => "Aspirantes",
    "TIPOS_DOCUMENTO_IDENTIDAD"                 => "Tipo de documento de identidad",
    "AYUDA_TIPOS_DOCUMENTO_IDENTIDAD"           => "Seleccione el tipo de documento de identidad por defecto pa los aspirantes",
    "PESTANA_INVENTARIOS"                       => "Inventarios",
    "TIPO_ARTICULO"                             => "Tipo articulo",
    "UNIDAD_COMPRA"                             => "Unidad de compra",
    "UNIDAD_VENTA"                              => "Unidad de venta",
    "UNIDAD_PRESENTACION"                       => "Unidad de presentación",
    "PRODUCTO_TERMINADO"                        => "Producto terminado",
    "OBSEQUIO"                                  => "Obsequio",
    "ACTIVO_FIJO"                               => "Activo fijo",
    "MATERIA_PRIMA"                             => "Materia prima",
    "IMPUESTO_COMPRA"                           => "Tasa impuesto compra",
    "IMPUESTO_VENTA"                            => "Tasa impuesto venta",
    "PESTANA_NOMINA_2"                          => "Recursos humanos 2",
    "NOMINA_PAGAR_SALUD"                        => "Nomina por pagar salud",
    "CUENTA_PAGAR_SALUD"                        => "Cuenta por pagar a entidad de salud",
    "CANCELACION_NOMINA_PAGAR_SALUD"            => "Cancelación nomina por pagar salud",
    "NOMINA_PAGAR_PENSION"                      => "Nomina por pagar pensión",
    "CUENTA_PAGAR_PENSION"                      => "Cuenta por pagar a entidad de pension",
    "CANCELACION_NOMINA_PAGAR_PENSION"          => "Cancelación nomina por pagar pensión",
    "FONDO_SOLIDARIDAD"                         => "Fondo de solidaridad pensión",
    "AYUDA_FONDO_SOLIDARIDAD"                   => "Seleccione la transaccion contable de fondo de solidaridad pensional",
    "FACTOR_FONDO_SOLIDARIDAD"                  => "Numero de salarios minimos para base del fondo de solidaridad pensional",
    "AYUDA_NOMINA_PAGAR_SALUD"                  => "Seleccione la transaccion contable de nomina por pagar para pensión",
    "AYUDA_CUENTA_PAGAR_SALUD"                  => "Seleccione la transaccion contable de cuenta por pagar a la entidad de salud",
    "AYUDA_CANCELACION_NOMINA_PAGAR_SALUD"      => "Seleccione la transaccion contable de para cancelar la nomina por pagar de salud",
    "AYUDA_NOMINA_PAGAR_PENSION"                => "Seleccione la transaccion contable de  nomina por pagar pensión",
    "AYUDA_CUENTA_PAGAR_PENSION"                => "Seleccione la transaccion contable de cuenta por pagar a la entidad de pensión",
    "AYUDA_CANCELACION_NOMINA_PAGAR_PENSION"    => "Seleccione la transaccion contable de para cancelar la nomina por pagar pensión",

    "ERROR_TABLAS"                              => "Las siguientes tablas necesitan al menos un registro para configurar las preferencias globales:\n\n",
    "TABLA_VACIA_TASAS"                         => "- Tasas (Contabilidad)",
    "TABLA_VACIA_UNIDADES"                      => "- Unidades de medida (Inventario)",
    "TABLA_VACIA_TIEMPO"                        => "- Transacciones de tiempo (Recursos Humanos)",
    "TABLA_VACIA_INCAPACIDADES"                 => "- Transacciones de tiempo Tipo de concepto incapacidades",
    "TABLA_VACIA_DOCUMENTOS"                    => "- Tipos de documentos de indentidad (Administración)",
    "TABLA_VACIA_TC_5"                          => "- Transacciones contables, concepto: Nomina por pagar salud",
    "TABLA_VACIA_TC_41"                         => "- Transacciones contables, concepto: Cancelación nomina por pagar salud",
    "TABLA_VACIA_TC_42"                         => "- Transacciones contables, concepto: Cuenta por pagar salud",
    "TABLA_VACIA_TC_6"                          => "- Transacciones contables, concepto: Nomina por pagar pensión",
    "TABLA_VACIA_TC_44"                         => "- Transacciones contables, concepto: Cancelación nomina por pagar pensión",
    "TABLA_VACIA_TC_45"                         => "- Transacciones contables, concepto: Cuenta por pagar pensión",
    "TABLA_VACIA_TC_4"                          => "- Plan contable",
    "TABLA_VACIA_TC_7"                          => "- Transacciones contables, concepto: Aporte solidaridad pensión",

    "EQUIVALE_SALARIO_INTEGRAL"                 => "A cuantos salarios minimos equivale un salario integral",
    "VALOR_MINIMO_INGRESOS_VARIOS"              => "Valor minimo ingresos varios",
);
?>
