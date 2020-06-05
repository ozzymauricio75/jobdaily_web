    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(id, selector) {
        var destino = $('#URLFormulario').val();
    }

    function generarDias() {
        var destino         = $('#URLFormulario').val();
        var fecha           = $('#fecha_inicial').val().split("-");        
        var fechaCompleta   = $('#fecha_inicial').val();
        var fechaQuincena   = $('#fecha_fin').val();
        var idPlanilla      = $('#id_planilla').val();
        var selector        = "";
        var ano = fecha[0];
        var mes = fecha[1];
        var dia = fecha[2];
        
                
        $.getJSON(destino, {generarDiasPago: true, ano_inicial: ano, mes_inicial: mes, dia_inicial: dia, fecha_completa: fechaCompleta, fecha_quincena:fechaQuincena, id_planilla:idPlanilla}, function(datos) {                
            var fecha_seleccionada = datos[0];                
            var mes_letras         = datos[1];                      
            var dia_letras         = datos[2];  
                      
                var valorClase = '';
                if ($("#listaDiasPagos tr:last").hasClass("even")) {
                      valorClase = 'odd';
                } else {
                      valorClase = 'even';
                }
      
                if(idPlanilla) {
                    var boton = $('#removedor').html();
                    var i = parseInt($('#incrementoDias').val());
                    
                    var campofecha = "<input type='text' class='selectorFecha' size='10' value='"+fecha_seleccionada+"' onchange='onchange("+i+",this.value)'>";                    
                    var campomes_letras = "<span id='mes_letra_"+i+"' class='dato'>"+mes_letras+"</span>";
                    var campodia_letras = "<span id='dia_letra_"+i+"' class='dato'>"+dia_letras+"</span>";
                    
                    var item  = '<tr id="fila_item_'+i+'" class="'+valorClase+'">'+
                            '<td align="center">'+
                            '<input type="hidden" class="idPosicion" name="idPosicion['+i+']" value="'+i+'">'+
                            '<input type="hidden" class="fecha" name="fecha['+i+']" value="'+fecha_seleccionada+'">'+boton+'</td>'+
                            '<td class="dato" align="left">'+campomes_letras+'</td>'+
                            '<td class="dato" align="left">'+campodia_letras+'</td>'+
                            '<td class="dato" align="left">'+campofecha+'</td>'+
                            '</tr>';
    
                    $('#listaDiasPagos').append(item);
                    i++;
                    $('#incrementoDias').val(i++);
                    
                    /*** Generar selector de fecha  ***/
                        $('.selectorFecha').datepicker($.extend({},
                            $.datepicker.regional['es'], {
                                showOn: 'both',
                                dateFormat: 'yy-mm-dd',
                                buttonImage: 'imagenes/calendario.png',
                                buttonImageOnly: true
                        })).attr('readonly', 'readonly');
                    /**********************************/
                    
                }
                

                for(var a = 4; a < datos.length; a++) {
                    var datos_importados = datos[a].split("|");
                    
                    var fecha_seleccionada = datos_importados[0];
                    var mes                = datos_importados[1];
                    var dia                = datos_importados[2];
                    
                    
                    if(idPlanilla) {
                        
                        var valorClase = '';
                        if ($("#listaDiasPagos tr:last").hasClass("even")) {
                              valorClase = 'odd';
                        } else {
                              valorClase = 'even';
                        }
                        
                        var boton = $('#removedor').html();
                        var i = parseInt($('#incrementoDias').val());
                        
                        var campofecha = "<input type='text' class='selectorFecha' size='10' value='"+fecha_seleccionada+"' onchange='cambiarRegistro("+i+",this.value)'>";
                        var campomes_letras = "<span id='mes_letra_"+i+"' class='dato'>"+mes+"</span>";
                        var campodia_letras = "<span id='dia_letra_"+i+"' class='dato'>"+dia+"</span>";
                        
                        var item  = '<tr id="fila_item_'+i+'" class="'+valorClase+'">'+
                                '<td align="center">'+
                                    '<input type="hidden" class="idPosicion" name="idPosicion['+i+']" value="'+i+'">'+
                                    '<input type="hidden" class="fecha" name="fecha['+i+']" value="'+fecha_seleccionada+'">'+boton+'</td>'+
                                '<td class="dato" align="left">'+campomes_letras+'</td>'+
                                '<td class="dato" align="left">'+campodia_letras+'</td>'+
                                '<td class="dato" align="left">'+campofecha+'</td>'+
                                '</tr>';                                
                        $('#listaDiasPagos').append(item);
                        i++;
                        $('#incrementoDias').val(i++);
                        
                        /*** Generar selector de fecha  ***/
                        $('.selectorFecha').datepicker($.extend({},
                            $.datepicker.regional['es'], {
                                showOn: 'both',
                                dateFormat: 'yy-mm-dd',
                                buttonImage: 'imagenes/calendario.png',
                                buttonImageOnly: true
                        })).attr('readonly', 'readonly');
                        /**********************************/
                    }
                }
        });
    }
    
   function cargarFechas(){
    
        var destino = $('#URLFormulario').val();
            var idPlanilla  = $('#id_planilla').val();
       
        $.getJSON(destino,{verificarPlanilla: true, id_planilla:idPlanilla}, function(datos){
           if(datos == true){
                $('#contenedor_fechas').parent().show();
                $('#contenedor_fechas').removeClass('campoInactivo').removeAttr('disabled','disabled');
                $('#contenedor_fechas').val();
                
            }else{
                $('#contenedor_fechas').parent().hide();
                $('#contenedor_fechas').addClass('campoInactivo').attr('disabled','disabled');
            }
       });
       
        $('.selectorFecha').datepicker($.extend({},
            $.datepicker.regional['es'], {
            showOn: 'both',
            dateFormat: 'yy-mm-dd',
            buttonImage: 'imagenes/calendario.png',
            buttonImageOnly: true
        })).attr('readonly', 'readonly');
}
    
    function removerItem(boton) {
        var id     = $(boton).parents('tr').attr('id').split('_')[2];
        var precio = parseInt($("input[name='idPosicion["+id+"]']").val());
        $(boton).parents('tr').remove();

    }
    
    function removerTable(boton) {
        $("#listaDiasPagos tbody").remove();
    }

