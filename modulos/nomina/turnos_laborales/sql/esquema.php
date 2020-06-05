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
$tablas["turnos_laborales"] = array(
    "codigo"                        => "SMALLINT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo del turno laboral'",
    "descripcion"                   => "VARCHAR(250) NOT NULL COMMENT 'Id de la tabla de terceros'",
    "permite_festivos"              => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Permite festivos 0->No 1->Si'",
    "paga_dominical"                => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Paga dominical 0->No 1->Si'",
    "paga_festivo"                  => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Paga festivo 0->No 1->Si'",
    /////////////////////////// LUNES ///////////////////////////////////////////////////////////////////////////////
    "tipo_turno_lunes"              => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Jornada continua 0->No 1->Si'",
    "dia_descanso_lunes"            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Dia de descanso 0->No 1->Si'",
    "hora_inicial_turno1_lunes"     => "TIME NULL COMMENT 'Hora en que inicia el turno en la primera parte'",
    "hora_final_turno1_lunes"       => "TIME NULL COMMENT 'Hora en que finaliza el turno en la primera parte'",
    "hora_inicial_turno2_lunes"     => "TIME NULL COMMENT 'Hora en que inicia el turno en la segunda parte'",
    "hora_final_turno2_lunes"       => "TIME NULL COMMENT 'Hora en que finaliza el turno en la segunda parte'",
    /////////////////////////// MARTES //////////////////////////////////////////////////////////////////////////////
    "tipo_turno_martes"             => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Jornada continua 0->No 1->Si'",
    "dia_descanso_martes"           => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Dia de descanso 0->No 1->Si'",
    "hora_inicial_turno1_martes"    => "TIME NULL COMMENT 'Hora en que inicia el turno en la primera parte'",
    "hora_final_turno1_martes"      => "TIME NULL COMMENT 'Hora en que finaliza el turno en la primera parte'",
    "hora_inicial_turno2_martes"    => "TIME NULL COMMENT 'Hora en que inicia el turno en la segunda parte'",
    "hora_final_turno2_martes"      => "TIME NULL COMMENT 'Hora en que finaliza el turno en la segunda parte'",
    /////////////////////////// MIERCOLES ///////////////////////////////////////////////////////////////////////////
    "tipo_turno_miercoles"          => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Jornada continua 0->No 1->Si'",
    "dia_descanso_miercoles"        => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Dia de descanso 0->No 1->Si'",
    "hora_inicial_turno1_miercoles" => "TIME NULL COMMENT 'Hora en que inicia el turno en la primera parte'",
    "hora_final_turno1_miercoles"   => "TIME NULL COMMENT 'Hora en que finaliza el turno en la primera parte'",
    "hora_inicial_turno2_miercoles" => "TIME NULL COMMENT 'Hora en que inicia el turno en la segunda parte'",
    "hora_final_turno2_miercoles"   => "TIME NULL COMMENT 'Hora en que finaliza el turno en la segunda parte'",
    /////////////////////////// JUEVES //////////////////////////////////////////////////////////////////////////////
    "tipo_turno_jueves"             => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Jornada continua 0->No 1->Si'",
    "dia_descanso_jueves"           => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Dia de descanso 0->No 1->Si'",
    "hora_inicial_turno1_jueves"    => "TIME NULL COMMENT 'Hora en que inicia el turno en la primera parte'",
    "hora_final_turno1_jueves"      => "TIME NULL COMMENT 'Hora en que finaliza el turno en la primera parte'",
    "hora_inicial_turno2_jueves"    => "TIME NULL COMMENT 'Hora en que inicia el turno en la segunda parte'",
    "hora_final_turno2_jueves"      => "TIME NULL COMMENT 'Hora en que finaliza el turno en la segunda parte'",
    /////////////////////////// VIERNES /////////////////////////////////////////////////////////////////////////////
    "tipo_turno_viernes"            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Jornada continua 0->No 1->Si'",
    "dia_descanso_viernes"          => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Dia de descanso 0->No 1->Si'",
    "hora_inicial_turno1_viernes"   => "TIME NULL COMMENT 'Hora en que inicia el turno en la primera parte'",
    "hora_final_turno1_viernes"     => "TIME NULL COMMENT 'Hora en que finaliza el turno en la primera parte'",
    "hora_inicial_turno2_viernes"   => "TIME NULL COMMENT 'Hora en que inicia el turno en la segunda parte'",
    "hora_final_turno2_viernes"     => "TIME NULL COMMENT 'Hora en que finaliza el turno en la segunda parte'",
    /////////////////////////// SABADO //////////////////////////////////////////////////////////////////////////////
    "tipo_turno_sabado"             => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Jornada continua 0->No 1->Si'",
    "dia_descanso_sabado"           => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Dia de descanso 0->No 1->Si'",
    "hora_inicial_turno1_sabado"    => "TIME NULL COMMENT 'Hora en que inicia el turno en la primera parte'",
    "hora_final_turno1_sabado"      => "TIME NULL COMMENT 'Hora en que finaliza el turno en la primera parte'",
    "hora_inicial_turno2_sabado"    => "TIME NULL COMMENT 'Hora en que inicia el turno en la segunda parte'",
    "hora_final_turno2_sabado"      => "TIME NULL COMMENT 'Hora en que finaliza el turno en la segunda parte'",
    /////////////////////////// DOMINGO /////////////////////////////////////////////////////////////////////////////
    "tipo_turno_domingo"            => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Jornada continua 0->No 1->Si'",
    "dia_descanso_domingo"          => "ENUM('0','1') NOT NULL DEFAULT '0' COMMENT 'Dia de descanso 0->No 1->Si'",
    "hora_inicial_turno1_domingo"   => "TIME NULL COMMENT 'Hora en que inicia el turno en la primera parte'",
    "hora_final_turno1_domingo"     => "TIME NULL COMMENT 'Hora en que finaliza el turno en la primera parte'",
    "hora_inicial_turno2_domingo"   => "TIME NULL COMMENT 'Hora en que inicia el turno en la segunda parte'",
    "hora_final_turno2_domingo"     => "TIME NULL COMMENT 'Hora en que finaliza el turno en la segunda parte'"
);

