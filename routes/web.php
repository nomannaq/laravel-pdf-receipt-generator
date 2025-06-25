<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/receipt', fn () => view('receipt.livewire-wrapper'));
Route::get('/templates', fn () => view('templates.index'));