    $(document).ready(function() {
        ejecutarFuncionesGlobales();
    });
    
    function recargarDatos(){    
    }

    /*** Cargar datos si el tercero existe ***/
    function cargarDatos() {
        var documento_identidad = $('#documento_identidad').val();
        var destino = $('#URLFormulario').val();

        /*** Descargar contenido  ***/
        $.getJSON(destino, {recargar: true, documento_identidad_carga: documento_identidad}, function(datos){
            if (datos != ""){
                $('#codigo_tipo_documento').val(datos[0]);
                $('#primer_nombre').val(datos[4]);
                $('#segundo_nombre').val(datos[5]);
                $('#primer_apellido').val(datos[6]);
                $('#segundo_apellido').val(datos[7]);
                $('#fecha_nacimiento').val(datos[8]);                
                $('#direccion').val(datos[12]);
                $('#telefono').val(datos[13]);
                $('#celular').val(datos[14]);
                $('#correo_electronico').val(datos[15]);
                $('#fax').val(datos[19]);
                $('#sitio_web').val(datos[20]);
                var genero=datos[16];

                if(genero=="M"){
                    $('#genero_masculino').click();
                }else{
                    $('#genero_femenino').click();
                } 
                
                var codigo_dane_municipio_documento = datos[1]+'|'+datos[2]+'|'+datos[3];
                $('#codigo_dane_municipio_documento').val(codigo_dane_municipio_documento);
                
                var codigo_dane_municipio_residencia = datos[9]+'|'+datos[10]+'|'+datos[11]+'|'+datos[17]+'|'+datos[18];
                $('#codigo_dane_municipio_residencia').val(codigo_dane_municipio_residencia);
                
                $.getJSON(destino, {recargarMunicipioDocumento: true, municipio_documento : codigo_dane_municipio_documento}, function(dato){
                    if(dato){
                        $('#selector1').val(dato);

                        $.getJSON(destino, {recargarMunicipioResidencia: true, municipio_residencia : codigo_dane_municipio_residencia}, function(dato2){
                            if(dato2){
                                $('#selector3').val(dato2);
                            }
                        });
                    }
                });

                

            }
        });
    }

    function agregarItem_laboral(men1, men2) {

        var nombreEmpresa               = $("#nombre_empresa").val();
        var ActividadEconomica          = $("#selector13").val();
        var tipoActividadEconomica      = $("#codigo_actividad_economica").val();
        var direccionEmpresa            = $("#direccion_empresa").val();
        var telefonoEmpresa             = $("#telefono_empresa").val();
        var departamento                = $("#codigo_departamento_empresa :selected").text();
        var departamentoEmpresa         = $("#codigo_departamento_empresa").val();
        var cargo                       = $("#codigo_cargo_empresa :selected").text();
        var cargoEmpresa                = $("#codigo_cargo_empresa").val();
        var jefeInmediatoEmpresa        = $("#jefe_inmediato").val();
        var fechaInicialEmpresa         = $("#fecha_inicial_empresa").val();
        var fechaFinalEmpresa           = $("#fecha_final_empresa").val();
        var horario                     = $("#horario_laboral_empresa :selected").text();
        var horarioEmpresa              = $("#horario_laboral_empresa").val();
        var contrato                    = $("#codigo_tipo_contrato :selected").text();
        var contratoEmpresa             = $("#codigo_tipo_contrato").val();
        var motivoRetiro                = $("#codigo_motivo_retiro :selected").text();
        var motivoRetiroEmpresa         = $("#codigo_motivo_retiro").val();
        var logrosEmpresa               = $("#logros_obtenidos").val();
        var lista_empresa               = parseInt($("#lista_empresa").val());

        var valorClase = '';
        if ($("#listaItemsLaboral tr:last").hasClass("even")) {
                valorClase = 'odd';
        } else {
                valorClase = 'even';
        }        

        if(nombreEmpresa && ActividadEconomica){

            var descripcionAc = tipoActividadEconomica.replace(/,/g, "|");
            var destino = $('#URLFormulario').val();

            $.getJSON(destino, {validarEnTablas: true, tabla : 'seleccion_actividades_economicas', campo : 'id', valor : descripcionAc}, function(dato){
                if(dato){
                    var res = dato;

                    if (res==1) {
                        var boton = $('#botonRemoverLaboral').html();
                        var item  = '<tr id="fila_'+lista_empresa+'" class="'+valorClase+'">'+
                        '<td align="center">'+
                            '<input type="hidden" class="idPosicionTablaEmpresa" name="idPosicionTablaEmpresa['+lista_empresa+']" value="'+lista_empresa+'">'+
                            '<input type="hidden" class="nombreTablaEmpresa" name="nombreTablaEmpresa['+lista_empresa+']" value="'+nombreEmpresa+'">'+
                            '<input type="hidden" class="tipoActividadEconomicaTablaEmpresa" name="tipoActividadEconomicaTablaEmpresa['+lista_empresa+']" value="'+tipoActividadEconomica+'">'+
                            '<input type="hidden" class="direccionTablaEmpresa" name="direccionTablaEmpresa['+lista_empresa+']" value="'+direccionEmpresa+'">'+
                            '<input type="hidden" class="telefonoTablaEmpresa" name="telefonoTablaEmpresa['+lista_empresa+']" value="'+telefonoEmpresa+'">'+
                            '<input type="hidden" class="departamentoTablaEmpresa" name="departamentoTablaEmpresa['+lista_empresa+']" value="'+departamentoEmpresa+'">'+
                            '<input type="hidden" class="cargoTablaEmpresa" name="cargoTablaEmpresa['+lista_empresa+']" value="'+cargoEmpresa+'">'+
                            '<input type="hidden" class="jefeInmediatoTablaEmpresa" name="jefeInmediatoTablaEmpresa['+lista_empresa+']" value="'+jefeInmediatoEmpresa+'">'+
                            '<input type="hidden" class="fechaInicialTablaEmpresa" name="fechaInicialTablaEmpresa['+lista_empresa+']" value="'+fechaInicialEmpresa+'">'+
                            '<input type="hidden" class="fechaFinalTablaEmpresa" name="fechaFinalTablaEmpresa['+lista_empresa+']" value="'+fechaFinalEmpresa+'">'+
                            '<input type="hidden" class="horarioTablaEmpresa" name="horarioTablaEmpresa['+lista_empresa+']" value="'+horarioEmpresa+'">'+
                            '<input type="hidden" class="contratoTablaEmpresa" name="contratoTablaEmpresa['+lista_empresa+']" value="'+contratoEmpresa+'">'+
                            '<input type="hidden" class="motivoRetiroTablaEmpresa" name="motivoRetiroTablaEmpresa['+lista_empresa+']" value="'+motivoRetiroEmpresa+'">'+
                            '<input type="hidden" class="logrosTablaEmpresa" name="logrosTablaEmpresa['+lista_empresa+']" value="'+logrosEmpresa+'">'+
                            boton+
                        '</td>'+
                        '<td align="left">'+nombreEmpresa+'<br />'+ActividadEconomica+'<br />'+direccionEmpresa+'<br />'+telefonoEmpresa+'</td>'+
                        '<td align="left">'+departamento+'</td>'+
                        '<td align="left">'+cargo+'<br />'+jefeInmediatoEmpresa+'<br />'+fechaInicialEmpresa+'<br />'+fechaFinalEmpresa+'<br />'+horario+'</td>'+
                        '<td align="left">'+contrato+'</td>'+
                        '<td align="left">'+motivoRetiro+'</td>'+
                        '<td align="left">'+logrosEmpresa+'</td>'+
                        '</tr>';
                        $('#listaItemsLaboral').prepend(item);

                        $("#nombre_empresa").val('');
                        $("#selector13").val('');
                        $("#codigo_actividad_economica").val('');
                        $("#direccion_empresa").val('');
                        $("#telefono_empresa").val('');
                        $("#jefe_inmediato").val('');
                        var mes   = new Date().getMonth()+1;
                        if(mes<10){
                            mes = '0'+mes;
                        }
                        var fecha = new Date().getFullYear()+'-'+mes+'-'+new Date().getDate();
                        $("#fecha_inicial_empresa").val(fecha);
                        $("#fecha_final_empresa").val(fecha);
                        $("#logros_obtenidos").val('');
                        lista_empresa++;
                        $("#lista_empresas").val(lista_empresa);
                    }else{
                        $("#selector13").parent().children('#errorDialogo').remove();
                        $("#selector13").focus();
                        $("#selector13").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men1+'</span>');
                        $("#selector13").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                    }
                }
            });            
        }else{
            $("#selector13").parent().children('#errorDialogo').remove();
            $("#selector13").focus();
            $("#selector13").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men2+'</span>');
            $("#selector13").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }
    }


    function agregarItem_educacion(men1, men2, men3) {

        var nivelEducacion              = $("#codigo_escolaridad :selected").text();
        var gradoEducacion              = $("#codigo_escolaridad").val();
        var tituloEducacion             = $("#titulo").val();
        var fechaInicialEducacion       = $("#fecha_inicial_estudios").val();
        var fechaFinalEducacion         = $("#fecha_final_estudios").val();
        var intensidadHorariaEducacion  = $("#intensidad_horaria_estudios").val();
        var horario                     = $("#horario_estudios :selected").text();
        var horarioEducacion            = $("#horario_estudios").val();
        var institutoEducacion          = $("#institucion").val();
        var municipio                   = $("#selector6").val();
        var municipioEducacion          = $("#codigo_dane_municipio_estudios").val();
        var lista_educacion             = parseInt($("#lista_educacion").val());

        var valorClase = '';
        
        if ($("#listaItemsEducacion tr:last").hasClass("even")) {
            valorClase = 'odd';
        }else{
            valorClase = 'even';
        }

        var idMunicipio = municipioEducacion.replace(/,/g, "|");
        var idEducacion = gradoEducacion;
        var destino = $('#URLFormulario').val();

        if (nivelEducacion && tituloEducacion && institutoEducacion && municipio) {

             $.getJSON(destino, {validarEnTablas: true, tabla : 'seleccion_escolaridad', campo : 'id', valor : idEducacion}, function(dato){
                if(dato){
                    var res1 = dato;

                    if (res1==1){

                         $.getJSON(destino, {validarEnTablas: true, tabla : 'seleccion_municipios', campo : 'id', valor : idMunicipio}, function(dato2){
                            if(dato2){
                                var res2 = dato2;

                                if (res2==1){
                                    var boton = $('#botonRemoverEducacion').html();
                                    var item  = '<tr id="fila_'+lista_educacion+'" class="'+valorClase+'">'+
                                        '<td align="center">'+
                                            '<input type="hidden" class="idPosicionTablaEducacion" name="idPosicionTablaEducacion['+lista_educacion+']" value="'+lista_educacion+'">'+
                                            '<input type="hidden" class="gradoEducacionTabla" name="gradoEducacionTabla['+lista_educacion+']" value="'+gradoEducacion+'">'+
                                            '<input type="hidden" class="tituloEducacionTabla" name="tituloEducacionTabla['+lista_educacion+']" value="'+tituloEducacion+'">'+
                                            '<input type="hidden" class="fechaInicialEducacionTabla" name="fechaInicialEducacionTabla['+lista_educacion+']" value="'+fechaInicialEducacion+'">'+
                                            '<input type="hidden" class="fechaFinalEducacionTabla" name="fechaFinalEducacionTabla['+lista_educacion+']" value="'+fechaFinalEducacion+'">'+
                                            '<input type="hidden" class="intensidadHorariaEducacionTabla" name="intensidadHorariaEducacionTabla['+lista_educacion+']" value="'+intensidadHorariaEducacion+'">'+
                                            '<input type="hidden" class="horarioEducacionTabla" name="horarioEducacionTabla['+lista_educacion+']" value="'+horarioEducacion+'">'+
                                            '<input type="hidden" class="institutoEducacionTabla" name="institutoEducacionTabla['+lista_educacion+']" value="'+institutoEducacion+'">'+
                                            '<input type="hidden" class="municipioEducacionTabla" name="municipioEducacionTabla['+lista_educacion+']" value="'+municipioEducacion+'">'+
                                            boton+
                                        '</td>'+
                                        '<td align="left">'+nivelEducacion+'</td>'+
                                        '<td align="left">'+tituloEducacion+'</td>'+
                                        '<td align="left">'+fechaInicialEducacion+'</td>'+
                                        '<td align="left">'+fechaFinalEducacion+'</td>'+
                                        '<td align="left">'+intensidadHorariaEducacion+'</td>'+
                                        '<td align="left">'+horario+'</td>'+
                                        '<td align="left">'+institutoEducacion+'</td>'+
                                        '<td align="left">'+municipio+'</td>'+
                                        '</tr>';
                                    $('#listaItemsEducacion').append(item);

                                    $("#codigo_escolaridad").val('');
                                    $("#titulo").val('');
                                    $("#fecha_inicial_estudios").val('');
                                    $("#fecha_final_estudios").val('');
                                    $("#intensidad_horaria_estudios").val('');
                                    $("#horario_estudios").val('1');
                                    $("#institucion").val('');
                                    $("#selector6").val('');
                                    $("#codigo_dane_municipio_estudios").val('');
                                    lista_educacion++;
                                    $("#lista_educacion").val(lista_educacion);

                                }else{
                                    $("#institucion").parent().children('#errorDialogo').remove();
                                    $("#institucion").focus();
                                    $("#institucion").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men2+'</span>');
                                    $("#institucion").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                                }
                            }
                        });
                    }else{
                        $("#institucion").parent().children('#errorDialogo').remove();
                        $("#institucion").focus();
                        $("#institucion").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men1+'</span>');
                        $("#institucion").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                    }
                }
            });
        }else{
            $("#institucion").parent().children('#errorDialogo').remove();
            $("#institucion").focus();
            $("#institucion").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men3+'</span>');
            $("#institucion").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }
    }

    function agregarItem_idiomaExtranjero(men) {

        var idiomaCodigo        = $('#idiomas').val();
        var idioma              = $('#idiomas :selected').text();
        var Habla = '';
        var Lee = '';
        var Escribe = '';
        var HablaTexto = '';
        var LeeTexto = '';
        var EscribeTexto = '';
        var existe=false;

        $('#listaItemsIdiomas').find('.idiomaTabla').each(function () {
            id = $(this).val();
            if(idiomaCodigo==id){
                existe=true;
            }
        });
        
        if ($('#habla_excelente').attr('checked')){
            HablaTexto = $('#habla_excelente_texto').val();
            Habla = $('#habla_excelente').val();
        }
            
        else if($('#habla_bien').attr('checked')){
            HablaTexto = $('#habla_bien_texto').val();
            Habla = $('#habla_bien').val();
        }
            
        else if($('#habla_regular').attr('checked')){
            HablaTexto = $('#habla_regular_texto').val();
            Habla = $('#habla_regular').val();
        }

        else if($('#no_habla').attr('checked')){
            HablaTexto = $('#no_habla_texto').val();
            Habla = $('#no_habla').val();
        } 
        
        if ($('#lee_excelente').attr('checked')){
            LeeTexto = $('#lee_excelente_texto').val();
            Lee = $('#lee_excelente').val();
        }
            
        else if($('#lee_bien').attr('checked')){
            LeeTexto = $('#lee_bien_texto').val();
            Lee = $('#lee_bien').val();
        }
            
        else if($('#lee_regular').attr('checked')){
            LeeTexto = $('#lee_regular_texto').val();
            Lee = $('#lee_regular').val();
        }

        else if($('#no_lee').attr('checked')){
            LeeTexto = $('#no_lee_texto').val();
            Lee = $('#no_lee').val();
        }
        
        if ($('#escribe_excelente').attr('checked')){
            EscribeTexto = $('#escribe_excelente_texto').val();
            Escribe = $('#escribe_excelente').val();
        }
            
        else if($('#escribe_bien').attr('checked')){
            EscribeTexto = $('#escribe_bien_texto').val();
            Escribe = $('#escribe_bien').val();
        }
            
        else if($('#escribe_regular').attr('checked')){
            EscribeTexto = $('#escribe_regular_texto').val();
            Escribe = $('#escribe_regular').val();
        }

        else if($('#no_escribe').attr('checked')){
            EscribeTexto = $('#no_escribe_texto').val();
            Escribe = $('#no_escribe').val();
        }
                
       var lista_idiomas             = parseInt($("#lista_idiomas").val());

        var valorClase = '';
        if ($("#listaItemsIdiomas tr:last").hasClass("even")) {
                valorClase = 'odd';
        } else {
                valorClase = 'even';
        }

        if(!existe){
            var boton = $('#botonRemoverIdiomas').html();
            var item  = '<tr id="fila_'+lista_idiomas+'" class="'+valorClase+'">'+
                '<td align="center">'+
                    '<input type="hidden" class="idPosicionTablaIdioma" name="idPosicionTablaIdioma['+lista_idiomas+']" value="'+lista_idiomas+'">'+
                    '<input type="hidden" class="idiomaTabla" name="idiomaTabla['+lista_idiomas+']" value="'+idiomaCodigo+'">'+
                    '<input type="hidden" class="idiomaLoHablaTabla" name="idiomaLoHablaTabla['+lista_idiomas+']" value="'+Habla+'">'+
                    '<input type="hidden" class="idiomaLoLeeTabla" name="idiomaLoLeeTabla['+lista_idiomas+']" value="'+Lee+'">'+
                    '<input type="hidden" class="idiomaLoEscribeTabla" name="idiomaLoEscribeTabla['+lista_idiomas+']" value="'+Escribe+'">'+
                    boton+
                '</td>'+
                '<td class="dato" align="left">'+idioma+'</td>'+
                '<td class="dato" align="left">'+HablaTexto+'</td>'+
                '<td class="dato" align="left">'+LeeTexto+'</td>'+
                '<td class="dato" align="left">'+EscribeTexto+'</td>'+
                '</tr>';
            $('#listaItemsIdiomas').append(item);
            $('#no_lee').click();
            $('#no_habla').click();
            $('#no_escribe').click();
            $('#idiomas').val('');
            lista_idiomas++;
            $("#lista_idiomas").val(lista_idiomas);
        }else{
            $("#idiomas").parent().children('#errorDialogo').remove();
            $("#idiomas").focus();
            $("#idiomas").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men+'</span>');
            $("#idiomas").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }
    }

    function agregarItem_familiar(men1, men2, men3) {

        var tipoDocumento            = $("#codigo_tipo_documento_familiar :selected").text();
        var tipoDocumentoFamiliar    = $("#codigo_tipo_documento_familiar").val();
        var numeroDocumentoFamiliar  = $("#documento_identidad_familiar").val();
        var nombreFamiliar           = $("#nombre_completo_familiar").val();
        var profesion                = $("#selector10").val();
        var profesionFamiliar        = $("#codigo_dane_profesion_familiar").val();
        var relacion                 = $("#parentesco_familiar :selected").text();
        var relacionFamiliar         = $("#parentesco_familiar").val();
        var fecha_nacimientoFamiliar = $("#fecha_nacimiento_familiar").val();
        var DependeTexto = '';
        var depende = '';
        var generoTexto = '';
        var genero = '';

        var existe=false;

        if ($('#dependencia_economica_si').attr('checked')){
            DependeTexto = $('#depende_texto_si').val();
            depende = $('#dependencia_economica_si').val();
        }
            
        else if ($('#dependencia_economica_no').attr('checked')){
            DependeTexto = $('#depende_texto_no').val();
            depende = $('#dependencia_economica_no').val();
        }
        
        if ($('#genero_masculino_familia').attr('checked')){
            generoTexto = $('#genero_texto_masculino').val();
            genero = $('#genero_masculino_familia').val();
        }
            
        else {
            generoTexto = $('#genero_texto_femenino').val();
            genero = $('#genero_femenino_familia').val();
        }
        
        var lista_familia             = parseInt($("#lista_familiar").val());

        var valorClase = '';
        if ($("#listaItemsFamiliar tr:last").hasClass("even")) {
                valorClase = 'odd';
        } else {
                valorClase = 'even';
        }

        var fechaNac = "";
        var hoy      = "";
        var edad     = "--";

        if(fecha_nacimientoFamiliar){
            fechaNac = new Date(fecha_nacimientoFamiliar);
            hoy      = new Date();
            edad     = parseInt((hoy-fechaNac)/365/24/60/60/1000);
        }

        var destino = $('#URLFormulario').val();

        if (nombreFamiliar) {

                if(profesion){
                    $.getJSON(destino, {validarEnTablas: true, tabla : 'seleccion_profesiones', campo : 'id', valor : profesionFamiliar}, function(dato){
                        if(dato){                    
                            var res1 = dato;                    
                            if (res1=='1'){

                                var boton = $('#botonRemoverFamilia').html();
                                var item  = '<tr id="fila_'+lista_familia+'" class="'+valorClase+'">'+
                                    '<td align="center">'+
                                        '<input type="hidden" class="idPosicionTablaFamiliar" name="idPosicionTablaFamiliar['+lista_familia+']" value="'+lista_familia+'">'+
                                        '<input type="hidden" class="id_tipo_documento_familiarTabla" name="id_tipo_documento_familiarTabla['+lista_familia+']" value="'+tipoDocumentoFamiliar+'">'+
                                        '<input type="hidden" class="numero_documento_familiarTabla" name="numero_documento_familiarTabla['+lista_familia+']" value="'+numeroDocumentoFamiliar+'">'+
                                        '<input type="hidden" class="nombre_familiarTabla" name="nombre_familiarTabla['+lista_familia+']" value="'+nombreFamiliar+'">'+
                                        '<input type="hidden" class="id_profesion_familiarTabla" name="id_profesion_familiarTabla['+lista_familia+']" value="'+profesionFamiliar+'">'+
                                        '<input type="hidden" class="relacion_familiarTabla" name="relacion_familiarTabla['+lista_familia+']" value="'+relacionFamiliar+'">'+
                                        '<input type="hidden" class="fecha_nacimiento_familiarTabla" name="fecha_nacimiento_familiarTabla['+lista_familia+']" value="'+fecha_nacimientoFamiliar+'">'+
                                        '<input type="hidden" class="genero_familiarTabla" name="genero_familiarTabla['+lista_familia+']" value="'+genero+'">'+
                                        '<input type="hidden" class="dependenciaFamiliarTabla" name="dependenciaFamiliarTabla['+lista_familia+']" value="'+depende+'">'+
                                        boton+
                                    '</td>'+
                                    '<td align="left">'+tipoDocumento+'</td>'+
                                    '<td align="left">'+numeroDocumentoFamiliar+'</td>'+
                                    '<td align="left">'+nombreFamiliar+'</td>'+
                                    '<td align="left">'+profesion+'</td>'+
                                    '<td align="left">'+relacion+'</td>'+
                                    '<td align="left">'+fecha_nacimientoFamiliar+'</td>'+
                                    '<td align="left">'+edad+'</td>'+
                                    '<td align="left">'+generoTexto+'</td>'+
                                    '<td align="left">'+DependeTexto+'</td>'+
                                    '</tr>';
                                $('#listaItemsFamiliar').append(item);
                                $("#codigo_tipo_documento_familiar").val('');
                                $("#documento_identidad_familiar").val('');
                                $("#nombre_completo_familiar").val('');
                                $("#selector10").val('');
                                $("codigo_dane_profesion_familiar").val('');
                                $("#parentesco_familiar").val('');
                                $("#fecha_nacimiento_familiar").val('');
                                $('#dependencia_economica_si').click();
                                $('#genero_femenino_familia').click();
                                lista_familia++;
                                $("#lista_familia").val(lista_familia);
                            }else{
                                $("#nombre_completo_familiar").parent().children('#errorDialogo').remove();
                                $("#nombre_completo_familiar").focus();
                                $("#nombre_completo_familiar").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men3+'</span>');
                                $("#nombre_completo_familiar").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                            }
                        }
                    });
                }else{
                    var boton = $('#botonRemoverFamilia').html();
                    var item  = '<tr id="fila_'+lista_familia+'" class="'+valorClase+'">'+
                        '<td align="center">'+
                            '<input type="hidden" class="idPosicionTablaFamiliar" name="idPosicionTablaFamiliar['+lista_familia+']" value="'+lista_familia+'">'+
                            '<input type="hidden" class="id_tipo_documento_familiarTabla" name="id_tipo_documento_familiarTabla['+lista_familia+']" value="'+tipoDocumentoFamiliar+'">'+
                            '<input type="hidden" class="numero_documento_familiarTabla" name="numero_documento_familiarTabla['+lista_familia+']" value="'+numeroDocumentoFamiliar+'">'+
                            '<input type="hidden" class="nombre_familiarTabla" name="nombre_familiarTabla['+lista_familia+']" value="'+nombreFamiliar+'">'+
                            '<input type="hidden" class="id_profesion_familiarTabla" name="id_profesion_familiarTabla['+lista_familia+']" value="'+profesionFamiliar+'">'+
                            '<input type="hidden" class="relacion_familiarTabla" name="relacion_familiarTabla['+lista_familia+']" value="'+relacionFamiliar+'">'+
                            '<input type="hidden" class="fecha_nacimiento_familiarTabla" name="fecha_nacimiento_familiarTabla['+lista_familia+']" value="'+fecha_nacimientoFamiliar+'">'+
                            '<input type="hidden" class="genero_familiarTabla" name="genero_familiarTabla['+lista_familia+']" value="'+genero+'">'+
                            '<input type="hidden" class="dependenciaFamiliarTabla" name="dependenciaFamiliarTabla['+lista_familia+']" value="'+depende+'">'+
                            boton+
                        '</td>'+
                        '<td align="left">'+tipoDocumento+'</td>'+
                        '<td align="left">'+numeroDocumentoFamiliar+'</td>'+
                        '<td align="left">'+nombreFamiliar+'</td>'+
                        '<td align="left">'+profesion+'</td>'+
                        '<td align="left">'+relacion+'</td>'+
                        '<td align="left">'+fecha_nacimientoFamiliar+'</td>'+
                        '<td align="left">'+edad+'</td>'+
                        '<td align="left">'+generoTexto+'</td>'+
                        '<td align="left">'+DependeTexto+'</td>'+
                        '</tr>';
                    $('#listaItemsFamiliar').append(item);
                    $("#codigo_tipo_documento_familiar").val('');
                    $("#documento_identidad_familiar").val('');
                    $("#nombre_completo_familiar").val('');
                    $("#selector10").val('');
                    $("codigo_dane_profesion_familiar").val('');
                    $("#parentesco_familiar").val('');
                    $("#fecha_nacimiento_familiar").val('');
                    $('#dependencia_economica_si').click();
                    $('#genero_femenino_familia').click();
                    lista_familia++;
                    $("#lista_familia").val(lista_familia);
                }
            }else{
                $("#nombre_completo_familiar").parent().children('#errorDialogo').remove();
                $("#nombre_completo_familiar").focus();
                $("#nombre_completo_familiar").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men2+'</span>');
                $("#nombre_completo_familiar").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
            }
        
    }

    function agregarItem_vivienda(men) {

        var tipoVivienda                   = $("#tipo_vivienda :selected").text();
        var tipoDerechoVivienda            = $("#tipo_vivienda").val();
        var BarrioVivienda                 = $("#codigo_dane_municipio_localidad_vivienda").val();
        var Barrio                         = $("#selector14").val();
        var direccionVivienda              = $("#direccion_vivienda").val();
        var telefonoVivienda               = $("#telefono_vivienda").val();
        var hipotecaVivienda               = $("#hipoteca_vivienda").val();
        var Hipoteca = '';
        var HipotecaTexto = '';
        
        if ($('#si_hipoteca').attr('checked')){
           HipotecaTexto = $('#hipoteca_texto_si').val();
           Hipoteca  = $('#si_hipoteca').val();
        }
            
        else{
            HipotecaTexto = $('#hipoteca_texto_no').val();
            Hipoteca = $('#no_hipoteca').val();
        }
        
        var lista_vivienda             = parseInt($("#lista_vivienda").val());
                
        var valorClase = '';
        if ($("#listaItemsVivienda tr:last").hasClass("even")) {
                valorClase = 'odd';
        } else {
                valorClase = 'even';
        }


       if (direccionVivienda && BarrioVivienda && telefonoVivienda) {
            var boton = $('#botonRemoverVivienda').html();
            var item  = '<tr id="fila_'+lista_vivienda+'" class="'+valorClase+'">'+
                    '<td align="center">'+
                        '<input type="hidden" class="idPosicionTablaVivienda" name="idPosicionTablaVivienda['+lista_vivienda+']" value="'+lista_vivienda+'">'+
                        '<input type="hidden" class="id_tipo_vivienda_Tabla" name="id_tipo_vivienda_Tabla['+lista_vivienda+']" value="'+tipoDerechoVivienda+'">'+
                        '<input type="hidden" class="hipoteca_vivienda_Tabla" name="hipoteca_vivienda_Tabla['+lista_vivienda+']" value="'+Hipoteca+'">'+
                        '<input type="hidden" class="direccion_vivienda_Tabla" name="direccion_vivienda_Tabla['+lista_vivienda+']" value="'+direccionVivienda+'">'+
                        '<input type="hidden" class="barrio_vivienda_Tabla" name="barrio_vivienda_Tabla['+lista_vivienda+']" value="'+BarrioVivienda+'">'+
                        '<input type="hidden" class="telefono_vivienda_Tabla" name="telefono_vivienda_Tabla['+lista_vivienda+']" value="'+telefonoVivienda+'">'+
                        boton+
                    '</td>'+
                    '<td align="left">'+tipoVivienda+'</td>'+
                    '<td align="center">'+HipotecaTexto+'</td>'+
                    '<td align="left">'+Barrio+'</td>'+
                    '<td align="left">'+direccionVivienda+'</td>'+
                    '<td align="left">'+telefonoVivienda+'</td>'+
                    '</tr>';
            $('#listaItemsVivienda').append(item);
            $("#tipo_vivienda").val('');
            $("#selector14").val('');
            $("#direccion_vivienda").val('');
            $("#codigo_dane_municipio_localidad_vivienda").val('');
            $("#telefono_vivienda").val('');
            $('#no_hipoteca').click();
            lista_vivienda++;
            $("#lista_vivienda").val(lista_vivienda);
        }else{
            $("#selector14").parent().children('#errorDialogo').remove();
            $("#selector14").focus();
            $("#selector14").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men+'</span>');
            $("#selector14").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }        
    }

    function agregarItem_vehiculo(men) {

        var Vehiculo            = $("#tipo_vehiculo :selected").text();
        var tipoVehiculo        = $("#tipo_vehiculo").val();
        var modeloVehiculo      = $("#modelo_vehiculo").val();
        var marcaVehiculo       = $("#marca_vehiculo").val();
        var matriculaVehiculo   = $("#matricula_vehiculo").val();
        var pignorado = '';
        var pignoradoTexto = '';
        
        if ($('#vehiculo_pignorado').attr('checked')){
           pignoradoTexto = $('#pignorado_texto_si').val();
           pignorado  = $('#vehiculo_pignorado').val();
        }
            
        else{
            pignoradoTexto = $('#pignorado_texto_no').val();
            pignorado = $('#vehiculo_no_pignorado').val();
        }
        
        var lista_vehiculo             = parseInt($("#lista_vehiculo").val());
                
        var valorClase = '';
        if ($("#listaItemsVehiculo tr:last").hasClass("even")) {
                valorClase = 'odd';
        } else {
                valorClase = 'even';
        }

       if (modeloVehiculo && marcaVehiculo && matriculaVehiculo) {
            var boton = $('#botonRemoverVehiculo').html();
            var item  = '<tr id="fila_'+lista_vehiculo+'" class="'+valorClase+'">'+
                    '<td align="center">'+
                        '<input type="hidden" class="idPosicionTablaVehiculoTabla" name="idPosicionTablaVehiculoTabla['+lista_vehiculo+']" value="'+lista_vehiculo+'">'+
                        '<input type="hidden" class="id_tipo_vehiculo_Tabla" name="id_tipo_vehiculo_Tabla['+lista_vehiculo+']" value="'+tipoVehiculo+'">'+
                        '<input type="hidden" class="modelo_vehiculo_Tabla" name="modelo_vehiculo_Tabla['+lista_vehiculo+']" value="'+modeloVehiculo+'">'+
                        '<input type="hidden" class="marca_vehiculo_Tabla" name="marca_vehiculo_Tabla['+lista_vehiculo+']" value="'+marcaVehiculo+'">'+
                        '<input type="hidden" class="placa_vehiculo_Tabla" name="placa_vehiculo_Tabla['+lista_vehiculo+']" value="'+matriculaVehiculo+'">'+
                        '<input type="hidden" class="pignorado_vehiculo_Tabla" name="pignorado_vehiculo_Tabla['+lista_vehiculo+']" value="'+pignorado+'">'+
                        boton+
                    '</td>'+
                    '<td align="left">'+Vehiculo+'</td>'+
                    '<td align="left">'+marcaVehiculo+'</td>'+
                    '<td align="left">'+modeloVehiculo+'</td>'+
                    '<td align="left">'+matriculaVehiculo+'</td>'+
                    '<td align="center">'+pignoradoTexto+'</td>'+
                    '</tr>';
            $('#listaItemsVehiculo').append(item);
            $("#tipo_vehiculo").val('');
            $("#modelo_vehiculo").val('');
            $("#marca_vehiculo").val('');
            $("#matricula_vehiculo").val('');
            $("#pignorado").val('');
            lista_vehiculo++;
            $("#lista_vehiculo").val(lista_vehiculo);
        }else{
            $("#marca_vehiculo").parent().children('#errorDialogo').remove();
            $("#marca_vehiculo").focus();
            $("#marca_vehiculo").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men+'</span>');
            $("#marca_vehiculo").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }        
    }

    function agregarItem_referencia(men1, men2) {

        var nombreReferencia        = $("#nombre_referencia").val();
        var Profesion               = $("#selector11").val();
        var ProfesionReferencia     = $("#codigo_dane_profesion_referencia").val();
        var direccionReferencia     = $("#direccion_referencia").val();
        var telefonoReferencia      = $("#telefono_referencia").val();

        var lista_referencia        = parseInt($('#lista_referencia').val());
                        
        var valorClase = '';
        if ($("#listaItemsReferencia tr:last").hasClass("even")) {
                valorClase = 'odd';
        } else {
                valorClase = 'even';
        }

       if (nombreReferencia && Profesion) {

            var idProfesion = ProfesionReferencia;
            var destino = $('#URLFormulario').val();

            $.getJSON(destino, {validarEnTablas: true, tabla : 'seleccion_profesiones', campo : 'id', valor : idProfesion}, function(dato){
                if(dato){                    
                    var res1 = dato;                    
                    if (res1==1){            
                        var boton = $('#botonRemoverReferencia').html();
                        var item  = '<tr id="fila_'+lista_referencia+'" class="'+valorClase+'">'+
                                '<td align="center">'+
                                    '<input type="hidden" class="idPosicionTablaReferencia" name="idPosicionTablaReferencia['+lista_referencia+']" value="'+lista_referencia+'">'+
                                    '<input type="hidden" class="nombre_referencia_Tabla" name="nombre_referencia_Tabla['+lista_referencia+']" value="'+nombreReferencia+'">'+
                                    '<input type="hidden" class="profesion_referencia_Tabla" name="profesion_referencia_Tabla['+lista_referencia+']" value="'+ProfesionReferencia+'">'+
                                    '<input type="hidden" class="direccion_referencia_Tabla" name="direccion_referencia_Tabla['+lista_referencia+']" value="'+direccionReferencia+'">'+
                                    '<input type="hidden" class="telefono_referencia_Tabla" name="telefono_referencia_Tabla['+lista_referencia+']" value="'+telefonoReferencia+'">'+
                                    boton+
                                '</td>'+
                                '<td align="left">'+nombreReferencia+'</td>'+
                                '<td align="left">'+Profesion+'</td>'+
                                '<td align="left">'+direccionReferencia+'</td>'+
                                '<td align="left">'+telefonoReferencia+'</td>'+
                                '</tr>';
                        $('#listaItemsReferencia').append(item);
                        $("#nombre_referencia").val('');
                        $("#codigo_dane_profesion_referencia").val('');
                        $("#selector11").val('');
                        $("#direccion_referencia").val('');
                        $("#telefono_referencia").val('');
                        lista_referencia++;
                        $("#lista_referencia").val(lista_referencia);
                    }else{
                        $("#telefono_referencia").parent().children('#errorDialogo').remove();
                        $("#telefono_referencia").focus();
                        $("#telefono_referencia").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men2+'</span>');
                        $("#telefono_referencia").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
                    }
                }
            });
        }else{
            $("#telefono_referencia").parent().children('#errorDialogo').remove();
            $("#telefono_referencia").focus();
            $("#telefono_referencia").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">'+men1+'</span>');
            $("#telefono_referencia").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }        
    }

    function agregarItem_aficion() {

        var lista_aficiones     = parseInt($('#lista_aficiones').val());
        var codigo_aficion      = $('#codigo_aficion').val();
        var descripcion_aficion = $('#codigo_aficion :selected').text();
        var existe=false;

        $('#listaItemsAficion').find('.codigoAficion').each(function () {
            id = $(this).val();
            if(codigo_aficion==id){
                existe=true;
            }
        });

        if(!existe){                
            var valorClase = '';
            if ($("#listaItemsAficion tr:last").hasClass("even")) {
                    valorClase = 'odd';
            } else {
                    valorClase = 'even';
            }

           
                var boton = $('#botonRemoverAficion').html();
                var item  = '<tr id="fila_'+lista_aficiones+'" class="'+valorClase+'">'+
                        '<td align="center">'+
                            '<input type="hidden" class="idPosicionTablaAficiones" name="idPosicionTablaAficiones['+lista_aficiones+']" value="'+lista_aficiones+'">'+
                            '<input type="hidden" class="codigoAficion" name="codigoAficion['+lista_aficiones+']" value="'+codigo_aficion+'">'+
                            '<input type="hidden" class="descripcionAficion" name="descripcionAficion['+lista_aficiones+']" value="'+descripcion_aficion+'">'+
                            boton+
                        '</td>'+
                        '<td align="left">'+descripcion_aficion+'</td>'+
                        '</tr>';
                $('#listaItemsAficion').append(item);
                $('#codigo_aficion').val('');
                lista_aficiones++;
                $("#lista_aficiones").val(lista_aficiones);
        }else{
            $("#codigo_deporte").parent().children('#errorDialogo').remove();
            $("#codigo_deporte").focus();
            $("#codigo_deporte").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">Error, la aficion ya existe en la tabla</span>');
            $("#codigo_deporte").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }
    }

    function agregarItem_deporte() {

        var lista_deportes      = parseInt($('#lista_deportes').val());
        var codigo_deporte      = $('#codigo_deporte').val();
        var descripcion_deporte = $('#codigo_deporte :selected').text();
        var existe=false;

        $('#listaItemsDeporte').find('.codigoDeporte').each(function () {
            id = $(this).val();
            if(codigo_deporte==id){
                existe=true;
            }
        });

        if(!existe){                        
            var valorClase = '';
            if ($("#listaItemsDeporte tr:last").hasClass("even")) {
                    valorClase = 'odd';
            } else {
                    valorClase = 'even';
            }

           
                var boton = $('#botonRemoverDeporte').html();
                var item  = '<tr id="fila_'+lista_deportes+'" class="'+valorClase+'">'+
                        '<td align="center">'+
                            '<input type="hidden" class="idPosicionTablaDeportes" name="idPosicionTablaDeportes['+lista_deportes+']" value="'+lista_deportes+'">'+
                            '<input type="hidden" class="codigoDeporte" name="codigoDeporte['+lista_deportes+']" value="'+codigo_deporte+'">'+
                            '<input type="hidden" class="descripcionDeporte" name="descripcionDeporte['+lista_deportes+']" value="'+descripcion_deporte+'">'+
                            boton+
                        '</td>'+
                        '<td align="left">'+descripcion_deporte+'</td>'+
                        '</tr>';
                $('#listaItemsDeporte').append(item);
                $('#codigo_deporte').val('');
                lista_deportes++;
                $("#lista_deportes").val(lista_deportes);
        }else{
            $("#codigo_deporte").parent().children('#errorDialogo').remove();
            $("#codigo_deporte").focus();
            $("#codigo_deporte").parent().append('<span id="errorDialogo" class="mensajeErrorLinea">Error, el deporte ya existe en la tabla</span>');
            $("#codigo_deporte").parent().children('.mensajeErrorLinea').fadeOut(5000).addClass('mensajeError').css('display','block');
        }
    }

    function removerItems(boton) {        
        $(boton).parents('tr').remove();        
    }


    function manejoLibreta(){
        if($('#clase_libreta_militar').val()==1){
            $('#libreta_militar').attr("disabled","disabled");
            $('#distrito_militar').attr("disabled","disabled");
            $('#libreta_militar').val('');
            $('#distrito_militar').val('');
            
        }else{
            $('#libreta_militar').removeAttr('disabled');
            $('#distrito_militar').removeAttr('disabled');
        }
    }

    function manejoPase(){
        if($('#categoria_permiso_conducir').val()==1){
            $('#permiso_conducir').attr("disabled","disabled");
            $('#permiso_conducir').val('');
            
        }else{
            $('#permiso_conducir').removeAttr('disabled');
        }
    }

    function manejoDerecho(){
        if($('#derecho_sobre_vivienda').val()>1){
            $('#canon_arrendo').attr("disabled","disabled");
            $('#fecha_inicio_vivienda').attr("disabled","disabled");
            $('#nombre_arrendatario').attr("disabled","disabled");
            $('#selector4').attr("disabled","disabled");
            $('#telefono_arrendatario').attr("disabled","disabled");
            $('#canon_arrendo').val('');
            $('#nombre_arrendatario').val('');
            $('#selector4').val('');
            $('#codigo_dane_municipio_arrendatario').val('');
            $('#telefono_arrendatario').val('');
            
        }else{
            $('#canon_arrendo').removeAttr('disabled');
            $('#fecha_inicio_vivienda').removeAttr('disabled');
            $('#selector4').removeAttr('disabled');
            $('#telefono_arrendatario').removeAttr('disabled');
            $('#nombre_arrendatario').removeAttr('disabled');
        }
    }

    function manejoEstadoCivil(){
        if($('#estado_civil').val()==1 || $('#estado_civil').val()==4 || $('#estado_civil').val()==5){
            $('#codigo_tipo_documento_conyugue').attr("disabled","disabled");
            $('#documento_identidad_conyugue').attr("disabled","disabled");
            $('#primer_nombre_conyugue').attr("disabled","disabled");
            $('#segundo_nombre_conyugue').attr("disabled","disabled");
            $('#primer_apellido_conyugue').attr("disabled","disabled");
            $('#segundo_apellido_conyugue').attr("disabled","disabled");
            $('#selector9').attr("disabled","disabled");
            $('#empresa_conyugue').attr("disabled","disabled");
            $('#codigo_cargo_conyugue').attr("disabled","disabled");
            $('#telefono_conyugue').attr("disabled","disabled");
            $('#celular_conyugue').attr("disabled","disabled");
            $('#codigo_tipo_documento_conyugue').val('');
            $('#documento_identidad_conyugue').val('');
            $('#primer_nombre_conyugue').val('');
            $('#segundo_nombre_conyugue').val('');
            $('#primer_apellido_conyugue').val('');
            $('#segundo_apellido_conyugue').val('');
            $('#selector9').val('');
            $('#codigo_dane_profesion_conyugue').val('');
            $('#empresa_conyugue').val('');
            $('#codigo_cargo_conyugue').val('');
            $('#telefono_conyugue').val('');            
            $('#celular_conyugue').val('');
            
        }else{
            $('#codigo_tipo_documento_conyugue').removeAttr('disabled');
            $('#documento_identidad_conyugue').removeAttr('disabled');
            $('#primer_nombre_conyugue').removeAttr('disabled');
            $('#segundo_nombre_conyugue').removeAttr('disabled');
            $('#primer_apellido_conyugue').removeAttr('disabled');
            $('#segundo_apellido_conyugue').removeAttr('disabled');
            $('#selector9').removeAttr('disabled');
            $('#empresa_conyugue').removeAttr('disabled');
            $('#codigo_cargo_conyugue').removeAttr('disabled');
            $('#telefono_conyugue').removeAttr('disabled');
            $('#celular_conyugue').removeAttr('disabled');
        }
    }

    function manejoPension(){
        if($('#no_pensionado').attr('checked')){
            $('#ingreso_pension').attr("disabled","disabled");
            $('#ingreso_pension').val('');
            
        }else{
            $('#ingreso_pension').removeAttr('disabled');
        }
    }
