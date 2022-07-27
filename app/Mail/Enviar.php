<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Enviar extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct($sucursal){
        $this->sucursal = $sucursal;
    }

    public function build()
    {
    	$correo = $this->view('correo.plano')->subject("Concesionario ".$this->sucursal." | Philip Morris International Coltabaco"); 
    	$ruta = '/var/www/html/integracion-coltabaco/public/plano';
    	// $ruta = 'public/plano';

	    if (is_dir($ruta)){
	        $gestor = opendir($ruta);
	        while (($archivo = readdir($gestor)) !== false)  {	                
	            $ruta_completa = $ruta . "/" . $archivo;
	            if ($archivo != "." && $archivo != "..") {
	            	$pos = strpos($archivo, $this->sucursal);
	            	// dd($pos);
	            	if ($pos === false) {
	            		echo "El archico '$archivo' no fue encontrada en la sucursal ".$this->sucursal."\n";
	            	}else{
	            		echo "El archico '$archivo' fue encontrada en la sucursal ".$this->sucursal." y existe en la posición $pos \n";
    					if (is_dir($ruta_completa)) {  self::obtenerArchivos($ruta_completa);  }else{  $correo->attach($ruta_completa);  }
	            	}
	            }else{
	            	echo "ARCHIVO B: ".$archivo." \n";
	            }
	        }
	        closedir($gestor);
	    } else {
	        return "No es una ruta de directorio valida <br/> \n";
	    }

        return $correo;
    }
}
