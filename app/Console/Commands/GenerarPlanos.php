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
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($value->tabla_destino); $resCons = $consTabla->get();

            $dataPlan = null;
            foreach ($resCons as $keya => $valueA) {                
                $suma = 0; $array = explode(",", $valueA); if ($consPlano['display_codigo'] == 0) { $sum = 1; }else{ $sum = 2; }                
                foreach ($array as $keyb => $valueB) {                  
                    if ($sum != 1) {

                        $campoDpl = true; $pos = strpos($valueB, ':'); $pos++;
                        $valueB = substr($valueB, $pos); $valueB = Funciones::ReplaceText($valueB);                     
                        $tipo = explode(",", $consFormato['tipo']); $longitud = explode(",", $consFormato['longitud']); 

                        // echo $valueB." | ".$suma."<br>";
                        // CAMPOS CON FUNCIONES ESPECIFICAS
                        foreach ($consPlanoFuncion as $planoFuncion) {
                            if ($planoFuncion->posicion == $suma) {
                                
                                if ($planoFuncion->tipo == 'dia') {
                                    $campoDpl = false; 
                                    $nombreDia = Funciones::nombreDia($valueB);
                                    $diaSemana = Funciones::diaSemana($nombreDia);
                                    if ($planoFuncion->tipo == 'texto') {
                                        $dataPlan .= " ".$consPlano['entre_columna'].str_pad($diaSemana, $planoFuncion->longitud).$consPlano['entre_columna'].$consPlano['separador'];
                                    }else{ $dataPlan .= str_pad($diaSemana, $planoFuncion->longitud).$consPlano['separador']; }
                                }else{
                                    $campoDpl = false; 
                                    if ($planoFuncion->tipo == 'texto') {
                                        $dataPlan .= " ".$consPlano['entre_columna'].str_pad($valueB, $planoFuncion->longitud).$consPlano['entre_columna'].$consPlano['separador'];
                                    }else{ $dataPlan .= str_pad($valueB, $planoFuncion->longitud).$consPlano['separador']; }
                                }
                            }
                        }

                        // CAMPOS QUEMADOS
                        if ($campoDpl == true) {
                            foreach ($consCampoQuemado as $campoQuemado) {
                                if ($campoQuemado->posicion == $suma) {
                                    $campoDpl = false; 
                                    if ($campoQuemado->tipo == 'texto') {
                                        $dataPlan .= " ".$consPlano['entre_columna'].str_pad($campoQuemado->valor, $campoQuemado->longitud).$consPlano['entre_columna'].$consPlano['separador'];
                                    }else{ $dataPlan .= str_pad($campoQuemado->valor, $campoQuemado->longitud).$consPlano['separador']; }
                                }
                            }
                        }


                        if ($campoDpl == true) {
                            // CAMPOS CONSULTA TABLA
                            if ($suma >= count($tipo)) {
                                // echo "ALERTA: => LA CANTIDAD DE CAMPOS EN LA POSICION `$sum` DE LA TABLA `tbl_formato` SOBREPASA, NO CONCUERDA CON LA CANTIDAD DE CAMPOS QUE CONTIENE LA TABLA: `$value->tabla_destino` ES `".count($tipo)."` <br>";
                            }else{
                                $tipoR = Funciones::ReplaceText($tipo[$suma]);  
                                $longitudR = Funciones::ReplaceText($longitud[$suma]);
                                
                                if ($tipoR == 'texto') {
                                    $dataPlan .= " ".$consPlano['entre_columna'].str_pad($valueB, $longitudR).$consPlano['entre_columna'].$consPlano['separador'];
                                }else{ $dataPlan .= str_pad($valueB, $longitudR).$consPlano['separador']; }
                            }   
                        }
                                        
                        
                    } $sum++; $suma++;
                }
                // echo "<br>";
                if ($consPlano['salto_linea'] == 1) { $dataPlan .= "\n"; } 
            }

            $nombreFile = Funciones::NombreArchivo($consPlano); $rutaFile = $consPlano['ruta'].$nombreFile;
            Funciones::crearTXT($dataPlan,$rutaFile,$nombreFile,$consPlano['ftp'],$consPlano['sftp']);                

        }
    }
}