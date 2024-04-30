<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PizzaController;

Route::get('/', [PizzaController::class, 'index'])->name('pizzas.index');
Route::post('/', [PizzaController::class, 'order'])->name('pizzas.order');