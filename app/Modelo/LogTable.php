<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class LogTable extends Model {
    protected $connection  = 'mysql';
    protected $table = 'tbl_log';
}
