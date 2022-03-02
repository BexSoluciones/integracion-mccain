<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Modelo\Conexion;
use App\Modelo\Consulta;
use App\Modelo\ConsultaCondicion;
use App\Modelo\ConsultaConsecutivo;
use App\Modelo\Funciones;
use App\Modelo\Tabla;
use App\Modelo\LogTable;

class GuardarInformacion extends Command
{
    protected $signature = 'integracion:guardar-informacion';
    protected $description = 'Obtener información y generar guardado en base de datos';

    public function __construct(){ parent::__construct(); }

    public function handle(){

        //OBTIENE LISTA DE CONEXION & CONSULTAS REGISTRADAS QUE ESTEN EN ESTADO 1
        $dataConexion = Conexion::where('estado',1)->first(); $listaConsulta = Consulta::where('estado',1)->orderBy('codigo','asc')->get(); $resultado = array(); LogTable::truncate(); 

        //RECORRE LISTA DE CONSULTAS REGISTRADAS
        foreach ($listaConsulta as $value) {

            echo "<br>##################### $value->tabla_destino ####################################<br>\n";

            //CONSULTA LISTA DE CONSECUTIVOS QUE TIENE LA CONSULTA QUE ESTA RECORRIENDO
            ConsultaConsecutivo::where('consulta',$value->codigo)->where('consecutivo_b','>',0)->update(['consecutivo_b' => 1]);
            $consecutivosTabla = ConsultaConsecutivo::where('consulta',$value->codigo)->get();

            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($value->tabla_destino); 

            // VERIFICA SI TRUNCATE ESTA ACTIVADO EN LA CONFIG DE CONSULTA
            if ($value->truncate == '1') { 
                $consTabla->truncate();  // BORRA REGISTROS PREVIOS DE TABLA CONSULTADA
                //ACTUALIZA EN 1 EL COMIENZO DE CONSECUTIVOS DE LA CONSULTA PARA EMPEZAR BUSQUEDA DESDE 1
                ConsultaConsecutivo::where('consulta',$value->codigo)->where('consecutivo','>',0)->update(['consecutivo' => 1]);
                ConsultaConsecutivo::where('consulta',$value->codigo)->where('consecutivo_b','>',0)->update(['consecutivo_b' => 1]);
            }
            
            $dataTableReg = $consTabla->get();

            //RECORREMOS CADA UNO DE LOS TIPOS DE CONSECUTIVOS QUE TENGA CADA CONSULTA
            foreach ($consecutivosTabla as $consecutivoValue) {

                //DECLARACION DE VARIABLES PARA POSTERIOR USO EN EL RECORRIDO DE DATOS CONSULTADOS SOAP
                $stopWhile = 1; $busqueda_alterna = false; 

                //CREACION DE BUCLE PARA RECORRER CONSULTA
                do{ 

                    echo "<br> ===============> $consecutivoValue->tipo_documento RODANDO BUCLE <br>\n";

                    $continuarSOAP = true;
                    //SI NO ARROJA RESULTADOS LA PRIMERA SENTENCIA, EJECUTA LA SENTENCIA ALTERNA REGISTRADA EN LA CONSULTA               
                    if ($busqueda_alterna == true) { 
                        echo "<br> EMPEZANDO BUSQUEDA ALTERNA <br>\n"; 
                        if ($value->sentencia_alterna == null) { $continuarSOAP = false; }
                    }

                    if ($continuarSOAP == true) {

                        //REMPLAZA PARAMETROS QUE TENGA LA SENTENCIA "@.." POR LOS PARAMETROS CORRESPONDIENTES EN LA TABLA DE CONSULTAS
                        $sentencia = Funciones::ParametroSentencia($value,$dataConexion,false,$busqueda_alterna,$consecutivoValue);
                        
                        //CONSTRUCTOR DE XML PARA CONSULTAR POR SOAP 
                        $xml = Funciones::consultaStructuraXML($dataConexion->conexion,$dataConexion->cia,$dataConexion->proveedor,$dataConexion->usuario,$dataConexion->clave,$sentencia,$dataConexion->consulta,1,0);

                        //RESULTADO DATOS CONSULTA SOAP
                        $datos = Funciones::SOAP_SAVE($dataConexion->url, $xml, $value->tabla_destino);
                        $resultado = Funciones::SOAP($dataConexion->url, $xml, $value->tabla_destino);

                        //VERIFICAMOS SI ARROJA RESULTADOS
                        if (is_array($resultado) && is_array($datos)) { 
                            echo "<br>================= $value->tabla_destino ================================<br>\n"; 
                            
                            //CONSULTA LISTA DE CONDICIONES PARA VALIDACION Y PASAR A REGISTRO
                            $condicionRegistro = ConsultaCondicion::where('id_consulta',$value->codigo)->first();  

                            //RECORRE RESULTADO DE DATOS ARROJADOS EN LA CONSULTA SOAP
                            foreach ($resultado as $resKey => $valres) {
                                echo "<br>".print_r($valres)."<br>";

                                //DECLARACION DE VARIABLE PARA VALIDAR REGITRO DE DATO, REINICIA EN CADA RECORRIDO DE RESULTADO SOAP
                                $regCond = true; //$reselect = null; 

                                //VALIDA SI LA CONSULTA CUENTA CON CONDICIONES REGISTRADAS 
                                if (isset($condicionRegistro)) {
                                    
                                    echo "TABLA REGISTRO: ".count($dataTableReg);
                                    // print_r($dataTableReg);
                                    echo "<br>";

                                    //CREAMOS UN ARRAY APARTIR DEL RESULTADO DE CONDICIONES Y OBTENEMOS LA CANTIDAD DE RESULTADOS
                                    $arrayCondB = explode(',', $condicionRegistro['condicion']); $totalCondRay = count($arrayCondB);
                                    echo "-----------------------------------<br>\n";
                                    echo "CONDICIONES: ".$totalCondRay."\n";
                                    echo "<br>-------------------------------<br>\n";

                                    $valueReSOAP = str_replace("'", "", $valres[$arrayCondB[0]]);

                                    $consultaCampoCond = new Tabla; $consultaCampoCond->getTable(); $consultaCampoCond->bind($value->tabla_destino); 
                                    $codigoCons = $consultaCampoCond->select('codigo')->where($arrayCondB[0],$valueReSOAP)->get(); 

                                    $coincidenciaCons = 0;

                                    if (count($codigoCons) > 0) {
                                        foreach ($codigoCons as $keyCons => $valueConstable) {

                                            $acumuladoCons = 1;

                                            for ($i=1; $i < count($arrayCondB); $i++) { 
                                                if (isset($valres[$arrayCondB[$i]])) {
                                                    
                                                    $valueConsoap = str_replace("'", "", $valres[$arrayCondB[$i]]);
                                                    
                                                    // echo "============== CONSULTA [ $arrayCondB[$i] = $valueConsoap ] SE ENCONTRO EN LA CONSULTA SOAP <br> \n";
                                                    
                                                    $consultaCampoCondicion = new Tabla; $consultaCampoCondicion->getTable(); 
                                                    $consultaCampoCondicion->bind($value->tabla_destino); 
                                                    $resCons = $consultaCampoCondicion->select('codigo')->where('codigo',$valueConstable['codigo'])->where($arrayCondB[$i],$valueConsoap)->first();

                                                    if (isset($resCons['codigo'])) { 
                                                        echo "LA CONDICION [ $arrayCondB[$i] = $valueConsoap ] SE ENCONTRO EN LA CONSULTA EN LA BD [ ".$valueConstable['codigo']." ] <br> \n";
                                                        $acumuladoCons++; 
                                                    }else{
                                                        echo "LA CONDICION [ $arrayCondB[$i] = $valueConsoap ] NO SE ENCONTRO EN LA CONSULTA EN LA BD [ ".$valueConstable['codigo']." ] <br> \n";
                                                    }

                                                }else{
                                                    echo "LA CONDICION [ $arrayCondB[$i] ] NO SE ENCONTRO EN LA CONSULTA SOAP <br> \n";
                                                }

                                            }

                                            if ($acumuladoCons == count($arrayCondB)){ $coincidenciaCons++; break; }

                                            echo "\n";

                                        }
                                    }

                                    echo "CONSULTANDO CONDICIONES CONSULTA SOAP <br> \n";                                    

                                    if ($coincidenciaCons > 0){
                                        echo "ESTE DATO [ ".$valueReSOAP." ] SE ENCUENTRA REGISTRADA EN LA TABLA [ $value->tabla_destino ], PASANDO AL SIGUIENTE RESULTADO <br> \n";
                                        $regCond = false;
                                    }else{
                                        echo "ESTE DATO NO SE ENCUENTRA REGISTRADA EN LA TABLA [ $value->tabla_destino ] <br> \n";
                                    }      

                                    // dd($coincidenciaCons);

                                }else{ 
                                    echo "NO TIENE CONDICIÓN <br>"; 
                                }
         
                                echo "<br><br>";

                                $keyDat = $resKey+1; //DECLARAMOS LA POSICION DEL KEY DE RESULTADOS SOAP QUE QUEREMOS REGISTRAR

                                echo "============= CONSECUTIVO $consecutivoValue->consecutivo =============== \n";
                                // dd($datos);
                                // echo "============= DATOS $keyDat =============== \n";
                                
                                // SI LA CONDICION PREVIA PARA REGISTRO ES TRUE
                                if ($regCond == true) { 
                                    echo "DATA REGISTRADO"; // VALIDA NUEVAMENTE QUE EL ARRAY CON DATOS SOAP CUENTE CON DATOS Y REGISTRA EN LA TABLA CONSULTADA LA INFORMACION
                                    if (count($datos) > 0) { $consTabla->insert($datos[$keyDat]); }  
                                    // dd($datos[$keyDat]);                          
                                }else{ unset($datos[$keyDat]); } // DE LO CONTRARIO EL SISTEMA CONCLUYE QUE EL DATO YA ESTA REGISTRADO O NO ES APTO PARA EL REGISTRO, ELIMINA ESTE DATO DEL ARRAY SOAP

                                $endArray = end($resultado); // CONSULTA EL ULTIMO REGISTRO DEL ARRAY SOAP

                                //VALIDA EL CONSECUTIVO SEA MAYOR A 0 PARA ACTUALIZAR AL CONSECUTIVO DEL ULTIMO REGISTRO IMPORTADO EN EL ARRAY SOAP
                                if ($consecutivoValue->consecutivo > 0) {
                                    //CONSULTAMOS EL DATO EN EL ULTIMO REGISTRO ARROJADO POR SOAP, EL CAMPO GUARDADO EN LA CONSULTA PARA EXTRAER CONSECUTIVO
                                    if (isset($endArray[$consecutivoValue->campo_consecutivo])) {
                                        $valRay = str_replace("'", "", $endArray[$consecutivoValue->campo_consecutivo]);
                                        ConsultaConsecutivo::where('codigo',$consecutivoValue->codigo)->update(['consecutivo' => $valRay]);
                                    }                    
                                }

                                //VALIDA EL CONSECUTIVO B SEA MAYOR A 0 PARA ACTUALIZAR AL CONSECUTIVO DEL ULTIMO REGISTRO IMPORTADO EN EL ARRAY SOAP
                                if ($consecutivoValue->consecutivo_b > 0) {
                                    //CONSULTAMOS EL DATO EN EL ULTIMO REGISTRO ARROJADO POR SOAP, EL CAMPO GUARDADO EN LA CONSULTA PARA EXTRAER CONSECUTIVO B 
                                    if (isset($endArray[$consecutivoValue->campo_consecutivo_b])) {
                                        $valRayB = str_replace("'", "", $endArray[$consecutivoValue->campo_consecutivo_b]);
                                        ConsultaConsecutivo::where('codigo',$consecutivoValue->codigo)->update(['consecutivo_b' => $valRayB]);
                                    }
                                }

                            }

                            $stopWhile = 0;

                        }else{

                            if ($busqueda_alterna == true) {      
                                $busqueda_alterna = false;  $stopWhile = 0; 
                            }else{ 
                                $busqueda_alterna = true; 
                            }

                            echo "<br> DEFINIENDO BUCLE $stopWhile <br>\n";

                        }
                    }else{ 
                        // if ($busqueda_alterna == true) {  $busqueda_alterna = false; }
                        $stopWhile = 0;                          
                    }                                      

                }while($stopWhile != 0);

            }

            
            
        }
    }  

}