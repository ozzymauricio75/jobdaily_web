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

$textos = array(
    "GESTCUBA"                      => "Cuentas bancarias",
    "ADICCUBA"                      => "Adicionar cuenta bancaria",
    "CONSCUBA"                      => "Consultar cuenta bancaria",
    "MODICUBA"                      => "Modificar cuenta bancaria",
    "ELIMCUBA"                      => "Eliminar cuenta bancaria",
    "LISTCUBA"                      => "Listar cuentas bancarias",
    "REPORTE_CUBA"                  => "Listado de cuentas bancarias",

    "BANCO"                         => "Banco",
    "CONSORCIO"                     => "Consorcio",
    "NUMERO"                        => "N�mero",
    "PESTANA_GENERAL"               => "General",
    "PESTANA_PLANTILLA"             => "Plantilla cheque",
    "SUCURSAL"                      => "Almac�n",
    "TIPO_DOCUMENTO"                => "Tipo documento",
    "PLAN_CONTABLE"                 => "Plan contable",
    "AUXILIAR_CONTABLE"             => "Auxiliar contable",
    "ESTADO"                        => "Estado",
    "ACTIVA"                        => "Activa",
    "INACTIVA"                      => "Inactiva",
    "TIPO_CUENTA"                   => "Tipo de cuenta",
    "AHORROS"                       => "Ahorros",
    "CORRIENTE"                     => "Corriente",
    "FIDUCIA"                       => "Fiducia",
    "PLANTILLA"                     => "Plantilla",

    "PLANTILLA_INICIAL"             =>
"


                                         AAAA  MM  DD   VVVVVVVVVVV

              PPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP

         SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS
SSSSSSSSSSSSSSSSSSSSSSSSSS










CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC
CCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCCC






                                                                              ",

    "ANO"                           => "A�o. Cuatro 'A' seguidas",
    "MES"                           => "Mes. Dos 'M' seguidas",
    "DIA"                           => "D�a. Dos 'D' seguidas",
    "VALOR_CHEQUE"                  => "Valor cheque. Once 'V' seguidas",
    "PAGUESE"                       => "Paguese a. Tantas 'P' seguidas como requiera",
    "SUMA"                          => "La suma de. Tantas 'S' seguidas como requiera",
    "SUCURSALES_BANCOS"             => "Sucursales del banco",
    "SIN_SUCURSALES"                => "No existen sucursales para el banco",
    "COMPROBANTE"                   => "Comprobante. Espacio para registro de los movimientos contables en el comprobante. Tantas 'C' seguidas como requiera, rengl�n completo",

    "ASIGNADA"                      => "El plan contable seleccionado ya posee una cuenta asignada",
    "AYUDA_NUMERO"                  => "Numero de la cuenta bancaria",
    "AYUDA_PLAN_CONTABLE"           => "Cuenta del plan contable que afecta la cuenta bancaria",
    "AYUDA_SUCURSAL"                => "Seleccione el almac�n al que desea asignar la cuenta",
    "AYUDA_TIPO_DOCUMENTO"          => "Seleccione el tipo de documento para la cuenta",
    "AYUDA_BANCO"                   => "Seleccione el banco",
    "AYUDA_ESTADO"                  => "Seleccione el estado de la cuenta",
    "AYUDA_SUCURSALES_BANCOS"       => "Seleccione el almacen para la cuenta bancaria",
    "ERROR_USUARIO_SIN_PRIVILEGIOS" => "Usuario sin privilegios para este componente",
    /*** Validar datps vacios ***/
    "NUMERO_VACIO"                  => "Debe ingresar el numero de la cuanta bancaria",
    "PLAN_CONTABLE_VACIO"           => "Debe ingresar la descripci�n o el c�digo contable que afecta la cuanta bancaria",
    "SUCURSAL_VACIO"                => "Debe seleccionar el almacen",
    "BANCO_VACIO"                   => "Debe seleccionar el banco",
    "TIPO_DOCUMENTO_VACIO"          => "Debe seleccionar el tipo de documento",
    "SUCURSAL_BANCO_VACIO"          => "Debe seleccionar la sucursal bancaria",
    "PLANTILLA_VACIA"               => "Debe ingresar el molde de la plantilla",
    "BANCO_SIN_SUCURSALES"          => "Este banco actualmente no tiene sucursales\nPor favor ingrese al menos una para la operacion",
    "CUENTA_EXISTE"                 => "Error, la cuenta que esta intertando ingresar ya existe, verifique",
    "ERROR_LLAVE_REPETIDA"          => "Error, para este almacen ya existe una cuenta bancaria asignada con datos similares",
    "ERROR_AUXILIARES_ASIGNADOS"    => "Error, el auxiliar seleccionado ya fue asignado a otra cuenta bancaria que posee la misma cuenta contable escogida"
);
?>
