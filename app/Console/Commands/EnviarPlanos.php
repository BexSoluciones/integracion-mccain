<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\Enviar;
use App\Modelo\Correo;
use Mail;

class EnviarPlanos extends Command
{
    protected $signature = 'integracion:enviar-planos';
    protected $description = 'Enviar archivos planos generados previamente';

    public function __construct(){
        parent::__construct();
    }

    public function handle()
    {
        $list = Correo::where('estado',1)->get(); 
        foreach ($list as $data) { $email = new Enviar(); Mail::to($data['correo'])->send($email); }

        $ruta = '/var/www/html/integracion-coltabaco/public/plano';
    	$ruta_enviado = '/var/www/html/integracion-coltabaco/public/plano_enviado';

        // $ruta = 'public/plano';
        // $ruta_enviado = 'public/plano_enviado';
    	
        if (is_dir($ruta)){
	        $gestor = opendir($ruta);
	        while (($archivo = readdir($gestor)) !== false)  {	                
                echo "ARCHIVO: ".$archivo." \n";
	            $ruta_completa = $ruta . "/" . $archivo;
	            $ruta_completa_nueva = $ruta_enviado . "/" . $archivo;
	            if ($archivo != "." && $archivo != "..") {
	                rename($ruta_completa, $ruta_completa_nueva);
	            }else{
                    echo "NO SE ENCONTRO ARCHIVOS \n";
                }
	        }
	        closedir($gestor);
	    }else{
            echo "NO SE ENCONTRO LA RUTA \n";
        }
    }
}