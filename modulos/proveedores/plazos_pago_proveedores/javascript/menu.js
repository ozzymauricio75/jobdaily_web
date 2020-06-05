    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });


    function recargarPlazo(){
	var dias_inicial = parseInt($('#inicial').val());
    if (dias_inicial==1){
        dias_inicial = 0;
    }
	var lista = '';
	for (i=dias_inicial; i <= 360 ; i=i+30) {
        var valor = i;
        if (i == 0){
            valor = 1;
        }
	    lista = lista+'<option value="'+valor+'">'+valor+'</option>';
	}
	$('#final').html(lista);
    }
