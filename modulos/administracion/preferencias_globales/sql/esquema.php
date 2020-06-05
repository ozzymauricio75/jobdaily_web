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

$tablas["preferencias"]   = array(
    "codigo_empresa"   => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave de la tabla empresa'",
    "codigo_sucursal"  => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'Llave de la tabla sucursal'",
    "codigo_usuario"   => "SMALLINT(4) UNSIGNED ZEROFILL  NOT NULL COMMENT 'Llave de la tabla usuario'",
    "tipo_preferencia" => "ENUM('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1-Global, 2-Empresa, 3-Sucursal, 4-Usuario'",
    "variable"         => "VARCHAR(255) NOT NULL COMMENT 'Nombre de la preferencia'",
    "valor"            => "VARCHAR(255) NOT NULL COMMENT 'Valor de la preferencia'"
);

// Definición de llaves primarias
$llavesPrimarias["preferencias"]    = "codigo_empresa,codigo_sucursal,codigo_usuario,tipo_preferencia,variable";


// Definición de llaves foráneas
$llavesForaneas["sucursales"] = array(
    array(
        // Nombre de la llave
        "preferencias_empresas",
        // Nombre del campo clave de la tabla local
        "codigo_empresa",
        // Nombre de la tabla relacionada
        "empresas",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "preferencias_sucursales",
        // Nombre del campo clave de la tabla local
        "codigo_sucursal",
        // Nombre de la tabla relacionada
        "sucursales",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    ),
    array(
        // Nombre de la llave
        "preferencias_usuarios",
        // Nombre del campo clave de la tabla local
        "codigo_usuario",
        // Nombre de la tabla relacionada
        "usuarios",
        // Nombre del campo clave en la tabla relacionada
        "codigo"
    )
);

$registros["componentes"] = array(
    array(
        "id"           => "GESTPREF",
        "padre"        => "SUBMACCE",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "200",
        "carpeta"      => "principal",
        "global"       => "0",
        "requiere_item" => "0",
        "tipo_enlace"  => "1"
    ),
    array(
        "id"           => "PREFGLOB",
        "padre"        => "GESTPREF",
        "id_modulo"    => "ADMINISTRACION",
        "visible"      => "1",
        "orden"        => "100",
        "carpeta"      => "preferencias_globales",
        "archivo"      => "modificar",
        "global"       => "0",
        "tipo_enlace"  => "2"
    )
);
?>
