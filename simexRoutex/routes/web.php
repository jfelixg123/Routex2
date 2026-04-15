<?php

use Illuminate\Support\Facades\Route;


//Toda la app usara esta porque toda la app son componentes.
Route::get('/', function () {
    return view('layouts.home');
})->name('home');

Route::get('/login', function () {
    return view('auth.login');
});
