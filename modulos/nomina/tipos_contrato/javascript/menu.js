    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });
    
    function cargarTipos(item){
        var destino = $('#URLFormulario').val();
        var lista   = "";
        
        var tipo =$(item).val();
        
        $.getJSON(destino,{
            verificarTipo: true,
            tipo:tipo
        }, function(datos){
              
            jQuery.each(datos, function(valor, texto) {
                lista = lista+'<option value="'+valor+'">'+texto+'</option>';
            });

                   
            $("#tipo_contratacion").html(lista);
                
        });
    }


    function OcultarCampos(item){
        var destino = $('#URLFormulario').val();
        var tipo =$(item).val();

        if(tipo==1 || tipo==8 || tipo==3 || tipo==11 || tipo==10)
        {
            $('#se_ajusta_minimo').parent().show();
            $('#ajusta_minimo_si').parent().show();
            $('#ajusta_minimo_no').parent().show();
        }else{
            $('#se_ajusta_minimo').parent().hide();
            $('#ajusta_minimo_si').parent().hide();
            $('#ajusta_minimo_no').parent().hide();
        }
    }
