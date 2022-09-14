<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Modelo\Consulta;
use App\Modelo\Plano;
use App\Modelo\Funciones;
use App\Modelo\Tabla;
use App\Modelo\Formato;
use App\Modelo\PlanoFuncion;
use App\Modelo\CampoQuemado;

class GenerarPlanos extends Command
{
    protected $signature = 'integracion:generar-planos';
    protected $description = 'Generar planos de tablas registradas';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $listaConsulta = Consulta::where('estado',1)->get();
        foreach ($listaConsulta as $value) {
            
            $consPlano = Plano::where('codigo',$value->id_plano)->first();
            $consPlanoFuncion = PlanoFuncion::where('id_consulta',$value->codigo)->get();
            $consCampoQuemado = CampoQuemado::where('id_consulta',$value->codigo)->get();
            $consFormato = Formato::where('id_consulta',$value->codigo)->first();
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($value->tabla_destino); 

            if ($value->group_by == '') {
                $resCons = $consTabla->where('planoRegistro',0)->orderBy($value->orderBy,$value->orderType)->get();
            }else{
                $resCons = $consTabla->where('planoRegistro',0)->groupBy($value->group_by)->orderBy($value->orderBy,$value->orderType)->get();
            }

            $totalSuc = array('00210'); 
            // $totalSuc = array('00210','00211'); 
            // $totalSuc = array('00210','00211','00212'); 
            $dataPlan = null; $name_us = null; $sumR = 0;

            foreach ($totalSuc as $planSuc) {

                echo "=> Tabla: ".$value->tabla_destino." | Sucursal: ".$planSuc." \n";

                foreach ($resCons as $keya => $valueA) {                

                    // echo "===> DATA: $sumR  \n";

                    $suma = 0; $array = explode(",", $valueA); $totalExpl = count($array) - 2;
                    if ($consPlano['display_codigo'] == 0) { $sum = 1; }else{ $sum = 2; }    

                    // dd($totalExpl);

                    if (count($array) > 0) {
                        foreach ($array as $keyb => $valueB) {                  
                            if ($sum != 1) {

				$valueB = Funciones::caracterEspecial($valueB);
                                if ($value->tabla_destino == "tbl_ws_total_facturado") {
                                    $valueB = str_replace('--','/',$valueB);
                                    $valueB = Funciones::caracterEspecialSimbolB($valueB);
                                }else{
                                    $valueB = Funciones::caracterEspecialSimbol($valueB);
                                }


                                $campoDpl = true; $pos = strpos($valueB, ':'); $pos++;
                                $valueB = substr($valueB, $pos); $valueB = Funciones::ReplaceText($valueB);
                    
                                $tipo = explode(",", $consFormato['tipo']); 
                                $longitud = explode(",", $consFormato['longitud']); 
                                
                                // echo "VALUE FOREACH:".$valueB." | ".$suma." | "."\n";
                                // echo "STATE A: $campoDpl <br>";

                                if ($valueB == 'NO') { $valueB = ''; }

                                // echo "TOTAL: ".(count($tipo) - 2)." / SUMA: $sum / VAL: $valueB \n";
                                // echo "SUMA: $sum \n";

                                if (count($tipo) == $sum || count($tipo) < $sum) { 
                                    // echo "ESPACIADO NULL \n"; 
                                    $separadorPlan = ""; 
                                }else{ 
                                    // echo "SEPARADO ; \n"; 
                                    $separadorPlan = $consPlano['separador']; 
                                }                           

                                // CAMPOS CON FUNCIONES ESPECIFICAS
                                foreach ($consPlanoFuncion as $planoFuncion) {
                                    if ($planoFuncion->posicion == $suma) {                                
                                        if($planoFuncion->tipo == 'name_us'){
                                            $name_us = $valueB;
                                        }elseif($planoFuncion->tipo == 'buscar_codigo'){ 
                                            $campoDpl = false; 
                                            $tablaBuscar = Consulta::where('codigo',$planoFuncion->consulta)->first();
                                            $buscarTabla = new Tabla; $buscarTabla->getTable(); $buscarTabla->bind($tablaBuscar['tabla_destino']); $resBusc = $buscarTabla->where($planoFuncion->nombre,$valueB)->first();     
                                            if ($planoFuncion->tipo == 'texto') {
                                                $dataResplan = substr($resBusc['codigo'], 0, $planoFuncion->longitud);
                                                $dataPlan .= " ".$consPlano['entre_columna'].str_pad($dataResplan, $planoFuncion->longitud).$consPlano['entre_columna'].$separadorPlan;
                                            }else{ $dataPlan = str_replace(" ", "", $resBusc['codigo']);  $dataPlan .= $resBusc['codigo'].$separadorPlan; }
                                        }else{
                                            $dataPlan .= Funciones::condicionPlano($planoFuncion,$valueB,$name_us,$consPlano);
                                            if ($dataPlan != false) { $campoDpl = false; }
                                        }
                                    }
                                }

                                // echo "STATE B: $campoDpl <br>";

                                // CAMPOS QUEMADOS
                                if ($campoDpl == true) {
                                    foreach ($consCampoQuemado as $campoQuemado) {
                                        if ($campoQuemado->posicion == $suma) {
                                            $campoDpl = false; // echo "$campoQuemado";
                                            if ($campoQuemado->tipo == 'texto') {
                                                $dataResplan = substr($campoQuemado->valor, 0, $campoQuemado->longitud);
                                                $dataPlan .= " ".$consPlano['entre_columna'].str_pad($dataResplan, $campoQuemado->longitud).$consPlano['entre_columna'].$separadorPlan;
                                            }else{ $campoQuemado->valor = str_replace(" ", "", $campoQuemado->valor);  $dataPlan .= $campoQuemado->valor.$separadorPlan; }
                                        }
                                    }
                                }

                                // echo "STATE C: $campoDpl <br>";

                                if ($campoDpl == true) {
                                    // CAMPOS CONSULTA TABLA
                                    if ($suma >= count($tipo)) {
                                        // echo "ALERTA: => LA CANTIDAD DE CAMPOS EN LA POSICION `$sum` DE LA TABLA `tbl_formato` SOBREPASA, NO CONCUERDA CON LA CANTIDAD DE CAMPOS QUE CONTIENE LA TABLA: `$value->tabla_destino` ES `".count($tipo)."` <br>";
                                    }else{
                                        $tipoR = Funciones::ReplaceText($tipo[$suma]);  
                                        $longitudR = Funciones::ReplaceText($longitud[$suma]);
                                        
                                        if ($tipoR == 'texto') {
                                            $dataResplan = substr($valueB, 0, $longitudR);
                                            $dataPlan .= "".$consPlano['entre_columna'].str_pad($dataResplan, 0).$consPlano['entre_columna'].$separadorPlan;
                                        }else{ $valueB = str_replace(" ", "", $valueB);  $dataPlan .= $valueB.$separadorPlan; }

                                    }   
                                }
                                                
                                
                            } $sum++; $suma++;
                        }
                        // echo "<br>";
                        // echo "PLANO ANT FUNCTION: $dataPlan \n";
                        // echo "\n";

                        if ($consPlano['salto_linea'] == 1) { $dataPlan .= "\n"; }
                    }   $sumR++;

                }

                if ($dataPlan != null) {
                    // dd($planSuc);
                    $nombreFile = Funciones::NombreArchivo($consPlano); 
                    $subsName = substr($nombreFile, 0,5);

                    if ($subsName != $planSuc) {
                        if ($planSuc == "00211") {
                            $nombreFile = str_replace($subsName, $planSuc, $nombreFile);
                            echo "=> 00211 NAME: ".$nombreFile." \n";
                        }else{
                            //$nombreFile = str_replace($subsName, $planSuc, $nombreFile);
                            //echo "=> 00212 NAME: ".$nombreFile." \n";
                        }
                    }

                    //$rutaFile = "public/plano/".$nombreFile; 
                    $rutaFile = $consPlano['ruta'].$nombreFile;
	            // $dataPlan = str_replace('-',"/", $dataPlan);
                    Funciones::crearTXT($dataPlan,$rutaFile,$nombreFile,$consPlano['ftp'],$consPlano['sftp']);
                } 
            }

            $consTabla->where('planoRegistro',0)->update(['planoRegistro' => 1]);        

        }
    }
}
