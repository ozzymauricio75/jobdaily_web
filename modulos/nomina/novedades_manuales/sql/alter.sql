ALTER TABLE job_movimiento_novedades_manuales CHANGE valor_noveda valor_movimiento   DECIMAL(15,2) NOT NULL COMMENT 'Valor hora del salario';
ALTER TABLE job_movimiento_novedades_manuales CHANGE codigo_contable codigo_contable VARCHAR(15) NOT NULL COMMENT 'Codigo contable de la transaccion contable';

ALTER TABLE job_movimiento_novedades_manuales ADD CONSTRAINT movimiento_novedades_manuales_auxiliares_plan_contable FOREIGN KEY(codigo_contable) REFERENCES job_plan_contable(codigo_contable) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE job_movimientos_salarios ADD CONSTRAINT movimiento_salario_plan_contable FOREIGN KEY(codigo_contable) REFERENCES job_plan_contable(codigo_contable) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE job_movimientos_auxilio_transporte   ADD CONSTRAINT movimiento_auxilio_plan_contable FOREIGN KEY(codigo_contable) REFERENCES job_plan_contable(codigo_contable) ON UPDATE CASCADE ON DELETE RESTRICT;