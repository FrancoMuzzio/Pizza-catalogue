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
        $pizzaName = $request->input('pizzaName');
        $orderedIngredients = $request->input('ingredients');
        $pizza = Pizza::findByName($pizzaName);
        if (!$pizza) {
            return response()->json(['error' => 'Pizza not found.'], 404);
        }
        list($extraIngredients, $removedIngredients) = $this->findIngredientDifferences($pizza, $orderedIngredients);
        $finalIngredients = $this->getFinalIngredients($extraIngredients, $removedIngredients, $pizza);
        $originalIngredients = $pizza->ingredients->pluck('name')->toArray();
        $finalPrice = $this->calculateFinalPrice($pizza, $finalIngredients);
        return $this->formatOrderResponse($pizzaName, $originalIngredients, $extraIngredients, $removedIngredients, $finalPrice);
    }

    private function findIngredientDifferences($pizza, $orderedIngredients)
    {
        $originalIngredients = $pizza->ingredients->pluck('name')->toArray();
        $extraIngredients = array_diff($orderedIngredients, $originalIngredients);
        $removedIngredients = array_diff($originalIngredients, $orderedIngredients);
        return [$extraIngredients, $removedIngredients];
    }

    private function getFinalIngredients($extraIngredients, $removedIngredients, $pizza)
    {
        $originalIngredients = $pizza->ingredients->pluck('name')->toArray();
        $finalIngredientsNames = array_unique(array_merge(array_diff($originalIngredients, $removedIngredients), $extraIngredients));
        return Ingredient::whereIn('name', $finalIngredientsNames)->get();
    }

    private function calculateFinalPrice(Pizza $pizza, $finalIngredients)
    {
        $pizza->setRelation('ingredients', $finalIngredients);
        return $pizza->getPrice();
    }

    private function formatOrderResponse($pizzaName, $originalIngredients, $extraIngredients, $removedIngredients, $finalPrice)
    {
        return response()->json([
            'pizzaName' => $pizzaName,
            'originalIngredients' => $originalIngredients,
            'extraIngredients' => array_values($extraIngredients),
            'removedIngredients' => array_values($removedIngredients),
            'price' => $finalPrice,
        ]);
    }
}
