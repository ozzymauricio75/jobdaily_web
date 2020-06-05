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
* modificarlo  bajo los t�rminos de la Licencia P�blica General GNU
* publicada por la Fundaci�n para el Software Libre, ya sea la versi�n 3
* de la Licencia, o (a su elecci�n) cualquier versi�n posterior.
*
* Este programa se distribuye con la esperanza de que sea �til, pero
* SIN GARANT�A ALGUNA; ni siquiera la garant�a impl�cita MERCANTIL o
* de APTITUD PARA UN PROP�SITO DETERMINADO. Consulte los detalles de
* la Licencia P�blica General GNU para obtener una informaci�n m�s
* detallada.
*
* Deber�a haber recibido una copia de la Licencia P�blica General GNU
* junto a este programa. En caso contrario, consulte:
* <http://www.gnu.org/licenses/>.
*
**/

$borrarSiempre = false;

// Definici�n de tablas
$tablas["transacciones_contables_empleado"] = array(
    "codigo"                               => "INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo interno para la base de datos'",
    "nombre"                               => "VARCHAR(40) NOT NULL COMMENT 'Detalle corto del tipo de transacci�n'",
    "descripcion"                          => "VARCHAR(255) NOT NULL COMMENT 'Detalle que describe la transaccio�n'",
    "codigo_contable"                      => "VARCHAR(15)  NOT NULL COMMENT 'Codigo del plan contable'",
    "sentido"                              => "ENUM('C','D') NOT NULL DEFAULT 'C' COMMENT 'C->Credito D->Debito'",
    "codigo_concepto_transaccion_contable" => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la tabla conceptos_transacciones_contables'",
    "acumula_cesantias"                    => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "acumula_prima"                        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "acumula_vacaciones"                   => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "ibc_salud"                            => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si 2->Si mayor del 40%'",
    "ibc_pension"                          => "ENUM('0','1','2') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si 2->Si mayor del 40%'",
    "ibc_arp"                              => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "ibc_icbf"                             => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "ibc_caja_compensacion"                => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "ibc_sena"                             => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT '0->No 1->Si'",
    "certificado_ingresos_retenciones"     => "VARCHAR(4) NULL COMMENT 'N�mero de �tem del certificado de ingresos y retenciones'",
    "tipo_retencion"                       => "ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT '1->No 2->Retenci�n salarios 3->Retenci�n vacaciones'",
    "columna_planilla"                     => "VARCHAR(10) NOT NULL COMMENT 'Columna en la planilla de pagos'"
);

// Definici�n de llaves primarias
$llavesPrimarias["transacciones_contables_empleado"] = "codigo";

// Definici�n de campos �nicos
$llavesUnicas["transacciones_contables_empleado"] = array(
    "descripcion"
);

// Definici�n de llaves Foraneas
$llavesForaneas["transacciones_contables_empleado"] = array(
   array(
        // Nombre de la llave foranea
        "transaccion_empleado_codigo_contable",
        // Nombre del campo en la tabla actual
        "codigo_contable",
        // Nombre de la tabla relacionada
        "plan_contable",
        // Nombre del campo de la tabla relacionada
        "codigo_contable"
    ),
    array(
        // Nombre de la llave foranea
        "transaccion_empleado_concepto_contable",
        // Nombre del campo en la tabla actual
        "codigo_concepto_transaccion_contable",
        // Nombre de la tabla relacionada
        "conceptos_transacciones_contables_empleado",
        // Nombre del campo de la tabla relacionada
        "codigo"
    )
);

