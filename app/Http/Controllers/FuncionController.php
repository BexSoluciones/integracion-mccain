<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Modelo\Tabla;

class FuncionController extends Controller{

    public function limpiarLog(){
    	file_put_contents('storage/logs/laravel.log', "");
    	return '=> Se ha limpiado el log de errores';
    }

    public function vista(Request $request){
    	$tabla = $request->input('view'); $columns = Schema::getColumnListing($tabla);
    	$dataTabla = new Tabla; $dataTabla->getTable(); $dataTabla->bind($tabla); $resCons = $dataTabla->get(); 
    	return view('vista_opcion',array('tabla'=>$tabla,'dataTabla'=>$resCons,'dataColumns'=>$columns));
    }

    public function comando(Request $request){
    	$comando = $request->input('comando');  Artisan::call($comando);
    	// return 'comando ´'.$comando.'´ ejecutado exitosamente';
    }

    public function registro(Request $request){
    	
    	$tabla = $request->input('tabla'); $columns = Schema::getColumnListing($tabla);
    	$darray = $request->input('datArray'); $newDat = array(); $sum = 0;
    	
    	foreach ($columns as $col) {
    		if ($col != 'codigo' && $col != 'created_at' && $col != 'updated_at') {
    			$newDat[$col] = $darray[$sum];
    			$sum++;
    		}
    	}

    	$consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($tabla);
    	if ($consTabla->insert($newDat)) {
    		$data[0] = true; $data[1] = 'Se registro exitosamente!';
    	}else{
    		$data[0] = false; $data[1] = 'No se pudo registrar!';
    	}

    	return $data;

    }

    public function actualizar(Request $request){
    	$tabla = $request->input('tabla'); $codigo = $request->input('codigo'); $columns = Schema::getColumnListing($tabla);
    	$darray = $request->input('datArray'); $newDat = array(); $sum = 0;

    	foreach ($columns as $col) {
    		if ($col != 'codigo' && $col != 'created_at' && $col != 'updated_at') {
    			$newDat[$col] = $darray[$sum];
    			$sum++;
    		}
    	}

    	$consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($tabla);
    	if ($consTabla->where('codigo',$codigo)->update($newDat)) {
    		$data[0] = true; $data[1] = 'Se actualizo exitosamente!';
    	}else{
    		$data[0] = false; $data[1] = 'No se pudo actualizar!';
    	}

    	return $data;
    }

    public function eliminar(Request $request){
    	$tabla = $request->input('tabla'); $codigo = $request->input('codigo');
    	$consTabla = new Tabla; $consTabla->getTable(); $consTabla->bind($tabla);
    	if ($consTabla->where('codigo',$codigo)->delete()) {
    		$data[0] = true; $data[1] = 'Se elimino exitosamente!';
    	}else{
    		$data[0] = false; $data[1] = 'No se pudo eliminar!';
    	}

    	return $data;
    }

}
    