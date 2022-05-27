<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Modelo\Conexion;
use App\Modelo\Consulta;
use App\Modelo\Plano;
use App\Modelo\Funciones;
use App\Modelo\Tabla;
use App\Modelo\Formato;
use App\Modelo\PlanoFuncion;
use App\Modelo\CampoQuemado;
use App\Modelo\LogTable;
use App\Mail\Enviar;
use App\Modelo\Correo;
use Mail;
use DB;

class LocalController extends Controller{

    public function consulta(Request $request){
    	return view('welcome'); 
    }

}
    