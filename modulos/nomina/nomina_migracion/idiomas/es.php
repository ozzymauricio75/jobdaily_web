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
    "GESTNMIG"                    => "Movimientos migración nomina",
    "ADICNMIG"                    => "Adicionar movimiento migración nomina",
    "CONSNMIG"                    => "Consultar movimiento migración nomina",
    "MODINMIG"                    => "Modificar movimiento migración nomina",
    "ELIMNMIG"                    => "Eliminar movimiento migración nomina",
    "LISTNMIG"                    => "Listado de movimientos de migración nomina",
    "SUCURSAL"                    => "Almacen",
    "PESTANA_SUCURSALES"          => "Almacenes",
    "SELECCIONAR_TODOS"           => "Seleccionar todos",
    "PESTANA_BASICA"              => "Básica",
    "DOCUMENTO"                   => "Documento identidad",
    "FECHA_PAGO_PLANILLA"         => "Fecha de pago",
    "EMPLEADO"                    => "Empleado",
    "AYUDA_EMPLEADO"              => "Digite el nombre o documento de identidad del empleado y seleccione de la lista",
    "TRANSACCION_CONTABLE"        => "Transacción contable",
    "ANO"                         => "Ano de pago",
    "MES"                         => "Mes de pago",
    "PERIODO"                     => "Periodo",
    "FECHA_PAGO"                  => "Fecha pago",
    "AYUDA_FECHA_PAGO"            => "Seleccion la fecha de pago",
    "PLANILLA"                    => "Planilla",
    "ENERO"                       => "Enero",
    "FEBRERO"                     => "Febrero",
    "MARZO"                       => "Marzo",
    "ABRIL"                       => "Abril",
    "MAYO"                        => "Mayo",
    "JUNIO"                       => "Junio",
    "JULIO"                       => "Julio",
    "AGOSTO"                      => "Agosto",
    "SEPTIEMBRE"                  => "Septiembre",
    "OCTUBRE"                     => "Octubre",
    "NOVIEMBRE"                   => "Noviembre",
    "DICIEMBRE"                   => "Diciembre",
    "AYUDA_MES_PAGO"              => "Mes del año en que paga la planilla",
    "AYUDA_DIA_PAGO"              => "Dia del mes en que paga la planilla",
    "AYUDA_PLANILLA"              => "Selecccione la planilla",
    "PESTANA_GENERAL"             => "Información General",
    "MENSUAL"                     => "Mensual",
    "PRIMERA_QUINCENA"            => "Primera quincena",
    "SEGUNDA_QUINCENA"            => "Segunda quincena",
    "PRIMERA_SEMANA"              => "Primer semana",
    "SEGUNDA_SEMANA"              => "Segunda semana",
    "TERCERA_SEMANA"              => "Tercer semana",
    "CUARTA_SEMANA"               => "Cuarta semana",
    "QUINTA_SEMANA"               => "Quinta semana",
    "FECHA_UNICA"                 => "Fecha unica",
    "ERROR_SUCURSAL_VACIO"        => "Seleccione un almacen",
    "ERROR_EMPLEADO_VACIO"        => "Error, empleado vacio",
    "ERROR_PLANILLA_VACIO"        => "Error, seleccione una planilla",
    "ERROR_FECHA_PAGO_VACIO"      => "Error, fecha pago vacio",
    "ERROR_PERIODO_VACIO"         => "Error, periodo vacio",
    "ERROR_CONTRATO"              => "Error, verifique los datos del contrato del empleado:\n-Almacen\n-Fecha ingreso\n-Estado(Activo/Retirado)",
    "ERROR_TRANSACCION_CONTABLE"  => "Error, seleccione una transaccion contable",
    "ERROR_VALOR_MOVIMIENTO"      => "Error, digite el valor del movimiento",
    "TRANSACCION_CONTABLE"        => "Transaccion contable",
    "AYUDA_TRANSACCION_CONTABLE"  => "Digite la descripcion de la transaccion contable y seleccinela de la lista",
    "VALOR_MOVIMIENTO"            => "Valor movimiento",
    "AYUDA_VALOR_MOVIMIENTO"      => "Digite el valor movimiento",
    "DESDE_HASTA"                 => "Fecha desde&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha hasta",
    "EMPLEADO"                    => "Empleado",
    "AYUDA_EMPLEADO"              => "Digite la cedula o el nombre del empleado y seleccionelo de la lista",
    "AYUDA_SUCURSAL"              => "Seleccione el almacen",
    "ERROR_SUCURSALES"            => "-Sucursales",
    "ERROR_EMPLEADOS"             => "-Empleados",
    "ERROR_SUCURSAL_VACIA"        => "Seleccione un almacen",
    "ERROR_FECHA_VACIA"           => "Seleccione las fechas para listar la información",
    "AYUDA_FECHA"                 => "Seleccione las fechas para el listado",
    "FECHAS"                      => "Fechas",
    "APELLIDO_NOMBRE"             => "Apellido-Nombre",
    "NOMBRE_APELLIDO"             => "Nombre-Apellido",
    "CEDULA"                      => "Documento",
    "PDF"                         => "Pdf",
    "PLANO"                       => "Plano para excel",
    "ORDEN_EMPLEADO"              => "Orden empleado",
    "AYUDA_ORDEN_EMPLEADO"        => "Seleccione el orden en que aparecen los empleados",
    "TIPO_LISTADO"                => "Tipo listado",
    "AYUDA_TIPO_LISTADO"          => "Seleccion el tipo de listado a generar",
    "TOTAL_SUCURSAL"              => "Total almacen",
    "TOTAL_EMPLEADO"              => "Total empleado",
    "DOCUMENTO_EMPLEADO"          => "Documento identidad",
    "DOCUMENTO"                   => "Documento",
    "NOMBRE_EMPLEADO"             => "Empleado",
    "USUARIO"                     => "Elaborada por:",
    "EMPLEADO"                    => "Nombre del empleado",
    "AYUDA_EMPLEADO"              => "Digite el nombre del empleado que desea listar",
    "TRANSACCION_CONTABLE"        => "Transaccion contable",
    "CUENTA_CONTABLE"             => "Cuenta contable",
    "SENTIDO"                     => "Sentido",
    "VALOR_MOVIMIENTO"            => "Valor movimiento",
    "FECHA_REGISTRO"              => "Fecha de registro",
    "AUXILIAR"                    => "Auxiliar",
    "ANEXO"                       => "Anexo",
    "LISTADO_GENREADO"            => "Listado movimientos para prima generado satisfactoriamente",
);
?>
