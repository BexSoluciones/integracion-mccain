<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Modelo\Conexion;
use App\Modelo\Consulta;
use App\Modelo\ConsultaConsecutivo;
use App\Modelo\Funciones;
use App\Modelo\Tabla;
use App\Modelo\LogTable;
use DB;

class VerificarTipoDocumento extends Command
{
    protected $signature = 'integracion:verificar-tipo-documento';
    protected $description = 'Verifica y actualiza la lista de tipos de documentos';

    public function __construct(){ parent::__construct(); }

    public function handle(){

        $dataConexion = Conexion::where('estado',1)->first(); 
        
        $listaConsultaTipoTablas = Consulta::where('estado',3)->get(); 
        $listaConsultaTablas = Consulta::where('estado',1)->get(); 
        
        $consultaConsecutivo = ConsultaConsecutivo::all(); $busqueda_alterna = false;

        if (count($listaConsultaTipoTablas) > 0) {
            foreach ($listaConsultaTipoTablas as $value) {

                // VERIFICA SI TRUNCATE ESTA ACTIVADO EN LA CONFIG DE CONSULTA
                if ($value->truncate == '1') { 
                    $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($value->tabla_destino); 
                    $consTabla->truncate();  // BORRA REGISTROS PREVIOS DE TABLA CONSULTADA
                }

                $sentencia = Funciones::ParametroSentencia($value,$dataConexion,false,$busqueda_alterna,null);
                $xml = Funciones::consultaStructuraXML($dataConexion->conexion,$dataConexion->cia,$dataConexion->proveedor,$dataConexion->usuario,$dataConexion->clave,$sentencia,$dataConexion->consulta,1,0);
                $resultado = Funciones::SOAP($dataConexion->url, $xml, $value->tabla_destino);

                if (is_array($resultado)) { 

                    echo "<br>================= $value->tabla_destino ================================<br>\n"; 

                    foreach ($listaConsultaTablas as $tablaConsultada) {
                        $consecutivosTabla = ConsultaConsecutivo::where('consulta',$tablaConsultada->codigo)->get();                        

                        foreach ($resultado as $resKey => $valres) {
                            foreach ($valres as $key => $valuer) {
                                $valRes = str_replace("'", "", $valuer);
                                //RECORRE LISTA DE CONSECUTIVOS POR CONSULTA TABLA 
                                $encontradoValuer = false; 
                                if (count($consecutivosTabla) > 0) {
                                    foreach ($consecutivosTabla as $valueConsecutivo) { if ($valRes == $valueConsecutivo->tipo_documento) { $encontradoValuer = true; } }
                                }                            

                                if ($encontradoValuer == false) {
                                    if (ConsultaConsecutivo::insert(['consulta' => $tablaConsultada->codigo,'tipo_documento' => $valRes,'consecutivo' => $tablaConsultada->consecutivo, 'campo_consecutivo' => $tablaConsultada->campo_consecutivo,'consecutivo_b' => $tablaConsultada->consecutivo_b,'campo_consecutivo_b' => $tablaConsultada->campo_consecutivo_b])) {
                                        echo "Nuevo registro de tipo = $valRes , consecutivo 1 , tabla = $tablaConsultada->tabla_destino \n";
                                    }else{
                                        echo "Hubo un error al registrar \n";
                                    }
                                }else{
                                    echo "El tipo documento = $valRes , ya se encuentra registrado en la consulta tabla = $tablaConsultada->tabla_destino \n";
                                }

                            }
                        }

                    }

                    
                }

            }
        }
    }

        
}