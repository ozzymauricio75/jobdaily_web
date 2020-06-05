$(document).ready(function() {
    ejecutarFuncionesGlobales();
});

function VerificaLiquidacionGenerada(){

        var destino             = $('#URLFormulario').val();
        var idPlanilla          = $('#id_planilla').val();
        var FechaInicial        = $('#fecha_inicial').val();
        var FechaFinal          = $('#fecha_final').val();
        
        $.getJSON(destino,{verificarPlanillaGenerada: true, id_planilla:idPlanilla, fecha_inicial:FechaInicial, fecha_final:FechaFinal}, function(datos){
            
            if(datos !=''){
                
                alert(datos);
                $('#fecha_inicial').focus();
            }
            
        });
}
            
function cargarLiquidaciones(){

        var destino             = $('#URLFormulario').val();
        var idPlanilla          = $('#id_planilla').val();
        var FechaInicial        = $('#fecha_inicial').val();
        var FechaFinal          = $('#fecha_final').val();
        var FechaTransaccion    = $('#fecha_transaccion').val();
        
        $("#listaItemsLiquidacionSalario tbody").remove();
        
        $('#PESTANA_SUCURSALES').find('.sucursales_liquidacion_salario:checkbox').each(function (grupos) {
            var id = $(this).val();
            if($('#sucursales_'+id).attr('checked')){
              
                $.getJSON(destino,{verificarPlanilla: true, id_planilla:idPlanilla, fecha_inicial:FechaInicial, fecha_final:FechaFinal, id_sucursal:id}, function(datos){
                
                    if(datos !=''){
                        
                        for(var a=0; a<datos.length; a++){

                            var valorClase = '';
                            if ($("#listaItemsLiquidacionSalario tr:last").hasClass("even")) {
                                valorClase = 'odd';
                            } else {
                                valorClase = 'even';
                            }
                            
                            var Horas  = datos[a].split("|");
                                                
                                var Totalhoras      = Horas[0]; 
                                var HoraInicial     = Horas[1];
                                var HoraFinal       = Horas[2];
                                var FechaIni        = Horas[3];
                                var FechaFin        = Horas[4];
                                var Empleado        = Horas[5];
                                var Planilla        = Horas[6];
                                var Contrato        = Horas[7];
                                var Salario         = Horas[8];
                                var TransaContable  = Horas[9];
                                var TransaTiempo    = Horas[10];
                                var CantidadHoras   = Horas[11];
                                var SalarioDiario   = Horas[12];
                                var SalarioMensual  = Horas[13];
                                                                            
                                var boton = $('#botonRemoverLiquidacion').html();
                                var lista_liquidacion    = parseInt($("#lista_liquidacion").val());
                                var item  = '<tr id="fila_'+lista_liquidacion+'" class="'+valorClase+'">'+
                                            '<td align="center">'+
                                                '<input type="hidden" class="idPosicionTablaIncapacidad" name="idPosicionTablaLiquidacion['+lista_liquidacion+']" value="'+lista_liquidacion+'">'+
                                                '<input type="hidden" class="TablaHoraInicial" name="TablaHoraInicial['+lista_liquidacion+']" value="'+HoraInicial+'">'+
                                                '<input type="hidden" class="TablaHoraFinal" name="TablaHoraFinal['+lista_liquidacion+']" value="'+HoraFinal+'">'+
                                                '<input type="hidden" class="TablaFechaInicial" name="TablaFechaInicial['+lista_liquidacion+']" value="'+FechaInicial+'">'+
                                                '<input type="hidden" class="TablaFechaFinal" name="TablaFechaFinal['+lista_liquidacion+']" value="'+FechaFinal+'">'+                                    
                                                '<input type="hidden" class="TablaFechaTransaccion" name="TablaFechaTransaccion['+lista_liquidacion+']" value="'+FechaTransaccion+'">'+                                    
                                                '<input type="hidden" class="TablaEmpleadoTransaccion" name="TablaEmpleadoTransaccion['+lista_liquidacion+']" value="'+Empleado+'">'+                                    
                                                '<input type="hidden" class="TablaPlanillaPago" name="TablaPlanillaPago['+lista_liquidacion+']" value="'+Planilla+'">'+                                    
                                                '<input type="hidden" class="TablaContratoEmpleado" name="TablaContratoEmpleado['+lista_liquidacion+']" value="'+Contrato+'">'+                                    
                                                '<input type="hidden" class="TablaTransaccionContable" name="TablaTransaccionContable['+lista_liquidacion+']" value="'+TransaContable+'">'+                                    
                                                '<input type="hidden" class="TablaTransaccionTiempo" name="TablaTransaccionTiempo['+lista_liquidacion+']" value="'+TransaTiempo+'">'+
                                                '<input type="hidden" class="TablaSalarioMes" name="TablaSalarioMes['+lista_liquidacion+']" value="'+SalarioMensual+'">'+
                                                '<input type="hidden" class="TablaSalarioDia" name="TablaSalarioDia['+lista_liquidacion+']" value="'+SalarioDiario+'">'+
                                                                                    
                                                boton+
                                            '</td>'+
                                            '<td class="dato" align="left">'+Empleado+'</td>'+
                                            '<td class="dato" align="left">'+CantidadHoras+'</td>'+
                                            '<td class="dato" align="left">'+FechaInicial+'</td>'+
                                            '<td class="dato" align="left">'+FechaFinal+'</td>'+
                                            '</tr>';
                                $('#listaItemsLiquidacionSalario').append(item);
                                lista_liquidacion++;
                                $("#lista_liquidacion").val(lista_liquidacion);

                        }
                    }
                });
            }  
        });        
}

