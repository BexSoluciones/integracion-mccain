<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model {
    protected $connection  = 'mysql';
    protected $table = 'tbl_consulta';
}