// Insercion de regitro vacio
$registros["transacciones_contables_empleado"] = array(
    array(
        "codigo"                               => 0,
        "nombre"                               => "",
        "descripcion"                          => "",
        "codigo_contable"                      => "",
        "sentido"                              => "D",
        "codigo_concepto_transaccion_contable" => 1,
        "acumula_cesantias"                    => 0,
        "acumula_prima"                        => 0,
        "acumula_vacaciones"                   => 0,
        "ibc_salud"                            => 0,
        "ibc_pension"                          => 0,
        "ibc_arp"                              => 0,
        "ibc_icbf"                             => 0,
        "ibc_caja_compensacion"                => 0,
        "ibc_sena"                             => 0,
        "certificado_ingresos_retenciones"     => "",
        "tipo_retencion"                       => "1",
        "columna_planilla"                     => ""
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTTRCO",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "1",
        "carpeta"       => "transacciones_contables_empleado",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICTRCO",
        "padre"         => "GESTTRCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "transacciones_contables_empleado",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSTRCO",
        "padre"         => "GESTTRCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "20",
        "visible"       => "0",
        "carpeta"       => "transacciones_contables_empleado",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODITRCO",
        "padre"         => "GESTTRCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "transacciones_contables_empleado",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMTRCO",
        "padre"         => "GESTTRCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "transacciones_contables_empleado",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTTRCO",
        "padre"         => "GESTTRCO",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "transacciones_contables_empleado",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);
// Definici�n de tablas
$tablas["conceptos_transacciones_contables_empleado"] = array(
    "codigo"      => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo interno que identifica el concepto de transacci�n contable'",
    "descripcion" => "VARCHAR(255) NOT NULL COMMENT 'Descripci�n del concepto contable'"
);

// Definici�n de llaves primarias
$llavesPrimarias["conceptos_transacciones_contables_empleado"] = "codigo";

// Definici�n de campos �nicos
$llavesUnicas["conceptos_transacciones_contables_empleado"] = array(
    "descripcion"
);

// Inserci�n de datos iniciales
$registros["conceptos_transacciones_contables_empleado"] = array(
    array(
        "codigo" => 1,
        "descripcion" => "Salario"
    ),
    array(
        "codigo" => 2,
        "descripcion" => "Otros-salario"
    ),
    array(
        "codigo" => 3,
        "descripcion" => "Auxilio-transporte"
    ),
    array(
        "codigo" => 4,
        "descripcion" => "Solo-contable"
    ),
    array(
        "codigo" => 5,
        "descripcion" => "Nomina por pagar salud"
    ),
    array(
        "codigo" => 6,
        "descripcion" => "Nomina por pagar pensi�n"
    ),
    array(
        "codigo" => 7,
        "descripcion" => "Aporte solidaridad pensi�n"
    ),
    array(
        "codigo" => 8,
        "descripcion" => "Aporte pensi�n voluntaria"
    ),
    array(
        "codigo" => 9,
        "descripcion" => "Prestamos empresa"
    ),
    array(
        "codigo" => 10,
        "descripcion" => "Bancaria"
    ),
    array(
        "codigo" => 11,
        "descripcion" => "Descuentos empleados de terceros"
    ),
    array(
        "codigo" => 12,
        "descripcion" => "Cesant�a pago prestaci�n"
    ),
    array(
        "codigo" => 13,
        "descripcion" => "Cesant�a pago gasto"
    ),
    array(
        "codigo" => 14,
        "descripcion" => "Cesant�a traslado fondo"
    ),
    array(
        "codigo" => 15,
        "descripcion" => "Cesant�a causaci�n prestaci�n"
    ),
    array(
        "codigo" => 16,
        "descripcion" => "Cesant�a causaci�n gasto"
    ),
    array(
        "codigo" => 17,
        "descripcion" => "Intereses pago prestaci�n"
    ),
    array(
        "codigo" => 18,
        "descripcion" => "Intereses pago gasto"
    ),
    array(
        "codigo" => 19,
        "descripcion" => "Intereses causaci�n prestaci�n"
    ),
    array(
        "codigo" => 20,
        "descripcion" => "Intereses causaci�n gasto"
    ),
    array(
        "codigo" => 21,
        "descripcion" => "Prima pago prestaci�n"
    ),
    array(
        "codigo" => 22,
        "descripcion" => "Prima pago gasto"
    ),
    array(
        "codigo" => 23,
        "descripcion" => "Prima causaci�n prestaci�n"
    ),
    array(
        "codigo" => 24,
        "descripcion" => "Prima causaci�n gasto"
    ),
    array(
        "codigo" => 25,
        "descripcion" => "Vacaci�n pago prestaci�n"
    ),
    array(
        "codigo" => 26,
        "descripcion" => "Vacaci�n pago gasto"
    ),
    array(
        "codigo" => 27,
        "descripcion" => "Vacaci�n causaci�n prestaci�n"
    ),
    array(
        "codigo" => 28,
        "descripcion" => "Vacaci�n causaci�n gasto"
    ),
    array(
        "codigo" => 29,
        "descripcion" => "Licencia maternidad"
    ),
    array(
        "codigo" => 30,
        "descripcion" => "Licencia paternidad"
    ),
    array(
        "codigo" => 31,
        "descripcion" => "Incapacidad tres d�as"
    ),
    array(
        "codigo" => 32,
        "descripcion" => "Incapacidad general ambulatoria"
    ),
    array(
        "codigo" => 33,
        "descripcion" => "Incapacidad general prorroga"
    ),
    array(
        "codigo" => 34,
        "descripcion" => "Incapacidad hospitalaria"
    ),
    array(
        "codigo" => 35,
        "descripcion" => "Incapacidad atep"
    ),
    array(
        "codigo" => 36,
        "descripcion" => "Unidad capitaci�n"
    ),
    array(
        "codigo" => 37,
        "descripcion" => "Retenci�n en la fuente"
    ),
    array(
        "codigo" => 38,
        "descripcion" => "Auxilios"
    ),
    array(
        "codigo" => 39,
        "descripcion" => "Indemnizaci�n"
    ),
    array(
        "codigo"      => 40,
        "descripcion" => "Prestamos de terceros"
    ),
    array(
        "codigo" => 41,
        "descripcion" => "Cancelaci�n nomina por pagar salud"
    ),
    array(
        "codigo" => 42,
        "descripcion" => "Cuenta por pagar salud"
    ),
    array(
        "codigo" => 43,
        "descripcion" => "Pago a entidad de salud"
    ),
    array(
        "codigo" => 44,
        "descripcion" => "Cancelaci�n nomina por pagar pensi�n"
    ),
    array(
        "codigo" => 45,
        "descripcion" => "Cuenta por pagar pensi�n"
    ),
    array(
        "codigo" => 46,
        "descripcion" => "Pago a entidad de pensi�n"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_transacciones_contables_empleado AS
        SELECT job_transacciones_contables_empleado.codigo AS id,
        job_transacciones_contables_empleado.codigo AS CODIGO,
        job_transacciones_contables_empleado.nombre AS NOMBRE,
        job_transacciones_contables_empleado.descripcion AS DESCRIPCION,
        job_conceptos_transacciones_contables_empleado.descripcion AS CONCEPTO_CONTABLE,
        CONCAT(job_plan_contable.codigo_contable,' ',job_plan_contable.descripcion) AS CODIGO_CONTABLE
        FROM job_transacciones_contables_empleado, job_plan_contable, job_conceptos_transacciones_contables_empleado
        WHERE job_transacciones_contables_empleado.codigo_concepto_transaccion_contable = job_conceptos_transacciones_contables_empleado.codigo
        AND job_transacciones_contables_empleado.codigo_contable = job_plan_contable.codigo_contable
        AND job_transacciones_contables_empleado.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_transacciones_contables_empleado AS
        SELECT job_transacciones_contables_empleado.codigo AS id,
        job_transacciones_contables_empleado.codigo AS CODIGO,
        job_transacciones_contables_empleado.nombre AS NOMBRE,
        job_transacciones_contables_empleado.descripcion AS DESCRIPCION,
        job_conceptos_transacciones_contables_empleado.descripcion AS CONCEPTO_CONTABLE,
        CONCAT(job_plan_contable.codigo_contable,' ',job_plan_contable.descripcion) AS CODIGO_CONTABLE
        FROM job_transacciones_contables_empleado, job_plan_contable, job_conceptos_transacciones_contables_empleado
        WHERE job_transacciones_contables_empleado.codigo_concepto_transaccion_contable = job_conceptos_transacciones_contables_empleado.codigo
        AND job_transacciones_contables_empleado.codigo_contable = job_plan_contable.codigo_contable
        AND job_transacciones_contables_empleado.codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_transacciones_contables_empleado AS
        SELECT codigo AS id,
        codigo_contable AS id_codigo_contable,
        CONCAT(codigo_contable,'-',descripcion,'|',codigo) AS nombre
        FROM job_transacciones_contables_empleado
        ORDER BY codigo;"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_transacciones_contables_empleado;
    DROP TABLE IF EXISTS job_buscador_transacciones_contables_empleado;
    DROP TABLE IF EXISTS job_seleccion_transacciones_contables_empleado;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_transacciones_contables_empleado AS
    SELECT job_transacciones_contables_empleado.codigo AS id,
    job_transacciones_contables_empleado.nombre AS NOMBRE,
    job_conceptos_transacciones_contables_empleado.descripcion AS CONCEPTO_CONTABLE,
    CONCAT(job_plan_contable.codigo_contable,' ',job_plan_contable.descripcion) AS CODIGO_CONTABLE
    FROM job_transacciones_contables_empleado, job_plan_contable, job_conceptos_transacciones_contables_empleado
    WHERE job_transacciones_contables_empleado.codigo_concepto_transaccion_contable = job_conceptos_transacciones_contables_empleado.codigo
    AND job_transacciones_contables_empleado.codigo_contable = job_plan_contable.codigo_contable
    AND job_transacciones_contables_empleado.codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_transacciones_contables_empleado AS
    SELECT job_transacciones_contables_empleado.codigo AS id,
    job_transacciones_contables_empleado.nombre AS NOMBRE,
    job_conceptos_transacciones_contables_empleado.descripcion AS CONCEPTO_CONTABLE,
    CONCAT(job_plan_contable.codigo_contable,' ',job_plan_contable.descripcion) AS CODIGO_CONTABLE
    FROM job_transacciones_contables_empleado, job_plan_contable, job_conceptos_transacciones_contables_empleado
    WHERE job_transacciones_contables_empleado.codigo_concepto_transaccion_contable = job_conceptos_transacciones_contables_empleado.codigo
    AND job_transacciones_contables_empleado.codigo_contable = job_plan_contable.codigo_contable
    AND job_transacciones_contables_empleado.codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_transacciones_contables_empleado AS
    SELECT codigo AS id,
    CONCAT(nombre,'|',codigo) AS nombre,
    codigo_contable AS codigo_contable
    FROM job_transacciones_contables_empleado
    ORDER BY codigo;
***/
?>
