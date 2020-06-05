SELECT * FROM job_consulta_contrato_empleado WHERE documento_identidad_empleado = '1114820980' AND codigo_sucursal = '00123'
AND fecha_ingreso_sucursal <= '2011-04-04' AND IF(fecha_retiro_sucursal != '0000-00-00',fecha_retiro_sucursal >= '2011-04-04',fecha_retiro_sucursal = '0000-00-00')
