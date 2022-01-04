<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Modelo\Conexion;
use App\Modelo\Consulta;
use App\Modelo\Funciones;
use App\Modelo\Tabla;
use App\Modelo\LogTable;

class GuardarInformacion extends Command
{
    protected $signature = 'integracion:guardar-informacion';
    protected $description = 'Obtener información y generar guardado en base de datos';

    public function __construct(){ parent::__construct(); }

    public function handle(){
        $dataConexion = Conexion::where('estado',1)->first(); $listaConsulta = Consulta::where('estado',1)->get();
        foreach ($listaConsulta as $value) {
            $sentencia = Funciones::ParametroSentencia($value,$dataConexion); 
            $xml = Funciones::consultaStructuraXML($dataConexion->conexion,$dataConexion->cia,$dataConexion->proveedor,$dataConexion->usuario,$dataConexion->clave,$sentencia,$dataConexion->consulta,1,0);
            $resultado = Funciones::SOAP_SAVE($dataConexion->url, $xml, $value->tabla_destino);
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($value->tabla_destino); 
            if ($value->truncate == 1) { $consTabla->truncate(); }
            if (is_array($resultado)) { $consTabla->insert($resultado); }
        }
    }
}