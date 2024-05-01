<?php

namespace Tests\Feature;

use App\Models\Pizza;
use App\Models\Ingredient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Inertia;

class PizzaControllerTest  extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        Ingredient::factory()->count(10)->create();
    }

    public function test_index_method_with_pizzas()
    {
        Pizza::factory()->count(4)->withIngredients()->create();
        $ingredients = Ingredient::all();
        $response = $this->get(route('pizzas.index'));
        $response->assertStatus(200);
        $response->assertInertia(function ($page) use ($ingredients) {
            $page->component('Pizzas/Index');
            $page->has('pizzas', 4);
            $page->has('allIngredients', 10);
            $page->where('pizzas', Pizza::with('ingredients')->get());
            $page->where('allIngredients', $ingredients);
        });
    }

    public function test_index_method_without_pizzas()
    {
        $ingredients = Ingredient::all();
        $response = $this->get(route('pizzas.index'));
        $response->assertStatus(200);
        $response->assertInertia(function ($page) use ($ingredients) {
            $page->component('Pizzas/Index');
            $page->has('pizzas', 0);
            $page->has('allIngredients', 10);
            $page->where('pizzas', Pizza::with('ingredients')->get());
            $page->where('allIngredients', $ingredients);
        });
    }

    public function test_order_pizza_successfully()
    {
        $pizza = Pizza::factory()->withIngredients()->create();
        $originalIngredients = $pizza->ingredients->pluck('name')->toArray();
        $response = $this->post(route('pizzas.order'), [
            'pizzaName' => $pizza->name,
            'ingredients' => $originalIngredients,
        ]);
        $jsonResponse = json_decode($response->getContent(), true);
        $response->assertStatus(200)
            ->assertJson([
                'pizzaName' => $pizza->name,
                'price' => $pizza->getPrice()
            ]);
        $this->assertSame(array_diff($originalIngredients, $jsonResponse['originalIngredients']), array_diff($jsonResponse['originalIngredients'], $originalIngredients));
        $this->assertSame($jsonResponse['extraIngredients'], []);
        $this->assertSame($jsonResponse['removedIngredients'], []);
    }

    public function test_order_pizza_not_found()
    {
        $response = $this->post(route('pizzas.order'), [
            'pizzaName' => 'Imaginary pizza',
            'ingredients' => ['Imaginary ingredient 1', 'Imaginary ingredient 2'],
        ]);

        $response->assertStatus(404)
            ->assertJson(['error' => 'Pizza not found']);
    }

    public function test_order_pizza_with_extra_and_removed_ingredients()
    {
        $pizza = Pizza::factory()->withIngredients()->create();
        $originalIngredients = $pizza->ingredients->pluck('name')->toArray();
        $ingredientToRemove = $originalIngredients[array_rand($originalIngredients)];
        $remainingIngredients = array_diff($originalIngredients, [$ingredientToRemove]);
        $extraIngredient = Ingredient::factory()->create();
        $orderedIngredients = array_merge($remainingIngredients, [$extraIngredient->name]);
        $response = $this->post(route('pizzas.order'), [
            'pizzaName' => $pizza->name,
            'ingredients' => $orderedIngredients,
        ]);
        $finalPrice = $pizza->setRelation('ingredients', Ingredient::whereIn('name', $orderedIngredients)->get())->getPrice();
        $jsonResponse = json_decode($response->getContent(), true);
        $response->assertStatus(200)
            ->assertJson([
                'pizzaName' => $pizza->name,
                'price' => $finalPrice
            ]);
        $this->assertSame(array_diff($originalIngredients, $jsonResponse['originalIngredients']), array_diff($jsonResponse['originalIngredients'], $originalIngredients));
        $this->assertSame([$extraIngredient->name], $jsonResponse['extraIngredients']);
        $this->assertSame([$ingredientToRemove], $jsonResponse['removedIngredients']);
    }
}
