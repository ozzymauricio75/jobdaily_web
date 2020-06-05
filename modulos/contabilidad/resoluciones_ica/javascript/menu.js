    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    /*** Validar un elemento de acuerdo a su llave primaria (LSM) ***/
    /*
    function validarItemsllaves(item,llave)
    {
        var destino  = $('#URLFormulario').val();
        var items=llave.split(",");
        var condicion="";
        var valor_campo=$(item).val();
        var id_item = $(item).attr('id');
        for(var i=0; i<items.length;i++){
            columna     = items[i].split(":")[0];
            valor       = items[i].split(":")[1];
            valor       = $("#"+valor).val();

            if(i==0){

                if(valor == null || valor.length == 0 || /^\s+$/.test(valor)){
                    valor = "0";

                }
            }
            if((i+1)== items.length){
                condicion += columna+"="+valor;
            }
            else{
                condicion += columna+"="+valor+" AND ";
            }
        }

                 $.getJSON(destino, {validarItemsllaves:true, condicion:condicion, item:id_item, valor:valor_campo}, function(mensaje){
                    if(mensaje != ""){
                        $(item).parent().children('#errorDialogo').remove();
                        $(item).focus();
                        $(item).parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensaje+'</span>');
                        $(item).parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                    }
                });
    }*/

   

