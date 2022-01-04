var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

$( document ).ready(function() {
    cargarLog();
});

function limpiarLog(contLogError){
    $("#spinnerLoaderLog").fadeIn(0); $('#log-container').fadeOut(0); timeOut(contLogError);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: './limpiar_log', 
        type: 'GET',        
        success: function(response){
            $("#spinnerLoaderLog").fadeOut(0); $('#log-container').fadeIn(0); cargarLog(); $(contLogError).html(response);
            timeOut(contLogError);
        },
        error: function(data){
            $(contLogError).html(data);
        }
    });
}

function desplegarLog(rutaLog){
    window.open(rutaLog, '_blank').focus();
}

function cargarLog(contLogError){
    $("#spinnerLoaderLog").fadeIn(0); $('#log-container').fadeOut(0); timeOut(contLogError);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url : "storage/logs/laravel.log",
        dataType: "text",
        success : function (data) {
            $("#spinnerLoaderLog").fadeOut(0); $('#log-container').fadeIn(0); $('#log-container').html('');
            $("#log-container").html(data); timeOut(contLogError);
        },
        error: function(data){
            $(contLogError).html(data);
        }
    });
}

function timeOut(objeto){
    setTimeout(function(){ $(objeto).fadeOut(0); }, 6000);
}

function ejecutarComando(idComand,idContError){
    var command = $(idComand).val();
    $("#spinnerLoaderReq").fadeIn(0); $('#cont_req').fadeOut(0); $(idContError).html('');
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: './ejecutar_comando', 
        data: { _token: CSRF_TOKEN, comando:command },
        type: 'GET',        
        success: function(response){
            $("#spinnerLoaderReq").fadeOut(0); $('#cont_req').fadeIn(0); $(idContError).html(response); timeOut(idContError);
        },
        error: function(data){
            $(idContError).html(data);
        }
    });
}

function registrarNuevo(tabla,claseCampo,contError){
    var dataCampo = new Array();
    
    $(claseCampo).each(function(){ 
        dataCampo.push($(this).val()); 
    });

    console.log(dataCampo);

    $("#spinnerLoaderReq").fadeIn(0); $('#cont_req').fadeOut(0); timeOut(contError);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: './registrar_dato', 
        data: { _token: CSRF_TOKEN, datArray:dataCampo, tabla:tabla },
        type: 'GET',        
        success: function(response){
            console.log(response);
            $("#spinnerLoaderReq").fadeOut(0); 
            if (response[0] == true) {
                requestView(tabla,'.contReqError');
            }else{
                $('#cont_req').fadeIn(0); $(contError).html(response[1]);
            }
        },
        error: function(data){
            $(contError).html(data);
        }
    });
}

function actualizarReg(tabla,codigo,tablaCampos,contError){
    var dataCampo = new Array();
    var claseCampo = tablaCampos+codigo;
    $(claseCampo).each(function(){ 
        dataCampo.push($(this).val()); 
    });

    console.log(dataCampo);

    $("#spinnerLoaderReq").fadeIn(0); $('#cont_req').fadeOut(0); timeOut(contError);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: './actualizar_dato', 
        data: { _token: CSRF_TOKEN, datArray:dataCampo, codigo:codigo, tabla:tabla },
        type: 'GET',        
        success: function(response){
            console.log(response);
            $("#spinnerLoaderReq").fadeOut(0); 
            if (response[0] == true) {
                requestView(tabla,'.contReqError');
            }else{
                $('#cont_req').fadeIn(0); $(contError).html(response[1]);
            }
        },
        error: function(data){
            $(contError).html(data);
        }
    });
}

function eliminarReg(tabla,codigo,contError){

    $("#spinnerLoaderReq").fadeIn(0); $('#cont_req').fadeOut(0); timeOut(contError);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: './eliminar_dato', 
        data: { _token: CSRF_TOKEN, codigo:codigo, tabla:tabla },
        type: 'GET',        
        success: function(response){
            console.log(response);
            $("#spinnerLoaderReq").fadeOut(0); 
            if (response[0] == true) {
                requestView(tabla,'.contReqError');
            }else{
                $('#cont_req').fadeIn(0); $(contError).html(response[1]);
            }
        },
        error: function(data){
            $(contError).html(data);
        }
    });
}

function requestView(view,contLogError){
    var idButt = "#"+view;
    $("#spinnerLoaderReq").fadeIn(0); $('#cont_req').fadeOut(0); timeOut(contLogError);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: './request_view', 
        data: { _token: CSRF_TOKEN, view:view },
        type: 'GET',        
        success: function(response){
            $(idButt).removeClass('btn-secondary'); $('.button-control').removeClass('btn-success'); $('.button-control').addClass('btn-secondary'); $(idButt).addClass('btn-success');
            $("#spinnerLoaderReq").fadeOut(0); $('#cont_req').fadeIn(0); $('#cont_req').html(response);
        },
        error: function(data){
            $(contLogError).html(data);
        }
    });
}

function consulta(tipo_consulta){
    $("#spinnerLoader").fadeIn(0); $(".links").fadeOut(); $("#breadcrumb").fadeOut();
    $.ajax({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: './consulta_webservice_empresa', 
        data: { _token: CSRF_TOKEN, tipo_consulta:tipo_consulta },
        type: 'GET',        
        success: function(response){
            $("#spinnerLoader").fadeOut(0); $(".links").fadeIn(); $("#breadcrumb").fadeIn();
            $(".error").fadeIn(300);
            $("#err_list").html('<p class="pl-2">>'+response+'</p>');
        },
        error: function(data)
        {
            $("#err_list").html('<p class="pl-2">>'+data+'</p>');
        }
    });
}