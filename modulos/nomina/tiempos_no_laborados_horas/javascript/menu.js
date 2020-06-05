    $(document).ready(function(){
        ejecutarFuncionesGlobales();
    });

  
    function recargarDatos(){
    }

    function acLocalEmpleados(item) {
        var destino  = $('#URLFormulario').val();
        var sucursal = $('#codigo_sucursal').val();
        $(item).autocomplete(destino, {
            minChars: 3,
            width: 250,
            max: 100,
            delay: 100,
            matchContains: true,
            cacheLength:100,
            extraParams: {
                verificar: true,
                codigo_sucursal: sucursal
            },
            formatResult: function(datos, valor) {
                return valor.split("|")[0];
            }
        }).blur(function() {
            var x = $(this).attr('id');
            $(x).autocomplete().remove();
        }).result(function(evento, datos, formateado) {
            if (datos) {
                $('#documento_identidad_empleado').val(datos[1]);
                $('#fecha_reporte').removeAttr("disabled","disabled");
            } else {
                $('#documento_identidad_empleado').val('');
                $('#fecha_reporte').attr("disabled");
                $('#anexos_contables').val('');
                $('#auxiliares_contables').val('');
            }
        });
    }
    
    function removerItems(boton) {
        $(boton).parents('tr').remove();
    }
   
    function removerTable() {
        $("#listaItemsExtras tbody").remove();
    }

    function determinarTurnos(){
        var destino             = $('#URLFormulario').val();
        var documento_identidad = $('#documento_identidad_empleado').val();
        var codigo_sucursal     = $('#codigo_sucursal').val();
        var hora_inicio         = $('#hora_inicio').val();
        var hora_fin            = $('#hora_fin').val();
        var fecha_inicio        = $('#fecha_reporte').val();
      
        $.getJSON(destino,{verificarHoraDentroTurno: true,documento_identidad:documento_identidad,hora_inicio:hora_inicio,
                           hora_fin:hora_fin,fecha_inicio:fecha_inicio,sucursal:codigo_sucursal}, function(datos){

          $("#turno_laborar").text(datos[0]);
          $("#tipo_turno").val(datos[1]);
          $("#dia_descanso").val(datos[2]);
          $("#hora_inicial_turno1").val(datos[3]);
          $("#hora_final_turno1").val(datos[4]);
          $("#hora_inicial_turno2").val(datos[5]);
          $("#hora_final_turno2").val(datos[6]);
          
        });

    }


    function limpiarCampos(){
        $("#selector1").val("");
        $("#documento_identidad_empleado").val("");
        $("#fechas").val("");
        $("#turno_laboral_1").val("");
        $("#turno_laboral_2").val("");
        $(".autorizarExtra").attr('checked', false);
        $("#contenedor_turno_laboral_1").addClass("oculto");
        $("#contenedor_turno_laboral_1").parent().hide();
        $("#contenedor_turno_laboral_2").addClass("oculto");
        $("#contenedor_turno_laboral_2").parent().hide();
        $("anexos_contables").val(0);
        $("auxiliares_contables").val('');
        removerTable();
    }


    function obtenerRango(){
        var documento_identidad = $('#documento_identidad_empleado').val();
        var destino             = $('#URLFormulario').val();
        $.getJSON(destino,{obtenerFechaRango: true,documento_identidad:documento_identidad}, function(rango){
            $("#rango_fecha").val(rango);
            bloquearDias(rango);
        });
    }

    function bloquearDias(rango){
        var diasBloquear = "-"+rango+"D";
        $.datepicker.setDefaults({
            minDate: diasBloquear,
            onSelect: function(){determinarTurnos();}

        });
        $("#fechas").addClass("selectorFecha");
    }


    function verificarAnexosEnTransacciones(){
        var destino             = $('#URLFormulario').val();
        var documento_identidad = $('#documento_identidad_empleado').val();
        var codigo_sucursal     = $('#codigo_sucursal').val();
        var codigo_anexo        = $('#anexos_contables').val();
        var fecha_inicial       = $('#fecha_inicial').val();

        if(documento_identidad && codigo_anexo==0){
            $.getJSON(destino,{verificaAnexos: true,empleado : documento_identidad, sucursal : codigo_sucursal , fecha_inicial:fecha_inicial}, function(datos){
                if(datos){
                    if(datos[0]==1){
                        $('#anexos_contables').val('');
                        $('#auxiliares_contables').val('');
                    }else if(datos[0]==2){
                        $('#anexos_contables').val(datos[1]);
                        recargarLista('anexos_contables','auxiliares_contables',datos[2]);
                    }
                }
            });
        }
    }

   


