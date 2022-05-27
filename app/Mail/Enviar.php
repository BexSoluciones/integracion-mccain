<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Enviar extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(){
        //
    }

    public function build()
    {
    	$correo = $this->view('correo.plano')->subject("Philip Morris International Coltabaco"); 
    	$ruta = '/var/www/html/integracion-coltabaco/public/plano';
    	// $ruta = 'public/plano';

	    if (is_dir($ruta)){
	        $gestor = opendir($ruta);
	        while (($archivo = readdir($gestor)) !== false)  {	                
	            $ruta_completa = $ruta . "/" . $archivo;
	            if ($archivo != "." && $archivo != "..") {
	                if (is_dir($ruta_completa)) {  self::obtenerArchivos($ruta_completa);  }else{  $correo->attach($ruta_completa);  }
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
