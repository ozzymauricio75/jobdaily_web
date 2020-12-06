    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });

    function valorCuota() {
        var destino        = $('#URLFormulario').val();
        var numero_credito = $('#selector5').val();
        var numero_cuota   = $('#cuotas_credito').val();

        $.getJSON(destino, {valorCuota: true, numero_credito: numero_credito, numero_cuota: numero_cuota }, function(datos) {
            if(datos!=""){
                $('#valor_de_cuota').text(datos);
            }else{
                alert('No existen datos para calcular la cuota');
                $('#valor_de_cuota').text('');
            }
        });
    }

    function activaCampos() {
        var destino         = $('#URLFormulario').val();
        var codigo_concepto = $('#codigo_concepto').val();

        $.getJSON(destino, {activaCampos: true, codigo_concepto: codigo_concepto}, function(datos) {
            if(datos!=0){
                $('#selector5').parent().show();
                $('#valor_credito').parent().show();
                $('#cuotas_credito').parent().show();
            } else {
                $('#selector5').parent().hide();
                $('#valor_credito').parent().hide();
                $('#cuotas_credito').parent().hide();
            }
        });
    }

    function activaCamposProveedorMovimiento() {
        var destino      = $('#URLFormulario').val();
        var codigo_grupo = $('#codigo_grupo').val();

        $.getJSON(destino, {activaCamposProveedorMovimiento: true, codigo_grupo: codigo_grupo}, function(datos) {
            if(datos!=0){
                $('#selector4').parent().show();
                $('#cuenta_destino').parent().show();
                $('#banco_proveedor').parent().show();
            } else {
                $('#selector4').parent().hide();
                $('#cuenta_destino').parent().hide();
                $('#banco_proveedor').parent().hide();
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
                 $('#cuotas_credito').val('');
            }
        });
    }

    function validarMonto() {
        var destino          = $('#URLFormulario').val();
        var codigo_concepto  = $('#codigo_concepto').val();
        var valor_movimiento = $('#valor').val();
        var numero_cuenta    = $('#selector3').val();

        $.getJSON(destino, {validarMonto: true, codigo_concepto: codigo_concepto, valor_movimiento: valor_movimiento, 
            numero_cuenta: numero_cuenta }, function(datos) {
            
            if(datos=="0"){
                alert('El valor del movimiento supera el saldo de la cuenta');
            }
        });
    }

    function cargaValorCredito() {
        var destino        = $('#URLFormulario').val();
        var numero_credito = $('#selector5').val();

        $.getJSON(destino, {cargaValorCredito: true, numero_credito: numero_credito }, function(datos) {
            if(datos!=""){
                $('#valor_credito').val(datos);
            }else{
                alert('No existen datos para calcular el valor');
                $('#valor_credito').val('');
            }
        });
    }

    function activaCamposCuentas(){
        var destino = $('#URLFormulario').val();

        if ($(".por_cuenta").is(':checked')){
            $('.por_banco').parent().show();
            $('#por_cuenta_activo').val(2);

        }else{
            $('.por_banco').val('');
            $('.por_banco').parent().hide();
        }
    }

    function activaCamposCreditos(){
        var destino = $('#URLFormulario').val();

        if ($(".por_cuenta").is(':checked')){
            $('.por_banco').parent().show();
            $('#por_cuenta_activo').val(2);

        }else{
            $('.por_banco').val('');
            $('.por_banco').parent().hide();
        }
    }

    function activaCamposProyectos(){
        var destino = $('#URLFormulario').val();

        if ($(".por_proyecto").is(':checked')){
            $('.por_proyecto_seleccionado').parent().show();
            $('#por_proyecto_activo').val(2);

        }else{
            $('.por_proyecto_seleccionado').val('');
            $('.por_proyecto_seleccionado').parent().hide();
        }
    }

    function activaCamposProveedores(){
        var destino = $('#URLFormulario').val();

        if ($(".por_proveedor").is(':checked')){
            $('.por_proveedor_seleccionado').parent().show();
            $('#por_proveedor_activo').val(2);
            
        }else{
            $('.por_proveedor_seleccionado').val('');
            $('.por_proveedor_seleccionado').parent().hide();
        }
    }

    function activaCamposConceptos(){
        var destino = $('#URLFormulario').val();

        if ($(".por_concepto").is(':checked')){
            $('.por_concepto_seleccionado').parent().show();
            $('#por_concepto_activo').val(2);
            
        }else{
            $('.por_concepto_seleccionado').val('');
            $('#codigo_concepto').text('');
            $('.por_concepto_seleccionado').parent().hide();
        }
    }

    function activaSaldosListado(){
        var destino = $('#URLFormulario').val();

        if ($(".por_saldo").is(':checked')){
            $('#muestra_saldo').val(1);
        }else{
            $('#muestra_saldo').val(0);
        }
    }

    function cargarCuenta() {
        var destino     = $('#URLFormulario').val();
        var cuenta      = $('#selector3').val();

        $.getJSON(destino, {cargarCuenta: true, cuenta: cuenta}, function(datos) {
            if(datos!=""){
                $('#banco').val(datos[0]);
                $('#tercero').val(datos[1]);
            }else{
                alert('No existen datos con ese numero de cuenta');
                $('#selector3').val('');
                $('#banco').val('');
                $('#tercero').val('');
                $('#selector3').focus();
            }
        });
    }

    function cargarCuentas() {
        var destino         = $('#URLFormulario').val();
        var cuenta_destino  = $('#selector6').val();

        $.getJSON(destino, {cargarCuentas: true, cuenta_destino: cuenta_destino}, function(datos) {
            if(datos!=""){
                $('#banco_destino').val(datos[0]);
                $('#tercero_destino').val(datos[1]);
            }else{
                alert('No existen datos con ese numero de cuenta');
                $('#selector6').val('');
                $('#banco_destino').val('');
                $('#tercero_destino').val('');
                $('#selector6').focus();
            }
        });
    }

    function cargarBancoProveedor() {
        var destino          = $('#URLFormulario').val();
        var cuenta_destino   = $('#cuenta_destino').val();

        $.getJSON(destino, {cargarBancoProveedor: true, cuenta_destino: cuenta_destino}, function(datos) {
            $('#banco_proveedor').val(datos);          
        });
    }

    function saldoCuenta() {
        var destino     = $('#URLFormulario').val();
        var cuenta      = $('#selector3').val();

        $.getJSON(destino, {saldoCuenta: true, cuenta: cuenta}, function(datos) {
            if(datos==0){
                alert('No existe saldo inicial de la cuenta origen');
                $('#selector3').val('');
                $('#banco').val('');
                $('#tercero').val('');
                $('#selector3').focus();
            }
        });
    }

    function cuentasDiferentes() {
        var destino        = $('#URLFormulario').val();
        var cuenta_origen  = $('#selector3').val();
        var cuenta_destino = $('#selector6').val();

        $.getJSON(destino, {cuentasDiferentes: true, cuenta_origen: cuenta_origen, cuenta_destino: cuenta_destino}, function(datos) {
            if(datos==0){
                alert('La cuenta destino debe ser diferente a la de origen');
                $('#selector3').val('');
                $('#selector6').val('');
                $('#selector3').focus();
            }
        });
    }

    function valorSaldo() {
        var destino     = $('#URLFormulario').val();
        var cuenta      = $('#selector3').val();
        var valor       = $('#valor').val();

        $.getJSON(destino, {valorSaldo: true, cuenta: cuenta, valor: valor}, function(datos) {
            if(datos==0){
                alert('El valor del movimiento es mayor que el saldo de la cuenta');
                $('#valor').val('');
                $('#valor').focus();
            }
        });
    }

    function cargarCuentaProveedor() {
        var destino         = $('#URLFormulario').val();
        var nit_proveedor   = $('#selector4').val();
        var lista           = '';

        $.getJSON(destino, {cargarCuentaProveedor: true, nit_proveedor: nit_proveedor}, function(datos) {
            jQuery.each(datos,function(valor, descripcion){
                if(valor=="0"){
                    alert(descripcion);
                    lista = lista+'<option value="">'+''+'</option>';
                }else{
                    lista = lista+'<option value="'+valor+'">'+descripcion+'</option>';
                }
            });
            $('#cuenta_destino').html(lista);
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

    function recargarDatos(cuenta) {
        var destino     = $('#URLFormulario').val();
        var lista       = '';

        $.getJSON(destino, {recargarDatosCuenta: true, id: cuenta}, function(datos) {
            jQuery.each(datos, function(id, dato){

                if (id) {
                    lista = lista+'<option value="'+id+'">'+dato+'</option>';
                    $('#auxiliar_contable').removeClass("oculto").parent().show();
                } else {

                    if (isNaN(dato)) {
                        $('#errorDialogo').html('');
                        $('#errorDialogo').removeAttr('class','style');
                        $('#errorDialogo').css('display','block');
                        $('#errorDialogo').html(dato).fadeOut(6000).addClass('mensajeError');

                        $('#selector1').val('').focus();
                        $('#codigo_plan_contable').val('');
                    } else {
                        lista = '<option value="0||0"> </option>';
                    }

                    $('#auxiliar_contable').addClass("oculto").parent().hide();
                }
            });

            $('#auxiliar_contable').html(lista);
        });
    }

    function verificarConceptos(){
        var destino      = $('#URLFormulario').val();
        var codigo_grupo = $('#codigo_grupo').val();
        var lista        = '';

        $.getJSON(destino,{recargar_conceptos:true, codigo_grupo: codigo_grupo},function(datos){
            jQuery.each(datos,function(valor, descripcion){
                if(valor=="0"){
                    alert(descripcion);
                    lista = lista+'<option value="">'+''+'</option>';
                }else{
                lista = lista+'<option value="'+valor+'">'+descripcion+'</option>';
                }
            });
            $('#codigo_concepto').html(lista);
        });
    }

    function cargarCuotasCreditos(){
        var destino         = $('#URLFormulario').val();
        var numero_credito  = $('#selector5').val();
        var lista_cuotas    = '';

        $.getJSON(destino,{cargarCuotasCreditos:true, numero_credito: numero_credito},function(datos){
            jQuery.each(datos,function(valor, descripcion){
                
                if(valor=="0"){
                    alert(descripcion);
                    lista_cuotas = lista_cuotas+'<option value="">'+''+'</option>';
                }else{
                    lista_cuotas = lista_cuotas+'<option value="'+valor+'">'+descripcion+'</option>';
                    $('#cuotas_credito').html(lista_cuotas);
                }
            });
        });
    }

    function verificarSucursalesListado(){
        var destino  = $('#URLFormulario').val();
        var id_banco = $('#codigo_banco').val();
        var lista    = '<option value="0"></option>';

        $.getJSON(destino,{recargar_sucursales:true,id_banco:id_banco},function(datos){
            jQuery.each(datos,function(valor, descripcion){
                lista = lista+'<option value="'+valor+'">'+descripcion+'</option>';
            });
            $('#codigo_sucursal_banco').html(lista);
        });
    }
