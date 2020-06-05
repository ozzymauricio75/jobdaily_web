    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    ///////////////Nuevos Datos///////////////////
    function acLocalEmpleados(item) {
        var destino  = $('#URLFormulario').val();
        var sucursal = $('#codigo_sucursal').val();
        $(item).autocomplete(destino, {
            minChars: 3,
            width: 250,
            max: 100,
            delay: 100,
            matchContains: true,
            cacheLength:100,
            extraParams: {
                verificar: true,
                codigo_sucursal: sucursal
            },
            formatResult: function(datos, valor) {
                return valor.split("|")[0];
            }
        }).blur(function() {
            var x = $(this).attr('id');
            $(x).autocomplete().remove();
        }).result(function(evento, datos, formateado) {
            //console.log(datos[1]);
            if (datos) {
                $('#documento_empleado').val(datos[1]);
                CampoformaPago();
                //recargarListaTransacciones('documento_aspirante','codigo_transaccion_tiempo');
                //verificarAnexosEnTransacciones();
            } else {
                $('#documento_empleado').val('');
            }
        });
    }


    function CampoformaPago()
    {
        var destino            = $('#URLFormulario').val();
        var documento_empleado = $("#documento_empleado").val();
        var lista   = '';

        if(documento_empleado!=""){
            $.getJSON(destino, {obtenerDatosContrato: true, documento_empleado:documento_empleado }, function(datos) {

                $("#codigo_planilla").val(datos["codigo_planilla"]);
                delete datos["codigo_planilla"];
                jQuery.each(datos, function(valor, texto) {
                    lista = lista+'<option value="'+valor+'">'+texto+'</option>';
                });

                $("#forma_pago_prestamo").html(lista);

            });
        }
    }

    function determinarNumeroCuotas()
    {
        var valorPrestamo    = parseInt($("#valor_prestamo").val());
        var valorCuota       = parseInt($("#valor_descuento").val());
        var valorCuotaMinima = parseInt($("#cuota_minima").val());
        var mensajeError     = $("#mensaje_valor_cuota").val()+" "+valorCuotaMinima;
        $("#valor_descuento").parent().children('#errorDialogo').remove();

        if(valorPrestamo < valorCuota){
            $("#valor_descuento").val(valorPrestamo);
            $("#generar").parent().show();
        }else if(valorCuotaMinima > valorCuota){
            $("#valor_descuento").parent().children('#errorDialogo').remove();
            $("#valor_descuento").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensajeError+'</span>');
            $("#valor_descuento").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
            $("#generar").parent().hide();
        }else{
            $("#generar").parent().show();
        }
    }

    function determinarValorPrestamo()
    {
        var valorPrestamo    = parseInt($("#valor_prestamo").val());
        var valorCuotaMinima = parseInt($("#cuota_minima").val());
        var mensajeError     = $("#mensaje_valor_prestamo").val()+" "+valorCuotaMinima;
        $("#valor_descuento").parent().children('#errorDialogo').remove();

        if(valorCuotaMinima > valorPrestamo){
            $("#valor_descuento").parent().children('#errorDialogo').remove();
            $("#valor_descuento").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+mensajeError+'</span>');
            $("#valor_descuento").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
            $("#generar").parent().hide();
        }else{
            $("#generar").parent().show();
        }
    }


    function generarTablaPagos()
    {
        var destino        = $('#URLFormulario').val();
        var fechaInicio    = $("#fecha_prestamo").val();
        var codigoPlanilla = $("#codigo_planilla").val();

        ///Calculo de numero de fechas a adicionar para completar el pago///
        var valorPrestamo = parseInt($("#valor_prestamo").val());
        var valorCuota    = parseInt($("#valor_descuento").val());
        var formaPago     = $("#forma_pago_prestamo").val();
        if (valorPrestamo >= valorCuota){
            removerTabla();
            recargarTabla(destino,fechaInicio,valorPrestamo,valorCuota,codigoPlanilla,formaPago,0);
        }
    }

    function removerTabla(){
        $("#listaItemsPagos tbody").remove();
    }

    function cargarNuevosDatos(){

        $("#boton_actualizar").val('0');
        $("#actualizar_cuota").val('0');

        var index_id       = $("#identificador_check").val();

        if (typeof(index_id)!='undefined'){

            var id             = "fila_"+index_id;
            var destino        = $('#URLFormulario').val();
            var codigoPlanilla = $("#codigo_planilla").val();
            var formaPago      = $("#forma_pago_prestamo").val();
            var valorCuota     = parseInt($("#valor_descuento").val());
            var tabla          = document.getElementById("listaItemsPagos");
            var numeroFilas    = tabla.rows.length;
            var columnas       = document.getElementById(id).getElementsByTagName("td");
            var i              = $("#"+id)[0].rowIndex;
            console.log(columnas);
            console.log(i);
            if (columnas){
                var c=0;
                while(c<(numeroFilas-i)){
                    tabla.deleteRow(i);
                    c++;
                }
            }
            ///Calculo de numero de fechas a adicionar para completar el pago///
            var fechaInicio    = columnas[1].innerHTML;
            var valorPrestamo =  parseInt(columnas[2].innerHTML)+valorCuota;
            recargarTabla(destino,fechaInicio,valorPrestamo,valorCuota,codigoPlanilla,formaPago,index_id);
        }
    }


    function recargarTabla(destino,fechaInicio,valorPrestamo,valorCuota,codigoPlanilla,formaPago,index_id){

        $("#boton_actualizar").val('0');
        $("#actualizar_cuota").val('0');
        var proceso = $("#proceso").val();
        var identificador_check = $("#genero_movimiento").val();

        $.getJSON(destino, {
            generarDatosTabla: true,
            fechaInicio:fechaInicio,
            valorPrestamo:valorPrestamo,
            valorCuota:valorCuota,
            codigoPlanilla:codigoPlanilla,
            formaPago:formaPago
        }, function(datos) {

            for(i=0;i<datos.length;i++)
            {
                var id          = new Date();
                datos_llegada   = datos[i].split(",");
                fecha_decuento  = datos_llegada[0];
                valor_saldo     = datos_llegada[1];
                valor_descuentos = datos_llegada[2];

                if ($("#listaItemsPagos tr:last").hasClass("even")) {
                    valorClase = 'odd';
                } else {
                    valorClase = 'even';
                }

                if(proceso=='M' && (index_id)==parseInt(identificador_check)){
                    var check = '<input id="DesautorizaDescuento" type="checkbox" onclick="cambiarEstadocheck(this)" name="desautorizaDescuento" value="ON" />';
                    $("#fecha_marcada").val(fecha_decuento);
                }else{
                    check="";
                }

                if(proceso=='M' || proceso=='A'){
                    identificador_texto = "texto_"+index_id;
                    texto = '<input type="text" id="'+identificador_texto+'" class="campo" onKeyUp="mostrarBotonActualizar(this);" onKeyPress="return campoEntero(event);" value="'+valor_descuentos+'" maxlength="30" size="10" name="texto_valor_cuota"  >';
                }else{
                    texto = valor_descuentos;
                }

                identificador = "fila_"+index_id;
                id_descuento  = "filaSaldo_"+index_id;
                id_saldo      = "filaDescuento_"+index_id;
                var item  = '<tr id="'+identificador+'" class="'+valorClase+'">'+
                                '<td align="center">'+
                                    '<input type="hidden" class="fechas_pago" name="fechas_pago[]" value="'+fecha_decuento+'">'+
                                    '<input type="hidden"  class="valor_saldo" name="valor_saldo[]" value="'+valor_saldo+'">'+
                                    '<input type="hidden"class="valor_descuentos" name="valor_descuentos[]" value="'+valor_descuentos+'">'+
                                    '<input type="hidden" class="descuenta" name="descuenta[]" value="1">'+check+
                                '</td>'+
                                '<td align="center">'+fecha_decuento+'</td>'+
                                '<td align="left">'+valor_saldo+'</td>'+
                                '<td align="left">'+texto+'</td>'+
                            '</tr>';
                $('#listaItemsPagos').append(item);
                index_id++;
            }
        });
    }

    function cambiarEstadocheck(campo){

        if($(campo).is(':checked')){
            $("#permite_descuento").val("0");
        }else{
            $("#permite_descuento").val("1");
        }
    }

    function cargarNuevosModificaCuota(id_campo){

        var check_marcado       = $("#identificador_check").val();
        //var id                = $(campo).parents('tr').attr('id');
        var id                  = id_campo;
        id_campo                = "#"+id_campo;
        index_id                = id.split("_");
        index_id                = parseInt(index_id[1]);
        var proceso             = $("#proceso").val();
        var destino             = $('#URLFormulario').val();
        var codigoPlanilla      = $("#codigo_planilla").val();
        var formaPago           = $("#forma_pago_prestamo").val();
        var identificador_texto = "#texto_"+index_id;
        var valorCuota          = parseInt($(identificador_texto).val());
        var valorCuotaEstable   = parseInt($("#valor_cuota_estable").val());
        var tabla               = document.getElementById("listaItemsPagos");
        var numeroFilas         = tabla.rows.length;
        var valorPrestamo       = 0;

        var id_siguiente       = "fila_"+(index_id+1);
        var columnas           = document.getElementById(id).getElementsByTagName("td");
        var columnas_siguiente = document.getElementById(id_siguiente).getElementsByTagName("td");

        var i = ($("#"+id)[0].rowIndex)+1;

        if(proceso=='A'){
            valorCuotaEstable   = parseInt($("#valor_descuento").val());
        }
        ///Calculo de numero de fechas a adicionar para completar el pago///
        var fechaInicio    = columnas_siguiente[1].innerHTML;

        if(index_id == parseInt(check_marcado))
        {
            if(parseInt($("#valor_saldo_llegada").val())>=valorCuota){
                valorPrestamo =  parseInt($("#valor_saldo_llegada").val())-valorCuota;
                continuar = true;
            }else{
                continuar = false;
            }
        }else{
            var id_anterior        = "fila_"+(index_id-1);
            columnas_anterior  = document.getElementById(id_anterior).getElementsByTagName("td");
            valorPrestamo =  parseInt(columnas_anterior[2].innerHTML)-valorCuota;

            if(parseInt(columnas_anterior[2].innerHTML)>=valorCuota){

                continuar = true;
            }else{
                continuar = false;
            }
        }

        if(continuar){

            var c=0;
            while(c<(numeroFilas-i)){
                tabla.deleteRow(i);
                c++;
            }

            columnas[2].innerHTML = valorPrestamo;
            //$(id_campo).children('input[class="valor_saldo"]').val(valorPrestamo);
            /*
            $(id_campo).parents('tr').children("td:first").children('input[class="valor_descuentos"]').val(valorCuota);
            $(id_campo).parents('tr').children("td:first").children('input[class="valor_saldo"]').val(valorPrestamo);
            */
            $(id_campo).children("td:first").children('input[class="valor_descuentos"]').val(valorCuota);
            $(id_campo).children("td:first").children('input[class="valor_saldo"]').val(valorPrestamo);

            recargarTabla(destino,fechaInicio,valorPrestamo,valorCuotaEstable,codigoPlanilla,formaPago,(index_id+1));
        }else{
            $(identificador_texto).val(valorCuotaEstable);
            alert($("#valor_cuota_mayor").val());
        }
    }

    function limpiarCampo()
    {
        $("#selector1").val("");
        $("#documento_empleado").val("");
        $("#selector2").val("");
        $("#codigo_transaccion_contable").val("");
        $("#valor_prestamo").val("");
        $("#valor_descuento").val("");
        $("#observaciones").val("");
    }

    function recargarDatosDocumento() {
        var destino     = $('#URLFormulario').val();
        var documento   = $('#tipo_documento option:selected').val();
        var sucursal    = $('#codigo_sucursal').val();
        var lista       = '';
        var genera      = false;
        var contador    = 0;
        $('#codigo_contable').val('');
        $('#selector2').val('');
        $('#selector2').removeAttr("disabled");
        $('#cuenta_bancaria').html('<option value="0"></option>');
        $('#cuenta_bancaria').attr("disabled","disabled").parent().hide();
        $('#consecutivo_cheque').val('');
        $('#consecutivo_cheque').attr("disabled","disabled").parent().hide();
/*
        $('#auxiliar_contable').attr("disabled","disabled").parent().hide();
        $('#auxiliar_contable').html(lista);
*/
        $.getJSON(destino, {recargarDatosDocumento: true, documento: documento, sucursal: sucursal}, function(datos) {
            jQuery.each(datos, function(id, dato){
                if(id == "consecutivo_documento"){
                    if(dato != 0){
                        $('#consecutivo_documento').val(dato);
                        $('#consecutivo_documento').attr("readonly","readonly");
                    }else{
                        $('#consecutivo_documento').val('');
                        $('#consecutivo_documento').removeAttr("readonly");
                    }
                }else if(id == "genera_cheque"){
                    if(dato == '1'){
                        genera = true;
                    }
                    $('#maneja_cheque').val(dato);
                }else if(id == "consecutivo_cheque"){
                    $('#consecutivo_cheque').removeAttr("disabled").parent().show();
                    $('#consecutivo_cheque').val(dato);
                } else{
                    lista = lista+'<option value="'+id+'">'+dato+'</option>';
                    $('#cuenta_bancaria').removeAttr("disabled").parent().show();
                    $('#cuenta_bancaria').html(lista);
                    contador++;
                }
            });
            if(genera && contador==0){
                alert($('#error_no_cuentas_bancarias').val());
                existenCuentas = false;
            }else if(genera && contador>0){
                existenCuentas = true;
                consecutivoCheque();
            }
        });
    }

    function consecutivoCheque(){
        var destino = $('#URLFormulario').val();
        var llave   = $('#cuenta_bancaria').val();
        $('#codigo_contable').val(llave.split('|')[8]);
        $.getJSON(destino, {recargar_consecutivo_cheque: true, cuenta: llave}, function(datos){
            $('#consecutivo_cheque').val(datos[0]);
            $('#selector2').val(datos[1]);
            $('#selector2').attr("disabled","disabled");
            var lista ='<option value="'+datos[2]+'">'+datos[3]+'</option>';
            /*$('#auxiliar_contable').removeAttr("disabled");
            $('#auxiliar_contable').html(lista);*/
        });
    }

    function validarCamposObligatorios()
    {
        var valor_prestamo      = parseInt($("#valor_prestamo").val());
        var valor_descuento     = parseInt($("#valor_descuento").val());
        var documento_identidad = $("#documento_empleado").val();
        var mensaje             = $("#mensaje_vacios_campos").val();

        var campos_vacios   = false;

        if (valor_prestamo < valor_descuento){
            mensaje = $("#valor_cuota_mayor").val();
            campos_vacios = true;
        } else {
            if(campoVacio(documento_identidad)){
                mensaje += $("#nombre_empleado_vacio").val();
                campos_vacios = true;
            }
            if(campoVacio(valor_prestamo)){
                mensaje       += $("#mensaje_vacio_valor_prestamo").val();
                campos_vacios = true;
            }
            if(campoVacio(valor_descuento)){
                mensaje += $("#mensaje_vacio_valor_cuota").val();
                campos_vacios = true;
            }
        }

        if(campos_vacios){
            $('#pestanas > ul').tabs("disable",1);
            alert(mensaje);
        }else{
            $('#pestanas > ul').tabs("enable",1);
        }
    }


    function mostrarBotonActualizar(campo){
        var actualizo_cuota = $("#actualizar_cuota").val();
        if(actualizo_cuota == '0'){
            var estado   = $("#boton_actualizar").val();
            var id_campo = $(campo).parents('tr').attr('id');
            if(estado=='0'){
                $(campo).parent().append("<span id='botonRestaurar' onclick='actualizarCampos(\""+id_campo+"\")' class='botonTexto'><img alt='' src='imagenes/restaurar.png'></span>");
                $("#boton_actualizar").val('1');
            }
        }else{
            alert($("#mensaje_actualizar_cuota").val());
        }
    }

    function actualizarCampos(id_campo){
        $("#botonRestaurar").remove();
        $("#botonRestaurar").remove();
        $("#boton_actualizar").val('0');
        cargarNuevosModificaCuota(id_campo);
    }


    function valorPrestamo(){
        removerTabla();

        var valor_prestamo     = parseInt($("#valor_prestamo").val());
        var valor_descuento    = parseInt($("#valor_descuento").val());
        var valor_cuota_minima = parseInt($("#valor_cuota_minima").val());

        if (valor_prestamo > 0 && valor_prestamo < valor_cuota_minima){
            $("#valor_prestamo").val(valor_cuota_minima);
        }
        var valor_prestamo     = parseInt($("#valor_prestamo").val());

        if (valor_prestamo < valor_descuento){
            $("#valor_descuento").val(valor_prestamo);
        }

    }
    function actualizarCuotas()
    {

        $("#actualizar_cuota").val("1");
        removerTabla();
        var valor_prestamo     = parseInt($("#valor_prestamo").val());
        var valor_descuento    = parseInt($("#valor_descuento").val());
        var valor_cuota_minima = parseInt($("#valor_cuota_minima").val());

        if(valor_descuento < valor_cuota_minima){
            $("#valor_descuento").val(valor_cuota_minima);
        }

        if(valor_prestamo > valor_descuento){
            cargarNuevosDatos();
        }
        $("#valor_cuota_estable").val($("#valor_descuento").val());
    }
