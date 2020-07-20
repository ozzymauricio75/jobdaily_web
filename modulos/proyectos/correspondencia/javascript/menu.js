    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarListaEmpresas(){
        var destino                 = $('#URLFormulario').val();
        var codigo_empresa_proyecto = $('#empresa').val();
        var sucursal                = $('#sucursal').val();   
        var lista                   = '';

        $.getJSON(destino, {recargar: true, codigo: codigo_empresa_proyecto, elemento: 'sucursal', codigo_empresa : sucursal}, function(datos) {
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });
            $('#sucursal').html(lista);
            //$('#sucursal').val('');
        });
    }

    function formato_numero(valor){
        var valor_formato = '';
        for ( m=0; m < valor.length; m++) {

            if (valor.charAt(m) != ',') {
                valor_formato = valor_formato + valor.charAt(m);
            }
        }
        return valor_formato;
    };
    
    
    function ponerMiles(valor) {
        var valorMiles = '';
        valor          = valor.toString();
        cont           = 0;
        for ( m=valor.length; m>=0; m--) {
            if (cont != 3) {
                valorMiles = valor.charAt(m-1)+valorMiles;
                cont++;
            } else {
                cont = 0;
                if (m != 0) {
                    valorMiles = ','+valorMiles;
                    m++;
                }
            }
        }
        return valorMiles;
    };

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

    