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

    "GESTDCPL"                                   => "Descontabilizar planilla",
    "PESTANA_BASICA"                             => "Datos de planilla",
    "PESTANA_SUCURSALES"                         => "Almacenes",
    "SELECCIONAR_TODOS"                          => "Seleccionar todo",
    "EJEMPLO"                                    => "Ejemplo",
    "AYUDA_SUCURSAL"                             => "Marque la casilla para seleccionar ó quitar la seleccion",
    "ANO_PLANILLA"                               => "Año",
    "AYUDA_ANO_PLANILLA"                         => "Seleccione el año de liquidación",
    "MES_PLANILLA"                               => "Mes",
    "AYUDA_MES_PLANILLA"                         => "Seleccione el mes de liquidación",
    "DATOS_PLANILLA"                             => "Datos de planilla",
    "PLANILLA"                                   => "Planilla",
    "AYUDA_PLANILLA"                             => "Selecciona la planilla",
    "FECHA_PAGO"                                 => "Fecha de pago",
    "AYUDA_FECHA_PAGO"                           => "Selecciona la fecha en la cual se paga la planilla",
    "PERIODO"                                    => "Periodo a liquidar",
    "AYUDA_PERIODO"                              => "Seleccione el periodo a liquidar",
    "MENSUAL"                                    => "Mensual",
    "PRIMERA_QUINCENA"                           => "Primera quincena",
    "SEGUNDA_QUINCENA"                           => "Segunda quincena",
    "PRIMER_SEMANA"                              => "Primer semana",
    "SEGUNDA_SEMANA"                             => "Segunda semana",
    "TERCERA_SEMANA"                             => "Tercer semana",
    "CUARTA_SEMANA"                              => "Cuarta semana",
    "QUINTA_SEMANA"                              => "Quinta semana",
    "ERROR_SUCURSALES"                           => "No existen almacenes en la empresa",
    "ERROR_PLANILLAS"                            => "No existen tipos de planillas",
    "ERROR_FECHAS_PLANILLAS"                     => "No existen fechas para generación de planillas",
    "ERROR_EMPLEADOS"                            => "No existen empleados en la empresa",
    "ERROR_SUCURSAL_VACIA"                       => "Seleccione por lo menos un almacen",
    "ERROR_CODIGO_PLANILLA"                      => "Seleccione una planilla",
    "ERROR_FECHAS"                               => "Fecha inicial no puede ser mayor a la fecha final",
    "ERROR_FECHA_PAGO"                           => "Seleccione una fecha de pago",
    "ERROR_PERIODO"                              => "Seleccione un periodo de pago",
    "ERROR_QUINCENAL"                            => "La fechas trabajadas suman mas de 15 dias y no puede ser mayor a este valor",
    "ERROR_MENSUAL"                              => "Los dias trabajados no pueden ser mayores a 30 dias",
    "ERROR_SEMANAL"                              => "Los dias trabajados son mayores a 7 dias",
    "NO_GENERO_INFORMACION"                      => "No se genero información",
    "ENERO"                                      => "Enero",
    "FEBRERO"                                    => "Febrero",
    "MARZO"                                      => "Marzo",
    "ABRIL"                                      => "Abril",
    "MAYO"                                       => "Mayo",
    "JUNIO"                                      => "Junio",
    "JULIO"                                      => "Julio",
    "AGOSTO"                                     => "Agosto",
    "SEPTIEMBRE"                                 => "Septiembre",
    "OCTUBRE"                                    => "Octubre",
    "NOVIEMBRE"                                  => "Noviembre",
    "DICIEMBRE"                                  => "Diciembre",
    "EXITO_PLANILLA_DESCONTABILIZADA"            => "Planilla descontabilizada de manera exitosa",
    "ERROR_PLANILLA_CONTABILIZADA_INCAPACIDADES" => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en Incapacidades",
    "ERROR_PLANILLA_CONTABILIZADA_TIEMPOS"       => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en Tiempos laborados",
    "ERROR_PLANILLA_CONTABILIZADA_SALUD"         => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en Salud",
    "ERROR_PLANILLA_CONTABILIZADA_PENSION"       => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en Pension",
    "ERROR_PLANILLA_CONTABILIZADA_SALARIOS"      => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en Salarios",
    "ERROR_PLANILLA_CONTABILIZADA_AUXILIO"       => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en Auxilio de transporte",
    "ERROR_PLANILLA_CONTABILIZADA_MANUALES"      => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en novedades manuales",
    "ERROR_PLANILLA_CONTABILIZADA_PRESTAMOS"     => "Ocurrio un error mientras se generaba la descontabilizacion de la planilla en prestamos a empleados",
    "ERROR_FORMA_PAGO"                           => "Ocurrio un error mientras se generaba la descontabilizacion de la forma de pago.",
    "PLANILLA_PAGADA"                            => "La planilla no puede ser descontabilizada porque ya fue pagada",
    "ERROR_ELIMINANDO_DOCUMENTO"                 => "Error al intentar eliminar un consecutivo de documento",
    "ERROR_FORMA_PAGO_NOMINA"                    => "Error al intentar eliminar forma de pago nomina por pagar",

    "GESTELMO"                                   => "Eliminar Movimientos",
    "AUTOMATICOS"                                => "Automaticos",
    "MANUALES"                                   => "Manuales",
    "TODAS"                                      => "Todos",
    "MOVIMIENTOS"                                => "Movimientos",
    "AYUDA_MOVIMIENTOS"                          => "selecciones el tipo de movimiento que desea eliminar",
    "ERROR_ELIMINAR_MOVIMIENTOS"                 => "Se generaron errores al intentar eliminar movimientos de las siguientes tablas \n ",
    "EXITO_ELIMINAR_MOVIMINETOS"                 => "Los movimientos ha sido eliminado con exito.",
    "PLANILLA_PAGADA"                            => "Los movimientos de los siguientes almacenes no fueron eliminados, porque la planilla ya fue cancelada: \n \n",
    "ERROR_PAGAR_PLANILLA"                       => "Se genero un error al intentar pagar la planilla"
);
?>
