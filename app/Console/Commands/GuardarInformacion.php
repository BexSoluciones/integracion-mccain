<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Modelo\Conexion;
use App\Modelo\Consulta;
use App\Modelo\ConsultaCondicion;
use App\Modelo\Funciones;
use App\Modelo\Tabla;
use App\Modelo\LogTable;

class GuardarInformacion extends Command
{
    protected $signature = 'integracion:guardar-informacion';
    protected $description = 'Obtener información y generar guardado en base de datos';

    public function __construct(){ parent::__construct(); }

    public function handle(){

        $dataConexion = Conexion::where('estado',1)->first(); $listaConsulta = Consulta::where('estado',1)->orderBy('codigo','asc')->get(); $resultado = array(); LogTable::truncate(); 
        foreach ($listaConsulta as $value) {
            echo "<br>##################### $value->tabla_destino ####################################<br>";
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($value->tabla_destino); 
            if ($value->truncate == '1') { $consTabla->truncate(); 
                Consulta::where('codigo',$value->codigo)->where('consecutivo','>',0)->update(['consecutivo' => 1]); 
                Consulta::where('codigo',$value->codigo)->where('consecutivo_b','>',0)->update(['consecutivo_b' => 1]); 
            }
            $stopWhile = 1; $busqueda_alterna = false;
            
            $dataTableReg = $consTabla->get();

            do{
                if ($busqueda_alterna == true) { echo "<br> EMPEZANDO BUSQUEDA ALTERNA <br>"; }
                $sentencia = Funciones::ParametroSentencia($value,$dataConexion,false,$busqueda_alterna);
                $xml = Funciones::consultaStructuraXML($dataConexion->conexion,$dataConexion->cia,$dataConexion->proveedor,$dataConexion->usuario,$dataConexion->clave,$sentencia,$dataConexion->consulta,1,0);
                $datos = Funciones::SOAP_SAVE($dataConexion->url, $xml, $value->tabla_destino);
                $resultado = Funciones::SOAP($dataConexion->url, $xml, $value->tabla_destino);
                
                if (is_array($resultado) && is_array($datos)) { 
                    echo "<br>================= $value->tabla_destino ================================<br>"; 
                    //print_r($datos); // print_r($resultado);               

                    $condicionRegistro = ConsultaCondicion::where('id_consulta',$value->codigo)->get();  
                    foreach ($resultado as $resKey => $valres) {
                        echo "<br>".print_r($valres)."<br>";
                        $reselect = null; $regCond = true;

                        if (count($condicionRegistro) > 0) {
                            
                            echo "TABLA REGISTRO: ".count($dataTableReg);
                            // print_r($dataTableReg);
                            echo "<br>";

                            $arrayCondB = explode(',', $condicionRegistro[0]['condicion']); $totalCondRay = count($arrayCondB);
                            echo "------------------------------------------------------------------------------------------------<br>";
                            echo "CONDICIONES: ".$totalCondRay;
                            echo "<br>------------------------------------------------------------------------------------------------<br>";

                            foreach ($dataTableReg as $valueCond) {

                                $arrayCondicion = array(); 
                                foreach ($arrayCondB as $keyArrB => $valueArrB) {
                                    $dataCond = trim($valueCond[$valueArrB]); 
                                    array_push($arrayCondicion, $dataCond);
                                }

                                $suCond = 0;
                                foreach ($arrayCondicion as $keyCond => $valuKyCond) {
                                    echo "<br>________________________________________________________________________________________________<br>";
                                    $valuKyCond = trim($valuKyCond);
                                    if ($valuKyCond == 'NO') {
                                        $clave = array_search("NO", $valres); if ($clave != '') { $suCond++; }
                                    }else{
                                        $clave = array_search("'".$valuKyCond."'", $valres); if ($clave != '') { $suCond++; }
                                    }
                                    // print_r($valres);
                                    // $resVal = Funciones::TrimArray($valres);
                                    // print_r($resVal);
                                    
                                }

                                if ($totalCondRay == $suCond) { $regCond = false; }
                                
                                echo "<br>________________________________________________________________________________________________<br>";
                                echo "DATA FILA:"; print_r($arrayCondicion); echo " TIENE [".$suCond."] INTERSECCIONES";
                                echo "<br>________________________________________________________________________________________________<br>";

                            }                            

                        }else{ 
                            echo "NO TIENE CONDICIÓN <br>"; 
                        }
 
                        echo "<br><br>";

                        $keyDat = $resKey+1;

                        if ($regCond == true) { 
                            echo "DATA REGISTRADO";
                            if (count($datos) > 0) { $consTabla->insert($datos[$keyDat]); }                            
                        }else{ unset($datos[$keyDat]); }

                        $endArray = end($resultado);
                        if ($value->consecutivo > 0) {
                            if (isset($endArray[$value->campo_consecutivo])) {
                                $valRay = str_replace("'", "", $endArray[$value->campo_consecutivo]);
                                Consulta::where('codigo',$value->codigo)->where('estado',1)->update(['consecutivo' => $valRay]);
                            }                    
                        }
                        if ($value->consecutivo_b > 0) {
                            if (isset($endArray[$value->campo_consecutivo_b])) {
                                $valRayB = str_replace("'", "", $endArray[$value->campo_consecutivo_b]);
                                Consulta::where('codigo',$value->codigo)->where('estado',1)->update(['consecutivo_b' => $valRayB]);
                            }
                        }

                    }

                    // print_r($datos);
                    $stopWhile = 0;

                }else{

                    // print_r($resultado);
                    // print_r($datos);

                    if ($busqueda_alterna == true) {                        
                        // if ($ultimoLog['descripcion'] == '') {
                        //     # code...
                        // }
                        $stopWhile = 0; 
                    }else{ 
                        $busqueda_alterna = true; 
                    }
                }

            }while($stopWhile != 0);
            
        }
    }  

}