$(document).ready(function() {
ejecutarFuncionesGlobales();

});

function acLocalEmpleados(item) {
    var destino  = $('#URLFormulario').val();
    var empresa  = $('#codigo_empresa').val();
    $(item).autocomplete(destino, {
        minChars: 3,
        width: 250,
        max: 100,
        delay: 100,
        matchContains: true,
        cacheLength:100,
        extraParams: {
            verificar: true,
            codigo_empresa: empresa
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
            
        } else {
            $('#documento_identidad_empleado').val('');
        }
    });
}


///////////////Nuevos Datos///////////////////
function seleccionar_todas_empresas(){
      var seleccionar_todos = true;
      var contador_casillas_seleccionadas = 0;
      var contador_total_casillas = 0;
      $('#PESTANA_EMPRESAS').find('.total_empresa:checkbox').each(function (grupos) {

        var id = $(this).val();
        if($('#empresas_'+id).attr('checked')){
            contador_casillas_seleccionadas++;
        }
        contador_total_casillas++;
      });

      if(contador_total_casillas == contador_casillas_seleccionadas)
        seleccionar_todos=false;
        $(".total_empresa:checkbox").attr('checked', seleccionar_todos);
}

function informacionEmpleado(){

    var destino            = $('#URLFormulario').val();
    var fecha_liquidacion  = $('#fecha_liquidacion').val();
    var codigo_empresa     = $('#codigo_empresa').val();
    removerTable();

    $.getJSON(destino, {
    informacion_empleado : true,
    fecha_liquidacion    : fecha_liquidacion,
    codigo_empresa       : codigo_empresa

    },function(datos){
        cargarDatosTabla(datos.join("¬"));
    });
}

function removerTable() {
    $("#listaItemsPrestaciones tbody").remove();
}

function cargarDatosTabla(datos){
    if(!campoVacio(datos)){
        $("#listaItemsPrestaciones th").remove();
        datos = datos.split("¬");

        for(i=0;i<datos.length;i++){
            datos_prestaciones = datos[i].split("|");
            id = new Date();
            items = '<tr id="'+id+'">'+ '<td colspan="4" align="left" width="250"></td>' + '</tr>';
            $('#listaItemsPrestaciones').append(items);
            id = new Date();
            items = '<tr id="'+id+'">'+ '<td colspan="4" align="left" width="250"><span class="etiqueta_grande" >'+datos_prestaciones[1]+" - "+datos_prestaciones[3]+'</span></td>' + '</tr>';
            $('#listaItemsPrestaciones').append(items);
            id = new Date();
            items = '<tr id="'+id+'" style="background-color: #BDD2EF;">'+
                '<td align="center" width="100"><span class="etiqueta">'   +$("#titulo_codigo_contable").val()+'</span></td>'+
                '<td align="center" width="250"><span class="etiqueta" > ' +$("#titulo_transaccion").val()+'</span></td>'+
                '<td align="center" width="100"><span class="etiqueta" >'  +$("#titulo_debitos").val()+'</span></td>'+
                '<td align="center" width="100"><span class="etiqueta" >'  +$("#titulo_creditos").val()+'</span></td>'+
            '</tr>';
            $('#listaItemsPrestaciones').append(items);
            for(c=8;c<datos_prestaciones.length;c+=4){
                var valorClase = 'even';

                if ($("#listaItemsPrestaciones tr:last").hasClass("even")) {
                    valorClase = 'odd';
                } else {
                    valorClase = 'even';
                }

                items =  '<tr id="'+id+'" class="'+valorClase+'">'+
                    '<td align="center" width="100">'+datos_prestaciones[c]+'</td>'+
                    '<td align="left" width="250">'+datos_prestaciones[c+1]+'</td>'+
                    '<td align="left" width="100">' +datos_prestaciones[c+2]+'</td>'+
                    '<td align="left" width="100">' +datos_prestaciones[c+3]+'</td>'+
                '</tr>';
                $('#listaItemsPrestaciones').append(items);
            }
        }
    }
}

function actualizarEventosCalendario(){
    var destino            = $('#URLFormulario').val();
    var codigo_empresa     = $('#codigo_empresa').val();
    $.getJSON(destino, {
    determinar_rango_dias_bloquear : true,
    codigo_empresa : codigo_empresa
    },function(datos){

        if(datos[0]=="0"){
            diasBloquear = null;
        }else{
            var diasBloquear = "-"+datos[1]+"D";
        }
        
        $.datepicker.setDefaults({
            minDate: diasBloquear,
            onSelect: function(){informacionEmpleado();}
        });
        $("#fecha_liquidacion").addClass("selectorFecha");
    }
    );
}

