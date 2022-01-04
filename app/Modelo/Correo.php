<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model {
    protected $connection  = 'mysql';
    protected $table = 'tbl_correo';
}
