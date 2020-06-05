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

$borrarSiempre   = false;
/*
$tablas["movimiento_retiro_cesantias"] = array(
    "forma_pago"                                        => "ENUM('1','2','3','4') NOT NULL COMMENT '1->nomina por pagar  2->pago efectivo 3-> cheque por planilla 4-> cheque por empleado'",
    "ano_generacion"                                    => "INT(4) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo interno que identifica la planilla'",
    "mes_generacion"                                    => "INT(2) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo interno que identifica la planilla'",
    "codigo_planilla"                                   => "SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'C�digo interno que identifica la planilla'",
    "periodo_pago"                                      => "ENUM('1','2','3','4','5','6','7','8') NOT NULL COMMENT '1->Mensual 2->Primera quincena 3->Segunda quincena 4->Primera semana 5->Segunda semana 6->Tercer semana 7->Cuarta semana 8->Quinta semana'",
    "fecha_pago_planilla"                               => "DATE NOT NULL COMMENT 'Fecha en la que inicia labores en la la sucursal el empleado'",
    "codigo_sucursal_genera"                            => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'tabla planilla de la tabla de sucursales'",
    "codigo_sucursal_recibe"                            => "MEDIUMINT(5) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la sucursal a la cual se le va a pagar'",
    "autorizado"                                        => "ENUM('0','1') NOT NULL COMMENT '0 -> No 1 -> Si'",
    "pagado"                                            => "ENUM('0','1') NOT NULL COMMENT '0 -> No 1 -> Si'"
);


$llavesPrimarias["forma_pago_planillas"]            = "ano_generacion,mes_generacion,codigo_planilla,periodo_pago,fecha_pago_planilla,codigo_sucursal_recibe";

$llavesForaneas["forma_pago_planillas_sucursal"] = array(
        array(
            //  Nombre de la llave foranea
            "forma_pago_planillas_sucursal_sucursal",
            //  Nombre del campo en la tabla actual
            "codigo_sucursal",
            //  Nombre de la tabla relacionada
            "sucursales",
            //  Nombre del campo de la tabla relacionada
            "codigo"
        ),
         array(
              // Nombre de la llave foranea
             "forma_pago_planillas_sucursal_cuentas_bancarias",
             // Nombre del campo en la tabla actual
             "codigo_sucursal_pertence,tipo_documento_cuenta_bancaria,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero",
             // Nombre de la tabla relacionada
             "cuentas_bancarias",
             // Nombre del campo de la tabla relacionada
             "codigo_sucursal,codigo_tipo_documento,codigo_sucursal_banco,codigo_iso,codigo_dane_departamento,codigo_dane_municipio,codigo_banco,numero"
         ),
        array(
              // Nombre de la llave foranea
             "forma_pago_planillas_sucursal_tipo_documento",
             // Nombre del campo en la tabla actual
             "codigo_tipo_documento",
             // Nombre de la tabla relacionada
             "tipos_documentos",
             // Nombre del campo de la tabla relacionada
             "codigo"
         ),
         array(
              // Nombre de la llave foranea
             "forma_pago_planillas_sucursal_consecutivo_docuemento",
             // Nombre del campo en la tabla actual
             "codigo_sucursal_consecutivo_documento,codigo_tipo_documento_consecutivo_documento,documento_identidad_tercero_consecutivo_documento,fecha_registro_consecutivo_documento,consecutivo_documento",
             // Nombre de la tabla relacionada
             "consecutivo_documentos",
             // Nombre del campo de la tabla relacionada
             "codigo_sucursal,codigo_tipo_documento,documento_identidad_tercero,fecha_registro,consecutivo"
         ),
         array(
              // Nombre de la llave foranea
             "forma_pago_planillas_sucursal_consecutivo_cheque",
             // Nombre del campo en la tabla actual
             "codigo_sucursal_consecutivo_cheque,codigo_tipo_documento_consecutivo_cheque,codigo_banco_consecutivo_cheque,numero_consecutivo_cheque,consecutivo_cheque",
             // Nombre de la tabla relacionada
             "consecutivo_cheques",
             // Nombre del campo de la tabla relacionada
             "codigo_sucursal,codigo_tipo_documento,codigo_banco,numero,consecutivo"
         )
    );
*/

/*
$registros["componentes"] = array(
   array(
        "id"            => "GESTCLPR",
        "padre"         => "SUBMPRSO",
        "id_modulo"     => "NOMINA",
        "orden"         => "02",
        "visible"       => "1",
        "carpeta"       => "calculo_prima",
        "global"        => "0",
        "archivo"       => "menu",
        "requiere_item" => "0",
        "tipo_enlace"   => "1"
        )
);
*/

?>
