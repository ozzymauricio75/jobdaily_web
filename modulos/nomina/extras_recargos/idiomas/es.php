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
    "GESTEXRE"                                      => "Normales, Extras y Recargos",
    "ADICEXRE"                                      => "Adicionar nomales, extras o recargos",
    "CONSEXRE"                                      => "Consultar nomales, extras o recargos",
    "MODIEXRE"                                      => "Modificar nomales, extras o recargos",
    "ELIMEXRE"                                      => "Eliminar nomales, extras o recargos",
    "LISTEXRE"                                      => "Listar nomales, extras o recargos",
    "REPORTE_HORAS"                                 => "Reporte de Horas normales, extras o recargos",

    "PESTANA_BASICA"                                => "Datos",
    "PESTANA_SUCURSALES"                            => "Almacenes",
    "SUCURSAL"                                      => "Almacen",
    "AYUDA_SUCURSAL"                                => "Seleccione el almacen",
    "EMPLEADO"                                      => "Nombre del empleado",
    "AYUDA_EMPLEADO"                                => "Digite el nombre del empleado",
    "FECHA_INICIO"                                  => "Fecha inicial",
    "FECHA_FIN"                                     => "Fecha Final",
    "AYUDA_FECHAS"                                  => "Seleccione el rango de fechas para el registro",
    "HORA_INICIO"                                   => "Hora Inicial",
    "AYUDA_HORA_INICIO"                             => "Digite la hora final",
    "HORA_FIN"                                      => "Hora Final",
    "AYUDA_HORA_FIN"                                => "Digite la hora final",
    "TRANSACCION"                                   => "Transaccion de tiempo",
    "CANTIDAD"                                      => "Total horas",
    "TRANSACCION_CONTABLE"                          => "Transaccion Contable",
    "ERROR_HORAS_CONTABILIZADAS"                    => "Error, el archivo no se puede eliminar ni modificar porque ya fue contabilizado",
    "AUXILIAR_CONTABLE"                             => "Auxiliar",
    "ANEXO_CONTABLE"                                => "Anexo",
    "ELIMINAR_TODOS"                                => "Eliminar todos",
    "TURNO_LABORAL"                                 => "Turno laboral en fechas seleccionadas",
    "AUTORIZA_EXTRAS"                               => "Autoriza extras",
    "HORAS_DOMINGOS_FESTIVOS"                       => "Horas domingos y festivos",
    "PEMITE_FESTIVOS"                               => "Permite Festivos",
    "SI"                                            => "Si",
    "NO"                                            => "No",
    "PAGO_DOMINICAL"                                => "Paga dominicales",
    "PAGO_FESTIVOS"                                 => "Paga festivos",
    "VALOR_MOVIMIENTO"                              => "Valor",
    "DESDE_HASTA"                                   => "Fecha desde&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha hasta",
    "DOCUMENTO_DESDE"                               => "Documento desde",
    "DOCUMENTO_HASTA"                               => "Documento hasta",
    "ERROR_DOCUMENTO_IDENTIDAD_1"                   => "Debe digitar el documento hasta si quiere usar el documento desde",
    "NO_EXISTEN_HORAS_NORMALES"                     => "No tiene asignada transaccion de tiempo de Horas normales",
    "NO_EXISTEN_HORAS_RECARGO_NOCTURNO"             => "No tiene asignada transaccion de tiempo de Horas recargo nocturno",
    "NO_EXISTEN_HORAS_RECARGO_NOCTURNO_DOMINGO"     => "No tiene asignada transaccion de tiempo de Horas recargo nocturno domingos y festivos",
    "NO_EXISTEN_HORAS_DOMINICALES"                  => "No tiene asignada transaccion de tiempo de Horas domingos y festivos",
    "NO_EXISTEN_HORAS_NOCTURNAS_DOMINICALES"        => "No tiene asignada transaccion de tiempo de Horas recargo nocturno domingos y festivos",
    "NO_EXISTEN_EXTRAS_HORAS_DOMINICALES"           => "No tiene asignada transaccion de tiempo de Hora extra dominingo o festivo",
    "NO_EXISTEN_EXTRAS_NOCTURNA_HORAS_DOMINICALES"  => "No tiene asignada transaccion de tiempo de Hora extra nocturna domingo y festivo",
    "NO_EXISTEN_EXTRAS"                             => "No tiene asignada transaccion de tiempo de Horas extras",
    "NO_EXISTEN_EXTRAS_NOCTURNAS"                   => "No tiene asignada transaccion de tiempo de Horas extras nocturna",
    "ERROR_EMPLEADO_INACTIVO"                       => "El empleado esta inactivo en la fecha seleccionada",
    "ERROR_SUCURSAL_EQUIVOCADA"                     => "En la fecha seleccionada este empelado esta activo para el almacen",
    "ERROR_SUCURSAL_EQUIVOCADA2"                    => "\nFavor cambie el almacen o verifique la informacion",
    "HORA_BORRADA_EXITOSAMENTE"                     => "La hora ha sido borrada satisfactoriamente",
    "ERROR_BORRAR_HORA"                             => "Error, no se pudo borrar el registro",
    "FOMARTO_HORA"                                  => "h",
    "FOMARTO_MINUTO"                                => "m",
    "FOMARTO_SEGUNDOS"                              => "s",
    "CONTABILIZADO_MOVIMIENTO"                      => "Los tiempos registrados ya fue leida por salario y/o pagada",
    "CONTABILIZADO"                                 => "Contabilizado",
    "CANCELADO"                                     => "Liquidado",
    "NO_CANCELADO"                                  => "No cancelado",
    "FECHA_PAGO"                                    => "Fecha pago",
    "ESTADO"                                        => "Estado",
    "FECHA_PAGO"                                    => "Fecha planilla",
    "FECHA_UNICA"                                   => "Fecha unica",
    "TOTAL_EMPLEADO"                                => "Total empleado",
    "EMPLEADO"                                      => "Empleado",
    "AYUDA_EMPLEADO"                                => "Digite la cedula o el nombre del empleado y seleccionelo de la lista",
    "ERROR_SUCURSALES"                              => "-Sucursales",
    "ERROR_EMPLEADOS"                               => "-Empleados",
    "ERROR_SUCURSAL_VACIA"                          => "Seleccione un almacen",
    "ERROR_FECHA_VACIA"                             => "Seleccione las fechas para listar la información",
    "AYUDA_FECHA"                                   => "Seleccione las fechas para el listado",
    "FECHAS"                                        => "Fechas",
    "APELLIDO_NOMBRE"                               => "Apellido-Nombre",
    "NOMBRE_APELLIDO"                               => "Nombre-Apellido",
    "CEDULA"                                        => "Documento de identidad",
    "PDF"                                           => "Pdf",
    "PLANO"                                         => "Plano para excel",
    "ORDEN_EMPLEADO"                                => "Orden empleado",
    "AYUDA_ORDEN_EMPLEADO"                          => "Seleccione el orden en que aparecen los empleados",
    "TIPO_LISTADO"                                  => "Tipo listado",
    "AYUDA_TIPO_LISTADO"                            => "Seleccion el tipo de listado a generar",
    "TOTAL_SUCURSAL"                                => "Total almacen",
    "DOCUMENTO_EMPLEADO"                            => "Documento identidad"
);
?>
