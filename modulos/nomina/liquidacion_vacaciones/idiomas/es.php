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
    "GESTVATO"                           => "Liquidación de vacaciones",
    "AUTOPAGO"                           => "Autorizar liquidación de vacaciones",
    "ADICVATO"                           => "Adicionar liquidación de vacaciones",
    "LIQUPAGA"                           => "Pagar liquidación de vacaciones",
    "CONSVATO"                           => "Consultar liquidación de vacaciones",
    "MODIRENL"                           => "Modificar liquidación de vacaciones",
    "ELIMRENL"                           => "Eliminar reporte de dias vacacionales",
    "LISTRENL"                           => "Listado reporte de tiempos no laborados por dias",
    "REPORTE_INCAPACIDADES"              => "Reporte de tiempos no laborados",
    "AUTORIZAR"                          => "Autorizar",
    "PESTANA_BASICA"                     => "Básica",
    "SUCURSAL_LABORA"                    => "Almacen",
    "AYUDA_EMPLEADO"                     => "Nombre completo del empleado",
    "TIPO_TRANSACCION"                   => "Tipo transaccion",
    "MOTIVO_TIEMPO_NO_LABORADO"          => "Motivo de tiempo no laborado",

    "FECHA_INICIAL"                      => "Fecha de inicio",
    "FECHA_REPORTE"                      => "Fecha Reporte",
    "CANTIDAD_DIAS"                      => "Días a tomar",
    "CANTIDAD_DIAS_PENDIENTES"           => "Días pendientes",
    "ERROR_DIAS_PENDIENTES"              => "Los dias a tomar deben ser menor a los dias pendientes de las vacaciones",

    "FECHA_TIEMPO"                       => "Fecha no laborada",
    "FECHAS_INCAPACIDAD"                 => "Fechas de la incapacidad",
    "ID_MOTIVO_INCAPACIDAD"              => "Motivo incapacidad",
    "ID_SUCURSAL"                        => "Almacen",
    "ESTA_INCAPACITADO"                  => "El empleado se encuentra incapacitado",
    "NO_PRORROGAS"                       => "El empleado no se encuentra incapacitado",
    "INCAPACIDAD_DIFERENTE"              => "El empleado se encuentra incapacitado por diferente motivo",
    "AYUDA_FECHA_INICIAL"                => "Fecha en la que inicia las vacaciones",
    "AYUDA_FECHA_REPORTE"                => "Fecha en la que se reporta la incapacidad en la EPS",

    "EMPLEADO"                           => "Nombre del empleado",
    "ALMACEN"                            => "Almacen",
    "FECHA_INICIA"                       => "Fecha Incapacidad",
    "DOCUMENTO_IDENTIDAD"                => "Documento identidad",

    "AYUDA_SUCURSAL_LABORA"              => "Almacen en la que labora el empleado",
    "AYUDA_MOTIVO_TIEMPO_NO_LABORADO"    => "Seleccione el motivo del tiempo no laborado",

    "AYUDA_TIPO_TRANSACCION"             => "Seleccionar el tipo de transaccion para la incapacidad",
    "ERROR_DATOS_VACIOS_JS"              => "Error, los campos Nombre del empleado, Dias de incapacidad, Tipo transaccion y Almacen no pueden quedar vacios",
    "ERROR_DATOS_VACIOS_JS2"             => "Error, el campo Dias de incapacidad no puede quedar vacio",
    "ERROR_TABLA_VACIA"                  => "Error, la tabla de incapacidades no tiene datos",
    "ELIMINAR_TODOS"                     => "Eliminar todos",
    "FECHA_INCAPACIDAD_TIEMPO"           => "Fecha inicio",
    "GENERAR_FECHAS"                     => "Generar Fechas",

    "ANEXO_CONTABLE"                     => "Anexo contable",
    "AUXILIAR_CONTABLE"                  => "Auxiliar contable",
    "AYUDA_ANEXO_CONTABLE"               => "Seleccione un anexo contable",
    "AYUDA_AUXILIAR_CONTABLE"            => "Seleccione un auxiliar contable",

    "MOTIVOS_DIFERENTES"                 => "No se puede generar la prorroga porque los motivos de incapacidad del dia anterior al de la Fecha de Inicio son diferentes",
    "NO_EXISTE_INCAPACIDAD"              => "No se puede generar la prorroga porque no existe incapacidad el dia anterior al de la Fecha de Inicio",
    "EXISTE_CRUCE"                       => "En el rango de fechas generadas ya existe una o varias incapacidades reportadas en la base de datos o en la tabla",
    "DIAS_CERO"                          => "Los dias no pueden ser iguales a 0 (cero), verifique",
    "DIAS_3"                             => "Los dias no pueden ser mayores a 3 en esta transaccion, verifique",
    "DIAS_89"                            => "Los dias no pueden ser mayores a 89 en esta transaccion, verifique",
    "DIAS_179"                           => "Los dias no pueden ser mayores a 179 en esta transaccion, verifique",
    "DIAS_180"                           => "Los dias no pueden ser menores a 180 en esta transaccion, verifique",

    "VALOR_DIA"                          => "Valor del dia",
    "DIVIDENDO"                          => "Dividendo",
    "DIVISOR"                            => "Divisor",
    "VALOR_MOVIMIENTO"                   => "Valor movimiento",
    "ERROR_FECHA_MENOR"                  => "Error, la fecha de inicio no puede ser menor a la fecha de reporte",
    "FECHA_INGRESO_NO_PERMITIDA"         => "Error, la fecha de reporte de incapacidad no puede ser menor a la fecha del ingreso del empleado al almacen",
    "NO_MODIFICAR_NO_ELIMINAR"           => "Error, El registro ya fue leido para salario y/o pagado.\nNo se permite modificar ni eliminar",
    //Reporte
    "SUCURSAL"                           => "Almacen",
    "EMPLEADO"                           => "Empleado",
    "DESDE_HASTA"                        => "Fecha desde&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha hasta",
    "DOCUMENTO_DESDE"                    => "Documento desde",
    "DOCUMENTO_HASTA"                    => "Documento hasta",
    "ERROR_DOCUMENTO_IDENTIDAD_1"        => "Debe digitar el documento hasta si quiere usar el documento desde",
    "NO_DIAS"                            => "No. Dias",
    "AYUDA_CANTIDAD_DIAS"                => "Indique la cantidad tomados por el empleado para vacaciones",
    "MENSAJE_FALTA_DATOS"                => "Las siguientas tablas deben contener un registro.\n\n",
    "NO_EXISTEN_EMPLEADOS"               => "-Empledos \n ",
    "NO_EXISTEN_SUCURSALES"              => "-Almacenes \n ",
    "NO_EXISTEN_DOMINGOS_FESTIVOS"       => "-Domingos y festivos para el año actual",
    "MENSAJE_FECHAS_EXISTE"              => "En el rango de fechas generadas ya existe una o varias incapacidades reportadas en la base de datos o en la tabla",
    "MENSAJE_CAMPOS_VACIOS"              => "Los siguientes son obligatorios para agregar los dias no laborados. \n \n ",
    "MENSAJE_VACIO_EMPLEADO"             => " - Empleado \n ",
    "FECHA_FINAL"                        => "Fecha final",
    "MENSAJE_LEIDO_SALARIO"              => " El registro ya fue leido para salario y/o pagado. No se permite modificar ni eliminar",
    "MENSAJE_CONFIRMACION"               => "Esta seguro de que desea eliminar los tiempo no laborados de la base de datos",
    "MENSAJE_EXITO_ELIMINO"              => "el registro fue eliminado con exito.",
    "MENSAJE_ERROR_ELIMINO"              => "se ha generado un error al momento de eliminar el registro.",
    "MENSAJE_PERIODO_CONTABLE"           => "El almacen seleccionado tiene el periodo contable cerrado para esta operacion",
    "MENSAJE_CERRADO_PERIODO"            => "El almacen seleccionado tiene el periodo contable cerrado para los siguientes dias \n \n" ,

    "VACIO_NOMBRE_EMPLEADO"              => "Por favor seleccione un empleado",
    "VACIO_FECHA_INICIAL"                => "Por favor seleccion la fecha de inicio",
    "VACIO_DIAS_TOMADOS"                 => "Por favor ingrese el numero de dias que desea tomar",
    "EXISTE_DATOS_FECHA"                 => "Error, ya existe un registros en la base de datos con las fechas tomadas como vacaciones",
    "FECHA_FINAL_VACACIONES"             => "Fecha final",
    "DIAS_DISFRUTAR"                     => "Dias a disfrutar",
    "FORMA_LIQUIDACION"                  => "Forma de liquidación",
    "AYUDA_FORMA_LIQUIDACION"            => "Indica la si se liquida todos los devengos obtenidos hasta la fecha o solo las vacaiones.",
    "CONCEPTO"                           => "Concepto",
    
    "TITULO_PAGOS"                       => "Resumen Liquidacion pagos :",
    "TITULO_DESCUENTOS"                  => "Resumen Liquidacion descuentos :",
    "TITULO_DEVENGO"                     => "Total devengo",
    "TITULO_DEDUCCIONES"                 => "Total deducciones",
    "TITULO_TOTAL_LIQUIDACION"           => "Total liquidacion",

    "VALOR_MOVIENTO"                     => "Valor",
    "VACACIONES"                         => "Vacaciones",
    "SALARIO"                            => "Salario",
    "AUXILIO_TRANSPORTE"                 => "Auxilio de transporte",
    "HORAS_EXTRAS"                       => "Horas extras",
    "SALUD"                              => "Salud",
    "PENSION"                            => "Pension",
    "AFECTA_PLANILLA"                    => "Afecta en planilla",
    "LIQUIDACION_TOTAL"                  => "Liquidación total de vacaciones",
    "ERROR_TRANSACCIONES_TIEMPO"         => "Por favor verifique que existan al menos una transaccion de tiempo este relacionado con las siguientes transacciones contable: \n",
    "ESTADO"                             => "Estado",
    "ESTADO_1"                           => "Pendiente",
    "ESTADO_2"                           => "Cancelado",
    "ESTADO_3"                           => "Autorizado",
    "ESTADO_4"                           => "Pagado",

    "TIPO_DOCUMENTO"                     => "Tipo de documento",
    "NUMERO_DOCUMENTO"                   => "Documento identidad",
    "AYUDA_TIPO_DOCUMENTO"               => "Seleccione el tipo de documento para el movimiento",
    "CUENTA_BANCARIA"                    => "Cuenta bancaria",
    "CONSECUTIVO_CHEQUE"                 => "Consecutivo cheque",
    "AYUDA_CONSECUTIVO_CHEQUE"           => "Número del cheque que genera el documento",
    "CUENTA"                             => "Cuenta",
    "AYUDA_CUENTA"                       => "Cuenta del plan contable por donde se retira el dinero",
    "CONSECUTIVO_DOCUMENTO"              => "Número documento",
    "AYUDA_CONSECUTIVO_DOCUMENTO"        => "Número del documento que genera el saldo",
    "SUCURSAL_PAGA"                      => "Almacen genera",
    "AYUDA_SUCURSAL_PAGA"                => "Almacen genera el pado de la liquidación",
    "CUENTAS_BANCARIAS_VACIAS"           => "El tipo de documento seleccionado genera cheques y no existen cuentas bancarias\npara este documento en el almacen que genera seleccionada.\nFavor verifique",
    "ERROR_AUTORIZAR_VACIO"              => "Por favor seleccione la liquidación de vacacion que desea autorizar",
    "ERROR_PAGAR_VACIO"                  => "Por favor seleccione la liquidación de vacacion que desea pagar",
    "ERROR_ESTADO"                       => "Para poder autorizar una liquidación es necesario que su estado sea pendiente",
    "ERROR_ESTADO_AUTORIZADO"            => "Para poder pagar una liquidación es necesario que su estado sea autorizado",
    "ERROR_SUCURSAL_GENERA"              => "Por favor seleccione la sucursal que genera el pago",
    "ERROR_TIPO_DOCUMENTO"               => "Por favor seleccione un tipo de documento",
    "ERROR_CUENTA_VACIA"                 => "Por favor seleccione una cuenta del plan contable por donde se retira el dinero",
    "ERROR_CONSECUTIVO_DOCUMENTO"        => "Por favor ingrese un Número de documento",
    "EXISTE_CONSECUTIVO_DOCUMENTO"       => "Se ha generado un error al momento de registrar el consecutivo documento",
    "CONSECUTIVO_DOCUMENTO_EXISTE"       => "Ya existe un documento con el mismo consecutivo.",
    "NO_EXISTE_TABLA"                    => "- Tablas (Nombre de la tabla : movimiento_liquidacion_vacaciones) \n ",
    "PAGAR"                              => "Pagar",
    "ERROR_PAGO_LIQUIDACION"             => "Se ha generado un error al momento de registrar datos de la for, Tabla: datos_liquidaciones_empleado",
    "ERROR_ADICIONAR_CONSECUTIVO_CHEQUE" => "Se ha generado un error al momento de registrar el consecutivo de cheque",
    "PENDIENTE"                          => "Pendiente",
    "AUTORIZADO"                         => "Autorizado",
    "PAGO"                               => "Pagado",
    "CANCELADO"                          => "Cancelado",
    "ERROR_NO_TIENE_PENDIENTES"          => "El empleado seleccionado no tiene dias pendientes dentro del año de la fecha seleccionada",
    "FORMA_PAGO"                         => "Forma de pago"

);
?>
