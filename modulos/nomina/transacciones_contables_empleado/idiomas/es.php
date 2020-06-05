<?php

/**
*
* Copyright (C) 2020 Jobdaily
* Walter Andrés Márquez Gutiérrez <walteramg@gmail.com>
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

$textos = array(
    "GESTTRCO"                  => "Transacciones contables",
    "ADICTRCO"                  => "Adicionar transacciones contables",
    "CONSTRCO"                  => "Consultar transacciones contables",
    "MODITRCO"                  => "Modificar transacciones contables",
    "ELIMTRCO"                  => "Eliminar transacciones contables",
    "LISTTRCO"                  => "Listar transacciones contables",
    "REPORTE_TRANSACCIONES"     => "Listado de transacciones contables",


    "PESTANA_BASICA"            => "Básica",
    "NOMBRE"                    => "Nombre transaccion",
    "CONCEPTO_CONTABLE"         => "Concepto contable",
    "CODIGO"                    => "Codigo",
    "AYUDA_CODIGO"              => "Digite el codigo para la transacción",
    "CODIGO_CONTABLE"           => "Codigo contable",
    "AYUDA_NOMBRE"              => "Nombre corto del tipo de transacción",
    "DESCRIPCION"               => "Descripcion",
    "AYUDA_DESCRIPCION"         => "Detalle que describe la transacción",
    "AYUDA_CODIGO_CONTABLE"     => "Digite el codigo o nombre de la cuenta contable",
    "AYUDA_CONCEPTO_CONTABLE"   => "Descripción concepto contable",
    "SENTIDO"                   => "Sentido",
    "CREDITO"                   => "Credito",
    "DEBITO"                    => "Debito",
    "CONCEPTO_CONTABLE"         => "Concepto contable",
    "CERTIFICADO_INGRESOS"      => "Certificado de ingresos",
    "CERTIFICADO_1"             => "Certificado 1",
    "CERTIFICADO_2"             => "Certificado 2",
    "CERTIFICADO_3"             => "Certificado 3",
    "AYUDA_CERTIFICADO_INGRESO" => "Seleccione el certificado de ingresos de la lista",
    "PLANILLA_PAGO"             => "Columna en la planilla de pagos",
    "AYUDA_PLANILLA_PAGO"       => "Columna que afecta en la planilla de pagos",
    "PESTANA_CONTABLE"          => "Contable",
    "RETENCION"                 => "Tipo de retención",
    "AYUDA_RETENCION"           => "Seleccione el tipo de retención de la lista",
    "SI"                        => "Si",
    "NO"                        => "No",
    "PARAFISCALES"              => "Parafiscales",
    "RETE_SALARIOS"             => "Salarios",
    "RETE_VACACIONES"           => "Vacaciones",
    "CESANTIAS"                 => "Acumula cesantias &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "PRIMA"                     => "Acumula prima &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "VACACIONES"                => "Acumula vacaciones &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "IBC_SALUD"                 => "Ingreso base salud &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "AYUDA_SALUD"               => "Ingreso base de cotización para salud",
    "IBC_PENSION"               => "Ingreso base pension &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "AYUDA_PENSION"             => "Ingreso base de cotización para pensión",
    "IBC_ARP"                   => "Ingreso base arp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "AYUDA_ARP"                 => "Ingreso base de cotización para accidentes de trabajo o enfermedad profesional",
    "IBC_ICBF"                  => "Ingreso base icbf &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "AYUDA_ICBF"                => "Ingreso base de cotización para aportes al ICBF",
    "IBC_SENA"                  => "Ingreso base sena &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
    "AYUDA_SENA"                => "Ingreso base de cotización SENA",
    "IBC_CAJA_COMPENSACION"     => "Ingreso caja de compensación &nbsp;&nbsp;&nbsp;",
    "AYUDA_CAJA_COMPENSACION"   => "Ingreso base de cotización  para aportes a la Caja de compensación",
    "ERROR_EXISTE_TRANSACCION"  => "Ya existe esta transaccion",
    "ERROR_CODIGO_CONTABLE"     => "No existen codigos contables de movimiento, debe crear por lo menos un codigo contable de movimiento",
    "AYUDA_SENTIDO"             => "Seleccion el sentido de la retención",
    "ERROR_CONCEPTOS"           => "No existe conceptos de transacciones contables para empleados, debe crear por lo menos un concepto",
    "CODIGO_CONTABLE_VACIO"     => "Error, por favor digite el codigo contable",
    "CONCEPTO_CONTABLE_VACIO"   => "Error, por favor seleccione el concepto",
    "NOMBRE_VACIO"              => "Error, por favor digite nombre de la transacción",
    "DESCRIPCION_VACIO"         => "Error, por favor digite la descripción del concepto'",
    "SALARIOS_OTROS_INGRESOS"   => "Salarios y otros ingresos",
    "RETENCIONES_PRACTICADAS"   => "Retenciones practicadas",
    "DESCUENTOS_SALUD_PENSION"  => "Descuentos salud pension",
    "PESTANA_CONTABLE_VACIO"    => "Debe seleccionar como minimo un dato en la pestaña contable.\nRecuerde que las no seleccionadas se grabaran por omisión como No",
    "ERROR_EXISTE_CODIGO"       => "Ya existe una transacción contable con ese codigo",
    "CODIGO_VACIO"              => "Digite el codigo de la transacción contable",
    "MAYOR_40"                  => "Si mayor 40%",
    // COLUMNAS PLANILLA //
    "COLUMNA_1"                 => "Salario devengado",
    "COLUMNA_2"                 => "Auxilio de transporte",
    "COLUMNA_3"                 => "Productividad",
    "COLUMNA_4"                 => "Horas extras",
    "COLUMNA_5"                 => "Incapacidades",
    "COLUMNA_6"                 => "Auxilio vehiculo",
    "COLUMNA_7"                 => "Auxilio extraordinario",
    "COLUMNA_8"                 => "Primas y vacaciones",
    "COLUMNA_10"                => "Salud",
    "COLUMNA_11"                => "Pension",
    "COLUMNA_12"                => "Descuentos varios",
    "COLUMNA_13"                => "Descuentos seguros",

    "COLUMNA_PLANILLA"          => "Columa planilla"
);
?>
