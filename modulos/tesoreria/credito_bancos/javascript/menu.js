    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

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

    function calcularCredito() {
        var destino       = $('#URLFormulario').val();
        var valor_credito = $('#valor_credito').val();
        var tasa_mensual  = $('#tasa_mensual').val();
        var numero_cuotas = $('#numero_cuotas').val();

        $.getJSON(destino, {calcularCredito: true, valor_credito: valor_credito, tasa_mensual: tasa_mensual, numero_cuotas: numero_cuotas
            }, function(datos) {
            if(datos!=""){
                $('#valor_cuota').val(datos);
            }else{
                alert('No existen datos para calcular la cuota');
                $('#valor_cuota').val('');
            }
        });
    }

    function cargarCuenta() {
        var destino     = $('#URLFormulario').val();
        var cuenta      = $('#selector5').val();

        $.getJSON(destino, {cargarCuenta: true, cuenta: cuenta}, function(datos) {
            if(datos!=""){
                $('#banco').val(datos[0]);
                //$('#tercero').val(datos[1]);
            }else{
                alert('No existen datos con ese numero de cuenta');
                $('#selector5').val('');
                $('#banco').val('');
                //$('#tercero').val('');
                $('#selector5').focus();
            }
        });
    }
