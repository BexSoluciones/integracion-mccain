<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Conexion extends Model {
    protected $connection  = 'mysql';
    protected $table = 'tbl_conexion';
}
