<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Modelo\Tabla;
use App\Modelo\LogTable;

class Funciones extends Model {   

    public static function convertirObjetosArrays($objetos){       
        $arrayValues = [];  $acumValues = 0;
        foreach ($objetos as $key => $objeto) {
            $arrayValuesRow = [];
            foreach ($objeto as $keyb => $valores) {
                if ($valores != '') {
                    $arrayValuesRow[(String) $keyb] = (String) "'".$valores."'";
                }else{
                    $arrayValuesRow[(String) $keyb] = (String) "NULL";
                }
            }
            $arrayValues[$acumValues] = (array) $arrayValuesRow;
            $acumValues++;
        }
        return $arrayValues;
    }

    public static function getTableColumns($table){
        return Schema::getColumnListing($table);
    }

    public static function convertirObjetosArraysWS($objetos,$tabla){
        $listaColumnas = self::getTableColumns($tabla); $data = [];
        foreach ($listaColumnas as $keya => $column) {
            if ($column != 'codigo' && $column != 'created_at' && $column != 'updated_at') {
                $sum = 1;
                foreach ($objetos as $keyb => $objeto) {
                    $srhColumn = false;
                    foreach ($objeto as $keyc => $value) {
                        if ($column == $keyc) { 
                            $srhColumn = true;
                            if ($value != '') {
                                $data[$sum][$column] = $value;
                            }else{
                                $data[$sum][$column] = NULL; 
                            }
                        }                  
                    }
                    if ($srhColumn == false) { $data[$sum][$column] = NULL; }
                    $sum++;
                } 
            }
        }  
        return $data;
    }

    public static function NombreArchivo($consPlano){
        
        $name = NULL;
        
        if ($consPlano['seccion_a'] != '') {
            $name .= self::RutaDate($consPlano['seccion_a']);
        }else if($consPlano['seccion_campo_a'] != ''){            
            $consPlan = $consPlano['seccion_campo_a'];
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($consPlan[0]); $resCons = $consTabla->select($consPlan[1])->first();
            $name .= $consTabla[$consPlan[1]];
        }else{ $name .= $consPlano['seccion_default']; }

        if ($consPlano['seccion_b'] != '') {
            $name .= self::RutaDate($consPlano['seccion_b']);
        }else if($consPlano['seccion_campo_b'] != ''){            
            $consPlan = $consPlano['seccion_campo_b'];
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($consPlan[0]); $resCons = $consTabla->select($consPlan[1])->first();
            $name .= $consTabla[$consPlan[1]];
        }else{ $name .= $consPlano['seccion_default']; }

        if ($consPlano['seccion_c'] != '') {
            $name .= self::RutaDate($consPlano['seccion_c']);
        }else if($consPlano['seccion_campo_c'] != ''){            
            $consPlan = $consPlano['seccion_campo_c'];
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($consPlan[0]); $resCons = $consTabla->select($consPlan[1])->first();
            $name .= $consTabla[$consPlan[1]];
        }else{ $name .= $consPlano['seccion_default']; }

        if ($consPlano['seccion_d'] != '') {
            $name .= self::RutaDate($consPlano['seccion_d']);
        }else if($consPlano['seccion_campo_d'] != ''){            
            $consPlan = $consPlano['seccion_campo_d'];
            $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($consPlan[0]); $resCons = $consTabla->select($consPlan[1])->first();
            $name .= $consTabla[$consPlan[1]];
        }else{ $name .= $consPlano['seccion_default']; }

        $name .= $consPlano['extension'];

        return $name;
    }

    public static function ReplaceText($texto){
        $texto = str_replace(['"',"'",'{','}','[',']','(',')','null','NULL'], "", $texto);
        $texto = trim($texto);
        return $texto;
    }

    public static function RutaDate($campo){
        
        $camp = $campo;

        if ($campo == 'DD') {
            $camp = date('d');
        }else if ($campo == 'MM') {
            $camp = date('m');
        }else if ($campo == 'AA') {
            $camp = date('Y');
        }

        if ($camp > 0 && $camp < 10) { $camp = "0".$camp; }
        return $camp;
    }

    public static function crearTXT($plano,$ruta,$nombreFile,$ftp,$sftp){
        
        $file = url('/')."/public/planos/".$nombreFile;

        if (file_exists($ruta)){
            $archivo = fopen($ruta, "w+"); fwrite($archivo, $plano); fclose($archivo);
        }else{
            $archivo = fopen($ruta, "w"); fwrite($archivo, $plano); fclose($archivo);
        } 

        if ($ftp === 1) { Storage::disk('ftp')->put($nombreFile, $plano); }
        if ($sftp === 1) { Storage::disk('sftp')->put($nombreFile, $plano); }

    }

    public static function nombreDia($fecha) {
        $dias = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
        $fecha = $dias[date('N', strtotime($fecha))];
        return $fecha;
    }

