    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function recargarDatos(){
    }

    function agregarItemSucursal() {

        var nombre        = $('#nombre_sucursal').val();
        var municipio     = $('#selector2').val();
        var id_municipio  = $('#codigo_municipio_sucursal').val();
        var direccion     = $('#direccion').val();
        var telefono      = $('#telefono').val();
        var contacto      = $('#contacto').val();
        var correo        = $('#correo').val();
        var celular       = $('#celular').val();
        var error_datos   = $('#error_datos').val();
        var orden         = parseInt($('#contador_sucursal').val());

        if (nombre && id_municipio && municipio && direccion && telefono) {

            var boton      = $('#removedorSucursal').html();
            var boton1     = $('#botonModificar').html();
            var valorClase = '';

            if ($("#lista_items_sucursales tr:last").hasClass("even")) {
                valorClase = 'odd';
            } else {
                valorClase = 'even';
            }

            var item  = '<tr id="fila_'+orden+'" class="'+valorClase+'">'+
                    '<td align="center">'+
                    '<input type="hidden" class="itemTabla" name="itemTabla['+orden+']" value="'+orden+'">'+
                    '<input type="hidden" class="nombres_sucursales" name="nombres_sucursales['+orden+']" value="'+nombre+'">'+
                    '<input type="hidden" class="municipios_sucursales" name="municipios_sucursales['+orden+']" value="'+id_municipio+'">'+
                    '<input type="hidden" class="nombres_municipios" name="nombres_municipios['+orden+']" value="'+municipio+'">'+
                    '<input type="hidden" class="direcciones_sucursales" name="direcciones_sucursales['+orden+']" value="'+direccion+'">'+
                    '<input type="hidden" class="telefonos_sucursales" name="telefonos_sucursales['+orden+']" value="'+telefono+'">'+
                    '<input type="hidden" class="contactos" name="contactos['+orden+']" value="'+contacto+'">'+
                    '<input type="hidden" class="celulares_sucursales" name="celulares_sucursales['+orden+']" value="'+celular+'">'+
                    '<input type="hidden" class="correos_sucursales" name="correos_sucursales['+orden+']" value="'+correo+'">'+
                    boton+
                    boton1+
                    '</td>'+
                    '<td align="left">'+nombre+'</td>'+
                    '<td align="left">'+municipio+'</td>'+
                    '<td align="left">'+direccion+'</td>'+
                    '<td align="left">'+telefono+'</td>'+
                    '<td align="left">'+contacto+'</td>'+
                    '<td align="left">'+celular+'</td>'+
                    '<td align="left">'+correo+'</td>'+
                '</tr>';

            $('#lista_items_sucursales').append(item);
            $('#nombre_sucursal').val('');
            $('#selector2').val('');
            $('#codigo_municipio_sucursal').val('');
            $('#direccion').val('');
            $('#telefono').val('');
            $('#contacto').val('');
            $('#correo').val('');
            $('#celular').val('');
            $('#sucursal').val('');
            orden++;
            $('#contador_sucursal').val(orden);
        }else{
            alert(error_datos);
        }
    }

    function removerItem(boton) {
        $(boton).parents('tr').remove();
    }

    function modificarItems(boton) {

        var id = $(boton).parents('tr:first').attr('id').split('_')[1];

        var nombre        = $("input[name='nombres_sucursales["+id+"]']").val();
        var municipio     = $("input[name='nombres_municipios["+id+"]']").val();
        var id_municipio  = $("input[name='municipios_sucursales["+id+"]']").val();
        var direccion     = $("input[name='direcciones_sucursales["+id+"]']").val();
        var telefono      = $("input[name='telefonos_sucursales["+id+"]']").val();
        var contacto      = $("input[name='contactos["+id+"]']").val();
        var celular       = $("input[name='celulares_sucursales["+id+"]']").val();
        var correo        = $("input[name='correos_sucursales["+id+"]']").val();

        $('#nombre_sucursal').val(nombre);
        $('#selector2').val(municipio);
        $('#codigo_municipio_sucursal').val(id_municipio);
        $('#direccion').val(direccion);
        $('#telefono').val(telefono);
        $('#contacto').val(contacto);
        $('#celular').val(celular);
        $('#correo').val(correo);

        removerItem(boton);
    }

    function modificarItems2(boton) {

        var id   = $(boton).parents('tr:first').attr('id').split('_')[1];
        var idTr = $(boton).parents('tr:first').attr('id');

        var nombre        = $("input[name='nombres_sucursales["+id+"]']").val();
        var municipio     = $("input[name='nombres_municipios["+id+"]']").val();
        var id_municipio  = $("input[name='municipios_sucursales["+id+"]']").val();
        var direccion     = $("input[name='direcciones_sucursales["+id+"]']").val();
        var telefono      = $("input[name='telefonos_sucursales["+id+"]']").val();
        var contacto      = $("input[name='contactos["+id+"]']").val();
        var celular       = $("input[name='celulares_sucursales["+id+"]']").val();
        var correo        = $("input[name='correos_sucursales["+id+"]']").val();
        var estado        = $("input[name='estadoModificar["+id+"]']").val();
        var codigo        = $("input[name='itemTabla["+id+"]']").val();

        $('#nombre_sucursal').val(nombre);
        $('#selector2').val(municipio);
        $('#codigo_municipio_sucursal').val(id_municipio);
        $('#direccion').val(direccion);
        $('#telefono').val(telefono);
        $('#contacto').val(contacto);
        $('#celular').val(celular);
        $('#correo').val(correo);
        if(estado=='1'){
            $('#estadoRegistros').val("MOD");
            $('#id_sucursal').val(codigo);
            $('#id_fila').val(idTr);
        }else{
            $('#estadoRegistros').val("ADD");
            $('#id_sucursal').val('');
            $('#id_fila').val('');
            removerItem(boton);
        }
    }

    function removerItemTotal(boton) {
        var destino = $('#URLFormulario').val();
        var id = $(boton).parents('tr:first').attr('id').split('_')[1];

        var confirmar_texto  = $('#confirmar').val();
        var confirmar_texto2 = $('#confirmar2').val();
        var codigo           = $('#codigo_banco').val();
        var estado           = $("input[name='estadoModificar["+id+"]']").val();

        if(estado=='1'){
            if(confirm(confirmar_texto)){
                $.getJSON(destino,{eliminarSucursal:true, id:id, banco:codigo}, function(datos){
                    if(parseInt(datos[0]) == 2){
                        $(boton).parents('tr').remove();
                        alert(datos[1]);
                    }else{
                        alert(datos[1]);
                    }
                });
            }
        }else{
            if(confirm(confirmar_texto2)){
                $(boton).parents('tr').remove();
            }
        }
    }

    function agregarItemSucursal2() {

        var nombre        = $('#nombre_sucursal').val();
        var municipio     = $('#selector2').val();
        var id_municipio  = $('#codigo_municipio_sucursal').val();
        var direccion     = $('#direccion').val();
        var telefono      = $('#telefono').val();
        var contacto      = $('#contacto').val();
        var correo        = $('#correo').val();
        var celular       = $('#celular').val();
        var error_datos   = $('#error_datos').val();
        var orden         = parseInt($('#contador_sucursal').val());
        var modificar     = '0';

        if (nombre && id_municipio && municipio && direccion && telefono) {

            var boton      = $('#removedorSucursal').html();
            var boton1     = $('#botonModificar').html();
            var valorClase = '';

            if ($("#lista_items_sucursales tr:last").hasClass("even")) {
                valorClase = 'odd';
            } else {
                valorClase = 'even';
            }

            if($('#estadoRegistros').val()=="MOD"){
                orden    =$('#id_sucursal').val();
                modificar = '1';
                var fila =$('#id_fila').val();
                $('#lista_items_sucursales').find('#'+fila).each(function () {
                    $(this).remove();
                });
            }

            var item  = '<tr id="fila_'+orden+'" class="'+valorClase+'">'+
                    '<td align="center">'+
                    '<input type="hidden" class="itemTabla" name="itemTabla['+orden+']" value="'+orden+'">'+
                    '<input type="hidden" class="nombres_sucursales" name="nombres_sucursales['+orden+']" value="'+nombre+'">'+
                    '<input type="hidden" class="municipios_sucursales" name="municipios_sucursales['+orden+']" value="'+id_municipio+'">'+
                    '<input type="hidden" class="nombres_municipios" name="nombres_municipios['+orden+']" value="'+municipio+'">'+
                    '<input type="hidden" class="direcciones_sucursales" name="direcciones_sucursales['+orden+']" value="'+direccion+'">'+
                    '<input type="hidden" class="telefonos_sucursales" name="telefonos_sucursales['+orden+']" value="'+telefono+'">'+
                    '<input type="hidden" class="contactos" name="contactos['+orden+']" value="'+contacto+'">'+
                    '<input type="hidden" class="celulares_sucursales" name="celulares_sucursales['+orden+']" value="'+celular+'">'+
                    '<input type="hidden" class="correos_sucursales" name="correos_sucursales['+orden+']" value="'+correo+'">'+
                    '<input type="hidden" class="estadoModificar" name="estadoModificar['+orden+']" value="'+modificar+'">'+
                    boton+
                    boton1+
                    '</td>'+
                    '<td align="left">'+nombre+'</td>'+
                    '<td align="left">'+municipio+'</td>'+
                    '<td align="left">'+direccion+'</td>'+
                    '<td align="right">'+telefono+'</td>'+
                    '<td align="left">'+contacto+'</td>'+
                    '<td align="right">'+celular+'</td>'+
                    '<td align="left">'+correo+'</td>'+
                '</tr>';

            $('#lista_items_sucursales').append(item);
            $('#nombre_sucursal').val('');
            $('#selector2').val('');
            $('#codigo_municipio_sucursal').val('');
            $('#direccion').val('');
            $('#telefono').val('');
            $('#contacto').val('');
            $('#correo').val('');
            $('#celular').val('');
            $('#sucursal').val('');

            if($('#estadoRegistros').val()=="MOD"){
                orden = parseInt($('#contador_sucursal').val());
            }else{
                orden++;
            }
            $('#contador_sucursal').val(orden);

            $('#estadoRegistros').val("ADD");
            $('#id_sucursal').val('');
            $('#id_fila').val('');
        }else{
            alert(error_datos);
        }
    }
