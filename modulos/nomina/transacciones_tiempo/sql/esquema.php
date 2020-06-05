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
* de APTITUD PARA UN PROPÓITO DETERMINADO. Consulte los detalles de
* la Licencia Pública General GNU para obtener una información más
* detallada.
*
* Debería haber recibido una copia de la Licencia Pública General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

// Definición de tablas
$tablas["transacciones_tiempo"]=array(
    "codigo"                             => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "nombre"                             => "VARCHAR(40) NOT NULL COMMENT 'Descripcion corta de la transacción'",
    "descripcion"                        => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe la transacción'",
    "codigo_concepto_transaccion_tiempo" => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla conceptos_transacciones_tiempo'",
    "tasa"                               => "DECIMAL(7,4) NOT NULL DEFAULT '0' COMMENT 'Porcentaje que corresponde sobre la hora de salario'",
    "codigo_transaccion_contable"        => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno que identifica la transaccion contable '",
    "resta_salario"                      => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "resta_auxilio_transporte"           => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "resta_cesantias"                    => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "resta_prima"                        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "resta_vacaciones"                   => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "extras_empleado"                    => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Estara activo para el empleado 0->No 1->Si'",
    "dividendo"                          => "SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Valor para liquidar incapacidades tipo de concepto: incapacidades'",
    "divisor"                            => "SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Valor sobre el que se divide para liquidar incapacidades tipo de concepto: incapacidades'"
);

// Definición de llaves primarias
$llavesPrimarias["transacciones_tiempo"] = "codigo";

// Definición de campos únicos
$llavesUnicas["transacciones_tiempo"] = array(
    "descripcion"
);

