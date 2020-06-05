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
    "GESTNOMA"                   => "Novedades manuales",
    "ADICNOMA"                   => "Adicionar novedad",
    "CONSNOMA"                   => "Consultar novedad",
    "MODINOMA"                   => "Modificar novedad",
    "ELIMNOMA"                   => "Eliminar novedad",
    "LISTNOMA"                   => "Listar novedades manuales",
    "REPORTE_NOVEDADES"          => "Reporte de novedades manuales",

    "VALOR"                      => "Valor novedad",
    "TRANSACCION_CONTABLE"       => "Transaccion contable",
    "AYUDA_TRANSACCION"          => "Nombre de la transaccion contable que se le adiona al empleado",
    "AYUDA_VALOR_NOVEDAD"        => "Valor de la novedad que desea registra",
    "ANEXO_CONTABLE"             => "Anexo contable",
    "AUXILIAR_CONTABLE"          => "Auxiliar contable",
    "AYUDA_ANEXO_CONTABLE"       => "Seleccione un anexo contable",
    "AYUDA_AUXILIAR_CONTABLE"    => "Seleccione un auxiliar contable",

    "PESTANA_BASICA"             => "Datos",
    "SUCURSAL"                   => "Almacen",
    "AYUDA_SUCURSAL"             => "Seleccione el almacen",
    "EMPLEADO"                   => "Nombre del empleado",
    "AYUDA_EMPLEADO"             => "Digite el nombre del empleado",
    "TRANSACCION"                => "Transaccion de tiempo",
    "TRANSACCION_CONTABLE"       => "Transaccion Contable",
    "ERROR_HORAS_CONTABILIZADAS" => "Error, el archivo no se puede eliminar ni modificar porque ya fue contabilizado",
    "ELIMINAR_TODOS"             => "Eliminar todos",
    "TURNO_LABORAL"              => "Turno laboral en fechas seleccionadas",
    "AUTORIZA_EXTRAS"            => "Autoriza extras",
    "ANO_PLANILLA"               => "Año",
    "AYUDA_ANO_PLANILLA"         => "Seleccione el año de liquidación",
    "MES_PLANILLA"               => "Mes",
    "AYUDA_MES_PLANILLA"         => "Seleccione el mes de liquidación",
    "ENERO"                      => "Enero",
    "FEBRERO"                    => "Febrero",
    "MARZO"                      => "Marzo",
    "ABRIL"                      => "Abril",
    "MAYO"                       => "Mayo",
    "JUNIO"                      => "Junio",
    "JULIO"                      => "Julio",
    "AGOSTO"                     => "Agosto",
    "SEPTIEMBRE"                 => "Septiembre",
    "OCTUBRE"                    => "Octubre",
    "NOVIEMBRE"                  => "Noviembre",
    "DICIEMBRE"                  => "Diciembre",
    "DATOS_PLANILLA"             => "Datos de planilla",
    "PLANILLA"                   => "Planilla",
    "AYUDA_PLANILLA"             => "Selecciona la planilla",
    "FECHA_PAGO"                 => "Fecha de pago",
    "AYUDA_FECHA_PAGO"           => "Selecciona la fecha en la cual se paga la planilla",
    "PERIODO"                    => "Periodo a liquidar",
    "AYUDA_PERIODO"              => "Seleccione el periodo a liquidar",
    "MENSUAL"                    => "Mensual",
    "PRIMERA_QUINCENA"           => "Primera quincena",
    "SEGUNDA_QUINCENA"           => "Segunda quincena",
    "PRIMER_SEMANA"              => "Primer semana",
    "SEGUNDA_SEMANA"             => "Segunda semana",
    "TERCERA_SEMANA"             => "Tercer semana",
    "CUARTA_SEMANA"              => "Cuarta semana",
    "QUINTA_SEMANA"              => "Quinta semana",
    "CAMPOS_OBLIGATORIOS"        => "Los siguientes datos son obligatorios \n\n ",
    "VACION_TRANSACCION"         => "- Transaccion Contable \n ",
    "VACIO_VALOR_NOVEDAD"        => "- Valor de novedad \n ",
    "PAGO_PLANILLA"              => "No se pueden registrar novedades porque ya se pago la planilla selecciona.",
    "ITEM_ADICIONADO_CORRECTO"   => "El Registro se ha adicionado con exito, es necesario volver a \n \n - liquidar salarios \n - liquidar salud y pension.",
    "MENSUAL"                    => "Mensual",
    "PRIMERA_QUINCENA"           => "Primera quincena",
    "SEGUNDA_QUINCENA"           => "Segunda quincena",
    "PRIMER_SEMANA"              => "Primer semana",
    "SEGUNDA_SEMANA"             => "Segunda semana",
    "TERCERA_SEMANA"             => "Tercer semana",
    "CUARTA_SEMANA"              => "Cuarta semana",
    "QUINTA_SEMANA"              => "Quinta semana",
    "PRIMERA_SEMANA"             => "Primera semana",
    "CONTABILIZADO_MOVIMIENTO"   => "La novedad ya fue leida por salario y/o pagada.",
    "DOCUMENTO_IDENTIDAD"        => "Documento de identidad",
    "MENSAJE_PERIODO_CONTABLE"   => "El almacen seleccionado tiene el periodo contable cerrado para esta operacion",
    "ERROR_NO_GENERO_NOVEDADES"  => "Por favor genere almenos una novedad manual",
    "FECHA_UNICA"                => "Fecha unica",
    "APELLIDO_NOMBRE"            => "Apellido-Nombre",
    "NOMBRE_APELLIDO"            => "Nombre-Apellido",
    "CEDULA"                     => "Documento de identidad",
    "PESTANA_SUCURSALES"         => "Almacenes",
    "DESDE_HASTA"                => "Fecha listado",
    "AYUDA_FECHA"                => "Seleccione las fechas para la información del listado",
    "EMPLEADO"                   => "Empleado",
    "ORDEN_EMPLEADO"             => "Digire el el nombre o documento de identidad del empleado",
    "AYUDA_ORDEN_EMPLEADO"       => "Orden empleado",
    "TIPO_LISTADO"               => "Tipo listado",
    "AYUDA_TIPO_LISTADO"         => "Seleccione el tipo de listado",
    "ARCHIVO_PDF"                => "Pdf",
    "ARCHIVO_PLANO"              => "Plano para excel",
    "ERROR_SUCURSALES"           => "-Sucursales",
    "ERROR_EMPLEADOS"            => "-Empleados",
    "ERROR_SUCURSAL_VACIA"       => "Seleccione un almacen",
    "ERROR_FECHA_VACIA"          => "Seleccione las fechas para listar la información",
    "TOTAL_EMPLEADO"             => "Total empleado",
    "FECHAS"                     => "Fechas"
);
?>
