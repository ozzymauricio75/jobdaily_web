    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function cargarCuenta() {
        var destino     = $('#URLFormulario').val();
        var cuenta      = $('#selector1').val();

        $.getJSON(destino, {cargarCuenta: true, cuenta: cuenta}, function(datos) {
            if(datos!=""){
                $('#banco').val(datos[0]);
                $('#tercero').val(datos[1]);
            }else{
                alert('No existen datos con ese numero de cuenta');
                $('#selector1').focus();
                $('#selector1').val('');
                $('#banco').val('');
                $('#tercero').val('');
            }
        });
    }

    function formatoMiles(input){
        var num = input.value.replace(/\./g,'');
        if(!isNaN(num)){
            num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
            num = num.split('').reverse().join('').replace(/^[\.]/,'');
            input.value = num;
        } else{ 
            alert('Solo se permiten numeros');
            input.value = input.value.replace(/[^\d\.]*/g,'');
        }
    }

