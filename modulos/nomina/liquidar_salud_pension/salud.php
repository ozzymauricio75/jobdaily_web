<?
                                $sucursal_activa = SQL::obtenerValor("ingreso_empleados","codigo_sucursal_activo","documento_identidad_empleado='".$datosMS->documento_identidad_empleado."' AND estado=1");
                                if(!empty($sucursal_activa)){//Si el empleado esta activo y esta dentro de la sucursal => siga
                                    $consulta = SQL::seleccionar(array("consulta_contrato_empleados"),array("*"),"documento_identidad_empleado='".$datosMS->documento_identidad_empleado."' AND codigo_sucursal='".$codigo_sucursal."' AND fecha_ingreso_sucursal <= '".$forma_fecha_pago."'","","fecha_ingreso_sucursal DESC",0,1);
                                    if(SQL::filasDevueltas($consulta)){
                                        $datosSCE = SQL::filaEnObjeto($consulta);
                                        if((($forma_periodo == '2' || $forma_periodo == '3') && $datosSCE->forma_descuento_salud == '1')||
                                          ($forma_periodo == '3' && $datosSCE->forma_descuento_salud == '2')||($forma_periodo != '2' && $forma_periodo != '3')){
                                            if($estado_ibc_salud == '1'){// Si tiene marca si
                                                if(isset($empleados[$datosMS->documento_identidad_empleado])){
                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{
                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado[$datosMS->documento_identidad_empleado][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                            }else if($estado_ibc_salud == '2'){// Si tiene marca 40%
                                                if(isset($baseIbc40[$datosMS->documento_identidad_empleado])){
                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal]+=$datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal]-= $datosMS->valor_movimiento;
                                                    }
                                                }else{
                                                    if($datosMS->sentido == 'D'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $datosMS->valor_movimiento;
                                                    }else if($datosMS->sentido == 'C'){
                                                        $sucursal_empleado40[$datosMS->documento_identidad_empleado][$codigo_sucursal] = (-1)*$datosMS->valor_movimiento;
                                                    }
                                                }
                                            }
                                            $id_empleados[$datosMS->documento_identidad_empleado]                      = $datosMS->documento_identidad_empleado;
                                            $entidades_Salud[$datosMS->documento_identidad_empleado][$codigo_sucursal] = $consultaESE;

                                            //echo var_dump("Al empleado ".$datosMS->documento_identidad_empleado." se le contabilizo para salud en tabla: ".$datosMS->tabla);
                                        }
                                        //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." ingreso satisfactoriamente a calculo");
                                    }else{
                                        //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." no esta dentro de la sucursal en esa fecha");
                                    }
                                }else{
                                    //echo var_dump("El empleado ".$datosMS->documento_identidad_empleado." esta inactivo");
                                }
?>
