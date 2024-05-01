<?php

namespace Database\Factories;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;
class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;
 
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->randomFloat(2, 0.5, 10.0),
        ];
    }
}
