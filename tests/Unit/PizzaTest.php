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
        $expectedPrice = $pizza->ingredients->sum('price') * 1.5;
        $this->assertEquals($expectedPrice, $pizza->getPrice());
    }

    public function test_get_price_when_has_not_ingredients(): void
    {
        $pizza = Pizza::factory()->create();

        $this->assertEquals(0, $pizza->getPrice());
    }
}
