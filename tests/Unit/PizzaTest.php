<?php

namespace Tests\Unit;
use App\Models\Pizza;
use App\Models\Ingredient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PizzaTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        Ingredient::factory()->count(10)->create();
    }

    public function test_get_price_when_has_ingredients(): void
    {
        $pizza = Pizza::factory()->withIngredients()->create();
        $expectedPrice = round($pizza->ingredients->sum('price') * 1.5, 2);
        $this->assertEquals($expectedPrice, $pizza->getPrice());
    }

    public function test_get_price_when_has_not_ingredients(): void
    {
        $pizza = Pizza::factory()->create();

        $this->assertEquals(0, $pizza->getPrice());
    }

    public function test_find_by_name_returns_pizza()
    {
        $pizzaName = 'Pizza to find';
        Pizza::factory()->withIngredients()->create(['name' => $pizzaName]);
        $foundPizza = Pizza::findByName($pizzaName);
        $this->assertNotNull($foundPizza);
        $this->assertEquals($pizzaName, $foundPizza->name);
    }

    public function test_find_by_name_returns_null_if_pizza_does_not_exist()
    {
        $foundPizza = Pizza::findByName('Imaginary pizza');
        $this->assertNull($foundPizza);
    }
}
