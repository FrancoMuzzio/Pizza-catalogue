<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pizza;
use App\Models\Ingredient;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class PizzaController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::all();
        $pizzas = Pizza::with('ingredients')->get();
        return Inertia::render('Pizzas/Index', [
            'pizzas' => $pizzas,
            'allIngredients' => $ingredients
        ]);
    }

    public function order(Request $request)
    {
        $pizzaName = $request->input('pizza');
        $orderIngredientNames = $request->input('ingredients');
        $orderedPizza = Pizza::where('name', $pizzaName)->with('ingredients')->first();
        $originalIngredients = $orderedPizza->ingredients->pluck('name')->toArray();
        $removedIngredients = array_diff($originalIngredients, $orderIngredientNames);
        $extraIngredients = array_diff($orderIngredientNames, $originalIngredients);
        $pizzaToCalculate = new Pizza();
        $pizzaToCalculate->setRelation('ingredients', $orderedPizza->ingredients);
        $finalPrice = $pizzaToCalculate->getPrice();

        return response()->json([
            'pizzaName' => $pizzaName,
            'originalIngredients' => $originalIngredients,
            'extraIngredients' => $extraIngredients,
            'removedIngredients' => $removedIngredients,
            'price' => $finalPrice,
        ]);
    }

}
