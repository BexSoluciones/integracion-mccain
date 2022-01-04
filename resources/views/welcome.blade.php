@extends('layout.general')
@section('content')

    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        <div class="content" style="overflow-x: hidden;">

            <div class="row">
                <div class="col-2">
                    <img height="90px" src="{{ url("/public/assets/img/bex.png") }}" class="mt-2 pt-3">
                </div>
                <div class="col-8">
                    <h3 class="titus mt-3 ml-3 text-left mb-3">{{config('app.name')}}</h3>
                    <div id="breadcrumb" class="container pb-4">
                        <nav aria-label="breadcrumb">
                          <ol class="breadcrumb">
                            <li id="bread-a" class="breadcrumb-item active" aria-current="page">{{config('app.titu')}}</li>
                          </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>

            {{-- {{Artisan::call('integracion:generar-planos')}} --}}
            <style type="text/css">
                .cont_tables{overflow-y: scroll;max-height: 400px;}
            </style>

            <div id="conttbl" class="container mb-5">
                <div class="row">
                    <div class="col-2 cont_tables">
                        <a href="{{url("/")}}" id="tbl_conexion" class="btn btn-info w-100 mt-2 button-control">Documentación</a>
                        <button type="button" id="tbl_conexion" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_conexion','.contReqError')">1 - Tabla Conexión</button>
                        <button type="button" id="tbl_plano" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_plano','.contReqError')">2 - Tabla Plano</button>
                        <button type="button" id="tbl_consulta" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_consulta','.contReqError')">3 - Tabla Consulta</button>
                        <button type="button" id="tbl_formato" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_formato','.contReqError')">4 - Tabla Formato</button>
                        <button type="button" id="tbl_plano_funcion" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_plano_funcion','.contReqError')">5 - Tabla Plano Función</button>
                        <button type="button" id="tbl_quemado" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_quemado','.contReqError')">6 - Tabla Quemados</button>
                        <button type="button" id="tbl_correo" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_correo','.contReqError')">7 - Tabla Correos</button>
                        <button type="button" id="tbl_comando" class="btn btn-secondary w-100 mt-2 button-control" onclick="requestView('tbl_comando','.contReqError')">8 - Tabla Comandos</button>
                        <button type="button" id="tbl_log" class="btn btn-danger w-100 mt-2 button-control" onclick="requestView('tbl_log','.contReqError')">9 - Tabla Log</button>
                    </div>
                    <div class="col-10 cont_tables">
                        <div class="bg-dark p-2 rounded text-white" style="overflow-x: scroll;">
                            <h4 id="titu_req"></h4>
                            <div class="contReqError"></div>
                            <div id="spinnerLoaderReq" class="w-100 text-center mt-3" style="display: none;">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                <p class="text-white">Por favor espere un momento...</p>
                            </div>
                            <div id="cont_req"><div class="row">@include('readme')</div></div>
                        </div> 
                    </div>

                </div>    
            </div>
            

            <div id="navRequerimiento" class="links display-none">
                <!--<a href="#" onclick="consulta('CLIENTES')" class="btn btn-secondary p-2 text-white">>Actualizar Lista Clientes</a>
                <a href="#" onclick="consulta('RUTEROS')" class="btn btn-secondary p-2 text-white">>Actualizar Lista Ruteros</a>-->
                <a href="#" onclick="consulta('FACTURAS')" class="btn btn-secondary p-2 text-white">>Actualizar Lista Facturas</a>
            </div>

            <div id="spinnerLoader" class="w-100 text-center display-none mt-3">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                <p class="text-dark">Enviando requerimiento, esto puede demorar unos minutos espere por favor...</p>
            </div>

            <div class="error bg-light rounded mt-4 p-1 text-left pl-4 display-none">
                <label class="mt-2 text-danger">RESPUESTA REQUERIMIENTO:</label>
                <div id="err_list"></div>
            </div>

        </div>
    </div>

@endsection