// Definición de llaves primarias
$llavesPrimarias["turnos_laborales"] = "codigo";


// Inserción de datos iniciales
$registros["turnos_laborales"] = array(
    array(
        "codigo"                => "0",
        "descripcion"           => "",
        "permite_festivos"      => "0",
        "paga_dominical"        => "0",
        "paga_festivo"          => "0"
    )
);

$registros["componentes"] = array(
    array(
        "id"            => "GESTTULA",
        "padre"         => "SUBMDCRH",
        "id_modulo"     => "NOMINA",
        "orden"         => "50",
        "visible"       => "1",
        "carpeta"       => "turnos_laborales",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ADICTULA",
        "padre"         => "GESTTULA",
        "id_modulo"     => "NOMINA",
        "orden"         => "10",
        "visible"       => "0",
        "carpeta"       => "turnos_laborales",
        "global"        => "0",
        "archivo"       => "adicionar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "CONSTULA",
        "padre"         => "GESTTULA",
        "id_modulo"     => "NOMINA",
        "orden"         => "25",
        "visible"       => "0",
        "carpeta"       => "turnos_laborales",
        "global"        => "0",
        "archivo"       => "consultar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "MODITULA",
        "padre"         => "GESTTULA",
        "id_modulo"     => "NOMINA",
        "orden"         => "30",
        "visible"       => "0",
        "carpeta"       => "turnos_laborales",
        "global"        => "0",
        "archivo"       => "modificar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "ELIMTULA",
        "padre"         => "GESTTULA",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "turnos_laborales",
        "global"        => "0",
        "archivo"       => "eliminar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    ),
    array(
        "id"            => "LISTTULA",
        "padre"         => "GESTTULA",
        "id_modulo"     => "NOMINA",
        "orden"         => "40",
        "visible"       => "0",
        "carpeta"       => "turnos_laborales",
        "global"        => "0",
        "archivo"       => "listar",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
    )
);

$vistas = array(
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_turnos_laborales AS
        SELECT codigo AS id,
        codigo AS CODIGO,
        descripcion AS DESCRIPCION
        FROM job_turnos_laborales
        WHERE codigo > 0;"
    ),
    array(
        "CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_turnos_laborales AS
        SELECT codigo AS id,
        descripcion AS DESCRIPCION
        FROM job_turnos_laborales
        WHERE codigo > 0;"
    )
);
/***
    DROP TABLE IF EXISTS job_menu_turnos_laborales;
    DROP TABLE IF EXISTS job_buscador_turnos_laborales;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_menu_turnos_laborales AS
    SELECT codigo AS id,
    codigo AS CODIGO,
    descripcion AS DESCRIPCION,
    CONCAT('ESTADO_',tipo_turno) AS TIPO_TURNO
    FROM job_turnos_laborales
    WHERE codigo > 0;

    CREATE OR REPLACE ALGORITHM = MERGE VIEW job_buscador_turnos_laborales AS
    SELECT codigo AS id,
    descripcion AS DESCRIPCION
    FROM job_turnos_laborales
    WHERE codigo > 0;

***/
?>