// Definición de llaves foraneas
$llavesForaneas["transacciones_tiempo"] = array(
    array(
        // Nombre de la llave foranea
        "transaccion_tiempo_transaccion_contable",
        // Nombre del campo en la tabla actual
        "codigo_transaccion_contable",
        // Nombre de la tabla relacionada
        "transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave foranea
        "concepto_transaccion_tiempo",
        // Nombre del campo en la tabla actual
        "codigo_concepto_transaccion_tiempo",
        // Nombre de la tabla relacionada
        "conceptos_transacciones_tiempo",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

$registros["transacciones_tiempo"]=array(
    array(
        "codigo"                             => 0,
        "nombre"                             => "",
        "descripcion"                        => "",
        "codigo_concepto_transaccion_tiempo" => 1,
        "tasa"                               => 0,
        "codigo_transaccion_contable"        => 0,
        "resta_salario"                      => "0",
        "resta_auxilio_transporte"           => "0",
        "resta_cesantias"                    => "0",
        "resta_prima"                        => "0",
        "resta_vacaciones"                   => "0",
        "extras_empleado"                    => "0",
        "dividendo"                          => 0,
        "divisor"                            => 0
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTTRTI",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "1",
        "carpeta"       => "transacciones_tiempo",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICTRTI",
        "padre"         => "GESTTRTI",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "transacciones_tiempo",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSTRTI",
        "padre"         => "GESTTRTI",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "transacciones_tiempo",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODITRTI",
        "padre"         => "GESTTRTI",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "transacciones_tiempo",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMTRTI",
        "padre"         => "GESTTRTI",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "transacciones_tiempo",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTTRTI",
        "padre"         => "GESTTRTI",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "transacciones_tiempo",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);
/*** Definición de tablas ***/
$tablas["conceptos_transacciones_tiempo"] = array(
    "codigo"      => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'código interno que identifica la transacción de tiempo'",
    "descripcion" => "VARCHAR(255) NOT NULL COMMENT 'Descripcion del concepto contable'",
    "tipo"        => "ENUM('1','2','3','4','5') NOT NULL DEFAULT '1' COMMENT '1-> Horas laborales 2-> Licencias-ausencias-permisos no remunerados 3-> Incapacidades 4-> Licencias-ausencias-permisos remunerados 5-> Vacaciones'"
);

/*** Definición de llaves primarias ***/
$llavesPrimarias["conceptos_transacciones_tiempo"] = "codigo";

/*** Definición de campos únicos ***/
$llavesUnicas["conceptos_transacciones_tiempo"] = array(
    "descripcion"
);


/*** Inserción de datos iniciales***/
$registros["conceptos_transacciones_tiempo"] = array(
    array(
        "codigo"          => 1,
        "descripcion"     => "Horas Normales",
        "tipo"            => "1"
    ),
    array(
        "codigo"      => 2,
        "descripcion" => "Horas Extras",
        "tipo"        => "1"
    ),
    array(
        "codigo"          => 3,
        "descripcion" => "Recargos - Nocturnos",
        "tipo"        => "1"
    ),
    array(
        "codigo"          => 4,
        "descripcion" => "Dominical - Festivos",
        "tipo"        => "1"
    ),
    array(
        "codigo"          => 5,
        "descripcion" => "Extras - Nocturnas",
        "tipo"        => "1"
    ),
    array(
        "codigo"          => 6,
        "descripcion" => "Extras-festivos",
        "tipo"        => "1"
    ),
    array(
        "codigo"          => 7,
        "descripcion" => "Extras-festivas-nocturnas",
        "tipo"        => "1"
    ),
    array(
        "codigo"          => 8,
        "descripcion" => "Festivo-nocturno",
        "tipo"        => "1"
    ),
    array(
        "codigo"          => 9,
        "descripcion" => "Licencias",
        "tipo"        => "2"
    ),
    array(
        "codigo"          => 10,
        "descripcion" => "Suspensiones",
        "tipo"        => "2"
    ),
    array(
        "codigo"          => 11,
        "descripcion" => "Ausencias",
        "tipo"        => "2"
    ),
    array(
        "codigo"          => 12,
        "descripcion" => "Permiso-remunerado",
        "tipo"        => "2"
    ),
    array(
        "codigo"          => 13,
        "descripcion" => "Vacaciones",
        "tipo"        => "5"
    ),
    array(
        "codigo"          => 14,
        "descripcion" => "Incapacidad-tres-días",
        "tipo"        => "3"
    ),
    array(
        "codigo"          => 15,
        "descripcion" => "Incapacidad-general-ambulatoria",
        "tipo"        => "3"
    ),
    array(
        "codigo"          => 16,
        "descripcion" => "Incapacidad-general-prorroga hasta 89 dias",
        "tipo"        => "3"
    ),
    array(
        "codigo"          => 17,
        "descripcion" => "Incapacidad-general-prorroga hasta 179 dias",
        "tipo"        => "3"
    ),
    array(
        "codigo"          => 18,
        "descripcion" => "Incapacidad-general-prorroga mayor o igual a 180 dias",
        "tipo"        => "3"
    ),
    array(
        "codigo"          => 19,
        "descripcion" => "Incapacidad-hospitalaria",
        "tipo"        => "3"
    ),
    array(
        "codigo"          => 20,
        "descripcion" => "Incapacidad-atep",
        "tipo"        => "3"
    ),
    array(
        "codigo"          => 21,
        "descripcion" => "Licencia-maternidad",
        "tipo"        => "4"
    ),
    array(
        "codigo"          => 22,
        "descripcion" => "Licencia-paternidad",
        "tipo"        => "4"
    ),
    array(
        "codigo"          => 23,
        "descripcion" => "Licencia de luto",
        "tipo"        => "4"
    ),
    array(
        "codigo"      => 24,
        "descripcion" => "Dia compesatorio",
        "tipo"        => "4"
    )
);


$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_transacciones_tiempo AS
        SELECT job_transacciones_tiempo.codigo AS id,
        job_transacciones_tiempo.codigo AS CODIGO,
        job_transacciones_tiempo.nombre AS NOMBRE,
        job_conceptos_transacciones_tiempo.descripcion AS CONCEPTO_TIEMPO,
        job_transacciones_contables_empleado.descripcion AS TRANSACCION_CONTABLE,
        CONCAT(job_plan_contable.codigo_contable,' - ',job_plan_contable.descripcion) AS CUENTA
        FROM job_transacciones_contables_empleado, job_transacciones_tiempo, job_conceptos_transacciones_tiempo,job_plan_contable
        WHERE job_transacciones_tiempo.codigo_transaccion_contable =  job_transacciones_contables_empleado.codigo
        AND job_transacciones_tiempo.codigo_concepto_transaccion_tiempo = job_conceptos_transacciones_tiempo.codigo
        AND job_transacciones_contables_empleado.codigo_contable = job_plan_contable.codigo_contable
        AND job_transacciones_tiempo.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_transacciones_tiempo AS
    SELECT job_transacciones_tiempo.codigo AS id,
    job_transacciones_tiempo.nombre AS NOMBRE,
    job_conceptos_transacciones_tiempo.descripcion AS CONCEPTO_TIEMPO,
    job_transacciones_contables_empleado.descripcion AS TRANSACCION_CONTABLE,
    CONCAT(job_plan_contable.codigo_contable,' - ',job_plan_contable.descripcion) AS CUENTA
    FROM job_transacciones_contables_empleado, job_transacciones_tiempo, job_conceptos_transacciones_tiempo,job_plan_contable
    WHERE job_transacciones_tiempo.codigo_transaccion_contable =  job_transacciones_contables_empleado.codigo
    AND job_transacciones_tiempo.codigo_concepto_transaccion_tiempo = job_conceptos_transacciones_tiempo.codigo
    AND job_transacciones_contables_empleado.codigo_contable = job_plan_contable.codigo_contable
    AND job_transacciones_tiempo.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_transacciones_tiempo AS
        SELECT codigo AS id,
        CONCAT(nombre,'|',codigo) AS nombre
        FROM job_transacciones_tiempo
        ORDER BY codigo;"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_transacciones_tiempo;
    DROP TABLE IF EXISTS job_buscador_transacciones_tiempo;
    DROP TABLE IF EXISTS job_seleccion_transacciones_tiempo;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_transacciones_tiempo AS
    SELECT job_transacciones_tiempo.codigo AS id,
    job_transacciones_tiempo.nombre AS NOMBRE,
    job_conceptos_transacciones_tiempo.descripcion AS CONCEPTO_TIEMPO,
    job_transacciones_contables_empleado.nombre AS TRANSACCION_CONTABLE
    FROM job_transacciones_contables_empleado, job_transacciones_tiempo, job_conceptos_transacciones_tiempo
    WHERE job_transacciones_tiempo.codigo_transaccion_contable =  job_transacciones_contables_empleado.codigo
    AND job_transacciones_tiempo.codigo_concepto_transaccion_tiempo = job_conceptos_transacciones_tiempo.codigo
    AND job_transacciones_tiempo.codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_transacciones_tiempo AS
    SELECT job_transacciones_tiempo.codigo AS id,
    job_transacciones_tiempo.nombre AS NOMBRE,
    job_conceptos_transacciones_tiempo.descripcion AS CONCEPTO_TIEMPO,
    job_transacciones_contables_empleado.nombre AS TRANSACCION_CONTABLE
    FROM job_transacciones_contables_empleado, job_transacciones_tiempo, job_conceptos_transacciones_tiempo
    WHERE job_transacciones_tiempo.codigo_transaccion_contable =  job_transacciones_contables_empleado.codigo
    AND job_transacciones_tiempo.codigo_concepto_transaccion_tiempo = job_conceptos_transacciones_tiempo.codigo
    AND job_transacciones_tiempo.codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_transacciones_tiempo AS
    SELECT codigo AS id,
    CONCAT(nombre,'|',codigo) AS nombre
    FROM job_transacciones_tiempo
    ORDER BY codigo;
***/
?>
