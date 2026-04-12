<?php

use App\Http\Controllers\AeroportController;
use App\Http\Controllers\CiutatController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DivisasController;
use App\Http\Controllers\EstatsOfertesController;
use App\Http\Controllers\IncotermController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\LineTransMarController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PaissosController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TipoIncotermController;
use App\Http\Controllers\TipusCarregaController;
use App\Http\Controllers\TipusContenedorsController;
use App\Http\Controllers\TipusFluxeController;
use App\Http\Controllers\TipusTransportController;
use App\Http\Controllers\TipusValidacionsController;
use App\Http\Controllers\TrackingStepsController;
use App\Http\Controllers\TransportistaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UsuariController::class, 'login']);
Route::post('/logout', [UsuariController::class, 'logout'])
    ->middleware('auth:sanctum');


Route::apiResource('aeroports',         AeroportController::class);
Route::get('/aeropuerto/buscar',        [AeroportController::class, 'buscarAeropuertos']);

Route::apiResource('ciutats',           CiutatController::class);
Route::apiResource('company',           CompanyController::class);
Route::apiResource('divisas',           DivisasController::class);
Route::apiResource('estats',            EstatsOfertesController::class);
Route::apiResource('incoterms',         IncotermController::class);
Route::apiResource('industries',        IndustryController::class);
Route::apiResource('lines',             LineTransMarController::class);
Route::apiResource('ofertas',           OfertaController::class);
Route::apiResource('paises',            PaissosController::class);
Route::apiResource('ports',             PortController::class);
Route::get('/port/buscar',              [PortController::class, 'buscarPuertos']);

Route::apiResource('rols',              RolController::class);
Route::apiResource('tipoIncoterm',      TipoIncotermController::class);
Route::apiResource('tipoCarga',         TipusCarregaController::class);
Route::apiResource('tipoContenedor',    TipusContenedorsController::class);
Route::apiResource('tipoFlujo',         TipusFluxeController::class);
Route::apiResource('tipoTransporte',    TipusTransportController::class);
Route::apiResource('tipoValidacion',    TipusValidacionsController::class);
Route::apiResource('trackingSteps',     TrackingStepsController::class);
Route::apiResource('transportistas',    TransportistaController::class);
Route::get('/transportista/buscar',     [TransportistaController::class, 'buscarTransportista']);

Route::apiResource('usuaris',           UsuariController::class);
Route::get('/usuari/buscar',           [UsuariController::class, 'buscarClientes']);
Route::get('/comercial/buscar',        [UsuariController::class, 'buscarComercial']);
