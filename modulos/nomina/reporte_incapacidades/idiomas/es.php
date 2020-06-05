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
        "GESTREIN"                      => "Reporte de incapacidades",
        "ADICREIN"                      => "Adicionar reporte incapacidad",
        "CONSREIN"                      => "Consultar reporte incapacidades",
        "MODIREIN"                      => "Modificar reporte incapacidades",
        "ELIMREIN"                      => "Eliminar reporte incapacidades",
        "LISTREIN"                      => "Listado reporte incapacidades",
        "REPORTE_INCAPACIDADES"         => "Reporte de Incapacidades",

        "PESTANA_BASICA"                => "Básica",
        "PESTANA_SUCURSALES"            => "Almacenes",
        "SUCURSAL_LABORA"               => "Almacen",
        "AYUDA_EMPLEADO"                => "Nombre completo del empleado",
        "TIPO_TRANSACCION"              => "Tipo transaccion",
        "MOTIVO_INCAPACIDAD"            => "Motivo de la incapacidad",
        "NUMERO_INCAPACIDAD"            => "Numero de incapacidad",
        "FECHA_INICIAL"                 => "Fecha de Inicio",
        "FECHA_REPORTE"                 => "Fecha Reporte",
        "CANTIDAD_DIAS"                 => "Días de incapacidad",
        "FECHA_INCAPACIDAD"             => "Fecha incapacidad",
        "FECHAS_INCAPACIDAD"            => "Fechas de la incapacidad",
        "ID_MOTIVO_INCAPACIDAD"         => "Motivo incapacidad",
        "ID_SUCURSAL"                   => "Almacen",
        "ESTA_INCAPACITADO"             => "El empleado se encuentra incapacitado",
        "NO_PRORROGAS"                  => "El empleado no se encuentra incapacitado",
        "INCAPACIDAD_DIFERENTE"         => "El empleado se encuentra incapacitado por diferente motivo",
        "AYUDA_FECHA_INICIAL"           => "Fecha en la que inicia la incapacidad para la contabilidad",
        "AYUDA_FECHA_REPORTE"           => "Fecha en la que se reporta la incapacidad en la EPS",

        "EMPLEADO"                      => "Nombre del empleado",
        "ALMACEN"                       => "Almacen",
        "FECHA_INICIA"                  => "Fecha Incapacidad",
        "DOCUMENTO_IDENTIDAD"           => "Documento identidad",

        "AYUDA_SUCURSAL_LABORA"         => "Alamacen en la que labora el empleado",
        "AYUDA_MOTIVO_INCAPACIDAD"      => "Seleccione el motivo de la incapacidad",
        "AYUDA_CANTIDAD_DIAS"           => "Indique la cantidad de dias de la incapacidad",
        "AYUDA_NUMERO_INCAPACIDAD"      => "Numero de incapacidad proporcionado por la EPS",
        "AYUDA_TIPO_TRANSACCION"        => "Seleccionar el tipo de transaccion para la incapacidad",
        "ERROR_DATOS_VACIOS_JS"         => "Error, los campos Nombre del empleado, Dias de incapacidad, Tipo transaccion y Almacen no pueden quedar vacios",
        "ERROR_DATOS_VACIOS_JS2"        => "Error, el campo Dias de incapacidad no puede quedar vacio",
        "ERROR_TABLA_VACIA"             => "Error, la tabla de incapacidades no tiene datos",
        "ELIMINAR_TODOS"                => "Eliminar todos",
        "FECHA_INCAPACIDAD_INICIO"      => "Fecha inicio incapacidad",
        "GENERAR_FECHAS"                => "Generar Fechas",

        "ANEXO_CONTABLE"                => "Anexo contable",
        "AUXILIAR_CONTABLE"             => "Auxiliar contable",
        "AYUDA_ANEXO_CONTABLE"          => "Seleccione un anexo contable",
        "AYUDA_AUXILIAR_CONTABLE"       => "Seleccione un auxiliar contable",

        "MOTIVOS_DIFERENTES"            => "No se puede generar la prorroga porque los motivos de incapacidad del dia anterior al de la Fecha de Inicio son diferentes",
        "NO_EXISTE_INCAPACIDAD"         => "No se puede generar la prorroga porque no existe incapacidad el dia anterior al de la Fecha de Inicio",
        "EXISTE_CRUCE"                  => "En el rango de fechas generadas ya existe una o varias incapacidades reportadas en la base de datos o en la tabla",
        "DIAS_CERO"                     => "Los dias no pueden ser iguales a 0 (cero), verifique",
        "DIAS_3"                        => "Los dias no pueden ser mayores a 3 en esta transaccion, verifique",
        "DIAS_89"                       => "Los dias no pueden ser mayores a 89 en esta transaccion, verifique",
        "DIAS_179"                      => "Los dias no pueden ser mayores a 179 en esta transaccion, verifique",
        "DIAS_180"                      => "Los dias no pueden ser menores a 180 en esta transaccion, verifique",

        "VALOR_DIA"                     => "Valor del dia",
        "DIVIDENDO"                     => "Dividendo",
        "DIVISOR"                       => "Divisor",
        "VALOR_MOVIMIENTO"              => "Valor movimiento",
        "ERROR_FECHA_MENOR"             => "Error, la fecha de inicio no puede ser menor a la fecha de reporte",
        "FECHA_INGRESO_NO_PERMITIDA"    => "Error, la fecha de reporte de incapacidad no puede ser menor a la fecha del ingreso del empleado al almacen",
        "NO_MODIFICAR_NO_ELIMINAR"      => "Error, El registro ya fue leido para salario y/o pagado.\nNo se permite modificar ni eliminar",

        //Reporte
        "SUCURSAL"                      => "Almacen",
        "AYUDA_SUCURSAL"                => "Marque la casilla de verificación si desea listar información de este almacen",
        "EMPLEADO"                      => "Empleado",
        "DESDE_HASTA"                   => "Fecha desde&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha hasta",
        "DOCUMENTO_DESDE"               => "Documento desde",
        "DOCUMENTO_HASTA"               => "Documento hasta",
        "ERROR_DOCUMENTO_IDENTIDAD_1"   => "Debe digitar el documento hasta si quiere usar el documento desde",
        "NO_DIAS"                       => "No. Dias",
        "FECHA_INICIAL"                 => "Fecha inicial",
        "FECHA_FINAL"                   => "Fecha final",
        "ERROR_PREFERENCIA_VACIA"       => "La preferencia global para la incapacidad tres dias se encuentra vacia o no existe.\nFavor verifique",
        "APELLIDO_NOMBRE"               => "Apellido-Nombre",
        "NOMBRE_APELLIDO"               => "Nombre-Apellido",
        "CEDULA"                        => "Documento",
        "PDF"                           => "Pdf",
        "PLANO"                         => "Plano para excel",
        "ORDEN_EMPLEADO"                => "Orden empleado",
        "AYUDA_ORDEN_EMPLEADO"          => "Seleccione el orden en que aparecen los empleados",
        "TIPO_LISTADO"                  => "Tipo listado",
        "AYUDA_TIPO_LISTADO"            => "Seleccion el tipo de listado a generar",
        "TOTAL_SUCURSAL"                => "Total almacen",
        "ARCHIVO_GENERADO"              => "Archivo generado satisfactoriamente",
        "ERROR_SUCURSAL_VACIA"          => "Seleccione un almacen",
        "ERROR_FECHA_VACIA"             => "Seleccione las fechas para listar la información",
        "TOTAL_EMPLEADO"                => "Total empleado",

    );
?>
