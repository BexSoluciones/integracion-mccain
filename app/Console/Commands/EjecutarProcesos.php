<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Modelo\Tabla;
use App\Modelo\Funciones;

class EjecutarProcesos extends Command
{
    protected $signature = 'integracion:ejecutar-proceso';
    protected $description = 'Consulta & verifica existencias de productos en bodegas con y sin WMS';

    public function __construct(){ parent::__construct(); }

    public function handle(){

        echo "=====> [ EJECUTANDO PROCESO COLTABACO ] <=====";

        $fechaInicio = Funciones::fechaConsulta("inicio");
        $fechaFin = Funciones::fechaConsulta("fin");

        // dd($fechaFin);

        $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind("tbl_consulta"); 
        
        if ($consTabla->where('codigo','>',0)->update(['fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin])) {
            echo "FECHAS CONSULTAS ACTUALIZADAS: INICIO[".$fechaInicio."] / FIN[".$fechaFin."] \n";
        }

        Artisan::call('integracion:verificar-tipo-documento'); // CONSULTA INVENTARIO EN BODEGAS DE LOS PRODUCTOS DE PEDIDOS APROBADOS 

        Artisan::call('integracion:guardar-informacion'); // VERIFICA INVENTARIO MINIMO EN BODEGAS PARA REMISIONAR, MOVER INVENTARIO

        Artisan::call('integracion:generar-planos'); // VERIFICA INVENTARIO MINIMO EN BODEGAS PARA REMISIONAR, MOVER INVENTARIO
        
        Artisan::call('integracion:enviar-planos'); // VERIFICA INVENTARIO MINIMO EN BODEGAS PARA REMISIONAR, MOVER INVENTARIO

    }  

}