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

$textos = array(
    "GESTPLCO"                     => "Plan contable",
    "ADICPLCO"                     => "Adicionar cuenta contable",
    "CONSPLCO"                     => "Cosultar cuenta contable",
    "MODIPLCO"                     => "Modificar cuenta contable",
    "ELIMPLCO"                     => "Eliminar cuenta contable",
    "LISTPLCO"                     => "Listar",
    "CODIGO_CONTABLE"              => "Código de la cuenta",
    "AYUDA_CODIGO_CONTABLE"        => "Código de la cuenta",
    "DESCRIPCION"                  => "Descripción",
    "AYUDA_DESCRIPCION"            => "Detalle que describe la cuenta",
    "CUENTA_PADRE"                 => "Código de cuenta padre",
    "AYUDA_CUENTA_PADRE"           => "Digite cuenta padre si la tiene",
    "TIPO_CUENTA"                  => "Tipo de cuenta",
    "AYUDA_TIPO_CUENTA"            => "Seleccione el tipo de cuenta",
    "BALANCE"                      => "Cuenta de balance",
    "GANANCIAS_Y_PERDIDAS"         => "Ganancias y perdidas",
    "CUENTA_ORDEN"                 => "Cuenta de orden",
    "AYUDA_TIPO_CUENTA"            => "Seleccione el tipo de cuenta",
    "NATURALEZA_CUENTA"            => "Naturaleza",
    "AYUDA_NATURALEZA_CUENTA"      => "Seleccione la naturaleza de la cuenta",
    "CLASE_CUENTA"                 => "Clase de cuenta",
    "AYUDA_CLASE_CUENTA"           => "Seleccione la clase de la cuenta",
    "MANEJA_TERCERO"               => "Maneja saldos por tercero",
    "MANEJA_SALDOS"                => "Maneja saldos por documento",
    "MANEJA_SUBSISTEMA"            => "Maneja subsistema",
    "ANEXO_CONTABLE"               => "Auxiliar contable",
    "AYUDA_ANEXO_CONTABLE"         => "Seleccione el auxiliar contable",
    "TASA_APLICAR_1"               => "Tasa 1",
    "AYUDA_TASA_APLICAR_1"         => "Seleccione la tasa a aplicar numero 1",
    "TASA_APLICAR_2"               => "Tasa 2",
    "AYUDA_TASA_APLICAR_2"         => "Seleccione la tasa a aplicar numero 2",
    "FORMATO_DIAN"                 => "Formato DIAN",
    "CONCEPTO_DIAN"                => "Concepto DIAN",
    "AYUDA_CONCEPTO_DIAN"          => "Seleccione el concepto DIAN",
    "TIPO_CERTIFICADO"             => "Tipo de certificado",
    "AYUDA_TIPO_CERTIFICADO"       => "Seleccione el tipo de certificado",
    "CAUSACION_AUTOMATICA"         => "Causación automatica",
    "FLUJO_EFECTIVO"               => "Flujo de efectivo",
    "AYUDA_FLUJO_EFECTIVO"         => "Seleccione el flujo de efectivo",
    "CUENTA_CONSOLIDA"             => "Cuenta donde consolida",
    "AYUDA_CUENTA_CONSOLIDA"       => "Cuenta donde consolida",
    "SUCURSAL"                     => "Sucursal",
    "AYUDA_SUCURSAL"               => "Seleccione sucursal si la cuenta lo requiere",
    "MONEDA_EXTRANJERA"            => "Moneda extranjera",
    "AYUDA_MONEDA_EXTRANJERA"      => "Seleccione una moneda extranjera si aplica",
    "PESTANA_GENERAL"              => "Informacion general",
    "PESTANA_CUENTA"               => "Cuenta padre",
    "PESTANA_MOVIMIENTO"           => "Cuentas de movimiento",
    "ERROR_EXISTE_CODIGO_CONTABLE" => "Ya existe una cuenta con ese código",
    "ERROR_EXISTE_DESCRIPCION"     => "Ya existe descripcion para otra cuenta",
    "DEBITO"                       => "Debito",
    "CREDITO"                      => "Credito",
    "CUENTA_MOVIMIENTO"            => "Cuenta de movimiento",
    "CUENTA_MAYOR"                 => "Cuenta mayor",
    "CUENTA_PRINCIPAL"             => "Cuenta principal",
    "AYUDA_CUENTA_PRINCIPAL"       => "Marque el cuadro si es cuenta principal",
    "NO_APLICA"                    => "<No aplica>",
    "RETENCION_FUENTE"             => "Retencion en la fuente",
    "RETENCION_ICA"                => "Retencion ICA",
    "RETENCION_IVA"                => "Retencion IVA",
    "NO_AFECTA_FLUJO"              => "No afecta flujo",
    "CAJA"                         => "Caja",
    "BANCOS"                       => "Bancos",
    "CUENTA_PADRE"                 => "Cuenta padre",
    "CODIGO_ANEXO"                 => "Anexo",
    "ERROR_ELIMINAR_CODIGO"        => "No se puede eliminar el codigo contable, porque es un codigo contable padre o es codigo contable consolida",
    "CREAR_TASAS"                  => "No existen tasas, debe crear por lo menos una",
    "ERROR_EXISTE_CUENTA_PADRE"    => "El código contable no puede ser principal por que pertenece al: ",
    "ERROR_CUENTA_PADRE"           => "El código contable pertenece al ",
    "ERROR_CUENTA_PADRE2"          => " y ud selecciono ",
    "CODIGO_MAYOR_PADRE"           => "El codigo contable debe tener por lo menos un digito de mas que la cuenta padre",
    "CODIGO_PADRE_IGUAL"           => "El codigo contable debe tener como padre la cuenta: ",
    "SELECCIONE_OTRO_PADRE"        => "La cuenta padre no es la correcta, seleccione la cuenta: ",
    "ERROR_DESCRIPCION_VACIO"      => "Debe digitar la descripción del codigo contable",
    "ERROR_CODIGO_VACIO"           => "Debe digitar el codigo contable",
    "CODIGO"                       => "Cod.cont",
    "PADRE"                        => "Cod.padre",
    "DB_CR"                        => "Db/Cr",
    "ANEXO"                        => "Anexo",
    "BENEFICIARIO"                 => "Ben.",
    "TASA_1_LISTADO"               => "%1",
    "TASA_2_LISTADO"               => "%2",
    "ARCHIVO_PLANO"                => "Archivo plano para excel",
    "ARCHIVO_PDF"                  => "Pdf",
    "CUENTA_INICIAL"               => "Cuenta inicial",
    "AYUDA_CUENTA_INICIAL"         => "Digite la cuenta desde la cual desea listar",
    "CUENTA_FINAL"                 => "Cuenta final",
    "AYUDA_CUENTA_FINAL"           => "Digite la cuenta hasta la cual desea listar",
    "TIPO_LISTADO"                 => "Tipo de listado",
    "AYUDA_TIPO_LISTADO"           => "Seleccione el tipo de listado a generar",
    "ERROR_CUENTA_FINAL"           => "Por favor seleccione una cuenta final",
    "ERROR_CUENTA_INICIAL"         => "La cuenta inicial no puede ser mayor que la cuenta final",
    "MENSAJE_NO_GENERA_PDF"        => "No se genero información",
    "MENSAJE_EXITO"                => "Archivo generado satisfactoriamente",
    "DB"                           => "Db",
    "CR"                           => "Cr",
    "PLAN_CONTABLE"                => "Plan contable",
    "EQUIVALENCIA"                 => "Equivalencia",
    "AYUDA_EQUIVALENCIA"           => "Digite el codigo contable de un sistema anterior si lo tiene",
    "ERROR_EXISTE_EQUIVALENCIA"    => "Ya existe equivalencia para otro codigo contable"
);
?>
