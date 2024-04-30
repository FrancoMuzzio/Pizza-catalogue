<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pizza;
use Inertia\Inertia;
use Inertia\Response;

class PizzaController extends Controller
{
    public function index()
    {
        $pizzas = Pizza::all();
        $pizzas->each(function ($pizza) {
            $pizza->price = number_format($pizza->getPrice(), 2);
        });
        return Inertia::render('Pizzas/Index', [
            'pizzas' => $pizzas
        ]);
    }
}