
ALTER TABLE job_movimientos_prestamos_empleados ADD sentido ENUM('D','C') NOT NULL DEFAULT 'D'  COMMENT 'D->Débito C->Crédito';

ALTER TABLE job_control_prestamos_empleados CHANGE consecutivo_documento consecutivo_documento INT( 8 ) UNSIGNED ZEROFILL NOT NULL COMMENT 'Consecutivo del documento'

ALTER TABLE  job_control_prestamos_empleados CHANGE transaccion_contable_descontar codigo_transaccion_contable_descontar INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion cuenta por descontar al empleado'

ALTER TABLE  job_control_prestamos_empleados CHANGE transaccion_contable_cobrar codigo_transaccion_contable_cobrar INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'codigo de la transaccion cuenta por cobrar empleado'


--------------------------------------------------------------------------------------------------------------------------------------------------------

ALTER TABLE `job_control_prestamos_empleados` ADD `fecha_registro` DATETIME NOT NULL COMMENT 'Fecha en que se genera el registro' AFTER `forma_pago`;

ALTER TABLE `job_movimiento_control_prestamos_empleados` DROP `codigo_usuario_modifica`;

ALTER TABLE job_control_prestamos_empleados ADD CONSTRAINT control_prestamos_empleados_usuario_registra FOREIGN KEY(codigo_usuario_registra) REFERENCES job_usuarios(codigo) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE job_control_prestamos_empleados ADD CONSTRAINT control_prestamos_empleados_usuario_modifica FOREIGN KEY(codigo_usuario_modifica) REFERENCES job_usuarios(codigo) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE job_movimiento_control_prestamos_empleados ADD CONSTRAINT movimiento_control_prestamos_empleados_usuario_registra FOREIGN KEY(codigo_usuario_registra) REFERENCES job_usuarios(codigo) ON UPDATE CASCADE ON DELETE RESTRICT;

-----------------------------------------------------------------------------------------------------------------------------


ALTER TABLE job_movimiento_control_prestamos_empleados ADD CONSTRAINT movimiento_control_prestamos_empleados_contable FOREIGN KEY(codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable) REFERENCES job_auxiliares_contables(codigo_empresa,codigo_anexo_contable,codigo) ON UPDATE CASCADE ON DELETE RESTRICT;

-----------------------------------------------------------------------------------------------------------------------------

ALTER TABLE job_movimientos_prestamos_empleados ADD codigo_empresa_auxiliar SMALLINT(3) UNSIGNED ZEROFILL NOT NULL COMMENT 'Codigo de la empresa del auxiliar contable' AFTER concepto_prestamo;
ALTER TABLE job_movimientos_prestamos_empleados ADD codigo_anexo_contable VARCHAR(3) NOT NULL COMMENT 'Código del anexo que permite dividir las cuentas' AFTER  codigo_empresa_auxiliar;
ALTER TABLE job_movimientos_prestamos_empleados ADD codigo_auxiliar_contable INT(8) UNSIGNED ZEROFILL NOT NULL COMMENT 'Código donde se acumulara la información' AFTER codigo_anexo_contable;

ALTER TABLE job_movimientos_prestamos_empleados ADD CONSTRAINT movimientos_prestamos_empleados_contable FOREIGN KEY(codigo_empresa_auxiliar,codigo_anexo_contable,codigo_auxiliar_contable) REFERENCES job_auxiliares_contables(codigo_empresa,codigo_anexo_contable,codigo) ON UPDATE CASCADE ON DELETE RESTRICT;

-----------------------------------------------------------------------------------------------------------------------------

