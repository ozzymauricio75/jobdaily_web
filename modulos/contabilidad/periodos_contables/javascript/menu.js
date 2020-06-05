    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });


    function seleccionar_todos(){
        var seleccionar_todos = true;
        var contador_casillas_seleccionadas = 0;
        var contador_total_casillas = 0;
        /*$('#PESTANA_MODULOS').find('.modulos:checkbox').each(function (grupos) {
            var id = $(this).val();
            if($('#modulo_'+id).attr('checked')){
                contador_casillas_seleccionadas++;
            }
            contador_total_casillas++;
        });*/

        $('#PESTANA_MODULOS').find('.modulos:checkbox').each(function (grupos) {
            var id = $(this).val();
            if($('#modulo_'+id).attr('checked')){
                contador_casillas_seleccionadas++;
            }
            contador_total_casillas++;
        });

        /*if(contador_total_casillas == contador_casillas_seleccionadas){
			seleccionar_todos=false;
		}
		$(".modulos:checkbox").attr('checked', seleccionar_todos);*/

        if(contador_total_casillas == contador_casillas_seleccionadas){
            $(".modulos:checkbox").removeAttr('checked');
            $(".modulos:checkbox").removeAttr('checked');
        } else {
            $(".modulos:checkbox").attr('checked','checked');
            $(".modulos:checkbox").attr('checked','checked');
        }
    }
