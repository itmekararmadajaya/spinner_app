<?php

use App\Livewire\Spinner;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', Spinner::class);

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/spinner_app/livewire/update', $handle);
});
Livewire::setScriptRoute(function ($handle) {
    return Route::get('/spinner_app/livewire/livewire.js', $handle);
});
