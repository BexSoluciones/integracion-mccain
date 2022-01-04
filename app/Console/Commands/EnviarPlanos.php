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
        $list = Correo::where('estado',1)->get(); foreach ($list as $data) { $email = new Enviar(); Mail::to($data['correo'])->send($email); }
    }
}