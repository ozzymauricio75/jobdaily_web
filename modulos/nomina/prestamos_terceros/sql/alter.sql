ALTER TABLE job_movimiento_cuenta_por_comprar_empleado RENAME job_movimiento_cuenta_por_cobrar_empleado;

--------------------------------------------------------------------------------------------------------

--------- Miercoles 13 de abril 2011 -------

ALTER TABLE job_movimiento_cuenta_por_cobrar_descuento ADD codigo_empresa_auxiliar SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable' AFTER codigo_sucursal;
ALTER TABLE job_movimiento_cuenta_por_cobrar_descuento ADD codigo_anexo_contable VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas' AFTER  codigo_empresa_auxiliar;
ALTER TABLE job_movimiento_cuenta_por_cobrar_descuento ADD codigo_auxiliar_contable INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Código donde se acumulara la información' AFTER codigo_anexo_contable;

ALTER TABLE job_movimiento_cuenta_por_cobrar_descuento ADD CONSTRAINT movimiento_cuenta_por_cobrar_empleado_contable FOREIGN KEY(codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable) REFERENCES job_auxiliares_contables(codigo_empresa,codigo_anexo_contable,codigo) ON UPDATE CASCADE ON DELETE RESTRICT;


--------------------------------------------------------------------------------------------------------


ALTER TABLE job_movimiento_cuenta_por_pagar_tercero ADD codigo_empresa_auxiliar SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable' AFTER codigo_sucursal;
ALTER TABLE job_movimiento_cuenta_por_pagar_tercero ADD codigo_anexo_contable VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas' AFTER  codigo_empresa_auxiliar;
ALTER TABLE job_movimiento_cuenta_por_pagar_tercero ADD codigo_auxiliar_contable INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Código donde se acumulara la información' AFTER codigo_anexo_contable;

ALTER TABLE job_movimiento_cuenta_por_cobrar_descuento ADD CONSTRAINT movimiento_cuenta_por_pagar_tercero_contable FOREIGN KEY(codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable) REFERENCES job_auxiliares_contables(codigo_empresa,codigo_anexo_contable,codigo) ON UPDATE CASCADE ON DELETE RESTRICT;


--------------------------------------------------------------------------------------------------------

ALTER TABLE job_movimiento_cuenta_pago_tercero ADD codigo_empresa_auxiliar SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable' AFTER codigo_sucursal;
ALTER TABLE job_movimiento_cuenta_pago_tercero ADD codigo_anexo_contable VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas' AFTER  codigo_empresa_auxiliar;
ALTER TABLE job_movimiento_cuenta_pago_tercero ADD codigo_auxiliar_contable INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Código donde se acumulara la información' AFTER codigo_anexo_contable;

ALTER TABLE job_movimiento_cuenta_pago_tercero ADD CONSTRAINT movimiento_cuenta_pago_tercero_contable FOREIGN KEY(codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable) REFERENCES job_auxiliares_contables(codigo_empresa,codigo_anexo_contable,codigo) ON UPDATE CASCADE ON DELETE RESTRICT;

---------------------------------------------------------------------------------------------------------


ALTER TABLE job_movimiento_cuenta_por_cobrar_empleado ADD codigo_empresa_auxiliar SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable' AFTER codigo_sucursal;
ALTER TABLE job_movimiento_cuenta_por_cobrar_empleado ADD codigo_anexo_contable VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas' AFTER  codigo_empresa_auxiliar;
ALTER TABLE job_movimiento_cuenta_por_cobrar_empleado ADD codigo_auxiliar_contable INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT 'Código donde se acumulara la información' AFTER codigo_anexo_contable;

ALTER TABLE job_movimiento_cuenta_por_cobrar_empleado ADD CONSTRAINT movimiento_cuenta_por_cobrar_empleado_transaccion_contable FOREIGN KEY(codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable) REFERENCES job_auxiliares_contables(codigo_empresa,codigo_anexo_contable,codigo) ON UPDATE CASCADE ON DELETE RESTRICT;

----------------------------------------------------------------------------------------------------------

--------- Miercoles 13 de abril 2011 HORA: 10:58:30-------

DELETE FROM job_movimiento_cuenta_por_cobrar_descuento;
DELETE FROM job_movimiento_cuenta_por_cobrar_empleado;
DELETE FROM job_movimiento_cuenta_por_pagar_tercero;
DELETE FROM job_movimiento_cuenta_pago_tercero;

ALTER TABLE job_movimiento_cuenta_por_cobrar_empleado ADD fecha_pago_planilla DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla' AFTER obligacion;
ALTER TABLE job_movimiento_cuenta_por_pagar_tercero   ADD fecha_pago_planilla DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla' AFTER obligacion;
ALTER TABLE job_movimiento_cuenta_pago_tercero        ADD fecha_pago_planilla DATE NOT NULL COMMENT 'Fecha rango de pago de la planilla' AFTER obligacion;


ALTER TABLE job_movimiento_cuenta_por_cobrar_empleado DROP PRIMARY KEY , ADD PRIMARY KEY (documento_identidad_empleado ,codigo_sucursal,obligacion,fecha_pago_planilla);
ALTER TABLE job_movimiento_cuenta_por_pagar_tercero   DROP PRIMARY KEY , ADD PRIMARY KEY (documento_identidad_empleado ,codigo_sucursal,obligacion,fecha_pago_planilla);
ALTER TABLE job_movimiento_cuenta_pago_tercero        DROP PRIMARY KEY , ADD PRIMARY KEY (documento_identidad_empleado ,codigo_sucursal,obligacion,fecha_pago_planilla);

----------------------------------------------------------
