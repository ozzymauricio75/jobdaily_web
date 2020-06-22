    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    /*** Adicionar combinaciones de teclas para el manejo de botones***/
    $(document).bind('keydown', 'Ctrl+a', function(evt) {$('#ADICOCPR').click(); return false;});
    $(document).bind('keydown', 'Ctrl+c', function(evt) {$('#CONSOCPR').click(); return false;});
    //$(document).bind('keydown', 'Ctrl+m', function(evt) {$('#MODICARA').click(); return false;});
    //$(document).bind('keydown', 'Ctrl+e', function(evt) {$('#ELIMCARA').click(); return false;});

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

    function activaCampos(tipo,descuento){
        if (tipo == 1){
            var clase1 = "aplica_descuento_global"+descuento;
            var clase2 = "global"+descuento;
        } else if(tipo==2){
            var clase1 = "descuento_financiero_fijo";
            var clase2 = "fijo";
        } else if(tipo==3){
            var clase1 = "descuento_financiero_pronto_pago";
            var clase2 = "pronto_pago";
        } else if (tipo==4){
            var clase1 = "descuento_linea";
            var clase2 = "linea";
        } else if (tipo==5){
            var clase1 = "maneja_criterio";
            var clase2 = "criterio";
        } else if (tipo==6){
            var clase1 = "maneja_caracteristicas";
            var clase2 = "caracteristica";
        } else if (tipo==7){
            var clase1 = "maneja_color";
            var clase2 = "colores";
        } else if (tipo==8){
            var clase1 = "maneja_criterio_articulo";
            var clase2 = "criterio";
        }
        if ($("."+clase1).is(':checked')){

            if (tipo== 6){
                var continuar = cargarDatosDesdeEstructura(2);
            }
            if ((tipo == 6 && continuar) || tipo !=6){
                if (tipo != 5){
                    $("."+clase2).parent().show();
                    $("."+clase2).removeAttr('disabled');
                    $("."+clase2).parent().show();
                } else {
                    var continuar = cargarDatosDesdeEstructura(1);
                }
            } else if((tipo ==5 || tipo ==6) && !continuar){
                $("."+clase1+":checkbox").removeAttr('checked');
                $("."+clase2).parent().hide();
                $("."+clase2).attr('disabled','disabled');
                $("."+clase2).val('');
            }
            if (descuento >= 1 && descuento <= 3){
                var descuento2 = parseInt(descuento) + 1;
                $(".aplica_descuento_global"+descuento2).parent().show();
                $(".aplica_descuento_global"+descuento2).removeAttr('disabled');
                $("#descuento_global"+descuento2).val('');
            }
        } else {
            $("."+clase2).parent().hide();
            $("."+clase2).attr('disabled','disabled');
            $("."+clase2).val('');
            if (tipo==5 || tipo==8){
                $('#id_subnivel_articulo').parent().hide();
                $('#id_subnivel_articulo').attr("disabled","disabled");
                $('#id_criterio_subnivel_articulo').parent().hide();
                $('#id_criterio_subnivel_articulo').attr("disabled","disabled");
                $('#id_criterio_subnivel_articulo').val("0");
                $('#id_concepto_criterio_subnivel_articulo').parent().hide();
                $('#id_concepto_criterio_subnivel_articulo').attr("disabled","disabled");
                $('#id_concepto_criterio_subnivel_articulo').val("0");
                $('#id_concepto_criterio_subnivel_articulo').html("");
            }
            if (descuento >= 1 && descuento <= 3){
                var descuento2 = parseInt(descuento) + 1;
                for (i=descuento2;i<=3;i++){
                    $(".global"+i).parent().hide();
                    $(".global"+i).attr('disabled','disabled');
                    $(".global"+i).val('');
                    $(".aplica_descuento_global"+i).parent().hide();
                    $(".aplica_descuento_global"+i).removeAttr('checked');
                    $(".aplica_descuento_global"+i).attr('disabled','disabled');
                }
            }
        }
    }

    function activaFechaFinal(){

        var fecha = $("#fecha_documento").val().split("-");
        ano = parseInt(fecha[0]);
        mes = parseInt(fecha[1])-1;
        dia = parseInt(fecha[2]);
        var fecha_minima = new Date(ano,mes,dia);
        var numero_entregas = $("#numero_entregas").val();
        if (numero_entregas != undefined && numero_entregas > 0){
            $(".fechas_entregas").parent().show();
            $(".fechas_entregas").removeAttr("disabled");
            $(".fecha_final_entregas").removeClass("selectorFechaBloquear");
            $(".fecha_final_entregas").removeClass("hasDatepicker");
            $(".fecha_final_entregas").val($("#fecha_documento").val());
            $(".fecha_final_entregas").next().remove();
            $('.fecha_final_entregas').datepicker($.extend({},
                $.datepicker.regional['es'], {
                    showOn: 'both',
                    dateFormat: 'yy-mm-dd',
                    buttonImage: 'imagenes/calendario.png',
                    buttonImageOnly: true,
                    minDate : fecha_minima
            })).attr('readonly', 'readonly');
        } else {
            $(".fechas_entregas").parent().hide();
            $(".fechas_entregas").attr("disabled","disabled");
            $(".fecha_final_entregas").val("");
        }

        $(".fecha_final_entrega_articulo").removeClass("selectorFechaBloquear");
        $(".fecha_final_entrega_articulo").removeClass("hasDatepicker");
        $(".fecha_final_entrega_articulo").val($("#fecha_documento").val());
        $(".fecha_final_entrega_articulo").next().remove();
        $('.fecha_final_entrega_articulo').datepicker($.extend({},
            $.datepicker.regional['es'], {
                showOn: 'both',
                dateFormat: 'yy-mm-dd',
                buttonImage: 'imagenes/calendario.png',
                buttonImageOnly: true,
                minDate : fecha_minima
        })).attr('readonly', 'readonly');
    }

    function acProveedor(item){
        var destino       = $('#URLFormulario').val();
        $(".ac_results").remove();
        $(item).autocomplete(destino, {
            minChars: 3,
            width: 250,
            max: 100,
            delay: 100,
            matchContains: true,
            cacheLength: 100,
            extraParams: {
                cargarTercero: true
            },
            formatResult: function(datos, valor) {return valor.split("|")[0];}
        }).blur(function() {
            var x = $(this).attr('id');
            $(x).autocomplete().remove();
        }).result(function(evento, datos, formateado) {
            if (datos) {
                $("#nit_proveedor").val(datos[1]);
                $("#municipio_proveedor").val(datos[2]);
                $("#id_municipio").val(datos[3]);
                $("#direccion").val(datos[4]);
                $("#telefono").val(datos[5]);
                $("#celular").val(datos[6]);
                $("#correo_electronico").val(datos[7]);
                $("#regimen").val(datos[8]);
                $("#participacion").val(datos[9]);
                if (datos[8] == "2"){
                    $(".iva_incluido").removeAttr('checked');
                    $(".iva_incluido").attr("disabled","disabled");
                    $(".iva_incluido").parent().hide();
                    $("#regimen_actual").val("2");
                    $("#participacion").val(0);
                    $("#participacion").parent().hide();
                    $("#participacion").attr("disabled","disabled");
                } else {
                    $(".iva_incluido").removeAttr('disabled');
                    $(".iva_incluido").removeAttr('checked');
                    $(".iva_incluido").parent().show();
                    $("#regimen_actual").val("1");
                    $("#participacion").parent().show();
                    $("#participacion").removeAttr("disabled");
                }
            }
        });
    }

    function cargarProveedor(){
        var destino                = $('#URLFormulario').val();
        var nit_proveedor          = parseInt($('#selector4').val());
        var razon_social_proveedor = $('#razon_social_proveedor').val();

        $.getJSON(destino, {cargarProveedor: true, nit_proveedor: nit_proveedor}, function(datos) {
            if (datos[0]) {
                $("#razon_social_proveedor").val(datos[0]);
                $("#municipio_proveedor").val(datos[1]);
                $("#id_municipio").val(datos[2]);
                $("#direccion").val(datos[3]);
                $("#telefono").val(datos[4]);
                $("#celular").val(datos[5]);
                $("#correo_electronico").val(datos[6]);
                $("#regimen").val(datos[7]);
                $("#participacion").val(datos[8]);
                if (datos[7] == "2"){
                    $(".iva_incluido").removeAttr('checked');
                    $(".iva_incluido").attr("disabled","disabled");
                    $(".iva_incluido").parent().hide();
                    $("#regimen_actual").val("2");
                    $("#participacion").val(0);
                    $("#participacion").parent().hide();
                    $("#participacion").attr("disabled","disabled");
                } else {
                    $(".iva_incluido").removeAttr('disabled');
                    $(".iva_incluido").removeAttr('checked');
                    $(".iva_incluido").parent().show();
                    $("#regimen_actual").val("1");
                    $("#participacion").parent().show();
                    $("#participacion").removeAttr("disabled");
                }
            }
        });
    }

    function activaDetalle(){

        var cantidad_total = $("#cantidad_total_articulo").val();

        if (cantidad_total != undefined && cantidad_total > 0){
            $(".detalle_pedido").parent().show();
            $(".detalle_pedido").removeAttr("disabled");
            $("#cantidad_total_control").val(0);
            $("#cantidad_total_control").val(cantidad_total);
            $("#cantidad_pendiente").html(cantidad_total);
        } else {
            $(".detalle_pedido").parent().hide();
            $(".detalle_pedido").attr("disabled","disabled");
            $("#cantidad_total_control").val(0);
            $("#cantidad_detalle").val('');
            $("#cantidad_pendiente").html(0);
        }
    }

    function activaArticulos(campo){
        $("#articulo").val('');
        $("#id_articulo").val('');
        $("#cantidad_total_articulo").val('');
        $("#cantidad_total_control").val('');
        $(".concepto").parent().hide();
        $(".concepto").attr('disabled','disabled');
        $(".concepto").html('');
        $(".colores").parent().hide();
        $(".colores").attr('disabled','disabled');
        //$("#id_color").val(0);
        $("#id_color").val("");
        $(".detalle_pedido").parent().hide();
        $(".detalle_pedido").attr('disabled','disabled');
        $("#cantidad_detalle").val('');
        $(".descuento_linea").removeAttr('checked');
        $(".linea").parent().hide();
        $(".linea").attr('disabled','disabled');
        $("#descuento_linea").val('');
        $("#observaciones_articulo").val('');
        $("#valor_unitario").val('');
        $("#cantidad_pendiente").html('');
        $("#id_categoria").val(0);
        $("#grupo1").val(0);
        $(".grupo1").parent().hide();
        $(".grupo1").attr('disabled','disabled');
        $("#grupo2").val(0);
        $(".grupo2").parent().hide();
        $(".grupo2").attr('disabled','disabled');
        $("#grupo3").val(0);
        $(".grupo3").parent().hide();
        $(".grupo3").attr('disabled','disabled');
        $("#grupo4").val(0);
        $(".grupo4").parent().hide();
        $(".grupo4").attr('disabled','disabled');
        $("#grupo5").val(0);
        $(".grupo5").parent().hide();
        $(".grupo5").attr('disabled','disabled');
        $("#grupo6").val(0);
        $(".grupo6").parent().hide();
        $(".grupo6").attr('disabled','disabled');
        $("#referencia").val('');
        $("#detalle").val('');
        $(".maneja_criterio").removeAttr('checked');
        $(".maneja_criterio_articulo").removeAttr('checked');
        $(".criterio").parent().hide();
        $(".criterio").attr('disabled','disabled');
        $(".criterio").html('');
        $("#id_subnivel_articulo").parent().hide();
        $("#id_subnivel_articulo").attr('disabled','disabled');
        $("#id_subnivel_articulo").html('');
        $("#id_subnivel_articulo").val('0');
        $(".maneja_criterio_articulo").parent().hide();
        $(".maneja_criterio_articulo").attr('disabled','disabled');
        $(".maneja_criterio_articulo").html('');
        $(".maneja_color").removeAttr('checked');
        $(".maneja_color").removeAttr('disabled');
        $(".maneja_caracteristicas").removeAttr('checked');
        $("#listaCaracteristica tbody").parents('tr').remove();
        $(".caracteristica").parent().hide();
        $(".caracteristica").attr('disabled','disabled');
        $("#foto_articulo").val('');

        if ($(campo).is(':checked')){
            $(".articulo_nuevo").parent().show();
            $(".articulo_nuevo").removeAttr("disabled");
            $(".articulo_existe").parent().hide();
            $(".articulo_existe").attr("disabled","disabled");
            $(".maneja_criterio_articulo").parent().hide();
            $(".maneja_criterio_articulo").attr("disabled","disabled");
        } else {
            $(".articulo_existe").parent().show();
            $(".articulo_existe").removeAttr("disabled");
            $(".maneja_criterio_articulo").parent().show();
            $(".maneja_criterio_articulo").removeAttr("disabled");
            $(".articulo_nuevo").parent().hide();
            $(".articulo_nuevo").attr("disabled","disabled");
        }
    }

    function cargarDatosDesdeEstructura(dato){
        var destino = $('#URLFormulario').val();
        var id_categoria = $("#id_categoria").val();
        if (id_categoria==undefined){
            id_categoria = "0";
        }
        var id_grupo1 = $("#id_grupo1").val();
        if (id_grupo1==undefined){
            id_grupo1 = "0";
        }
        var id_grupo2 = $("#id_grupo2").val();
        if (id_grupo2==undefined){
            id_grupo2 = "0";
        }
        var id_grupo3 = $("#id_grupo3").val();
        if (id_grupo3==undefined){
            id_grupo3 = "0";
        }
        var id_grupo4 = $("#id_grupo4").val();
        if (id_grupo4==undefined){
            id_grupo4 = "0";
        }
        var id_grupo5 = $("#id_grupo5").val();
        if (id_grupo5==undefined){
            id_grupo5 = "0";
        }
        var id_grupo6 = $("#id_grupo6").val();
        if (id_grupo6==undefined){
            id_grupo6 = "0";
        }
        var nivel = $("#nivel").val();

        var continuar = true;
        if (nivel == 2 && id_grupo1 == 0){
            continuar = false;
        }
        if (nivel == 3 && id_grupo2 == 0){
            continuar = false;
        }
        if (nivel == 4 && id_grupo3 == 0){
            continuar = false;
        }
        if (nivel == 5 && id_grupo4 == 0){
            continuar = false;
        }
        if (nivel == 6 && id_grupo5 == 0){
            continuar = false;
        }
        if (nivel == 7 && id_grupo6 == 0){
            continuar = false;
        }

        if (id_categoria > 0 && id_grupo1 > 0 && continuar){
            var estructura = id_categoria + "|" + id_grupo1 + "|" + id_grupo2 + "|" + id_grupo3 + "|" + id_grupo4 + "|" +id_grupo5 + "|" + id_grupo6;
            $.getJSON(destino, {cargarDatosDesdeEstructura: true, dato: dato, estructura: estructura}, function(datos){
                if (datos){
                    if (dato == 1){
                        var elemento = "id_criterio_subnivel_articulo";
                    } else {
                        var elemento = "id_caracteristica";
                    }
                    var lista = "";
                    if (datos[0] == "subnivel_articulo"){
                        cargarSubnivel();
                    } else {
                        jQuery.each(datos, function(valor, texto) {
                            if (dato == 1){
                                lista = lista+'<option class="criterio'+valor+' id_criterio" value="'+valor+'">'+texto+'</option>';
                            } else {
                                lista = lista+'<option class="caracteristica'+valor+' id_caracteristica" value="'+valor+'">'+texto+'</option>';
                            }
                        });
                        $('#'+elemento).html(lista);
                    }
                }
            });
            return(true);
        } else {
            alert($("#datos_incompletos_estructura").val());
            return(false);
        }
    }

    function cargarSubnivel(){
        var destino = $('#URLFormulario').val();
        if ($(".maneja_criterio").is(":checked")){
            $.getJSON(destino, {cargarSubnivel: true}, function(datos){
                if (datos){
                    var lista = "";
                    $('#id_subnivel_articulo').parent().show();
                    $('#id_subnivel_articulo').removeAttr("disabled");
                    jQuery.each(datos, function(valor, texto) {
                        lista = lista+'<option class="subnivel_'+valor+' id_subnivel" value="'+valor+'">'+texto+'</option>';
                    });
                    $('#id_subnivel_articulo').html(lista);
                }
            });
        } else {
            $('#id_subnivel_articulo').parent().hide();
            $('#id_subnivel_articulo').attr("disabled","disabled");
            $('#id_criterio_subnivel_articulo').parent().hide();
            $('#id_criterio_subnivel_articulo').attr("disabled","disabled");
            $('#id_criterio_subnivel_articulo').val("0");
            $('#id_concepto_criterio_subnivel_articulo').parent().hide();
            $('#id_concepto_criterio_subnivel_articulo').attr("disabled","disabled");
            $('#id_concepto_criterio_subnivel_articulo').val("0");
            $('#id_concepto_criterio_subnivel_articulo').html("");
        }
    }

    function cargarSubnivelArticulo(){
        var destino = $('#URLFormulario').val();
        if ($(".maneja_criterio_articulo").is(":checked")){
            var id_articulo = parseInt($("#id_articulo").val());
            //if (id_articulo != undefined && isNaN(id_articulo) && id_articulo > 0){
            if (id_articulo > 0){
                $.getJSON(destino, {datosCriterioArticulo: true, id_articulo: id_articulo}, function(datos){
                    console.log(datos[0]);
                    if (datos[0] == "subniveles"){
                        $('#id_subnivel_articulo').parent().show();
                        $('#id_subnivel_articulo').removeAttr("disabled","disabled");
                        $('#id_criterio_subnivel_articulo').parent().hide();
                        $('#id_criterio_subnivel_articulo').attr("disabled","disabled");
                        $('#id_criterio_subnivel_articulo').val("0");
                        $('#id_concepto_criterio_subnivel_articulo').parent().hide();
                        $('#id_concepto_criterio_subnivel_articulo').attr("disabled","disabled");
                        $('#id_concepto_criterio_subnivel_articulo').val("0");
                        $('#id_concepto_criterio_subnivel_articulo').html("");
                        $.getJSON(destino, {cargarSubnivel: true}, function(datos){
                            if (datos){
                                var lista = "";
                                $('#id_subnivel_articulo').parent().show();
                                $('#id_subnivel_articulo').removeAttr("disabled");
                                jQuery.each(datos, function(valor, texto) {
                                    lista = lista+'<option class="subnivel_'+valor+' id_subnivel" value="'+valor+'">'+texto+'</option>';
                                });
                                $('#id_subnivel_articulo').html(lista);
                            }
                        });
                    } else {
                        var lista = "";
                        $('#id_subnivel_articulo').parent().hide();
                        $('#id_subnivel_articulo').attr("disabled","disabled");
                        $('#id_criterio_subnivel_articulo').parent().hide();
                        $('#id_criterio_subnivel_articulo').attr("disabled","disabled");
                        $('#id_criterio_subnivel_articulo').val("0");
                        $('#id_concepto_criterio_subnivel_articulo').parent().show();
                        $('#id_concepto_criterio_subnivel_articulo').removeAttr("disabled");
                        jQuery.each(datos, function(valor, texto) {
                            lista = lista+'<option class="concepto_'+valor+' id_concepto" value="'+valor+'">'+texto+'</option>';
                        });
                        $('#id_concepto_criterio_subnivel_articulo').html(lista);
                    }
                });
            }
        } else {
            $('#id_subnivel_articulo').parent().hide();
            $('#id_subnivel_articulo').attr("disabled","disabled");
            $('#id_criterio_subnivel_articulo').parent().hide();
            $('#id_criterio_subnivel_articulo').attr("disabled","disabled");
            $('#id_criterio_subnivel_articulo').val("0");
            $('#id_concepto_criterio_subnivel_articulo').parent().hide();
            $('#id_concepto_criterio_subnivel_articulo').attr("disabled","disabled");
            $('#id_concepto_criterio_subnivel_articulo').val("0");
            $('#id_concepto_criterio_subnivel_articulo').html("");
        }
    }

    function cargaCriterio(){
        var destino = $('#URLFormulario').val();
        var id_subnivel_articulo = parseInt($("#id_subnivel_articulo").val());
        if (id_subnivel_articulo > 0){
            $.getJSON(destino, {cargarCriterio: true, id_subnivel_articulo: id_subnivel_articulo}, function(datos){
                if (datos){
                    var lista = "";
                    $('#id_criterio_subnivel_articulo').parent().show();
                    $('#id_criterio_subnivel_articulo').removeAttr("disabled");
                    jQuery.each(datos, function(valor, texto) {
                        lista = lista+'<option class="subnivel_'+valor+' id_subnivel" value="'+valor+'">'+texto+'</option>';
                    });
                    $('#id_criterio_subnivel_articulo').html(lista);
                }
            });
        } else {
            $('#id_criterio_subnivel_articulo').parent().hide();
            $('#id_criterio_subnivel_articulo').attr("disabled","disabled");
        }
    }

    function removerDatosEstructura(){
        $('#id_criterio_subnivel_articulo').html('');
        $('#id_criterio_subnivel_articulo').val('');
        $('#id_caracteristica').html('');
        $('#id_caracteristica').val('');
        $(".maneja_criterio:checkbox").removeAttr('checked');
        $(".maneja_criterio_articulo:checkbox").removeAttr('checked');
        $(".criterio").parent().hide();
        $(".criterio").attr('disabled','disabled');
        $(".criterio").val('');
        $("#id_subnivel_articulo").parent().hide();
        $("#id_subnivel_articulo").attr('disabled','disabled');
        $("#id_subnivel_articulo").html('');
        $("#id_subnivel_articulo").val('0');
        $(".maneja_criterio_articulo").parent().hide();
        $(".maneja_criterio_articulo").attr('disabled','disabled');
        $(".maneja_criterio_articulo").val('');
        $(".maneja_caracteristicas:checkbox").removeAttr('checked');
        $(".caracteristica").parent().hide();
        $(".caracteristica").attr('disabled','disabled');
        $(".caracteristica").val('');
        $('#listaCaracteristica tbody tr').remove();
    }

    function agregarCaracteristica(){
        var id_caracteristica = $("#id_caracteristica").val();
        var caracteristica = $(".caracteristica"+id_caracteristica).html();
        var existe = false;
        $("#listaCaracteristica").find('.id_caracteristica_tabla').each(function () {
            id_tabla = $(this).val();
            if (id_tabla == id_caracteristica){
                existe = true;
            }
        });
        if (!existe && id_caracteristica>0){
            var valorClase = '';
            if ($("#listaCaracteristica tr:last").hasClass("even")) {
                valorClase = 'odd';
            } else {
                valorClase = 'even';
            }
            var remover = $('#removerCaracteristica').html();
            var item = '<tr id="id_caracteristica'+id_caracteristica+'" class="'+valorClase+'">'+
                            '<td align="left">'+
                                '<input class="id_caracteristica_tabla" type="hidden" name="id_caracteristica_tabla['+id_caracteristica+']" value="'+id_caracteristica+'">'+remover+
                            '</td>'+
                            '<td align="left">'+caracteristica+'</td>'+
                        '</tr>';
            $('#listaCaracteristica').append(item);
        } else {
            if (existe){
                alert($("#existe_caracteristica").val());
            }
            if (id_caracteristica <= 0){
                alert($("#seleccionar_caracteristica").val());
            }
        }
    }

    function removerCaracteristica(boton){
        $(boton).parents('tr').remove();
    }

    /*function activaConcepto(){
        var destino = $('#URLFormulario').val();
        var articulo_existe = true;
        if ($(".crear_articulo").is(':checked')){
            articulo_existe = false;
        }
    }*/

    function recargarDatos(id_articulo, campo){
        var destino = $('#URLFormulario').val();
        var descripcion = $("#"+campo).val();
        $.getJSON(destino, {cargarDatosArticulo: true, id_articulo:id_articulo, articulo_nuevo: true}, function(datos){
            if (datos){
                var lista = "";
                var color = false;
                jQuery.each(datos, function(valor, texto) {
                    if (valor == "referencia"){
                        $("#referencia").parent().show();
                        $("#referencia").removeAttr("disabled");
                        $("#referencia").val(texto);
                    } else if (valor == "color"){
                        $(".maneja_color").parent().show();
                        if (texto){
                            $(".colores").parent().show();
                            $(".colores").removeAttr('disabled');
                            $(".maneja_color").attr('checked','checked');
                            color = true;
                        } else {
                            $(".colores").parent().hide();
                            $(".colores").attr('disabled','disabled');
                            //$("#id_color").val(0);
                            $("#id_color").val("");
                            $(".maneja_color").removeAttr('checked');
                        }
                    } else if (valor == "criterios"){
                        $(".maneja_criterio_articulo").parent().show();
                        $(".maneja_criterio_articulo").removeAttr('disabled');
                        $(".maneja_criterio_articulo").removeAttr('checked');
                        $(".concepto").parent().hide();
                        $(".concepto").attr("disabled","disabled");
                        $('#id_concepto_criterio_subnivel_articulo').parent().hide();
                    } else {
                        $(".maneja_criterio_articulo").parent().show();
                        $(".maneja_criterio_articulo").removeAttr('disabled');
                        $(".maneja_criterio_articulo").attr('checked','checked');
                        $(".concepto").parent().show();
                        $(".concepto").removeAttr("disabled");
                        lista = lista+'<option class="concepto'+valor+' id_concepto" value="'+valor+'">'+texto+'</option>';
                    }
                });
                if (!color){
                    $(".colores").parent().hide();
                    $(".colores").attr('disabled','disabled');
                    //$("#id_color").val(0);
                    $("#id_color").val("");
                }
                if (lista != ""){
                    $('#id_concepto_criterio_subnivel_articulo').html(lista);
                } else {
                    $('#id_concepto_criterio_subnivel_articulo').parent().hide();
                }
            }
        });
    }

    function activaConcepto(){
        var destino = $('#URLFormulario').val();
        var id_criterio = parseInt($("#id_criterio_subnivel_articulo").val());
        $.getJSON(destino, {cargarDatosArticulo: true, id_criterio:id_criterio}, function(datos){
            if (datos){
                $(".concepto").parent().show();
                $(".concepto").removeAttr("disabled");
                var lista = "";
                jQuery.each(datos, function(valor, texto) {
                    lista = lista+'<option class="concepto'+valor+' id_concepto" value="'+valor+'">'+texto+'</option>';
                });
                $('#id_concepto_criterio_subnivel_articulo').html(lista);
            } else {
                $(".concepto").parent().hide();
                $(".concepto").attr("disabled","disabled");
                $('#id_concepto_criterio_subnivel_articulo').html('');
                if (id_criterio > 0){
                    alert($("#no_maneja_conceptos").val());
                }
            }
        });
    };

    function agregarArticulo(){
        var destino = $('#URLFormulario').val();
        var continuar = true;
        var mensajes = new Array();
        var contador_mensajes = 1;

        /////////// Encabezado ///////////////////
        var nit_proveedor = parseInt($("#nit_proveedor").val());
        if (nit_proveedor == undefined || isNaN(nit_proveedor)|| nit_proveedor == 0){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_nit_proveedor").val();
            contador_mensajes++;
        }
        var razon_social_proveedor = $("#razon_social_proveedor").val();
        if (razon_social_proveedor == undefined || razon_social_proveedor == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_razon_social_proveedor").val();
            contador_mensajes++;
        }

        /////////// Pestaña Datos generales ///////////////////
        var id_municipio = parseInt($("#id_municipio").val());
        if (id_municipio == undefined || isNaN(id_municipio) || id_municipio == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_municipio").val();
            contador_mensajes++;
        }
        var direccion = $("#direccion").val();
        if (direccion == undefined|| direccion == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_direccion").val();
            contador_mensajes++;
        }
        var telefono = $("#telefono").val();
        if (telefono == undefined || telefono == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_telefono").val();
            contador_mensajes++;
        }
        var celular = $("#celular").val();
        var correo_electronico = $("#correo_electronico").val();
        var id_sucursal_pedido = $("#id_sucursal").val();
        var fecha_pedido = $("#fecha_documento").val();
        if (fecha_pedido == undefined || fecha_pedido == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_fecha_pedido").val();
            contador_mensajes++;
        }
        var id_tipo_documento = $("#id_tipo_documento").val();
        var id_tipo_entrada_salida = $("#id_tipo_entrada_salida").val();
        /////////// Pestaña Datos negociación ///////////////////
        var id_comprador = $("#id_comprador").val();
        var participacion = parseFloat($("#participacion").val());
        if (participacion == undefined || isNaN(participacion) || participacion == ""){
            participacion = 0;
        }
        var descuento_financiero_fijo = 0;
        if ($(".descuento_financiero_fijo").is(':checked')){
            descuento_financiero_fijo = parseFloat($("#descuento_financiero_fijo").val());
            if (descuento_financiero_fijo == undefined || isNaN(descuento_financiero_fijo) || descuento_financiero_fijo==0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_descuento_financiero_fijo").val();
                contador_mensajes++;
            }
        }
        var descuento_financiero_pronto_pago = 0;
        if ($(".descuento_financiero_pronto_pago").is(':checked')){
            descuento_financiero_pronto_pago = parseFloat($("#descuento_financiero_pronto_pago").val());
            if (descuento_financiero_pronto_pago == undefined || isNaN(descuento_financiero_pronto_pago) || descuento_financiero_pronto_pago==0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_descuento_financiero_pronto_pago").val();
                contador_mensajes++;
            }
        }
        var descuento_global1 = 0;
        if ($(".aplica_descuento_global1").is(':checked')){
            descuento_global1 = parseFloat($("#descuento_global1").val());
            if (descuento_global1 == undefined || isNaN(descuento_global1) || descuento_global1==0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_descuento_global1").val();
                contador_mensajes++;
            }
        }
        var descuento_global2 = 0;
        if ($(".aplica_descuento_global2").is(':checked')){
            descuento_global2 = parseFloat($("#descuento_global2").val());
            if (descuento_global2 == undefined || isNaN(descuento_global2) || descuento_global2==0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_descuento_global2").val();
                contador_mensajes++;
            }
        }
        var descuento_global3 = 0;
        if ($(".aplica_descuento_global3").is(':checked')){
            descuento_global3 = parseFloat($("#descuento_global3").val());
            if (descuento_global3 == undefined || isNaN(descuento_global3) || descuento_global3==0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_descuento_global3").val();
                contador_mensajes++;
            }
        }
        var iva_incluido = false;
        if ($(".iva_incluido").is(':checked')){
            iva_incluido = true;
        }
        var numero_dias_pago = $("#numero_dias_pago").val();
        if (numero_dias_pago == undefined){
            numero_dias_pago = 0;
        }
        var numero_entregas = parseInt($("#numero_entregas").val());
        var fecha_final_entregas = "0000-00-00";
        if (numero_entregas == undefined || isNaN(numero_entregas) || numero_entregas == 0){
            numero_entregas = 0;
        } else if(numero_entregas > 0){
            fecha_final_entregas = $("#fecha_final_entregas").val();
            if (fecha_final_entregas == undefined || fecha_final_entregas == ""){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_fecha_final_entregas").val();
                contador_mensajes++;
            }
        }
        var observaciones = $("#observaciones").val();
        /////////// Pestaña pedidos de articulos ///////////////////
        var crear_articulo = false;
        if ($(".crear_articulo").is(':checked')){
            crear_articulo = true;
        }
        // datos articulo existe //
        var id_articulo = parseInt($("#id_articulo").val());
        var articulo = $("#articulo").val();
        if (!crear_articulo && (id_articulo == undefined || isNaN(id_articulo) || id_articulo == 0)){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_articulo").val();
            contador_mensajes++;
        }
        // datos articulo nuevo //
        var id_detalle = parseInt($("#id_detalle").val());
        var id_estructura_grupo = parseInt($("#id_estructura_grupo").val());
        var referencia = $("#referencia").val();
        var detalle = $("#detalle").val();
        var id_tasa = parseInt($("#id_tasa").val());
        var maneja_color = false;
        if ($(".maneja_color").is(':checked')){
            maneja_color = true;
        }
        var maneja_criterio = false;
        var id_criterio_subnivel_articulo = 0;
        if ($(".maneja_criterio").is(':checked') || $(".maneja_criterio_articulo").is(':checked')){
            maneja_criterio = true;
            id_criterio_subnivel_articulo = parseInt($("#id_criterio_subnivel_articulo").val());
        }
        var maneja_caracteristicas = false;
        if ($(".maneja_caracteristicas").is(':checked')){
            maneja_caracteristicas = true;
        }
        if (crear_articulo){
            if (id_estructura_grupo == undefined || isNaN(id_estructura_grupo) || id_estructura_grupo == 0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_estructura_grupo").val();
                contador_mensajes++;
            } else {
                var id_categoria = $("#id_categoria").val();
                if (id_categoria==undefined || isNaN(id_categoria)){
                    id_categoria = "0";
                }
                var id_grupo1 = $("#id_grupo1").val();
                if (id_grupo1==undefined || isNaN(id_grupo1)){
                    id_grupo1 = "0";
                }
                var id_grupo2 = $("#id_grupo2").val();
                if (id_grupo2==undefined || isNaN(id_grupo2)){
                    id_grupo2 = "0";
                }
                var id_grupo3 = $("#id_grupo3").val();
                if (id_grupo3==undefined || isNaN(id_grupo3)){
                    id_grupo3 = "0";
                }
                var id_grupo4 = $("#id_grupo4").val();
                if (id_grupo4==undefined || isNaN(id_grupo4)){
                    id_grupo4 = "0";
                }
                var id_grupo5 = $("#id_grupo5").val();
                if (id_grupo5==undefined || isNaN(id_grupo5)){
                    id_grupo5 = "0";
                }
                var id_grupo6 = $("#id_grupo6").val();
                if (id_grupo6==undefined || isNaN(id_grupo6)){
                    id_grupo6 = "0";
                }
                var nivel = $("#nivel").val();

                var continuar_estructura = true;
                if (nivel == 2 && id_grupo1 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 3 && id_grupo2 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 4 && id_grupo3 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 5 && id_grupo4 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 6 && id_grupo5 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 7 && id_grupo6 == 0){
                    continuar_estructura = false;
                }
                if (!continuar_estructura){
                    continuar = false;
                    mensajes[contador_mensajes] = $("#error_estructura_grupo").val();
                    contador_mensajes++;
                }
            }
            if (referencia == undefined || referencia == ""){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_referencia").val();
                contador_mensajes++;
            }
            if (detalle == undefined || detalle == ""){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_detalle").val();
                contador_mensajes++;
            }
            if (id_tasa == undefined || isNaN(id_tasa) || id_tasa == 0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_tasa").val();
                contador_mensajes++;
            }
            if (maneja_criterio && (id_criterio_subnivel_articulo == undefined || isNaN(id_criterio_subnivel_articulo) || id_criterio_subnivel_articulo == 0)){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_criterio").val();
                contador_mensajes++;
            }
            if (maneja_caracteristicas){
                existe_caracteristica = false;
                $("#listaCaracteristica").find('.id_caracteristica_tabla').each(function () {
                    existe_caracteristica = true;
                });
                $("#listaCaracteristica tbody tr td").find(".")
                if (!existe_caracteristica){
                    continuar = false;
                    mensajes[contador_mensajes] = $("#error_caracteristica").val();
                    contador_mensajes++;
                }
            }
        }

        // datos pedido //
        var cantidad_total_articulo = parseFloat($("#cantidad_total_articulo").val());
        if (cantidad_total_articulo == undefined || isNaN(cantidad_total_articulo) || cantidad_total_articulo == 0){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_cantidad_total").val();
            contador_mensajes++;
        }
        var cantidad_total_control = parseFloat($("#cantidad_total_control").val());
        var id_unidad = $("#id_unidad").val();
        var valor_unitario = parseFloat($("#valor_unitario").val());
        if (valor_unitario == undefined || isNaN(valor_unitario) || valor_unitario == 0){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_valor_unitario").val();
            contador_mensajes++;
        }
        var descuento_linea = false;
        var porcentaje_descuento_linea = 0;
        if ($(".descuento_linea").is(':checked')){
            descuento_linea = true;
            porcentaje_descuento_linea = parseFloat($("#descuento_linea").val());
            if (porcentaje_descuento_linea == undefined || isNaN(porcentaje_descuento_linea) || porcentaje_descuento_linea == 0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_porcentaje_descuento_linea").val();
                contador_mensajes++;
            }
        }
        var observaciones_articulo = $("#observaciones_articulo").val();
        var id_sucursal_destino = $("#id_sucursal_destino").val();
        var cantidad_detalle = parseFloat($("#cantidad_detalle").val());
        if (cantidad_detalle == undefined || isNaN(cantidad_detalle) ||  cantidad_detalle == 0){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_cantidad_detalle").val();
            contador_mensajes++;
        }
        var id_concepto_criterio_subnivel_articulo = parseInt($("#id_concepto_criterio_subnivel_articulo").val());
        if (maneja_criterio && (id_concepto_criterio_subnivel_articulo == undefined || isNaN(id_concepto_criterio_subnivel_articulo) ||  id_concepto_criterio_subnivel_articulo == 0)){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_concepto").val();
            contador_mensajes++;
        }
        //var id_color = parseInt($("#id_color").val());
        //if (maneja_color && (id_color == undefined || isNaN(id_color) ||  id_color == 0)){
        var id_color = $("#id_color").val();
        if (maneja_color && (id_color == undefined || id_color == "")){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_color").val();
            contador_mensajes++;
        }
        var fecha_entrega_articulo = $("#fecha_entrega_articulo").val();
        if (fecha_entrega_articulo == undefined || fecha_entrega_articulo == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_fecha_entrega_articulo").val();
            contador_mensajes++;
        }

        if (cantidad_detalle > cantidad_total_control){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_cantidad_detalle_mayor").val();
            contador_mensajes++;
        }

        var regimen = $("#regimen_actual").val();
        if (regimen == "2"){
            $(".iva_incluido").removeAttr('checked');
            $(".iva_incluido").attr("disabled","disabled");
            $(".iva_incluido").parent().hide();
        } else {
            $(".iva_incluido").removeAttr('checked');
            $(".iva_incluido").removeAttr("disabled");
            $(".iva_incluido").parent().show();
        }

        if (continuar){
            if (cantidad_total_articulo == cantidad_total_control){
                $("#id_unidad_actual").val($("#id_unidad").val());
                $("#aplica_descuento_linea_actual").val($(descuento_linea));
                $("#id_categoria_actual").val(id_categoria);
                $("#id_grupo1_actual").val(id_grupo1);
                $("#id_grupo2_actual").val(id_grupo2);
                $("#id_grupo3_actual").val(id_grupo3);
                $("#id_grupo4_actual").val(id_grupo4);
                $("#id_grupo5_actual").val(id_grupo5);
                $("#id_grupo6_actual").val(id_grupo6);
                $("#referencia_actual").val(referencia);
                $("#detalle_actual").val(detalle);
                $("#id_tasa_actual").val(id_tasa);
                $("#maneja_color_actual").val(maneja_color);
                $("#id_criterio_subnivel_articulo_actual").val(id_criterio_subnivel_articulo);
                $("#maneja_caracteristica_actual").val(maneja_caracteristicas);
                $("#valor_unitario_actual").val(valor_unitario);
                $("#descuento_global1_actual").val(descuento_global1);
                $("#descuento_global2_actual").val(descuento_global2);
                $("#descuento_global3_actual").val(descuento_global3);
                $("#descuento_linea_actual").val(porcentaje_descuento_linea);
            }
            $(".agregar_articulo").parent().hide();
            $(".agregar_articulo").attr("disabled","disabled");
            $('#indicadorEsperaFormulario').width(128);
            $('#indicadorEsperaFormulario').height(15);
            $('#indicadorEsperaFormulario').css("display","block");
            $('#formularioPrincipal').ajaxForm();
            $('#formularioPrincipal').ajaxSubmit({
                dataType: 'json',
                data: {insertar_movimiento: true},
                success: function(datos) {
                    if (datos[0]){
                        var id_propuesta_pedido = $("#id_propuesta_pedido").val();
                        /*$(".aplica_descuento_global1").attr("disabled","disabled");
                        $("#descuento_global1").attr("readOnly","true");
                        $(".aplica_descuento_global2").attr("disabled","disabled");
                        $("#descuento_global2").attr("readOnly","true");
                        $(".aplica_descuento_global3").attr("disabled","disabled");
                        $("#descuento_global3").attr("readOnly","true");*/
                        if (id_propuesta_pedido == 0){
                            // Deshabilitar campos del encabezado
                            $("#nit_proveedor_mostrar").html($("#nit_proveedor").val());
                            $("#razon_social_proveedor_mostrar").html($("#razon_social_proveedor").val());
                            $("#regimen").attr("disabled","disabled");
                            $("#nit_proveedor_mostrar").parent().show();
                            $("#nit_proveedor_mostrar").removeAttr("disabled");
                            $("#razon_social_proveedor_mostrar").parent().show();
                            $("#razon_social_proveedor_mostrar").removeAttr("disabled");
                            $("#nit_proveedor").parent().hide();
                            $("#razon_social_proveedor").parent().hide();
                            $("#id_sucursal").attr("disabled","disabled");
                            $("#id_tipo_documento").attr("disabled","disabled");
                            $("#id_tipo_entrada_salida").attr("disabled","disabled");
                            $(".fecha_pedido").parent().hide();
                            $("#fecha_documento_mostrar").parent().show();
                            $("#fecha_documento_mostrar").html($("#fecha_documento").val());
                            $("#id_propuesta_pedido").val(datos[15]);
                            $(".terminar_pedido").parent().show();
                            $(".terminar_pedido").removeAttr("disabled");
                            $(".total_pedido").parent().show();
                            $(".total_pedido").removeAttr("disabled");
                            $("#numero_propuesta").attr("disabled","disabled");
                            $("#numero_propuesta").parent().show();
                            $("#numero_propuesta").html(datos[17]);
                        }
                        var cantidad_pendiente = cantidad_total_control - cantidad_detalle;
                        $("#cantidad_total_control").val(cantidad_pendiente);
                        $("#cantidad_pendiente").html(cantidad_pendiente);
                        if (cantidad_pendiente > 0){
                            $("#id_categoria").attr("disabled","disabled");
                            $("#id_grupo1").attr("disabled","disabled");
                            $("#id_grupo2").attr("disabled","disabled");
                            $("#id_grupo3").attr("disabled","disabled");
                            $("#id_grupo4").attr("disabled","disabled");
                            $("#id_grupo5").attr("disabled","disabled");
                            $("#id_grupo6").attr("disabled","disabled");
                            $("#referencia").attr("readOnly","true");
                            $("#detalle").attr("readOnly","true");
                            $("#id_tasa").attr("disabled","disabled");
                            $(".maneja_color").attr("disabled","disabled");
                            $(".maneja_criterio").attr("disabled","disabled");
                            $(".maneja_criterio_articulo").attr("disabled","disabled");
                            $("#id_subnivel_articulo").attr("disabled","disabled");
                            $("#id_criterio_subnivel_articulo").attr("disabled","disabled");
                            $(".maneja_caracteristicas").attr("disabled","disabled");
                            $("#id_caracteristica").parent().hide();
                            $("#id_caracteristica").attr("disabled","disabled");
                            $(".item_caracteristica").parent().hide();
                            $(".item_caracteristica").attr("disabled","disabled");
                            var id_lista_caracteristica = $("#listaCaracteristica thead tr th:first-child").attr("id");
                            if (id_lista_caracteristica == ""){
                                $("#listaCaracteristica thead tr th:first-child").hide();
                            }
                            $("#articulo").attr("readOnly","true");
                            $("#cantidad_total_articulo").attr("readOnly","true");
                            $("#valor_unitario").attr("readOnly","true");
                            $("#id_unidad").attr("disabled","disabled");
                            $(".descuento_linea").attr("disabled","disabled");
                            $("#descuento_linea").attr("readOnly","true");
                            $("#cantidad_detalle").val(cantidad_pendiente);
                            $("#foto_articulo").val('');
                        } else {
                            $("#articulo").removeAttr("readOnly");
                            $("#cantidad_total_articulo").removeAttr("readOnly");
                            $("#valor_unitario").removeAttr("readOnly");
                            $("#id_unidad_medida").removeAttr("readOnly");
                            $(".detalle_pedido").parent().hide();
                            $(".detalle_pedido").attr("disabled","disabled");
                            $("#cantidad_total_control").val(0);
                            $("#cantidad_pendiente").html(0);
                            $("#articulo").val('');
                            $("#id_articulo").val('');
                            $("#cantidad_total_articulo").val('');
                            $("#cantidad_total_control").val('');
                            $(".concepto").parent().hide();
                            $(".concepto").attr('disabled','disabled');
                            $(".concepto").html('');
                            $(".colores").parent().hide();
                            $(".colores").attr('disabled','disabled');
                            //$("#id_color").val(0);
                            $("#id_color").val("");
                            $(".detalle_pedido").parent().hide();
                            $(".detalle_pedido").attr('disabled','disabled');
                            $("#cantidad_detalle").val('');
                            $(".descuento_linea").removeAttr('checked');
                            $(".linea").parent().hide();
                            $(".linea").attr('disabled','disabled');
                            $("#descuento_linea").val('');
                            $("#observaciones_articulo").val('');
                            $("#valor_unitario").val('');
                            $("#cantidad_pendiente").html('');
                            $("#id_unidad").removeAttr('disabled');
                            $("#id_categoria").val(0);
                            $("#id_categoria").removeAttr('disabled');
                            $("#grupo1").val(0);
                            $(".grupo1").parent().hide();
                            $(".grupo1").attr('disabled','disabled');
                            $("#grupo2").val(0);
                            $(".grupo2").parent().hide();
                            $(".grupo2").attr('disabled','disabled');
                            $("#grupo3").val(0);
                            $(".grupo3").parent().hide();
                            $(".grupo3").attr('disabled','disabled');
                            $("#grupo4").val(0);
                            $(".grupo4").parent().hide();
                            $(".grupo4").attr('disabled','disabled');
                            $("#grupo5").val(0);
                            $(".grupo5").parent().hide();
                            $(".grupo5").attr('disabled','disabled');
                            $("#grupo6").val(0);
                            $(".grupo6").parent().hide();
                            $(".grupo6").attr('disabled','disabled');
                            $("#referencia").val('');
                            $("#detalle").val('');
                            $(".maneja_criterio").removeAttr('checked');
                            $(".maneja_criterio_articulo").removeAttr('checked');
                            $(".criterio").parent().hide();
                            $(".criterio").attr('disabled','disabled');
                            $(".criterio").html('');
                            $("#id_subnivel_articulo").parent().hide();
                            $("#id_subnivel_articulo").attr('disabled','disabled');
                            $("#id_subnivel_articulo").html('');
                            $("#id_subnivel_articulo").val('0');
                            $(".maneja_criterio_articulo").parent().hide();
                            $(".maneja_criterio_articulo").attr('disabled','disabled');
                            $(".maneja_criterio_articulo").html('');
                            $(".maneja_color").removeAttr('disabled');
                            $(".maneja_color").removeAttr('checked');
                            $(".id_color").val('');
                            $(".colores").parent().hide();
                            $(".colores").val('');
                            $(".maneja_caracteristicas").removeAttr('checked');
                            $("#listaCaracteristica tbody").parents('tr').remove();
                            $(".caracteristica").parent().hide();
                            $(".caracteristica").attr('disabled','disabled');
                            $("#foto_articulo").val('');
                            $(".articulo_existe").parent().show();
                            $(".articulo_existe").removeAttr("disabled");
                            $(".articulo_nuevo").parent().hide();
                            $(".articulo_nuevo").attr("disabled","disabled");
                            $(".crear_articulo").removeAttr("checked");
                            $(".descuento_linea").removeAttr("disabled");
                            $("#referencia").removeAttr("readOnly");
                            $("#detalle").removeAttr("readOnly");
                            $("#descuento_linea").removeAttr("readOnly");
                            $("#listaCaracteristica thead tr th:first-child").show();
                            $("#id_unidad").val($("#id_unidad_medida_pedidos").val());
                        }
                        var valorClase = '';
                        if ($("#listaArticulos tbody tr:first-child").hasClass("even")) {
                            valorClase = 'odd';
                        } else {
                            valorClase = 'even';
                        }
                        var removerArticulo = $('#removerArticulo').html();
                        var modificar = $('#modificarArticulo').html();
                        if (datos[13] == ""){
                            var foto = '<td align="right"></td>';
                        } else {
                            var foto = '<td align="center"><a target="_new" href="'+datos[13]+'">[Descargar]</a></td>';
                        }
                        var item = '<tr id="'+datos[16]+'" class="'+valorClase+'">'+
                                        '<td align="center">'+
                                            '<input type="hidden" name="id_movimiento_tabla['+datos[16]+']" value="'+datos[16]+'">'+
                                            modificar+
                                        '</td>'+
                                        '<td align="center">'+
                                            removerArticulo+
                                        '</td>'+
                                        '<td align="left">'+datos[0]+'</td>'+
                                        '<td align="left">'+datos[1]+'</td>'+
                                        '<td align="left">'+datos[2]+'</td>'+
                                        '<td align="left">'+datos[3]+'</td>'+
                                        '<td align="left">'+datos[4]+'</td>'+
                                        '<td align="left">'+datos[5]+'</td>'+
                                        '<td align="right">'+datos[6]+'</td>'+
                                        '<td align="left">'+datos[7]+'</td>'+
                                        '<td align="right">'+datos[8]+'</td>'+
                                        '<td align="right">'+datos[9]+'</td>'+
                                        '<td align="right">'+datos[10]+'</td>'+
                                        '<td align="right">'+datos[11]+'</td>'+
                                        '<td align="center">'+datos[12]+'</td>'+
                                        foto+
                                        '<td align="left">'+observaciones_articulo+'</td>'+
                                    '</tr>';
                        $('#listaArticulos').prepend(item);
                        if (cantidad_pendiente > 0){
                            $('.removerArticuloTabla').css("display","none");
                            $('.modificarArticuloTabla').css("display","none");
                        } else {
                            $('.removerArticuloTabla').removeAttr("style");
                            $('.modificarArticuloTabla').removeAttr("style");
                        }
                        $("#observaciones_articulo").val("");
                    } else {
                        alert(datos[1]);
                    }
                    $('#indicadorEsperaFormulario').css("display","none");
                    $(".agregar_articulo").parent().show();
                    $(".agregar_articulo").removeAttr("disabled");
                    $("#id_propuesta_pedido").val(datos[15]);
                    if (regimen == "2"){
                        $(".iva_incluido").attr("disabled","disabled");
                        $(".iva_incluido").removeAttr('checked');
                        $(".iva_incluido").parent().hide();
                    } else {
                        $(".iva_incluido").removeAttr("disabled");
                        $(".iva_incluido").removeAttr('checked');
                        $(".iva_incluido").parent().show();
                    }
                }
            });
        } else {
            var mensaje_pantalla = "";
            for(var i=1;i<contador_mensajes;i++){
                mensaje_pantalla = mensaje_pantalla +" "+ mensajes[i]+"\n";
            }
            alert(mensaje_pantalla);
        }

        return true;
    }

    function cargarCantidad(){
        var cantidad_total_articulo = $("#cantidad_total_articulo").val();
        $("#cantidad_total_control").val(cantidad_total_articulo);
        $("#cantidad_detalle").val(cantidad_total_articulo);
        $("#cantidad_pendiente").html(cantidad_total_articulo);
    }

    function totalPedido(){
        var destino = $('#URLFormulario').val();
        var id_propuesta_pedido = $("#id_propuesta_pedido").val();
        var iva_incluido = "0";
        if ($(".iva_incluido").is(':checked')){
            iva_incluido = "1";
        }
        global1 = parseFloat($("#descuento_global1").val());
        if (global1 == undefined || isNaN(global1) || global1==0){
            global1 = "0.0000";
        }
        global2 = parseFloat($("#descuento_global2").val());
        if (global2 == undefined || isNaN(global2) || global2==0){
            global2 = "0.0000";
        }
        global3 = parseFloat($("#descuento_global3").val());
        if (global3 == undefined || isNaN(global3) || global3==0){
            global3 = "0.0000";
        }
        $.getJSON(destino, {total_pedido: true, id_propuesta_pedido: id_propuesta_pedido, iva_incluido: iva_incluido, global1: global1, global2: global2, global3: global3}, function (datos){
            $("#total_unidades").val(datos[0]);
            $("#subtotal_pedido").val(datos[1]);
            if (datos[2] != "0.00"){
                $("#total_descuentos_linea_pedido").val(datos[2]);
                $("#total_descuentos_linea_pedido").parent().show();
                $("#total_descuentos_linea_pedido").removeAttr("disabled");
            } else {
                $("#total_descuentos_linea_pedido").val("");
                $("#total_descuentos_linea_pedido").parent().hide();
            }
            if (datos[3] != "0.00"){
                $("#total_descuentos_globales_pedido").val(datos[3]);
                $("#total_descuentos_globales_pedido").parent().show();
                $("#total_descuentos_globales_pedido").removeAttr("disabled");
            } else {
                $("#total_descuentos_globales_pedido").val("");
                $("#total_descuentos_globales_pedido").parent().hide();
            }
            if (datos[4] != "0.00"){
                $("#total_iva_pedido").val(datos[4]);
                $("#total_iva_pedido").parent().show();
            } else {
                $("#total_iva_pedido").val("");
                $("#total_iva_pedido").parent().hide();
            }
            $("#total_pedido").val(datos[5]);
        });
    }

    function removerArticulo(boton){
        var destino = $("#URLFormulario").val();
        var id_tabla = $(boton).parents('tr').attr('id').split('_')[0];
        if (id_tabla == 'fila'){
            var id_tabla = $(boton).parents('tr').attr('id').split('_')[1];
        }
        if (!isNaN(id_tabla))
            id_tabla = parseInt(id_tabla);

        $.getJSON(destino, {elimina_movimiento: true, id_tabla: id_tabla}, function(datos){
            if (!datos[0]){
                alert(datos[1]);
            } else {
                $(boton).parents('tr').remove();
            }
        });
    }

    function activaIva(){
        var regimen = $("#regimen").val();
        if (regimen == "2"){
            $(".iva_incluido").attr("disabled","disabled");
            $(".iva_incluido").removeAttr('checked');
            $(".iva_incluido").parent().hide();
            $("#regimen_actual").val("2");
            $("#participacion").val(0);
            $("#participacion").parent().hide();
            $("#participacion").attr("disabled","disabled");
        } else {
            $(".iva_incluido").removeAttr('disabled');
            $(".iva_incluido").removeAttr('checked');
            $(".iva_incluido").parent().show();
            $("#regimen_actual").val("1");
            $("#participacion").val("");
            $("#participacion").parent().show();
            $("#participacion").removeAttr("disabled");
        }
    }

    function modificarArticulo(boton){
        var destino = $("#URLFormulario").val();
        var id_tabla = $(boton).parents('tr').attr('id').split('_')[0];
        if (id_tabla == 'fila'){
            var id_tabla = $(boton).parents('tr').attr('id').split('_')[1];
        }
        if (!isNaN(id_tabla))
            id_tabla = parseInt(id_tabla);

        $.getJSON(destino, {actualiza_movimiento: true, id_tabla: id_tabla}, function(datos){
            if (!datos[0]){
                alert(datos[1]);
            } else {
                $(".modificarArticuloTabla").css("display","none");
                $(".removerArticuloTabla").css("display","none");
                $(".movimiento").parent().hide();
                $(".modificar").parent().show();
                $(".actualizarArticulo").parent().show();
                $("#id_articulo_modificar").val(parseInt(datos[13]));
                $("#id_pedido_detalle_modificar").val(parseInt(datos[18]));
                $("#id_unidad_modificar").val(datos[15]);
                $("#fecha_entrega_articulo").val(datos[20]);
                $("#fecha_entrega_modificar").val(datos[20]);
                if (parseInt(datos[13]) <= 0){
                    $(".modificar_detalle").parent().show();
                    $(".modificar_detalle").removeAttr("disabled","disabled");
                    $(".modificar_articulo").parent().hide();
                    $("#nivel").val(datos[1]);
                    $("#id_categoria").val(datos[2]);
                    $("#id_grupo1").val(parseInt(datos[3]));
                    $("#id_grupo2").val(parseInt(datos[4]));
                    $("#id_grupo3").val(parseInt(datos[5]));
                    $("#id_grupo4").val(parseInt(datos[6]));
                    $("#id_grupo5").val(parseInt(datos[7]));
                    $("#id_grupo6").val(parseInt(datos[8]));
                    modificarEstructura(datos[2],datos[3],datos[4],datos[5],datos[6],datos[7],datos[8]);
                    $("#id_tasas").val(parseInt(datos[21]));
                    $("#detalle").val(datos[10]);
                    if (datos[11]){
                        $(".maneja_caracteristicas").parent().show();
                        $(".maneja_caracteristicas").removeAttr("disabled");
                        $(".maneja_caracteristicas").attr('checked','checked');
                        $(".caracteristica").parent().show();
                        $(".caracteristica").removeAttr("disabled");
                        var estructura = datos[2] + "|" + datos[3] + "|" + datos[4] + "|" + datos[5] + "|" + datos[6] + "|" +datos[7] + "|" + datos[8];
                        $.getJSON(destino, {cargarDatosDesdeEstructura: true, dato: 2, estructura: estructura}, function(datos_caracteristicas){
                            if (datos_caracteristicas){
                                var lista_caracteristicas = "";
                                jQuery.each(datos_caracteristicas, function(valor_caracteristica, texto_caracteristica) {
                                    lista_caracteristicas = lista_caracteristicas+'<option class="caracteristica'+valor_caracteristica+' id_caracteristica" value="'+valor_caracteristica+'">'+texto_caracteristica+'</option>';
                                });
                                $('#id_caracteristica').html(lista_caracteristicas);
                                var caracteristica = datos[11].split('|');
                                var remover = $('#removerCaracteristica').html();
                                $('#listaCaracteristica tbody tr').remove();
                                jQuery.each(caracteristica, function(indice, valor) {
                                    var valorClase = '';
                                    if ($("#listaCaracteristica tr:last").hasClass("even")) {
                                        valorClase = 'odd';
                                    } else {
                                        valorClase = 'even';
                                    }
                                    valor = valor.split('==');
                                    id_caracteristica = valor[0];
                                    caracteristica = valor[1];
                                    var item = '<tr id="id_caracteristica'+id_caracteristica+'" class="'+valorClase+'">'+
                                                    '<td align="left">'+
                                                        '<input class="id_caracteristica_tabla" type="hidden" name="id_caracteristica_tabla['+id_caracteristica+']" value="'+id_caracteristica+'">'+remover+
                                                    '</td>'+
                                                    '<td align="left">'+caracteristica+'</td>'+
                                                '</tr>';
                                    $('#listaCaracteristica').append(item);
                                });
                            }
                        });
                    } else {
                        $(".maneja_caracteristicas").parent().hide();
                        $(".maneja_caracteristicas").removeAttr('checked');
                        $(".caracteristica").parent().hide();
                    }
                } else {
                    $(".modificar_detalle").parent().hide();
                    $(".modificar_articulo").parent().show();
                    $(".modificar_articulo").removeAttr("disabled","disabled");
                    $("#articulo").attr("disabled","disabled");
                    $("#articulo").val(datos[12]);
                    $("#id_articulo").val(datos[13]);
                }
                $("#referencia").removeAttr("disabled");
                $("#referencia").val(datos[14]);
                $("#id_unidad").val(datos[15]);
                $("#valor_unitario").val(datos[16]);
                $(".descuento_linea").parent().show();
                if (datos[17] > 0){
                    $(".descuento_linea:checkbox").attr("checked","checked");
                    $(".linea").parent().show();
                    $(".linea").removeAttr("disabled");
                    $("#descuento_linea").val(datos[17]);
                } else {
                    $(".descuento_linea:checkbox").removeAttr("checked");
                    $(".linea").parent().hide();
                    $(".linea").attr("disabled","disabled");
                    $("#descuento_linea").val(datos[17]);
                }
                $("#id_estructura_grupo").val(datos[19]);
            }
        });
    }

    function modificarEstructura(id_categoria,id_grupo1,id_grupo2,id_grupo3,id_grupo4,id_grupo5,id_grupo6){
        var destino = $("#URLFormulario").val();

        if (id_categoria > 0){
            $('#id_categoria').parent().show();
            $('#id_categoria').removeAttr("disabled");
            $('#id_categoria').val(id_categoria);
        }
        if (id_grupo1 > 0){
            var lista1  = '';
            var datos1  = "";
            $.getJSON(
                destino,
                {
                    cargar_estructura_grupos: true,
                    nivel: 1,
                    id_categoria: id_categoria,
                    id_grupo1: id_grupo1,
                    id_grupo2:"0",
                    id_grupo3:"0",
                    id_grupo4:"0",
                    id_grupo5:"0",
                    id_grupo6:"0"
                },
                function(datos1) {
                    var cantidad1 = 0;
                    jQuery.each(datos1, function(valor1, texto1) {
                            lista1 = lista1+'<option value="'+valor1+'">'+texto1+'</option>';
                            cantidad1++;
                        }
                    );
                    if (cantidad1 > 1){
                        $('#id_grupo1').parent().show();
                        $('#id_grupo1').removeAttr("disabled");
                        $('#id_grupo1').html(lista1);
                        $('#id_grupo1').val(parseInt(id_grupo1));
                    }
                }
            );
        }
        if (id_grupo2 > 0){
            var lista2 = '';
            var datos2 = "";
            $.getJSON(
                destino,
                {
                    cargar_estructura_grupos: true,
                    nivel: 2,
                    id_categoria: id_categoria,
                    id_grupo1: id_grupo1,
                    id_grupo2: id_grupo2,
                    id_grupo3:"0",
                    id_grupo4:"0",
                    id_grupo5:"0",
                    id_grupo6:"0"
                },
                function(datos2) {
                    var cantidad2 = 0;
                    jQuery.each(datos2, function(valor2, texto2) {
                            lista2 = lista2+'<option value="'+valor2+'">'+texto2+'</option>';
                            cantidad2++;
                        }
                    );
                    if (cantidad2 > 1){
                        $('#id_grupo2').parent().show();
                        $('#id_grupo2').removeAttr("disabled");
                        $('#id_grupo2').html(lista2);
                        $('#id_grupo2').val(parseInt(id_grupo2));
                    }
                }
            );
        }
        if (id_grupo3 > 0){
            var lista3 = '';
            var datos3 = "";
            $.getJSON(
                destino,
                {
                    cargar_estructura_grupos: true,
                    nivel: 3,
                    id_categoria: id_categoria,
                    id_grupo1: id_grupo1,
                    id_grupo2: id_grupo2,
                    id_grupo3: id_grupo3,
                    id_grupo4:"0",
                    id_grupo5:"0",
                    id_grupo6:"0"
                },
                function(datos3) {
                    var cantidad3 = 0;
                    jQuery.each(datos3, function(valor3, texto3) {
                            lista3 = lista3+'<option value="'+valor3+'">'+texto3+'</option>';
                            cantidad3++;
                        }
                    );
                    if (cantidad3 > 1){
                        $('#id_grupo3').parent().show();
                        $('#id_grupo3').removeAttr("disabled");
                        $('#id_grupo3').html(lista3);
                        $('#id_grupo3').val(parseInt(id_grupo3));
                    }
                }
            );
        }
        if (id_grupo4 > 0){
            var lista4 = '';
            var datos4 = "";
            $.getJSON(
                destino,
                {
                    cargar_estructura_grupos: true,
                    nivel: 4,
                    id_categoria: id_categoria,
                    id_grupo1: id_grupo1,
                    id_grupo2: id_grupo2,
                    id_grupo3: id_grupo3,
                    id_grupo4: id_grupo4,
                    id_grupo5:"0",
                    id_grupo6:"0"
                },
                function(datos4) {
                    var cantidad4 = 0;
                    jQuery.each(datos4, function(valor4, texto4) {
                            lista4 = lista4+'<option value="'+valor4+'">'+texto4+'</option>';
                            cantidad4++;
                        }
                    );
                    if (cantidad4 > 1){
                        $('#id_grupo4').parent().show();
                        $('#id_grupo4').removeAttr("disabled");
                        $('#id_grupo4').html(lista4);
                        $('#id_grupo4').val(parseInt(id_grupo4));
                    }
                }
            );
        }
        if (id_grupo5 > 0){
            var lista5 = '';
            var datos5 = "";
            $.getJSON(
                destino,
                {
                    cargar_estructura_grupos: true,
                    nivel: 5,
                    id_categoria: id_categoria,
                    id_grupo1: id_grupo1,
                    id_grupo2: id_grupo2,
                    id_grupo3: id_grupo3,
                    id_grupo4: id_grupo4,
                    id_grupo5: id_grupo5,
                    id_grupo6:"0"
                },
                function(datos5) {
                    var cantidad5 = 0;
                    jQuery.each(datos5, function(valor5, texto5) {
                            lista5 = lista5+'<option value="'+valor5+'">'+texto5+'</option>';
                            cantidad5++;
                        }
                    );
                    if (cantidad5 > 1){
                        $('#id_grupo5').parent().show();
                        $('#id_grupo5').removeAttr("disabled");
                        $('#id_grupo5').html(lista5);
                        $('#id_grupo5').val(parseInt(id_grupo5));
                    }
                }
            );
        }
        if (id_grupo6 > 0){
            var lista6 = '';
            var datos6 = "";
            $.getJSON(
                destino,
                {
                    cargar_estructura_grupos: true,
                    nivel: 6,
                    id_categoria: id_categoria,
                    id_grupo1: id_grupo1,
                    id_grupo2: id_grupo2,
                    id_grupo3: id_grupo3,
                    id_grupo4: id_grupo4,
                    id_grupo5: id_grupo5,
                    id_grupo6: id_grupo6
                },
                function(datos6) {
                    var cantidad6 = 0;
                    jQuery.each(datos6, function(valor6, texto6) {
                            lista6 = lista6+'<option value="'+valor6+'">'+texto6+'</option>';
                            cantidad6++;
                        }
                    );
                    if (cantidad > 1){
                        $('#id_grupo6').parent().show();
                        $('#id_grupo6').removeAttr("disabled");
                        $('#id_grupo6').html(lista6);
                        $('#id_grupo6').val(parseInt(id_grupo6));
                    }
                }
            );
        }
    }

    function actualizarArticulo(){
        var destino = $('#URLFormulario').val();
        var continuar = true;
        var mensajes = new Array();
        var contador_mensajes = 1;
        var regimen = $("#regimen_actual").val();
        var id_articulo = $("#id_articulo_modificar").val();
        var id_pedido_detalle = $("#id_pedido_detalle_modificar").val();
        var referencia = $("#referencia").val();
        if (referencia == undefined || referencia == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_referencia").val();
            contador_mensajes++;
        }
        var id_unidad_medida = $("#id_unidad_medida").val();
        var valor_unitario = $("#valor_unitario").val();
        if (valor_unitario == undefined || isNaN(valor_unitario) || valor_unitario == 0){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_valor_unitario").val();
            contador_mensajes++;
        }
        var fecha_entrega_articulo = $("#fecha_entrega_articulo").val();
        if (fecha_entrega_articulo == undefined || fecha_entrega_articulo == ""){
            continuar = false;
            mensajes[contador_mensajes] = $("#error_fecha_entrega_articulo").val();
            contador_mensajes++;
        }

        if (id_pedido_detalle > 0){

            var id_estructura_grupo = $("#id_estructura_grupo").val();
            if (id_estructura_grupo == undefined || isNaN(id_estructura_grupo) || id_estructura_grupo == 0){
                continuar = false;
                mensajes[contador_mensajes] = $("#error_estructura_grupo").val();
                contador_mensajes++;
            } else {
                var id_categoria = $("#id_categoria").val();
                if (id_categoria==undefined || isNaN(id_categoria)){
                    id_categoria = "0";
                }
                var id_grupo1 = $("#id_grupo1").val();
                if (id_grupo1==undefined || isNaN(id_grupo1)){
                    id_grupo1 = "0";
                }
                var id_grupo2 = $("#id_grupo2").val();
                if (id_grupo2==undefined || isNaN(id_grupo2)){
                    id_grupo2 = "0";
                }
                var id_grupo3 = $("#id_grupo3").val();
                if (id_grupo3==undefined || isNaN(id_grupo3)){
                    id_grupo3 = "0";
                }
                var id_grupo4 = $("#id_grupo4").val();
                if (id_grupo4==undefined || isNaN(id_grupo4)){
                    id_grupo4 = "0";
                }
                var id_grupo5 = $("#id_grupo5").val();
                if (id_grupo5==undefined || isNaN(id_grupo5)){
                    id_grupo5 = "0";
                }
                var id_grupo6 = $("#id_grupo6").val();
                if (id_grupo6==undefined || isNaN(id_grupo6)){
                    id_grupo6 = "0";
                }
                var nivel = $("#nivel").val();

                var continuar_estructura = true;
                if (nivel == 2 && id_grupo1 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 3 && id_grupo2 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 4 && id_grupo3 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 5 && id_grupo4 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 6 && id_grupo5 == 0){
                    continuar_estructura = false;
                }
                if (nivel == 7 && id_grupo6 == 0){
                    continuar_estructura = false;
                }
                if (!continuar_estructura){
                    continuar = false;
                    mensajes[contador_mensajes] = $("#error_estructura_grupo").val();
                    contador_mensajes++;
                }
            }
        }
        if (continuar){
            $('#indicadorEsperaFormulario').width(128);
            $('#indicadorEsperaFormulario').height(15);
            $('#indicadorEsperaFormulario').css("display","block");
            $('#formularioPrincipal').ajaxForm();
            $('#formularioPrincipal').ajaxSubmit({
                dataType: 'json',
                data: {modificar_movimiento: true},
                success: function(datos) {
                    if (datos[0] != ""){
                        var valorClase = '';
                        if ($("#listaArticulos tbody tr:first-child").hasClass("even")) {
                            valorClase = 'odd';
                        } else {
                            valorClase = 'even';
                        }
                        var removerArticulo = $('#removerArticulo').html();
                        var modificar = $('#modificarArticulo').html();
                        $("#listaArticulos tbody").children('tr').remove();
                        jQuery.each(datos, function(indice, valor) {
                            var datos_movimiento = datos[indice].split('|');
                            if (datos_movimiento[13] == ""){
                                var foto = '<td align="right"></td>';
                            } else {
                                var foto = '<td align="center"><a target="_new" href="'+datos_movimiento[13]+'">[Descargar]</a></td>';
                            }
                            var item = '<tr id="'+datos_movimiento[16]+'" class="'+valorClase+'">'+
                                            '<td align="center">'+
                                                '<input type="hidden" name="id_movimiento_tabla['+datos_movimiento[16]+']" value="'+datos_movimiento[16]+'">'+
                                                modificar+
                                            '</td>'+
                                            '<td align="center">'+
                                                removerArticulo+
                                            '</td>'+
                                            '<td align="left">'+datos_movimiento[0]+'</td>'+
                                            '<td align="left">'+datos_movimiento[1]+'</td>'+
                                            '<td align="left">'+datos_movimiento[2]+'</td>'+
                                            '<td align="left">'+datos_movimiento[3]+'</td>'+
                                            '<td align="left">'+datos_movimiento[4]+'</td>'+
                                            '<td align="left">'+datos_movimiento[5]+'</td>'+
                                            '<td align="right">'+datos_movimiento[6]+'</td>'+
                                            '<td align="left">'+datos_movimiento[7]+'</td>'+
                                            '<td align="left">'+datos_movimiento[8]+'</td>'+
                                            '<td align="right">'+datos_movimiento[9]+'</td>'+
                                            '<td align="right">'+datos_movimiento[10]+'</td>'+
                                            '<td align="right">'+datos_movimiento[11]+'</td>'+
                                            '<td align="center">'+datos_movimiento[12]+'</td>'+
                                            foto+
                                            '<td align="left">'+datos_movimiento[14]+'</td>'+
                                        '</tr>';
                            $('#listaArticulos').prepend(item);
                        });
                        $('.removerArticuloTabla').removeAttr("style");
                        $('.modificarArticuloTabla').removeAttr("style");
                        $("#observaciones_articulo").val("");
                        $(".crear_articulo").parent().show();
                        $("#contenedor_articulo_nuevo").parent().show();
                        $('#indicadorEsperaFormulario').css("display","none");
                        $(".articulo_nuevo").parent().hide();
                        $(".articulo_nuevo").removeAttr("disabled");
                        $(".articulo_existe").parent().show();
                        $(".articulo_existe").removeAttr("disabled");
                        $("#id_articulo").val("");
                        $(".maneja_criterio_articulo").parent().hide();
                        $(".subnivel").parent().hide();
                        $(".criterio").parent().hide();
                        $(".caracteristica").parent().hide();
                        $(".maneja_caracteristicas").parent().hide();
                        $("#cantidad_total_articulo").parent().show();
                        $("#valor_unitario").parent().show();
                        $(".descuento_linea").parent().show();
                        $(".descuento_linea").removeAttr('checked');
                        $(".linea").parent().hide();
                        $(".linea").val("");
                        $("#observaciones_articulo").parent().show();
                        $("#id_articulo_modificar").val("");
                        $("#id_pedido_detalle_modificar").val("");
                        $("#id_pedido_detalle_modificar").val("");
                        $(".propuesta_pedido").val(datos[15]);
                        $(".detalle_pedido").parent().hide();
                        $(".actualizarArticulo").parent().hide();
                        $("#articulo").val("");
                        $("#valor_unitario").val("");
                        $("#foto_articulo").val("");
                        if (regimen == "2"){
                            $(".iva_incluido").removeAttr('checked');
                            $(".iva_incluido").attr("disabled","disabled");
                            $(".iva_incluido").parent().hide();
                        } else {
                            $(".iva_incluido").removeAttr('checked');
                            $(".iva_incluido").removeAttr("disabled");
                            $(".iva_incluido").parent().show();
                        }
                    } else {
                        alert(datos[1]);
                    }
                }
            });
        } else {
            var mensaje_pantalla = "";
            for(var i=1;i<contador_mensajes;i++){
                mensaje_pantalla = mensaje_pantalla +" "+ mensajes[i]+"\n";
            }
            alert(mensaje_pantalla);
        }
    }