    public static function diaSemana($dia) {
        if ($dia == 'Lunes') {
            return "1";
        }else if ($dia == 'Martes') {
            return "2";
        }else if ($dia == 'Miercoles') {
            return "3";
        }else if ($dia == 'Jueves') {
            return "4";
        }else if ($dia == 'Viernes') {
            return "5";
        }else if ($dia == 'Sabado') {
            return "6";
        }else if ($dia == 'Domingo') {
            return "7";
        }
    }

    // CREA UNA ESTRUCTURA XML CON LOS DATOS DE CONEXION Y CONSULTA DE LA BD PARA REALIZAR CIERTA CONSULTA
    public static function consultaStructuraXML($empresa,$cia,$proveedor,$usuario,$clave,$sentencia,$idConsulta,$printError,$cacheWSDL){
        $parm['printTipoError'] = $printError;
        $parm['cache_wsdl'] = $cacheWSDL;        
        $parm['pvstrxmlParametros'] = "<Consulta>
                                            <NombreConexion>" . $empresa . "</NombreConexion>  
                                            <IdCia>" . $cia . "</IdCia>
                                            <IdProveedor>" . $proveedor . "</IdProveedor>
                                            <IdConsulta>" . $idConsulta . "</IdConsulta>
                                            <Usuario>" . $usuario . "</Usuario> 
                                            <Clave>" . $clave . "</Clave>
                                            <Parametros> 
                                                <Sql>".$sentencia."</Sql>
                                            </Parametros>
                                        </Consulta>";
        return $parm;
    }

    // EJECUTA CONEXION SOAP CON LA URL DE CONEXION CONSULTADA Y LA ESTRUCTURA XML CREADA ANTERIORMENTE
    public static function SOAP($url, $parametro){
        try {
            $client = new \SoapClient($url, $parametro);
            $result = $client->EjecutarConsultaXML($parametro)->EjecutarConsultaXMLResult->any; $any = simplexml_load_string($result);
            if (@is_object($any->NewDataSet->Resultado)) { return Funciones::convertirObjetosArrays($any->NewDataSet->Resultado); }

            if (@$any->NewDataSet->Table) {
                foreach ($any->NewDataSet->Table as $key => $value) {
                    echo ("\n");
                    echo ("\n Error Linea:\t " . $value->F_NRO_LINEA);
                    echo ("\n Error Value:\t " . $value->F_VALOR);
                    echo ("\n Error Desc:\t " . $value->F_DETALLE);
                }
            }  
        }catch (\Exception $e){
            $error = $e->getMessage(); $reg = new LogTable; $reg->descripcion = $error;
            if ($reg->save()) {}else{ echo 'Excepción capturada: ', $e->getMessage(), "\n"; }
        }
    }

    // EJECUTA CONEXION SOAP CON LA URL DE CONEXION CONSULTADA Y LA ESTRUCTURA XML CREADA ANTERIORMENTE
    public static function SOAP_SAVE($url, $parametro, $table){
        try {
            $client = new \SoapClient($url, $parametro);
            $result = $client->EjecutarConsultaXML($parametro)->EjecutarConsultaXMLResult->any; $any = simplexml_load_string($result);
            if (@is_object($any->NewDataSet->Resultado)) { return Funciones::convertirObjetosArraysWS($any->NewDataSet->Resultado,$table); }
            if (@$any->NewDataSet->Table) {
                foreach ($any->NewDataSet->Table as $key => $value) {
                    echo ("\n");
                    echo ("\n Error Linea:\t " . $value->F_NRO_LINEA);
                    echo ("\n Error Value:\t " . $value->F_VALOR);
                    echo ("\n Error Desc:\t " . $value->F_DETALLE);
                }
            }  
        }catch (\Exception $e){
            $error = $e->getMessage(); $reg = new LogTable; $reg->descripcion = $error;
            if ($reg->save()) {}else{ echo 'Excepción capturada: ', $e->getMessage(), "\n"; }
        }
    }

    public static function ParametroSentencia($consulta,$conexion){
        $criterio = $consulta->criterio;
        $sentencia = str_replace('@Cia', $conexion->cia, $consulta->sentencia);
        $sentencia = str_replace('@tipoDoc', $consulta->tipo_doc, $sentencia);
        $sentencia = str_replace('@conseDoc', $consulta->consecutivo, $sentencia);
        $sentencia = str_replace('@desdeItems', $consulta->desde_items, $sentencia);
        $sentencia = str_replace('@idPlan', $consulta->id_plan, $sentencia);
        $sentencia = str_replace('@idCriterio', $criterio[$consulta->criterio_sel], $sentencia);
        $sentencia = str_replace('@idEstadoActivo', 1, $sentencia);
        $sentencia = str_replace('@idEstado', 1, $sentencia);
        $sentencia = str_replace('@idValTercero', 1, $sentencia);
        $sentencia = str_replace('@idClaseImpuesto', 1, $sentencia);
        return $sentencia;
    }

}
