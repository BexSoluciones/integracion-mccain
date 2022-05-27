<?php

namespace App\Modelo;

use Illuminate\Database\Eloquent\Model;

class Tabla extends Model {
	use BindsDynamically;
}

trait BindsDynamically{

    protected $connection = null;
    protected $table = null;

    public function bind(string $table){
       $this->setConnection('mysql');
       $this->setTable($table);
    }

    public function newInstance($attributes = [], $exists = false){
       $model = parent::newInstance($attributes, $exists); $model->setTable($this->table);
       return $model;
    }

}