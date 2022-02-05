<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Modelo\Conexion;
use App\Modelo\Consulta;
use App\Modelo\Funciones;
use App\Modelo\ConsultaConsecutivo;
use App\Modelo\Tabla;
use App\Modelo\LogTable;
use DB;

class VerificarTablas extends Command
{
    protected $signature = 'integracion:verificar-tablas';
    protected $description = 'Verifica y crea tablas en base de datos';

    public function __construct(){ parent::__construct(); }
    
    // FUNCION CON ARRAY DE TABLAS EXENTAS DE ELIMINAR EN LA FUNCION `SEARCH_DELETE_TABLE`
    public static function EXCEPTION_TABLE(){ $table = ['tbl_conexion','tbl_consulta','tbl_comando','tbl_correo','tbl_plano','tbl_formato','tbl_plano_funcion','tbl_log','tbl_quemado','tbl_consulta_condicion','tbl_consulta_consecutivo']; return $table; }

    // CONSULTA LISTA DE TABLAS EN ESTADO 1 Y LUEGO BUSCA SI EXISTE LAS TABLAS EN LA BD, SI NO EXISTE CREA LA TABLA CON LOS CAMPOS DEL 
    // RESULTADO WS, SI EXISTE TABLA HACE TRUNCATE EN CASO DE ESTAR ACTIVO EN LA BD. TAMBIEN  BUSCA Y ELIMINA TABLAS DE LA BD

    public function handle(){
        $listaConsulta = Consulta::where('estado',1)->get();
        foreach ($listaConsulta as $value) {            
            Consulta::where('codigo', $value->codigo)->update(['prioridad' => 1]); $tabla = $value->tabla_destino;
            if (Schema::hasTable($tabla)) {
                if ($value->truncate == 1) {
                    $consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($tabla); $consTabla->truncate();
                }    
                if ($value->drop_table == 1) {
                    Schema::drop($tabla);
                    try {
                        Schema::create($tabla, function($table){ 
                            $resultado = self::TABLE_SOAP(); 
                            if (is_array($resultado)) {
                                if (count($resultado) > 0) {
                                    $columns = array_keys($resultado[0]); $table->increments('codigo','11'); 
                                    foreach ($columns as $key => $tablaName) { $table->string($tablaName,'100')->nullable(true);  }
                                    $table->boolean('planoRegistro')->default(0)->nullable(true); $table->timestamps();
                                }
                            } 
                        });
                    } catch (\Exception $e) {
                        $error = $e->getMessage(); $reg = new LogTable; $reg->descripcion = '´'.$tabla.'´ : '.$error;
                        if ($reg->save()) {}else{ echo 'Excepción capturada registro tabla ´'.$tabla.'´: ', $e->getMessage(), "\n"; }
                    }
                }         
            }else{
                try {
                    Schema::create($tabla, function($table){ 
                        $resultado = self::TABLE_SOAP(); 
                        if (is_array($resultado)) {
                            if (count($resultado) > 0) {
                                $columns = array_keys($resultado[0]); $table->increments('codigo','11'); 
                                foreach ($columns as $key => $tablaName) { $table->string($tablaName,'100')->nullable(true);  } 
                                $table->boolean('planoRegistro')->default(0)->nullable(true); $table->timestamps();
                            }
                        }
                    });   
                }catch (\Exception $e) {
                    $error = $e->getMessage(); $reg = new LogTable; $reg->descripcion = '´'.$tabla.'´ : '.$error;
                    if ($reg->save()) {}else{ echo 'Excepción capturada registro tabla ´'.$tabla.'´: ', $e->getMessage(), "\n"; }
                }
            }            
            Consulta::where('codigo', '>', 0)->update(['prioridad' => 0]);
        }   

        self::SEARCH_DELETE_TABLE();
    }

    // BUSCA INFORMACION AL WS DE LA TABLA CON PRIORIDAD 1 Y RETORNA EL RESULTADO DEL WS
    public static function TABLE_SOAP(){
        $dataConexion = Conexion::where('estado',1)->first(); $listaConsulta = Consulta::where('prioridad',1)->first(); $consecutivoCons = ConsultaConsecutivo::where('consulta',$listaConsulta->codigo)->first(); 
        $sentencia = Funciones::ParametroSentencia($listaConsulta,$dataConexion,true,false,$consecutivoCons); $xml = Funciones::consultaStructuraXML($dataConexion->conexion,$dataConexion->cia,$dataConexion->proveedor,$dataConexion->usuario,$dataConexion->clave,$sentencia,$dataConexion->consulta,1,0);
        $resultado = Funciones::SOAP($dataConexion->url, $xml, $listaConsulta->tabla_destino);
        return $resultado;
    }

    // BUSCA Y ELIMINA TABLAS DE LA BD QUE NO ESTEN REGISTRADAS EN LA TABLA DE ´tbl_consulta´ EN ESTADO 1 Y QUE NO ESTEN EN LA FUNCION ´EXCEPTION_TABLE´
    public static function SEARCH_DELETE_TABLE(){
        $lista_tablas_cons = Consulta::where('estado',1)->get(); $tabla_exe = self::EXCEPTION_TABLE(); 
        foreach ($lista_tablas_cons as $value) { array_push($tabla_exe, $value->tabla_destino); } $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            foreach ($table as $key => $value){
                $encontrado_ = false; foreach ($tabla_exe as $key => $valueTable) { if ($valueTable === $value) { $encontrado_ = true; } }
                if ($encontrado_ == false) { Schema::drop($value); }
            }
        } 
    }
}