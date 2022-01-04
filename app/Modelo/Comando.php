<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Comando extends Model {
    protected $connection  = 'mysql';
    protected $table = 'tbl_comando';
}