function modificarItems(boton) {
    
    var id = $(boton).parents('tr:first').attr('id').split('_')[1];
    var FechaInicial        = $("input[name='TablaFechaInicial["+id+"]']").val();
    var FechaFinal          = $("input[name='TablaFechaFinal["+id+"]']").val();
    var FechaTransaccion    = ($("input[name='TablaFechaTransaccion["+id+"]']").val());
    var PlanillaPago        = ($("input[name='TablaPlanillaPago["+id+"]']").val());

    $('#fecha_inicial').val(FechaInicial);
    $('#fecha_final').val(FechaFinal);
    $('#fecha_transaccion').val(FechaTransaccion);
    $('#id_planilla').val(PlanillaPago);


removerItems(boton);
}


function removerItems(boton) {
    $(boton).parents('tr').remove();
}

function removerTable(boton) {
    $("#listaItemsLiquidacionSalario tbody").remove();
}

function seleccionar_todas_sucursales(){
      var seleccionar_todos = true;
      var contador_casillas_seleccionadas = 0;
      var contador_total_casillas = 0;
      $('#PESTANA_SUCURSALES').find('.sucursales_liquidacion_salario:checkbox').each(function (grupos) {

        var id = $(this).val();
        if($('#sucursales_'+id).attr('checked')){
            contador_casillas_seleccionadas++;
        }
        contador_total_casillas++;
      });
     
      if(contador_total_casillas == contador_casillas_seleccionadas)
        seleccionar_todos=false;
        $(".sucursales_liquidacion_salario:checkbox").attr('checked', seleccionar_todos);
}

function cargarLiquidacionesAuxilio(){
        
        var destino             = $('#URLFormulario').val();
        var idPlanilla          = $('#id_planilla').val();
		
		$.getJSON(destino,{verificarTipoPlanilla: true, id_planilla:idPlanilla}, function(datos){
		
			if(datos == '1'){
				
				$('#periodo_pago_uno').parent().show();
                $('#periodo_pago_uno').removeClass("oculto").removeAttr("disabled");
                $('#periodo_pago_dos').parent().hide();
                $('#periodo_pago_dos').addClass("oculto").attr("disabled");
                $('#periodo_pago_tres').parent().hide();
                $('#periodo_pago_tres').addClass("oculto").attr("disabled");
                $('#periodo_pago_cuatro').parent().hide();
                $('#periodo_pago_cuatro').addClass("oculto").attr("disabled");

			}
			else if(datos == '2'){
				
				$('#periodo_pago_uno').parent().show();
                $('#periodo_pago_uno').removeClass("oculto").removeAttr("disabled");
                $('#periodo_pago_dos').parent().show();
                $('#periodo_pago_dos').removeClass("oculto").removeAttr("disabled");
                $('#periodo_pago_tres').parent().hide();
                $('#periodo_pago_tres').addClass("oculto").attr("disabled");
                $('#periodo_pago_cuatro').parent().hide();
                $('#periodo_pago_cuatro').addClass("oculto").attr("disabled");
			}
			
			else if(datos == '3'){
				
				$('#periodo_pago_uno').parent().show();
                $('#periodo_pago_uno').removeClass("oculto").removeAttr("disabled");
                $('#periodo_pago_dos').parent().show();
                $('#periodo_pago_dos').removeClass("oculto").removeAttr("disabled");
                $('#periodo_pago_tres').parent().show();
                $('#periodo_pago_tres').removeClass("oculto").removeAttr("disabled");
                $('#periodo_pago_cuatro').parent().show();
                $('#periodo_pago_cuatro').removeClass("oculto").removeAttr("disabled");
			}
		
		});
} 
