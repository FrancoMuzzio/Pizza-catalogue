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

    public function test_order_with_complete_pizza()
    {
        $pizza = Pizza::factory()->withIngredients()->create();
        $data = [
            'pizza' => $pizza->name,
            'ingredients' => $pizza->ingredients->pluck('name')->toArray()
        ];
        $response = $this->post(route('pizzas.order'), $data);
        $response->assertStatus(200);
        $response->assertJson([
            'pizzaName' => $pizza->name,
            'originalIngredients' => $pizza->ingredients->pluck('name')->toArray(),
            'extraIngredients' => [],
            'removedIngredients' => [],
            'price' => $pizza->getPrice(),
        ]);
    }


    public function test_order_with_modified_pizza()
    {
        $pizza = Pizza::factory()->withIngredients()->create();
        $pizzaOriginalIngredients = $pizza->ingredients;
        $randomIngredient = $pizzaOriginalIngredients->random();
        $extraIngredient = Ingredient::factory()->create();
        $pizza->ingredients()->detach($randomIngredient);
        $pizza->ingredients()->attach($extraIngredient);
        $data = [
            'pizza' => $pizza->name,
            'ingredients' => $pizza->ingredients->pluck('name')->toArray()
        ];
        $response = $this->post(route('pizzas.order'), $data);
        $response->assertStatus(200);
        $response->assertJson([
            'pizzaName' => $pizza->name,
            'originalIngredients' => $pizzaOriginalIngredients->pluck('name')->toArray(),
            'extraIngredients' => [$extraIngredient->name],
            'removedIngredients' => [$randomIngredient->name],
            'price' => $pizza->getPrice(),
        ]);
    }
}
