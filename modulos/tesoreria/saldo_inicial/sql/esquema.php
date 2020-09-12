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
* de APTITUD PARA UN PROPÓSITO DETERMINADO. Consulte los detalles de
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
$tablas["saldo_inicial_cuentas"]  = array(
    "codigo"                      => "INT(9) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo interno asignado por el usuario'",
    "cuenta_origen"               => "VARCHAR(30) NULL COMMENT 'Numero de la cuenta bancaria origen'",
    "saldo"                       => "DECIMAL(15,4) UNSIGNED  NOT NULL COMMENT 'Valor total del movimiento'",
    "fecha_saldo"                 => "DATE NOT NULL COMMENT 'Fecha inicio del saldo'",
    "fecha_registra"              => "DATETIME NOT NULL COMMENT 'Fecha ingreso al sistema'",
    "codigo_usuario_registra"     => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Id del usuario que genera el registro'",
    "observaciones"               => "VARCHAR(255) COMMENT 'Observacion general del movimiento'"
);

// Definición de llaves primarias
$llavesPrimarias["saldo_inicial_cuentas"] = "codigo";

// Definición de las llaves unicas
$llavesUnicas["saldo_inicial_cuentas"] = array(
    "codigo,cuenta_origen,saldo,fecha_saldo"
);

$registros["componentes"] = array(
    array(
        "id"              => "GESTSITE",
        "padre"           => "SUBMDCTE",
        "id_modulo"       => "TESORERIA",
        "orden"           => "500",
        "carpeta"         => "saldo_inicial",
        "archivo"         => "menu",
        "requiere_item"   => "1",
        "tabla_principal" => "saldo_inicial_cuentas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ADICSIMT",
        "padre"           => "GESTSITE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "10",
        "carpeta"         => "saldo_inicial",
        "archivo"         => "adicionar",
        "requiere_item"   => "0",
        "tabla_principal" => "saldo_inicial_cuentas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "CONSSIMT",
        "padre"           => "GESTSITE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "20",
        "carpeta"         => "saldo_inicial",
        "archivo"         => "consultar",
        "requiere_item"   => "1",
        "tabla_principal" => "saldo_inicial_cuentas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "MODISIMT",
        "padre"           => "GESTSITE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "30",
        "carpeta"         => "saldo_inicial",
        "archivo"         => "modificar",
        "requiere_item"   => "1",
        "tabla_principal" => "saldo_inicial_cuentas",
        "tipo_enlace"     => "1"
    ),
    array(
        "id"              => "ELIMSIMT",
        "padre"           => "GESTSITE",
        "id_modulo"       => "TESORERIA",
        "visible"         => "0",
        "orden"           => "40",
        "carpeta"         => "saldo_inicial",
        "archivo"         => "eliminar",
        "requiere_item"   => "1",
        "tabla_principal" => "saldo_inicial_cuentas",
        "tipo_enlace"     => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_saldo_inicial_cuentas AS
        SELECT job_saldo_inicial_cuentas.codigo AS id,
            job_saldo_inicial_cuentas.codigo AS CODIGO,
            job_saldo_inicial_cuentas.cuenta_origen AS CUENTA_ORIGEN,
            FORMAT(job_saldo_inicial_cuentas.saldo,0) AS SALDO_INICIAL,
            job_saldo_inicial_cuentas.fecha_saldo AS FECHA_SALDO,
            job_saldo_inicial_cuentas.observaciones AS OBSERVACIONES
        FROM job_saldo_inicial_cuentas
        WHERE job_saldo_inicial_cuentas.codigo != 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_seleccion_saldo_inicial_cuentas AS
        SELECT job_saldo_inicial_cuentas.codigo AS id,
            job_saldo_inicial_cuentas.codigo AS codigo,
            job_saldo_inicial_cuentas.cuenta_origen AS cuenta_origen,
            FORMAT(job_saldo_inicial_cuentas.saldo,0) AS saldo_inicial,
            job_saldo_inicial_cuentas.fecha_saldo AS fecha_saldo,
            job_saldo_inicial_cuentas.observaciones AS observaciones
        FROM job_saldo_inicial_cuentas
        WHERE job_saldo_inicial_cuentas.codigo != 0;"
    )
)
?>
