<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/','LocalController@consulta'); // [ INICIO ] // 
Route::get('/panel','LocalController@consulta'); // [ PANEL ] // 
Route::get('/request_view','FuncionController@vista'); // [ REQUERIR VISTA ] // 
Route::get('/limpiar_log','FuncionController@limpiarLog'); // [ LIMPIAR LOG LARAVEL ] // 
Route::get('/ejecutar_comando','FuncionController@comando'); // [ EJECUTAR ARTISAN COMANDO ] // 
Route::get('/registrar_dato','FuncionController@registro'); // [ REGISTRAR DATO TABLA ] // 
Route::get('/actualizar_dato','FuncionController@actualizar'); // [ ACTUALIZAR DATO TABLA ] // 
Route::get('/eliminar_dato','FuncionController@eliminar'); // [ ELIMINAR DATO TABLA ] // 