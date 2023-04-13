<?php

use App\Http\Controllers\ConcursoController;
use Illuminate\Support\Facades\Route;

Route::get('/data', [ConcursoController::class, 'data']);
Route::post('/data', [ConcursoController::class, 'saveData'])->name('data');

Route::get('/index', [ConcursoController::class, 'index']);

Route::get('/concurso', [ConcursoController::class, 'concurso']);
Route::get('/concurso/{convocatoria}', [ConcursoController::class, 'show']);
Route::get('/concurso/botoneria/{convocatoria}/{btn_url}', [ConcursoController::class, 'botoneria']);
Route::post('/botoneria_data', [ConcursoController::class, 'printPant'])->name('botoneria_data');

// Redirects to the Stock Resource Controller
Route::get('/', function () {
    return redirect('/data');
});
