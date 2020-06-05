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

$borrarSiempre = false;

// Definición de tablas
$tablas["gastos_prestaciones_sociales"] = array(
    "codigo"                               => "INT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del registro'",
    "descripcion"                          => "VARCHAR(255) NOT NULL COMMENT 'Descripcion del registro'",
    "cesantia_pago_prestacion"             => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "cesantia_pago_gasto"                  => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "cesantia_traslado_fondo"              => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "cesantia_causacion_prestacion"        => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "cesantia_causacion_gasto"             => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "intereses_pago_prestacion"            => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "intereses_pago_gasto"                 => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "intereses_causacion_prestacion"       => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "intereses_causacion_gasto"            => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "prima_pago_prestacion"                => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "prima_pago_gasto"                     => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "prima_causacion_prestacion"           => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "prima_causacion_gasto"                => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "vacacion_pago_prestacion_disfrute"    => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "vacacion_pago_prestacion_liquidacion" => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "vacacion_pago_gasto_disfrute"         => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "vacacion_pago_gasto_liquidacion"      => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "vacacion_causacion_prestacion"        => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'",
    "vacacion_causacion_gasto"             => "INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Codigo de la transaccion contable'"
);

// Definición de llaves primarias
$llavesPrimarias["gastos_prestaciones_sociales"] = "codigo";

// Definición de campos únicos
$llavesUnicas["gastos_prestaciones_sociales"] = array(
    "descripcion"
);

$registros["gastos_prestaciones_sociales"] = array(
    array(
        "codigo"      => "0",
        "descripcion" => ""
    )
);

// Definicion de llaves Foraneas
$llavesForaneas["gastos_prestaciones_sociales"] = array(
   array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_1",
        // Nombre del campo en la tabla actual
        "cesantia_pago_prestacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_2",
        // Nombre del campo en la tabla actual
        "cesantia_pago_gasto",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_3",
        // Nombre del campo en la tabla actual
        "cesantia_traslado_fondo",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_4",
        // Nombre del campo en la tabla actual
        "cesantia_causacion_prestacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_5",
        // Nombre del campo en la tabla actual
        "cesantia_causacion_gasto",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_6",
        // Nombre del campo en la tabla actual
        "prima_pago_prestacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_7",
        // Nombre del campo en la tabla actual
        "prima_pago_gasto",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_8",
        // Nombre del campo en la tabla actual
        "prima_causacion_prestacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_9",
        // Nombre del campo en la tabla actual
        "prima_causacion_gasto",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_transacciones_contables_10_1",
        // Nombre del campo en la tabla actual
        "vacacion_pago_prestacion_disfrute",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_transacciones_contables_10_2",
        // Nombre del campo en la tabla actual
        "vacacion_pago_prestacion_liquidacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_transacciones_contables_11_1",
        // Nombre del campo en la tabla actual
        "vacacion_pago_gasto_disfrute",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_transacciones_contables_11_2",
        // Nombre del campo en la tabla actual
        "vacacion_pago_gasto_liquidacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_12",
        // Nombre del campo en la tabla actual
        "vacacion_causacion_prestacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_13",
        // Nombre del campo en la tabla actual
        "vacacion_causacion_gasto",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_14",
        // Nombre del campo en la tabla actual
        "intereses_pago_prestacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_15",
        // Nombre del campo en la tabla actual
        "intereses_pago_gasto",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_16",
        // Nombre del campo en la tabla actual
        "intereses_causacion_prestacion",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "gastos_prestaciones_sociales_transacciones_contables_empleado_17",
        // Nombre del campo en la tabla actual
        "intereses_causacion_gasto",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTCGPS",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "1",
        "carpeta"       => "gastos_prestaciones_sociales",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICCGPS",
        "padre"         => "GESTCGPS",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "gastos_prestaciones_sociales",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSCGPS",
        "padre"         => "GESTCGPS",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "gastos_prestaciones_sociales",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODICGPS",
        "padre"         => "GESTCGPS",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "gastos_prestaciones_sociales",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMCGPS",
        "padre"         => "GESTCGPS",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "gastos_prestaciones_sociales",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_gastos_prestaciones_sociales AS
            SELECT  codigo AS id,
                    codigo AS CODIGO,
                    descripcion AS DESCRIPCION
            FROM    job_gastos_prestaciones_sociales WHERE codigo != 0;"
    ),
    array("CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_gastos_prestaciones_sociales AS
            SELECT  codigo AS id,
                    codigo AS CODIGO,
                    descripcion AS DESCRIPCION
            FROM    job_gastos_prestaciones_sociales WHERE codigo != 0;"
    )
);
?>
