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

    function activaCamposCreditos(){
        var destino = $('#URLFormulario').val();

        if ($(".por_credito").is(':checked')){
            $('.por_banco').parent().show();
            $('#por_credito_activo').val(2);

        }else{
            $('.por_banco').val('');
            $('.por_banco').parent().hide();
        }
    }

    function activaCamposEstado(){
        var destino = $('#URLFormulario').val();

        if ($(".por_estado").is(':checked')){
            $('.por_estado_credito').parent().show();
            $('#por_estado_activo').val(2);

        }else{
            $('.estado_credito').val('');
            $('.por_estado_credito').parent().hide();
        }
    }

    function activaCamposBancos(){
        var destino = $('#URLFormulario').val();

        if ($(".por_banco_credito").is(':checked')){
            $('.por_banco_seleccionado').parent().show();
            $('#por_banco_activo').val(2);

        }else{
            $('.por_banco_seleccionado').val('');
            $('.por_banco_seleccionado').parent().hide();
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

    function existeCredito() {
        var destino        = $('#URLFormulario').val();
        var numero_credito = $('#selector5').val();

        $.getJSON(destino, {existeCredito: true, numero_credito: numero_credito }, function(datos) {
            if(datos==""){
                alert('No existe el numero del credito');
                $('#selector5').val('');
                $('#valor_credito').val('');
                $('#selector5').focus();
            }
        });
    }

    function cargaValorCredito() {
        var destino        = $('#URLFormulario').val();
        var numero_credito = $('#selector5').val();

        $.getJSON(destino, {cargaValorCredito: true, numero_credito: numero_credito }, function(datos) {
            if(datos!=""){
                $('#valor_credito').text(datos[0]);
                $('#banco').text(datos[1]);
            }else{
                alert('No existen datos del credito digitado');
                $('#valor_credito').text('');
                $('#banco').text('');
                $('#selector5').focus();
            }
        });
    }

    function agregarItemCuota() {
        var destino       = $('#URLFormulario').val();
        var tasa_mensual  = $('#tasa_mensual').val();
        var numero_cuotas = $('#numero_cuotas').val();
        var codigo        = $('#codigo').val();

        $.getJSON(destino, {agregarItemCuota: true, tasa_mensual: tasa_mensual, numero_cuotas: numero_cuotas, codigo: codigo
            }, function(datos) {
            //jQuery.each(datos, function(id, valor){    
            
            for ( j=1; j>=datos; j++) {
                if ($("#listaCuotas tr:last").hasClass("even")) {
                    valor = 'odd';
                }
                var item  = '<tr id="'+j+'" class="'+0+'">'+
                '</tr>';

                $('#listaCuotas').append(item);
            }
            //});
        });
    }

    function insertarCuota(cuota,id) {
        var destino       = $('#URLFormulario').val();
        var abono_capital = $("input[name='cuota["+cuota+"]']").val();

        $.getJSON(destino, {insertarCuota: true, cuota: cuota, id: id, abono_capital: abono_capital 
            }, function(datos) {
            
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

    function formatoMiles(input){
        var num = input.value.replace(/\./g,'');
            if(!isNaN(num)){
                num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
                num = num.split('').reverse().join('').replace(/^[\.]/,'');
                input.value = num;
            } else{ alert('Solo se permiten numeros');
                input.value = input.value.replace(/[^\d\.]*/g,'');
        }
    }
